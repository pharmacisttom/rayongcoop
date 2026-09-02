<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class LoanController extends Controller
{
    public function index(): void
    {
        $products = Database::query("SELECT * FROM loan_products WHERE status = 'active' ORDER BY sort_order ASC");
        $rates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'loan' AND status = 'active' ORDER BY sort_order ASC");
        $schedules = Database::query("SELECT * FROM loan_schedules WHERE status = 'active' ORDER BY year DESC, month DESC LIMIT 6");

        $this->render('public.loans.index', [
            'title' => 'ผลิตภัณฑ์เงินกู้และสินเชื่อ',
            'products' => $products,
            'rates' => $rates,
            'schedules' => $schedules,
        ]);
    }
}
