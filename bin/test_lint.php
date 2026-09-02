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

echo "Testing PHP Syntax across " . count($phpFiles) . " files...\n";
$errors = 0;

foreach ($phpFiles as $file) {
    $cmd = sprintf('php -l "%s"', $file);
    exec($cmd, $output, $returnCode);
    if ($returnCode !== 0) {
        echo "✗ Syntax Error in: {$file}\n";
        echo implode("\n", $output) . "\n";
        $errors++;
    }
    $output = [];
}

if ($errors === 0) {
    echo "✓ All " . count($phpFiles) . " PHP files passed syntax validation successfully with 0 errors!\n";
} else {
    echo "✗ Found {$errors} syntax errors.\n";
    exit(1);
}
