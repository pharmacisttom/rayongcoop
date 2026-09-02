<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class CalculatorController extends Controller
{
    public function index(): void
    {
        $loanProducts = Database::query("SELECT * FROM loan_products WHERE is_calculator_enabled = 1 AND status = 'active' ORDER BY sort_order ASC");

        $this->render('public.calculator', [
            'title' => 'โปรแกรมคำนวณเงินกู้ออนไลน์ (Loan Calculator)',
            'loanProducts' => $loanProducts,
        ]);
    }
}
