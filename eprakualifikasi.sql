-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for eprakualifikasi
CREATE DATABASE IF NOT EXISTS `eprakualifikasi` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `eprakualifikasi`;

-- Dumping structure for table eprakualifikasi.email_verification_tokens
CREATE TABLE IF NOT EXISTS `email_verification_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_verification_tokens_token_index` (`token`),
  KEY `email_verification_tokens_user_id_expires_at_index` (`user_id`,`expires_at`),
  CONSTRAINT `email_verification_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.email_verification_tokens: ~0 rows (approximately)
DELETE FROM `email_verification_tokens`;

-- Dumping structure for table eprakualifikasi.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2025_08_07_000001_create_users_table', 1),
	(3, '2025_08_07_000002_create_password_reset_tokens_table', 1),
	(4, '2025_08_07_000003_create_email_verification_tokens_table', 1),
	(5, '2025_08_07_000004_create_user_login_histories_table', 1),
	(6, '2025_08_07_000005_create_vendors_table', 1),
	(7, '2025_08_07_000006_vendor_field_mapping', 1);

-- Dumping structure for table eprakualifikasi.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_tokens_email_expires_at_index` (`email`,`expires_at`),
  KEY `password_reset_tokens_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table eprakualifikasi.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table eprakualifikasi.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('DS','SC','MF','FW') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','tidak_aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_level_status_index` (`level`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.users: ~3 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `email_verified_at`, `password`, `level`, `type`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Administrator PLN', 'admin@pln.co.id', '2025-08-07 19:13:03', '$2y$12$522qdtIMVA3Gca6w3EqOPer9ckumO2VLuR6zdsO12M0mScWLN7sf6', 'admin', NULL, 'aktif', NULL, '2025-08-07 19:13:03', '2025-08-07 19:13:03'),
	(2, 'user_test', 'User Test PLN', 'user@pln.co.id', '2025-08-07 19:13:03', '$2y$12$.W.V7aOL6oDtkT9G3/tkce11GKMg/DcEs1o36Lq787dOkhy8bh1d.', 'user', 'DS', 'aktif', NULL, '2025-08-07 19:13:03', '2025-08-07 19:13:03'),
	(3, 'amar', 'PT Amar Rabbany', 'andag5700@gmail.com', '2025-08-07 19:14:10', '$2y$12$N7StYKsPqbB/0EYJkVcVUepHvhrlJiDURK81p9NbOCdcIrwE60ay.', 'user', 'DS', 'aktif', '2025-08-07 19:18:20', '2025-08-07 19:13:44', '2025-08-07 19:18:20');

-- Dumping structure for table eprakualifikasi.user_login_histories
CREATE TABLE IF NOT EXISTS `user_login_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.user_login_histories: ~0 rows (approximately)
DELETE FROM `user_login_histories`;

-- Dumping structure for table eprakualifikasi.vendors
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nomor_vendor` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Format: SC-0001, DS-0001, FW-0001, MF-0001',
  `tipe_perusahaan` enum('SC','DS','FW','MF') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SC=Subcontractor, DS=Distributor, FW=Forwarder, MF=Manufacturer',
  `nama_perusahaan` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Diambil dari registrasi - companyName',
  `npwp` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diambil dari registrasi - format: 00.000.000.0-000.000',
  `sumber_informasi` enum('website','instagram','facebook','rekomendasi','brosur','lainnya') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diambil dari registrasi - knownFrom',
  `rekomendasi_dari` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jika sumber_informasi = rekomendasi',
  `lainnya` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jika sumber_informasi = lainnya',
  `nama_perusahaan_induk` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Untuk DS dan MF',
  `alamat_perusahaan_induk` text COLLATE utf8mb4_unicode_ci COMMENT 'Untuk DS dan MF',
  `alamat` text COLLATE utf8mb4_unicode_ci COMMENT 'Alamat perusahaan utama - untuk DS dan MF',
  `alamat_kantor_pusat` text COLLATE utf8mb4_unicode_ci COMMENT 'Untuk FW',
  `alamat_kantor_operasional` text COLLATE utf8mb4_unicode_ci COMMENT 'Untuk DS',
  `kode_pos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Untuk semua kecuali MF',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telepon utama - untuk semua',
  `phone_pusat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telepon kantor pusat - untuk FW',
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Website perusahaan - untuk semua kecuali MF',
  `contact_person` json DEFAULT NULL COMMENT 'Contact person utama - untuk semua kecuali MF',
  `top_level` json DEFAULT NULL COMMENT 'Manajemen tingkat atas - untuk semua',
  `mid_level` json DEFAULT NULL COMMENT 'Manajemen tingkat menengah - untuk semua',
  `sales_marketing` json DEFAULT NULL COMMENT 'Sales & Marketing - untuk semua',
  `tanggal_berdiri` date DEFAULT NULL COMMENT 'Tanggal berdiri perusahaan - untuk semua',
  `bagian_grup` enum('ya','tidak') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Apakah bagian dari grup - untuk SC',
  `tanggal_beroperasi` date DEFAULT NULL COMMENT 'Tanggal mulai beroperasi - untuk semua',
  `modal_dasar` decimal(15,2) DEFAULT NULL COMMENT 'Modal dasar perusahaan - untuk SC',
  `modal_dikeluarkan` decimal(15,2) DEFAULT NULL COMMENT 'Modal yang dikeluarkan - untuk SC',
  `pemegang_saham` text COLLATE utf8mb4_unicode_ci COMMENT 'Data pemegang saham - untuk SC',
  `nama_direktur` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama direktur utama - untuk SC',
  `jumlah_karyawan` int DEFAULT NULL COMMENT 'Jumlah karyawan - untuk SC',
  `kepemilikan` enum('tidak','satu','lebih dari satu') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kepemilikan armada - khusus FW',
  `kepemilikan_armada` enum('sewa','miliki pribadi') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Status kepemilikan armada - khusus FW',
  `profile_completed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Status kelengkapan profil',
  `completion_percentage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Persentase kelengkapan profil (0-100)',
  `verified_at` timestamp NULL DEFAULT NULL COMMENT 'Tanggal verifikasi oleh admin',
  `verified_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_user_id_unique` (`user_id`),
  UNIQUE KEY `vendors_nomor_vendor_unique` (`nomor_vendor`),
  KEY `vendors_verified_by_foreign` (`verified_by`),
  KEY `idx_vendors_nomor` (`nomor_vendor`),
  KEY `idx_vendors_tipe` (`tipe_perusahaan`),
  KEY `idx_vendors_completed` (`profile_completed`),
  KEY `idx_vendors_verified` (`verified_at`),
  KEY `idx_vendors_tipe_created` (`tipe_perusahaan`,`created_at`),
  CONSTRAINT `vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vendors_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.vendors: ~1 rows (approximately)
DELETE FROM `vendors`;
INSERT INTO `vendors` (`id`, `user_id`, `nomor_vendor`, `tipe_perusahaan`, `nama_perusahaan`, `npwp`, `sumber_informasi`, `rekomendasi_dari`, `lainnya`, `nama_perusahaan_induk`, `alamat_perusahaan_induk`, `alamat`, `alamat_kantor_pusat`, `alamat_kantor_operasional`, `kode_pos`, `phone`, `phone_pusat`, `website`, `contact_person`, `top_level`, `mid_level`, `sales_marketing`, `tanggal_berdiri`, `bagian_grup`, `tanggal_beroperasi`, `modal_dasar`, `modal_dikeluarkan`, `pemegang_saham`, `nama_direktur`, `jumlah_karyawan`, `kepemilikan`, `kepemilikan_armada`, `profile_completed`, `completion_percentage`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES
	(1, 3, 'DS-0001', 'DS', 'PT Amar Rabbany', '11.111.111.1-111.111', 'website', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 7.14, NULL, NULL, '2025-08-07 19:13:44', '2025-08-07 19:13:44');

-- Dumping structure for table eprakualifikasi.vendor_field_mappings
CREATE TABLE IF NOT EXISTS `vendor_field_mappings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipe_perusahaan` enum('SC','DS','FW','MF') COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `field_order` int NOT NULL DEFAULT '0',
  `field_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `field_options` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tipe_field` (`tipe_perusahaan`,`field_name`),
  KEY `idx_field_tipe` (`tipe_perusahaan`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.vendor_field_mappings: ~55 rows (approximately)
DELETE FROM `vendor_field_mappings`;
INSERT INTO `vendor_field_mappings` (`id`, `tipe_perusahaan`, `field_name`, `field_label`, `is_required`, `field_order`, `field_type`, `field_options`, `created_at`, `updated_at`) VALUES
	(1, 'SC', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(2, 'SC', 'kode_pos', 'Kode Pos', 1, 2, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(3, 'SC', 'phone', 'Telepon', 1, 3, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(4, 'SC', 'website', 'Website', 1, 4, 'url', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(5, 'SC', 'contact_person', 'Contact Person', 1, 5, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(6, 'SC', 'top_level', 'Top Level Management', 1, 6, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(7, 'SC', 'mid_level', 'Mid Level Management', 1, 7, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(8, 'SC', 'sales_marketing', 'Sales & Marketing', 1, 8, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(9, 'SC', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 9, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(10, 'SC', 'bagian_grup', 'Bagian Grup', 1, 10, 'select', '["ya", "tidak"]', '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(11, 'SC', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 11, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(12, 'SC', 'modal_dasar', 'Modal Dasar', 1, 12, 'number', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(13, 'SC', 'modal_dikeluarkan', 'Modal Dikeluarkan', 1, 13, 'number', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(14, 'SC', 'pemegang_saham', 'Pemegang Saham', 1, 14, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(15, 'SC', 'nama_direktur', 'Nama Direktur', 1, 15, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(16, 'SC', 'jumlah_karyawan', 'Jumlah Karyawan', 1, 16, 'number', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(17, 'FW', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(18, 'FW', 'npwp', 'NPWP', 1, 2, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(19, 'FW', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 3, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(20, 'FW', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 4, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(21, 'FW', 'contact_person', 'Contact Person', 1, 5, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(22, 'FW', 'top_level', 'Top Level Management', 1, 6, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(23, 'FW', 'mid_level', 'Mid Level Management', 1, 7, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(24, 'FW', 'sales_marketing', 'Sales & Marketing', 1, 8, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(25, 'FW', 'alamat_kantor_pusat', 'Alamat Kantor Pusat', 1, 9, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(26, 'FW', 'kode_pos', 'Kode Pos', 1, 10, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(27, 'FW', 'phone_pusat', 'Telepon Pusat', 1, 11, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(28, 'FW', 'phone', 'Telepon', 1, 12, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(29, 'FW', 'website', 'Website', 1, 13, 'url', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(30, 'FW', 'kepemilikan', 'Kepemilikan', 1, 14, 'select', '["tidak", "satu", "lebih dari satu"]', '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(31, 'FW', 'kepemilikan_armada', 'Kepemilikan Armada', 1, 15, 'select', '["sewa", "miliki pribadi"]', '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(32, 'DS', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(33, 'DS', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 2, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(34, 'DS', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 3, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(35, 'DS', 'alamat', 'Alamat', 1, 4, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(36, 'DS', 'kode_pos', 'Kode Pos', 1, 5, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(37, 'DS', 'alamat_kantor_operasional', 'Alamat Kantor Operasional', 1, 6, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(38, 'DS', 'phone', 'Telepon', 1, 7, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(39, 'DS', 'website', 'Website', 1, 8, 'url', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(40, 'DS', 'contact_person', 'Contact Person', 1, 9, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(41, 'DS', 'top_level', 'Top Level Management', 1, 10, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(42, 'DS', 'mid_level', 'Mid Level Management', 1, 11, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(43, 'DS', 'sales_marketing', 'Sales & Marketing', 1, 12, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(44, 'DS', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', 1, 13, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(45, 'DS', 'alamat_perusahaan_induk', 'Alamat Perusahaan Induk', 1, 14, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(46, 'MF', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(47, 'MF', 'npwp', 'NPWP', 1, 2, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(48, 'MF', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 3, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(49, 'MF', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 4, 'date', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(50, 'MF', 'alamat', 'Alamat', 1, 5, 'textarea', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(51, 'MF', 'phone', 'Telepon', 1, 6, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(52, 'MF', 'top_level', 'Top Level Management', 1, 7, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(53, 'MF', 'mid_level', 'Mid Level Management', 1, 8, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(54, 'MF', 'sales_marketing', 'Sales & Marketing', 1, 9, 'json', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02'),
	(55, 'MF', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', 1, 10, 'text', NULL, '2025-08-07 19:13:02', '2025-08-07 19:13:02');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
