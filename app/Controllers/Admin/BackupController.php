<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class BackupController extends Controller
{
    public function index(): void
    {
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        $backups = [];

        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/*.sql');
            rsort($files);
            foreach ($files as $f) {
                $backups[] = [
                    'filename' => basename($f),
                    'size' => filesize($f),
                    'created_at' => date('Y-m-d H:i:s', filemtime($f)),
                ];
            }
        }

        $this->render('admin.backups.index', [
            'title' => 'ระบบสำรองและกู้คืนฐานข้อมูล (Database Backup)',
            'backups' => $backups,
        ], 'layouts.admin');
    }

    public function createBackup(): void
    {
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0775, true);
        }

        $filename = 'backup_' . date('Y_m_d_His') . '.sql';
        $target = "{$backupDir}/{$filename}";

        $pdo = Database::connect();
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        $dump = "-- RayongCoop Database Backup\n-- Generated: " . date('Y-m-d H:i:s') . "\n\nSET FOREIGN_KEY_CHECKS=0;\n";
        foreach ($tables as $table) {
            $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $dump .= "\nDROP TABLE IF EXISTS `{$table}`;\n" . $createTable['Create Table'] . ";\n\n";
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $fields = array_map(function($val) use ($pdo) {
                    return $val === null ? 'NULL' : $pdo->quote((string)$val);
                }, array_values($row));
                $dump .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $fields) . ");\n";
            }
        }
        $dump .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
        file_put_contents($target, $dump);

        AuditService::log('backups', 'create', $filename, null, ['size' => filesize($target)]);
        Session::flash('success', "สำรองฐานข้อมูลเรียบร้อยแล้ว: {$filename}");
        $this->redirect(url('admin/backups'));
    }

    public function download(string $filename): void
    {
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        $filePath = realpath($backupDir . '/' . basename($filename));

        if ($filePath && file_exists($filePath) && str_starts_with($filePath, realpath($backupDir))) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        }

        Session::flash('error', 'ไม่พบไฟล์สำรองข้อมูล');
        $this->redirect(url('admin/backups'));
    }
}
