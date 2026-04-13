-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 12, 2026 at 11:39 AM
-- Server version: 8.0.45
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bm_vehicle_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `bms`
--

CREATE TABLE `bms` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yearly_budget` decimal(10,2) NOT NULL DEFAULT '100000.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bms`
--

INSERT INTO `bms` (`id`, `user_id`, `name`, `yearly_budget`, `created_at`, `updated_at`) VALUES
(3, 32, 'CLIENT', 250000.00, '2026-03-26 05:46:10', '2026-04-10 02:59:34'),
(4, 34, 'Person2', 20000.00, '2026-03-31 01:15:28', '2026-03-31 01:15:28'),
(5, 33, 'Person1', 50000.00, '2026-03-31 07:01:36', '2026-04-06 01:50:50'),
(6, 35, 'Person3', 50000.00, '2026-04-06 01:58:56', '2026-04-06 01:58:56'),
(8, 38, 'Person6', 50000.00, '2026-04-10 01:03:30', '2026-04-10 01:03:30'),
(9, 39, 'Person5', 50000.00, '2026-04-10 01:03:56', '2026-04-10 01:03:56'),
(10, 40, 'Person7', 50000.00, '2026-04-10 01:04:37', '2026-04-10 01:04:37'),
(11, 41, 'Janial Bacani', 150000.00, '2026-04-10 06:24:42', '2026-04-12 03:57:58'),
(12, 42, 'Person8', 1000.00, '2026-04-10 06:47:44', '2026-04-10 06:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_slips`
--

CREATE TABLE `fuel_slips` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED DEFAULT NULL,
  `vehicle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `liters` decimal(8,2) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `km_reading` int NOT NULL,
  `driver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `control_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prepared_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_official_business` tinyint(1) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_slips`
--

INSERT INTO `fuel_slips` (`id`, `user_id`, `vehicle_id`, `vehicle_name`, `plate_number`, `liters`, `unit_cost`, `total_cost`, `km_reading`, `driver`, `control_number`, `prepared_by_name`, `approved_by_name`, `is_official_business`, `date`, `created_at`, `updated_at`) VALUES
(61, 32, 32, 'Toyota AE86', 'CLT 810', 20.00, 130.00, 2600.00, 100, 'Ligen', 'FS-20260326-836517', 'Noligen', 'Cabradilla', 0, '2026-03-26', '2026-03-26 05:58:41', '2026-03-26 05:58:41'),
(62, 32, 33, 'SNIPER', 'IDZ 128', 5.00, 92.00, 460.00, 6054, 'Janial', 'FS-20260331-091284', 'Summit', 'Asus', 0, '2026-03-31', '2026-03-31 07:04:15', '2026-03-31 07:04:15'),
(63, 32, 32, 'Toyota AE86', 'CLT 810', 50.00, 130.00, 6500.00, 10021, 'HAHA', 'FS-20260331-772144', 'jdas;', 'lkdajf', 0, '2026-03-31', '2026-03-31 07:05:22', '2026-03-31 07:05:22'),
(64, 32, 32, 'Toyota AE86', 'CLT 810', 54.00, 120.00, 6480.00, 23432, 'ASD', 'FS-20260407-414062', 'DS', 'DSA', 0, '2026-04-07', '2026-04-07 02:43:09', '2026-04-07 02:43:09'),
(65, 32, 32, 'Toyota AE86', 'CLT 810', 50.00, 130.00, 6500.00, 25321, 'ASD', 'FS-20260408-119473', 'janj', 'janaj', 0, '2026-04-08', '2026-04-08 02:10:54', '2026-04-08 02:10:54'),
(66, 32, 33, 'SNIPER', 'IDZ 128', 5.00, 90.00, 450.00, 12343, 'Janial', 'FS-20260408-354711', 'janjan', 'janjan', 0, '2026-05-01', '2026-04-08 02:12:39', '2026-04-08 02:12:39'),
(67, 34, 31, 'Ferrari', 'SAC 1234', 50.00, 110.00, 5500.00, 120, 'Jayden', 'FS-20260408-964314', 'jdflas', 'dl;kfja', 0, '2026-04-08', '2026-04-08 06:34:50', '2026-04-08 06:34:50'),
(68, 32, 33, 'SNIPER', 'IDZ 128', 50.00, 140.00, 7000.00, 14543, 'Janial', 'FS-20260410-996612', 'janjan', 'janjan', 0, '2026-04-10', '2026-04-10 02:09:55', '2026-04-10 02:09:55'),
(69, 34, 31, 'Ferrari', 'SAC 1234', 122.00, 40.00, 4880.00, 1000, 'Jayden', 'FS-20260410-461059', 'fja;dl', 'lkfdj;laskj', 0, '2026-04-10', '2026-04-10 02:11:54', '2026-04-10 02:11:54'),
(70, 34, 31, 'Ferrari', 'SAC 1234', 100.00, 120.00, 12000.00, 1200, 'Jayden', 'FS-20260410-704952', 'fasdf', 'dfasd', 0, '2026-04-10', '2026-04-10 02:15:15', '2026-04-10 02:15:15'),
(71, 32, 32, 'Toyota AE86', 'CLT 810', 500.00, 140.00, 70000.00, 27000, 'Ligen', 'FS-20260410-717495', 'jrfal;s', 'dfl;kasjfd', 0, '2026-04-10', '2026-04-10 02:20:08', '2026-04-10 02:20:08'),
(72, 35, 35, 'LC 300', 'HDM', 100.00, 130.00, 13000.00, 0, 'Pepe', 'FS-20260410-620118', 'janjan', 'janjan', 1, '2026-04-10', '2026-04-10 02:33:17', '2026-04-10 02:33:17'),
(73, 41, 36, 'Land Cruiser', 'JMB 1234', 50.00, 120.00, 6000.00, 4500, 'Janial Bacani', 'FS-20260410-978050', 'Janjan', 'Bacani', 0, '2026-04-10', '2026-04-10 06:37:07', '2026-04-10 06:37:07'),
(74, 41, 36, 'Land Cruiser', 'JMB 1234', 50.00, 140.00, 7000.00, 6500, 'Janial Bacani', 'FS-20260410-100060', 'l;kasdf', 'lkdfjas', 0, '2026-04-10', '2026-04-10 06:38:45', '2026-04-10 06:38:45'),
(75, 41, 36, 'Land Cruiser', 'JMB 1234', 100.00, 121.00, 12100.00, 11000, 'Janial Bacani', 'FS-20260412-553810', 'dfjalk;', 'sd;lkjfas', 0, '2026-05-12', '2026-04-12 04:04:39', '2026-04-12 04:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenances`
--

CREATE TABLE `maintenances` (
  `id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `maintenance_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preventive',
  `maintenance_km` int DEFAULT NULL,
  `operation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `conduct` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `call_of_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `prepared_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenances`
--

INSERT INTO `maintenances` (`id`, `vehicle_id`, `maintenance_type`, `maintenance_km`, `operation`, `cost`, `conduct`, `call_of_no`, `date`, `prepared_by_name`, `approved_by_name`, `photo`, `created_at`, `updated_at`) VALUES
(23, 32, 'preventive', 100, 'Change Oil', 20000.00, 'Toyota', 'MN-20260326-970957', '2026-03-26', 'Noligen', 'Cabradilla', NULL, '2026-03-26 06:01:59', '2026-03-26 06:01:59'),
(24, 32, 'repair', 10021, 'change front bumpers', 52043.00, 'Toyota', 'MN-20260331-878009', '2026-03-31', 'jaaj', 'jajaaj', NULL, '2026-03-31 07:13:11', '2026-03-31 07:13:11'),
(25, 32, 'repair', 29998, 'change oil', 21341.00, 'Toyota', 'MN-20260407-657152', '2026-04-07', 'Jayden', 'Janvic Madayag', NULL, '2026-04-07 02:40:10', '2026-04-07 02:40:10'),
(26, 32, 'preventive', 23211, 'change oil', 3000.00, 'TOYOTA', 'MN-20260407-783389', '2026-04-07', 'Janial Bacani', 'JANJAN', NULL, '2026-04-07 02:41:52', '2026-04-07 02:41:52'),
(27, 32, 'preventive', 24322, 'change tiressss', 500.00, 'Toyota', 'MN-20260407-435347', '2026-04-07', 'ligen', 'ligen', NULL, '2026-04-07 05:41:35', '2026-04-07 05:41:35'),
(28, 32, 'preventive', 28909, 'haha', 3211.00, 'faf', 'MN-20260408-054329', '2026-04-08', 'fadf', 'ggasdfa', NULL, '2026-04-08 02:30:51', '2026-04-08 02:30:51'),
(29, 31, 'preventive', 5654, 'fdsafas', 5678.00, 'dfasf', 'MN-20260408-440393', '2026-04-08', 'fdsafa', 'fdasfad', NULL, '2026-04-08 02:35:15', '2026-04-08 02:35:15'),
(30, 33, 'preventive', 12343, 'fgsfdg', 4563.00, 'gsgd', 'MN-20260408-934608', '2026-04-08', 'fdga', 'dfaf', NULL, '2026-04-08 02:46:08', '2026-04-08 02:46:08'),
(31, 36, 'preventive', 6500, 'change oil', 20000.00, 'TOyotra', 'MN-20260410-066398', '2026-04-10', 'fd;lak', 'lkfdas', NULL, '2026-04-10 06:40:39', '2026-04-10 06:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_27_013728_create_bms_table', 1),
(5, '2026_01_27_013736_create_vehicles_table', 1),
(6, '2026_01_27_013751_create_fuel_slips_table', 1),
(7, '2026_01_27_080302_add_user_id_to_fuel_slips_table', 1),
(8, '2026_01_28_055123_fix_vehicles_bm_id_foreign_key', 1),
(9, '2026_01_29_000001_create_maintenances_table', 1),
(10, '2026_01_29_000002_add_preventive_fields_to_maintenances_table', 1),
(11, '2026_02_02_000001_create_password_reset_tokens_table', 2),
(12, '2026_02_11_000000_add_vehicle_name_driver_to_vehicles_table', 3),
(13, '2026_02_11_100000_create_offices_table', 3),
(14, '2026_02_11_100001_add_office_id_to_users_table', 3),
(15, '2026_02_11_100002_add_office_id_to_vehicles_table', 3),
(16, '2026_02_20_000000_add_signatory_names_to_fuel_slips_table', 4),
(17, '2026_02_26_000000_modify_fuel_slips_cost_columns', 5),
(18, '2026_01_29_000003_add_prepared_approved_to_maintenances_table', 6),
(19, '2026_03_26_011534_add_user_id_to_bms_table', 7),
(20, '2026_03_26_022448_make_bm_id_nullable_in_vehicles_table', 8),
(21, '2026_04_10_020511_add_is_official_business_to_fuel_slips_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`id`, `name`, `address`, `created_at`, `updated_at`) VALUES
(4, 'Secretariat', NULL, '2026-02-12 06:04:11', '2026-02-12 06:04:11'),
(5, 'Tracking and Monitoring Unit', 'B1', '2026-02-13 01:57:45', '2026-02-13 05:19:37'),
(8, 'Board Member Office', NULL, '2026-03-04 06:57:03', '2026-03-04 07:01:44'),
(9, 'BM Janial Bacani', NULL, '2026-04-12 11:38:58', '2026-04-12 11:38:58'),
(10, 'BM Noligen Cabradilla', NULL, '2026-04-12 11:39:13', '2026-04-12 11:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('janial.bacani17@gmail.com', '$2y$12$A7w9kz0MWRMiC2WC5WN59.tPWRegRVK0MjWKeeDLV25Ks.TCiUhpi', '2026-03-12 11:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','boardmember') COLLATE utf8mb4_unicode_ci NOT NULL,
  `office_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `office_id`, `created_at`, `updated_at`) VALUES
(25, 'Janial M. Bacani', 'janial.bacani17@gmail.com', '$2y$12$yTYZJXEX3qt9UCv54jB6OuMcv/9NrqLi941kmzfXs4.5XfWkfxfRC', 'admin', NULL, '2026-02-13 05:13:20', '2026-03-23 23:58:41'),
(28, 'user', 'user@gmail.com', '$2y$12$QP5ZYge7OhdIEWodm7PoS.LoenOsyQxglDo692d1rMDwR6RcKmIha', 'admin', 5, '2026-03-04 00:31:22', '2026-03-05 01:34:54'),
(32, 'CLIENT', 'client@example.com', '$2y$12$wqd8J/qoIgxygQ6RE2c8ju8qSOU8J5AEWqFWtQDctKRETjIpzpQgS', 'boardmember', 5, '2026-03-26 05:41:27', '2026-03-26 05:46:10'),
(33, 'Person1', 'person1@example.com', '$2y$12$cPyWgHOzd0igf7w68h5nauK3cIDqvaJUWIivNJ1v7aZUZwLSqsSHy', 'admin', 8, '2026-03-31 01:01:46', '2026-03-31 01:05:13'),
(34, 'Person2', 'person2@example.com', '$2y$12$nzHhasSvP0uXpT/.jd8hp.2LZ1IBSm751JGpEOYlGp906SH9cPFPi', 'boardmember', 8, '2026-03-31 01:02:17', '2026-03-31 01:15:28'),
(35, 'Person3', 'person3@example.com', '$2y$12$gX9M6KAspQgWFWqL5q3JR.ZdI5.hBGs5Gi9ZEnqHv7QCZD63a7XwW', 'boardmember', 5, '2026-03-31 01:02:59', '2026-04-06 01:58:55'),
(36, 'Person4', 'person4@gmail.com', '$2y$12$5Q6t3PJk/E2MvRl1DqmpQ.shm8Lrj7XUcJ4vm9rtc6WjmEZNAQdde', 'boardmember', 5, '2026-03-31 01:03:51', '2026-03-31 01:05:22'),
(38, 'Person6', 'person6@gmail.com', '$2y$12$NtWTPig3O5G2XQ2WKEbOm.GG9JbUnpd5vB5H.DGiPDqjPLPjUcoqW', 'boardmember', 4, '2026-04-10 01:03:30', '2026-04-10 01:03:30'),
(39, 'Person5', 'person5@example.com', '$2y$12$KzdtE8k5FRwD1SdxgI6VJOwhKhqbTAAPUfHZJq8FVAffZhlCJN3Tq', 'boardmember', 5, '2026-04-10 01:03:56', '2026-04-10 01:03:56'),
(40, 'Person7', 'person7@example.com', '$2y$12$c5kMGAS9luquG0/Hyji6bOOYe.gyvcO2Il/KJkTb8fIsNFalLZax.', 'boardmember', 4, '2026-04-10 01:04:37', '2026-04-10 01:04:37'),
(41, 'Janial Bacani', 'janial.bacani@lorma.edu', '$2y$12$XiWiu8chS5ER2ZpIEReadexiVHbBZn6FGr4X8bj9dIXM9BhUe.NDq', 'boardmember', 4, '2026-04-10 06:24:42', '2026-04-10 06:24:42'),
(42, 'Person8', 'person8@example.com', '$2y$12$Cf6Yn6kOLFzeuOLxe8t6XOwXRctcNBKm6EmyhRYVtEsPT5blK3bka', 'boardmember', 5, '2026-04-10 06:47:44', '2026-04-10 06:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL,
  `bm_id` bigint UNSIGNED DEFAULT NULL,
  `office_id` bigint UNSIGNED DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_fuel_limit` decimal(8,2) NOT NULL DEFAULT '100.00',
  `current_km` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `bm_id`, `office_id`, `plate_number`, `vehicle_name`, `driver`, `monthly_fuel_limit`, `current_km`, `created_at`, `updated_at`) VALUES
(29, 33, 8, 'JMB 2312', 'Toyota HiAce GL GRS', 'Janial bacani', 100.00, 0, '2026-03-09 23:24:58', '2026-03-31 01:14:07'),
(31, 34, 8, 'SAC 1234', 'Ferrari', 'Jayden', 100.00, 1200, '2026-03-19 01:29:58', '2026-04-10 02:15:15'),
(32, 32, 5, 'CLT 810', 'Toyota AE86', 'Ligen', 100.00, 27000, '2026-03-26 05:48:11', '2026-04-10 02:20:08'),
(33, 32, 5, 'IDZ 128', 'SNIPER', 'Janial', 100.00, 14543, '2026-03-31 00:37:41', '2026-04-10 02:09:55'),
(34, 36, 5, 'PRS 4000', 'LC 300', 'summit', 100.00, 0, '2026-03-31 01:05:58', '2026-03-31 01:05:58'),
(35, 35, 5, 'HDM', 'LC 300', 'Pepe', 100.00, 0, '2026-04-10 02:32:48', '2026-04-10 02:32:48'),
(36, 41, 4, 'JMB 1234', 'Land Cruiser', 'Janial Bacani', 100.00, 11000, '2026-04-10 06:36:28', '2026-04-12 04:04:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bms`
--
ALTER TABLE `bms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bms_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fuel_slips`
--
ALTER TABLE `fuel_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fuel_slips_control_number_unique` (`control_number`),
  ADD KEY `fuel_slips_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `fuel_slips_user_id_foreign` (`user_id`);

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
-- Indexes for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maintenances_call_of_no_unique` (`call_of_no`),
  ADD KEY `maintenances_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_office_id_foreign` (`office_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`),
  ADD KEY `vehicles_bm_id_foreign` (`bm_id`),
  ADD KEY `vehicles_office_id_foreign` (`office_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bms`
--
ALTER TABLE `bms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuel_slips`
--
ALTER TABLE `fuel_slips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenances`
--
ALTER TABLE `maintenances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bms`
--
ALTER TABLE `bms`
  ADD CONSTRAINT `bms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_slips`
--
ALTER TABLE `fuel_slips`
  ADD CONSTRAINT `fuel_slips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_slips_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD CONSTRAINT `maintenances_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_bm_id_foreign` FOREIGN KEY (`bm_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicles_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
