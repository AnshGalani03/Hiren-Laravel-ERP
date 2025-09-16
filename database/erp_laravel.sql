-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 16, 2025 at 06:02 AM
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
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
CREATE TABLE IF NOT EXISTS `bills` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bill_number` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `bill_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','sent','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_gst` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  KEY `bills_customer_id_foreign` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `bill_number`, `customer_id`, `bill_date`, `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, `notes`, `status`, `is_gst`, `created_at`, `updated_at`) VALUES
(1, 'HSN25001', 1, '2025-09-13', 7500.00, 0.00, 0.00, 7500.00, NULL, 'draft', 0, '2025-09-13 06:03:31', '2025-09-13 06:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

DROP TABLE IF EXISTS `bill_items`;
CREATE TABLE IF NOT EXISTS `bill_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bill_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_items_bill_id_foreign` (`bill_id`),
  KEY `bill_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(4, 1, 2, 10, 500.00, 5000.00, '2025-09-13 06:04:14', '2025-09-13 06:04:14'),
(3, 1, 1, 5, 500.00, 2500.00, '2025-09-13 06:04:14', '2025-09-13 06:04:14');

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
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `gst`, `phone_no`, `created_at`, `updated_at`) VALUES
(1, 'Naitik', 'Surat', 'GSTPANFH4585GST', '4525658525', '2025-09-13 05:55:15', '2025-09-13 05:55:15');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `dealer_name`, `mobile_no`, `gst`, `address`, `account_no`, `account_name`, `ifsc`, `bank_name`, `created_at`, `updated_at`) VALUES
(1, 'Ansh', '4525658545', 'GST4565GST45', 'Surat, Gujarat', '2545625854', 'ICICI', 'ICICI4585ICI', 'Demo', '2025-09-13 05:47:38', '2025-09-13 05:47:38');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `designation`, `mobile_no`, `alt_contact_no`, `pan_no`, `aadhar_no`, `salary`, `pf`, `esic`, `bank_name`, `account_no`, `ifsc`, `created_at`, `updated_at`) VALUES
(1, 'Milan', 'CEO', '4585652545', '2545856585', 'PAN456PAN', '4525685458565', 50000.00, 'PF123PF', 'ESIC458565ESIC', 'Demo', '52365458565', 'Demo4525', '2025-09-13 05:53:52', '2025-09-13 05:53:52');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_project`
--

INSERT INTO `employee_project` (`id`, `employee_id`, `project_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-09-13 05:55:58', '2025-09-13 05:55:58');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomings`
--

INSERT INTO `incomings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dealer Cash', '2025-09-13 06:00:10', '2025-09-13 06:00:10'),
(2, 'Bills', '2025-09-13 06:00:15', '2025-09-13 06:00:15'),
(3, 'Interest', '2025-09-13 06:00:19', '2025-09-13 06:00:19'),
(4, 'Uchina', '2025-09-13 06:00:24', '2025-09-13 06:00:24'),
(5, 'Loan', '2025-09-13 06:00:28', '2025-09-13 06:00:28');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `dealer_id`, `bill_no`, `amount`, `date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, '01', 5000.00, '2025-09-03', 'Demo', '2025-09-13 05:47:56', '2025-09-13 05:47:56');

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(15, '2025_09_01_070901_update_employees_table_pf_esic_to_numbers', 1),
(16, '2025_09_01_083422_create_employee_project_pivot_table', 1),
(17, '2025_09_01_112147_create_transactions_table', 1),
(18, '2025_09_01_112149_create_expenses_table', 1),
(19, '2025_09_02_091030_create_sub_contractors_table', 1),
(20, '2025_09_02_091131_create_sub_contractor_bills_table', 1),
(21, '2025_09_02_103558_add_bill_no_to_sub_contractor_bills_table', 1),
(22, '2025_09_02_130732_add_active_to_projects_table', 1),
(23, '2025_09_03_062056_add_remark_to_invoices_table', 1),
(24, '2025_09_08_112038_fix_invoices_remark_column', 2),
(25, '2025_09_08_112057_add_payment_status_to_upads_table', 3),
(26, '2025_09_08_114743_add_payment_status_to_upads_table', 4),
(27, '2025_09_09_060656_remove_month_and_pending_from_upads_table', 5),
(28, '2025_09_10_051040_create_products_table', 6),
(29, '2025_09_10_071217_create_bills_table', 7),
(30, '2025_09_10_071218_create_bill_items_table', 8),
(31, '2025_09_11_052724_drop_expenses_column_from_projects_table', 9),
(32, '2025_09_11_053957_drop_expenses_column_from_sub_contractors_table', 10),
(33, '2025_09_11_064505_add_sub_contractor_to_transactions_table', 11),
(34, '2025_09_11_110558_create_customers_table', 12),
(35, '2025_09_11_115949_replace_dealer_with_customer_in_bills_table', 13),
(36, '2025_09_12_063258_add_hsn_code_to_products_table', 14);

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outgoings`
--

INSERT INTO `outgoings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Other', '2025-09-13 05:58:48', '2025-09-13 05:58:48'),
(2, 'Personal', '2025-09-13 05:58:52', '2025-09-13 05:58:52'),
(3, 'Office', '2025-09-13 05:58:57', '2025-09-13 05:58:57'),
(4, 'Department', '2025-09-13 05:59:03', '2025-09-13 05:59:03'),
(5, 'Vehicle', '2025-09-13 05:59:07', '2025-09-13 05:59:07'),
(6, 'Home', '2025-09-13 05:59:16', '2025-09-13 05:59:16'),
(7, 'Loan', '2025-09-13 05:59:43', '2025-09-13 05:59:43'),
(8, 'Salary', '2025-09-13 05:59:50', '2025-09-13 05:59:50'),
(9, 'Dealer', '2025-09-13 05:59:56', '2025-09-13 05:59:56');

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
  `hsn_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `hsn_code`, `date`, `created_at`, `updated_at`) VALUES
(1, 'Cement', 'HSNCEMENT', '2025-09-13', '2025-09-13 06:02:48', '2025-09-13 06:02:48'),
(2, 'Still', 'STILL56', '2025-09-13', '2025-09-13 06:02:57', '2025-09-13 06:02:57');

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

INSERT INTO `projects` (`id`, `name`, `date`, `department_name`, `amount_project`, `time_limit`, `emd_fdr_detail`, `work_order_date`, `remark`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Surat', '2025-09-08', 'Road', 500000.00, '6 month', '5000', '2025-09-09', NULL, 1, '2025-09-13 05:55:49', '2025-09-13 05:55:49');

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
  `work_order_date` date DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_contractors`
--

INSERT INTO `sub_contractors` (`id`, `contractor_name`, `date`, `project_name`, `department_name`, `amount_project`, `time_limit`, `emd_fdr_detail`, `work_order_date`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'Milan', '2025-09-07', 'Surat', 'Road', 5000.00, '6 Month', '2000', '2025-09-04', NULL, '2025-09-13 05:57:44', '2025-09-13 05:57:44');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenders`
--

INSERT INTO `tenders` (`id`, `work_name`, `department`, `amount_emd_fdr`, `amount_dd`, `above_below`, `remark`, `return_detail`, `date`, `result`, `created_at`, `updated_at`) VALUES
(1, 'Road', 'Road', 2000.00, 1000.00, 'Above', 'Surat', 'Demo', '2025-09-03', 'Pending', '2025-09-13 05:56:48', '2025-09-13 05:56:48');

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
  `sub_contractor_id` bigint UNSIGNED DEFAULT NULL,
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
  KEY `transactions_outgoing_id_foreign` (`outgoing_id`),
  KEY `transactions_sub_contractor_id_foreign` (`sub_contractor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `project_id`, `dealer_id`, `sub_contractor_id`, `incoming_id`, `outgoing_id`, `amount`, `date`, `description`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'outgoing', 1, 1, NULL, NULL, 1, 2000.00, '2025-09-01', 'Demo', NULL, '2025-09-13 06:01:03', '2025-09-13 06:01:03'),
(2, 'incoming', 1, 1, NULL, 1, NULL, 5000.00, '2025-09-03', 'demo', NULL, '2025-09-13 06:01:27', '2025-09-13 06:01:27'),
(3, 'incoming', NULL, NULL, 1, 5, NULL, 200.00, '2025-09-01', 'demo', NULL, '2025-09-13 06:01:49', '2025-09-13 06:01:49');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upads`
--

INSERT INTO `upads` (`id`, `employee_id`, `date`, `salary`, `salary_paid`, `upad`, `upad_paid`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-09-02', 50000.00, 0, 2000.00, 0, NULL, '2025-09-13 05:54:06', '2025-09-13 05:54:06'),
(2, 1, '2025-08-01', 50000.00, 0, 10000.00, 0, 'Demo', '2025-09-13 05:54:33', '2025-09-13 05:54:33');

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
(1, 'Ansh', 'anshgalani@yahoo.com', NULL, '$2y$12$e63VF4Z10IFUeGjHc9E1tOm/5.qp3Tb2vaGt6OFjCmNyJiOTuFv9C', NULL, '2025-09-13 05:46:52', '2025-09-15 05:57:56');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
