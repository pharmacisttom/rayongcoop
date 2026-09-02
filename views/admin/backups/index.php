<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-navy mb-1"><i class="bi bi-database-check me-2 text-primary"></i> ระบบสำรองฐานข้อมูล (Database Backup)</h3>
        <p class="text-muted small mb-0">สร้างไฟล์สำรองโครงสร้างและข้อมูลของระบบทั้งหมด (SQL Dump)</p>
    </div>
    <form action="<?= url('admin/backups/create') ?>" method="POST" id="createBackupForm">
        <?= csrf_field() ?>
        <button type="button" class="btn btn-primary btn-sm" onclick="showConfirm('ยืนยันการสำรองข้อมูล?', 'ระบบจะทำการส่งออกฐานข้อมูลทั้งหมดในรูปแบบ SQL', 'สร้างไฟล์ Backup', 'ยกเลิก', () => document.getElementById('createBackupForm').submit())">
            <i class="bi bi-database-add me-1"></i> สำรองข้อมูลทันที
        </button>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>ชื่อไฟล์สำรอง</th>
                        <th>ขนาดไฟล์</th>
                        <th>วันที่สร้างไฟล์</th>
                        <th class="text-end">ดาวน์โหลด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($backups)): ?>
                        <?php foreach ($backups as $i => $b): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <div class="fw-bold font-monospace text-navy"><?= e($b['filename']) ?></div>
                                </td>
                                <td class="font-monospace small"><?= number_format($b['size'] / 1024, 2) ?> KB</td>
                                <td class="small text-muted"><?= thai_date($b['created_at'], true) ?></td>
                                <td class="text-end">
                                    <a href="<?= url('admin/backups/download?file=' . urlencode($b['filename'])) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> ดาวน์โหลด SQL
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">ยังไม่มีไฟล์สำรองข้อมูลในระบบ</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
