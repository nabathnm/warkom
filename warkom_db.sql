-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 10:17 PM
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
-- Database: `warkom_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, '2026_04_22_163401_create_products_table', 1),
(5, '2026_04_22_171937_add_role_to_users_table', 1),
(6, '2026_04_22_174017_create_carts_table', 1),
(7, '2026_04_22_191258_add_category_to_products_table', 1),
(8, '2026_04_22_194520_create_orders_table', 1),
(9, '2026_04_22_194521_create_order_items_table', 1),
(10, '2026_04_22_201444_create_reviews_table', 1),
(11, '2026_04_23_060834_add_images_to_products_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Pemuda Karplo Indonesia', 2800000, 'paid', '2026-04-22 22:43:15', '2026-04-22 22:43:15'),
(2, 3, 'Kar', 2500000, 'paid', '2026-04-22 22:45:04', '2026-04-22 22:45:04'),
(3, 3, '213', 16500000, 'paid', '2026-04-23 01:40:04', '2026-04-23 01:40:04'),
(4, 3, 'test', 7500000, 'paid', '2026-04-23 01:40:36', '2026-04-23 01:40:36'),
(5, 3, 'test', 1200000, 'paid', '2026-04-25 09:59:06', '2026-04-25 09:59:06'),
(6, 3, 'test', 2100000, 'paid', '2026-04-25 10:02:05', '2026-04-25 10:02:05'),
(7, 3, 'test', 2200000, 'paid', '2026-04-25 10:06:14', '2026-04-25 10:06:14'),
(8, 3, 'karl[lps', 2200000, 'paid', '2026-04-25 10:06:47', '2026-04-25 10:06:47'),
(9, 3, 'test', 650000, 'paid', '2026-04-25 10:07:02', '2026-04-25 10:07:02'),
(10, 3, 'test', 2100000, 'paid', '2026-04-25 10:07:43', '2026-04-25 10:07:43'),
(11, 3, 'ngent', 2843000, 'paid', '2026-04-25 12:15:16', '2026-04-25 12:15:16'),
(12, 3, 'NGENTSTETSE', 2843000, 'paid', '2026-04-25 12:17:16', '2026-04-25 12:17:16'),
(13, 4, 'Pemuda Karplo Indonesia', 2843000, 'paid', '2026-04-25 12:31:32', '2026-04-25 12:31:32'),
(14, 4, 'teststs', 8499000, 'paid', '2026-04-25 12:52:44', '2026-04-25 12:52:44');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 2800000, '2026-04-22 22:43:15', '2026-04-22 22:43:15'),
(2, 2, 1, 1, 2500000, '2026-04-22 22:45:04', '2026-04-22 22:45:04'),
(3, 3, 5, 3, 5500000, '2026-04-23 01:40:04', '2026-04-23 01:40:04'),
(4, 4, 1, 3, 2500000, '2026-04-23 01:40:36', '2026-04-23 01:40:36'),
(5, 5, 9, 1, 1200000, '2026-04-25 09:59:06', '2026-04-25 09:59:06'),
(6, 6, 11, 1, 2100000, '2026-04-25 10:02:05', '2026-04-25 10:02:05'),
(7, 7, 14, 1, 2200000, '2026-04-25 10:06:14', '2026-04-25 10:06:14'),
(8, 8, 14, 1, 2200000, '2026-04-25 10:06:47', '2026-04-25 10:06:47'),
(9, 9, 18, 1, 650000, '2026-04-25 10:07:02', '2026-04-25 10:07:02'),
(10, 10, 20, 1, 2100000, '2026-04-25 10:07:43', '2026-04-25 10:07:43'),
(11, 11, 2, 1, 2800000, '2026-04-25 12:15:16', '2026-04-25 12:15:16'),
(12, 12, 2, 1, 2800000, '2026-04-25 12:17:16', '2026-04-25 12:17:16'),
(13, 13, 2, 1, 2800000, '2026-04-25 12:31:32', '2026-04-25 12:31:32'),
(14, 14, 2, 3, 2800000, '2026-04-25 12:52:44', '2026-04-25 12:52:44');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('cpu','motherboard','vga','ram','storage','psu','casing','cooling','aksesoris') NOT NULL DEFAULT 'aksesoris',
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `stock`, `images`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Intel Core i5-12400F', 'Prosesor generasi ke-12 Intel, 6 Cores 12 Threads.', 'cpu', 2500000.00, 11, '[\"products\\/0bwuXvBfjtuCUFc1iZz5XqDfqj8tiOEsQ1UM7pSc.jpg\",\"products\\/OKQjAmOw0CXUvdbXXR3VRxr19Mv37RXdzYZ54pVg.jpg\",\"products\\/7b0ArDaVQzFwN0YfZYwbhAXiVblE8xhBHo7BJp5M.png\",\"products\\/IH6aI50KJxtNDIGQ9kqeSwzPUyn0mUJ3zw5qIc39.png\",\"products\\/z9WBlkXLt508gXSNIWTW20iNedXlq64k0vwbvXNz.png\",\"products\\/f2y4MzNhqLi6QztgE8U5xg4e5uXCkB8d7CnLHPES.png\",\"products\\/ZJT45U8keTSOSXuPDIQqZmniFlvVqwf44Ws30Y3Z.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-23 01:40:36'),
(2, 'AMD Ryzen 5 5600X', 'Prosesor AMD Ryzen 5, sangat cocok untuk gaming.', 'cpu', 2800000.00, 3, '[\"products\\/Y87n8U7A7sXXjowGOS29dwI8MgZJpwiR3gZh6p7c.jpg\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 12:52:44'),
(4, 'MSI B550 TOMAHAWK', 'Motherboard tangguh untuk prosesor AMD Ryzen.', 'motherboard', 2900000.00, 8, '[\"products\\/motherboard_mockup_1776924680968.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-23 01:47:29'),
(5, 'ASUS ROG Strix Z690-A', 'Motherboard premium untuk prosesor Intel gen 12/13.', 'motherboard', 5500000.00, 1, '[\"products\\/motherboard_mockup_1776924680968.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-23 01:40:04'),
(6, 'NVIDIA GeForce RTX 3060 12GB', 'Kartu grafis mainstream untuk gaming 1080p.', 'vga', 5200000.00, 12, '[\"products\\/vga_mockup_1776924699579.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(7, 'AMD Radeon RX 6700 XT 12GB', 'Pesaing tangguh untuk kelas menengah dengan VRAM besar.', 'vga', 6000000.00, 7, '[\"products\\/vga_mockup_1776924699579.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(8, 'ASUS TUF Gaming RTX 4070', 'Performa grafis generasi terbaru dari seri 40 NVIDIA.', 'vga', 12500000.00, 3, '[\"products\\/vga_mockup_1776924699579.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(9, 'Corsair Vengeance RGB Pro 16GB (2x8GB) 3200MHz', 'Memori RAM dengan RGB yang memukau.', 'ram', 1200000.00, 19, '[\"products\\/ram_mockup_1776924714779.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 09:59:06'),
(10, 'G.Skill Trident Z Neo 32GB (2x16GB) 3600MHz', 'RAM berkecepatan tinggi cocok untuk sistem Ryzen.', 'ram', 2500000.00, 10, '[\"products\\/ram_mockup_1776924714779.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(11, 'Samsung 980 PRO 1TB NVMe M.2 SSD', 'Penyimpanan SSD PCIe Gen4 super cepat.', 'storage', 2100000.00, 14, '[\"products\\/storage_mockup_1776924730300.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 10:02:05'),
(12, 'Seagate Barracuda 2TB HDD', 'Hard disk besar untuk menyimpan semua file Anda.', 'storage', 950000.00, 25, '[\"products\\/storage_mockup_1776924730300.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(13, 'Western Digital WD Blue SN570 500GB', 'NVMe terjangkau untuk sistem ringan.', 'storage', 700000.00, 18, '[\"products\\/storage_mockup_1776924730300.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(14, 'Corsair RM750x 80+ Gold Fully Modular', 'Power supply efisien dengan kabel modular.', 'psu', 2200000.00, 6, '[\"products\\/psu_mockup_1776924745047.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 10:06:47'),
(15, 'Seasonic Focus GX-650', 'PSU handal 650W untuk rig gaming menengah.', 'psu', 1700000.00, 12, '[\"products\\/psu_mockup_1776924745047.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(16, 'NZXT H510 Flow', 'Casing minimalis dengan sirkulasi udara (airflow) mantap.', 'casing', 1400000.00, 6, '[\"products\\/casing_mockup_1776924760034.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(17, 'Lian Li PC-O11 Dynamic', 'Casing sultan untuk menampung banyak radiator pendingin.', 'casing', 2600000.00, 5, '[\"products\\/casing_mockup_1776924760034.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(18, 'Cooler Master Hyper 212 Black Edition', 'Pendingin udara legendaris yang handal.', 'cooling', 650000.00, 21, '[\"products\\/cooling_mockup_1776924773988.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 10:07:02'),
(19, 'NZXT Kraken X63 280mm AIO', 'Liquid cooler AIO dengan layar infinity mirror RGB.', 'cooling', 2800000.00, 4, '[\"products\\/cooling_mockup_1776924773988.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55'),
(20, 'Logitech G Pro X Superlight', 'Mouse gaming wireless super ringan.', 'aksesoris', 2100000.00, 14, '[\"products\\/aksesoris_mockup_1776924788244.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-25 10:07:43'),
(21, 'Keychron K8 Wireless Mechanical Keyboard', 'Keyboard mekanikal untuk kerja dan gaming.', 'aksesoris', 1600000.00, 9, '[\"products\\/aksesoris_mockup_1776924788244.png\"]', NULL, '2026-04-22 22:38:44', '2026-04-22 23:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 5, 'Produk ini sangat bagus, stok selalu tersedia. Recommended!', '2026-04-22 22:38:45', '2026-04-22 22:38:45'),
(2, 2, 2, 5, 'Produk ini sangat bagus, stok selalu tersedia. Recommended!', '2026-04-22 22:38:45', '2026-04-22 22:38:45'),
(4, 3, 4, 4, 'Barangnya mantap, harga bersahabat. Pengiriman juga cepat. Terima kasih!', '2026-04-22 22:38:45', '2026-04-22 22:38:45'),
(5, 3, 5, 4, 'Barangnya mantap, harga bersahabat. Pengiriman juga cepat. Terima kasih!', '2026-04-22 22:38:45', '2026-04-22 22:38:45'),
(6, 3, 2, 5, 'AMD terdebest!', '2026-04-22 22:43:58', '2026-04-22 22:43:58'),
(7, 3, 1, 3, 'jelek', '2026-04-25 09:56:08', '2026-04-25 09:56:08'),
(8, 3, 9, 2, 'test', '2026-04-25 09:59:18', '2026-04-25 09:59:18'),
(9, 3, 11, 2, 'etsts', '2026-04-25 10:02:16', '2026-04-25 10:02:16'),
(10, 3, 14, 3, NULL, '2026-04-25 10:06:24', '2026-04-25 10:06:24'),
(11, 3, 18, 5, 'ambud', '2026-04-25 10:07:16', '2026-04-25 10:07:16'),
(12, 3, 20, 5, 'test', '2026-04-25 10:07:57', '2026-04-25 10:07:57'),
(13, 4, 2, 5, 'Nice', '2026-04-25 12:31:46', '2026-04-25 12:31:46');

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
('Iljcrne0NBZlmXLvZjdwKGw3XPgEvEp5paSITuxw', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOGZIVVZEOUY5THY4OFZoZ2I5eE5vdlA2dFp1NUh1QXV1U3RHNFVrQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1777147124);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Test User', 'test@example.com', '2026-04-22 22:38:44', '$2y$12$fHPM3V.VexpRCcdqlMWpy.izrqkdkMRyUQyeVZAwxJwCfIJcUcbsG', 'jJPq8x24Ql', '2026-04-22 22:38:44', '2026-04-22 22:38:44', 'user'),
(2, 'Admin 1', 'admin1@gmail.com', NULL, '$2y$12$MyrejXdjjmS8n/HKb9uauebxDGb8gmNxSAuRHd7HXERT889AmZUUy', NULL, '2026-04-22 22:38:44', '2026-04-22 22:38:44', 'admin'),
(3, 'Andi', 'andi@gmail.com', NULL, '$2y$12$KFxuZOM1lIzidQgS4gc6Feb63Yf8iQdcwEvUBhliTNXYVOhN39aUi', NULL, '2026-04-22 22:38:44', '2026-04-22 22:38:44', 'user'),
(4, 'daps', 'daps@gmail.com', NULL, '$2y$12$VbMZT4YegiTvAoDQphEw0.5OrT.gP9ZBmSkuO12ov2NlyHXFUScuC', NULL, '2026-04-25 12:30:48', '2026-04-25 12:30:48', 'user');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
