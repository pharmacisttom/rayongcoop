<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RateLimitMiddleware
{
    private string $key;
    private int $maxAttempts;
    private int $decaySeconds;

    public function __construct(string $key = 'global', int $maxAttempts = 60, int $decaySeconds = 60)
    {
        $this->key = $key;
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function handle(Request $request, Response $response): bool
    {
        $ip = $request->ip();
        $cacheKey = "rate_limit:{$this->key}:{$ip}";

        $now = time();
        $attempts = Session::get("{$cacheKey}:attempts", 0);
        $firstAttemptTime = Session::get("{$cacheKey}:first_attempt", $now);

        if ($now - $firstAttemptTime > $this->decaySeconds) {
            // Reset window
            $attempts = 1;
            $firstAttemptTime = $now;
        } else {
            $attempts++;
        }

        Session::set("{$cacheKey}:attempts", $attempts);
        Session::set("{$cacheKey}:first_attempt", $firstAttemptTime);

        if ($attempts > $this->maxAttempts) {
            $retryAfter = $this->decaySeconds - ($now - $firstAttemptTime);
            if ($request->isAjax()) {
                $response->json([
                    'success' => false,
                    'code' => 'RATE_LIMIT_EXCEEDED',
                    'message' => "คุณทำรายการเกินกำหนด กรุณารออีก {$retryAfter} วินาทีก่อนลองใหม่"
                ], 429);
                return false;
            }

            Session::flash('error', "คุณทำรายการเกินกำหนด กรุณารออีก {$retryAfter} วินาที");
            $response->redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
            return false;
        }

        return true;
    }
}
