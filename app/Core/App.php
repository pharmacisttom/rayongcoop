<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private Router $router;
    private Request $request;
    private Response $response;

    public function __construct()
    {
        // Set timezone
        date_default_timezone_set(config('app.timezone', 'Asia/Bangkok'));

        // Initialize session
        Session::start();

        // Initialize core objects
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        try {
            // Load application routes
            $router = $this->router;
            $routesFile = dirname(__DIR__, 2) . '/config/routes.php';
            if (file_exists($routesFile)) {
                require $routesFile;
            }

            // Dispatch request
            $this->router->dispatch($this->request, $this->response);
        } catch (\Throwable $e) {
            Logger::error('Unhandled Application Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (config('app.debug', false)) {
                $this->response->setStatusCode(500);
                echo "<h1>500 Server Error</h1>";
                echo "<p><strong>Message:</strong> " . e($e->getMessage()) . "</p>";
                echo "<p><strong>File:</strong> " . e($e->getFile()) . " on line " . $e->getLine() . "</p>";
                echo "<pre>" . e($e->getTraceAsString()) . "</pre>";
            } else {
                $this->response->setStatusCode(500);
                if ($this->request->isAjax()) {
                    $this->response->json([
                        'success' => false,
                        'message' => 'เกิดข้อผิดพลาดภายในระบบ กรุณาลองใหม่อีกครั้งในภายหลัง'
                    ], 500);
                } else {
                    $html = View::render('public.500', [
                        'title' => '500 - ระบบขัดข้องชั่วคราว',
                        'message' => 'เกิดข้อผิดพลาดภายในระบบ ขออภัยในความไม่สะดวก'
                    ], 'layouts.public');
                    $this->response->setContent($html)->send();
                }
            }
        }
    }
}
