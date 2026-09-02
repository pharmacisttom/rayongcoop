<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class DepositProductController extends Controller
{
    public function index(): void
    {
        $products = Database::query("SELECT * FROM deposit_products WHERE deleted_at IS NULL ORDER BY sort_order ASC");

        $this->render('admin.deposits.index', [
            'title' => 'จัดการผลิตภัณฑ์เงินฝาก',
            'products' => $products,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'interest_rate' => 'required|numeric',
        ]);

        $sql = "INSERT INTO deposit_products (name, slug, short_description, full_description, interest_rate, min_deposit, max_deposit, withdrawal_condition, eligibility, required_documents, is_featured, status, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['name'],
            str_slug($data['name']) . '-' . time(),
            $this->request->input('short_description'),
            $this->request->input('full_description'),
            (float) $data['interest_rate'],
            (float) ($this->request->input('min_deposit') ?: 0),
            (float) ($this->request->input('max_deposit') ?: 0),
            $this->request->input('withdrawal_condition'),
            $this->request->input('eligibility'),
            $this->request->input('required_documents'),
            (int) ($this->request->input('is_featured') ? 1 : 0),
            $this->request->input('status', 'active'),
            Auth::id()
        ]);

        AuditService::log('deposit_products', 'create', (string)$id, null, ['name' => $data['name']]);
        Session::flash('success', 'เพิ่มผลิตภัณฑ์เงินฝากเรียบร้อยแล้ว');
        $this->redirect(url('admin/deposits'));
    }

    public function destroy(string $id): void
    {
        $p = Database::first("SELECT * FROM deposit_products WHERE id = ? LIMIT 1", [(int) $id]);
        if ($p) {
            Database::execute("UPDATE deposit_products SET deleted_at = NOW(), updated_by = ? WHERE id = ?", [Auth::id(), (int) $id]);
            AuditService::log('deposit_products', 'delete', (string)$id, $p);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบผลิตภัณฑ์เรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบผลิตภัณฑ์เรียบร้อยแล้ว');
            $this->redirect(url('admin/deposits'));
        }
    }
}
