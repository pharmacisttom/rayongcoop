<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class ApiController extends Controller
{
    public function logCookieConsent(): void
    {
        $input = $this->request->all();
        $anonId = $input['anonymous_consent_id'] ?? bin2hex(random_bytes(16));
        $ipHash = hash('sha256', $this->request->ip());
        $ua = substr($this->request->userAgent(), 0, 255);

        $sql = "INSERT INTO cookie_consents (anonymous_consent_id, consent_version, necessary, functional, analytics, marketing, third_party, ip_hash, user_agent, consented_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    functional = VALUES(functional), 
                    analytics = VALUES(analytics), 
                    marketing = VALUES(marketing), 
                    third_party = VALUES(third_party),
                    updated_at = NOW()";

        Database::execute($sql, [
            $anonId,
            $input['consent_version'] ?? '1.0',
            1, // necessary
            (int) ($input['functional'] ?? 1),
            (int) ($input['analytics'] ?? 0),
            (int) ($input['marketing'] ?? 0),
            (int) ($input['third_party'] ?? 0),
            $ipHash,
            $ua
        ]);

        $this->json(['success' => true, 'message' => 'Consent logged']);
    }

    public function logPopupEvent(): void
    {
        $input = $this->request->all();
        $popupId = (int) ($input['popup_id'] ?? 0);
        $eventType = $input['event_type'] ?? 'impression';
        $pagePath = $input['page_path'] ?? '/';

        if ($popupId <= 0) {
            $this->json(['success' => false, 'message' => 'Invalid popup ID'], 400);
            return;
        }

        // Update popup counter
        if ($eventType === 'impression') {
            Database::execute("UPDATE popups SET impressions_count = impressions_count + 1 WHERE id = ?", [$popupId]);
        } elseif (in_array($eventType, ['click', 'cta_click'], true)) {
            Database::execute("UPDATE popups SET clicks_count = clicks_count + 1 WHERE id = ?", [$popupId]);
        }

        // Insert event record
        $ip = $this->request->ip();
        $ua = substr($this->request->userAgent(), 0, 255);
        $device = (str_contains(strtolower($ua), 'mobile')) ? 'mobile' : 'desktop';

        Database::execute(
            "INSERT INTO popup_events (popup_id, event_type, ip_address, user_agent, device_type, page_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$popupId, $eventType, $ip, $ua, $device, $pagePath]
        );

        $this->json(['success' => true, 'message' => 'Event tracked']);
    }
}
