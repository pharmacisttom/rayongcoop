<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class ContactController extends Controller
{
    public function index(): void
    {
        $this->render('public.contact', [
            'title' => 'ติดต่อสหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด',
        ]);
    }

    public function submit(): void
    {
        $data = $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($this->request->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'ส่งข้อความติดต่อเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับโดยเร็วที่สุด',
            ]);
        } else {
            $this->render('public.contact', ['title' => 'ติดต่อเรา', 'flashSuccess' => 'ส่งข้อความเรียบร้อยแล้ว']);
        }
    }

    public function faqs(): void
    {
        $faqs = Database::query("SELECT * FROM faqs WHERE status = 'active' ORDER BY sort_order ASC");

        $this->render('public.faqs', [
            'title' => 'คำถามที่พบบ่อย (Frequently Asked Questions)',
            'faqs' => $faqs,
        ]);
    }
}
