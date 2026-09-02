<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class LoanProductController extends Controller
{
    public function index(): void
    {
        $products = Database::query("SELECT * FROM loan_products WHERE deleted_at IS NULL ORDER BY sort_order ASC");

        $this->render('admin.loans.index', [
            'title' => 'จัดการผลิตภัณฑ์เงินกู้และสินเชื่อ',
            'products' => $products,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'category' => 'required',
            'interest_rate' => 'required|numeric',
        ]);

        $sql = "INSERT INTO loan_products (category, name, slug, short_description, full_description, interest_rate, max_loan_limit, max_term_months, calculation_type, eligibility, guarantor_requirement, is_featured, is_calculator_enabled, status, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['category'],
            $data['name'],
            str_slug($data['name']) . '-' . time(),
            $this->request->input('short_description'),
            $this->request->input('full_description'),
            (float) $data['interest_rate'],
            (float) ($this->request->input('max_loan_limit') ?: 0),
            (int) ($this->request->input('max_term_months') ?: 120),
            $this->request->input('calculation_type', 'effective'),
            $this->request->input('eligibility'),
            $this->request->input('guarantor_requirement'),
            (int) ($this->request->input('is_featured') ? 1 : 0),
            (int) ($this->request->input('is_calculator_enabled') ? 1 : 0),
            $this->request->input('status', 'active'),
            Auth::id()
        ]);

        AuditService::log('loan_products', 'create', (string)$id, null, ['name' => $data['name']]);
        Session::flash('success', 'เพิ่มผลิตภัณฑ์เงินกู้เรียบร้อยแล้ว');
        $this->redirect(url('admin/loans'));
    }

    public function destroy(string $id): void
    {
        $p = Database::first("SELECT * FROM loan_products WHERE id = ? LIMIT 1", [(int) $id]);
        if ($p) {
            Database::execute("UPDATE loan_products SET deleted_at = NOW(), updated_by = ? WHERE id = ?", [Auth::id(), (int) $id]);
            AuditService::log('loan_products', 'delete', (string)$id, $p);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบผลิตภัณฑ์เงินกู้เรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบผลิตภัณฑ์เงินกู้เรียบร้อยแล้ว');
            $this->redirect(url('admin/loans'));
        }
    }
}
