<!-- Cookie Preference Center Modal -->
<div class="modal fade" id="cookiePreferenceModal" tabindex="-1" aria-labelledby="cookiePreferenceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-navy d-flex align-items-center" id="cookiePreferenceModalLabel">
                    <i class="bi bi-sliders text-primary me-2 fs-4"></i> ศูนย์ตั้งค่าความเป็นส่วนตัวและความยินยอมคุกกี้
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="text-secondary small mb-4">
                    สหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด ให้ความสำคัญกับสิทธิความเป็นส่วนตัวและการคุ้มครองข้อมูลส่วนบุคคลของท่าน คุณสามารถเลือกเปิดหรือปิดการใช้งานคุกกี้แต่ละประเภทได้ตามรายละเอียดด้านล่างนี้
                </p>

                <div class="accordion" id="cookieAccordion">
                    <!-- 1. Necessary -->
                    <div class="card border rounded-3 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-navy mb-0">1. คุกกี้ที่จำเป็นอย่างยิ่ง (Strictly Necessary Cookies)</h6>
                                <span class="badge bg-success">เปิดใช้งานตลอดเวลา</span>
                            </div>
                            <p class="text-muted small mb-0">
                                คุกกี้ที่มีความจำเป็นสำหรับการทำงานพื้นฐานและความปลอดภัยของเว็บไซต์ เช่น การรักษาความปลอดภัย การเข้าสู่ระบบ และการป้องกันภัยคุกคามทางไซเบอร์ ไม่สามารถปิดการใช้งานได้
                            </p>
                        </div>
                    </div>

                    <!-- 2. Functional -->
                    <div class="card border rounded-3 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-navy mb-0">2. คุกกี้เพื่อการทำงานของเว็บไซต์ (Functional Cookies)</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-5" type="checkbox" id="cookieFunctionalToggle" checked>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                ช่วยจดจำการตั้งค่า ตัวเลือกภาษา หรือการปรับแต่งหน้าจอ เพื่ออำนวยความสะดวกในการกลับมาเข้าใช้งานในครั้งถัดไป
                            </p>
                        </div>
                    </div>

                    <!-- 3. Analytics -->
                    <div class="card border rounded-3 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-navy mb-0">3. คุกกี้เพื่อการวิเคราะห์และวัดผล (Analytics Cookies)</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-5" type="checkbox" id="cookieAnalyticsToggle">
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                ช่วยให้เราเข้าใจพฤติกรรมการใช้งาน เพื่อนำไปปรับปรุงประสิทธิภาพและโครงสร้างเว็บไซต์ให้ตอบสนองต่อความต้องการของสมาชิกมากยิ่งขึ้น
                            </p>
                        </div>
                    </div>

                    <!-- 4. Marketing -->
                    <div class="card border rounded-3 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-navy mb-0">4. คุกกี้เพื่อการตลาดและประชาสัมพันธ์ (Marketing Cookies)</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-5" type="checkbox" id="cookieMarketingToggle">
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                ใช้เพื่อนำเสนอข่าวสาร สิทธิประโยชน์ และโปรโมชั่นเงินฝาก/สินเชื่อที่ตรงกับความสนใจของท่าน
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm btn-reject-cookies">ปฏิเสธทั้งหมดที่ไม่จำเป็น</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm px-4" id="btnSaveCookiePreferences">
                        <i class="bi bi-check-lg me-1"></i> บันทึกการตั้งค่า
                    </button>
                    <button type="button" class="btn btn-success btn-sm px-4 btn-accept-all-cookies">
                        ยอมรับทั้งหมด
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
