-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 04, 2025 at 02:31 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utetmvmy_hiren_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_number` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bill_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','sent','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_gst` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_card` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealer_bank_accounts`
--

CREATE TABLE `dealer_bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_project`
--

CREATE TABLE `employee_project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomings`
--

CREATE TABLE `incomings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED NOT NULL,
  `bill_no` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `original_amount` decimal(12,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(36, '2025_09_12_063258_add_hsn_code_to_products_table', 14),
(37, '2025_09_20_073627_add_contractor_type_to_sub_contractors_table', 15),
(42, '2025_09_23_123249_create_r_a_bills_table', 16),
(43, '2025_09_24_044710_add_project_id_to_r_a_bills_table', 17),
(44, '2025_09_24_054004_drop_work_description_from_r_a_bills_table', 18),
(45, '2025_09_24_070739_add_pan_card_to_customers_table', 19),
(46, '2025_09_20_112555_add_gst_fields_to_invoices_table', 20),
(47, '2025_09_30_115559_add_soft_deletes_to_r_a_bills_table', 21),
(48, '2025_10_01_044547_add_soft_deletes_to_dealers_table', 22),
(50, '2025_10_03_053235_update_projects_table_remove_date_add_percentage_and_final_amount', 23),
(51, '2025_10_03_060918_create_dealer_bank_accounts_table', 24),
(52, '2025_10_03_072006_remove_bank_columns_from_dealers_table', 25),
(53, '2025_10_03_073809_rename_third_party_name_to_agency_name_in_sub_contractors_table', 26),
(54, '2025_10_03_085631_update_contractor_type_column_final', 27),
(55, '2025_10_03_095118_add_soft_deletes_to_transactions_table', 28),
(56, '2025_10_04_064905_add_soft_deletes_to_invoices_table', 29);

-- --------------------------------------------------------

--
-- Table structure for table `outgoings`
--

CREATE TABLE `outgoings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `password_reset_tokens` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hsn_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_project` decimal(12,2) NOT NULL,
  `percentage` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_project_amount` decimal(15,2) DEFAULT NULL,
  `time_limit` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emd_fdr_detail` text COLLATE utf8mb4_unicode_ci,
  `work_order_date` date DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_employee`
--

CREATE TABLE `project_employee` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_expenses`
--

CREATE TABLE `project_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `outgoing_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_incomes`
--

CREATE TABLE `project_incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `incoming_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `r_a_bills`
--

CREATE TABLE `r_a_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ra_bill_amount` decimal(15,2) NOT NULL,
  `dept_taxes_overheads` decimal(15,2) NOT NULL,
  `tds_1_percent` decimal(15,2) NOT NULL,
  `rmd_amount` decimal(15,2) NOT NULL,
  `welfare_cess` decimal(15,2) NOT NULL,
  `testing_charges` decimal(15,2) NOT NULL,
  `total_c` decimal(15,2) NOT NULL,
  `sgst_9_percent` decimal(15,2) NOT NULL,
  `cgst_9_percent` decimal(15,2) NOT NULL,
  `igst_0_percent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_with_gst` decimal(15,2) NOT NULL,
  `total_deductions` decimal(15,2) NOT NULL,
  `net_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_contractors`
--

CREATE TABLE `sub_contractors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contractor_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contractor_type` enum('self','agency') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `project_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_project` decimal(12,2) NOT NULL,
  `time_limit` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emd_fdr_detail` text COLLATE utf8mb4_unicode_ci,
  `work_order_date` date DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_contractor_bills`
--

CREATE TABLE `sub_contractor_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_contractor_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE `tenders` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('incoming','outgoing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dealer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_contractor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `incoming_id` bigint(20) UNSIGNED DEFAULT NULL,
  `outgoing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upads`
--

CREATE TABLE `upads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `salary_paid` tinyint(1) NOT NULL DEFAULT '0',
  `upad` decimal(10,2) NOT NULL,
  `upad_paid` tinyint(1) NOT NULL DEFAULT '0',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ansh', 'anshgalani@yahoo.com', NULL, '$2y$12$e63VF4Z10IFUeGjHc9E1tOm/5.qp3Tb2vaGt6OFjCmNyJiOTuFv9C', NULL, '2025-09-13 05:46:52', '2025-09-15 05:57:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  ADD KEY `bills_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_items_bill_id_foreign` (`bill_id`),
  ADD KEY `bill_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dealer_bank_accounts`
--
ALTER TABLE `dealer_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dealer_bank_accounts_dealer_id_foreign` (`dealer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_project`
--
ALTER TABLE `employee_project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_project_employee_id_project_id_unique` (`employee_id`,`project_id`),
  ADD KEY `employee_project_project_id_foreign` (`project_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incomings`
--
ALTER TABLE `incomings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_dealer_id_foreign` (`dealer_id`),
  ADD KEY `invoices_deleted_at_index` (`deleted_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outgoings`
--
ALTER TABLE `outgoings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_employee`
--
ALTER TABLE `project_employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_employee_project_id_foreign` (`project_id`),
  ADD KEY `project_employee_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `project_expenses`
--
ALTER TABLE `project_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_expenses_project_id_foreign` (`project_id`),
  ADD KEY `project_expenses_outgoing_id_foreign` (`outgoing_id`);

--
-- Indexes for table `project_incomes`
--
ALTER TABLE `project_incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_incomes_project_id_foreign` (`project_id`),
  ADD KEY `project_incomes_incoming_id_foreign` (`incoming_id`);

--
-- Indexes for table `r_a_bills`
--
ALTER TABLE `r_a_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `r_a_bills_customer_id_foreign` (`customer_id`),
  ADD KEY `r_a_bills_project_id_foreign` (`project_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_contractors`
--
ALTER TABLE `sub_contractors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_contractors_contractor_type_contractor_name_index` (`contractor_type`,`contractor_name`);

--
-- Indexes for table `sub_contractor_bills`
--
ALTER TABLE `sub_contractor_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_contractor_bills_sub_contractor_id_foreign` (`sub_contractor_id`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_project_id_foreign` (`project_id`),
  ADD KEY `transactions_dealer_id_foreign` (`dealer_id`),
  ADD KEY `transactions_incoming_id_foreign` (`incoming_id`),
  ADD KEY `transactions_outgoing_id_foreign` (`outgoing_id`),
  ADD KEY `transactions_sub_contractor_id_foreign` (`sub_contractor_id`);

--
-- Indexes for table `upads`
--
ALTER TABLE `upads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upads_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealer_bank_accounts`
--
ALTER TABLE `dealer_bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_project`
--
ALTER TABLE `employee_project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomings`
--
ALTER TABLE `incomings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `outgoings`
--
ALTER TABLE `outgoings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_employee`
--
ALTER TABLE `project_employee`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_expenses`
--
ALTER TABLE `project_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_incomes`
--
ALTER TABLE `project_incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_a_bills`
--
ALTER TABLE `r_a_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_contractors`
--
ALTER TABLE `sub_contractors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_contractor_bills`
--
ALTER TABLE `sub_contractor_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenders`
--
ALTER TABLE `tenders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upads`
--
ALTER TABLE `upads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
