<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $request->csrfToken();

            if (!Csrf::validate($token)) {
                if ($request->isAjax()) {
                    $response->json([
                        'success' => false,
                        'code' => 'CSRF_INVALID',
                        'message' => 'CSRF Token ไม่ถูกต้องหรือหมดอายุ กรุณารีเฟรชหน้าเว็บและลองใหม่'
                    ], 419);
                    return false;
                }

                Session::flash('error', 'เซสชันการส่งข้อมูลหมดอายุ กรุณาลองใหม่อีกครั้ง');
                $response->redirect($_SERVER['HTTP_REFERER'] ?? url('/'));
                return false;
            }
        }

        return true;
    }
}
