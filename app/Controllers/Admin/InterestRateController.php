<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class InterestRateController extends Controller
{
    public function index(): void
    {
        $depositRates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'deposit' AND deleted_at IS NULL ORDER BY sort_order ASC");
        $loanRates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'loan' AND deleted_at IS NULL ORDER BY sort_order ASC");

        $history = Database::query("SELECT h.*, u1.name as changer_name, u2.name as approver_name 
                                   FROM interest_rate_history h 
                                   LEFT JOIN users u1 ON h.changed_by = u1.id 
                                   LEFT JOIN users u2 ON h.approved_by = u2.id 
                                   ORDER BY h.created_at DESC LIMIT 20");

        $this->render('admin.interest_rates.index', [
            'title' => 'จัดการอัตราดอกเบี้ยและประวัติการเปลี่ยนแปลง',
            'depositRates' => $depositRates,
            'loanRates' => $loanRates,
            'history' => $history,
        ], 'layouts.admin');
    }

    public function updateRate(string $id): void
    {
        $rate = Database::first("SELECT * FROM interest_rates WHERE id = ? AND deleted_at IS NULL LIMIT 1", [(int) $id]);
        if (!$rate) {
            $this->redirect(url('admin/interest-rates'));
            return;
        }

        $data = $this->validate([
            'new_rate' => 'required|numeric',
            'effective_date' => 'required',
            'note' => 'required',
        ]);

        $oldRate = (float) $rate['rate'];
        $newRate = (float) $data['new_rate'];
        $userId = Auth::id();

        // 1. Update active rate
        Database::execute("UPDATE interest_rates SET rate = ?, effective_date = ?, updated_by = ?, updated_at = NOW() WHERE id = ?", [
            $newRate,
            $data['effective_date'],
            $userId,
            (int) $id
        ]);

        // 2. Insert immutable history entry
        Database::execute("INSERT INTO interest_rate_history (interest_rate_id, product_name, old_rate, new_rate, effective_date, changed_by, approved_by, note, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())", [
            (int) $id,
            $rate['product_name'],
            $oldRate,
            $newRate,
            $data['effective_date'],
            $userId,
            $userId,
            $data['note']
        ]);

        AuditService::log('interest_rates', 'rate_change', (string)$id, ['rate' => $oldRate], ['rate' => $newRate, 'effective_date' => $data['effective_date']]);
        Session::flash('success', "ปรับปรุงอัตราดอกเบี้ย {$rate['product_name']} เป็น {$newRate}% เรียบร้อยแล้ว");
        $this->redirect(url('admin/interest-rates'));
    }
}
