<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class EServiceController extends Controller
{
    public function index(): void
    {
        $eservices = Database::query("SELECT * FROM eservice_links WHERE status = 'active' ORDER BY sort_order ASC");

        $this->render('public.eservice', [
            'title' => 'ศูนย์บริการออนไลน์สำหรับสมาชิก (E-Service Gateway)',
            'eservices' => $eservices,
        ]);
    }
}
