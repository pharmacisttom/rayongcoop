<div class="py-5 bg-navy text-white">
    <div class="container">
        <span class="badge bg-gold text-white mb-2 px-3 py-1">ช่องทางการติดต่อ</span>
        <h1 class="text-white fw-bold display-6 mb-2">ติดต่อสหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด</h1>
        <p class="text-light-blue lead mb-0">พร้อมให้บริการและคำปรึกษาทางการเงินแก่สมาชิกทุกท่าน</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Info & Google Maps -->
        <div class="col-lg-5">
            <div class="coop-card p-4 p-md-5 mb-4">
                <h4 class="fw-bold text-navy mb-4"><i class="bi bi-geo-alt-fill text-danger me-2"></i> สำนักงานใหญ่</h4>
                
                <div class="d-flex mb-3">
                    <i class="bi bi-pin-map text-primary fs-5 me-3"></i>
                    <div>
                        <b class="d-block text-navy">ที่อยู่:</b>
                        <span class="text-muted small"><?= config('app.coop.address') ?></span>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <i class="bi bi-telephone text-primary fs-5 me-3"></i>
                    <div>
                        <b class="d-block text-navy">หมายเลขโทรศัพท์:</b>
                        <span class="text-muted small"><?= config('app.coop.phone') ?></span>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <i class="bi bi-envelope text-primary fs-5 me-3"></i>
                    <div>
                        <b class="d-block text-navy">อีเมล:</b>
                        <span class="text-muted small"><?= config('app.coop.email') ?></span>
                    </div>
                </div>

                <div class="d-flex">
                    <i class="bi bi-clock text-primary fs-5 me-3"></i>
                    <div>
                        <b class="d-block text-navy">เวลาทำการ:</b>
                        <span class="text-muted small"><?= config('app.coop.office_hours') ?></span>
                    </div>
                </div>
            </div>

            <!-- Map Embed -->
            <div class="coop-card overflow-hidden" style="height: 260px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.7525381831825!2d101.24838647579148!3d12.678128587609279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3102f835b3c38fbb%3A0x6fb708bf93c68ea7!2sRayong%20Provincial%20Public%20Health%20Office!5e0!3m2!1sen!2sth!4v1700000000000!5m2!1sen!2sth" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="coop-card p-4 p-md-5">
                <h4 class="fw-bold text-navy mb-2"><i class="bi bi-send text-primary me-2"></i> ส่งข้อความถึงเรา</h4>
                <p class="text-muted small mb-4">กรอกข้อมูลด้านล่างเพื่อส่งข้อความสอบถามหรือข้อเสนอแนะ</p>

                <form id="publicContactForm" action="<?= url('contact/submit') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">อีเมล</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">เรื่องที่ต้องการติดต่อ <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">ข้อความ <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                <i class="bi bi-send-fill me-2"></i> ส่งข้อความ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
