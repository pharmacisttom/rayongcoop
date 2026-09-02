<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function json(array $data, int $status = 200): void
    {
        $this->setStatusCode($status);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->sendHeaders();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public function redirect(string $url, int $status = 302): void
    {
        $this->setStatusCode($status);
        $this->header('Location', $url);
        $this->sendHeaders();
        exit;
    }

    public function sendHeaders(): void
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);

            // Default Security Headers
            $defaultSecurityHeaders = [
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
                'X-XSS-Protection' => '1; mode=block',
                'Referrer-Policy' => 'strict-origin-when-cross-origin',
            ];

            foreach ($defaultSecurityHeaders as $k => $v) {
                if (!isset($this->headers[$k])) {
                    header("{$k}: {$v}");
                }
            }

            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
    }

    public function send(): void
    {
        $this->sendHeaders();
        echo $this->content;
        exit;
    }
}
