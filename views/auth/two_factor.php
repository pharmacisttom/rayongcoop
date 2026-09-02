<form action="<?= url('admin/2fa/verify') ?>" method="POST" id="twoFactorForm">
    <?= csrf_field() ?>

    <div class="text-center mb-4">
        <i class="bi bi-shield-check text-success display-4 mb-2 d-block"></i>
        <h5 class="fw-bold text-navy">การยืนยันตัวตนสองขั้นตอน (2FA)</h5>
        <p class="text-muted small mb-0">กรุณากรอกรหัสความปลอดภัย 6 หลักจากแอปพลิเคชัน Authenticator ของคุณ</p>
    </div>

    <div class="mb-4 text-center">
        <input type="text" name="code" class="form-control form-control-lg text-center font-monospace fs-3 tracking-widest" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required autofocus>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
        <i class="bi bi-check-circle me-1"></i> ยืนยันรหัสความปลอดภัย
    </button>

    <div class="text-center mt-4 pt-2 border-top">
        <a href="<?= url('admin/logout') ?>" class="text-muted small text-decoration-none">
            <i class="bi bi-box-arrow-left me-1"></i> ยกเลิกและกลับสู่หน้าล็อกอิน
        </a>
    </div>
</form>
