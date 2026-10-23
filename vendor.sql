-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 03:41 PM
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
-- Database: `vendor`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `details` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `label`, `image`, `details`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Home Cleaning', 'Home cleaning', 'cleaning.jpg', 'awesome', 1, NULL, NULL),
(2, 'Interior Work', 'interior', 'cleaning.jpg', 'awesome', 1, NULL, NULL),
(3, 'Foam-Jet AC Service', 'Free gas check', '3_ac_banner.jpeg', 'Deep clean AC vents for efficient cooling', 1, NULL, NULL),
(4, 'Electrical work', 'Awesome', '4_electrical_banner.jpeg', 'Electrical work', 1, NULL, NULL),
(5, 'POP Work', 'Awesome', '5_pop_banner.jpeg', 'POP work', 1, NULL, NULL),
(6, 'Interior Painting', 'Awesome', '6_interior_painting_banner.jpeg', 'Interior Painting', 1, NULL, NULL),
(7, 'Plumber Work', 'Awesome', '7_plumber_banner.jpeg', 'Plumber', 1, NULL, NULL),
(8, 'Microwave Own', 'Awesome', '8_microwave.jpeg', 'Microwave own', 1, NULL, NULL),
(9, 'Kitchen Chimney', 'Awesome', '9_chimney.jpeg', 'Chimney', 1, NULL, NULL),
(10, 'RO/Water Purifier', 'Awesome', '10_water_purifier.jpeg', 'RO/Water Purifier', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(1, 'We service all brands', 'ac_brand.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_slug` varchar(100) DEFAULT NULL,
  `image` varchar(75) DEFAULT NULL,
  `showHome` enum('inside','outside') DEFAULT NULL,
  `category_modal` varchar(50) DEFAULT NULL,
  `open_to` enum('modal','link','all') NOT NULL DEFAULT 'modal',
  `total` enum('count_1','count_2','count_3','count_4','count_5','count_6','count_7','count_8','count_9','count_10') DEFAULT NULL,
  `status` int(5) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `banner_id`, `category_name`, `category_slug`, `image`, `showHome`, `category_modal`, `open_to`, `total`, `status`, `created_at`, `updated_at`) VALUES
(185, NULL, 'Cleaning', 'cleaning', '185_cleaning.jpg', 'outside', 'cleaning', 'modal', 'count_1', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(186, NULL, 'POP & Painting', 'painting', '186_home-painting.jpg', 'outside', 'painting', 'modal', 'count_2', 1, '2026-09-08 01:40:29', '2026-09-08 06:24:39'),
(187, NULL, 'AC & Fridge', 'ac-fridge', '187_ac-appliance.jpg', 'outside', 'ac', 'modal', 'count_5', 1, '2026-09-08 01:40:47', '2026-09-08 06:25:29'),
(189, NULL, 'Washing Machine', 'washing-machine', '189_washing-machine.jpg', 'inside', 'ac', 'modal', 'count_5', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(190, NULL, 'Refrigerator', 'refrigerator', '190_refrigerator.jpg', 'inside', 'ac', 'modal', 'count_5', 1, '2026-09-08 06:55:14', '2026-09-08 06:55:16'),
(191, NULL, 'Television', 'television', '191_television.jpg', 'inside', 'ac', 'modal', 'count_5', 1, '2026-09-08 06:55:48', '2026-09-08 06:55:48'),
(192, 3, 'AC', 'ac', '187_ac-appliance.jpg', 'inside', 'ac', 'modal', 'count_5', 1, '2026-09-08 07:06:16', '2026-09-08 07:06:16'),
(193, 9, 'Chimney', 'chimney', '193_chimney.jpg', 'inside', 'appliances', 'modal', 'count_4', 1, '2026-09-08 07:12:23', '2026-09-08 07:12:23'),
(194, 8, 'Microwave', 'microwave', '194_microwave.jpg', 'inside', 'appliances', 'modal', 'count_4', 1, '2026-09-08 07:19:34', '2026-09-08 07:19:34'),
(195, 10, 'RO/Water Purifier', 'rowater-purifier', '195_rowater-purifier.jpg', 'inside', 'appliances', 'modal', 'count_4', 1, '2026-09-08 07:19:56', '2026-09-08 07:19:56'),
(196, NULL, 'Geyser', 'geyser', '196_geyser.jpeg', 'inside', 'appliances', 'modal', 'count_4', 1, '2026-09-08 07:20:14', '2026-09-08 07:20:14'),
(197, NULL, 'Air Cooler', 'air-cooler', '197_air-cooler.jpg', 'inside', 'ac', 'modal', 'count_5', 1, '2026-09-08 07:20:33', '2026-09-08 07:20:33'),
(201, NULL, 'All Services', 'all-services', '201_all-services.jpeg', 'outside', 'services', 'all', 'count_8', 1, '2026-09-08 07:20:33', '2026-09-08 07:20:33'),
(208, 1, 'Home Cleaning', 'home-cleaning', '208_home-cleaning.jpg', 'inside', 'cleaning', 'link', 'count_1', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(216, 7, 'Plumber', 'plumber', '235_plumber.jpg', 'inside', 'plumber', 'link', 'count_7', 1, '2026-09-08 01:41:01', '2026-09-08 01:41:01'),
(218, NULL, 'Carpenter', 'carpenter', '218_carpenter-work.jpg', 'outside', 'carpenter', 'modal', 'count_3', 1, '2026-09-08 01:40:29', '2026-09-08 06:24:39'),
(220, 5, 'POP Work', 'pop-work', '220_pop-work.jpg', 'inside', 'painting', 'modal', 'count_2', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(225, 2, 'Interior Work', 'interior-work', '225_interior-work.jpg', 'inside', 'carpenter', 'modal', 'count_3', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(227, NULL, 'Modular Kitchen Installation', 'modular-kitchen-installation', '227_modular-kitchen-installation.jpg', 'inside', 'carpenter', 'modal', 'count_3', 0, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(230, NULL, 'Curtain Rod', 'curtain-rod', '230_curtain-rod.jpg', 'inside', 'carpenter', 'modal', 'count_3', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(232, NULL, 'Bed & Sofa Frame Repair', 'bed-&-sofa-frame-repair', '189_bed-&-sofa-frame-repair.jpg', 'inside', 'carpenter', 'modal', 'count_3', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(233, NULL, 'Office Furniture', 'office-furniture', '189_office-furniture-repair.jpg', 'inside', 'carpenter', 'modal', 'count_3', 1, '2026-09-08 01:46:00', '2026-09-08 06:54:11'),
(235, NULL, 'Plumber & Electrician', 'plumber', '235_plumber.jpg', 'outside', 'plumber', 'modal', 'count_7', 1, '2026-09-08 01:41:01', '2026-09-08 01:41:01'),
(244, 4, 'Electrician', 'electrician', '244_electrician.jpg', 'inside', 'plumber', 'modal', 'count_6', 1, '2026-09-08 01:41:01', '2026-09-08 01:41:01'),
(251, NULL, 'Move-in / Move-out Cleaning', 'move-in-move-out-cleaning', '251_move-in-move-out-cleaning.jpg', 'inside', 'cleaning', 'link', 'count_1', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(252, NULL, 'Office Cleaning', 'office-cleaning', '252_office-cleaning.jpg', 'inside', 'cleaning', 'link', 'count_1', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(256, 6, 'Interior Painting', 'interior-painting', '256_interior-painting.jpg', 'inside', 'painting', 'link', 'count_2', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(257, NULL, 'Exterior Painting', 'exterior-painting', '257_exterior-painting.jpg', 'inside', 'painting', 'link', 'count_2', 1, '2026-09-08 01:25:09', '2026-09-08 01:25:09'),
(262, NULL, 'Appliances', 'appliances', '262_appliances.jpg', 'outside', 'appliances', 'modal', 'count_4', 1, '2026-09-08 01:40:47', '2026-09-08 06:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_product`
--

CREATE TABLE `coupon_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_coupons_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address_type` enum('Home','Office') DEFAULT 'Home',
  `default_address` int(3) DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` text NOT NULL,
  `locality` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `zip` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `user_id`, `address_type`, `default_address`, `name`, `mobile`, `address`, `locality`, `city`, `state_id`, `zip`, `created_at`, `updated_at`) VALUES
(22, 1, 'Home', 1, 'Mukesh', '9978835005', 'B-1003, Shlok Heights, Mansarovar road,', 'Chandkheda', 'Ahmedabad', 7, '382424', '2026-09-11 05:54:05', '2026-10-01 08:01:28'),
(24, 1, 'Office', 0, 'Priyanka', '09538135005', 'Surayansh  Vedura', 'Chandkheda', 'Ahmedabad', 7, '382424', '2026-09-30 05:51:37', '2026-10-01 08:01:28');

-- --------------------------------------------------------

--
-- Table structure for table `deal_stock_notifications`
--

CREATE TABLE `deal_stock_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `affiliate_product_id` bigint(20) UNSIGNED NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deal_stock_notifications`
--

INSERT INTO `deal_stock_notifications` (`id`, `user_id`, `affiliate_product_id`, `notified`, `created_at`, `updated_at`) VALUES
(5, 7, 4, 0, '2026-04-24 06:43:16', '2026-04-24 06:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_percentages_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `sub_category_id`, `discount_percentages_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(29, 15, 1, '2026-09-01', '2026-09-30', 1, NULL, NULL),
(30, 16, 1, '2026-09-01', '2026-09-30', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupons`
--

CREATE TABLE `discount_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(25) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `max_uses` varchar(10) DEFAULT NULL,
  `max_uses_user` varchar(10) DEFAULT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'fixed',
  `discount_amount` double(10,2) NOT NULL,
  `min_amount` double(10,2) DEFAULT NULL,
  `used_count` int(3) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_coupons`
--

INSERT INTO `discount_coupons` (`id`, `code`, `image`, `name`, `description`, `max_uses`, `max_uses_user`, `type`, `discount_amount`, `min_amount`, `used_count`, `status`, `starts_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'IND30', 'coupon1.jpg', 'Save 161', '30%  off on minimum purchase of Rs. 300 .', '10', '3', 'percent', 10.00, 999.00, 1, 1, '2023-11-27 11:36:57', '2026-03-31 11:36:59', '2023-11-28 06:07:01', '2023-11-29 02:18:46'),
(5, 'IND99', 'coupon2.jpg', 'Independence Day', '50%  off on minimum purchase of Rs. 300 .', '10', '2', 'fixed', 90.00, 1000.00, NULL, 1, '2026-03-01 11:39:33', '2026-03-31 11:36:59', '2023-11-28 06:09:46', '2023-11-29 02:17:51'),
(13, 'IND999', 'coupon2.jpg', 'April Fool Day', '50%  off on minimum purchase of Rs. 300 .', '10', '2', 'fixed', 999.00, 1000.00, NULL, 1, '2026-03-01 11:39:33', '2026-04-30 11:36:59', '2023-11-28 06:09:46', '2023-11-29 02:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `discount_percentages`
--

CREATE TABLE `discount_percentages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `percentage` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_percentages`
--

INSERT INTO `discount_percentages` (`id`, `percentage`, `created_at`, `updated_at`) VALUES
(1, 10, NULL, NULL),
(2, 20, NULL, NULL),
(3, 30, NULL, NULL),
(4, 40, NULL, NULL),
(5, 50, NULL, NULL),
(6, 60, NULL, NULL),
(7, 70, NULL, NULL),
(8, 80, NULL, NULL),
(9, 90, NULL, NULL);

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `title`, `details`, `created_at`, `updated_at`) VALUES
(1, 'Frequently asked questions', '[\r\n    {\r\n        \"question\": \"Are spare parts covered under warranty?\",\r\n        \"answer\": \"Yes, parts sourced by us are covered under warranty. Parts provided by the customer are not covered under warranty.\"\r\n    },\r\n{\r\n        \"question\": \"What if the same issue occurs again?\",\r\n        \"answer\": \"We offer a warranty after repair. If the same issue occurs again, we will fix it for free.\"\r\n    },\r\n{\r\n        \"question\": \"Are spare parts covered under warranty?\",\r\n        \"answer\": \"Yes, parts sourced by us are covered under warranty. Parts provided by the customer are not covered under warranty.\"\r\n    },\r\n{\r\n        \"question\": \"Are spare parts covered under warranty?\",\r\n        \"answer\": \"Yes, parts sourced by us are covered under warranty. Parts provided by the customer are not covered under warranty.\"\r\n    },\r\n{\r\n        \"question\": \"Are spare parts covered under warranty?\",\r\n        \"answer\": \"Yes, parts sourced by us are covered under warranty. Parts provided by the customer are not covered under warranty.\"\r\n    },\r\n{\r\n        \"question\": \"Are spare parts covered under warranty?\",\r\n        \"answer\": \"Yes, parts sourced by us are covered under warranty. Parts provided by the customer are not covered under warranty.\"\r\n    }\r\n]', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `includes`
--

CREATE TABLE `includes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `includes`
--

INSERT INTO `includes` (`id`, `title`, `details`, `created_at`, `updated_at`) VALUES
(1, 'What\'s included', '[\r\n    {        \r\n        \"description\": \"Free inspection & gas check followed by a service quotation.\"\r\n    },\r\n    {\r\n        \"description\": \"Final system check after repair.\"\r\n    },\r\n    {\r\n        \"description\": \"Clean-up of the work area.\"\r\n    }\r\n]', '2026-09-10 11:39:49', '2026-09-10 11:39:49');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_11_18_051106_alter_users_table', 2),
(6, '2023_11_20_052052_create_categories_table', 3),
(7, '2023_11_20_091142_create_temp_images_table', 4),
(8, '2023_11_20_123339_create_sub_categories_table', 5),
(9, '2023_11_21_045811_create_brands_table', 6),
(10, '2023_11_21_063746_create_products_table', 7),
(11, '2023_11_21_063811_create_product_images_table', 7),
(12, '2023_11_23_101727_alter_categories_table', 8),
(13, '2023_11_23_102759_alter_products_table', 9),
(14, '2023_11_23_103442_alter_sub_categories_table', 10),
(15, '2023_11_24_064315_alter_products_table', 11),
(16, '2023_11_25_072939_create_countries_table', 12),
(17, '2023_11_25_075119_create_orders_table', 13),
(18, '2023_11_25_075155_create_orders_items_table', 13),
(19, '2023_11_25_075250_create_customer_addresses_table', 13),
(20, '2023_11_25_135444_create_shipping_charges_table', 14),
(21, '2023_11_28_090521_create_discount_coupons_table', 15),
(22, '2023_11_28_091637_create_discount_coupons_table', 16),
(23, '2023_11_28_091724_create_discount_coupons_table', 17),
(24, '2023_11_28_092025_create_discount_coupons_table', 18),
(25, '2023_11_28_092115_create_discount_coupons_table', 19),
(26, '2023_11_28_092301_create_discount_coupons_table', 20),
(27, '2023_11_29_084104_alter_orders_table', 21),
(28, '2023_11_29_104758_alter_orders_table', 22),
(29, '2023_11_30_051729_create_wishlists_table', 23),
(30, '2023_12_01_060717_alter_users_table', 24),
(31, '2023_12_01_072404_create_pages_table', 25),
(32, '2023_12_02_111056_create_product_ratings_table', 26),
(33, '2023_12_29_074318_create_payments_table', 27),
(34, '2025_01_15_105251_create_sessions_table', 27),
(35, '2026_02_16_073802_create_sub_sub_categories_table', 27),
(36, '2026_02_16_085459_create_sub_sub_categories_table', 28),
(37, '2026_02_17_105618_add_sub2_category_id_to_products_table', 29),
(38, '2026_02_17_110712_add_sub2_category_id_to_products_table', 30),
(39, '2026_02_17_111316_add_sub_sub_category_id_to_products_table', 31),
(40, '2026_02_17_112636_create_colors_table', 32),
(41, '2026_02_17_112942_create_sizes_table', 33),
(42, '2026_02_17_113153_add_color_id_to_products_table', 34),
(43, '2026_02_17_113319_add_size_id_to_products_table', 35),
(44, '2026_02_19_050146_create_states_table', 36),
(45, '2026_02_21_125624_create_ratings_table', 37),
(46, '2026_02_23_054037_create_reviews_table', 38),
(47, '2026_02_23_080323_create_product_variants_table', 39),
(48, '2026_02_25_123902_add_state_id_to_orders_table', 40),
(49, '2026_02_25_124841_add_variant_fields_to_order_items_table', 41),
(50, '2026_03_06_104029_add_customer_address_id_to_properties_table', 42),
(51, '2026_03_06_122940_create_order_status_histories_table', 43),
(52, '2026_03_09_065212_create_coupon_product_table', 44),
(53, '2026_03_10_124112_create_discounts_table', 45),
(54, '2026_03_11_041927_create_discount_percentages_table', 46),
(55, '2026_03_11_043119_add_discount_percentage_id_to_products_table', 47),
(56, '2026_03_12_130417_create_payments_table', 48),
(57, '2026_03_13_101312_add_product_variant_id_to_orders_table', 49),
(58, '2026_03_13_102116_add_product_variant_id_to_orders_table', 50),
(59, '2026_03_14_070156_create_carts_table', 51),
(60, '2026_03_20_142012_create_product_color_table', 52),
(61, '2026_03_20_142026_create_product_size_table', 52),
(62, '2026_03_21_141335_add_discount_percentages_id_to_properties_table', 53),
(63, '2026_03_30_140422_add_color_id_to_order_items_table', 54),
(64, '2026_03_30_140655_add_size_id_to_order_items_table', 55),
(65, '2026_03_30_142417_add_size_id_to_order_items_table', 56),
(66, '2026_04_07_070039_add_color_id_to_properties_table', 57),
(67, '2026_04_07_071111_add_color_id_to_properties_table', 58),
(68, '2026_04_16_053849_create_affiliate_products_table', 59),
(69, '2026_04_18_114206_add_affiliate_product_id_to_affiliate_wishlist_table', 60),
(70, '2026_04_18_114611_create_affiliate_wishlists_table', 61),
(71, '2026_04_24_074106_create_stock_notifications_table', 62),
(72, '2026_04_24_081651_create_stock_notifications_table', 63),
(73, '2026_04_24_112458_create_deal_stock_notifications_table', 64),
(74, '2026_04_24_112731_create_deal_stock_notifications_table', 65),
(75, '2026_08_26_052818_create_sub_categories_table', 66),
(76, '2026_08_26_063234_add_user_id_to_vendors_table', 67),
(77, '2026_08_26_063818_add_vendor_id_to_services_table', 68),
(78, '2026_08_26_064557_create_service_images_table', 69),
(79, '2026_09_10_070647_create_processes_table', 70),
(80, '2026_09_10_071000_create_needs_table', 71),
(81, '2026_09_10_071100_create_brands_table', 71),
(82, '2026_09_10_071448_create_faqs_table', 72),
(83, '2026_09_10_071615_create_includes_table', 72),
(84, '2026_09_10_071656_create_warantees_table', 72),
(85, '2026_09_10_071745_create_highlights_table', 72),
(86, '2026_10_17_073524_create_banners_table', 73);

-- --------------------------------------------------------

--
-- Table structure for table `needs`
--

CREATE TABLE `needs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `needs`
--

INSERT INTO `needs` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(1, 'What we will need from you', 'need_from_you.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` double(10,2) NOT NULL DEFAULT 0.00,
  `grandtotal` double(10,2) NOT NULL,
  `razorpay_order_id` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(20) DEFAULT NULL,
  `razorpay_signature` text DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `status` enum('Confirmed','Placed','Packed','Shipped','Out for Delivery','Delivered','Cancelled','Returned','Exchanged') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_address_id`, `subtotal`, `grandtotal`, `razorpay_order_id`, `transaction_id`, `razorpay_signature`, `payment_status`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(329, 7, 22, 5176.00, 4227.00, 'order_SbiFimoPDvWIZr', 'pay_SbiFmTOTKicNb3', '098d259e87affa2f431855bd8a9c119016a7130907401c6b1052b70882dca1ad', 'paid', 'razorpay', 'Delivered', '2026-04-10 02:17:48', '2026-04-10 02:34:04'),
(330, 7, NULL, 5176.00, 4227.00, 'order_SbiFimoPDvWIZr', 'pay_SbiFmTOTKicNb3', '098d259e87affa2f431855bd8a9c119016a7130907401c6b1052b70882dca1ad', 'paid', 'razorpay', 'Cancelled', '2026-04-10 02:17:48', '2026-04-10 02:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `size_id` bigint(20) UNSIGNED DEFAULT NULL,
  `color_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `coupon_code` varchar(30) DEFAULT NULL,
  `coupon_id` int(10) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `discount_percent` int(10) DEFAULT NULL,
  `discounted_price` double(10,2) DEFAULT NULL,
  `shipping` double(10,2) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `scheduled_time` time(6) DEFAULT NULL,
  `subtotal` double(10,2) NOT NULL DEFAULT 0.00,
  `grandtotal` double(10,2) NOT NULL,
  `return_days` varchar(10) DEFAULT NULL,
  `delivery_min_days` date DEFAULT NULL,
  `delivery_max_days` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_histories`
--

CREATE TABLE `order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `tracking_number` varchar(20) DEFAULT NULL,
  `courier` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `cancel_reason` varchar(60) DEFAULT NULL,
  `cancel_comments` text DEFAULT NULL,
  `status` enum('Confirmed','Placed','Packed','Shipped','Out for Delivery','Delivered','Cancelled','Returned','Exchanged') NOT NULL DEFAULT 'Confirmed',
  `date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_histories`
--

INSERT INTO `order_status_histories` (`id`, `order_id`, `tracking_number`, `courier`, `note`, `cancel_reason`, `cancel_comments`, `status`, `date`, `created_at`, `updated_at`) VALUES
(261, 329, NULL, 'Shadofox', 'note', NULL, NULL, 'Confirmed', '2026-04-10 02:17:49', '2026-04-10 02:17:49', '2026-04-10 02:17:49'),
(262, 329, NULL, 'Shadofax', 'note', NULL, NULL, 'Shipped', NULL, '2026-04-10 02:33:22', '2026-04-10 02:33:22'),
(263, 329, NULL, 'Shadofax', 'note', NULL, NULL, 'Out for Delivery', NULL, '2026-04-10 02:33:51', '2026-04-10 02:33:51'),
(264, 329, NULL, 'Shadofax', 'note', NULL, NULL, 'Delivered', NULL, '2026-04-10 02:34:04', '2026-04-10 02:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `content` text DEFAULT NULL,
  `menu_order` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `content`, `menu_order`, `created_at`, `updated_at`) VALUES
(2, 'About us', 'about-us', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p><p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\"><br></span><br></p>', 2, '2023-12-01 03:33:50', '2023-12-01 03:33:50'),
(3, 'Contact', 'contact-us', '<p>Corporate Address</p>', 1, '2023-12-01 03:44:47', '2026-04-27 01:20:19');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `banner` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `highlights` varchar(255) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `bring` varchar(100) DEFAULT NULL,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notes`)),
  `tips` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tips`)),
  `professionals` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`professionals`)),
  `needs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`needs`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `processes`
--

INSERT INTO `processes` (`id`, `title`, `banner`, `brand`, `highlights`, `details`, `bring`, `notes`, `tips`, `professionals`, `needs`, `created_at`, `updated_at`) VALUES
(1, 'Our Process', NULL, NULL, NULL, '[\r\n    {\r\n        \"name\": \"Pre-service checks\",\r\n        \"description\": \"Pre-service inspection includes a free gas level check via the Co-Pilot machine.\",\r\n        \"image\": \"process_01.jpeg\"\r\n    },\r\n    {\r\n        \"name\": \"Indoor unit cleaning\",\r\n        \"description\": \"Indoor unit cleaned via foam and jet spray this includes coils and tray with spill protection.\",\r\n        \"image\": \"process_02.jpeg\"\r\n    },\r\n    {\r\n        \"name\": \"Outdoor unit cleaning\",\r\n        \"description\": \"The outdoor unit cleaned thoroughly using a jet spray to remove accumulated dirt.\",\r\n        \"image\": \"process_03.jpeg\"\r\n    },\r\n    {\r\n        \"name\": \"Gas refilling (if required)\",\r\n        \"description\": \"We\'ll provide a quote and only refill after your approval.\",\r\n        \"image\": \"process_04.jpeg\"\r\n    },\r\n    {\r\n        \"name\": \"Final clean-up\",\r\n        \"description\": \"The indoor unit & surrounding area are cleaned to ensure a neat finish.\",\r\n        \"image\": \"process_04.jpeg\"\r\n    }\r\n]', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Hair Cut for Men', 'hair_cut_for_men_banner.png', NULL, 'Highly recommended by customers for customised hairstyles, precision haircuts & hygienic grooming experience', '[\r\n    {\r\n        \"name\": \"Consultation\",\r\n        \"description\": \"Hair type, condition & style preference are checked before starting\",\r\n        \"image\": \"hair_cut_for_men.png\"\r\n    },\r\n    {\r\n        \"name\": \"Set-up\",\r\n        \"description\": \"Tools are sanitised, followed by cape, mirror & floor sheet placement.\",\r\n        \"image\": \"hair_cut_01.png\"\r\n    },\r\n    {\r\n        \"name\": \"Sectioning\",\r\n        \"description\": \"Hair is detangled & divided into small sections for an even cut\",\r\n        \"image\": \"hair_cut_02.png\"\r\n    },\r\n    {\r\n        \"name\": \"Haircut\",\r\n        \"description\": \"Hair is dampened & cut as per the selected style, with cape on\",\r\n        \"image\": \"hair_cut_03.png\"\r\n    },\r\n    {\r\n        \"name\": \"Post-service review\",\r\n        \"description\": \"Cut is reviewed with the customer & adjusted if required\",\r\n        \"image\": \"hair_cut_04.png\"\r\n    },\r\n{\r\n        \"name\": \"Clean-up\",\r\n        \"description\": \"Loose hair is removed, followed by tool sanitisation & area clean-up\",\r\n        \"image\": \"hair_cut_05.png\"\r\n    }\r\n]', 'hair_cut_for_men_bring.png', '[\r\n    {\r\n        \"description\": \"Includes haircut service only. No hair wash or beard styling.\"\r\n    },\r\n    {\r\n        \"description\": \"Ensure hair is washed before the service for best results.\"\r\n    }\r\n]', '[\r\n    {\r\n        \"description\": \"Wash hair afterwards to remove loose strands\"\r\n    },\r\n    {\r\n        \"description\": \"Use recommended styling products\"\r\n    }\r\n]', '[\r\n    {\r\n        \"description\": \"Background-verified pro\"\r\n    },\r\n    {\r\n        \"description\": \"Certified grooming professionals\"\r\n    },\r\n    {\r\n        \"description\": \"Trained in global skincare techniques\"\r\n    }\r\n]', '[\r\n    {\r\n        \"image\": \"icon_01.jpeg\"\r\n    },\r\n    {\r\n        \"image\": \"icon_02.jpeg\"\r\n    },\r\n    {    \r\n        \"image\": \"icon_03.jpeg\"\r\n    }\r\n]', NULL, NULL),
(3, 'Hair Cut for Boys', 'hair_cut_for_boys_banner.png', NULL, 'Highly recommended by customers for customised hairstyles, precision haircuts & hygienic grooming experience', '[\r\n    {\r\n        \"name\": \"Consultation\",\r\n        \"description\": \"Hair type, condition & style preference are checked before starting\",\r\n        \"image\": \"hair_cut_for_boys.png\"\r\n    },\r\n    {\r\n        \"name\": \"Set-up\",\r\n        \"description\": \"Tools are sanitised, followed by cape, mirror & floor sheet placement.\",\r\n        \"image\": \"hair_cut_01.png\"\r\n    },\r\n    {\r\n        \"name\": \"Sectioning\",\r\n        \"description\": \"Hair is detangled & divided into small sections for an even cut\",\r\n        \"image\": \"hair_cut_02.png\"\r\n    },\r\n    {\r\n        \"name\": \"Haircut\",\r\n        \"description\": \"Hair is dampened & cut as per the selected style, with cape on\",\r\n        \"image\": \"hair_cut_03.png\"\r\n    },\r\n    {\r\n        \"name\": \"Post-service review\",\r\n        \"description\": \"Cut is reviewed with the customer & adjusted if required\",\r\n        \"image\": \"hair_cut_04.png\"\r\n    },\r\n{\r\n        \"name\": \"Clean-up\",\r\n        \"description\": \"Loose hair is removed, followed by tool sanitisation & area clean-up\",\r\n        \"image\": \"hair_cut_05.png\"\r\n    }\r\n]', 'hair_cut_for_men_bring.png', '[\r\n    {\r\n        \"description\": \"Includes haircut service only. No hair wash or beard styling.\"\r\n    },\r\n    {\r\n        \"description\": \"Ensure hair is washed before the service for best results.\"\r\n    }\r\n]', '[\r\n    {\r\n        \"description\": \"Wash hair afterwards to remove loose strands\"\r\n    },\r\n    {\r\n        \"description\": \"Use recommended styling products\"\r\n    }\r\n]', '[\r\n    {\r\n        \"description\": \"Background-verified pro\"\r\n    },\r\n    {\r\n        \"description\": \"Certified grooming professionals\"\r\n    },\r\n    {\r\n        \"description\": \"Trained in global skincare techniques\"\r\n    }\r\n]', '[\r\n    {\r\n        \"image\": \"icon_01.jpeg\"\r\n    },\r\n    {\r\n        \"image\": \"icon_02.jpeg\"\r\n    },\r\n    {    \r\n        \"image\": \"icon_03.jpeg\"\r\n    }\r\n]', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `related_products` text DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_featured` enum('Yes','No') NOT NULL DEFAULT 'No',
  `recommended` varchar(10) DEFAULT NULL,
  `views` varchar(10) DEFAULT NULL,
  `discount_percentage` varchar(10) DEFAULT NULL,
  `average_rating` varchar(10) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `description`, `short_description`, `related_products`, `price`, `category_id`, `sub_category_id`, `is_featured`, `recommended`, `views`, `discount_percentage`, `average_rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Foam Jet Service', 'foam_jet_service', 'test', 'test', NULL, 449.00, 192, 14, 'Yes', NULL, NULL, NULL, '4.5', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `rating` double(3,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ratings` tinyint(3) UNSIGNED NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review` text DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `showService` int(5) DEFAULT 1,
  `status` enum('pending','approved','rejected','blocked') NOT NULL DEFAULT 'pending',
  `note` varchar(50) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `vendor_id`, `category_id`, `sub_category_id`, `showService`, `status`, `note`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 4, 192, 17, 1, 'approved', NULL, NULL, NULL, NULL),
(2, 4, 192, 14, 1, 'approved', NULL, NULL, NULL, NULL),
(26, 4, 187, 29, 1, 'approved', NULL, NULL, NULL, NULL),
(27, 2, 187, 29, 1, 'approved', NULL, NULL, NULL, NULL),
(28, 2, 187, 30, 1, 'approved', NULL, NULL, NULL, NULL),
(29, 2, 187, 31, 1, 'approved', NULL, NULL, NULL, NULL),
(30, 2, 187, 32, 1, 'approved', NULL, NULL, NULL, NULL),
(31, 2, 187, 33, 1, 'approved', NULL, NULL, NULL, NULL),
(32, 2, 187, 34, 1, 'approved', NULL, NULL, NULL, NULL),
(33, 2, 187, 35, 1, 'approved', NULL, NULL, NULL, NULL),
(34, 2, 187, 36, 1, 'approved', NULL, NULL, NULL, NULL),
(35, 2, 187, 37, 1, 'approved', NULL, NULL, NULL, NULL),
(47, 4, 225, 72, 1, 'approved', NULL, NULL, NULL, NULL),
(48, 4, 225, 73, 1, 'approved', NULL, NULL, NULL, NULL),
(49, 4, 225, 74, 1, 'approved', NULL, NULL, NULL, NULL),
(50, 4, 227, 74, 1, 'approved', NULL, NULL, NULL, NULL),
(51, 4, 225, 77, 1, 'approved', NULL, NULL, NULL, NULL),
(52, 4, 225, 78, 1, 'approved', NULL, NULL, NULL, NULL),
(53, 4, 225, 79, 1, 'approved', NULL, NULL, NULL, NULL),
(54, 4, 225, 80, 1, 'approved', NULL, NULL, NULL, NULL),
(55, 4, 244, 81, 1, 'approved', NULL, NULL, NULL, NULL),
(56, 4, 244, 82, 1, 'approved', NULL, NULL, NULL, NULL),
(57, 4, 244, 83, 1, 'approved', NULL, NULL, NULL, NULL),
(58, 4, 208, 68, 1, 'approved', NULL, NULL, NULL, NULL),
(59, 4, 208, 85, 1, 'approved', NULL, NULL, NULL, NULL),
(60, 4, 208, 86, 1, 'approved', NULL, NULL, NULL, NULL),
(61, 4, 208, 87, 1, 'approved', NULL, NULL, NULL, NULL),
(62, 4, 208, 88, 1, 'approved', NULL, NULL, NULL, NULL),
(63, 4, 220, 89, 1, 'approved', NULL, NULL, NULL, NULL),
(64, 4, 220, 90, 1, 'approved', NULL, NULL, NULL, NULL),
(65, 4, 220, 91, 1, 'approved', NULL, NULL, NULL, NULL),
(66, 4, 256, 92, 1, 'approved', NULL, NULL, NULL, NULL),
(67, 4, 256, 93, 1, 'approved', NULL, NULL, NULL, NULL),
(68, 4, 256, 94, 1, 'approved', NULL, NULL, NULL, NULL),
(69, 4, 256, 95, 1, 'approved', NULL, NULL, NULL, NULL),
(70, 4, 256, 96, 1, 'approved', NULL, NULL, NULL, NULL),
(71, 4, 216, 97, 1, 'approved', NULL, NULL, NULL, NULL),
(72, 4, 216, 98, 1, 'approved', NULL, NULL, NULL, NULL),
(73, 4, 216, 99, 1, 'approved', NULL, NULL, NULL, NULL),
(74, 4, 216, 100, 1, 'approved', NULL, NULL, NULL, NULL),
(75, 4, 216, 101, 1, 'approved', NULL, NULL, NULL, NULL),
(76, 4, 216, 102, 1, 'approved', NULL, NULL, NULL, NULL),
(77, 4, 216, 103, 1, 'approved', NULL, NULL, NULL, NULL),
(78, 4, 192, 15, 1, 'approved', NULL, NULL, NULL, NULL),
(79, 4, 192, 16, 1, 'approved', NULL, NULL, NULL, NULL),
(80, 4, 262, 106, 1, 'approved', NULL, NULL, NULL, NULL),
(81, 4, 193, 106, 1, 'approved', NULL, NULL, NULL, NULL),
(82, 4, 193, 107, 1, 'approved', NULL, NULL, NULL, NULL),
(83, 4, 193, 108, 1, 'approved', NULL, NULL, NULL, NULL),
(84, 4, 193, 110, 1, 'approved', NULL, NULL, NULL, NULL),
(85, 4, 194, 111, 1, 'approved', NULL, NULL, NULL, NULL),
(86, 4, 194, 112, 1, 'approved', NULL, NULL, NULL, NULL),
(87, 4, 194, 113, 1, 'approved', NULL, NULL, NULL, NULL),
(88, 10, 194, 114, 1, 'approved', NULL, NULL, NULL, NULL),
(89, 10, 195, 115, 1, 'approved', NULL, NULL, NULL, NULL),
(90, 10, 195, 116, 1, 'approved', NULL, NULL, NULL, NULL),
(91, 10, 195, 117, 1, 'approved', NULL, NULL, NULL, NULL),
(92, 10, 195, 118, 1, 'approved', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_images`
--

CREATE TABLE `service_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `shipping_charges`
--

CREATE TABLE `shipping_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_charges`
--

INSERT INTO `shipping_charges` (`id`, `state_id`, `amount`, `created_at`, `updated_at`) VALUES
(13, 7, 50.00, '2023-11-28 00:49:44', '2023-11-28 02:49:48'),
(20, 22, 50.00, '2026-02-19 00:59:06', '2026-02-19 00:59:06'),
(21, 37, 11.00, '2026-02-19 01:02:59', '2026-02-19 01:02:59'),
(22, 3, 60.00, '2026-02-26 00:12:00', '2026-02-26 00:12:00'),
(23, 4, 55.00, '2026-02-26 00:12:43', '2026-02-26 00:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Andhra Pradesh', 'AP', NULL, NULL),
(2, 'Arunachal Pradesh', 'AR', NULL, NULL),
(3, 'Assam', 'AS', NULL, NULL),
(4, 'Bihar', 'BR', NULL, NULL),
(5, 'Chhattisgarh', 'CG', NULL, NULL),
(6, 'Goa', 'GA', NULL, NULL),
(7, 'Gujarat', 'GJ', NULL, NULL),
(8, 'Haryana', 'HR', NULL, NULL),
(9, 'Himachal Pradesh', 'HP', NULL, NULL),
(10, 'Jharkhand', 'JH', NULL, NULL),
(11, 'Karnataka', 'KA', NULL, NULL),
(12, 'Kerala', 'KL', NULL, NULL),
(13, 'Madhya Pradesh', 'MP', NULL, NULL),
(14, 'Maharashtra', 'MH', NULL, NULL),
(15, 'Manipur', 'MN', NULL, NULL),
(16, 'Meghalaya', 'ML', NULL, NULL),
(17, 'Mizoram', 'MZ', NULL, NULL),
(18, 'Nagaland', 'NL', NULL, NULL),
(19, 'Odisha', 'OD', NULL, NULL),
(20, 'Punjab', 'PB', NULL, NULL),
(21, 'Rajasthan', 'RJ', NULL, NULL),
(22, 'Sikkim', 'SK', NULL, NULL),
(23, 'Tamil Nadu', 'TN', NULL, NULL),
(24, 'Telangana', 'TS', NULL, NULL),
(25, 'Tripura', 'TR', NULL, NULL),
(26, 'Uttar Pradesh', 'UP', NULL, NULL),
(27, 'Uttarakhand', 'UK', NULL, NULL),
(28, 'West Bengal', 'WB', NULL, NULL),
(29, 'Andaman and Nicobar Islands', 'AN', NULL, NULL),
(30, 'Chandigarh', 'CH', NULL, NULL),
(31, 'Dadra and Nagar Haveli and Daman and Diu', 'DN', NULL, NULL),
(32, 'Delhi', 'DL', NULL, NULL),
(33, 'Jammu and Kashmir', 'JK', NULL, NULL),
(34, 'Ladakh', 'LA', NULL, NULL),
(35, 'Lakshadweep', 'LD', NULL, NULL),
(36, 'Puducherry', 'PY', NULL, NULL),
(37, 'Rest of the state', 'RS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_notifications`
--

CREATE TABLE `stock_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `process_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_name` varchar(75) NOT NULL,
  `sub_category_slug` varchar(75) NOT NULL,
  `image` varchar(75) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `price_type` enum('fixed','hourly','starting_from','quote') NOT NULL DEFAULT 'fixed',
  `time` varchar(10) DEFAULT NULL,
  `instant` enum('yes','no') DEFAULT 'no',
  `sort_order` int(5) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `process_id`, `sub_category_name`, `sub_category_slug`, `image`, `description`, `price`, `price_type`, `time`, `instant`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(14, 192, 1, 'Service', 'service', '14_service.jpg', NULL, 399.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(15, 192, 1, 'Repair & gas refill', 'repair_&_gas_refill', '15_repair_&_gas_refill.jpg', NULL, 999.00, 'fixed', '2 hrs', 'no', 3, 1, NULL, NULL),
(16, 192, 1, 'Installation/Uninstallation', 'installation_uninstallation', '16_installation_uninstallation.jpg', NULL, 299.00, 'fixed', '2 hrs', 'no', 4, 1, NULL, NULL),
(17, 192, 1, 'Super saver packages', 'super_saver_packages', '17_super_saver_packages.jpg', NULL, 799.00, 'fixed', '2 hrs', 'no', 1, 1, NULL, NULL),
(29, 187, NULL, 'Foam-jet AC service', 'foam-jet-ac-service', '29_foam-jet-ac-service.jpeg', NULL, 599.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(30, 187, NULL, 'AC Repair', 'ac-repair', '30_ac-repair.png', NULL, 299.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(31, 187, NULL, 'Water Purifier Service & Installation', 'water-purifier-service-&-installation', '31_water-purifier-service-&-installation.jpeg', NULL, 299.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(32, 187, NULL, 'AC uninstallation', 'ac-uninstallation', '32_ac-uninstallation.png', NULL, 649.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(33, 187, NULL, 'TV check-up', 'tv-check-up', '33_tv-check-up.jpeg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(34, 187, NULL, 'Geyser check-up', 'geyser-check-up', '34_geyser-check-up.jpeg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(35, 187, NULL, 'Microwave check-up', 'microwave-check-up', '35_microwave-check-up.jpeg', NULL, 199.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(36, 187, NULL, 'Deep chimney service', 'deep-chimney-service', '36_deep-chimney-service.jpeg', NULL, 1199.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(37, 187, NULL, 'AC installation', 'ac-installation', '37_ac-installation.png', NULL, 799.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(68, 208, 1, 'Bathroom cleaning', 'bathroom-cleaning', '208_bathroom-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(70, 185, NULL, 'Intense bathroom cleaning', 'intense-bathroom-cleaning', '71_intense-bathroom-cleaning.jpeg', NULL, 499.00, 'fixed', NULL, 'yes', 0, 1, NULL, NULL),
(72, 225, 1, 'Carpenter Service', 'carpenter-service', '225_carpenter-work.jpg', NULL, 299.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(73, 225, 1, 'Modular Kitchen', 'modular-kitchen', '225_modular-kitchen.jpg', NULL, 299.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(74, 225, 1, 'Wooden Partition', 'wooden-partition', '225_wooden-partition.jpg', NULL, 2299.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(76, 187, 1, 'Washing Machine', 'washing-machine', '225_wooden-partition.jpg', NULL, 2299.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(77, 225, 1, 'Wardrobe', 'wardrobe', '225_wardrobe.jpg', NULL, 299.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(78, 225, 1, 'Hinges & Handle replacement', 'hinges-&Handle-replacement', '225_hinges-&Handle-replacement.jpg', NULL, 199.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(79, 225, 1, 'Carpenter for small repairs', 'carpenter-for-small-repairs', '225_carpenter-for-small-repairs.jpg', NULL, 99.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(80, 225, 1, 'Drawer & Cabinet Repair', 'drawer-&-cabinet-repairs', '225_drawer-&-cabinet-repairs.jpg', NULL, 99.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(81, 244, 1, 'Fan Installation', 'fan-installation', '209_fan-installation.jpg', NULL, 99.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(82, 244, 1, 'Light Installation & repair', 'light-installation-&-repair', '209_light-installation-&-repair.jpg', NULL, 99.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(83, 244, 1, 'Geyser electrical connection', 'geyser-electrical-connection', '209_geyser-electrical-connection.jpg', NULL, 99.00, 'fixed', '2 hrs', 'yes', 2, 1, NULL, NULL),
(85, 208, 1, 'Kitchen cleaning', 'kitchen-cleaning', '208_kitchen-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(86, 208, 1, 'Water tank cleaning', 'water-tank-cleaning', '208_water-tank-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(87, 208, 1, 'Sofa cleaning', 'sofa-cleaning', '208_sofa-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(88, 208, 1, 'Balcony cleaning', 'balcony-cleaning', '208_balcony-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(89, 220, 1, 'False Ceiling', 'false-ceiling', '220_false-ceiling.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(90, 220, 1, 'Gypsum Board Ceiling', 'gypsum-board-ceiling', '220_gypsum-board-ceiling.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(91, 220, 1, 'POP cornice & border work', 'pop-cornice-&-border-work', '220_pop-cornice-&-border-work.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(92, 256, 1, 'Home paintings', 'home-paintings', '256_home-paintings.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(93, 256, 1, 'Waterproof paintings', 'waterproof-painting', '256_waterproof-painting.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(94, 256, 1, 'Door and window paintings', 'door-and-window-paintings', '256_door-and-window-paintings.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(95, 256, 1, 'Wood painting and polishing', 'wood-painting-and-polishing', '256_wood-painting-and-polishing.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(96, 256, 1, 'Office paintings', 'office-paintings', '256_office-paintings.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(97, 216, 1, 'Tap repair & installation', 'tap-repair-&-installation', '216_tap-repair-&-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(98, 216, 1, 'Wash basin installation', 'wash-basin-installation', '216_wash-basin-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(99, 216, 1, 'Toilet repair & installation', 'toilet-repair-&-installation', '216_toilet-repair-&-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(100, 216, 1, 'Water tank Installation', 'water-tank-installation', '216_water-tank-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(101, 216, 1, 'Water pipe Installation', 'water-pipe-installation', '216_water-pipe-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(102, 216, 1, 'Plumber on demand', 'plumber-on-demand', '216_plumber-on-demand.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(103, 216, 1, 'Pipe leakage repair', 'pipe-leakage-repair', '216_pipe-leakage-repair.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(104, 244, 1, 'Water motor installation', 'water-motor-installation', '244_water-motor-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(105, 244, 1, 'Inverter Installation & Repair', 'inverter-installation-&-repair', '244_inverter-installation-&-repair.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(106, 193, 1, 'Chimney cleaning', 'chimney-cleaning', '193_kitchen-chimney-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(107, 193, 1, 'Chimney installation', 'chimney-installation', '193_kitchen-chimney-installation.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(108, 193, 1, 'Chimney motor repair & replacement', 'chimney-motor-repair-&-replacement', '193_chimney-motor-repair-&-replacement.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(109, 193, 1, 'Chimney deep cleaning', 'chimney-deep-cleaning', '193_kitchen-chimney-deep-cleaning.jpg', NULL, 249.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(110, 193, 1, 'Chimney duct cleaning & installation', 'chimney-duct-cleaning-&-installation', '193_chimney-duct-cleaning-&-installation.jpg', NULL, 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(111, 194, 1, 'Microwave oven repair', 'microwave-oven-repair', '194_microwave-oven-repair.jpg', NULL, 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(112, 194, 1, 'Microwave oven Inspection', 'microwave-oven-inspection', '194_microwave-oven-inspection.jpg', NULL, 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(113, 194, 1, 'Microwave oven door repair', 'microwave-oven-door-repair', '194_microwave-oven-door-repair.jpg', NULL, 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(114, 194, 1, 'Microwave oven heating repair', 'microwave-oven-heating-repair', '194_microwave-oven-heating-repair.jpg', 'test', 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(115, 195, 1, 'Installing a new water purifier', 'installing-a-new-water-purifier', '195_installing-a-new-water-purifier.jpg', 'test', 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(116, 195, 1, 'Replacing RO filters', 'replacing-ro-filters', '195_replacing-ro-filters.jpg', 'test', 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(117, 195, 1, 'General maintenance and inspection', 'general-maintenance-and-inspection', '195_general-maintenance-and-inspection.jpg', 'test', 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL),
(118, 195, 1, 'Annual maintenance contract', 'annual-maintenance-contract', '195_annual-maintenance-contract.jpg', 'test', 49.00, 'fixed', NULL, 'no', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `temp_images`
--

CREATE TABLE `temp_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temp_images`
--

INSERT INTO `temp_images` (`id`, `name`, `created_at`, `updated_at`) VALUES
(407, '1775549304.jpg', '2026-04-07 02:38:24', '2026-04-07 02:38:24'),
(408, '1775549314.jpg', '2026-04-07 02:38:34', '2026-04-07 02:38:34'),
(409, '1775549420.jpg', '2026-04-07 02:40:20', '2026-04-07 02:40:20'),
(410, '1775549559.jpg', '2026-04-07 02:42:39', '2026-04-07 02:42:39'),
(411, '1775549628.jpg', '2026-04-07 02:43:48', '2026-04-07 02:43:48'),
(412, '1775560644.jpg', '2026-04-07 05:47:24', '2026-04-07 05:47:24'),
(413, '1775560663.JPG', '2026-04-07 05:47:43', '2026-04-07 05:47:43'),
(414, '1775560663.JPG', '2026-04-07 05:47:43', '2026-04-07 05:47:43'),
(415, '1775560664.JPG', '2026-04-07 05:47:44', '2026-04-07 05:47:44'),
(416, '1775560993.JPG', '2026-04-07 05:53:13', '2026-04-07 05:53:13'),
(417, '1775560995.JPG', '2026-04-07 05:53:15', '2026-04-07 05:53:15'),
(418, '1775560997.JPG', '2026-04-07 05:53:17', '2026-04-07 05:53:17'),
(419, '1775561001.JPG', '2026-04-07 05:53:21', '2026-04-07 05:53:21'),
(420, '1775561005.JPG', '2026-04-07 05:53:25', '2026-04-07 05:53:25'),
(421, '1775561010.JPG', '2026-04-07 05:53:30', '2026-04-07 05:53:30'),
(422, '1775561418.JPG', '2026-04-07 06:00:18', '2026-04-07 06:00:18'),
(423, '1775561418.JPG', '2026-04-07 06:00:18', '2026-04-07 06:00:18'),
(424, '1775561418.JPG', '2026-04-07 06:00:18', '2026-04-07 06:00:18'),
(425, '1775561426.JPG', '2026-04-07 06:00:26', '2026-04-07 06:00:26'),
(426, '1775561429.JPG', '2026-04-07 06:00:29', '2026-04-07 06:00:29'),
(427, '1775561576.JPG', '2026-04-07 06:02:56', '2026-04-07 06:02:56'),
(428, '1775561579.JPG', '2026-04-07 06:02:59', '2026-04-07 06:02:59'),
(429, '1775561637.JPG', '2026-04-07 06:03:57', '2026-04-07 06:03:57'),
(430, '1775561686.JPG', '2026-04-07 06:04:46', '2026-04-07 06:04:46'),
(431, '1775561712.JPG', '2026-04-07 06:05:12', '2026-04-07 06:05:12'),
(432, '1775562006.JPG', '2026-04-07 06:10:06', '2026-04-07 06:10:06'),
(433, '1775562019.JPG', '2026-04-07 06:10:19', '2026-04-07 06:10:19'),
(434, '1775562021.JPG', '2026-04-07 06:10:21', '2026-04-07 06:10:21'),
(435, '1775562072.JPG', '2026-04-07 06:11:12', '2026-04-07 06:11:12'),
(436, '1775562111.JPG', '2026-04-07 06:11:51', '2026-04-07 06:11:51'),
(437, '1775562254.JPG', '2026-04-07 06:14:14', '2026-04-07 06:14:14'),
(438, '1775562256.JPG', '2026-04-07 06:14:16', '2026-04-07 06:14:16'),
(439, '1775562369.JPG', '2026-04-07 06:16:09', '2026-04-07 06:16:09'),
(440, '1775562372.JPG', '2026-04-07 06:16:12', '2026-04-07 06:16:12'),
(441, '1775562548.JPG', '2026-04-07 06:19:08', '2026-04-07 06:19:08'),
(442, '1775562550.JPG', '2026-04-07 06:19:10', '2026-04-07 06:19:10'),
(443, '1775562625.JPG', '2026-04-07 06:20:25', '2026-04-07 06:20:25'),
(444, '1775562627.JPG', '2026-04-07 06:20:27', '2026-04-07 06:20:27'),
(445, '1775562629.JPG', '2026-04-07 06:20:29', '2026-04-07 06:20:29'),
(446, '1775562632.JPG', '2026-04-07 06:20:32', '2026-04-07 06:20:32'),
(447, '1775562634.JPG', '2026-04-07 06:20:34', '2026-04-07 06:20:34'),
(448, '1775562636.JPG', '2026-04-07 06:20:36', '2026-04-07 06:20:36'),
(449, '1775562863.JPG', '2026-04-07 06:24:23', '2026-04-07 06:24:23'),
(450, '1775562866.JPG', '2026-04-07 06:24:26', '2026-04-07 06:24:26'),
(451, '1775562868.JPG', '2026-04-07 06:24:28', '2026-04-07 06:24:28'),
(452, '1775562896.JPG', '2026-04-07 06:24:56', '2026-04-07 06:24:56'),
(453, '1775562899.JPG', '2026-04-07 06:24:59', '2026-04-07 06:24:59'),
(454, '1775562909.JPG', '2026-04-07 06:25:09', '2026-04-07 06:25:09'),
(455, '1775563022.JPG', '2026-04-07 06:27:02', '2026-04-07 06:27:02'),
(456, '1775563025.JPG', '2026-04-07 06:27:05', '2026-04-07 06:27:05'),
(457, '1775563027.JPG', '2026-04-07 06:27:07', '2026-04-07 06:27:07'),
(458, '1775563123.JPG', '2026-04-07 06:28:43', '2026-04-07 06:28:43'),
(459, '1775563126.JPG', '2026-04-07 06:28:46', '2026-04-07 06:28:46'),
(460, '1775563128.JPG', '2026-04-07 06:28:48', '2026-04-07 06:28:48'),
(461, '1775563321.JPG', '2026-04-07 06:32:01', '2026-04-07 06:32:01'),
(462, '1775563323.JPG', '2026-04-07 06:32:03', '2026-04-07 06:32:03'),
(463, '1775563325.JPG', '2026-04-07 06:32:05', '2026-04-07 06:32:05'),
(464, '1791360886.JPG', '2026-10-07 02:44:46', '2026-10-07 02:44:46'),
(465, '1791360920.JPG', '2026-10-07 02:45:20', '2026-10-07 02:45:20'),
(466, '1791360960.JPG', '2026-10-07 02:46:00', '2026-10-07 02:46:00'),
(467, '1791361065.JPG', '2026-10-07 02:47:45', '2026-10-07 02:47:45'),
(468, '1791372221.jpg', '2026-10-07 05:53:41', '2026-10-07 05:53:41'),
(469, '1791372303.jpg', '2026-10-07 05:55:03', '2026-10-07 05:55:03'),
(470, '1791372334.jpg', '2026-10-07 05:55:34', '2026-10-07 05:55:34'),
(471, '1791372407.jpg', '2026-10-07 05:56:47', '2026-10-07 05:56:47'),
(472, '1791372462.jpg', '2026-10-07 05:57:42', '2026-10-07 05:57:42'),
(473, '1791372533.jpg', '2026-10-07 05:58:53', '2026-10-07 05:58:53'),
(474, '1791372580.jpg', '2026-10-07 05:59:40', '2026-10-07 05:59:40'),
(475, '1791372627.jpg', '2026-10-07 06:00:27', '2026-10-07 06:00:27'),
(476, '1791372674.jpg', '2026-10-07 06:01:14', '2026-10-07 06:01:14'),
(477, '1791372717.jpg', '2026-10-07 06:01:57', '2026-10-07 06:01:57'),
(478, '1791372722.jpg', '2026-10-07 06:02:02', '2026-10-07 06:02:02'),
(479, '1791372778.jpg', '2026-10-07 06:02:58', '2026-10-07 06:02:58'),
(480, '1791372872.jpg', '2026-10-07 06:04:32', '2026-10-07 06:04:32'),
(481, '1791373043.jpg', '2026-10-07 06:07:23', '2026-10-07 06:07:23'),
(482, '1791373049.jpg', '2026-10-07 06:07:29', '2026-10-07 06:07:29'),
(483, '1791373094.jpg', '2026-10-07 06:08:14', '2026-10-07 06:08:14'),
(484, '1791373098.jpg', '2026-10-07 06:08:18', '2026-10-07 06:08:18'),
(485, '1791373188.jpg', '2026-10-07 06:09:48', '2026-10-07 06:09:48'),
(486, '1791373192.jpg', '2026-10-07 06:09:52', '2026-10-07 06:09:52'),
(487, '1791373197.jpg', '2026-10-07 06:09:57', '2026-10-07 06:09:57'),
(488, '1791373218.jpg', '2026-10-07 06:10:18', '2026-10-07 06:10:18'),
(489, '1791373227.jpg', '2026-10-07 06:10:27', '2026-10-07 06:10:27'),
(490, '1791373253.jpg', '2026-10-07 06:10:53', '2026-10-07 06:10:53'),
(491, '1791373257.jpg', '2026-10-07 06:10:57', '2026-10-07 06:10:57'),
(492, '1791373261.jpg', '2026-10-07 06:11:01', '2026-10-07 06:11:01'),
(493, '1791373312.jpg', '2026-10-07 06:11:52', '2026-10-07 06:11:52'),
(494, '1791373316.jpg', '2026-10-07 06:11:56', '2026-10-07 06:11:56'),
(495, '1791373319.jpg', '2026-10-07 06:11:59', '2026-10-07 06:11:59'),
(496, '1791373375.jpg', '2026-10-07 06:12:55', '2026-10-07 06:12:55'),
(497, '1791373378.jpg', '2026-10-07 06:12:58', '2026-10-07 06:12:58'),
(498, '1791373381.jpg', '2026-10-07 06:13:01', '2026-10-07 06:13:01'),
(499, '1791373462.jpg', '2026-10-07 06:14:22', '2026-10-07 06:14:22'),
(500, '1791373467.jpg', '2026-10-07 06:14:27', '2026-10-07 06:14:27'),
(501, '1791373471.jpg', '2026-10-07 06:14:31', '2026-10-07 06:14:31'),
(502, '1791373476.jpg', '2026-10-07 06:14:36', '2026-10-07 06:14:36'),
(503, '1791373480.jpg', '2026-10-07 06:14:40', '2026-10-07 06:14:40'),
(504, '1791373528.jpg', '2026-10-07 06:15:28', '2026-10-07 06:15:28'),
(505, '1791373531.jpg', '2026-10-07 06:15:31', '2026-10-07 06:15:31'),
(506, '1791373567.jpg', '2026-10-07 06:16:07', '2026-10-07 06:16:07'),
(507, '1791373570.jpg', '2026-10-07 06:16:10', '2026-10-07 06:16:10'),
(508, '1791373588.jpg', '2026-10-07 06:16:28', '2026-10-07 06:16:28'),
(509, '1791373590.jpg', '2026-10-07 06:16:30', '2026-10-07 06:16:30'),
(510, '1791373593.jpg', '2026-10-07 06:16:33', '2026-10-07 06:16:33'),
(511, '1791373610.jpg', '2026-10-07 06:16:50', '2026-10-07 06:16:50'),
(512, '1791373752.jpg', '2026-10-07 06:19:12', '2026-10-07 06:19:12'),
(513, '1791373756.jpg', '2026-10-07 06:19:16', '2026-10-07 06:19:16'),
(514, '1791373825.jpg', '2026-10-07 06:20:25', '2026-10-07 06:20:25'),
(515, '1791373855.jpg', '2026-10-07 06:20:55', '2026-10-07 06:20:55'),
(516, '1791373901.jpg', '2026-10-07 06:21:41', '2026-10-07 06:21:41'),
(517, '1791373953.jpg', '2026-10-07 06:22:33', '2026-10-07 06:22:33'),
(518, '1791374015.jpg', '2026-10-07 06:23:35', '2026-10-07 06:23:35'),
(519, '1791374121.jpg', '2026-10-07 06:25:21', '2026-10-07 06:25:21'),
(520, '1791374134.jpg', '2026-10-07 06:25:34', '2026-10-07 06:25:34'),
(521, '1791374137.jpg', '2026-10-07 06:25:37', '2026-10-07 06:25:37'),
(522, '1791374143.jpg', '2026-10-07 06:25:43', '2026-10-07 06:25:43'),
(523, '1791374147.jpg', '2026-10-07 06:25:47', '2026-10-07 06:25:47'),
(524, '1791374200.jpg', '2026-10-07 06:26:40', '2026-10-07 06:26:40'),
(525, '1791374201.jpg', '2026-10-07 06:26:41', '2026-10-07 06:26:41'),
(526, '1791374203.jpg', '2026-10-07 06:26:43', '2026-10-07 06:26:43'),
(527, '1791374203.jpg', '2026-10-07 06:26:43', '2026-10-07 06:26:43'),
(528, '1791374204.jpg', '2026-10-07 06:26:44', '2026-10-07 06:26:44'),
(529, '1791374229.jpg', '2026-10-07 06:27:09', '2026-10-07 06:27:09'),
(530, '1791374233.jpg', '2026-10-07 06:27:13', '2026-10-07 06:27:13'),
(531, '1791374303.jpg', '2026-10-07 06:28:23', '2026-10-07 06:28:23'),
(532, '1791374304.jpg', '2026-10-07 06:28:24', '2026-10-07 06:28:24'),
(533, '1791374305.jpg', '2026-10-07 06:28:25', '2026-10-07 06:28:25'),
(534, '1791374306.jpg', '2026-10-07 06:28:26', '2026-10-07 06:28:26'),
(535, '1791374307.jpg', '2026-10-07 06:28:27', '2026-10-07 06:28:27'),
(536, '1791374376.jpg', '2026-10-07 06:29:36', '2026-10-07 06:29:36'),
(537, '1791374377.jpg', '2026-10-07 06:29:37', '2026-10-07 06:29:37'),
(538, '1791374378.jpg', '2026-10-07 06:29:38', '2026-10-07 06:29:38'),
(539, '1791374379.jpg', '2026-10-07 06:29:39', '2026-10-07 06:29:39'),
(540, '1791374380.jpg', '2026-10-07 06:29:40', '2026-10-07 06:29:40'),
(541, '1791374383.jpg', '2026-10-07 06:29:43', '2026-10-07 06:29:43'),
(542, '1791374412.jpg', '2026-10-07 06:30:12', '2026-10-07 06:30:12'),
(543, '1791374413.jpg', '2026-10-07 06:30:13', '2026-10-07 06:30:13'),
(544, '1791374414.jpg', '2026-10-07 06:30:14', '2026-10-07 06:30:14'),
(545, '1791374415.jpg', '2026-10-07 06:30:15', '2026-10-07 06:30:15'),
(546, '1791374416.jpg', '2026-10-07 06:30:16', '2026-10-07 06:30:16'),
(547, '1791374482.jpg', '2026-10-07 06:31:22', '2026-10-07 06:31:22'),
(548, '1791374483.jpg', '2026-10-07 06:31:23', '2026-10-07 06:31:23'),
(549, '1791374484.jpg', '2026-10-07 06:31:24', '2026-10-07 06:31:24'),
(550, '1791374485.jpg', '2026-10-07 06:31:25', '2026-10-07 06:31:25'),
(551, '1791374486.jpg', '2026-10-07 06:31:26', '2026-10-07 06:31:26'),
(552, '1791374534.jpg', '2026-10-07 06:32:14', '2026-10-07 06:32:14'),
(553, '1791374535.jpg', '2026-10-07 06:32:15', '2026-10-07 06:32:15'),
(554, '1791374537.jpg', '2026-10-07 06:32:17', '2026-10-07 06:32:17'),
(555, '1791374537.jpg', '2026-10-07 06:32:17', '2026-10-07 06:32:17'),
(556, '1791374538.jpg', '2026-10-07 06:32:18', '2026-10-07 06:32:18'),
(557, '1791374539.jpg', '2026-10-07 06:32:19', '2026-10-07 06:32:19'),
(558, '1791458938.JPG', '2026-10-08 05:58:58', '2026-10-08 05:58:58'),
(559, '1791458946.JPG', '2026-10-08 05:59:06', '2026-10-08 05:59:06'),
(560, '1791458947.JPG', '2026-10-08 05:59:07', '2026-10-08 05:59:07'),
(561, '1791458950.JPG', '2026-10-08 05:59:10', '2026-10-08 05:59:10'),
(562, '1791458952.JPG', '2026-10-08 05:59:12', '2026-10-08 05:59:12'),
(563, '1791458954.JPG', '2026-10-08 05:59:14', '2026-10-08 05:59:14'),
(564, '1791459046.JPG', '2026-10-08 06:00:46', '2026-10-08 06:00:46'),
(565, '1791459136.JPG', '2026-10-08 06:02:16', '2026-10-08 06:02:16'),
(566, '1791459605.JPG', '2026-10-08 06:10:05', '2026-10-08 06:10:05'),
(567, '1791460566.JPG', '2026-10-08 06:26:06', '2026-10-08 06:26:06'),
(568, '1791460617.JPG', '2026-10-08 06:26:57', '2026-10-08 06:26:57'),
(569, '1791460619.JPG', '2026-10-08 06:26:59', '2026-10-08 06:26:59'),
(570, '1791460621.JPG', '2026-10-08 06:27:01', '2026-10-08 06:27:01'),
(571, '1791460625.JPG', '2026-10-08 06:27:05', '2026-10-08 06:27:05'),
(572, '1791460626.JPG', '2026-10-08 06:27:06', '2026-10-08 06:27:06'),
(573, '1791460718.JPG', '2026-10-08 06:28:38', '2026-10-08 06:28:38'),
(574, '1791460720.JPG', '2026-10-08 06:28:40', '2026-10-08 06:28:40'),
(575, '1791460723.JPG', '2026-10-08 06:28:43', '2026-10-08 06:28:43'),
(576, '1791460725.JPG', '2026-10-08 06:28:45', '2026-10-08 06:28:45'),
(577, '1791460727.JPG', '2026-10-08 06:28:47', '2026-10-08 06:28:47'),
(578, '1791461119.JPG', '2026-10-08 06:35:19', '2026-10-08 06:35:19'),
(579, '1791461121.JPG', '2026-10-08 06:35:21', '2026-10-08 06:35:21'),
(580, '1791461123.JPG', '2026-10-08 06:35:23', '2026-10-08 06:35:23'),
(581, '1791461125.JPG', '2026-10-08 06:35:25', '2026-10-08 06:35:25'),
(582, '1791461127.JPG', '2026-10-08 06:35:27', '2026-10-08 06:35:27'),
(583, '1791461224.JPG', '2026-10-08 06:37:04', '2026-10-08 06:37:04'),
(584, '1791461638.JPG', '2026-10-08 06:43:58', '2026-10-08 06:43:58'),
(585, '1791461639.JPG', '2026-10-08 06:43:59', '2026-10-08 06:43:59'),
(586, '1791461639.JPG', '2026-10-08 06:43:59', '2026-10-08 06:43:59'),
(587, '1791461640.JPG', '2026-10-08 06:44:00', '2026-10-08 06:44:00'),
(588, '1791461640.JPG', '2026-10-08 06:44:00', '2026-10-08 06:44:00'),
(589, '1791461765.JPG', '2026-10-08 06:46:05', '2026-10-08 06:46:05'),
(590, '1791461767.JPG', '2026-10-08 06:46:07', '2026-10-08 06:46:07'),
(591, '1791461770.JPG', '2026-10-08 06:46:10', '2026-10-08 06:46:10'),
(592, '1791461924.JPG', '2026-10-08 06:48:44', '2026-10-08 06:48:44'),
(593, '1791461925.JPG', '2026-10-08 06:48:45', '2026-10-08 06:48:45'),
(594, '1791461926.JPG', '2026-10-08 06:48:46', '2026-10-08 06:48:46'),
(595, '1791461926.JPG', '2026-10-08 06:48:46', '2026-10-08 06:48:46'),
(596, '1791462051.JPG', '2026-10-08 06:50:51', '2026-10-08 06:50:51'),
(597, '1791462052.JPG', '2026-10-08 06:50:52', '2026-10-08 06:50:52'),
(598, '1791462052.JPG', '2026-10-08 06:50:52', '2026-10-08 06:50:52'),
(599, '1791463019.JPG', '2026-10-08 07:06:59', '2026-10-08 07:06:59'),
(600, '1791463022.JPG', '2026-10-08 07:07:02', '2026-10-08 07:07:02'),
(601, '1791463024.JPG', '2026-10-08 07:07:04', '2026-10-08 07:07:04'),
(602, '1791463026.JPG', '2026-10-08 07:07:06', '2026-10-08 07:07:06'),
(603, '1791463028.JPG', '2026-10-08 07:07:08', '2026-10-08 07:07:08'),
(604, '1791463200.JPG', '2026-10-08 07:10:00', '2026-10-08 07:10:00'),
(605, '1791463203.JPG', '2026-10-08 07:10:03', '2026-10-08 07:10:03'),
(606, '1791463204.JPG', '2026-10-08 07:10:04', '2026-10-08 07:10:04'),
(607, '1791463207.JPG', '2026-10-08 07:10:07', '2026-10-08 07:10:07'),
(608, '1791463208.JPG', '2026-10-08 07:10:08', '2026-10-08 07:10:08'),
(609, '1791463883.JPG', '2026-10-08 07:21:23', '2026-10-08 07:21:23'),
(610, '1791463884.JPG', '2026-10-08 07:21:24', '2026-10-08 07:21:24'),
(611, '1791463884.JPG', '2026-10-08 07:21:24', '2026-10-08 07:21:24'),
(612, '1791463885.JPG', '2026-10-08 07:21:25', '2026-10-08 07:21:25'),
(613, '1791463885.JPG', '2026-10-08 07:21:25', '2026-10-08 07:21:25'),
(614, '1791547353.JPG', '2026-10-09 06:32:33', '2026-10-09 06:32:33'),
(615, '1791547576.JPG', '2026-10-09 06:36:16', '2026-10-09 06:36:16'),
(616, '1791547860.JPG', '2026-10-09 06:41:00', '2026-10-09 06:41:00'),
(617, '1791547891.JPG', '2026-10-09 06:41:31', '2026-10-09 06:41:31'),
(618, '1791549986.JPG', '2026-10-09 07:16:26', '2026-10-09 07:16:26'),
(619, '1791551719.JPG', '2026-10-09 07:45:19', '2026-10-09 07:45:19'),
(620, '1791551731.JPG', '2026-10-09 07:45:31', '2026-10-09 07:45:31'),
(621, '1791551837.JPG', '2026-10-09 07:47:17', '2026-10-09 07:47:17'),
(622, '1791551978.JPG', '2026-10-09 07:49:38', '2026-10-09 07:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT 'male',
  `role` enum('user','vendor','admin') NOT NULL DEFAULT 'user',
  `avatar_color` varchar(10) DEFAULT NULL,
  `image` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` int(10) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `mobile`, `birthdate`, `gender`, `role`, `avatar_color`, `image`, `status`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'mukeshbhavsar210@gmail.com', '', NULL, NULL, NULL, 'admin', NULL, '', 1, NULL, '$2y$12$Iy5Wh1TVAkCYAvaefrR71OEKD4QDjhnnWBxknqjwnioSSM6sAJMnO', 1, NULL, '2023-11-17 23:52:06', '2023-12-01 05:59:34'),
(3, 'Priyanka', 'p.bhavsar2610@gmail', '9538135005', '9978812324', '2026-02-18', 'female', 'vendor', NULL, 'priyanka.png', 1, NULL, '$2y$12$Iy5Wh1TVAkCYAvaefrR71OEKD4QDjhnnWBxknqjwnioSSM6sAJMnO', 1, NULL, '2023-11-25 00:32:42', '2026-03-04 00:10:24'),
(7, 'Dhruv Bhavsar', 'dhruvbhavsar210@gmail.com', '9538135005', '9978812324', '2026-02-18', 'male', 'vendor', '#FF5733', '', 1, NULL, '$2y$12$Iy5Wh1TVAkCYAvaefrR71OEKD4QDjhnnWBxknqjwnioSSM6sAJMnO', 1, NULL, '2023-11-25 00:32:42', '2026-03-31 07:01:33'),
(16, 'Sona', 'sonabhavsar@gmail.com', '9978835005', '9978835005', NULL, 'male', '', '#2ECC71', NULL, 1, NULL, '$2y$10$70tWC5qyH2GA03SyHV/.oeaLcZKtYAbyI9X1P.8KaTU.iVMisMKma', 1, NULL, '2026-10-01 06:12:40', '2026-10-01 06:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `business_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `phone` varchar(12) DEFAULT NULL,
  `mobile` varchar(12) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','approved','rejected','blocked') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `business_name`, `slug`, `services`, `phone`, `mobile`, `email`, `logo`, `address`, `city`, `state`, `pincode`, `latitude`, `longitude`, `status`, `admin_note`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 7, 'Hitachi AC Services Pvt. Ltd.', 'hitachi_ac_services_pvt_ltd', NULL, '9999999999', NULL, 'test@gmail.com', 'vendors/logos/hitachi-ac-services-pvt-ltd.JPG', 'te', 'Sabarmati', 'te', 'te', 1.0000000, 1.0000000, 'approved', NULL, NULL, '2026-08-24 05:27:38', '2026-08-24 05:56:54'),
(4, 3, 'Samsung Pvt. Ltd.', 'samsung', NULL, '9978835005', NULL, 'test@gmail.com', 'hitachi-ac-services-pvt-ltd.png', 'te', 'Sabarmati', 'te', 'te', 1.0000000, 1.0000000, 'approved', NULL, NULL, '2026-08-24 05:27:38', '2026-08-24 05:56:54'),
(5, 1, 'Sony Pvt. Ltd.', 'sony', NULL, '9999999999', NULL, 'test@gmail.com', 'vendors/logos/hitachi-ac-services-pvt-ltd.JPG', 'te', 'Sabarmati', 'te', 'te', 1.0000000, 1.0000000, 'approved', NULL, NULL, '2026-08-24 05:27:38', '2026-08-24 05:56:54'),
(10, 7, 'Dobariya', 'dobariya', '[\"18\",\"23\",\"24\",\"25\",\"26\"]', '1', '1', 'dobariya@gmail.com', NULL, '1', '1', 'Gujarat', '1', 1.0000000, 1.0000000, 'pending', NULL, NULL, '2026-10-09 07:49:53', '2026-10-09 07:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `warantees`
--

CREATE TABLE `warantees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warantees`
--

INSERT INTO `warantees` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, '10-day warranty', 'With upto 10,000 damage cover & more', 'waranty_shield.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_banner_id_foreign` (`banner_id`);

--
-- Indexes for table `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_product_discount_coupons_id_foreign` (`discount_coupons_id`),
  ADD KEY `coupon_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_user_id_foreign` (`user_id`),
  ADD KEY `customer_addresses_state_id_foreign` (`state_id`);

--
-- Indexes for table `deal_stock_notifications`
--
ALTER TABLE `deal_stock_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_stock_notifications_user_id_foreign` (`user_id`),
  ADD KEY `deal_stock_notifications_affiliate_product_id_foreign` (`affiliate_product_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_discount_percentages_id_foreign` (`discount_percentages_id`),
  ADD KEY `discounts_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_percentages`
--
ALTER TABLE `discount_percentages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `includes`
--
ALTER TABLE `includes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `needs`
--
ALTER TABLE `needs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_customer_address_id_foreign` (`customer_address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_variant_id` (`product_variant_id`),
  ADD KEY `order_items_color_id_foreign` (`color_id`),
  ADD KEY `order_items_size_id_foreign` (`size_id`);

--
-- Indexes for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_histories_order_id_foreign` (`order_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`),
  ADD KEY `product_images_color_id_foreign` (`color_id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ratings_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_variants_color_id_foreign` (`color_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ratings_service_id_index` (`service_id`),
  ADD KEY `ratings_vendor_id_index` (`vendor_id`),
  ADD KEY `ratings_user_id_index` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_vendor_id_status_index` (`status`),
  ADD KEY `services_category_id_status_index` (`status`),
  ADD KEY `services_city_status_index` (`status`),
  ADD KEY `services_status_is_featured_index` (`status`),
  ADD KEY `services_vendor_id_foreign` (`vendor_id`),
  ADD KEY `services_category_id_foreign` (`category_id`),
  ADD KEY `services_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `service_images`
--
ALTER TABLE `service_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_images_service_id_foreign` (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_charges_state_id_foreign` (`state_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_notifications_user_id_foreign` (`user_id`),
  ADD KEY `stock_notifications_product_id_foreign` (`product_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_sub_category_slug_unique` (`sub_category_slug`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`),
  ADD KEY `sub_categories_process_id_foreign` (`process_id`);

--
-- Indexes for table `temp_images`
--
ALTER TABLE `temp_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_slug_unique` (`slug`),
  ADD KEY `vendors_user_id_foreign` (`user_id`);

--
-- Indexes for table `warantees`
--
ALTER TABLE `warantees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`),
  ADD KEY `wishlists_service_id_foreign` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `coupon_product`
--
ALTER TABLE `coupon_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `deal_stock_notifications`
--
ALTER TABLE `deal_stock_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `discount_percentages`
--
ALTER TABLE `discount_percentages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `includes`
--
ALTER TABLE `includes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `needs`
--
ALTER TABLE `needs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `service_images`
--
ALTER TABLE `service_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `temp_images`
--
ALTER TABLE `temp_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `warantees`
--
ALTER TABLE `warantees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_banner_id_foreign` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD CONSTRAINT `coupon_product_discount_coupons_id_foreign` FOREIGN KEY (`discount_coupons_id`) REFERENCES `discount_coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `customer_addresses_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deal_stock_notifications`
--
ALTER TABLE `deal_stock_notifications`
  ADD CONSTRAINT `deal_stock_notifications_affiliate_product_id_foreign` FOREIGN KEY (`affiliate_product_id`) REFERENCES `affiliate_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deal_stock_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_discount_percentages_id_foreign` FOREIGN KEY (`discount_percentages_id`) REFERENCES `discount_percentages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `discounts_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_address_id_foreign` FOREIGN KEY (`customer_address_id`) REFERENCES `customer_addresses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD CONSTRAINT `order_status_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `services_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_images`
--
ALTER TABLE `service_images`
  ADD CONSTRAINT `service_images_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  ADD CONSTRAINT `shipping_charges_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `stock_notifications`
--
ALTER TABLE `stock_notifications`
  ADD CONSTRAINT `stock_notifications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_categories_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
