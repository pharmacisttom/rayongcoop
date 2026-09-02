<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class ComplaintController extends Controller
{
    public function index(): void
    {
        $complaints = Database::query("SELECT c.*, u.name as officer_name 
                                       FROM complaints c 
                                       LEFT JOIN users u ON c.assigned_officer_id = u.id 
                                       ORDER BY c.created_at DESC");

        $this->render('admin.complaints.index', [
            'title' => 'ศูนย์รับเรื่องร้องเรียนและข้อเสนอแนะ',
            'complaints' => $complaints,
        ], 'layouts.admin');
    }

    public function show(string $id): void
    {
        $complaint = Database::first("SELECT * FROM complaints WHERE id = ? LIMIT 1", [(int) $id]);
        if (!$complaint) {
            $this->redirect(url('admin/complaints'));
            return;
        }

        $logs = Database::query("SELECT l.*, u.name as officer_name 
                                FROM complaint_logs l 
                                LEFT JOIN users u ON l.officer_id = u.id 
                                WHERE l.complaint_id = ? 
                                ORDER BY l.created_at ASC", [(int) $id]);

        $officers = Database::query("SELECT * FROM users WHERE status = 'active' AND deleted_at IS NULL");

        $this->render('admin.complaints.show', [
            'title' => 'รายละเอียดเรื่องร้องเรียน: ' . $complaint['ticket_no'],
            'complaint' => $complaint,
            'logs' => $logs,
            'officers' => $officers,
        ], 'layouts.admin');
    }

    public function updateStatus(string $id): void
    {
        $complaint = Database::first("SELECT * FROM complaints WHERE id = ? LIMIT 1", [(int) $id]);
        if (!$complaint) {
            $this->redirect(url('admin/complaints'));
            return;
        }

        $status = $this->request->input('status');
        $priority = $this->request->input('priority');
        $officerId = $this->request->input('assigned_officer_id');
        $responseMsg = $this->request->input('response_message');
        $note = $this->request->input('action_note');

        $sql = "UPDATE complaints SET status = ?, priority = ?, assigned_officer_id = ?, response_message = ?, updated_at = NOW()";
        $params = [$status, $priority, $officerId ?: null, $responseMsg];

        if ($status === 'answered') {
            $sql .= ", answered_at = NOW()";
        } elseif ($status === 'closed') {
            $sql .= ", closed_at = NOW()";
        }

        $sql .= " WHERE id = ?";
        $params[] = (int) $id;

        Database::execute($sql, $params);

        // Add to complaint logs
        $userId = Auth::id();
        Database::execute("INSERT INTO complaint_logs (complaint_id, action, note, officer_id, created_at) VALUES (?, ?, ?, ?, NOW())", [
            (int) $id,
            "ปรับปรุงสถานะเป็น: {$status}",
            $note ?: "ดำเนินการโดยเจ้าหน้าที่",
            $userId
        ]);

        AuditService::log('complaints', 'update_status', (string)$id, $complaint, ['status' => $status, 'priority' => $priority]);
        Session::flash('success', 'บันทึกการดำเนินการเรื่องร้องเรียนเรียบร้อยแล้ว');
        $this->redirect(url('admin/complaints/' . $id));
    }
}
