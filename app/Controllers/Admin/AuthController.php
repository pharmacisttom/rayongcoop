<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Session;
use App\Services\AuditService;
use App\Services\TwoFactorService;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect(url('admin/dashboard'));
            return;
        }

        $this->render('auth.login', [
            'title' => 'เข้าสู่ระบบเจ้าหน้าที่ (Admin Login)',
        ], 'layouts.auth');
    }

    public function login(): void
    {
        $data = $this->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = trim($data['email']);
        $password = $data['password'];
        $ip = $this->request->ip();
        $ua = $this->request->userAgent();

        // Check user
        $sql = "SELECT u.*, r.slug as role_slug, r.name as role_name 
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE (u.email = ? OR u.username = ?) AND u.deleted_at IS NULL
                LIMIT 1";
        $user = Database::first($sql, [$email, $email]);

        if (!$user || !password_verify($password, $user['password'])) {
            // Log failed attempt
            Database::execute("INSERT INTO login_logs (email, status, failure_reason, ip_address, user_agent, created_at) VALUES (?, 'failed', 'รหัสผ่านไม่ถูกต้อง หรือไม่พบบัญชีผู้ใช้', ?, ?, NOW())", [$email, $ip, $ua]);
            Logger::auth("Failed login attempt for: {$email} from IP: {$ip}");

            Session::flash('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            $this->redirect(url('admin/login'));
            return;
        }

        if ($user['status'] !== 'active') {
            Database::execute("INSERT INTO login_logs (user_id, email, status, failure_reason, ip_address, user_agent, created_at) VALUES (?, ?, 'locked_out', 'บัญชีถูกระงับการใช้งาน', ?, ?, NOW())", [$user['id'], $email, $ip, $ua]);
            Session::flash('error', 'บัญชีผู้ใช้งานนี้ถูกระงับ กรุณาติดต่อผู้ดูแลระบบ');
            $this->redirect(url('admin/login'));
            return;
        }

        // Check if 2FA is enabled
        if ((int)$user['two_factor_enabled'] === 1 && !empty($user['two_factor_secret'])) {
            // Set partial session for 2FA verification step
            Auth::login($user, false);
            $this->redirect(url('admin/2fa'));
            return;
        }

        // Login success (no 2FA required)
        Auth::login($user, true);
        Database::execute("UPDATE users SET last_login_at = NOW(), last_login_ip = ? WHERE id = ?", [$ip, $user['id']]);
        Database::execute("INSERT INTO login_logs (user_id, email, status, ip_address, user_agent, created_at) VALUES (?, ?, 'success', ?, ?, NOW())", [$user['id'], $email, $ip, $ua]);
        AuditService::log('auth', 'login', (string)$user['id'], null, ['status' => 'success']);

        Session::flash('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ' . $user['name']);
        $this->redirect(url('admin/dashboard'));
    }

    public function showTwoFactor(): void
    {
        if (!Auth::checkPending2FA()) {
            $this->redirect(url('admin/login'));
            return;
        }

        $this->render('auth.two_factor', [
            'title' => 'ยืนยันรหัสความปลอดภัย 2FA',
        ], 'layouts.auth');
    }

    public function verifyTwoFactor(): void
    {
        if (!Auth::checkPending2FA()) {
            $this->redirect(url('admin/login'));
            return;
        }

        $code = trim((string)$this->request->input('code'));
        $userId = Auth::id();
        $user = Database::first("SELECT * FROM users WHERE id = ? LIMIT 1", [$userId]);

        if (!$user || !TwoFactorService::verifyCode($user['two_factor_secret'], $code)) {
            Database::execute("INSERT INTO login_logs (user_id, email, status, failure_reason, ip_address, user_agent, created_at) VALUES (?, ?, '2fa_failed', 'รหัส 2FA ไม่ถูกต้อง', ?, ?, NOW())", [$user['id'], $user['email'], $this->request->ip(), $this->request->userAgent()]);
            Session::flash('error', 'รหัส 2FA 6 หลักไม่ถูกต้องหรือหมดอายุ');
            $this->redirect(url('admin/2fa'));
            return;
        }

        // 2FA Success
        Auth::verify2FA();
        Database::execute("UPDATE users SET last_login_at = NOW(), last_login_ip = ? WHERE id = ?", [$this->request->ip(), $user['id']]);
        Database::execute("INSERT INTO login_logs (user_id, email, status, ip_address, user_agent, created_at) VALUES (?, ?, 'success', ?, ?, NOW())", [$user['id'], $user['email'], $this->request->ip(), $this->request->userAgent()]);
        AuditService::log('auth', '2fa_verified', (string)$user['id'], null, ['status' => '2fa_success']);

        Session::flash('success', 'ยืนยันตัวตนสำเร็จ');
        $this->redirect(url('admin/dashboard'));
    }

    public function logout(): void
    {
        AuditService::log('auth', 'logout', (string)Auth::id());
        Auth::logout();
        Session::flash('info', 'ออกจากระบบเรียบร้อยแล้ว');
        $this->redirect(url('admin/login'));
    }
}
