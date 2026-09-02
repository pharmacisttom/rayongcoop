<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-navy mb-1">ยินดีต้อนรับสู่ RayongCoop CMS</h3>
        <p class="text-muted small mb-0">ระบบบริหารจัดการดิจิทัล สหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('admin/executive') ?>" class="btn btn-navy btn-sm">
            <i class="bi bi-pie-chart-fill me-1 text-warning"></i> Executive Dashboard
        </a>
        <a href="<?= url('admin/news/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> เพิ่มข่าวประชาสัมพันธ์
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div>
                <small class="text-muted fw-bold text-uppercase">ข่าวที่เผยแพร่แล้ว</small>
                <div class="admin-stat-number"><?= number_format($counts['published_news']) ?></div>
                <small class="text-info"><i class="bi bi-pencil-square"></i> ร่าง/รออนุมัติ: <?= $counts['draft_news'] ?></small>
            </div>
            <div class="admin-stat-icon bg-primary text-white">
                <i class="bi bi-newspaper"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div>
                <small class="text-muted fw-bold text-uppercase">แคมเปญ Popup เปิดใช้งาน</small>
                <div class="admin-stat-number text-success"><?= number_format($counts['active_popups']) ?></div>
                <small class="text-muted">สไลด์แบนเนอร์: <?= $counts['active_slides'] ?></small>
            </div>
            <div class="admin-stat-icon bg-success text-white">
                <i class="bi bi-window-stack"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div>
                <small class="text-muted fw-bold text-uppercase">ดาวน์โหลดเอกสารรวม</small>
                <div class="admin-stat-number text-primary"><?= number_format($counts['total_downloads']) ?></div>
                <small class="text-muted">จำนวนเอกสารในระบบ: <?= $counts['total_documents'] ?></small>
            </div>
            <div class="admin-stat-icon bg-info text-white">
                <i class="bi bi-file-earmark-arrow-down"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div>
                <small class="text-muted fw-bold text-uppercase">เรื่องร้องเรียนรอดำเนินการ</small>
                <div class="admin-stat-number text-danger"><?= number_format($counts['pending_complaints']) ?></div>
                <small class="text-muted">ต้องตรวจสอบ/ตอบกลับ</small>
            </div>
            <div class="admin-stat-icon bg-danger text-white">
                <i class="bi bi-chat-left-dots"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Recent Complaints & Audit Logs -->
<div class="row g-4">
    <!-- Complaints List -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h5 class="admin-card-title"><i class="bi bi-chat-dots me-2 text-primary"></i> เรื่องร้องเรียนล่าสุด</h5>
                <a href="<?= url('admin/complaints') ?>" class="btn btn-sm btn-link p-0 text-primary">ดูทั้งหมด</a>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>เลขที่เรื่อง</th>
                                <th>หัวข้อ</th>
                                <th>สถานะ</th>
                                <th>วันที่</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentComplaints)): ?>
                                <?php foreach ($recentComplaints as $c): ?>
                                    <tr>
                                        <td class="font-monospace fw-bold small"><?= e($c['ticket_no']) ?></td>
                                        <td class="small text-truncate" style="max-width: 180px;"><?= e($c['subject']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary small"><?= e($c['status']) ?></span>
                                        </td>
                                        <td class="small text-muted"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">ไม่มีเรื่องร้องเรียนใหม่</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h5 class="admin-card-title"><i class="bi bi-journal-check me-2 text-primary"></i> บันทึกกิจกรรมล่าสุด (Audit Trail)</h5>
                <a href="<?= url('admin/audit-logs') ?>" class="btn btn-sm btn-link p-0 text-primary">ดูทั้งหมด</a>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ผู้ใช้งาน</th>
                                <th>โมดูล / การกระทำ</th>
                                <th>IP Address</th>
                                <th>เวลา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAudits as $a): ?>
                                <tr>
                                    <td class="small fw-bold"><?= e($a['user_name'] ?? 'System') ?></td>
                                    <td class="small">
                                        <span class="badge bg-light text-navy border"><?= e($a['module']) ?></span>
                                        <span class="text-muted ms-1"><?= e($a['action']) ?></span>
                                    </td>
                                    <td class="small font-monospace text-muted"><?= e($a['ip_address']) ?></td>
                                    <td class="small text-muted"><?= date('H:i:s d/m', strtotime($a['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
