/**
 * RayongCoop Digital Portal - Main Frontend Script
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. External Link Interception for Services with SweetAlert2 Confirmation
    document.querySelectorAll('a[data-confirm-external="1"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const name = this.getAttribute('data-service-name') || 'ระบบบริการภายนอก';
            if (typeof showExternalLinkConfirm === 'function') {
                showExternalLinkConfirm(url, name);
            } else {
                window.open(url, '_blank');
            }
        });
    });

    // 2. Initialize Bootstrap Tooltips & Popovers
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // 3. Sticky Header Elevation Shadow on Scroll
    const header = document.querySelector('.main-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                header.classList.add('shadow-sm');
            } else {
                header.classList.remove('shadow-sm');
            }
        });
    }

    // 4. Global Contact Form AJAX Handler with SweetAlert2
    const contactForm = document.getElementById('publicContactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch(this.getAttribute('action') || window.APP_URL + '/contact/submit', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) submitBtn.disabled = false;
                if (data.success) {
                    showSuccess('ส่งข้อความสำเร็จ!', data.message || 'เจ้าหน้าที่จะติดต่อกลับโดยเร็วที่สุด', () => {
                        contactForm.reset();
                    });
                } else {
                    showError('เกิดข้อผิดพลาด', data.message || 'กรุณาตรวจสอบข้อมูลที่กรอก');
                }
            })
            .catch(err => {
                if (submitBtn) submitBtn.disabled = false;
                showNetworkError();
            });
        });
    }

    // 5. Global Complaint Form AJAX Handler
    const complaintForm = document.getElementById('publicComplaintForm');
    if (complaintForm) {
        complaintForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch(this.getAttribute('action') || window.APP_URL + '/complaints/submit', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) submitBtn.disabled = false;
                if (data.success) {
                    SwalApp.fire({
                        icon: 'success',
                        title: 'ส่งเรื่องร้องเรียนสำเร็จ!',
                        html: `ระบบได้บันทึกเรื่องร้องเรียนของท่านแล้ว<br>เลขที่เรื่องร้องเรียนของคุณคือ: <b class="text-primary font-monospace fs-5">${data.ticket_no}</b><br><small class="text-muted">โปรดบันทึกเลขที่นี้เพื่อใช้ในการติดตามผล</small>`,
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        complaintForm.reset();
                        window.location.href = window.APP_URL + '/complaints/track?ticket=' + encodeURIComponent(data.ticket_no);
                    });
                } else {
                    showError('เกิดข้อผิดพลาด', data.message || 'กรุณาตรวจสอบข้อมูลที่กรอก');
                }
            })
            .catch(err => {
                if (submitBtn) submitBtn.disabled = false;
                showNetworkError();
            });
        });
    }
});
