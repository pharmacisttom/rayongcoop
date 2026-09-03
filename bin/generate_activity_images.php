<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$actDir = dirname(__DIR__) . '/public/assets/img/activities';
if (!is_dir($actDir)) {
    @mkdir($actDir, 0775, true);
}

$activities = [
    [
        'file' => 'activity_1.jpg',
        'title' => 'ANNUAL GENERAL MEETING 2568',
        'sub' => 'Scholarships Awarding & Member Gathering',
        'color1' => [7, 59, 116],
        'color2' => [11, 94, 215],
        'accent' => [255, 215, 0],
        'badge' => 'ANNUAL MEETING',
    ],
    [
        'file' => 'activity_2.jpg',
        'title' => 'FINANCIAL PLANNING SEMINAR',
        'sub' => 'Retirement & Investment Wealth Masterclass',
        'color1' => [15, 76, 129],
        'color2' => [2, 132, 199],
        'accent' => [250, 204, 21],
        'badge' => 'SEMINAR & WORKSHOP',
    ],
    [
        'file' => 'activity_3.jpg',
        'title' => 'RAYONGCOOP MOBILE OUTREACH',
        'sub' => 'On-site Welfare Service & Consultation',
        'color1' => [12, 74, 110],
        'color2' => [14, 165, 233],
        'accent' => [255, 255, 255],
        'badge' => 'MEMBER SERVICES',
    ],
    [
        'file' => 'activity_4.jpg',
        'title' => 'CSR & MEDICAL EQUIPMENT DONATION',
        'sub' => 'Supporting Public Health Community',
        'color1' => [6, 95, 70],
        'color2' => [16, 185, 129],
        'accent' => [255, 215, 0],
        'badge' => 'CSR COMMUNITY',
    ],
    [
        'file' => 'activity_5.jpg',
        'title' => 'ANNUAL HEALTH CHECK-UP PROGRAM',
        'sub' => 'Free Healthcare Screening For Members',
        'color1' => [159, 18, 57],
        'color2' => [225, 29, 72],
        'accent' => [255, 255, 255],
        'badge' => 'HEALTHCARE & WELFARE',
    ],
    [
        'file' => 'activity_6.jpg',
        'title' => 'OUTSTANDING MEMBER AWARDS 2568',
        'sub' => 'Financial Discipline & Savings Recognition',
        'color1' => [120, 53, 15],
        'color2' => [217, 119, 6],
        'accent' => [255, 255, 255],
        'badge' => 'HONOR & AWARDS',
    ],
];

foreach ($activities as $act) {
    $width = 600;
    $height = 400;
    $im = imagecreatetruecolor($width, $height);

    // Gradient background
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = (int)($act['color1'][0] + ($act['color2'][0] - $act['color1'][0]) * $ratio);
        $g = (int)($act['color1'][1] + ($act['color2'][1] - $act['color1'][1]) * $ratio);
        $b = (int)($act['color1'][2] + ($act['color2'][2] - $act['color1'][2]) * $ratio);
        $color = imagecolorallocate($im, $r, $g, $b);
        imageline($im, 0, $y, $width, $y, $color);
    }

    // Shapes
    $glow = imagecolorallocatealpha($im, 255, 255, 255, 115);
    imagefilledellipse($im, (int)($width * 0.85), (int)($height * 0.25), 260, 260, $glow);
    imagefilledellipse($im, (int)($width * 0.1), (int)($height * 0.8), 200, 200, $glow);

    // Badge Pill
    $badgeBg = imagecolorallocatealpha($im, 0, 0, 0, 80);
    imagefilledrectangle($im, 30, 30, 30 + strlen($act['badge']) * 10 + 20, 60, $badgeBg);
    $accentColor = imagecolorallocate($im, $act['accent'][0], $act['accent'][1], $act['accent'][2]);
    imagestring($im, 3, 40, 38, $act['badge'], $accentColor);

    // Central Card
    $cardBg = imagecolorallocatealpha($im, 255, 255, 255, 105);
    imagefilledrectangle($im, 40, 160, $width - 40, 340, $cardBg);

    // Typography
    $white = imagecolorallocate($im, 255, 255, 255);
    $gold = imagecolorallocate($im, 255, 215, 0);

    imagestring($im, 5, 60, 195, $act['title'], $white);
    imagestring($im, 4, 60, 235, $act['sub'], $gold);
    imagestring($im, 3, 60, 280, "Rayong Public Health Savings Cooperative Limited", $white);

    $outPath = "{$actDir}/{$act['file']}";
    imagejpeg($im, $outPath, 92);
    imagedestroy($im);
    echo "✓ Generated Activity Image: {$outPath}\n";
}

echo "All 6 activity images generated.\n";
