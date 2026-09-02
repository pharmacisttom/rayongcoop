<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class HeroSlideController extends Controller
{
    public function index(): void
    {
        $slides = Database::query("SELECT * FROM hero_slides WHERE deleted_at IS NULL ORDER BY priority DESC, sort_order ASC");

        $this->render('admin.hero_slides.index', [
            'title' => 'จัดการ Hero Slideshow แบนเนอร์หน้าแรก',
            'slides' => $slides,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'title' => 'required',
        ]);

        $sql = "INSERT INTO hero_slides (title, subtitle, description, desktop_image, button_text, button_url, button_target, text_alignment, overlay_opacity, priority, status, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['title'],
            $this->request->input('subtitle'),
            $this->request->input('description'),
            $this->request->input('desktop_image', 'hero_slide_1.jpg'),
            $this->request->input('button_text'),
            $this->request->input('button_url'),
            $this->request->input('button_target', '_self'),
            $this->request->input('text_alignment', 'left'),
            (float) $this->request->input('overlay_opacity', 0.40),
            (int) $this->request->input('priority', 10),
            $this->request->input('status', 'active'),
            Auth::id()
        ]);

        AuditService::log('hero_slides', 'create', (string)$id, null, ['title' => $data['title']]);
        Session::flash('success', 'เพิ่มสไลด์เรียบร้อยแล้ว');
        $this->redirect(url('admin/hero-slides'));
    }

    public function destroy(string $id): void
    {
        $slide = Database::first("SELECT * FROM hero_slides WHERE id = ? LIMIT 1", [(int) $id]);
        if ($slide) {
            Database::execute("UPDATE hero_slides SET deleted_at = NOW(), updated_by = ? WHERE id = ?", [Auth::id(), (int) $id]);
            AuditService::log('hero_slides', 'delete', (string)$id, $slide);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบสไลด์เรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบสไลด์เรียบร้อยแล้ว');
            $this->redirect(url('admin/hero-slides'));
        }
    }
}
