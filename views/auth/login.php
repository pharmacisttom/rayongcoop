<form action="<?= url('admin/login') ?>" method="POST" id="adminLoginForm">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label fw-bold small text-navy">ชื่อผู้ใช้หรืออีเมล (Username / Email)</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person text-secondary"></i></span>
            <input type="text" name="email" class="form-control form-control-lg" placeholder="admin@rayongcoop.com" value="<?= e(old('email', 'admin@rayongcoop.com')) ?>" required autofocus>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold small text-navy">รหัสผ่าน (Password)</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-key text-secondary"></i></span>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••••••" value="Admin@RayongCoop2026!" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
        <i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ CMS
    </button>

    <div class="text-center mt-4 pt-2 border-top">
        <a href="<?= url('/') ?>" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> กลับสู่หน้าหลักเว็บไซต์
        </a>
    </div>
</form>
