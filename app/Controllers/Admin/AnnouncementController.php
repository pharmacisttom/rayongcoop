<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class AnnouncementController extends Controller
{
    public function index(): void
    {
        $announcements = Database::query("SELECT * FROM announcements WHERE deleted_at IS NULL ORDER BY created_at DESC");

        $this->render('admin.announcements.index', [
            'title' => 'จัดการแถบประกาศสำคัญ (Announcement Bar)',
            'announcements' => $announcements,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'title' => 'required',
            'message' => 'required',
        ]);

        $sql = "INSERT INTO announcements (title, message, link_url, link_text, priority, display_type, start_at, is_active, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['title'],
            $data['message'],
            $this->request->input('link_url'),
            $this->request->input('link_text', 'คลิกอ่านเพิ่มเติม'),
            $this->request->input('priority', 'general'),
            $this->request->input('display_type', 'top_bar'),
            (int) ($this->request->input('is_active') ? 1 : 0),
            Auth::id()
        ]);

        AuditService::log('announcements', 'create', (string)$id, null, ['title' => $data['title']]);
        Session::flash('success', 'สร้างประกาศเรียบร้อยแล้ว');
        $this->redirect(url('admin/announcements'));
    }

    public function destroy(string $id): void
    {
        $a = Database::first("SELECT * FROM announcements WHERE id = ? LIMIT 1", [(int) $id]);
        if ($a) {
            Database::execute("UPDATE announcements SET deleted_at = NOW() WHERE id = ?", [(int) $id]);
            AuditService::log('announcements', 'delete', (string)$id, $a);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบประกาศเรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบประกาศเรียบร้อยแล้ว');
            $this->redirect(url('admin/announcements'));
        }
    }
}
