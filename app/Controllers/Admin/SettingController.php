<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Services\AuditService;

class SettingController extends Controller
{
    public function index(): void
    {
        $settingsRaw = Database::query("SELECT * FROM site_settings");
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        $this->render('admin.settings.index', [
            'title' => 'ตั้งค่าระบบและองค์กร (Site Settings)',
            'settings' => $settings,
        ], 'layouts.admin');
    }

    public function update(): void
    {
        $inputs = $this->request->all();
        unset($inputs['_csrf_token']);

        foreach ($inputs as $k => $v) {
            Database::execute("INSERT INTO site_settings (`key`, `value`, `group`, updated_at) VALUES (?, ?, 'general', NOW()) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW()", [$k, $v]);
        }

        AuditService::log('settings', 'update', null, null, $inputs);
        Session::flash('success', 'บันทึกการตั้งค่าระบบเรียบร้อยแล้ว');
        $this->redirect(url('admin/settings'));
    }
}
