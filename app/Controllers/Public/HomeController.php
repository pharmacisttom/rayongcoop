<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        // 1. Hero Slides
        $heroSlides = Database::query("SELECT * FROM hero_slides WHERE status = 'active' AND (start_at IS NULL OR start_at <= NOW()) AND (end_at IS NULL OR end_at >= NOW()) ORDER BY priority DESC, sort_order ASC");

        // 2. Deposit Rates & Loan Rates
        $depositRates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'deposit' AND status = 'active' ORDER BY sort_order ASC LIMIT 4");
        $loanRates = Database::query("SELECT * FROM interest_rates WHERE product_type = 'loan' AND status = 'active' ORDER BY sort_order ASC LIMIT 4");

        // 3. Featured News & Announcements
        $latestNews = Database::query("SELECT n.*, c.name as category_name FROM news n JOIN news_categories c ON n.category_id = c.id WHERE n.workflow_status = 'published' AND (n.publish_at IS NULL OR n.publish_at <= NOW()) ORDER BY n.is_pinned DESC, n.publish_at DESC LIMIT 6");

        // 4. Featured Deposit & Loan Products
        $featuredDeposits = Database::query("SELECT * FROM deposit_products WHERE is_featured = 1 AND status = 'active' ORDER BY sort_order ASC LIMIT 3");
        $featuredLoans = Database::query("SELECT * FROM loan_products WHERE is_featured = 1 AND status = 'active' ORDER BY sort_order ASC LIMIT 3");

        // 5. Executive Statistics (Latest)
        $latestStats = Database::first("SELECT * FROM financial_statistics ORDER BY year DESC, month DESC LIMIT 1");

        // 6. E-Service Quick Links
        $eservices = Database::query("SELECT * FROM eservice_links WHERE status = 'active' ORDER BY sort_order ASC LIMIT 6");

        // 7. Cooperative & Member Activities
        $activities = [
            [
                'id' => 1,
                'title' => 'การประชุมใหญ่สามัญประจำปี 2568 และมอบทุนการศึกษาบุตรสมาชิก',
                'category' => 'meeting',
                'category_name' => 'ประชุมใหญ่ & สัมมนา',
                'date' => '2026-08-25',
                'location' => 'ห้องประชุมใหญ่ รพ.ระยอง',
                'image' => 'activities/activity_1.jpg',
                'description' => 'สหกรณ์ออมทรัพย์สาธารณสุขระยอง จำกัด จัดการประชุมใหญ่สามัญประจำปี พร้อมพิธีมอบทุนการศึกษาแก่บุตรสมาชิกสหกรณ์ เพื่อส่งเสริมการศึกษาและอนาคตเยาวชน',
            ],
            [
                'id' => 2,
                'title' => 'โครงการอบรมวางแผนทางการเงินและการลงทุนเพื่อสมาชิกวัยเกษียณ',
                'category' => 'training',
                'category_name' => 'ประชุมใหญ่ & สัมมนา',
                'date' => '2026-07-18',
                'location' => 'โรงแรมสตาร์ คอนเวนชั่น ระยอง',
                'image' => 'activities/activity_2.jpg',
                'description' => 'ส่งเสริมความรู้การบริหารจัดการเงินออม การลงทุน และการวางแผนภาษี เพื่อคุณภาพชีวิตที่มั่นคงและมีความสุขหลังเกษียณอายุราชการ',
            ],
            [
                'id' => 3,
                'title' => 'สหกรณ์สัญจรพบสมาชิก มอบสวัสดิการและให้คำปรึกษาทางการเงิน ณ รพ.แกลง',
                'category' => 'welfare',
                'category_name' => 'สวัสดิการ & สมาชิก',
                'date' => '2026-06-30',
                'location' => 'โรงพยาบาลแกลง จ.ระยอง',
                'image' => 'activities/activity_3.jpg',
                'description' => 'ทีมงานฝ่ายจัดการออกหน่วยสัญจรรับคำขอกู้เงิน เปิดบัญชีเงินฝาก และให้บริการสมาชิกถึงหน่วยงาน เพื่อความสะดวกรวดเร็ว',
            ],
            [
                'id' => 4,
                'title' => 'สหกรณ์ร่วมบริจาคเงินและสนับสนุนอุปกรณ์การแพทย์เพื่อสังคม (CSR)',
                'category' => 'csr',
                'category_name' => 'กิจกรรมเพื่อสังคม (CSR)',
                'date' => '2026-05-15',
                'location' => 'รพ.สต. ในเขตจังหวัดระยอง',
                'image' => 'activities/activity_4.jpg',
                'description' => 'สนับสนุนกองทุนพัฒนาสถานพยาบาลและอุปกรณ์การแพทย์ เพื่อยกระดับการให้บริการสาธารณสุขแก่ประชาชนในพื้นที่จังหวัดระยอง',
            ],
            [
                'id' => 5,
                'title' => 'กิจกรรมตรวจสุขภาพประจำปีฟรี สำหรับสมาชิกสหกรณ์',
                'category' => 'welfare',
                'category_name' => 'สวัสดิการ & สมาชิก',
                'date' => '2026-04-10',
                'location' => 'สำนักงานสหกรณ์ออมทรัพย์สาธารณสุขระยอง',
                'image' => 'activities/activity_5.jpg',
                'description' => 'ให้บริการตรวจเช็คสุขภาพ ตรวจเลือด และเอกซเรย์ปอดฟรี ประจำปี เพื่อส่งเสริมสุขภาพและดูแลคุณภาพชีวิตสมาชิกอย่างใกล้ชิด',
            ],
            [
                'id' => 6,
                'title' => 'พิธีมอบของที่ระลึกและรางวัลสมาชิกผู้มีวินัยทางการเงินดีเด่น',
                'category' => 'welfare',
                'category_name' => 'สวัสดิการ & สมาชิก',
                'date' => '2026-03-20',
                'location' => 'สำนักงานใหญ่ สอ.สธ.ระยอง',
                'image' => 'activities/activity_6.jpg',
                'description' => 'เชิดชูเกียรติสมาชิกที่มียอดเงินออมสม่ำเสมอและมีวินัยในการชำระหนี้ตรงเวลาอย่างต่อเนื่อง เพื่อสร้างวัฒนธรรมทางการเงินที่เข้มแข็ง',
            ],
        ];

        $this->render('public.home', [
            'title' => 'หน้าแรก',
            'heroSlides' => $heroSlides,
            'depositRates' => $depositRates,
            'loanRates' => $loanRates,
            'latestNews' => $latestNews,
            'featuredDeposits' => $featuredDeposits,
            'featuredLoans' => $featuredLoans,
            'latestStats' => $latestStats,
            'eservices' => $eservices,
            'activities' => $activities,
        ]);
    }
}
