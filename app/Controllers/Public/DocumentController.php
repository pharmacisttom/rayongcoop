<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class DocumentController extends Controller
{
    public function index(): void
    {
        $categorySlug = $this->request->query('cat');
        $keyword = $this->request->query('q');
        $year = $this->request->query('year');

        $categories = Database::query("SELECT * FROM document_categories WHERE status = 'active' ORDER BY sort_order ASC");

        $sql = "SELECT d.*, c.name as category_name, c.slug as category_slug 
                FROM documents d 
                JOIN document_categories c ON d.category_id = c.id 
                WHERE d.status = 'active'";
        $params = [];

        if (!empty($categorySlug)) {
            $sql .= " AND c.slug = ?";
            $params[] = $categorySlug;
        }

        if (!empty($keyword)) {
            $sql .= " AND (d.title LIKE ? OR d.document_number LIKE ? OR d.tag LIKE ?)";
            $params[] = "%{$keyword}%";
            $params[] = "%{$keyword}%";
            $params[] = "%{$keyword}%";
        }

        if (!empty($year)) {
            $sql .= " AND d.year = ?";
            $params[] = (int) $year;
        }

        $sql .= " ORDER BY d.sort_order ASC, d.created_at DESC";
        $documents = Database::query($sql, $params);

        $this->render('public.documents.index', [
            'title' => 'ศูนย์ดาวน์โหลดเอกสารและระเบียบข้อบังคับ',
            'categories' => $categories,
            'documents' => $documents,
            'selectedCategory' => $categorySlug,
            'keyword' => $keyword,
            'year' => $year,
        ]);
    }

    public function download(string $id): void
    {
        $doc = Database::first("SELECT * FROM documents WHERE id = ? AND status = 'active' LIMIT 1", [(int) $id]);
        if (!$doc) {
            $this->redirect(url('documents'));
            return;
        }

        // Increment download counter
        Database::execute("UPDATE documents SET download_count = download_count + 1 WHERE id = ?", [$doc['id']]);

        // File download / preview
        $filePath = dirname(__DIR__, 3) . '/storage/uploads/' . $doc['file_path'];
        if (file_exists($filePath)) {
            header('Content-Type: ' . ($doc['file_type'] ?: 'application/pdf'));
            header('Content-Disposition: inline; filename="' . basename($doc['file_path']) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            $this->redirect(url('documents'));
        }
    }
}
