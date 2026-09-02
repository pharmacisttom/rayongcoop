# Installation Guide — RayongCoop Digital Portal

คู่มือการติดตั้งระบบ RayongCoop Digital Portal สำหรับสภาพแวดล้อม Local Development (XAMPP / Laragon / Docker)

---

## 1. ข้อกำหนดของระบบ (System Requirements)
- **PHP**: เวอร์ชัน 8.2 หรือสูงกว่า (รองรับสมบูรณ์บน PHP 8.4)
  - ส่วนขยายที่ต้องเปิดใช้งาน: `pdo_mysql`, `openssl`, `mbstring`, `fileinfo`, `curl`, `json`, `gd`
- **Database**: MySQL 8.0+ หรือ MariaDB 10.4+
- **Composer**: เวอร์ชัน 2.x
- **Web Server**: Apache 2.4+ หรือ Nginx 1.24+

---

## 2. ขั้นตอนการติดตั้ง (Step-by-Step Installation)

### 2.1 ติดตั้ง Dependencies และ Autoloader
เปิด Terminal / PowerShell ในโฟลเดอร์โปรเจกต์:
```bash
composer install
composer dump-autoload
```

### 2.2 กำหนดค่าสภาพแวดล้อม (.env)
คัดลอกไฟล์ `.env.example` เป็น `.env` และตั้งค่าการเชื่อมต่อฐานข้อมูล:
```ini
APP_NAME="RayongCoop Digital Portal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/rayongcoop/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rayongcoop_db
DB_USERNAME=root
DB_PASSWORD=
```

### 2.3 สร้างฐานข้อมูลและรัน Migration & Seeder
ใช้คำสั่ง CLI `bin/console`:
```bash
# 1. สร้างฐานข้อมูล
php bin/console db:create

# 2. นำเข้าโครงสร้างตารางทั้งหมด (36 ตาราง)
php bin/console db:migrate

# 3. นำเข้าข้อมูลเริ่มต้น (Roles, 276 Permissions, Super Admin, Products, Rates, Welfare)
php bin/console db:seed
```

---

## 3. ข้อมูลผู้ดูแลระบบเริ่มต้น (Default Super Admin Account)
- **URL เข้าสู่ระบบ CMS**: `http://localhost/rayongcoop/public/admin/login`
- **อีเมล / Username**: `admin@rayongcoop.com` หรือ `admin`
- **รหัสผ่าน**: `Admin@RayongCoop2026!`
- **สิทธิ์การใช้งาน**: Super Admin (เข้าถึงได้ทุกโมดูล)
