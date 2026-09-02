<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\App;
use App\Core\Database;

echo "Testing RayongCoop Digital Portal core application routes...\n";

$routes = [
    '/',
    '/deposits',
    '/loans',
    '/calculator',
    '/welfare',
    '/documents',
    '/news',
    '/eservice',
    '/complaints',
    '/complaints/track',
    '/about',
    '/board',
    '/statistics',
    '/contact',
    '/faqs',
    '/privacy/policy',
    '/privacy/cookies',
    '/terms',
    '/admin/login'
];

$passed = 0;
foreach ($routes as $path) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/rayongcoop/public' . $path;
    $_SERVER['SCRIPT_NAME'] = '/rayongcoop/public/index.php';
    $_SERVER['SERVER_NAME'] = 'localhost';
    $_SERVER['SERVER_PORT'] = '80';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit/TestClient';

    ob_start();
    try {
        $app = new App();
        $app->run();
        $output = ob_get_clean();
        $code = http_response_code();
        if ($code === 200 || $code === false) {
            echo "✓ Route [GET] {$path} returned HTTP 200 (Length: " . strlen($output) . " bytes)\n";
            $passed++;
        } else {
            echo "✗ Route [GET] {$path} returned HTTP {$code}\n";
        }
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "✗ Route [GET] {$path} threw exception: {$e->getMessage()}\n in {$e->getFile()}:{$e->getLine()}\n";
    }
}

echo "\nResult: {$passed}/" . count($routes) . " routes tested successfully!\n";
