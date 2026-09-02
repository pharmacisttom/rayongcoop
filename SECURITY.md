# Security Policies & Hardening — RayongCoop Digital Portal

เอกสารมาตรฐานความมั่นคงปลอดภัยไซเบอร์และมาตรการป้องกันข้อมูลตามหลัก PDPA สำหรับสหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด

---

## 1. การจัดการรหัสผ่านและ 2FA (Authentication & 2FA)
- **Password Hashing**: ใช้อัลกอริทึม **Argon2id** (`PASSWORD_ARGON2ID`) พร้อมพารามิเตอร์ `memory_cost=65536`, `time_cost=4`, `threads=2` ซึ่งเป็นมาตรฐานสูงสุดของ OWASP
- **Two-Factor Authentication (2FA)**: มาตรฐาน **RFC 6238 Time-Based One-Time Password (TOTP)** เข้ากันได้กับ Google Authenticator และ Microsoft Authenticator
- **Account Lockout & Rate Limiting**: ล็อกบัญชีชั่วคราวเมื่อกรอกรหัสผ่านผิดเกิน 5 ครั้งในรอบ 15 นาที พร้อมบันทึกลงตาราง `login_logs`

---

## 2. การรักษาความปลอดภัยเซสชัน (Session Security)
- `HttpOnly`: ป้องกัน JavaScript จากการเข้าถึง Session Cookie เพื่อป้องกัน Session Hijacking
- `SameSite=Lax`: ป้องกันการโจมตีข้ามไซต์ (CSRF)
- `Idle Timeout`: ตัดการเชื่อมต่ออัตโนมัติเมื่อไม่มีการใช้งานเกิน 30 นาที
- `Session Rotation`: สลับหมายเลข Session ID ใหม่อัตโนมัติทุก 30 นาที และทุกครั้งที่มีการเปลี่ยนสถานะการล็อกอิน

---

## 3. การป้องกันช่องโหว่เว็บแอปพลิเคชัน (OWASP Top 10 Protections)
- **SQL Injection**: ใช้ **PDO Prepared Statements 100%** ห้ามต่อ SQL String จาก Input
- **Cross-Site Scripting (XSS)**: ฟังก์ชัน `e()` สำหรับ Encode Output และ HTML Purifier สำหรับ Rich Text
- **CSRF Protection**: ตรวจสอบโทเค็น CSRF ในทุกคำขอ `POST`, `PUT`, `PATCH`, `DELETE` ทั้ง Form submit และ Fetch API/AJAX (`X-CSRF-TOKEN`)
- **Upload File Validation**:
  - ตรวจสอบ MIME type จริงผ่าน `finfo_file`
  - ตรวจสอบนามสกุลไฟล์ และบล็อกนามสกุลอันตราย เช่น `.php`, `.phar`, `.phtml`, `.exe`, `.sh`, `.bat`
  - สุ่มเปลี่ยนชื่อไฟล์ด้วย `random_bytes(16)` เพื่อป้องกัน Path Traversal

---

## 4. บันทึกประวัติการทำงาน (Immutable Audit Trail)
- ทุกการแก้ไข ลบ ปรับอัตราดอกเบี้ย หรืออนุมัติเนื้อหา จะถูกบันทึกอัตโนมัติลงในตาราง `audit_logs`
- บันทึกค่าเดิม (`old_values`), ค่าใหม่ (`new_values`), หมายเลขไอพี (`ip_address`), ข้อมูลเบราว์เซอร์ (`user_agent`) และเวลาที่เกิดรายการ
- ตาราง `audit_logs` ไม่มีคำสั่ง Delete ใน Admin CMS
