<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class DashboardController extends Controller
{
    public function index(): void
    {
        $counts = [
            'published_news' => (int) Database::value("SELECT COUNT(*) FROM news WHERE workflow_status = 'published' AND deleted_at IS NULL"),
            'draft_news' => (int) Database::value("SELECT COUNT(*) FROM news WHERE workflow_status IN ('draft', 'submitted', 'under_review') AND deleted_at IS NULL"),
            'active_popups' => (int) Database::value("SELECT COUNT(*) FROM popups WHERE status = 'active' AND deleted_at IS NULL"),
            'active_slides' => (int) Database::value("SELECT COUNT(*) FROM hero_slides WHERE status = 'active' AND deleted_at IS NULL"),
            'total_documents' => (int) Database::value("SELECT COUNT(*) FROM documents WHERE status = 'active' AND deleted_at IS NULL"),
            'total_downloads' => (int) Database::value("SELECT SUM(download_count) FROM documents WHERE deleted_at IS NULL") ?: 0,
            'pending_complaints' => (int) Database::value("SELECT COUNT(*) FROM complaints WHERE status IN ('received', 'under_review', 'assigned', 'in_progress')"),
            'total_users' => (int) Database::value("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL"),
        ];

        $recentAudits = Database::query("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 6");
        $recentComplaints = Database::query("SELECT * FROM complaints ORDER BY created_at DESC LIMIT 5");

        $this->render('admin.dashboard', [
            'title' => 'Dashboard ภาพรวมระบบ',
            'counts' => $counts,
            'recentAudits' => $recentAudits,
            'recentComplaints' => $recentComplaints,
        ], 'layouts.admin');
    }

    public function executive(): void
    {
        $statsHistory = Database::query("SELECT * FROM financial_statistics ORDER BY year ASC, month ASC");
        $latest = end($statsHistory) ?: null;

        $this->render('admin.executive', [
            'title' => 'Executive Financial Dashboard (สำหรับผู้บริหาร)',
            'statsHistory' => $statsHistory,
            'latest' => $latest,
        ], 'layouts.admin');
    }
}
