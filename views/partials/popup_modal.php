<!-- Dynamic Campaign Popup Modal -->
<div class="modal fade" id="campaignPopupModal" tabindex="-1" aria-labelledby="popupModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0 overflow-hidden">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-navy" id="popupModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="popupModalBody">
                <!-- Dynamic injected via popup-manager.js -->
            </div>
            <div class="modal-footer bg-light border-0 py-3 px-4 d-flex align-items-center" id="popupModalFooter">
                <!-- Dynamic controls injected via popup-manager.js -->
            </div>
        </div>
    </div>
</div>

<?php
use App\Core\Database;
// Fetch active popup queue for the current page
$popups = Database::query("SELECT * FROM popups WHERE status = 'active' AND (start_at IS NULL OR start_at <= NOW()) AND (end_at IS NULL OR end_at >= NOW()) ORDER BY FIELD(priority, 'critical', 'high', 'normal', 'low'), created_at DESC");
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popupsData = <?= json_encode($popups, JSON_UNESCAPED_UNICODE) ?>;
        if (typeof PopupManager !== 'undefined') {
            PopupManager.init(popupsData);
        }
    });
</script>
