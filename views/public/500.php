<div class="py-5 text-center my-5">
    <div class="container">
        <div class="coop-card p-5 mx-auto" style="max-width: 600px;">
            <i class="bi bi-shield-x text-danger display-1 mb-3"></i>
            <h1 class="fw-bold text-navy mb-2">500 - ระบบขัดข้องชั่วคราว</h1>
            <p class="text-muted mb-4"><?= e($message ?? 'เกิดข้อผิดพลาดภายในระบบ ขออภัยในความไม่สะดวก เจ้าหน้าที่กำลังดำเนินการแก้ไข') ?></p>
            <a href="<?= url('/') ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-house-door me-1"></i> กลับสู่หน้าแรก
            </a>
        </div>
    </div>
</div>
