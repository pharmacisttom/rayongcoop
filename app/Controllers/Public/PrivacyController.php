<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;

class PrivacyController extends Controller
{
    public function policy(): void
    {
        $this->render('public.privacy.policy', [
            'title' => 'นโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA Privacy Policy)',
        ]);
    }

    public function cookies(): void
    {
        $this->render('public.privacy.cookies', [
            'title' => 'นโยบายการใช้คุกกี้ (Cookie Policy)',
        ]);
    }

    public function terms(): void
    {
        $this->render('public.privacy.terms', [
            'title' => 'ข้อกำหนดและเงื่อนไขการใช้งานเว็บไซต์ (Terms of Use)',
        ]);
    }
}
