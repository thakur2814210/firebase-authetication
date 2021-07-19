-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.5049
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table webinten_bc.notifications: ~20 rows (approximately)
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` (`id`, `event`, `event_date`, `recipient_user_id`, `active_user_id`) VALUES
	(10, 'Link request received', '2016-03-18 12:32:40', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(11, 'Link request received', '2016-03-18 12:36:05', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(12, 'Link request received', '2016-03-18 12:36:37', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(13, 'Link request received', '2016-03-18 12:36:52', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(14, 'Link request received', '2016-03-18 12:37:15', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(15, 'Link request received', '2016-03-18 12:37:29', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(16, 'Link request received', '2016-03-18 12:37:42', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(17, 'Link request received', '2016-03-18 12:37:55', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(18, 'Link request received', '2016-03-18 12:38:07', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(19, 'Link request received', '2016-03-18 12:38:23', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(20, 'Link request received', '2016-03-18 12:39:14', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(21, 'Link request received', '2016-03-18 12:39:26', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(22, 'Link request received', '2016-03-18 12:40:00', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(23, 'Link request received', '2016-03-18 12:40:37', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(24, 'Link request received', '2016-03-18 12:46:11', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(25, 'Link request received', '2016-03-18 12:48:59', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(26, 'Link request received', '2016-03-18 12:51:52', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(27, 'Link request received', '2016-03-18 12:53:07', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(28, 'Link request received', '2016-03-18 12:54:31', '55aa0234da7465.31665697', '55a68e17697a68.73286341'),
	(29, 'Link request received', '2016-03-18 12:57:30', '55aa0234da7465.31665697', '55a68e17697a68.73286341');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
