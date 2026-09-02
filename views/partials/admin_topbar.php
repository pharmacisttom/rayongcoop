<header class="admin-topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-light d-lg-none me-2" id="btnToggleSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="text-muted small d-none d-sm-inline">
            <i class="bi bi-shield-shaded text-success me-1"></i> โหมดความปลอดภัยสูงสุด (Argon2id & Audit Logged)
        </span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- View Public Website -->
        <a href="<?= url('/') ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            <i class="bi bi-globe me-1"></i> ดูหน้าเว็บไซต์
        </a>

        <!-- Notification Bell -->
        <div class="dropdown">
            <button class="btn btn-light rounded-circle position-relative p-2" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" style="width: 280px;">
                <li class="px-3 py-2 border-bottom fw-bold text-navy small">การแจ้งเตือนระบบ</li>
                <li><a class="dropdown-item py-2 small" href="<?= url('admin/complaints') ?>"><i class="bi bi-chat-dots text-warning me-2"></i> เรื่องร้องเรียนใหม่รอตรวจสอบ</a></li>
                <li><a class="dropdown-item py-2 small" href="<?= url('admin/interest-rates') ?>"><i class="bi bi-percent text-success me-2"></i> อัตราดอกเบี้ยมีผลบังคับใช้</a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item text-center small text-primary" href="#">ดูการแจ้งเตือนทั้งหมด</a></li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 p-1 pe-3 rounded-pill" type="button" data-bs-toggle="dropdown">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="text-start d-none d-md-block" style="line-height: 1.2;">
                    <div class="fw-bold small text-navy"><?= e($currentUser['name'] ?? 'Admin') ?></div>
                    <small class="text-muted" style="font-size: 0.72rem;"><?= e(strtoupper($currentUser['role_slug'] ?? 'Super Admin')) ?></small>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li><a class="dropdown-item" href="<?= url('admin/profile') ?>"><i class="bi bi-person me-2"></i> ข้อมูลส่วนตัว</a></li>
                <li><a class="dropdown-item" href="<?= url('admin/security') ?>"><i class="bi bi-shield-lock me-2"></i> ความปลอดภัย & 2FA</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="<?= url('admin/logout') ?>" method="POST" id="logoutForm">
                        <?= csrf_field() ?>
                        <button type="button" class="dropdown-item text-danger" onclick="showLogoutConfirm(() => document.getElementById('logoutForm').submit())">
                            <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
