-- =========================================================================
-- RAYONGCOOP DIGITAL PORTAL - Production Database Schema
-- Rayong Public Health Savings and Credit Cooperative Limited
-- =========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. USERS
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` VARCHAR(36) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    `two_factor_secret` VARCHAR(255) DEFAULT NULL,
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `last_login_at` DATETIME DEFAULT NULL,
    `last_login_ip` VARCHAR(45) DEFAULT NULL,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_users_status` (`status`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ROLES
CREATE TABLE IF NOT EXISTS `roles` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PERMISSIONS
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `module` VARCHAR(50) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_module_action` (`module`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. USER_ROLES
CREATE TABLE IF NOT EXISTS `user_roles` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_user_role` (`user_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ROLE_PERMISSIONS
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_role_permission` (`role_id`, `permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. HERO SLIDES
CREATE TABLE IF NOT EXISTS `hero_slides` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `subtitle` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `desktop_image` VARCHAR(255) NOT NULL,
    `mobile_image` VARCHAR(255) DEFAULT NULL,
    `button_text` VARCHAR(100) DEFAULT NULL,
    `button_url` VARCHAR(255) DEFAULT NULL,
    `button_target` ENUM('_self', '_blank') NOT NULL DEFAULT '_self',
    `text_position` ENUM('left', 'center', 'right') NOT NULL DEFAULT 'left',
    `text_alignment` ENUM('left', 'center', 'right') NOT NULL DEFAULT 'left',
    `overlay_opacity` DECIMAL(3,2) NOT NULL DEFAULT 0.40,
    `sort_order` INT NOT NULL DEFAULT 0,
    `priority` INT NOT NULL DEFAULT 0,
    `start_at` DATETIME DEFAULT NULL,
    `end_at` DATETIME DEFAULT NULL,
    `status` ENUM('draft', 'active', 'inactive') NOT NULL DEFAULT 'active',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_hero_status_dates` (`status`, `start_at`, `end_at`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. POPUPS
CREATE TABLE IF NOT EXISTS `popups` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `type` ENUM('image', 'image_text', 'text_only', 'video', 'announcement', 'emergency', 'promotion') NOT NULL DEFAULT 'image',
    `content` LONGTEXT DEFAULT NULL,
    `desktop_image` VARCHAR(255) DEFAULT NULL,
    `mobile_image` VARCHAR(255) DEFAULT NULL,
    `video_url` VARCHAR(255) DEFAULT NULL,
    `button_text` VARCHAR(100) DEFAULT NULL,
    `button_url` VARCHAR(255) DEFAULT NULL,
    `button_target` ENUM('_self', '_blank') NOT NULL DEFAULT '_self',
    `display_mode` ENUM('load', 'delay', 'scroll', 'exit') NOT NULL DEFAULT 'load',
    `delay_seconds` INT NOT NULL DEFAULT 0,
    `scroll_percent` INT NOT NULL DEFAULT 50,
    `frequency` ENUM('always', 'session', 'daily', 'x_days', 'do_not_show') NOT NULL DEFAULT 'session',
    `frequency_days` INT NOT NULL DEFAULT 7,
    `priority` ENUM('critical', 'high', 'normal', 'low') NOT NULL DEFAULT 'normal',
    `start_at` DATETIME DEFAULT NULL,
    `end_at` DATETIME DEFAULT NULL,
    `status` ENUM('draft', 'active', 'inactive') NOT NULL DEFAULT 'active',
    `impressions_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `clicks_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_popups_status_prio` (`status`, `priority`, `start_at`, `end_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. POPUP PAGES
CREATE TABLE IF NOT EXISTS `popup_pages` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `popup_id` BIGINT UNSIGNED NOT NULL,
    `page_path` VARCHAR(255) NOT NULL DEFAULT '*',
    `device_target` ENUM('all', 'desktop', 'mobile') NOT NULL DEFAULT 'all',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`popup_id`) REFERENCES `popups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. POPUP EVENTS
CREATE TABLE IF NOT EXISTS `popup_events` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `popup_id` BIGINT UNSIGNED NOT NULL,
    `event_type` ENUM('impression', 'click', 'close', 'cta_click', 'dismiss') NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `device_type` VARCHAR(20) DEFAULT NULL,
    `page_path` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`popup_id`) REFERENCES `popups` (`id`) ON DELETE CASCADE,
    INDEX `idx_pe_popup_event` (`popup_id`, `event_type`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. DEPOSIT PRODUCTS
CREATE TABLE IF NOT EXISTS `deposit_products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `short_description` VARCHAR(255) DEFAULT NULL,
    `full_description` LONGTEXT DEFAULT NULL,
    `interest_rate` DECIMAL(5,3) NOT NULL DEFAULT 0.000,
    `min_deposit` DECIMAL(15,2) DEFAULT NULL,
    `max_deposit` DECIMAL(15,2) DEFAULT NULL,
    `withdrawal_condition` TEXT DEFAULT NULL,
    `eligibility` TEXT DEFAULT NULL,
    `required_documents` TEXT DEFAULT NULL,
    `faqs` JSON DEFAULT NULL,
    `related_forms` JSON DEFAULT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_deposit_status` (`status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. LOAN PRODUCTS
CREATE TABLE IF NOT EXISTS `loan_products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` ENUM('emergency', 'general', 'special', 'welfare', 'housing') NOT NULL DEFAULT 'general',
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `short_description` VARCHAR(255) DEFAULT NULL,
    `full_description` LONGTEXT DEFAULT NULL,
    `interest_rate` DECIMAL(5,3) NOT NULL DEFAULT 0.000,
    `max_loan_limit` DECIMAL(15,2) DEFAULT NULL,
    `max_term_months` INT NOT NULL DEFAULT 120,
    `calculation_type` ENUM('flat', 'effective') NOT NULL DEFAULT 'effective',
    `eligibility` TEXT DEFAULT NULL,
    `guarantor_requirement` TEXT DEFAULT NULL,
    `collateral` TEXT DEFAULT NULL,
    `documents` TEXT DEFAULT NULL,
    `conditions` TEXT DEFAULT NULL,
    `faqs` JSON DEFAULT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_calculator_enabled` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_loan_status_cat` (`status`, `category`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. INTEREST RATES
CREATE TABLE IF NOT EXISTS `interest_rates` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_type` ENUM('deposit', 'loan') NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `rate` DECIMAL(5,3) NOT NULL,
    `min_amount` DECIMAL(15,2) DEFAULT NULL,
    `max_amount` DECIMAL(15,2) DEFAULT NULL,
    `condition_note` VARCHAR(255) DEFAULT NULL,
    `effective_date` DATE NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_rates_type_status` (`product_type`, `status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. INTEREST RATE HISTORY
CREATE TABLE IF NOT EXISTS `interest_rate_history` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `interest_rate_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `old_rate` DECIMAL(5,3) NOT NULL,
    `new_rate` DECIMAL(5,3) NOT NULL,
    `effective_date` DATE NOT NULL,
    `changed_by` BIGINT UNSIGNED NOT NULL,
    `approved_by` BIGINT UNSIGNED DEFAULT NULL,
    `note` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`interest_rate_id`) REFERENCES `interest_rates` (`id`) ON DELETE CASCADE,
    INDEX `idx_rate_hist_date` (`effective_date`, `interest_rate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. LOAN SCHEDULES
CREATE TABLE IF NOT EXISTS `loan_schedules` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `year` INT NOT NULL,
    `month` INT NOT NULL,
    `submission_deadline` DATE NOT NULL,
    `approval_date` DATE NOT NULL,
    `disbursement_date` DATE NOT NULL,
    `status` ENUM('active', 'closed') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. WELFARE
CREATE TABLE IF NOT EXISTS `welfare` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(100) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `short_description` VARCHAR(255) DEFAULT NULL,
    `full_description` LONGTEXT DEFAULT NULL,
    `benefit_amount` VARCHAR(255) DEFAULT NULL,
    `eligibility` TEXT DEFAULT NULL,
    `required_documents` TEXT DEFAULT NULL,
    `application_process` TEXT DEFAULT NULL,
    `contact_info` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_welfare_status` (`status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. DOCUMENT CATEGORIES
CREATE TABLE IF NOT EXISTS `document_categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. DOCUMENTS
CREATE TABLE IF NOT EXISTS `documents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `document_number` VARCHAR(100) DEFAULT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `file_type` VARCHAR(50) NOT NULL DEFAULT 'application/pdf',
    `year` INT DEFAULT NULL,
    `tag` VARCHAR(100) DEFAULT NULL,
    `effective_date` DATE DEFAULT NULL,
    `download_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE,
    INDEX `idx_docs_cat_year_status` (`category_id`, `year`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. NEWS CATEGORIES
CREATE TABLE IF NOT EXISTS `news_categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. NEWS
CREATE TABLE IF NOT EXISTS `news` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `summary` TEXT DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    `cover_image` VARCHAR(255) DEFAULT NULL,
    `gallery_images` JSON DEFAULT NULL,
    `attachments` JSON DEFAULT NULL,
    `tags` VARCHAR(255) DEFAULT NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `views_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `publish_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `expire_at` DATETIME DEFAULT NULL,
    `workflow_status` ENUM('draft', 'submitted', 'under_review', 'approved', 'published', 'rejected', 'archived') NOT NULL DEFAULT 'published',
    `author_id` BIGINT UNSIGNED DEFAULT NULL,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE CASCADE,
    INDEX `idx_news_wf_pub` (`workflow_status`, `publish_at`, `is_pinned`, `is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. ANNOUNCEMENTS
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `link_url` VARCHAR(255) DEFAULT NULL,
    `link_text` VARCHAR(100) DEFAULT NULL,
    `priority` ENUM('important', 'urgent', 'general', 'loan', 'welfare', 'meeting', 'procurement') NOT NULL DEFAULT 'general',
    `display_type` ENUM('top_bar', 'inline_alert', 'modal') NOT NULL DEFAULT 'top_bar',
    `start_at` DATETIME DEFAULT NULL,
    `end_at` DATETIME DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_announcements_active_dates` (`is_active`, `start_at`, `end_at`, `priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. BOARDS
CREATE TABLE IF NOT EXISTS `boards` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `position` VARCHAR(150) NOT NULL,
    `role_type` ENUM('director', 'auditor', 'advisor', 'manager') NOT NULL DEFAULT 'director',
    `term_years` VARCHAR(50) DEFAULT NULL,
    `term_number` INT DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_board_type_sort` (`role_type`, `sort_order`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. STAFF
CREATE TABLE IF NOT EXISTS `staff` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `position` VARCHAR(150) NOT NULL,
    `department` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_staff_dept_sort` (`department`, `sort_order`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. ESERVICE LINKS
CREATE TABLE IF NOT EXISTS `eservice_links` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `url` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(50) NOT NULL DEFAULT 'bi-globe',
    `category` VARCHAR(50) NOT NULL DEFAULT 'general',
    `is_internal` TINYINT(1) NOT NULL DEFAULT 0,
    `open_new_tab` TINYINT(1) NOT NULL DEFAULT 1,
    `confirm_before_redirect` TINYINT(1) NOT NULL DEFAULT 1,
    `is_maintenance` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_eservice_sort` (`sort_order`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. FAQS
CREATE TABLE IF NOT EXISTS `faqs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(50) NOT NULL DEFAULT 'general',
    `question` VARCHAR(255) NOT NULL,
    `answer` TEXT NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. COMPLAINTS
CREATE TABLE IF NOT EXISTS `complaints` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_no` VARCHAR(30) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `complainant_name` VARCHAR(100) DEFAULT NULL,
    `complainant_phone` VARCHAR(50) DEFAULT NULL,
    `complainant_email` VARCHAR(100) DEFAULT NULL,
    `attachment` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('received', 'under_review', 'assigned', 'in_progress', 'answered', 'closed', 'rejected') NOT NULL DEFAULT 'received',
    `priority` ENUM('low', 'normal', 'high', 'urgent') NOT NULL DEFAULT 'normal',
    `assigned_officer_id` BIGINT UNSIGNED DEFAULT NULL,
    `response_message` TEXT DEFAULT NULL,
    `answered_at` DATETIME DEFAULT NULL,
    `closed_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_complaints_ticket` (`ticket_no`),
    INDEX `idx_complaints_status` (`status`, `priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. COMPLAINT LOGS
CREATE TABLE IF NOT EXISTS `complaint_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `complaint_id` BIGINT UNSIGNED NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `note` TEXT DEFAULT NULL,
    `officer_id` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. COOKIE CATEGORIES
CREATE TABLE IF NOT EXISTS `cookie_categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name_th` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `description_th` TEXT NOT NULL,
    `description_en` TEXT NOT NULL,
    `is_required` TINYINT(1) NOT NULL DEFAULT 0,
    `default_state` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28. COOKIE CONSENTS
CREATE TABLE IF NOT EXISTS `cookie_consents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `anonymous_consent_id` VARCHAR(64) NOT NULL UNIQUE,
    `consent_version` VARCHAR(20) NOT NULL DEFAULT '1.0',
    `necessary` TINYINT(1) NOT NULL DEFAULT 1,
    `functional` TINYINT(1) NOT NULL DEFAULT 0,
    `analytics` TINYINT(1) NOT NULL DEFAULT 0,
    `marketing` TINYINT(1) NOT NULL DEFAULT 0,
    `third_party` TINYINT(1) NOT NULL DEFAULT 0,
    `ip_hash` VARCHAR(64) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `consented_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_cookie_consents_anon` (`anonymous_consent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 29. PRIVACY POLICY VERSIONS
CREATE TABLE IF NOT EXISTS `privacy_policy_versions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `version` VARCHAR(20) NOT NULL,
    `policy_type` ENUM('privacy_policy', 'cookie_policy', 'pdpa_notice', 'terms_of_use') NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` LONGTEXT NOT NULL,
    `effective_date` DATE NOT NULL,
    `require_reconsent` TINYINT(1) NOT NULL DEFAULT 0,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 30. THIRD PARTY SCRIPTS
CREATE TABLE IF NOT EXISTS `third_party_scripts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `category` ENUM('analytics', 'marketing', 'functional', 'third_party') NOT NULL,
    `script_tag` TEXT NOT NULL,
    `location` ENUM('head', 'body_top', 'body_bottom') NOT NULL DEFAULT 'head',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 31. FINANCIAL STATISTICS
CREATE TABLE IF NOT EXISTS `financial_statistics` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `year` INT NOT NULL,
    `month` INT NOT NULL,
    `total_members` INT NOT NULL DEFAULT 0,
    `share_capital` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_deposits` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_loans` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_assets` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `reserve_fund` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `npl_percentage` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `net_profit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `dividend_rate` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `liquidity_ratio` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `note` TEXT DEFAULT NULL,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_fin_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 32. MEDIA
CREATE TABLE IF NOT EXISTS `media` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `file_size` BIGINT UNSIGNED NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `disk` VARCHAR(50) NOT NULL DEFAULT 'local',
    `folder` VARCHAR(50) NOT NULL DEFAULT 'general',
    `alt_text` VARCHAR(255) DEFAULT NULL,
    `caption` VARCHAR(255) DEFAULT NULL,
    `uploaded_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    INDEX `idx_media_folder` (`folder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 33. SITE SETTINGS
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` LONGTEXT DEFAULT NULL,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `is_public` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_group` (`group`, `key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 34. AUDIT LOGS
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `module` VARCHAR(50) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `record_id` VARCHAR(50) DEFAULT NULL,
    `old_values` JSON DEFAULT NULL,
    `new_values` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_module_record` (`module`, `record_id`, `created_at`),
    INDEX `idx_audit_user` (`user_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 35. LOGIN LOGS
CREATE TABLE IF NOT EXISTS `login_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `email` VARCHAR(100) NOT NULL,
    `status` ENUM('success', 'failed', 'locked_out', '2fa_failed') NOT NULL,
    `failure_reason` VARCHAR(255) DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_login_logs_email` (`email`, `created_at`),
    INDEX `idx_login_logs_ip` (`ip_address`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 36. SYSTEM LOGS
CREATE TABLE IF NOT EXISTS `system_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `level` ENUM('info', 'warning', 'error', 'critical') NOT NULL DEFAULT 'info',
    `message` TEXT NOT NULL,
    `context` JSON DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
