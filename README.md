# RayongCoop Digital Portal
### Main Website & Financial Cooperative CMS (Production-Ready)
**สหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด (Rayong Public Health Savings and Credit Cooperative Limited)**

---

## 🌟 ภาพรวมระบบ (System Overview)
**RayongCoop Digital Portal** เป็นระบบเว็บแอปพลิเคชันและระบบบริหารจัดการข้อมูล (CMS) ระดับสถาบันการเงิน (Financial Institution Standard) ที่ได้รับการออกแบบและพัฒนาขึ้นใหม่ทั้งหมดสำหรับบุคลากรสาธารณสุขจังหวัดระยอง โดยเน้นความมั่นคง ปลอดภัย ทันสมัย โปร่งใส และเข้าถึงง่าย (1–2 Click Access)

### คุณสมบัติเด่น (Key Highlights)
- 🏛️ **Modern Financial Design System**: ดีไซน์ระดับสถาบันการเงิน โทนสี Navy `#073B74`, Blue `#0B5ED7`, Light Blue `#EAF4FF`, Gold Accent `#C99A2E` และฟอนต์ Noto Sans Thai / Inter
- 🔐 **Enterprise Cybersecurity**:
  - การเข้ารหัสรหัสผ่านด้วยอัลกอริทึม **Argon2id**
  - ระบบยืนยันตัวตนสองขั้นตอน **2FA (RFC 6238 TOTP Authenticator)**
  - ป้องกัน CSRF Token ในทุกคำขอ POST/PUT/DELETE
  - ป้องกัน XSS, SQL Injection (PDO Prepared Statements 100%) และ Rate Limiting ป้องกัน Brute Force
- 🛡️ **PDPA & Privacy-by-Design Cookie CMP**:
  - ระบบจัดการความยินยอมคุกกี้ตามมาตรฐาน PDPA
  - บล็อกสคริปต์บุคคลภายนอก (Google Analytics / Marketing / Tracking) จนกว่าจะได้รับความยินยอม
  - ศูนย์ตั้งค่าคุกกี้ (Cookie Preference Center) และบันทึก Anonymous Consent Log
- 📢 **Popup Campaign Management & Queue**:
  - ระบบป็อปอัปพร้อมจัดลำดับความสำคัญ (Critical, High, Normal, Low)
  - รองรับเงื่อนไขการแสดงผล (Load, Delay, Scroll %, Exit Intent) และความถี่ (Session, Daily, X Days)
- 📊 **Executive Financial Dashboard & Interactive Calculator**:
  - แดชบอร์ดสรุปสถิติทางการเงินสำหรับผู้บริหาร (สินทรัพย์, ทุนเรือนหุ้น, เงินฝาก, สินเชื่อ, NPL, สภาพคล่อง)
  - โปรแกรมคำนวณเงินกู้แบบลดต้นลดดอก (Amortization Schedule) พร้อมตารางผ่อนชำระละเอียด
- 🔄 **E-Service Gateway & Legacy Safe Integration**:
  - เชื่อมโยงระบบบริการสมาชิกเดิม (ตรวจสอบหุ้น, เงินปันผล, สสธท., กสธท.) อย่างปลอดภัย
  - ระบบ SweetAlert2 แจ้งเตือนยืนยันก่อนเปิดลิงก์ภายนอก
- 📝 **Maker-Checker Workflow & Immutable Audit Trail**:
  - บันทึกประวัติการเปลี่ยนแปลงข้อมูลทุกรายการ (Old vs New, IP, วันเวลา) ไม่สามารถแก้ไขหรือลบได้
- 🚨 **Central SweetAlert2 Standard**:
  - กำหนดค่ามาตรฐานกลาง (`swal-config.js`) สำหรับ Alert, Dialog, Confirm, Toast, Loading ทั่วทั้งระบบ

---

## 🛠️ Technology Stack
- **Backend**: PHP 8.2+ / PHP 8.4+ (Clean MVC + Service Layer + Repository Pattern)
- **Database**: MySQL 8.0+ / MariaDB 10.4+ (36 Normalized Tables with Foreign Keys & Indexes)
- **Frontend**: HTML5, Vanilla CSS3 Custom Tokens, JavaScript ES6+, Bootstrap 5.3, Bootstrap Icons, Swiper.js, Chart.js, DataTables, SweetAlert2 v11+
- **CLI Utility**: `php bin/console` (Database migration, Seeding, Cron tasks, Backup engine)
- **Server Support**: Ubuntu 24.04 LTS, Nginx, PHP-FPM 8.4, MySQL 8+, HTTPS/SSL

---

## 🚀 เอกสารคู่มือระบบ (Documentation)
1. 📖 [INSTALLATION.md](file:///c:/xampp/htdocs/rayongcoop/INSTALLATION.md) — คู่มือการติดตั้งระบบบน Local Development & Test Server
2. 🚀 [DEPLOYMENT.md](file:///c:/xampp/htdocs/rayongcoop/DEPLOYMENT.md) — คู่มือการนำขึ้นระบบ Production (Ubuntu 24.04 + Nginx + PHP-FPM 8.4)
3. 🔒 [SECURITY.md](file:///c:/xampp/htdocs/rayongcoop/SECURITY.md) — นโยบายความปลอดภัย มาตรฐาน 2FA และ PDPA
4. 💾 [BACKUP.md](file:///c:/xampp/htdocs/rayongcoop/BACKUP.md) — คู่มือการสำรองข้อมูลและกู้คืนระบบ (Disaster Recovery)
5. 🔄 [MIGRATION.md](file:///c:/xampp/htdocs/rayongcoop/MIGRATION.md) — แผนและกลยุทธ์การถ่ายโอนข้อมูลจากระบบเดิมโดยไม่กระทบบริการ
