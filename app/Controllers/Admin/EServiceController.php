<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class EServiceController extends Controller
{
    public function index(): void
    {
        $eservices = Database::query("SELECT * FROM eservice_links WHERE deleted_at IS NULL ORDER BY sort_order ASC");

        $this->render('admin.eservices.index', [
            'title' => 'จัดการ E-Service Gateway และลิงก์บริการเดิม',
            'eservices' => $eservices,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'url' => 'required',
        ]);

        $sql = "INSERT INTO eservice_links (name, description, url, icon, category, is_internal, open_new_tab, confirm_before_redirect, is_maintenance, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['name'],
            $this->request->input('description'),
            $data['url'],
            $this->request->input('icon', 'bi-globe'),
            $this->request->input('category', 'general'),
            (int) ($this->request->input('is_internal') ? 1 : 0),
            (int) ($this->request->input('open_new_tab') ? 1 : 0),
            (int) ($this->request->input('confirm_before_redirect') ? 1 : 0),
            (int) ($this->request->input('is_maintenance') ? 1 : 0),
            $this->request->input('status', 'active')
        ]);

        AuditService::log('eservices', 'create', (string)$id, null, ['name' => $data['name']]);
        Session::flash('success', 'เพิ่มลิงก์ E-Service เรียบร้อยแล้ว');
        $this->redirect(url('admin/eservices'));
    }

    public function destroy(string $id): void
    {
        $es = Database::first("SELECT * FROM eservice_links WHERE id = ? LIMIT 1", [(int) $id]);
        if ($es) {
            Database::execute("UPDATE eservice_links SET deleted_at = NOW() WHERE id = ?", [(int) $id]);
            AuditService::log('eservices', 'delete', (string)$id, $es);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบลิงก์บริการเรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบลิงก์บริการเรียบร้อยแล้ว');
            $this->redirect(url('admin/eservices'));
        }
    }
}
