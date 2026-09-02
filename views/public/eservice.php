<div class="py-5 bg-navy text-white">
    <div class="container">
        <span class="badge bg-gold text-white mb-2 px-3 py-1">บริการออนไลน์ 24 ชั่วโมง</span>
        <h1 class="text-white fw-bold display-6 mb-2">ศูนย์บริการออนไลน์ (E-Service Gateway)</h1>
        <p class="text-light-blue lead mb-0">เข้าถึงระบบบริการสมาชิก ตรวจสอบข้อมูลหุ้น เงินฝาก เงินกู้ เงินปันผล และระบบสมาคมฌาปนกิจสงเคราะห์</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($eservices as $es): ?>
            <div class="col-lg-6">
                <div class="coop-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-start mb-3">
                        <div class="quick-service-icon me-3 flex-shrink-0" style="background: linear-gradient(135deg, var(--coop-navy) 0%, var(--coop-blue) 100%); color: #fff;">
                            <i class="bi <?= e($es['icon']) ?>"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h5 class="fw-bold text-navy mb-0"><?= e($es['name']) ?></h5>
                                <?php if ($es['is_maintenance']): ?>
                                    <span class="badge bg-danger">ปิดปรับปรุงชั่วคราว</span>
                                <?php else: ?>
                                    <span class="badge bg-success">เปิดให้บริการ</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted small mb-0"><?= e($es['description']) ?></p>
                        </div>
                    </div>

                    <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted"><i class="bi bi-shield-check text-success me-1"></i> เชื่อมต่อความปลอดภัยระดับสูง</small>
                        <?php if ($es['is_maintenance']): ?>
                            <button class="btn btn-secondary btn-sm" disabled>อยู่ระหว่างปิดปรับปรุง</button>
                        <?php else: ?>
                            <a href="<?= e($es['url']) ?>" class="btn btn-primary btn-sm px-4" data-confirm-external="<?= $es['confirm_before_redirect'] ?>" data-service-name="<?= e($es['name']) ?>">
                                เข้าใช้งานระบบ <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
