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

-- Dumping data for table eprakualifikasi.subcontractors: ~1 rows (approximately)
DELETE FROM `subcontractors`;
INSERT INTO `subcontractors` (`id`, `vendor_id`, `holding_nama_perusahaan`, `holding_tanggal_berdiri`, `holding_alamat`, `holding_phone`, `holding_modal_dasar`, `holding_modal_dikeluarkan`, `holding_pemegang_saham`, `holding_contact_person`, `holding_nama_direktur`, `facilities`, `other_services`, `employees`, `scope_of_work`, `major_projects`, `local_regulation_knowledge`, `regulation_knowledge_details`, `created_at`, `updated_at`) VALUES
	(1, 3, 'Repellendus Non con', '1990-01-17', 'Cillum sapiente quia', '+1 (815) 685-6308', 7.00, 78.00, 'Ea anim veritatis se', '[{"nama": "Sequi eligendi quia", "email": "wegyboz@mailinator.com", "jabatan": "Voluptatem velit ve", "telepon": "+1 (146) 737-1486"}]', 'Fugiat ea qui qui i', '["mechanical_erection_power"]', 'Exercitationem ut ei', '[{"foreman": 99, "manager": 79, "supervisor": 75, "craft_labor": 71, "classification": "Bricklayers"}, {"foreman": 42, "manager": 85, "supervisor": 98, "craft_labor": 75, "classification": "Carpenters"}, {"foreman": 87, "manager": 39, "supervisor": 20, "craft_labor": 3, "classification": "Laborers Civil"}, {"foreman": 26, "manager": 74, "supervisor": 37, "craft_labor": 81, "classification": "Reinforcing Steel Fixers"}, {"foreman": 26, "manager": 83, "supervisor": 32, "craft_labor": 79, "classification": "Scaffolders"}, {"foreman": 21, "manager": 62, "supervisor": 86, "craft_labor": 37, "classification": "Boilermakers"}, {"foreman": 50, "manager": 57, "supervisor": 67, "craft_labor": 76, "classification": "Erector Riggers"}, {"foreman": 94, "manager": 77, "supervisor": 94, "craft_labor": 12, "classification": "Laborers Mechanical"}, {"foreman": 50, "manager": 49, "supervisor": 81, "craft_labor": 97, "classification": "Millwrights"}, {"foreman": 35, "manager": 0, "supervisor": 100, "craft_labor": 33, "classification": "Pipe fitters"}, {"foreman": 37, "manager": 21, "supervisor": 72, "craft_labor": 77, "classification": "Welders"}, {"foreman": 33, "manager": 55, "supervisor": 98, "craft_labor": 55, "classification": "Sheet Metal workers"}, {"foreman": 45, "manager": 85, "supervisor": 44, "craft_labor": 21, "classification": "Drivers"}, {"foreman": 71, "manager": 86, "supervisor": 26, "craft_labor": 43, "classification": "Electricians"}, {"foreman": 55, "manager": 78, "supervisor": 54, "craft_labor": 43, "classification": "Instrument Fitters"}, {"foreman": 15, "manager": 4, "supervisor": 70, "craft_labor": 97, "classification": "Insulators"}, {"foreman": 28, "manager": 93, "supervisor": 3, "craft_labor": 40, "classification": "Others"}]', 'Reprehenderit reici', '[{"phone": "+1 (584) 108-7815", "value": null, "project_name": "Colorado Wong", "work_package": "Est eius ipsum recus", "project_owner": "Magni mollit molliti", "scope_of_work": "Cillum autem quaerat", "work_duration": "Ipsam sit explicabo", "main_contractor": "Earum qui ea unde ir", "year_of_execution": "1970", "client_representative": "Aliqua Sunt cupidat"}, {"phone": "+1 (767) 658-9207", "value": null, "project_name": "Wynter Waters", "work_package": "Dolore porro reprehe", "project_owner": "Quod eos amet iusto", "scope_of_work": "Quod esse in eiusmo", "work_duration": "Libero consequat Se", "main_contractor": "Magni deleniti offic", "year_of_execution": "1981", "client_representative": "Labore dolore dolore"}, {"phone": "+1 (998) 521-4336", "value": null, "project_name": "Irene Bryant", "work_package": "Voluptates excepteur", "project_owner": "Quo culpa et vitae", "scope_of_work": "Iure tempore esse", "work_duration": "Excepturi officia ei", "main_contractor": "Eos quas iste aliqua", "year_of_execution": "1971", "client_representative": "Quae dolore repudian"}]', 'yes', 'Nihil enim ex soluta', '2025-08-22 01:11:07', '2025-08-22 01:12:44');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
