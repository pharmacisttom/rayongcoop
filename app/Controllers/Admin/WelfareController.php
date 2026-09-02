<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class WelfareController extends Controller
{
    public function index(): void
    {
        $welfares = Database::query("SELECT * FROM welfare WHERE deleted_at IS NULL ORDER BY sort_order ASC");

        $this->render('admin.welfare.index', [
            'title' => 'จัดการสวัสดิการสมาชิก',
            'welfares' => $welfares,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'category' => 'required',
            'title' => 'required',
            'benefit_amount' => 'required',
        ]);

        $sql = "INSERT INTO welfare (category, title, slug, short_description, full_description, benefit_amount, eligibility, contact_info, status, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['category'],
            $data['title'],
            str_slug($data['title']) . '-' . time(),
            $this->request->input('short_description'),
            $this->request->input('full_description'),
            $data['benefit_amount'],
            $this->request->input('eligibility'),
            $this->request->input('contact_info'),
            $this->request->input('status', 'active'),
            Auth::id()
        ]);

        AuditService::log('welfare', 'create', (string)$id, null, ['title' => $data['title']]);
        Session::flash('success', 'เพิ่มสวัสดิการเรียบร้อยแล้ว');
        $this->redirect(url('admin/welfare'));
    }

    public function destroy(string $id): void
    {
        $w = Database::first("SELECT * FROM welfare WHERE id = ? LIMIT 1", [(int) $id]);
        if ($w) {
            Database::execute("UPDATE welfare SET deleted_at = NOW(), updated_by = ? WHERE id = ?", [Auth::id(), (int) $id]);
            AuditService::log('welfare', 'delete', (string)$id, $w);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบสวัสดิการเรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบสวัสดิการเรียบร้อยแล้ว');
            $this->redirect(url('admin/welfare'));
        }
    }
}
