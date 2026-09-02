<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class PrivacyCookieController extends Controller
{
    public function index(): void
    {
        $categories = Database::query("SELECT * FROM cookie_categories ORDER BY sort_order ASC");
        $consentsCount = (int) Database::value("SELECT COUNT(*) FROM cookie_consents");
        $analyticsConsentCount = (int) Database::value("SELECT COUNT(*) FROM cookie_consents WHERE analytics = 1");
        $marketingConsentCount = (int) Database::value("SELECT COUNT(*) FROM cookie_consents WHERE marketing = 1");

        $recentConsents = Database::query("SELECT * FROM cookie_consents ORDER BY consented_at DESC LIMIT 15");

        $this->render('admin.privacy_cookies.index', [
            'title' => 'จัดการความเป็นส่วนตัวและคุกกี้ (PDPA & Cookie CMP)',
            'categories' => $categories,
            'consentsCount' => $consentsCount,
            'analyticsConsentCount' => $analyticsConsentCount,
            'marketingConsentCount' => $marketingConsentCount,
            'recentConsents' => $recentConsents,
        ], 'layouts.admin');
    }
}
