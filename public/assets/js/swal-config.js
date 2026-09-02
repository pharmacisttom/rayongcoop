/**
 * RayongCoop Digital Portal - Central SweetAlert2 Configuration & Helper Functions
 * Standardized across both Public Portal and Admin CMS.
 */

// Central SweetAlert2 instance with custom styling
const SwalApp = Swal.mixin({
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary px-4 py-2 me-2 font-weight-medium shadow-sm',
        cancelButton: 'btn btn-outline-secondary px-4 py-2 font-weight-medium',
        denyButton: 'btn btn-danger px-4 py-2 me-2 font-weight-medium',
        popup: 'swal2-rayongcoop-popup rounded-4 shadow-lg border-0',
        title: 'swal2-rayongcoop-title text-navy font-weight-bold',
        htmlContainer: 'swal2-rayongcoop-html text-secondary'
    },
    reverseButtons: true,
    focusConfirm: false
});

// Toast Notification Instance (Top-End)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
    customClass: {
        popup: 'swal2-toast-rayongcoop shadow rounded-3 border-0'
    }
});

/**
 * Toast Helper
 * @param {'success'|'error'|'warning'|'info'} icon
 * @param {string} title
 * @param {number} timer (ms)
 */
function showToast(icon = 'success', title = 'ดำเนินการเรียบร้อยแล้ว', timer = 3500) {
    return Toast.fire({
        icon: icon,
        title: title,
        timer: timer
    });
}

/**
 * Success Alert Dialog
 */
function showSuccess(title = 'สำเร็จ!', text = 'ดำเนินการเรียบร้อยแล้ว', callback = null) {
    return SwalApp.fire({
        icon: 'success',
        iconColor: '#198754',
        title: title,
        text: text,
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Error Alert Dialog
 */
function showError(title = 'เกิดข้อผิดพลาด', text = 'ไม่สามารถทำรายการได้ กรุณาลองใหม่อีกครั้ง', callback = null) {
    return SwalApp.fire({
        icon: 'error',
        iconColor: '#DC3545',
        title: title,
        text: text,
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Warning Alert Dialog
 */
function showWarning(title = 'แจ้งเตือน', text = '', callback = null) {
    return SwalApp.fire({
        icon: 'warning',
        iconColor: '#FFC107',
        title: title,
        text: text,
        confirmButtonText: 'รับทราบ'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Info Alert Dialog
 */
function showInfo(title = 'ข้อมูล', text = '', callback = null) {
    return SwalApp.fire({
        icon: 'info',
        iconColor: '#0B5ED7',
        title: title,
        text: text,
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Generic Confirmation Dialog
 */
function showConfirm(title = 'ยืนยันการทำรายการ?', text = 'กรุณาตรวจสอบความถูกต้องก่อนยืนยัน', confirmText = 'ยืนยัน', cancelText = 'ยกเลิก', onConfirm = null, onCancel = null) {
    return SwalApp.fire({
        icon: 'question',
        iconColor: '#073B74',
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof onConfirm === 'function') onConfirm();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            if (typeof onCancel === 'function') onCancel();
        }
    });
}

/**
 * Delete Confirmation Dialog (Soft Delete)
 */
function showDeleteConfirm(onConfirm, customTitle = 'ยืนยันการลบข้อมูล?', customText = 'ข้อมูลนี้จะถูกย้ายไปถังขยะและสามารถกู้คืนได้') {
    return SwalApp.fire({
        icon: 'warning',
        iconColor: '#DC3545',
        title: customTitle,
        text: customText,
        showCancelButton: true,
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            confirmButton: 'btn btn-danger px-4 py-2 me-2 font-weight-medium shadow-sm',
            cancelButton: 'btn btn-outline-secondary px-4 py-2 font-weight-medium'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        }
    });
}

/**
 * Permanent Delete Confirmation Dialog
 */
function showPermanentDeleteConfirm(onConfirm, recordName = '') {
    return SwalApp.fire({
        icon: 'error',
        iconColor: '#DC3545',
        title: 'ลบข้อมูลถาวร?',
        html: `การดำเนินการนี้<b>ไม่สามารถย้อนกลับได้</b> ข้อมูล ${recordName} จะถูกลบออกจากฐานข้อมูลอย่างถาวร<br><br>พิมพ์ <code>DELETE</code> ด้านล่างเพื่อยืนยัน:`,
        input: 'text',
        inputPlaceholder: 'DELETE',
        showCancelButton: true,
        confirmButtonText: 'ลบถาวร',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            confirmButton: 'btn btn-danger px-4 py-2 me-2 font-weight-medium shadow-sm',
            cancelButton: 'btn btn-outline-secondary px-4 py-2 font-weight-medium'
        },
        preConfirm: (inputVal) => {
            if (inputVal !== 'DELETE') {
                Swal.showValidationMessage('กรุณาพิมพ์คำว่า DELETE ให้ถูกต้อง');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        }
    });
}

/**
 * Save Confirmation Dialog
 */
function showSaveConfirm(onConfirm) {
    return showConfirm('ยืนยันการบันทึกข้อมูล?', 'คุณต้องการบันทึกการเปลี่ยนแปลงนี้ใช่หรือไม่', 'บันทึกข้อมูล', 'กลับไปแก้ไข', onConfirm);
}

/**
 * Logout Confirmation Dialog
 */
function showLogoutConfirm(onConfirm) {
    return SwalApp.fire({
        icon: 'question',
        iconColor: '#073B74',
        title: 'ออกจากระบบ?',
        text: 'คุณต้องการออกจากระบบการทำงานใช่หรือไม่',
        showCancelButton: true,
        confirmButtonText: 'ออกจากระบบ',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            confirmButton: 'btn btn-danger px-4 py-2 me-2 font-weight-medium',
            cancelButton: 'btn btn-outline-secondary px-4 py-2 font-weight-medium'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        }
    });
}

/**
 * External Link Confirmation Dialog
 */
function showExternalLinkConfirm(targetUrl, serviceName = 'ระบบบริการภายนอก') {
    return SwalApp.fire({
        icon: 'info',
        iconColor: '#0B5ED7',
        title: 'กำลังจะไปยังระบบภายนอก',
        html: `คุณกำลังจะออกจากเว็บไซต์หลัก เพื่อไปยัง <b>${serviceName}</b><br><small class="text-muted">${targetUrl}</small>`,
        showCancelButton: true,
        confirmButtonText: 'ดำเนินการต่อ <i class="bi bi-box-arrow-up-right ms-1"></i>',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(targetUrl, '_blank');
        }
    });
}

/**
 * Show Loading Dialog
 */
function showLoading(title = 'กำลังประมวลผล...', text = 'กรุณารอสักครู่ ห้ามปิดหน้าต่างนี้') {
    return Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
        customClass: {
            popup: 'swal2-rayongcoop-popup rounded-4 shadow-lg border-0'
        }
    });
}

/**
 * Close Loading Dialog
 */
function closeLoading() {
    Swal.close();
}

/**
 * Network Error Dialog
 */
function showNetworkError() {
    return showError('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตของท่าน แล้วลองใหม่อีกครั้ง');
}

/**
 * Permission Denied Dialog
 */
function showPermissionDenied() {
    return showError('ไม่มีสิทธิ์เข้าถึง (403)', 'คุณไม่มีสิทธิ์ในการเข้าถึงหรือทำรายการในส่วนนี้ กรุณาติดต่อผู้ดูแลระบบ');
}

/**
 * Session Expired Dialog
 */
function showSessionExpired(redirectUrl = '/admin/login') {
    return SwalApp.fire({
        icon: 'warning',
        iconColor: '#FFC107',
        title: 'เซสชันหมดอายุ',
        text: 'เซสชันการใช้งานของคุณหมดอายุแล้ว กรุณาเข้าสู่ระบบใหม่อีกครั้ง',
        confirmButtonText: 'เข้าสู่ระบบใหม่',
        allowOutsideClick: false
    }).then(() => {
        window.location.href = redirectUrl;
    });
}
