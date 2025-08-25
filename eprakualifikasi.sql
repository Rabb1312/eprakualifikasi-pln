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

-- Dumping structure for table eprakualifikasi.distributors
CREATE TABLE IF NOT EXISTS `distributors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `engineering_activities` text COLLATE utf8mb4_unicode_ci COMMENT 'Please describe your Engineering activities (if any)',
  `priority_treatment` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint_agreement` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_contact_centre` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distributors_vendor_id_index` (`vendor_id`),
  KEY `distributors_vendor_id_created_at_index` (`vendor_id`,`created_at`),
  CONSTRAINT `distributors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.distributors: ~1 rows (approximately)
DELETE FROM `distributors`;
INSERT INTO `distributors` (`id`, `vendor_id`, `engineering_activities`, `priority_treatment`, `complaint_agreement`, `has_contact_centre`, `created_at`, `updated_at`) VALUES
	(2, 2, 'Eos fugiat hic do a', 'no', 'no', 'no', '2025-08-22 01:15:15', '2025-08-22 01:15:29');

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

-- Dumping structure for table eprakualifikasi.forwarders
CREATE TABLE IF NOT EXISTS `forwarders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `other_services_none` tinyint(1) NOT NULL DEFAULT '0',
  `able_to_conduct_expediting` tinyint(1) NOT NULL DEFAULT '0',
  `able_to_submit_regular_status_report` tinyint(1) NOT NULL DEFAULT '0',
  `able_to_have_scope_electrical_supervision` tinyint(1) NOT NULL DEFAULT '0',
  `having_ability_make_temporary_jetty` tinyint(1) NOT NULL DEFAULT '0',
  `other_services_others` tinyint(1) NOT NULL DEFAULT '0',
  `other_services_others_description` text COLLATE utf8mb4_unicode_ci,
  `heavy_lift_capability` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heavy_lift_cooperation_companies` text COLLATE utf8mb4_unicode_ci,
  `monitoring_management` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monitoring_coordination_description` text COLLATE utf8mb4_unicode_ci,
  `software_none` tinyint(1) NOT NULL DEFAULT '0',
  `software_existing` tinyint(1) NOT NULL DEFAULT '0',
  `software_shipment_tracking` tinyint(1) NOT NULL DEFAULT '0',
  `software_shipment_tracking_description` text COLLATE utf8mb4_unicode_ci,
  `software_others` tinyint(1) NOT NULL DEFAULT '0',
  `software_others_description` text COLLATE utf8mb4_unicode_ci,
  `communication_system` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `communication_system_description` text COLLATE utf8mb4_unicode_ci,
  `project_execution_procedure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_execution_sop_list` text COLLATE utf8mb4_unicode_ci,
  `indonesian_branch_offices` json DEFAULT NULL,
  `overseas_branch_offices` json DEFAULT NULL,
  `shipping_line_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_lines` json DEFAULT NULL,
  `airlines_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `airlines` json DEFAULT NULL,
  `insurance_none` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_all_risk_covered_by_company` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_liability_with_insurance_company` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_negotiable_with_without_insurance` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_others` tinyint(1) NOT NULL DEFAULT '0',
  `insurance_others_description` text COLLATE utf8mb4_unicode_ci,
  `insurance_claim_arrangement_description` text COLLATE utf8mb4_unicode_ci,
  `manpower_support` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manpower_certifications` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forwarders_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `forwarders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.forwarders: ~0 rows (approximately)
DELETE FROM `forwarders`;

-- Dumping structure for table eprakualifikasi.manufactures
CREATE TABLE IF NOT EXISTS `manufactures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `product_types` json DEFAULT NULL,
  `personnel` json DEFAULT NULL,
  `plants` json DEFAULT NULL,
  `after_sales` json DEFAULT NULL,
  `engineering_design` json DEFAULT NULL,
  `inventory` json DEFAULT NULL,
  `subcontracting` text COLLATE utf8mb4_unicode_ci,
  `code_standard` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufactures_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `manufactures_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.manufactures: ~0 rows (approximately)
DELETE FROM `manufactures`;

-- Dumping structure for table eprakualifikasi.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2025_08_07_000001_create_users_table', 1),
	(3, '2025_08_07_000002_create_password_reset_tokens_table', 1),
	(4, '2025_08_07_000003_create_email_verification_tokens_table', 1),
	(5, '2025_08_07_000004_create_user_login_histories_table', 1),
	(6, '2025_08_07_000005_create_vendors_table', 1),
	(7, '2025_08_07_000006_vendor_field_mapping', 1),
	(8, '2025_08_08_000007_create_subcontractors_table', 1),
	(9, '2025_08_14_000008_create_vendor_documents_table', 1),
	(10, '2025_08_20_000009_create_distributors_table', 1),
	(11, '2025_08_21_000010_create_forwarders_table', 1),
	(12, '2025_08_21_000011_create_manufactures_table', 1);

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

-- Dumping structure for table eprakualifikasi.subcontractors
CREATE TABLE IF NOT EXISTS `subcontractors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `holding_nama_perusahaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holding_tanggal_berdiri` date DEFAULT NULL,
  `holding_alamat` text COLLATE utf8mb4_unicode_ci,
  `holding_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holding_modal_dasar` decimal(15,2) DEFAULT NULL,
  `holding_modal_dikeluarkan` decimal(15,2) DEFAULT NULL,
  `holding_pemegang_saham` text COLLATE utf8mb4_unicode_ci,
  `holding_contact_person` json DEFAULT NULL,
  `holding_nama_direktur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facilities` json DEFAULT NULL,
  `other_services` text COLLATE utf8mb4_unicode_ci,
  `employees` json DEFAULT NULL,
  `scope_of_work` text COLLATE utf8mb4_unicode_ci,
  `major_projects` json DEFAULT NULL,
  `local_regulation_knowledge` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regulation_knowledge_details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subcontractors_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `subcontractors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.subcontractors: ~0 rows (approximately)
DELETE FROM `subcontractors`;
INSERT INTO `subcontractors` (`id`, `vendor_id`, `holding_nama_perusahaan`, `holding_tanggal_berdiri`, `holding_alamat`, `holding_phone`, `holding_modal_dasar`, `holding_modal_dikeluarkan`, `holding_pemegang_saham`, `holding_contact_person`, `holding_nama_direktur`, `facilities`, `other_services`, `employees`, `scope_of_work`, `major_projects`, `local_regulation_knowledge`, `regulation_knowledge_details`, `created_at`, `updated_at`) VALUES
	(1, 3, 'Repellendus Non con', '1990-01-17', 'Cillum sapiente quia', '+1 (815) 685-6308', 7.00, 78.00, 'Ea anim veritatis se', '[{"nama": "Sequi eligendi quia", "email": "wegyboz@mailinator.com", "jabatan": "Voluptatem velit ve", "telepon": "+1 (146) 737-1486"}]', 'Fugiat ea qui qui i', '["mechanical_erection_power"]', 'Exercitationem ut ei', '[{"foreman": 99, "manager": 79, "supervisor": 75, "craft_labor": 71, "classification": "Bricklayers"}, {"foreman": 42, "manager": 85, "supervisor": 98, "craft_labor": 75, "classification": "Carpenters"}, {"foreman": 87, "manager": 39, "supervisor": 20, "craft_labor": 3, "classification": "Laborers Civil"}, {"foreman": 26, "manager": 74, "supervisor": 37, "craft_labor": 81, "classification": "Reinforcing Steel Fixers"}, {"foreman": 26, "manager": 83, "supervisor": 32, "craft_labor": 79, "classification": "Scaffolders"}, {"foreman": 21, "manager": 62, "supervisor": 86, "craft_labor": 37, "classification": "Boilermakers"}, {"foreman": 50, "manager": 57, "supervisor": 67, "craft_labor": 76, "classification": "Erector Riggers"}, {"foreman": 94, "manager": 77, "supervisor": 94, "craft_labor": 12, "classification": "Laborers Mechanical"}, {"foreman": 50, "manager": 49, "supervisor": 81, "craft_labor": 97, "classification": "Millwrights"}, {"foreman": 35, "manager": 0, "supervisor": 100, "craft_labor": 33, "classification": "Pipe fitters"}, {"foreman": 37, "manager": 21, "supervisor": 72, "craft_labor": 77, "classification": "Welders"}, {"foreman": 33, "manager": 55, "supervisor": 98, "craft_labor": 55, "classification": "Sheet Metal workers"}, {"foreman": 45, "manager": 85, "supervisor": 44, "craft_labor": 21, "classification": "Drivers"}, {"foreman": 71, "manager": 86, "supervisor": 26, "craft_labor": 43, "classification": "Electricians"}, {"foreman": 55, "manager": 78, "supervisor": 54, "craft_labor": 43, "classification": "Instrument Fitters"}, {"foreman": 15, "manager": 4, "supervisor": 70, "craft_labor": 97, "classification": "Insulators"}, {"foreman": 28, "manager": 93, "supervisor": 3, "craft_labor": 40, "classification": "Others"}]', 'Reprehenderit reici', '[{"phone": "+1 (584) 108-7815", "value": null, "project_name": "Colorado Wong", "work_package": "Est eius ipsum recus", "project_owner": "Magni mollit molliti", "scope_of_work": "Cillum autem quaerat", "work_duration": "Ipsam sit explicabo", "main_contractor": "Earum qui ea unde ir", "year_of_execution": "1970", "client_representative": "Aliqua Sunt cupidat"}, {"phone": "+1 (767) 658-9207", "value": null, "project_name": "Wynter Waters", "work_package": "Dolore porro reprehe", "project_owner": "Quod eos amet iusto", "scope_of_work": "Quod esse in eiusmo", "work_duration": "Libero consequat Se", "main_contractor": "Magni deleniti offic", "year_of_execution": "1981", "client_representative": "Labore dolore dolore"}, {"phone": "+1 (998) 521-4336", "value": null, "project_name": "Irene Bryant", "work_package": "Voluptates excepteur", "project_owner": "Quo culpa et vitae", "scope_of_work": "Iure tempore esse", "work_duration": "Excepturi officia ei", "main_contractor": "Eos quas iste aliqua", "year_of_execution": "1971", "client_representative": "Quae dolore repudian"}]', 'yes', 'Nihil enim ex soluta', '2025-08-22 01:11:07', '2025-08-22 01:12:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `email_verified_at`, `password`, `level`, `type`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Administrator PLN', 'admin@pln.co.id', '2025-08-21 23:56:07', '$2y$12$rWtEidRerNhzYSbOnDUW5uvZRao7xOiF6HrLjpg0mOWHIDweJzE2m', 'admin', NULL, 'aktif', NULL, '2025-08-21 23:56:07', '2025-08-21 23:56:07'),
	(3, 'rabbany', 'rabbany', 'andag5700@gmail.com', '2025-08-22 00:13:43', '$2y$12$4zUfwrfDTfSDzCWeJPtrZu0ijgKJLjQ5t5TGfuY.1rMvJw1NPaRUa', 'user', 'DS', 'aktif', '2025-08-22 01:15:13', '2025-08-22 00:13:43', '2025-08-22 01:15:13'),
	(4, 'amar', 'amar', 'andag5800@gmail.com', '2025-08-22 01:05:00', '$2y$12$C03Wv3VBj36Ur3BgUQVnHOTMruZ/y0OaloMnbdGOPjo0.acFaRcEu', 'user', 'SC', 'aktif', '2025-08-22 01:16:50', '2025-08-22 01:05:00', '2025-08-22 01:16:50');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.vendors: ~1 rows (approximately)
DELETE FROM `vendors`;
INSERT INTO `vendors` (`id`, `user_id`, `nomor_vendor`, `tipe_perusahaan`, `nama_perusahaan`, `npwp`, `sumber_informasi`, `rekomendasi_dari`, `lainnya`, `nama_perusahaan_induk`, `alamat_perusahaan_induk`, `alamat`, `alamat_kantor_pusat`, `alamat_kantor_operasional`, `kode_pos`, `phone`, `phone_pusat`, `website`, `contact_person`, `top_level`, `mid_level`, `sales_marketing`, `tanggal_berdiri`, `bagian_grup`, `tanggal_beroperasi`, `modal_dasar`, `modal_dikeluarkan`, `pemegang_saham`, `nama_direktur`, `jumlah_karyawan`, `kepemilikan`, `kepemilikan_armada`, `profile_completed`, `completion_percentage`, `verified_at`, `verified_by`, `created_at`, `updated_at`) VALUES
	(2, 3, 'DS-0001', 'DS', 'rabbany', NULL, 'lainnya', NULL, 'Dibuat oleh Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 26.32, '2025-08-22 01:07:25', 1, '2025-08-22 00:13:43', '2025-08-22 01:15:29'),
	(3, 4, 'SC-0001', 'SC', 'amar', 'Consequuntur blandit', 'lainnya', NULL, 'Dibuat oleh Administrator', NULL, NULL, 'Incidunt reiciendis', NULL, 'Aut ipsa laborum S', 'Aute', '+1 (437) 962-7499', NULL, 'https://www.xym.in', '[{"nama": "Impedit sapiente de", "email": "nipaw@mailinator.com", "jabatan": "Sed eos animi in ha", "telepon": "Tempora dolor adipis"}]', '[{"nama": "Dolores harum mollit", "email": "lukujone@mailinator.com", "jabatan": "Tempora non sunt ex", "telepon": "Deserunt inventore c"}]', '[{"nama": "Labore est fugiat re", "email": "vyjafuna@mailinator.com", "jabatan": "Ipsum omnis temporib", "telepon": "In blanditiis soluta"}]', '[{"nama": "Harum quidem volupta", "email": "gyfeketegi@mailinator.com", "jabatan": "Est eos enim debiti", "telepon": "Qui deserunt anim au"}]', '1983-02-23', 'tidak', '1990-11-17', 37.00, 11.00, '54', 'Assumenda natus nobi', 77, NULL, NULL, 0, 70.83, '2025-08-22 01:13:29', 1, '2025-08-22 01:05:00', '2025-08-22 01:13:29');

-- Dumping structure for table eprakualifikasi.vendor_documents
CREATE TABLE IF NOT EXISTS `vendor_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_index` int NOT NULL DEFAULT '0',
  `allows_multiple` tinyint(1) NOT NULL DEFAULT '0',
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `has_expiry_date` tinyint(1) NOT NULL DEFAULT '0',
  `has_template` tinyint(1) NOT NULL DEFAULT '0',
  `template_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_download_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `expires_soon` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('not_uploaded','uploaded','under_review','approved','rejected','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_uploaded',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `version` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_documents_reviewed_by_foreign` (`reviewed_by`),
  KEY `vendor_documents_vendor_id_document_type_sub_index_index` (`vendor_id`,`document_type`,`sub_index`),
  KEY `vendor_documents_status_index` (`status`),
  KEY `vendor_documents_expiry_date_index` (`expiry_date`),
  KEY `vendor_documents_parent_type_index` (`parent_type`),
  CONSTRAINT `vendor_documents_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vendor_documents_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.vendor_documents: ~0 rows (approximately)
DELETE FROM `vendor_documents`;
INSERT INTO `vendor_documents` (`id`, `vendor_id`, `document_type`, `document_number`, `parent_type`, `group`, `group_name`, `document_subtitle`, `sub_index`, `allows_multiple`, `document_name`, `is_required`, `has_expiry_date`, `has_template`, `template_url`, `template_download_url`, `template_filename`, `file_name`, `file_path`, `file_size`, `file_type`, `expiry_date`, `expires_soon`, `status`, `admin_notes`, `rejection_reason`, `reviewed_by`, `reviewed_at`, `uploaded_at`, `version`, `created_at`, `updated_at`) VALUES
	(1, 3, 'articles_association', '1.1', 'attachment_1', '1', 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)', 'Akta Pendirian', 0, 0, 'Articles of Association', 1, 0, 0, NULL, NULL, NULL, 'Amar Juara.pdf', 'vendor_documents/3/1755850472_0_articles_association.pdf', '158949', 'application/pdf', NULL, 0, 'uploaded', NULL, NULL, NULL, NULL, '2025-08-22 01:14:32', 1, '2025-08-22 01:14:32', '2025-08-22 01:14:32'),
	(2, 2, 'articles_association', '1.1', 'attachment_1', '1', 'Articles of Association and Amendment of Articles of Association (With Validity Sheet)', 'Akta Pendirian', 0, 0, 'Articles of Association', 1, 0, 0, NULL, NULL, NULL, 'Surat Riset Cap.pdf', 'vendor_documents/2/1755850538_0_articles_association.pdf', '698801', 'application/pdf', NULL, 0, 'uploaded', NULL, NULL, NULL, NULL, '2025-08-22 01:15:38', 1, '2025-08-22 01:15:38', '2025-08-22 01:15:38');

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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table eprakualifikasi.vendor_field_mappings: ~58 rows (approximately)
DELETE FROM `vendor_field_mappings`;
INSERT INTO `vendor_field_mappings` (`id`, `tipe_perusahaan`, `field_name`, `field_label`, `is_required`, `field_order`, `field_type`, `field_options`, `created_at`, `updated_at`) VALUES
	(1, 'SC', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(2, 'SC', 'alamat', 'Alamat', 1, 2, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(3, 'SC', 'kode_pos', 'Kode Pos', 1, 3, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(4, 'SC', 'phone', 'Telepon', 1, 4, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(5, 'SC', 'website', 'Website', 1, 5, 'url', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(6, 'SC', 'contact_person', 'Contact Person', 1, 6, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(7, 'SC', 'top_level', 'Top Level Management', 1, 7, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(8, 'SC', 'mid_level', 'Mid Level Management', 1, 8, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(9, 'SC', 'sales_marketing', 'Sales & Marketing', 1, 9, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(10, 'SC', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 10, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(11, 'SC', 'bagian_grup', 'Bagian Grup', 1, 11, 'select', '["ya", "tidak"]', '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(12, 'SC', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 12, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(13, 'SC', 'modal_dasar', 'Modal Dasar', 1, 13, 'number', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(14, 'SC', 'modal_dikeluarkan', 'Modal Dikeluarkan', 1, 14, 'number', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(15, 'SC', 'pemegang_saham', 'Pemegang Saham', 1, 15, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(16, 'SC', 'nama_direktur', 'Nama Direktur', 1, 16, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(17, 'SC', 'jumlah_karyawan', 'Jumlah Karyawan', 1, 17, 'number', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(18, 'FW', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(19, 'FW', 'npwp', 'NPWP', 1, 2, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(20, 'FW', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 3, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(21, 'FW', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 4, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(22, 'FW', 'contact_person', 'Contact Person', 1, 5, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(23, 'FW', 'top_level', 'Top Level Management', 1, 6, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(24, 'FW', 'mid_level', 'Mid Level Management', 1, 7, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(25, 'FW', 'sales_marketing', 'Sales & Marketing', 1, 8, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(26, 'FW', 'alamat_kantor_pusat', 'Alamat Kantor Pusat', 1, 9, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(27, 'FW', 'kode_pos', 'Kode Pos', 1, 10, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(28, 'FW', 'phone_pusat', 'Telepon Pusat', 1, 11, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(29, 'FW', 'phone', 'Telepon', 1, 12, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(30, 'FW', 'website', 'Website', 1, 13, 'url', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(31, 'FW', 'kepemilikan', 'Kepemilikan', 1, 14, 'select', '["tidak", "satu", "lebih dari satu"]', '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(32, 'FW', 'kepemilikan_armada', 'Kepemilikan Armada', 1, 15, 'select', '["sewa", "miliki pribadi"]', '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(33, 'DS', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(34, 'DS', 'npwp', 'NPWP', 1, 2, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(35, 'DS', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 3, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(36, 'DS', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 4, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(37, 'DS', 'alamat', 'Alamat', 1, 5, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(38, 'DS', 'kode_pos', 'Kode Pos', 1, 6, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(39, 'DS', 'alamat_kantor_operasional', 'Alamat Kantor Operasional', 1, 7, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(40, 'DS', 'phone', 'Telepon', 1, 8, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(41, 'DS', 'website', 'Website', 1, 9, 'url', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(42, 'DS', 'contact_person', 'Contact Person', 1, 10, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(43, 'DS', 'top_level', 'Top Level Management', 1, 11, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(44, 'DS', 'mid_level', 'Mid Level Management', 1, 12, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(45, 'DS', 'sales_marketing', 'Sales & Marketing', 1, 13, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(46, 'DS', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', 1, 14, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(47, 'DS', 'alamat_perusahaan_induk', 'Alamat Perusahaan Induk', 1, 15, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(48, 'MF', 'nama_perusahaan', 'Nama Perusahaan', 1, 1, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(49, 'MF', 'npwp', 'NPWP', 1, 2, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(50, 'MF', 'tanggal_berdiri', 'Tanggal Berdiri', 1, 3, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(51, 'MF', 'tanggal_beroperasi', 'Tanggal Beroperasi', 1, 4, 'date', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(52, 'MF', 'alamat', 'Alamat', 1, 5, 'textarea', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(53, 'MF', 'kode_pos', 'Kode Pos', 1, 6, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(54, 'MF', 'phone', 'Telepon', 1, 7, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(55, 'MF', 'top_level', 'Top Level Management', 1, 8, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(56, 'MF', 'mid_level', 'Mid Level Management', 1, 9, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(57, 'MF', 'sales_marketing', 'Sales & Marketing', 1, 10, 'json', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05'),
	(58, 'MF', 'nama_perusahaan_induk', 'Nama Perusahaan Induk', 1, 11, 'text', NULL, '2025-08-21 23:56:05', '2025-08-21 23:56:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
