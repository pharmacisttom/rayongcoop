<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class DepositController extends Controller
{
    public function index(): void
    {
        $products = Database::query("SELECT * FROM deposit_products WHERE status = 'active' ORDER BY sort_order ASC");
        $rates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'deposit' AND status = 'active' ORDER BY sort_order ASC");

        $this->render('public.deposits.index', [
            'title' => 'ผลิตภัณฑ์เงินฝากและอัตราดอกเบี้ย',
            'products' => $products,
            'rates' => $rates,
        ]);
    }

    public function show(string $slug): void
    {
        $product = Database::first("SELECT * FROM deposit_products WHERE slug = ? AND status = 'active' LIMIT 1", [$slug]);
        if (!$product) {
            $this->redirect(url('deposits'));
            return;
        }

        $this->render('public.deposits.show', [
            'title' => $product['name'],
            'product' => $product,
        ]);
    }
}
