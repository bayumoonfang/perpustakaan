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

-- Dumping structure for table baktimulya400sch_library.tbl_books
CREATE TABLE IF NOT EXISTS `tbl_books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `school` bigint(20) NOT NULL,
  `class` bigint(20) NOT NULL,
  `mapel` bigint(20) NOT NULL,
  `library` bigint(20) NOT NULL,
  `rak` bigint(20) NOT NULL,
  `category` bigint(20) NOT NULL,
  `books_subjectid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `year` varchar(5) DEFAULT NULL,
  `kolasi` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `bentuk` varchar(255) DEFAULT NULL,
  `cover` longtext DEFAULT NULL,
  `fileurl` longtext DEFAULT NULL,
  `language` bigint(20) NOT NULL,
  `qty` double NOT NULL DEFAULT 0,
  `is_physical_book` enum('0','1') NOT NULL DEFAULT '0',
  `is_digital_book` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `private` enum('0','1') NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tbl_books_fk1` (`books_subjectid`),
  CONSTRAINT `tbl_books_fk1` FOREIGN KEY (`books_subjectid`) REFERENCES `tbl_subject` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table baktimulya400sch_library.tbl_books: ~21 rows (approximately)
DELETE FROM `tbl_books`;
INSERT INTO `tbl_books` (`id`, `code`, `barcode`, `school`, `class`, `mapel`, `library`, `rak`, `category`, `books_subjectid`, `title`, `author`, `publisher`, `year`, `kolasi`, `isbn`, `bentuk`, `cover`, `fileurl`, `language`, `qty`, `is_physical_book`, `is_digital_book`, `status`, `private`, `price`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 'A001', NULL, 0, 0, 0, 1, 1, 2, NULL, 'Bahasa dan Bersastra Indonesia untuk SMA/SMK Kelas X', 'Fadillah Tri Aulia, Sefi Indra Gumilar', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-325-4', NULL, 'https://library.bukakalabs.com/upload/cover/1663078639-302.png', 'https://library.bukakalabs.com/upload/ebook/1663078639-302.pdf', 1, 5, '1', '1', '1', '0', 19700, '2022-09-08 13:06:47', 1, '2022-09-13 11:17:19', 302, NULL),
	(2, 'AAAA', NULL, 0, 54, 244, 6, 3, 4, NULL, 'Test judul buku', 'Fiersa', 'Erlang', '2011', NULL, '123', NULL, 'https://library.bukakalabs.com/upload/cover/1662996802-1.jpg', 'https://library.bukakalabs.com/upload/ebook/1662996802-1.pdf', 1, 0, '1', '1', '1', '0', 20000, '2022-09-12 12:29:31', 1, '2022-09-12 14:43:11', 1528, '2022-09-12 14:43:11'),
	(3, '7373', NULL, 0, 0, 243, 6, 3, 4, NULL, 'ddu', 'djdu', 'jueeu', '2022', NULL, '87338', NULL, NULL, NULL, 1, 0, '1', '0', '1', '0', 2300, '2022-09-12 12:32:54', 1528, '2022-09-12 14:43:08', 1528, '2022-09-12 14:43:08'),
	(4, 'KODEBUKU', NULL, 0, 1, 4, 4, 2, 3, NULL, 'test ', 'luang', 'pala', '2022', NULL, '123', NULL, 'https://library.bukakalabs.com/upload/cover/1662997545-1.jpeg', 'https://library.bukakalabs.com/upload/ebook/1662997545-1.pdf', 2, 0, '1', '1', '1', '0', 23400, '2022-09-12 12:35:04', 1, '2022-09-12 12:45:45', 1, NULL),
	(5, 'TK001-2022', NULL, 0, 0, 0, 6, 3, 4, NULL, 'Buku Panduan Guru Belajar dan Bermain Berbasis Buku untuk Satuan PAUD', 'Arleen Amidjaja, Anna Farida Kurniasari, Ni Ekawati', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-562-3', NULL, 'https://library.bukakalabs.com/upload/cover/1663004581-1528.png', 'https://library.bukakalabs.com/upload/ebook/1663004581-1528.pdf', 1, 0, '1', '1', '1', '0', 24400, '2022-09-12 14:43:01', 1528, '2022-09-12 14:43:01', 1528, NULL),
	(6, 'TK002-2022', NULL, 0, 53, 245, 6, 3, 4, NULL, 'Buku Panduan Guru Capaian Pembelajaran Elemen Jati Diri', 'C. Ninuk Helista, Oktaviani Puspitasari, Saskhya Aulia Prima, Yuni Dwi Anggraini', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-563-0', NULL, 'https://library.bukakalabs.com/upload/cover/1663004854-1528.png', 'https://library.bukakalabs.com/upload/ebook/1663004854-1528.pdf', 1, 0, '1', '1', '1', '0', 19300, '2022-09-12 14:47:34', 1528, '2022-09-12 14:47:34', 1528, NULL),
	(7, 'TKBM003-2022', NULL, 0, 53, 216, 6, 3, 4, NULL, 'Nilai Agama dan Budi Pekerti', 'Anna Farida Kurniasari, Wiwin Muhyi Susanti', 'Pusat Perbukuan, Badan Standar, Kurikulum, dan Asesmen Pendidikan, Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi', '2021', NULL, '978-602-244-740-5', NULL, 'https://library.bukakalabs.com/upload/cover/1663005065-1528.png', 'https://library.bukakalabs.com/upload/ebook/1663005065-1528.pdf', 1, 0, '1', '1', '1', '0', 22300, '2022-09-12 14:51:05', 1528, '2022-09-12 14:51:05', 1528, NULL),
	(8, 'PG-001-2022', NULL, 0, 6, 21, 1, 1, 5, NULL, 'Buku Panduan Guru Ilmu Pengetahuan Alam untuk SMA Kelas X', 'Ayuk Ratna Puspaningsih, Elizabeth Tjahjadarmawan, Niken Resminingpuri Krisdianti', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-378-0', NULL, 'https://library.bukakalabs.com/upload/cover/1663078251-302.png', 'https://library.bukakalabs.com/upload/ebook/1663078251-302.pdf', 1, 5, '1', '1', '1', '0', 41400, '2022-09-13 11:10:51', 302, '2022-09-13 11:10:51', 302, NULL),
	(9, 'PG-002-2022', NULL, 0, 6, 11, 1, 1, 5, NULL, 'Buku Panduan Guru Bahasa dan Bersastra Indonesia untuk SMA/SMK Kelas X', 'Sefi Indra Gumilar, Fadillah Tri Aulia', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-323-0', NULL, 'https://library.bukakalabs.com/upload/cover/1663078466-302.png', 'https://library.bukakalabs.com/upload/ebook/1663078466-302.pdf', 1, 0, '1', '1', '1', '0', 33300, '2022-09-13 11:14:26', 302, '2022-09-13 11:14:26', 302, NULL),
	(10, 'B6373', NULL, 0, 0, 0, 1, 1, 1, NULL, 'Cerdas Cergas Berbahasa dan Bersastra Indonesia untuk SMA/SMK Kelas XI', 'Heny Marwati, K. Waskitaningtyas', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-324-7', NULL, 'https://library.bukakalabs.com/upload/cover/1663078886-302.png', 'https://library.baktimulya400.sch.id/upload/ebook/1685414832-1.pdf', 1, 0, '1', '1', '1', '0', 18900, '2022-09-13 11:21:26', 302, '2023-05-29 19:47:12', 1, NULL),
	(11, '4545', NULL, 0, 0, 0, 1, 1, 1, NULL, 'Buku Panduan Guru Bahasa Indonesia Tingkat Lanjut Cakap Berbahasa dan Bersastra Indonesia untuk SMA Kelas XI', 'Maman, Rahmah Purwahida', 'Pusat Kurikulum dan Perbukuan', '2021', NULL, '978-602-244-743-6', NULL, 'https://library.bukakalabs.com/upload/cover/1663079102-302.png', 'https://library.baktimulya400.sch.id/upload/ebook/1685414759-1.pdf', 1, 0, '1', '1', '1', '0', 37200, '2022-09-13 11:25:02', 302, '2023-05-29 19:45:59', 1, NULL),
	(12, '06', '1212', 0, 0, 0, 7, 5, 18, NULL, 'Dracula', 'Robert W. Hefner', 'LKiS', '1897', NULL, '979-896-679-1', 'Buku', 'https://library.baktimulya400.sch.id/upload/cover/1695266552-1.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1695266552-1.pdf', 2, 4, '1', '1', '1', '0', 210000, '2022-11-30 05:42:58', 1, '2023-09-20 20:22:32', 1, NULL),
	(14, '1221', '12', 0, 0, 0, 7, 14, 18, 1, 'Biografi Gus Dur : The Authorized Biography of KH. Abdurrahman Wahid', 'Supardi', 'Gava Media', '2009', NULL, '978-979-107-882-5', 'Buku', 'https://library.baktimulya400.sch.id/upload/cover/1695266041-1.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1695266041-1.pdf', 1, 8, '1', '1', '1', '0', 150000, '2022-12-06 05:29:14', 8382, '2023-09-20 20:25:19', 1, NULL),
	(15, 'SMABM042200950', '001', 0, 0, 345, 10, 12, 12, 1, 'Enrich Your Vocabulary Through', 'Salomo Simanungkalit', 'Crest Publishing House', '2021', NULL, '978-521-61403-0', 'Text', 'https://library.baktimulya400.sch.id/upload/cover/1687368930-8810.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1687368930-8810.pdf', 2, 8, '1', '1', '1', '0', 25000, '2023-06-21 10:35:30', 8810, '2023-06-21 10:35:30', 8810, NULL),
	(16, 'SMPBM1301353', '001', 0, 0, 0, 10, 12, 12, 1, 'Harry Potter dan Kamar Rahasia', 'test', 'test', '2023', NULL, '12013310293', 'Text', 'https://library.baktimulya400.sch.id/upload/cover/1687415798-8810.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1687415798-8810.pdf', 1, 18, '1', '1', '1', '0', 20000, '2023-06-21 23:36:38', 8810, '2023-06-25 20:27:46', 8810, NULL),
	(17, '813', '', 0, 0, 0, 1, 4, 2, 1, 'Koala Kumal', 'Raditya Dika', 'Gagas Media Jakarta', '2015', NULL, ' 979-780-769-X', '249 hal. :ilus. ;21 cm.', 'https://library.baktimulya400.sch.id/upload/cover/1690419132-7176.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1690419132-7176.pdf', 1, 5, '1', '1', '1', '0', 66000, '2023-07-26 17:52:12', 7176, '2023-07-26 18:05:53', 7176, NULL),
	(18, '330', '', 0, 0, 0, 1, 4, 13, 1, 'How to be Successful Investor', 'William Cai', 'PT Elex Media Komputindo', '2012', NULL, '9786020277424', '245 hlm', 'https://library.baktimulya400.sch.id/upload/cover/1690857111-7176.png', NULL, 1, 1, '1', '0', '1', '0', 79000, '2023-07-31 19:31:51', 7176, '2023-07-31 19:33:56', 7176, NULL),
	(19, '813.3', '', 0, 0, 0, 1, 4, 2, 1, ' The Da Vinci Code', 'Dan Brown', 'Mizan', '2013', NULL, '979-335-80-7', ' 624 hlm. : ilus. ; 24 cm.', 'https://library.baktimulya400.sch.id/upload/cover/1691025770-7176.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1691025770-7176.pdf', 1, 1, '1', '1', '1', '0', 79000, '2023-08-02 18:22:50', 7176, '2023-08-02 18:22:50', 7176, NULL),
	(20, '082', '', 0, 0, 0, 1, 4, 2, 1, 'How To Be Interesting', 'Jessica Hagy', 'Workman Publishing', '2013', NULL, '9780761174707', '263 hlm', 'https://library.baktimulya400.sch.id/upload/cover/1691108577-7176.jpg', NULL, 2, 1, '1', '0', '1', '0', 76000, '2023-08-03 17:22:57', 7176, '2023-08-03 17:22:57', 7176, NULL),
	(21, '0812', '1221', 0, 0, 0, 7, 7, 18, 1, 'The Captain : A Magazine for Boys & "Old Boys"', 'Percy F. Westerman', 'E.S. Hodgson', '2018', NULL, '98-217-311-386-482', 'Buku', 'https://library.baktimulya400.sch.id/upload/cover/1695270343-1.jpg', 'https://library.baktimulya400.sch.id/upload/ebook/1695270343-1.pdf', 2, 10, '1', '1', '1', '0', 90000, '2023-09-20 21:25:43', 1, '2023-09-20 21:25:43', 1, NULL),
	(22, 'N00001', '000011222', 0, 0, 0, 11, 15, 19, 1, 'Harry Potter And The Philosopher Stone', 'J.K. Rowling', 'Gramedia', '2001', 'kolasi', '9781408855652 ', 'Text', 'http://localhost/library/upload/cover/1703169393-3.jpeg', 'http://localhost/library/upload/ebook/1703169394-3.pdf', 1, 0, '1', '1', '1', '0', 160000, '2023-12-21 08:29:44', 3, '2023-12-21 08:36:34', 3, NULL),
	(23, 'N00002', '000011222', 0, 0, 0, 10, 12, 19, 1, 'Harry Potter And The Chamber of Secret', 'J.K. Rowling', 'Gramedia', '2001', 'kolasi', '9781408855652 ', 'Buku', 'http://localhost/library/upload/cover/1703169393-3.jpeg', 'http://localhost/library/upload/ebook/1703169394-3.pdf', 1, 0, '1', '1', '1', '0', 160000, '2023-12-21 08:29:44', 3, '2023-12-21 08:36:34', 3, NULL);

-- Dumping structure for table baktimulya400sch_library.tbl_subject
CREATE TABLE IF NOT EXISTS `tbl_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `library` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `min_value` varchar(7) DEFAULT NULL,
  `max_value` varchar(7) DEFAULT NULL,
  `length_value` int(2) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table baktimulya400sch_library.tbl_subject: ~3 rows (approximately)
DELETE FROM `tbl_subject`;
INSERT INTO `tbl_subject` (`id`, `library`, `name`, `min_value`, `max_value`, `length_value`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 10, 'Bidang Umum', '000', '099', 3, '1', '2023-12-23 21:37:53', 8432, '2023-12-23 21:37:53', 8432, NULL),
	(2, 11, 'Filsafat', '100', '199', 3, '1', '2023-12-23 21:39:36', 8432, '2023-12-23 21:39:36', 8432, NULL),
	(3, 10, 'Ilmu Sosial', '200', '299', 3, '1', '2023-12-23 21:41:08', 8432, '2023-12-23 21:41:08', 8432, NULL);

-- Dumping structure for table baktimulya400sch_library.tbl_type
CREATE TABLE IF NOT EXISTS `tbl_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `library` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table baktimulya400sch_library.tbl_type: ~7 rows (approximately)
DELETE FROM `tbl_type`;
INSERT INTO `tbl_type` (`id`, `library`, `name`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 10, 'Buku', '1', '2023-12-22 02:10:40', 8432, '2023-12-22 02:10:40', 8432, NULL),
	(2, 10, 'Text', '1', '2023-12-22 02:18:17', 8432, '2023-12-22 02:18:17', 8432, NULL),
	(3, 10, 'test-audio', '1', '2023-12-22 02:18:32', 8432, '2023-12-22 02:18:32', 8432, NULL),
	(4, 10, 'Amburadul', '1', '2023-12-22 02:18:54', 8432, '2023-12-25 07:49:18', 8432, NULL),
	(5, 10, 'Open', '1', '2023-12-22 02:20:29', 8432, '2023-12-25 07:50:16', 8432, NULL),
	(6, 10, 'Server Medan', '1', '2023-12-22 02:21:01', 8432, '2023-12-22 02:21:01', 8432, NULL),
	(7, 10, 'Hore', '1', '2023-12-22 20:35:39', 8432, '2023-12-22 20:35:39', 8432, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
