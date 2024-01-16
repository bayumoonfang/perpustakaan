-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.2.0.6576
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table baktimulya400sch_library.tbl_book_callnumber
CREATE TABLE IF NOT EXISTS `tbl_book_callnumber` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `library` bigint(20) NOT NULL,
  `book` bigint(20) NOT NULL,
  `callnumber` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table baktimulya400sch_library.tbl_book_callnumber: ~18 rows (approximately)
DELETE FROM `tbl_book_callnumber`;
INSERT INTO `tbl_book_callnumber` (`id`, `library`, `book`, `callnumber`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 10, 16, 'Copy ke-001', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(2, 10, 16, 'Copy ke-002', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(3, 10, 16, 'Copy ke-003', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(4, 10, 16, 'Copy ke-004', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(5, 10, 16, 'Copy ke-005', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(6, 10, 16, 'Copy ke-006', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(7, 10, 16, 'Copy ke-007', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(8, 10, 16, 'Copy ke-008', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(9, 10, 16, 'Copy ke-009', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(10, 10, 16, 'Copy ke-010', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(11, 10, 16, 'Copy ke-011', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(12, 10, 16, 'Copy ke-012', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(13, 10, 16, 'Copy ke-013', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(14, 10, 16, 'Copy ke-014', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(15, 10, 16, 'Copy ke-015', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(16, 10, 16, 'Copy ke-016', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(17, 10, 16, 'Copy ke-017', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL),
	(18, 10, 16, 'Copy ke-018', '2024-01-16 01:24:44', 3, '2024-01-16 01:24:44', 3, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
