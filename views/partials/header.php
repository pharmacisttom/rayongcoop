<header class="main-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light py-2">
            <!-- Brand Logo & Name -->
            <a class="navbar-brand d-flex align-items-center" href="<?= url('/') ?>">
                <div class="brand-icon me-2 d-flex align-items-center justify-content-center bg-navy text-white rounded-3 p-2 shadow-sm">
                    <i class="bi bi-bank2 fs-4"></i>
                </div>
                <div>
                    <div class="brand-text-main"><?= config('app.coop.short_name') ?></div>
                    <div class="brand-text-sub d-none d-md-block"><?= config('app.coop.full_name_th') ?></div>
                </div>
            </a>

            <!-- Mobile Controls -->
            <div class="d-flex align-items-center d-lg-none ms-auto">
                <a href="<?= url('eservice') ?>" class="btn btn-navy btn-sm me-2">
                    <i class="bi bi-person-circle me-1"></i> E-Service
                </a>
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Desktop Menu -->
            <div class="collapse navbar-collapse" id="desktopNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $request->uri() === '/' ? 'active' : '' ?>" href="<?= url('/') ?>">
                            <i class="bi bi-house-door me-1"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item dropdown has-megamenu">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-info-circle me-1"></i> เกี่ยวกับสหกรณ์
                        </a>
                        <div class="dropdown-menu megamenu-dropdown">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-navy mb-2"><i class="bi bi-building me-1"></i> องค์กร</h6>
                                    <a class="megamenu-item-link" href="<?= url('about') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-shield-check"></i></div>
                                        <div>
                                            <div class="fw-semibold">ประวัติและวิสัยทัศน์</div>
                                            <small class="text-muted">ความเป็นมา ค่านิยม และเป้าหมาย</small>
                                        </div>
                                    </a>
                                    <a class="megamenu-item-link" href="<?= url('board') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-people"></i></div>
                                        <div>
                                            <div class="fw-semibold">คณะกรรมการดำเนินการ</div>
                                            <small class="text-muted">โครงสร้างการบริหารและผู้ตรวจสอบ</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-navy mb-2"><i class="bi bi-graph-up-arrow me-1"></i> ผลการดำเนินงาน</h6>
                                    <a class="megamenu-item-link" href="<?= url('statistics') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-pie-chart"></i></div>
                                        <div>
                                            <div class="fw-semibold">ฐานะการเงินและสถิติ</div>
                                            <small class="text-muted">ทุนเรือนหุ้น เงินฝาก สินเชื่อ และสินทรัพย์</small>
                                        </div>
                                    </a>
                                    <a class="megamenu-item-link" href="<?= url('documents?cat=annual-reports') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
                                        <div>
                                            <div class="fw-semibold">รายงานประจำปี</div>
                                            <small class="text-muted">งบแสดงฐานะการเงินรายปี</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light-blue rounded-3">
                                        <h6 class="fw-bold text-navy"><i class="bi bi-shield-lock me-1"></i> ความโปร่งใส</h6>
                                        <p class="small text-muted mb-2">มุ่งมั่นบริหารงานด้วยหลักธรรมาภิบาล มั่นคง โปร่งใส ตรวจสอบได้</p>
                                        <a href="<?= url('complaints') ?>" class="btn btn-sm btn-outline-primary w-100">ศูนย์รับเรื่องร้องเรียน</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/deposits') ? 'active' : '' ?>" href="<?= url('deposits') ?>">
                            <i class="bi bi-piggy-bank me-1"></i> เงินฝาก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/loans') ? 'active' : '' ?>" href="<?= url('loans') ?>">
                            <i class="bi bi-cash-stack me-1"></i> เงินกู้
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/calculator') ? 'active' : '' ?>" href="<?= url('calculator') ?>">
                            <i class="bi bi-calculator me-1"></i> คำนวณเงินกู้
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/welfare') ? 'active' : '' ?>" href="<?= url('welfare') ?>">
                            <i class="bi bi-heart-pulse me-1"></i> สวัสดิการ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/news') ? 'active' : '' ?>" href="<?= url('news') ?>">
                            <i class="bi bi-newspaper me-1"></i> ข่าวสาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/documents') ? 'active' : '' ?>" href="<?= url('documents') ?>">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> เอกสาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/contact') ? 'active' : '' ?>" href="<?= url('contact') ?>">
                            <i class="bi bi-geo-alt me-1"></i> ติดต่อเรา
                        </a>
                    </li>
                </ul>

                <!-- E-Service CTA Button -->
                <div class="d-flex align-items-center">
                    <a href="<?= url('eservice') ?>" class="btn btn-navy shadow-sm">
                        <i class="bi bi-person-circle me-1"></i> เข้าสู่ระบบ E-Service
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenuOffcanvas" aria-labelledby="mobileMenuOffcanvasLabel">
    <div class="offcanvas-header bg-navy text-white">
        <h5 class="offcanvas-title d-flex align-items-center" id="mobileMenuOffcanvasLabel">
            <i class="bi bi-bank2 me-2"></i> สอ.สธ.ระยอง
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3 bg-light border-bottom">
            <a href="<?= url('eservice') ?>" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-person-circle me-1"></i> เข้าสู่ระบบ E-Service สมาชิก
            </a>
        </div>
        <div class="list-group list-group-flush">
            <a href="<?= url('/') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-house-door me-2 text-primary"></i> หน้าแรก</a>
            <a href="<?= url('about') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-info-circle me-2 text-primary"></i> เกี่ยวกับสหกรณ์</a>
            <a href="<?= url('deposits') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-piggy-bank me-2 text-primary"></i> ผลิตภัณฑ์เงินฝาก</a>
            <a href="<?= url('loans') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-cash-stack me-2 text-primary"></i> ผลิตภัณฑ์เงินกู้</a>
            <a href="<?= url('calculator') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-calculator me-2 text-primary"></i> คำนวณเงินกู้ออนไลน์</a>
            <a href="<?= url('welfare') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-heart-pulse me-2 text-primary"></i> สวัสดิการสมาชิก</a>
            <a href="<?= url('news') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-newspaper me-2 text-primary"></i> ข่าวสารและกิจกรรม</a>
            <a href="<?= url('documents') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-file-earmark-arrow-down me-2 text-primary"></i> ศูนย์ดาวน์โหลดเอกสาร</a>
            <a href="<?= url('eservice') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-grid me-2 text-primary"></i> ศูนย์บริการออนไลน์</a>
            <a href="<?= url('complaints') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-chat-dots me-2 text-primary"></i> แจ้งเรื่องร้องเรียน</a>
            <a href="<?= url('contact') ?>" class="list-group-item list-group-item-action py-3"><i class="bi bi-geo-alt me-2 text-primary"></i> ติดต่อสหกรณ์</a>
        </div>
    </div>
</div>
