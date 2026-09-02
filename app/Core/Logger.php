<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    public static function log(string $type, string $message, array $context = []): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }

        $filename = match ($type) {
            'auth' => 'auth.log',
            'audit' => 'audit.log',
            'system' => 'system.log',
            'error' => 'error.log',
            default => 'app.log',
        };

        $filePath = "{$logDir}/{$filename}";
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $entry = sprintf("[%s] [%s] [IP: %s] %s%s%s", $timestamp, strtoupper($type), $ip, $message, $contextStr, PHP_EOL);

        @file_put_contents($filePath, $entry, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function auth(string $message, array $context = []): void
    {
        self::log('auth', $message, $context);
    }

    public static function system(string $message, array $context = []): void
    {
        self::log('system', $message, $context);
    }
}
