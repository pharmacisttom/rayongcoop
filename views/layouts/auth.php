<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'เข้าสู่ระบบ') ?> — RayongCoop CMS</title>
    
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=1.0.0">

    <style>
        body.auth-body {
            background: linear-gradient(135deg, #073B74 0%, #0B5ED7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .auth-header {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .auth-body-content {
            padding: 30px;
        }
    </style>
</head>
<body class="auth-body">

    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= asset('img/logo.png') ?>" alt="Logo" class="shadow-sm rounded-circle mb-2" style="width: 64px; height: 64px; object-fit: contain;">
            <h4 class="fw-bold text-navy mt-1 mb-1">RayongCoop Portal</h4>
            <p class="text-muted small mb-0">ระบบบริหารจัดการดิจิทัล สอ.สธ.ระยอง จำกัด</p>
        </div>
        <div class="auth-body-content">
            <?= $content ?? '' ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <script src="<?= asset('js/swal-config.js') ?>?v=1.0.0"></script>

    <?php if (!empty($flashSuccess)): ?>
        <script>document.addEventListener('DOMContentLoaded', () => showToast('success', <?= json_encode($flashSuccess) ?>));</script>
    <?php endif; ?>
    <?php if (!empty($flashError)): ?>
        <script>document.addEventListener('DOMContentLoaded', () => showError('แจ้งเตือน', <?= json_encode($flashError) ?>));</script>
    <?php endif; ?>
</body>
</html>
