<header class="main-header border-bottom">
    <div class="container-xl">
        <nav class="navbar navbar-expand-xl navbar-light py-2">
            <!-- Brand Logo & Name -->
            <a class="navbar-brand d-flex align-items-center py-0 me-3" href="<?= url('/') ?>">
                <img src="<?= asset('img/logo.png') ?>" alt="<?= config('app.coop.short_name') ?>" class="me-2 shadow-sm rounded-circle" style="width: 44px; height: 44px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="brand-icon me-2 align-items-center justify-content-center bg-navy text-white rounded-3 shadow-sm" style="width: 44px; height: 44px; display: none;">
                    <i class="bi bi-bank2 fs-4"></i>
                </div>
                <div>
                    <div class="brand-text-main fw-bold text-navy" style="font-size: 1.15rem; line-height: 1.2; letter-spacing: -0.01em;"><?= config('app.coop.short_name') ?></div>
                    <div class="brand-text-sub text-muted small d-none d-sm-block" style="font-size: 0.72rem;"><?= config('app.coop.full_name_th') ?></div>
                </div>
            </a>

            <!-- Mobile Controls -->
            <div class="d-flex align-items-center d-xl-none ms-auto gap-2">
                <a href="<?= url('eservice') ?>" class="btn btn-navy btn-sm px-3 shadow-sm">
                    <i class="bi bi-person-circle me-1"></i> E-Service
                </a>
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Desktop Menu -->
            <div class="collapse navbar-collapse" id="desktopNavbar">
                <ul class="navbar-nav mx-auto align-items-center mb-2 mb-xl-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $request->uri() === '/' ? 'active' : '' ?>" href="<?= url('/') ?>">
                            หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item dropdown has-megamenu">
                        <a class="nav-link dropdown-toggle <?= in_array($request->uri(), ['/about', '/board', '/statistics', '/complaints']) ? 'active' : '' ?>" href="#" data-bs-toggle="dropdown">
                            เกี่ยวกับสหกรณ์
                        </a>
                        <div class="dropdown-menu megamenu-dropdown shadow-lg border-0">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-navy mb-3 pb-2 border-bottom"><i class="bi bi-building me-1 text-primary"></i> ข้อมูลองค์กร</h6>
                                    <a class="megamenu-item-link" href="<?= url('about') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-shield-check"></i></div>
                                        <div>
                                            <div class="fw-semibold">ประวัติและวิสัยทัศน์</div>
                                            <small class="text-muted">ความเป็นมา ค่านิยม พันธกิจ</small>
                                        </div>
                                    </a>
                                    <a class="megamenu-item-link" href="<?= url('board') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-people"></i></div>
                                        <div>
                                            <div class="fw-semibold">คณะกรรมการดำเนินการ</div>
                                            <small class="text-muted">โครงสร้างการบริหารและฝ่ายจัดการ</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-navy mb-3 pb-2 border-bottom"><i class="bi bi-graph-up-arrow me-1 text-primary"></i> ผลการดำเนินงาน</h6>
                                    <a class="megamenu-item-link" href="<?= url('statistics') ?>">
                                        <div class="megamenu-icon"><i class="bi bi-pie-chart"></i></div>
                                        <div>
                                            <div class="fw-semibold">ฐานะการเงินและสถิติ</div>
                                            <small class="text-muted">ทุนเรือนหุ้น เงินฝาก สินเชื่อ</small>
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
                                    <div class="p-3 bg-light-blue rounded-3 border">
                                        <h6 class="fw-bold text-navy mb-2"><i class="bi bi-shield-lock me-1 text-primary"></i> ความโปร่งใส & บริการ</h6>
                                        <p class="small text-muted mb-3">ยึดมั่นการดำเนินงานตามหลักธรรมาภิบาล มั่นคง โปร่งใส ตรวจสอบได้</p>
                                        <a href="<?= url('complaints') ?>" class="btn btn-sm btn-primary w-100 fw-medium">ศูนย์รับเรื่องร้องเรียน</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/deposits') ? 'active' : '' ?>" href="<?= url('deposits') ?>">
                            เงินฝาก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/loans') ? 'active' : '' ?>" href="<?= url('loans') ?>">
                            สินเชื่อ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/calculator') ? 'active' : '' ?>" href="<?= url('calculator') ?>">
                            คำนวณเงินกู้
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/welfare') ? 'active' : '' ?>" href="<?= url('welfare') ?>">
                            สวัสดิการ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/news') ? 'active' : '' ?>" href="<?= url('news') ?>">
                            ข่าวสาร
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/documents') ? 'active' : '' ?>" href="<?= url('documents') ?>">
                            ดาวน์โหลด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with($request->uri(), '/contact') ? 'active' : '' ?>" href="<?= url('contact') ?>">
                            ติดต่อเรา
                        </a>
                    </li>
                </ul>

                <!-- E-Service CTA Button -->
                <div class="d-flex align-items-center ms-lg-2">
                    <a href="<?= url('eservice') ?>" class="btn btn-header-eservice d-inline-flex align-items-center shadow-sm">
                        <i class="bi bi-person-fill-lock fs-5 me-2"></i>
                        <span>เข้าสู่ระบบ E-Service</span>
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
