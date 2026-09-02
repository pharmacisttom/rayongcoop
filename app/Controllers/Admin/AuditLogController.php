<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class AuditLogController extends Controller
{
    public function index(): void
    {
        $logs = Database::query("SELECT a.*, u.name as user_name, u.email as user_email 
                                FROM audit_logs a 
                                LEFT JOIN users u ON a.user_id = u.id 
                                ORDER BY a.created_at DESC 
                                LIMIT 200");

        $this->render('admin.audit_logs.index', [
            'title' => 'บันทึกประวัติการใช้งานระบบ (Immutable Audit Trail)',
            'logs' => $logs,
        ], 'layouts.admin');
    }
}
