<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;
use App\Services\MediaService;

class DocumentController extends Controller
{
    public function index(): void
    {
        $categories = Database::query("SELECT * FROM document_categories WHERE status = 'active' ORDER BY sort_order ASC");
        $documents = Database::query("SELECT d.*, c.name as category_name 
                                     FROM documents d 
                                     JOIN document_categories c ON d.category_id = c.id 
                                     WHERE d.deleted_at IS NULL 
                                     ORDER BY d.created_at DESC");

        $this->render('admin.documents.index', [
            'title' => 'จัดการศูนย์เอกสารและระเบียบ',
            'categories' => $categories,
            'documents' => $documents,
        ], 'layouts.admin');
    }

    public function store(): void
    {
        $data = $this->validate([
            'category_id' => 'required|numeric',
            'title' => 'required',
        ]);

        $filePath = 'sample_document.pdf';
        $fileSize = 1024 * 50;

        if ($this->request->hasFile('file')) {
            try {
                $uploaded = MediaService::upload($this->request->file('file'), 'documents');
                $filePath = $uploaded['path'];
                $fileSize = $uploaded['size'];
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect(url('admin/documents'));
                return;
            }
        }

        $sql = "INSERT INTO documents (category_id, title, slug, document_number, file_path, file_size, year, tag, status, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $id = Database::insert($sql, [
            $data['category_id'],
            $data['title'],
            str_slug($data['title']) . '-' . time(),
            $this->request->input('document_number'),
            $filePath,
            $fileSize,
            (int) ($this->request->input('year') ?: date('Y') + 543),
            $this->request->input('tag'),
            $this->request->input('status', 'active'),
            Auth::id()
        ]);

        AuditService::log('documents', 'create', (string)$id, null, ['title' => $data['title']]);
        Session::flash('success', 'เพิ่มเอกสารเรียบร้อยแล้ว');
        $this->redirect(url('admin/documents'));
    }

    public function destroy(string $id): void
    {
        $doc = Database::first("SELECT * FROM documents WHERE id = ? LIMIT 1", [(int) $id]);
        if ($doc) {
            Database::execute("UPDATE documents SET deleted_at = NOW(), updated_by = ? WHERE id = ?", [Auth::id(), (int) $id]);
            AuditService::log('documents', 'delete', (string)$id, $doc);
        }

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'ลบเอกสารเรียบร้อยแล้ว']);
        } else {
            Session::flash('success', 'ลบเอกสารเรียบร้อยแล้ว');
            $this->redirect(url('admin/documents'));
        }
    }
}
