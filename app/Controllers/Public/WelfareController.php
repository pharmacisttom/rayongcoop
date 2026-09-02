<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class WelfareController extends Controller
{
    public function index(): void
    {
        $welfares = Database::query("SELECT * FROM welfare WHERE status = 'active' ORDER BY sort_order ASC");

        $this->render('public.welfare.index', [
            'title' => 'สวัสดิการสมาชิกสหกรณ์',
            'welfares' => $welfares,
        ]);
    }
}
