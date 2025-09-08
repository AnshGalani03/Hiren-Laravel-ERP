-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 01, 2025 at 11:15 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

DROP TABLE IF EXISTS `dealers`;
CREATE TABLE IF NOT EXISTS `dealers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dealer_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `dealer_name`, `mobile_no`, `gst`, `address`, `account_no`, `account_name`, `ifsc`, `bank_name`, `created_at`, `updated_at`) VALUES
(2, 'Ansh', '7545856585', '24AAACH7409R2Z6', 'Surat, Gujarat', '1234567890', '45256585458565', 'ABC123DH', 'HDFC', '2025-09-01 01:28:14', '2025-09-01 01:28:23'),
(3, 'Hardik', '4525658545', '24AAACH7409R2Z7', 'Surat, Gujarat', '452545158565', 'Hardik Kukadiya', 'KHS52KH', 'BOB', '2025-09-01 01:30:51', '2025-09-01 01:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_contact_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhar_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pf` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `esic` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `designation`, `mobile_no`, `alt_contact_no`, `pan_no`, `aadhar_no`, `salary`, `pf`, `esic`, `bank_name`, `account_no`, `ifsc`, `created_at`, `updated_at`) VALUES
(2, 'Ansh', 'CEO', '4585652585', '4525658545', 'BAJPC4350M', '4525489562545', 50000.00, 'PF123456PF', 'ESIC456585ESIC', 'BOB', '452545658545', 'BOB564BOB', '2025-09-01 01:51:47', '2025-09-01 01:53:08'),
(3, 'Hardik', 'Founder', '45212565854', '4565854585', 'HK5245KPH6', '754852459525', 75000.00, 'PF545PJK5', 'ESIC5245ESIC', 'HDFC', '254512526585', 'HDFC5245', '2025-09-01 01:52:58', '2025-09-01 01:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `employee_project`
--

DROP TABLE IF EXISTS `employee_project`;
CREATE TABLE IF NOT EXISTS `employee_project` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_project_employee_id_project_id_unique` (`employee_id`,`project_id`),
  KEY `employee_project_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_project`
--

INSERT INTO `employee_project` (`id`, `employee_id`, `project_id`, `created_at`, `updated_at`) VALUES
(5, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomings`
--

DROP TABLE IF EXISTS `incomings`;
CREATE TABLE IF NOT EXISTS `incomings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomings`
--

INSERT INTO `incomings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Payment Received', '2025-08-30 03:22:05', '2025-08-30 03:22:05'),
(2, 'Advance', '2025-08-30 03:22:15', '2025-08-30 03:22:15'),
(3, 'Bonus', '2025-08-30 03:22:25', '2025-08-30 03:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dealer_id` bigint UNSIGNED NOT NULL,
  `bill_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_dealer_id_foreign` (`dealer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `dealer_id`, `bill_no`, `amount`, `date`, `created_at`, `updated_at`) VALUES
(1, 2, '500', 50000.00, '2025-08-06', '2025-09-01 01:28:44', '2025-09-01 01:28:55'),
(2, 3, '505', 5000.00, '2025-09-04', '2025-09-01 04:54:58', '2025-09-01 04:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_30_071917_create_dealers_table', 1),
(5, '2025_08_30_071918_create_invoices_table', 1),
(6, '2025_08_30_071919_create_employees_table', 1),
(7, '2025_08_30_071920_create_upads_table', 1),
(8, '2025_08_30_071921_create_projects_table', 1),
(9, '2025_08_30_071922_create_project_expenses_table', 1),
(10, '2025_08_30_071923_create_project_incomes_table', 1),
(11, '2025_08_30_071924_create_project_employee_table', 1),
(12, '2025_08_30_071924_create_tenders_table', 1),
(13, '2025_08_30_071925_create_outgoings_table', 1),
(14, '2025_08_30_071926_create_incomings_table', 1),
(15, '2025_09_01_070901_update_employees_table_pf_esic_to_numbers', 2),
(16, '2025_09_01_083422_create_employee_project_pivot_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `outgoings`
--

DROP TABLE IF EXISTS `outgoings`;
CREATE TABLE IF NOT EXISTS `outgoings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoings`
--

INSERT INTO `outgoings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Transport', '2025-08-30 03:18:58', '2025-08-30 03:18:58'),
(2, 'Materials', '2025-08-30 03:19:30', '2025-08-30 03:19:30'),
(3, 'Labor', '2025-08-30 03:21:53', '2025-08-30 03:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `department_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_project` decimal(12,2) NOT NULL,
  `time_limit` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emd_fdr_detail` text COLLATE utf8mb4_unicode_ci,
  `expenses` decimal(12,2) NOT NULL DEFAULT '0.00',
  `work_order_date` date DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `date`, `department_name`, `amount_project`, `time_limit`, `emd_fdr_detail`, `expenses`, `work_order_date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'Demo', '2025-09-12', 'Demo', 50000.00, '6 month', 'Demo', 5000.00, '2025-09-16', 'Demo', '2025-08-31 23:21:53', '2025-09-01 04:53:56');

-- --------------------------------------------------------

--
-- Table structure for table `project_employee`
--

DROP TABLE IF EXISTS `project_employee`;
CREATE TABLE IF NOT EXISTS `project_employee` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_employee_project_id_foreign` (`project_id`),
  KEY `project_employee_employee_id_foreign` (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_expenses`
--

DROP TABLE IF EXISTS `project_expenses`;
CREATE TABLE IF NOT EXISTS `project_expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `outgoing_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_expenses_project_id_foreign` (`project_id`),
  KEY `project_expenses_outgoing_id_foreign` (`outgoing_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_expenses`
--

INSERT INTO `project_expenses` (`id`, `project_id`, `outgoing_id`, `amount`, `date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 500.00, '2025-09-11', 'Demo', '2025-09-01 03:53:49', '2025-09-01 03:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `project_incomes`
--

DROP TABLE IF EXISTS `project_incomes`;
CREATE TABLE IF NOT EXISTS `project_incomes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED NOT NULL,
  `incoming_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_incomes_project_id_foreign` (`project_id`),
  KEY `project_incomes_incoming_id_foreign` (`incoming_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_incomes`
--

INSERT INTO `project_incomes` (`id`, `project_id`, `incoming_id`, `amount`, `date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5000.00, '2025-09-03', NULL, '2025-09-01 03:54:24', '2025-09-01 03:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

DROP TABLE IF EXISTS `tenders`;
CREATE TABLE IF NOT EXISTS `tenders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `work_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_emd_fdr` decimal(10,2) NOT NULL,
  `amount_dd` decimal(10,2) NOT NULL,
  `above_below` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `return_detail` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `result` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenders`
--

INSERT INTO `tenders` (`id`, `work_name`, `department`, `amount_emd_fdr`, `amount_dd`, `above_below`, `remark`, `return_detail`, `date`, `result`, `created_at`, `updated_at`) VALUES
(2, 'Demo', 'Road', 5000.00, 500.00, 'Above', 'Demo', 'Demo', '2025-09-04', 'Pending', '2025-09-01 04:56:24', '2025-09-01 05:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `upads`
--

DROP TABLE IF EXISTS `upads`;
CREATE TABLE IF NOT EXISTS `upads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `month` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `upad` decimal(10,2) NOT NULL,
  `pending` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upads_employee_id_foreign` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upads`
--

INSERT INTO `upads` (`id`, `employee_id`, `month`, `date`, `salary`, `upad`, `pending`, `remark`, `created_at`, `updated_at`) VALUES
(1, 2, 'August', '2025-09-11', 50000.00, 1000.00, 49000.00, NULL, '2025-09-01 01:53:29', '2025-09-01 02:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ansh', 'anshgalani@yahoo.com', NULL, '$2y$12$JkTVQN3JocArw2gJYkTZnesTwqcwsPCsAdjGMJJrGTPp0aBHULyDS', NULL, '2025-08-30 03:25:18', '2025-08-30 03:25:18');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
