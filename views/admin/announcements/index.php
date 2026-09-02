<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-navy mb-1"><i class="bi bi-megaphone me-2 text-primary"></i> จัดการแถบประกาศสำคัญ</h3>
        <p class="text-muted small mb-0">แถบข้อความแจ้งเตือนด้านบนสุดของหน้าเว็บ (Top Announcement Bar)</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAnnounceModal">
        <i class="bi bi-plus-lg me-1"></i> สร้างประกาศใหม่
    </button>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>หัวข้อประกาศ</th>
                        <th>ข้อความแจ้งเตือน</th>
                        <th>Priority</th>
                        <th>ปุ่มลิงก์</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-bold text-navy"><?= e($a['title']) ?></td>
                            <td class="small text-muted text-truncate" style="max-width: 300px;"><?= e($a['message']) ?></td>
                            <td>
                                <span class="badge <?= $a['priority'] === 'urgent' ? 'bg-danger' : ($a['priority'] === 'important' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                    <?= e(strtoupper($a['priority'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($a['link_url'])): ?>
                                    <span class="badge bg-light text-navy border"><?= e($a['link_text'] ?? 'ลิงก์') ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $a['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $a['is_active'] ? 'เปิดแสดงผล' : 'ปิด' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAnnounce(<?= $a['id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Announcement Modal -->
<div class="modal fade" id="createAnnounceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-navy">สร้างแถบประกาศใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('admin/announcements/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">หัวข้อประกาศ <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="เช่น แจ้งสมาชิกตรวจสอบเงินปันผล...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ข้อความประกาศ <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">URL ปลายทาง</label>
                            <input type="text" name="link_url" class="form-control" placeholder="/eservice">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ข้อความบนปุ่ม</label>
                            <input type="text" name="link_text" class="form-control" value="คลิกอ่านเพิ่มเติม">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ระดับความสำคัญ</label>
                            <select name="priority" class="form-select">
                                <option value="general">General (ทั่วไป)</option>
                                <option value="important" selected>Important (สำคัญ)</option>
                                <option value="urgent">Urgent (ด่วนที่สุด)</option>
                                <option value="loan">Loan (สินเชื่อ)</option>
                                <option value="welfare">Welfare (สวัสดิการ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">สถานะ</label>
                            <select name="is_active" class="form-select">
                                <option value="1">เปิดแสดงผลทันที</option>
                                <option value="0">ปิดการแสดงผล</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">บันทึกประกาศ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteAnnounce(id) {
    showDeleteConfirm(() => {
        fetch(window.APP_URL + '/admin/announcements/' + id + '/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'ลบประกาศเรียบร้อยแล้ว');
                setTimeout(() => location.reload(), 800);
            } else {
                showError('เกิดข้อผิดพลาด', data.message);
            }
        });
    });
}
</script>
