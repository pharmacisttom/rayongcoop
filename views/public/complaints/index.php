<div class="py-5 bg-navy text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-gold text-white mb-2 px-3 py-1">ธรรมาภิบาลและความโปร่งใส</span>
                <h1 class="text-white fw-bold display-6 mb-2">ศูนย์รับเรื่องร้องเรียนและข้อเสนอแนะ</h1>
                <p class="text-light-blue lead mb-0">ช่องทางรับฟังความคิดเห็น แจ้งเบาะแส และเรื่องร้องเรียน เพื่อการปรับปรุงการบริการที่มีคุณภาพ</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="<?= url('complaints/track') ?>" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-search me-1"></i> ติดตามสถานะเรื่องร้องเรียน
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="coop-card p-4 p-md-5">
                <h4 class="fw-bold text-navy mb-3"><i class="bi bi-envelope-paper me-2 text-primary"></i> แบบฟอร์มแจ้งเรื่องร้องเรียน / ข้อเสนอแนะ</h4>
                <p class="text-muted small mb-4">ข้อมูลของท่านจะถูกเก็บรักษาเป็นความลับตามนโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)</p>

                <form id="publicComplaintForm" action="<?= url('complaints/submit') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">ประเภทเรื่อง <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">-- เลือกประเภทเรื่อง --</option>
                                <option value="การให้บริการของเจ้าหน้าที่">การให้บริการของเจ้าหน้าที่</option>
                                <option value="ข้อเสนอแนะเพื่อการพัฒนา">ข้อเสนอแนะเพื่อการพัฒนา</option>
                                <option value="สินเชื่อและเงินฝาก">ข้อสอบถาม/ปัญหาด้านสินเชื่อและเงินฝาก</option>
                                <option value="ระบบออนไลน์/E-Service">ปัญหาการใช้งานระบบออนไลน์ / E-Service</option>
                                <option value="การแจ้งเบาะแสทุจริต">การแจ้งเบาะแสทุจริต / การไม่ปฏิบัติตามระเบียบ</option>
                                <option value="อื่นๆ">เรื่องอื่นๆ</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small">หัวข้อเรื่องร้องเรียน <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="ระบุหัวข้อเรื่องให้ชัดเจน" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold small">รายละเอียดเรื่องร้องเรียน / ข้อเสนอแนะ <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" placeholder="ระบุรายละเอียด เหตุการณ์ วันเวลา หรือข้อมูลที่เกี่ยวข้อง..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ชื่อ - นามสกุล ผู้แจ้ง <span class="text-danger">*</span></label>
                            <input type="text" name="complainant_name" class="form-control" placeholder="ชื่อ-นามสกุล" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">เบอร์โทรศัพท์ติดต่อ <span class="text-danger">*</span></label>
                            <input type="tel" name="complainant_phone" class="form-control" placeholder="08X-XXXXXXX" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">อีเมล (ถ้ามี)</label>
                            <input type="email" name="complainant_email" class="form-control" placeholder="example@email.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">แนบไฟล์ประกอบ (ถ้ามี - PDF, รูปภาพ สูงสุด 20MB)</label>
                            <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                <i class="bi bi-send-fill me-2"></i> ส่งเรื่องร้องเรียน
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
