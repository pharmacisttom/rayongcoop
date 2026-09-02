<?php
use App\Core\Database;
$activeAnnouncement = Database::first("SELECT * FROM announcements WHERE is_active = 1 AND (start_at IS NULL OR start_at <= NOW()) AND (end_at IS NULL OR end_at >= NOW()) ORDER BY FIELD(priority, 'urgent', 'important', 'general'), created_at DESC LIMIT 1");
?>
<?php if ($activeAnnouncement): ?>
<div class="announcement-bar">
    <div class="container-xl d-flex align-items-center justify-content-between flex-wrap">
        <div class="d-flex align-items-center me-3 py-1">
            <span class="badge rounded-pill me-2 px-2 py-1">
                <i class="bi bi-megaphone-fill me-1"></i> <?= e(strtoupper($activeAnnouncement['priority'])) ?>
            </span>
            <span class="text-truncate fw-medium" style="max-width: 800px;">
                <b><?= e($activeAnnouncement['title']) ?>:</b> <?= e($activeAnnouncement['message']) ?>
            </span>
        </div>
        <?php if (!empty($activeAnnouncement['link_url'])): ?>
            <div class="py-1">
                <a href="<?= url($activeAnnouncement['link_url']) ?>" class="btn btn-sm btn-light py-0 px-2 fw-bold text-navy" style="font-size: 0.78rem;">
                    <?= e($activeAnnouncement['link_text'] ?? 'อ่านเพิ่มเติม') ?> <i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
