-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 10, 2025 at 06:29 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `dealer_name`, `mobile_no`, `gst`, `address`, `account_no`, `account_name`, `ifsc`, `bank_name`, `created_at`, `updated_at`) VALUES
(1, 'Ansh', '4525652565', '24AAACH7409R2Z6', 'Surat', '254565852565', 'Ansh', 'HDFC2546HD', 'HDFC', '2025-09-04 07:28:38', '2025-09-04 07:28:38'),
(2, 'Hardik', '5425658565', 'GST45625KIPY', 'Surat', '254565853254', 'Demo', 'IKHP5458PH', 'Demo', '2025-09-04 23:06:25', '2025-09-04 23:06:25');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `designation`, `mobile_no`, `alt_contact_no`, `pan_no`, `aadhar_no`, `salary`, `pf`, `esic`, `bank_name`, `account_no`, `ifsc`, `created_at`, `updated_at`) VALUES
(1, 'Ansh', 'Founder', '95625458565', '25452565856', 'DNKPG56525KHS', '452325895625', 50000.00, 'PF1234PKJ', 'ESIC5648', 'HDFC', '254565854525', 'HDFC456HDFC', '2025-09-04 07:31:23', '2025-09-04 07:31:23');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_project`
--

INSERT INTO `employee_project` (`id`, `employee_id`, `project_id`, `created_at`, `updated_at`) VALUES
(11, 1, 1, '2025-09-05 07:22:22', '2025-09-05 07:22:22'),
(10, 2, 1, '2025-09-05 07:18:13', '2025-09-05 07:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomings`
--

INSERT INTO `incomings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(5, 'Dealer Cash', '2025-09-01 08:28:29', '2025-09-01 08:28:29'),
(4, 'Bills', '2025-09-01 08:28:21', '2025-09-01 08:28:21'),
(6, 'Interest', '2025-09-01 08:28:37', '2025-09-01 08:28:37'),
(7, 'Uchina', '2025-09-01 08:28:43', '2025-09-01 08:28:43'),
(8, 'Loan', '2025-09-01 08:28:49', '2025-09-01 08:28:49');

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
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_dealer_id_foreign` (`dealer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `dealer_id`, `bill_no`, `amount`, `date`, `remark`, `created_at`, `updated_at`) VALUES
(2, 1, '45', 2000.00, '2025-08-14', 'Demo', '2025-09-04 23:05:43', '2025-09-04 23:05:43'),
(3, 2, '20', 6000.00, '2025-08-21', 'Demo', '2025-09-04 23:06:44', '2025-09-04 23:06:44');

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(16, '2025_09_01_083422_create_employee_project_pivot_table', 3),
(17, '2025_09_01_112147_create_transactions_table', 4),
(18, '2025_09_01_112149_create_expenses_table', 4),
(19, '2025_09_02_091030_create_sub_contractors_table', 5),
(20, '2025_09_02_091131_create_sub_contractor_bills_table', 5),
(21, '2025_09_02_103558_add_bill_no_to_sub_contractor_bills_table', 6),
(22, '2025_09_02_130732_add_active_to_projects_table', 7),
(23, '2025_09_03_061935_add_remark_to_invoices_table', 8),
(24, '2025_09_03_062056_add_remark_to_invoices_table', 9),
(25, '2025_09_08_114743_add_payment_status_to_upads_table', 10),
(26, '2025_09_09_060656_remove_month_and_pending_from_upads_table', 11),
(27, '2025_09_10_051040_create_products_table', 12);

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoings`
--

INSERT INTO `outgoings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(5, 'Other', '2025-09-01 08:27:07', '2025-09-01 08:27:07'),
(4, 'Personal', '2025-09-01 08:27:00', '2025-09-01 08:27:00'),
(6, 'Office', '2025-09-01 08:27:14', '2025-09-01 08:27:14'),
(7, 'Department', '2025-09-01 08:27:20', '2025-09-01 08:27:20'),
(8, 'Vehicle', '2025-09-01 08:27:27', '2025-09-01 08:27:27'),
(9, 'Home', '2025-09-01 08:27:34', '2025-09-01 08:27:34'),
(10, 'Loan', '2025-09-01 08:27:40', '2025-09-01 08:27:40'),
(11, 'Salary', '2025-09-01 08:27:53', '2025-09-01 08:27:53'),
(12, 'Dealer', '2025-09-01 08:28:04', '2025-09-01 08:28:04');

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
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `date`, `created_at`, `updated_at`) VALUES
(2, 'Cement', '2025-09-10', '2025-09-09 23:57:00', '2025-09-09 23:57:00');

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
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `date`, `department_name`, `amount_project`, `time_limit`, `emd_fdr_detail`, `expenses`, `work_order_date`, `remark`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Surat', '2025-08-08', 'Road', 500000.00, '1 Year', 'Demo EMD/FDR', 0.00, '2025-08-16', 'Demo', 1, '2025-09-04 07:35:50', '2025-09-04 07:35:50');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `sub_contractors`
--

DROP TABLE IF EXISTS `sub_contractors`;
CREATE TABLE IF NOT EXISTS `sub_contractors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contractor_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `project_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Dumping data for table `sub_contractors`
--

INSERT INTO `sub_contractors` (`id`, `contractor_name`, `date`, `project_name`, `department_name`, `amount_project`, `time_limit`, `emd_fdr_detail`, `expenses`, `work_order_date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'Milan', '2025-08-15', 'Surat', 'Road', 100000.00, '6 Month', 'Demo EMD/FDR', 0.00, '2025-08-20', 'Demo Remark', '2025-09-04 07:38:12', '2025-09-04 07:38:12');

-- --------------------------------------------------------

--
-- Table structure for table `sub_contractor_bills`
--

DROP TABLE IF EXISTS `sub_contractor_bills`;
CREATE TABLE IF NOT EXISTS `sub_contractor_bills` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bill_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_contractor_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_contractor_bills_sub_contractor_id_foreign` (`sub_contractor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_contractor_bills`
--

INSERT INTO `sub_contractor_bills` (`id`, `bill_no`, `sub_contractor_id`, `amount`, `date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'Bill01', 1, 5000.00, '2025-08-13', NULL, '2025-09-08 03:01:19', '2025-09-08 03:01:19');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenders`
--

INSERT INTO `tenders` (`id`, `work_name`, `department`, `amount_emd_fdr`, `amount_dd`, `above_below`, `remark`, `return_detail`, `date`, `result`, `created_at`, `updated_at`) VALUES
(1, 'Bardoli', 'Surat', 50000.00, 5000.00, 'Above', 'Demo', 'Demo Return', '2025-08-13', 'Pending', '2025-09-04 07:36:52', '2025-09-04 07:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('incoming','outgoing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `dealer_id` bigint UNSIGNED DEFAULT NULL,
  `incoming_id` bigint UNSIGNED DEFAULT NULL,
  `outgoing_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_project_id_foreign` (`project_id`),
  KEY `transactions_dealer_id_foreign` (`dealer_id`),
  KEY `transactions_incoming_id_foreign` (`incoming_id`),
  KEY `transactions_outgoing_id_foreign` (`outgoing_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `project_id`, `dealer_id`, `incoming_id`, `outgoing_id`, `amount`, `date`, `description`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'incoming', 1, 1, 4, NULL, 10000.00, '2025-08-15', 'Receive bill from dealers', 'Demo', '2025-09-04 07:41:10', '2025-09-04 07:41:10'),
(2, 'outgoing', 1, 2, NULL, 6, 5000.00, '2025-08-21', 'Buy Office item', NULL, '2025-09-04 07:41:54', '2025-09-04 23:07:16'),
(3, 'outgoing', 1, 1, NULL, 9, 5000.00, '2025-08-15', 'Demo', NULL, '2025-09-04 07:48:41', '2025-09-04 07:48:41'),
(4, 'incoming', 1, 1, 6, NULL, 2000.00, '2025-08-23', 'Demo', NULL, '2025-09-08 00:39:45', '2025-09-08 00:39:45'),
(5, 'incoming', 1, NULL, 7, NULL, 6500.00, '2025-09-08', 'Ansh', 'Demo From Dev', '2025-09-08 03:53:47', '2025-09-08 03:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `upads`
--

DROP TABLE IF EXISTS `upads`;
CREATE TABLE IF NOT EXISTS `upads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `salary_paid` tinyint(1) NOT NULL DEFAULT '0',
  `upad` decimal(10,2) NOT NULL,
  `upad_paid` tinyint(1) NOT NULL DEFAULT '0',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upads_employee_id_foreign` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upads`
--

INSERT INTO `upads` (`id`, `employee_id`, `date`, `salary`, `salary_paid`, `upad`, `upad_paid`, `remark`, `created_at`, `updated_at`) VALUES
(13, 1, '2025-08-14', 50000.00, 0, 10000.00, 0, NULL, '2025-09-08 08:22:31', '2025-09-08 08:22:58'),
(16, 1, '2025-09-07', 50000.00, 0, 2000.00, 0, NULL, '2025-09-08 23:20:51', '2025-09-09 00:00:49'),
(11, 1, '2025-09-05', 50000.00, 0, 10000.00, 0, NULL, '2025-09-08 08:21:40', '2025-09-09 00:00:49'),
(14, 1, '2025-08-20', 50000.00, 0, 35000.00, 0, NULL, '2025-09-08 08:22:58', '2025-09-08 08:22:58'),
(15, 1, '2025-09-06', 50000.00, 0, 20000.00, 0, NULL, '2025-09-08 08:23:53', '2025-09-09 00:00:49'),
(18, 1, '2025-09-05', 50000.00, 0, 2000.00, 0, 'Demo', '2025-09-09 00:39:34', '2025-09-09 00:47:39'),
(19, 1, '2025-09-04', 50000.00, 0, 2000.00, 0, NULL, '2025-09-09 00:46:55', '2025-09-09 00:46:55');

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
