<?php

declare(strict_types=1);

/**
 * RayongCoop Digital Portal - Front Controller
 * Rayong Public Health Savings and Credit Cooperative Limited
 */

// Load Composer Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\App;

// Bootstrap and run Application
$app = new App();
$app->run();
