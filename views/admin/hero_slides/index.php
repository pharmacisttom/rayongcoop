<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-navy mb-1"><i class="bi bi-images me-2 text-primary"></i> จัดการ Hero Slideshow</h3>
        <p class="text-muted small mb-0">แบนเนอร์ภาพสไลด์ขนาดใหญ่บนหน้าแรกของเว็บไซต์</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSlideModal">
        <i class="bi bi-plus-lg me-1"></i> เพิ่มสไลด์ใหม่
    </button>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ลำดับ</th>
                        <th>หัวข้อสไลด์ (Title)</th>
                        <th>ปุ่ม CTA</th>
                        <th>ความโปร่งแสง Overlay</th>
                        <th>Priority</th>
                        <th>สถานะ</th>
                        <th class="text-end">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slides as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-bold text-navy"><?= e($s['title']) ?></div>
                                <small class="text-muted"><?= e($s['subtitle'] ?? '') ?></small>
                            </td>
                            <td>
                                <?php if (!empty($s['button_text'])): ?>
                                    <span class="badge bg-light text-primary border"><?= e($s['button_text']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= (float)$s['overlay_opacity'] * 100 ?>%</td>
                            <td><span class="badge bg-secondary"><?= e($s['priority']) ?></span></td>
                            <td>
                                <span class="badge <?= $s['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= e(strtoupper($s['status'])) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSlide(<?= $s['id'] ?>)">
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

<!-- Create Slide Modal -->
<div class="modal fade" id="createSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-navy">เพิ่ม Hero Slide ใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('admin/hero-slides/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">หัวข้อใหญ่ (Main Title) <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="เช่น มั่นคง โปร่งใส ทันสมัย...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">หัวข้อย่อย (Subtitle / Badge)</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="เช่น สหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">คำอธิบาย (Description)</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ข้อความบนปุ่ม CTA</label>
                            <input type="text" name="button_text" class="form-control" placeholder="เข้าสู่ระบบ E-Service">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ลิงก์ปลายทางปุ่ม (URL)</label>
                            <input type="text" name="button_url" class="form-control" placeholder="/eservice">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ตำแหน่งข้อความ</label>
                            <select name="text_alignment" class="form-select">
                                <option value="left">ชิดซ้าย (Left)</option>
                                <option value="center">กึ่งกลาง (Center)</option>
                                <option value="right">ชิดขวา (Right)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ความโปร่งแสง Overlay</label>
                            <input type="number" name="overlay_opacity" class="form-control" value="0.40" step="0.05" min="0" max="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ระดับความสำคัญ (Priority)</label>
                            <input type="number" name="priority" class="form-control" value="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">บันทึกสไลด์</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteSlide(id) {
    showDeleteConfirm(() => {
        fetch(window.APP_URL + '/admin/hero-slides/' + id + '/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'ลบสไลด์เรียบร้อยแล้ว');
                setTimeout(() => location.reload(), 800);
            } else {
                showError('เกิดข้อผิดพลาด', data.message);
            }
        });
    });
}
</script>
