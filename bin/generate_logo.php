<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$imgDir = dirname(__DIR__) . '/public/assets/img';
if (!is_dir($imgDir)) {
    @mkdir($imgDir, 0775, true);
}

// Generate High-Res Logo PNG (512x512 with transparency)
$size = 512;
$im = imagecreatetruecolor($size, $size);
imagealphablending($im, false);
imagesavealpha($im, true);
$transparent = imagecolorallocatealpha($im, 255, 255, 255, 127);
imagefilledrectangle($im, 0, 0, $size, $size, $transparent);
imagealphablending($im, true);

$navy = imagecolorallocate($im, 7, 59, 116);
$royalBlue = imagecolorallocate($im, 11, 94, 215);
$gold = imagecolorallocate($im, 201, 154, 46);
$brightGold = imagecolorallocate($im, 255, 215, 0);
$white = imagecolorallocate($im, 255, 255, 255);
$healthGreen = imagecolorallocate($im, 16, 149, 93);

$center = (int)($size / 2);

// Outer Gold Rim
imagefilledellipse($im, $center, $center, 480, 480, $gold);
imagefilledellipse($im, $center, $center, 460, 460, $navy);

// Inner Circle
imagefilledellipse($im, $center, $center, 440, 440, $royalBlue);
imagefilledellipse($im, $center, $center, 380, 380, $white);

// Ministry of Public Health / Cooperative Symbol
// 1. Base Pedestal / Steps
imagefilledrectangle($im, $center - 110, $center + 80, $center + 110, $center + 100, $navy);
imagefilledrectangle($im, $center - 90, $center + 65, $center + 90, $center + 80, $gold);

// 2. Cooperative Bank Pillars (4 Pillars)
$pillarWidth = 22;
$pillarGap = 16;
$startX = $center - 75;
for ($i = 0; $i < 4; $i++) {
    $px = $startX + $i * ($pillarWidth + $pillarGap);
    imagefilledrectangle($im, $px, $center - 40, $px + $pillarWidth, $center + 65, $navy);
    imagefilledrectangle($im, $px + 4, $center - 36, $px + $pillarWidth - 4, $center + 61, $royalBlue);
}

// 3. Roof / Pediment (Triangle)
$roofPoints = [
    $center, $center - 110,
    $center - 110, $center - 40,
    $center + 110, $center - 40
];
imagefilledpolygon($im, $roofPoints, $navy);

// Inner Roof Triangle
$innerRoof = [
    $center, $center - 95,
    $center - 90, $center - 45,
    $center + 90, $center - 45
];
imagefilledpolygon($im, $innerRoof, $gold);

// Center Emblem (Circle with Public Health Cross / Star of Life)
imagefilledellipse($im, $center, $center - 65, 36, 36, $healthGreen);
imagefilledrectangle($im, $center - 4, $center - 77, $center + 4, $center - 53, $white);
imagefilledrectangle($im, $center - 12, $center - 69, $center + 12, $center - 61, $white);

// Text in Outer Ring
$fontPath = 'C:/Windows/Fonts/tahoma.ttf';
if (file_exists($fontPath)) {
    imagettftext($im, 18, 0, $center - 140, $center + 140, $brightGold, $fontPath, "สหกรณ์ออมทรัพย์สาธารณสุขระยอง");
} else {
    imagestring($im, 5, $center - 90, $center + 130, "RAYONG COOP", $brightGold);
}

// Save PNG & WebP
imagepng($im, "{$imgDir}/logo.png", 9);
if (function_exists('imagewebp')) {
    imagewebp($im, "{$imgDir}/logo.webp", 95);
}

// Also save to root img/ directory if exists or create it
$rootImgDir = dirname(__DIR__) . '/img';
if (!is_dir($rootImgDir)) {
    @mkdir($rootImgDir, 0775, true);
}
imagepng($im, "{$rootImgDir}/logo.png", 9);
if (function_exists('imagewebp')) {
    imagewebp($im, "{$rootImgDir}/logo.webp", 95);
}

imagedestroy($im);

// Generate Vector SVG Logo
$svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <defs>
    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#FFE57F;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#C99A2E;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="navyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#073B74;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#0B5ED7;stop-opacity:1" />
    </linearGradient>
    <filter id="dropShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-opacity="0.25"/>
    </filter>
  </defs>

  <!-- Outer Rings -->
  <circle cx="256" cy="256" r="240" fill="url(#goldGrad)" filter="url(#dropShadow)"/>
  <circle cx="256" cy="256" r="226" fill="url(#navyGrad)"/>
  <circle cx="256" cy="256" r="185" fill="#FFFFFF"/>

  <!-- Cooperative Emblem -->
  <!-- Pedestal -->
  <rect x="146" y="320" width="220" height="24" rx="4" fill="#073B74"/>
  <rect x="166" y="304" width="180" height="16" rx="3" fill="url(#goldGrad)"/>

  <!-- Pillars -->
  <rect x="176" y="200" width="24" height="104" rx="3" fill="#073B74"/>
  <rect x="216" y="200" width="24" height="104" rx="3" fill="#073B74"/>
  <rect x="272" y="200" width="24" height="104" rx="3" fill="#073B74"/>
  <rect x="312" y="200" width="24" height="104" rx="3" fill="#073B74"/>

  <rect x="180" y="204" width="16" height="96" rx="2" fill="#0B5ED7"/>
  <rect x="220" y="204" width="16" height="96" rx="2" fill="#0B5ED7"/>
  <rect x="276" y="204" width="16" height="96" rx="2" fill="#0B5ED7"/>
  <rect x="316" y="204" width="16" height="96" rx="2" fill="#0B5ED7"/>

  <!-- Pediment (Roof) -->
  <polygon points="256,130 136,200 376,200" fill="#073B74"/>
  <polygon points="256,148 156,192 356,192" fill="url(#goldGrad)"/>

  <!-- Medical Cross Circle -->
  <circle cx="256" cy="172" r="18" fill="#10B981"/>
  <rect x="252" y="160" width="8" height="24" rx="2" fill="#FFFFFF"/>
  <rect x="244" y="168" width="24" height="8" rx="2" fill="#FFFFFF"/>

  <!-- Base Label -->
  <text x="256" y="380" font-family="'Noto Sans Thai', 'Sarabun', sans-serif" font-size="20" font-weight="bold" fill="#073B74" text-anchor="middle">สอ.สธ.ระยอง</text>
  <text x="256" y="405" font-family="'Inter', sans-serif" font-size="13" font-weight="600" fill="#64748B" text-anchor="middle">EST. 2026</text>
</svg>
SVG;

file_put_contents("{$imgDir}/logo.svg", $svgContent);
file_put_contents("{$rootImgDir}/logo.svg", $svgContent);

echo "✓ Created logo.png, logo.webp, and logo.svg in public/assets/img/ and img/\n";
