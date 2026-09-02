<?php

declare(strict_types=1);

$dir = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$phpFiles = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        if (str_contains($path, 'vendor')) {
            continue;
        }
        $phpFiles[] = $path;
    }
}

echo "Fast-checking PHP Syntax for " . count($phpFiles) . " application files...\n";
$errors = 0;

foreach ($phpFiles as $file) {
    $code = file_get_contents($file);
    // Parse check
    try {
        token_get_all($code, TOKEN_PARSE);
    } catch (\ParseError $e) {
        echo "✗ Parse Error in: {$file} on line {$e->getLine()}: {$e->getMessage()}\n";
        $errors++;
    } catch (\Throwable $e) {
        echo "✗ Error in: {$file}: {$e->getMessage()}\n";
        $errors++;
    }
}

if ($errors === 0) {
    echo "✓ All " . count($phpFiles) . " PHP files passed token & syntax validation with 0 errors!\n";
} else {
    echo "✗ Found {$errors} errors.\n";
    exit(1);
}
