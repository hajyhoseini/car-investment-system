-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 11:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_investment`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('bank','dollar','gold') NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `type`, `name`, `amount`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'bank', 'بانک ملی - حساب جاری', 125000000.00, 125000000.00, 'حساب اصلی شرکت', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(2, 'bank', 'بانک ملت - سپرده', 450000000.00, 450000000.00, 'سپرده بلندمدت با سود ۱۸٪', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(3, 'bank', 'بانک تجارت - جاری', 75000000.00, 75000000.00, 'برای هزینه‌های جاری', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(4, 'dollar', 'دلار آمریکا', 5000.00, 3000000000.00, 'ذخیره ارزی', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(5, 'dollar', 'دلار آمریکا', 2000.00, 1200000000.00, 'برای واردات', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(6, 'gold', 'سکه تمام بهار آزادی', 10.00, 2800000000.00, 'سکه سرمایه‌گذاری', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(7, 'gold', 'نیم سکه', 5.00, 700000000.00, 'برای نقدشوندگی سریع', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(8, 'gold', 'ربع سکه', 8.00, 600000000.00, 'سرمایه‌گذاری کوچک', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(9, 'gold', 'طلای آب شده', 150.00, 525000000.00, 'طلای آب شده با خلوص ۱۸', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-sara@example.com|127.0.0.1', 'i:1;', 1771840584),
('laravel-cache-sara@example.com|127.0.0.1:timer', 'i:1771840584;', 1771840584),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:30:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"view cars\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"create cars\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"edit cars\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"delete cars\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"sell cars\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"view investors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"create investors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:14:\"edit investors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"delete investors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"view investments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"create investments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"edit investments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:18:\"delete investments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view profits\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"view assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:13:\"create assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:11:\"edit assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"delete assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:16:\"view liabilities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:18:\"create liabilities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"edit liabilities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:18:\"delete liabilities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:14:\"view dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:20:\"view admin dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"investor\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}}}', 1771925227),
('laravel-cache-user@example.com|127.0.0.1', 'i:1;', 1771840574),
('laravel-cache-user@example.com|127.0.0.1:timer', 'i:1771840574;', 1771840574);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `kilometers` int(11) NOT NULL,
  `fuel_type` varchar(255) NOT NULL,
  `transmission` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `purchase_price` decimal(15,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `status` enum('available','sold','reserved') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `title`, `brand`, `model`, `year`, `kilometers`, `fuel_type`, `transmission`, `color`, `description`, `purchase_price`, `purchase_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'پژو ۲۰۶ تیپ ۲', 'پژو', '۲۰۶', 1400, 45000, 'بنزین', 'دنده‌ای', 'سفید', 'ماشین سالم، روغ‌کاری شده، بدون رنگ', 450000000.00, '1402-05-15', 'available', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(2, 'پراید ۱۳۱', 'سایپا', '۱۳۱', 1399, 62000, 'بنزین', 'دنده‌ای', 'نقره‌ای', 'موتور سالم، کولر سالم', 280000000.00, '1402-06-20', 'available', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(3, 'سمند LX', 'ایران خودرو', 'سمند', 1401, 28000, 'بنزین', 'دنده‌ای', 'مشکی', 'صفر کیلومتر، فقط ۲۸۰۰۰ کارکرد', 550000000.00, '1402-07-10', 'reserved', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(4, 'رنو تندر ۹۰', 'رنو', 'تندر', 1398, 85000, 'بنزین', 'دنده‌ای', 'سرمه‌ای', 'دست اول، سرویس شده', 620000000.00, '1402-04-05', 'sold', '2026-02-23 04:17:23', '2026-02-23 04:17:24'),
(5, 'هایما S7', 'هایما', 'S7', 1402, 5000, 'بنزین', 'اتوماتیک', 'طلایی', 'تقریبا نو، آپشن کامل', 1850000000.00, '1402-08-15', 'available', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(6, 'کوییک R', 'سایپا', 'کوییک', 1401, 15000, 'بنزین', 'دنده‌ای', 'قرمز', 'بسیار تمیز، فول آپشن', 420000000.00, '1402-09-01', 'available', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(7, 'دنا پلاس', 'ایران خودرو', 'دنا', 1402, 8000, 'بنزین', 'اتوماتیک', 'سفید', 'تیپ ۶، کروز کنترل', 980000000.00, '1402-10-20', 'available', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(8, 'تیبا ۲', 'سایپا', 'تیبا', 1400, 35000, 'بنزین', 'دنده‌ای', 'نقره‌ای', 'باکس دنده سالم', 320000000.00, '1402-11-05', 'sold', '2026-02-23 04:17:23', '2026-02-23 04:17:24'),
(9, 'پراید', 'پراید', '207', 1399, 1000, 'بنزین', 'دنده‌ای', 'سیاه', NULL, 1200000000.00, '2026-02-23', 'available', '2026-02-23 05:21:36', '2026-02-23 05:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `car_images`
--

CREATE TABLE `car_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(4, 1, 'cars/1/1771838915_699c1dc343e53.jpg', 1, 0, '2026-02-23 05:58:35', '2026-02-23 05:58:35'),
(5, 9, 'cars/9/1771840118_699c22761b7fd.jpg', 0, 0, '2026-02-23 06:18:38', '2026-02-23 06:24:23'),
(6, 9, 'cars/9/1771840118_699c2276aa31f.jpg', 1, 1, '2026-02-23 06:18:38', '2026-02-23 06:24:23'),
(7, 9, 'cars/9/1771840118_699c2276ab2ff.jpg', 0, 2, '2026-02-23 06:18:38', '2026-02-23 06:24:23'),
(8, 2, 'cars/2/1771840303_699c232f2527e.jpg', 1, 0, '2026-02-23 06:21:43', '2026-02-23 06:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `car_sales`
--

CREATE TABLE `car_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `total_profit` decimal(15,2) NOT NULL,
  `sale_date` date NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_phone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_sales`
--

INSERT INTO `car_sales` (`id`, `car_id`, `selling_price`, `total_profit`, `sale_date`, `buyer_name`, `buyer_phone`, `created_at`, `updated_at`) VALUES
(1, 4, 750000000.00, 130000000.00, '1402-12-10', 'احمد رضایی', '09123456789', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(2, 8, 380000000.00, 60000000.00, '1402-12-15', 'سعید کریمی', '09129876543', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `investment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `car_id`, `investor_id`, `amount`, `percentage`, `investment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 225000000.00, 50.00, '1402-05-16', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(2, 1, 2, 135000000.00, 30.00, '1402-05-17', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(3, 1, 3, 90000000.00, 20.00, '1402-05-18', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(4, 2, 2, 140000000.00, 50.00, '1402-06-21', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(5, 2, 4, 84000000.00, 30.00, '1402-06-22', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(6, 2, 5, 56000000.00, 20.00, '1402-06-23', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(7, 3, 3, 330000000.00, 60.00, '1402-07-11', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(8, 3, 6, 220000000.00, 40.00, '1402-07-12', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(9, 4, 1, 310000000.00, 50.00, '1402-04-06', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(10, 4, 4, 186000000.00, 30.00, '1402-04-07', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(11, 4, 5, 124000000.00, 20.00, '1402-04-08', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(12, 5, 2, 925000000.00, 50.00, '1402-08-16', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(13, 5, 3, 555000000.00, 30.00, '1402-08-17', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(14, 5, 6, 370000000.00, 20.00, '1402-08-18', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(15, 6, 1, 210000000.00, 50.00, '1402-09-02', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(16, 6, 4, 126000000.00, 30.00, '1402-09-03', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(17, 6, 5, 84000000.00, 20.00, '1402-09-04', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(18, 7, 2, 490000000.00, 50.00, '1402-10-21', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(19, 7, 3, 294000000.00, 30.00, '1402-10-22', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(20, 7, 6, 196000000.00, 20.00, '1402-10-23', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(21, 8, 1, 160000000.00, 50.00, '1402-11-06', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(22, 8, 4, 96000000.00, 30.00, '1402-11-07', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(23, 8, 5, 64000000.00, 20.00, '1402-11-08', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `national_code` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `total_invested` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investors`
--

INSERT INTO `investors` (`id`, `full_name`, `national_code`, `phone`, `email`, `address`, `total_invested`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'علی محمدی', '0012345678', '09121234567', 'ali@gmail.com', 'تهران، خیابان انقلاب', 905000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(2, 'سارا احمدی', '0012345679', '09131234567', 'sara@yahoo.com', 'اصفهان، چهارباغ', 1690000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(3, 'رضا کریمی', '0012345680', '09141234567', 'reza@gmail.com', 'شیراز، معالی‌آباد', 1269000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(4, 'مریم حسینی', '0012345681', '09151234567', 'maryam@yahoo.com', 'مشهد، احمدآباد', 492000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(5, 'مهدی رضایی', '0012345682', '09161234567', 'mehdi@gmail.com', 'تبریز، ولیعصر', 328000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(6, 'زهرا موسوی', '0012345683', '09171234567', 'zahra@yahoo.com', 'کرج، عظیمیه', 786000000.00, '2026-02-23 04:17:24', '2026-02-23 04:17:24', NULL),
(7, 'مدیر سیستم', '00000000001', '09120000000', 'admin@example.com', 'تهران', 0.00, '2026-02-23 04:18:59', '2026-02-23 04:18:59', 1),
(8, 'مدیر محتوا', '00000000009', '09120000000', 'manager@example.com', 'تهران', 0.00, '2026-02-23 06:27:07', '2026-02-23 06:27:07', 9);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `liabilities`
--

CREATE TABLE `liabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('debt','check','installment') NOT NULL,
  `creditor_name` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `remaining_amount` decimal(15,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liabilities`
--

INSERT INTO `liabilities` (`id`, `type`, `creditor_name`, `amount`, `remaining_amount`, `due_date`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 'debt', 'حسین رحیمی', 150000000.00, 150000000.00, '1403-03-15', 'pending', 'قرض الحسنه', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(2, 'debt', 'شرکت پخش البرز', 85000000.00, 85000000.00, '1403-02-20', 'pending', 'بابت خرید قطعات', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(3, 'check', 'نمایندگی ایران خودرو', 550000000.00, 550000000.00, '1403-04-01', 'pending', 'چک تضمینی برای خرید خودرو', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(4, 'check', 'تعمیرگاه مجاز', 45000000.00, 45000000.00, '1403-01-30', 'overdue', 'چک بابت تعمیرات', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(5, 'check', 'نمایشگاه خودرو اطلس', 320000000.00, 0.00, '1402-12-20', 'paid', 'پرداخت شده', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(6, 'installment', 'بانک اقتصاد نوین', 900000000.00, 600000000.00, '1403-06-15', 'pending', 'وام خرید خودرو - ۱۲ قسط ۷۵ میلیونی، ۸ قسط باقی‌مانده', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(7, 'installment', 'لیزینگ خودرو', 480000000.00, 160000000.00, '1403-03-25', 'pending', 'اجاره به شرط تملیک - ۲ قسط باقی‌مانده', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(8, 'installment', 'صندوق کارآفرینی امید', 300000000.00, 100000000.00, '1403-02-10', 'pending', 'تسهیلات کسب و کار - ۲ قسط آخر', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_18_071846_create_investors_table', 1),
(5, '2026_02_18_071847_create_cars_table', 1),
(6, '2026_02_18_071848_create_car_sales_table', 1),
(7, '2026_02_18_071848_create_investments_table', 1),
(8, '2026_02_18_071849_create_assets_table', 1),
(9, '2026_02_18_071850_create_liabilities_table', 1),
(10, '2026_02_21_055228_create_permission_tables', 1),
(11, '2026_02_21_082515_add_avatar_to_users_table', 1),
(12, '2026_02_21_103908_add_user_id_to_investors_table', 1),
(13, '2026_02_22_061302_make_user_id_unique_in_investors', 1),
(14, '2026_02_23_083827_create_car_images_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view cars', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(2, 'create cars', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(3, 'edit cars', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(4, 'delete cars', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(5, 'sell cars', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(6, 'view investors', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(7, 'create investors', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(8, 'edit investors', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(9, 'delete investors', 'web', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(10, 'view investments', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(11, 'create investments', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(12, 'edit investments', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(13, 'delete investments', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(14, 'view sales', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(15, 'create sales', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(16, 'view profits', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(17, 'view assets', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(18, 'create assets', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(19, 'edit assets', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(20, 'delete assets', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(21, 'view liabilities', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(22, 'create liabilities', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(23, 'edit liabilities', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(24, 'delete liabilities', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(25, 'view users', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(26, 'create users', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(27, 'edit users', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(28, 'delete users', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(29, 'view dashboard', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(30, 'view admin dashboard', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(2, 'manager', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(3, 'investor', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24'),
(4, 'user', 'web', '2026-02-23 04:17:24', '2026-02-23 04:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(10, 1),
(10, 2),
(10, 3),
(11, 1),
(11, 2),
(12, 1),
(13, 1),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(16, 3),
(17, 1),
(17, 2),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(21, 2),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(29, 2),
(29, 3),
(29, 4),
(30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('QYbhCawaSWhVmRdSJlwCeLmmOf9eJqM75BZdyADQ', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYjBGZ3c1QVphUmxkcmJtTWwwNmFqdTVPSDd0SW1CYkF2bWRRRzRaWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXItc2FsZXMiO3M6NToicm91dGUiO3M6MTU6ImNhci1zYWxlcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1771840701);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `phone`, `bio`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'مدیر سیستم', 'admin@example.com', NULL, '09123456789', 'مدیر اصلی سیستم', NULL, '$2y$12$maHfWpM3oSNajliDqp9FxebgLs156tJ4tbgAoSdV4rlszz4aYdZ9G', NULL, '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(2, 'Garnet Kovacek', 'baumbach.darby@example.org', NULL, NULL, NULL, '2026-02-23 04:17:23', '$2y$12$g3WP3i9NFwMwYpmhFHoqK.KyfTphbq8JnLTb9ja9ZI5p.8r13yxD2', 'l8K9PyGosN', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(3, 'Prof. Loraine Emard', 'rose21@example.com', NULL, NULL, NULL, '2026-02-23 04:17:23', '$2y$12$g3WP3i9NFwMwYpmhFHoqK.KyfTphbq8JnLTb9ja9ZI5p.8r13yxD2', '4ayjLjdyzK', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(4, 'Prof. Albert Predovic', 'kertzmann.mable@example.org', NULL, NULL, NULL, '2026-02-23 04:17:23', '$2y$12$g3WP3i9NFwMwYpmhFHoqK.KyfTphbq8JnLTb9ja9ZI5p.8r13yxD2', '3e1YQn3Mdc', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(5, 'Mrs. Thelma Bode', 'henri.sipes@example.org', NULL, NULL, NULL, '2026-02-23 04:17:23', '$2y$12$g3WP3i9NFwMwYpmhFHoqK.KyfTphbq8JnLTb9ja9ZI5p.8r13yxD2', 'TLM3D8iYiC', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(6, 'Alverta Murazik', 'lindgren.malvina@example.com', NULL, NULL, NULL, '2026-02-23 04:17:23', '$2y$12$g3WP3i9NFwMwYpmhFHoqK.KyfTphbq8JnLTb9ja9ZI5p.8r13yxD2', '5K3lPgnVAg', '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(7, 'کاربر یک', 'user1@example.com', NULL, '09123456788', NULL, NULL, '$2y$12$fm8gaq0j4O9xhKr6jby/9umZbpXqk/U/MWOOMWv52y501D95T6/oK', NULL, '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(8, 'کاربر دو', 'user2@example.com', NULL, '09123456787', NULL, NULL, '$2y$12$GS/VtcZwl0TZxrJC93wG0OhGhMMa6lJnY4ID5jzCJ79FW5S7rLAMG', NULL, '2026-02-23 04:17:23', '2026-02-23 04:17:23'),
(9, 'مدیر محتوا', 'manager@example.com', NULL, NULL, NULL, '2026-02-23 04:17:24', '$2y$12$sfNW5LM6VAg6b8QfGWe5s.Nk9KlcikU5xnPtRdjpZ1oyJYczi.X3O', NULL, '2026-02-23 04:17:24', '2026-02-23 04:17:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_images_car_id_foreign` (`car_id`);

--
-- Indexes for table `car_sales`
--
ALTER TABLE `car_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_sales_car_id_foreign` (`car_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_car_id_foreign` (`car_id`),
  ADD KEY `investments_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investors_national_code_unique` (`national_code`),
  ADD UNIQUE KEY `investors_user_id_unique` (`user_id`);

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
-- Indexes for table `liabilities`
--
ALTER TABLE `liabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `car_sales`
--
ALTER TABLE `car_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liabilities`
--
ALTER TABLE `liabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_sales`
--
ALTER TABLE `car_sales`
  ADD CONSTRAINT `car_sales_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investments_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investors`
--
ALTER TABLE `investors`
  ADD CONSTRAINT `investors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
