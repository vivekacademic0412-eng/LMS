-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 12:46 PM
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
-- Database: `lmsam`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(120) NOT NULL,
  `action` varchar(80) NOT NULL,
  `description` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` varchar(120) DEFAULT NULL,
  `subject_label` varchar(255) DEFAULT NULL,
  `route_name` varchar(255) DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `subject_type`, `subject_id`, `subject_label`, `route_name`, `method`, `url`, `ip_address`, `user_agent`, `properties`, `created_at`, `updated_at`) VALUES
(1, NULL, 'System', 'maintenance', 'Enhanced activity tracking was enabled for login, logout, and write actions.', NULL, NULL, 'Activity Logging Upgrade', NULL, 'SYSTEM', 'codex://activity-logging-upgrade', NULL, NULL, '{\"includes\":[\"login\",\"logout\",\"write actions\"],\"logged_at\":\"2026-03-25 12:34:02\"}', '2026-03-25 07:04:02', '2026-03-25 07:04:02'),
(2, 6, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-25 07:17:38', '2026-03-25 07:17:38'),
(3, 2, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '2', 'Admin | admin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Admin\",\"email\":\"admin@lms.test\",\"role\":\"admin\"}}', '2026-03-25 07:18:02', '2026-03-25 07:18:02'),
(4, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-27 05:07:25', '2026-03-27 05:07:25'),
(5, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-27 05:07:38', '2026-03-27 05:07:38'),
(6, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-27 05:08:31', '2026-03-27 05:08:31'),
(7, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-27 23:17:30', '2026-03-27 23:17:30'),
(8, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-27 23:19:52', '2026-03-27 23:19:52'),
(9, 1, 'Demo Tasks', 'assign', 'Assigned a demo task.', NULL, NULL, 'demo -> test34', 'demo-tasks.assign', 'POST', 'http://127.0.0.1:8000/demo-tasks/assign?4=', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"4\",\"user_id\":\"80\",\"4\":null,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-27 23:23:20', '2026-03-27 23:23:20'),
(10, 1, 'Demo Tasks', 'assign', 'Assigned a demo task.', NULL, NULL, 'demo -> test34', 'demo-tasks.assign', 'POST', 'http://127.0.0.1:8000/demo-tasks/assign?4=', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"4\",\"user_id\":\"80\",\"4\":null,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-27 23:23:27', '2026-03-27 23:23:27'),
(11, 6, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-27 23:40:20', '2026-03-27 23:40:20'),
(12, 4, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-27 23:40:32', '2026-03-27 23:40:32'),
(13, 4, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-27 23:42:00', '2026-03-27 23:42:00'),
(14, 3, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '3', 'Manager HR | manager.hr@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Manager HR\",\"email\":\"manager.hr@lms.test\",\"role\":\"manager_hr\"}}', '2026-03-27 23:42:13', '2026-03-27 23:42:13'),
(15, 3, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '3', 'Manager HR | manager.hr@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Manager HR\",\"email\":\"manager.hr@lms.test\",\"role\":\"manager_hr\"}}', '2026-03-27 23:51:21', '2026-03-27 23:51:21'),
(16, 3, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '3', 'Manager HR | manager.hr@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Manager HR\",\"email\":\"manager.hr@lms.test\",\"role\":\"manager_hr\"}}', '2026-03-27 23:51:41', '2026-03-27 23:51:41'),
(17, 3, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '3', 'Manager HR | manager.hr@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Manager HR\",\"email\":\"manager.hr@lms.test\",\"role\":\"manager_hr\"}}', '2026-03-28 00:01:38', '2026-03-28 00:01:38'),
(18, 4, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-28 00:02:02', '2026-03-28 00:02:02'),
(19, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:26:11', '2026-03-30 00:26:11'),
(20, 6, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:28:24', '2026-03-30 00:28:24'),
(21, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 00:29:00', '2026-03-30 00:29:00'),
(22, 1, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 00:41:09', '2026-03-30 00:41:09'),
(23, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'demo | demo@gmail.com', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 00:41:20', '2026-03-30 00:41:20'),
(24, 80, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '80', 'demo | demo@gmail.com', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 00:42:19', '2026-03-30 00:42:19'),
(25, 4, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-30 00:45:05', '2026-03-30 00:45:05'),
(26, 4, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-30 00:48:47', '2026-03-30 00:48:47'),
(27, 2, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '2', 'Admin | admin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Admin\",\"email\":\"admin@lms.test\",\"role\":\"admin\"}}', '2026-03-30 00:49:03', '2026-03-30 00:49:03'),
(28, 2, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '2', 'Admin | admin@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Admin\",\"email\":\"admin@lms.test\",\"role\":\"admin\"}}', '2026-03-30 00:50:48', '2026-03-30 00:50:48'),
(29, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:51:08', '2026-03-30 00:51:08'),
(30, 6, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:53:55', '2026-03-30 00:53:55'),
(31, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:54:14', '2026-03-30 00:54:14'),
(32, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:55:30', '2026-03-30 00:55:30'),
(33, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 00:57:04', '2026-03-30 00:57:04'),
(34, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 00:58:36', '2026-03-30 00:58:36'),
(35, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 02:06:06', '2026-03-30 02:06:06'),
(36, 4, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-30 02:12:35', '2026-03-30 02:12:35'),
(37, 4, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '4', 'IT | it@lms.test', 'logout', 'POST', 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"IT\",\"email\":\"it@lms.test\",\"role\":\"it\"}}', '2026-03-30 02:13:18', '2026-03-30 02:13:18'),
(38, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 02:14:05', '2026-03-30 02:14:05'),
(39, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 04:28:51', '2026-03-30 04:28:51'),
(40, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 04:45:07', '2026-03-30 04:45:07'),
(41, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 04:48:35', '2026-03-30 04:48:35'),
(42, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 05:21:52', '2026-03-30 05:21:52'),
(43, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 05:28:59', '2026-03-30 05:28:59'),
(44, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 05:29:25', '2026-03-30 05:29:25'),
(45, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 05:35:37', '2026-03-30 05:35:37'),
(46, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:36:19', '2026-03-30 05:36:19'),
(47, 1, 'Demo Feature Videos', 'delete', 'Deleted a demo feature video.', 'App\\Models\\DemoFeatureVideo', '3', 'ranveer', 'demo-feature-video.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-feature-video/3', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:39:20', '2026-03-30 05:39:20'),
(48, 1, 'Demo Feature Videos', 'delete', 'Deleted a demo feature video.', 'App\\Models\\DemoFeatureVideo', '1', 'For demo', 'demo-feature-video.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-feature-video/1', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:49:40', '2026-03-30 05:49:40'),
(49, 1, 'Demo Feature Videos', 'delete', 'Deleted a demo feature video.', 'App\\Models\\DemoFeatureVideo', '2', 'tes2', 'demo-feature-video.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-feature-video/2', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:49:49', '2026-03-30 05:49:49'),
(50, 1, 'Review Videos', 'delete', 'Deleted a review video.', 'App\\Models\\DemoReviewVideo', '3', 'wdwqddwqwqdwdq', 'demo-review-videos.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-review-videos/3', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:50:25', '2026-03-30 05:50:25'),
(51, 1, 'Review Videos', 'delete', 'Deleted a review video.', 'App\\Models\\DemoReviewVideo', '2', 'test', 'demo-review-videos.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-review-videos/2', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:50:36', '2026-03-30 05:50:36'),
(52, 1, 'Review Videos', 'delete', 'Deleted a review video.', 'App\\Models\\DemoReviewVideo', '1', 'youtube', 'demo-review-videos.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-review-videos/1', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:50:43', '2026-03-30 05:50:43'),
(53, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_url\":\"https:\\/\\/youtu.be\\/n0i98MURRBE\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations Astha for completing your internship Successfully.\\r\\nAstha is now working as Backend Academic Writer at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:52:21', '2026-03-30 05:52:21'),
(54, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/jVzeHpsTH8c\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations, Saurav, for completing your internship successfully.\\r\\nSaurav is now working as a Digital Marketing Executive at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:55:03', '2026-03-30 05:55:03'),
(55, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"3\",\"video_url\":\"https:\\/\\/youtu.be\\/5dYOb6RcTqI\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations, Pihu, for completing your internship successfully.\\r\\nPihu is now working as an HR Executive at Job Suraksha.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:56:32', '2026-03-30 05:56:32'),
(56, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"4\",\"video_url\":\"https:\\/\\/youtu.be\\/VfFl1bdJBVI\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations, Prabh, for completing your internship successfully.\\r\\nPrabh is now working as a Digital Marketing Executive at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 05:58:43', '2026-03-30 05:58:43'),
(57, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"5\",\"video_url\":\"https:\\/\\/youtu.be\\/eNEALxE24Xs\",\"title\":\"Student Reviews || Enhance Skills with Academic Mantra Services\",\"description\":\"Shivani has joined our live internship for the past 10 days as a content writer,\\r\\nYou can also kickstart your career with the Academic Mantra Services Internship Program. Enrol Today!\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:00:15', '2026-03-30 06:00:15'),
(58, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"5\",\"video_url\":\"https:\\/\\/youtu.be\\/eNEALxE24Xs\",\"title\":\"Student Reviews || Enhance Skills with Academic Mantra Services\",\"description\":\"Shivani has joined our live internship for the past 10 days as a content writer,\\r\\nYou can also kickstart your career with the Academic Mantra Services Internship Program. Enrol Today!\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:01:48', '2026-03-30 06:01:48'),
(59, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"6\",\"video_url\":\"https:\\/\\/youtu.be\\/R-oC2E8f5-s\",\"title\":\"Student Reviews || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations, Urvashi, for completing your HR internship successfully.\\r\\nUrvashi is now working as an HR Executive at Job Suraksha.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:05:23', '2026-03-30 06:05:23'),
(60, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"7\",\"video_url\":\"https:\\/\\/youtu.be\\/wn_Os8j7W30\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations, Shivangi, for completing your internship successfully.\\r\\nShivangi is now working as a Backend Academic Writer at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:06:56', '2026-03-30 06:06:56'),
(61, 1, 'Review Videos', 'create', 'Created a review video.', NULL, NULL, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.store', 'POST', 'http://192.168.1.6:8000/demo-review-videos', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"8\",\"video_url\":\"https:\\/\\/youtu.be\\/jgY_f7pjvjI\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Arshdeep has joined our 3-month live internship as an HR,\\r\\nYou can also kickstart your career with the Academic Mantra Services Internship Program. Enrol Today!\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:09:00', '2026-03-30 06:09:00'),
(62, 1, 'Demo Feature Videos', 'create', 'Created a demo feature video.', NULL, NULL, 'test', 'demo-feature-video.store', 'POST', 'http://192.168.1.6:8000/demo-feature-video', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"test\",\"description\":\"test\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:12:11', '2026-03-30 06:12:11'),
(63, 1, 'Demo Feature Videos', 'create', 'Created a demo feature video.', NULL, NULL, 'tst2', 'demo-feature-video.store', 'POST', 'http://192.168.1.6:8000/demo-feature-video', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/2-xE_NJ2iXU\",\"title\":\"tst2\",\"description\":\"tes3\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:13:16', '2026-03-30 06:13:16'),
(64, 1, 'Demo Feature Videos', 'create', 'Created a demo feature video.', NULL, NULL, 'tst2', 'demo-feature-video.store', 'POST', 'http://192.168.1.6:8000/demo-feature-video', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/2-xE_NJ2iXU\",\"title\":\"tst2\",\"description\":\"tes3\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:13:20', '2026-03-30 06:13:20'),
(65, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'test', 'demo-feature-video.update', 'PUT', 'http://192.168.1.6:8000/demo-feature-video/4', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/2-xE_NJ2iXU\",\"title\":\"Celebrating Internship Day\",\"description\":\"Celebrating Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:17:07', '2026-03-30 06:17:07'),
(66, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '5', 'Celebrating Internship Day', 'demo-feature-video.update', 'PUT', 'http://192.168.1.6:8000/demo-feature-video/5', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/2-xE_NJ2iXU\",\"title\":\"Celebrating Internship Day\",\"description\":\"Celebrating Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:17:46', '2026-03-30 06:17:46'),
(67, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Celebrating Internship Day', 'demo-feature-video.update', 'PUT', 'http://192.168.1.6:8000/demo-feature-video/4', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Celebrating Internship Day\",\"description\":\"Celebrating Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:18:04', '2026-03-30 06:18:04'),
(68, 1, 'Demo Feature Videos', 'create', 'Created a demo feature video.', NULL, NULL, 'test', 'demo-feature-video.store', 'POST', 'http://192.168.1.6:8000/demo-feature-video', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"3\",\"video_url\":null,\"title\":\"test\",\"description\":\"test\",\"video_file\":{\"file_name\":\"cld-sample-video.mp4\",\"mime\":\"video\\/mp4\",\"size\":35988643},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:23:13', '2026-03-30 06:23:13'),
(69, 1, 'Demo Feature Videos', 'delete', 'Deleted a demo feature video.', 'App\\Models\\DemoFeatureVideo', '6', 'test', 'demo-feature-video.destroy', 'DELETE', 'http://192.168.1.6:8000/demo-feature-video/6', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:24:56', '2026-03-30 06:24:56'),
(70, 1, 'Users', 'update', 'Updated a user account.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'users.update', 'PUT', 'http://192.168.1.6:8000/users/80', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\",\"is_active\":\"1\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:26:42', '2026-03-30 06:26:42'),
(71, 80, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 06:37:06', '2026-03-30 06:37:06'),
(72, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 06:37:31', '2026-03-30 06:37:31'),
(73, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 06:43:07', '2026-03-30 06:43:07'),
(74, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 06:43:30', '2026-03-30 06:43:30'),
(75, 1, 'Profiles', 'update', 'Updated a profile.', NULL, NULL, 'Super Admin', 'profile.update', 'PUT', 'http://192.168.1.6:8000/profile', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"avatar\":{\"file_name\":\"Gemini_Generated_Image_6y3b316y3b316y3b.png\",\"mime\":\"image\\/png\",\"size\":214100},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:46:21', '2026-03-30 06:46:21'),
(76, 1, 'Users', 'update', 'Updated a user account.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'users.update', 'PUT', 'http://192.168.1.6:8000/users/80', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\",\"is_active\":\"1\",\"avatar\":{\"file_name\":\"Gemini_Generated_Image_6y3b316y3b316y3b.png\",\"mime\":\"image\\/png\",\"size\":214100},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 06:47:31', '2026-03-30 06:47:31'),
(77, 80, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 06:50:32', '2026-03-30 06:50:32'),
(78, 40, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 06:50:45', '2026-03-30 06:50:45'),
(79, 40, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '40', 'Student 1 | student1@lms.test', 'logout', 'POST', 'http://192.168.1.6:8000/logout', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student 1\",\"email\":\"student1@lms.test\",\"role\":\"student\"}}', '2026-03-30 06:51:45', '2026-03-30 06:51:45'),
(80, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.6:8000/login', '192.168.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 06:52:00', '2026-03-30 06:52:00'),
(81, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 22:48:33', '2026-03-30 22:48:33'),
(82, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 22:48:50', '2026-03-30 22:48:50'),
(83, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-30 23:34:05', '2026-03-30 23:34:05'),
(84, 1, 'Demo Tasks', 'delete', 'Deleted a demo task.', 'App\\Models\\DemoTask', '4', 'test34', 'demo-tasks.destroy', 'DELETE', 'http://192.168.1.15:8000/demo-tasks/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 23:54:54', '2026-03-30 23:54:54'),
(85, 1, 'Demo Tasks', 'assign', 'Assigned a demo task.', NULL, NULL, 'Demo -> Task Instructions', 'demo-tasks.assign', 'POST', 'http://192.168.1.15:8000/demo-tasks/assign?3=', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"3\",\"user_id\":\"80\",\"3\":null,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 23:55:04', '2026-03-30 23:55:04'),
(86, 1, 'Demo Tasks', 'create', 'Created a demo task.', NULL, NULL, 'placeholder for title', 'demo-tasks.store', 'POST', 'http://192.168.1.15:8000/demo-tasks', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"title\":\"placeholder for title\",\"resource_url\":null,\"ai_video_url\":\"https:\\/\\/app.videoexpress.ai\\/\",\"description\":\"placeholder for Description\",\"task_video\":{\"file_name\":\"cld-sample-video.mp4\",\"mime\":\"video\\/mp4\",\"size\":35988643},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 23:58:46', '2026-03-30 23:58:46'),
(87, 1, 'Demo Tasks', 'update', 'Updated a demo task assignment.', 'App\\Models\\DemoTaskAssignment', '6', 'Demo -> placeholder for title', 'demo-tasks.assignments.update', 'PUT', 'http://192.168.1.15:8000/demo-tasks/assign/6', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"5\",\"user_id\":\"80\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-30 23:59:01', '2026-03-30 23:59:01'),
(88, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-31 07:18:02', '2026-03-31 07:18:02'),
(89, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-03-31 22:43:55', '2026-03-31 22:43:55'),
(90, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-03-31 22:44:52', '2026-03-31 22:44:52'),
(91, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 06:04:14', '2026-04-01 06:04:14'),
(92, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 06:04:22', '2026-04-01 06:04:22'),
(93, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '6', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/6/submit', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"answer_text\":null,\"submission_file\":{\"file_name\":\"Ibrahim. 8000 words.completed..docx\",\"mime\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"size\":3607180},\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 06:12:56', '2026-04-01 06:12:56'),
(94, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '6', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/6/submit', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"answer_text\":null,\"submission_file\":{\"file_name\":\"Ibrahim. 8000 words.completed..docx\",\"mime\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"size\":3607180},\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 06:12:58', '2026-04-01 06:12:58'),
(95, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 06:49:22', '2026-04-01 06:49:22'),
(96, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Peep into live skills training @ Academic Mantra Services.', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Peep into live skills training @ Academic Mantra Services.\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 07:19:37', '2026-04-01 07:19:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `subject_type`, `subject_id`, `subject_label`, `route_name`, `method`, `url`, `ip_address`, `user_agent`, `properties`, `created_at`, `updated_at`) VALUES
(97, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '5', 'Peep into live skills training @Academic Mantra Services', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/5', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"2\",\"video_url\":\"https:\\/\\/youtu.be\\/2-xE_NJ2iXU\",\"title\":\"Peep into live skills training @Academic Mantra Services\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 07:20:06', '2026-04-01 07:20:06'),
(98, 1, 'Demo Tasks', 'delete', 'Deleted a demo task assignment.', 'App\\Models\\DemoTaskAssignment', '6', 'Demo -> placeholder for title', 'demo-tasks.assignments.destroy', 'DELETE', 'http://192.168.1.15:8000/demo-tasks/assign/6', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 07:43:39', '2026-04-01 07:43:39'),
(99, 1, 'Demo Tasks', 'assign', 'Assigned a demo task.', NULL, NULL, 'Demo -> placeholder for title', 'demo-tasks.assign', 'POST', 'http://192.168.1.15:8000/demo-tasks/assign?5=', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"5\",\"user_id\":\"80\",\"5\":null,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 07:44:05', '2026-04-01 07:44:05'),
(100, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-01 22:38:57', '2026-04-01 22:38:57'),
(101, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 22:40:56', '2026-04-01 22:40:56'),
(102, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"Ranveer\",\"participant_email\":\"ranveer@gmail.com\",\"participant_phone\":\"6367205305\",\"answer_text\":\"test\",\"submission_file\":{\"file_name\":\"java-certificate.pdf\",\"mime\":\"application\\/pdf\",\"size\":457911},\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 23:32:07', '2026-04-01 23:32:07'),
(103, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test\",\"participant_email\":\"test@gmail.com\",\"participant_phone\":\"88809809809\",\"answer_text\":\"test1\",\"submission_file\":{\"file_name\":\"MEM23004A _H_A2 (1) (1).docx\",\"mime\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"size\":2476904},\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 23:37:33', '2026-04-01 23:37:33'),
(104, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 23:43:57', '2026-04-01 23:43:57'),
(105, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test2\",\"participant_email\":\"test2@gmail.com\",\"participant_phone\":\"809890880809\",\"answer_text\":\"test2\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 23:48:15', '2026-04-01 23:48:15'),
(106, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test3\",\"participant_email\":\"test2a@gmail.com\",\"participant_phone\":\"989890809809\",\"answer_text\":\"test3\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-01 23:56:26', '2026-04-01 23:56:26'),
(107, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test\",\"participant_email\":\"test@gmail.com\",\"participant_phone\":\"7698798\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 00:00:31', '2026-04-02 00:00:31'),
(108, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"w\",\"participant_email\":\"y@gmail.com\",\"participant_phone\":\"7780798009\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 00:04:33', '2026-04-02 00:04:33'),
(109, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"sa\",\"participant_email\":\"vivekacademic.0412@gmail.com\",\"participant_phone\":\"88809809809\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 00:06:46', '2026-04-02 00:06:46'),
(110, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"task3\",\"participant_email\":\"task3@gmail.com\",\"participant_phone\":\"80980808\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 00:15:36', '2026-04-02 00:15:36'),
(111, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test\",\"participant_email\":\"vivekacademic.0412@gmail.com\",\"participant_phone\":\"88809809809\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 00:32:33', '2026-04-02 00:32:33'),
(112, 1, 'Demo Tasks', 'update', 'Updated a demo task.', 'App\\Models\\DemoTask', '5', 'placeholder for title', 'demo-tasks.update', 'PUT', 'http://192.168.1.15:8000/demo-tasks/5', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"title\":\"placeholder for title\",\"resource_url\":null,\"ai_video_url\":\"https:\\/\\/app.videoexpress.ai\\/\",\"description\":\"placeholder for Description\",\"task_video\":{\"file_name\":\"3283 (1).mp4\",\"mime\":\"video\\/mp4\",\"size\":24182691},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-02 00:43:04', '2026-04-02 00:43:04'),
(113, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"jdjwqkj\",\"participant_email\":\"iuhihi@gmail.com\",\"participant_phone\":\"hiuhihi\",\"video_rating\":\"1\",\"answer_text\":null,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 01:16:27', '2026-04-02 01:16:27'),
(114, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"Ranveer\",\"participant_email\":\"vivekacademic.0412@gmail.com\",\"participant_phone\":\"8987708709879\",\"video_rating\":\"4\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 01:17:56', '2026-04-02 01:17:56'),
(115, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"test\",\"participant_email\":\"vivekacademic.0412@gmail.com\",\"participant_phone\":\"6367205305\",\"video_rating\":\"3\",\"answer_text\":\"test\",\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 01:25:49', '2026-04-02 01:25:49'),
(116, 80, 'Demo Tasks', 'submit', 'Submitted a demo task response.', 'App\\Models\\DemoTaskAssignment', '7', 'Demo -> placeholder for title', 'demo-assignments.submit', 'POST', 'http://192.168.1.15:8000/demo-assignments/7/submit', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"participant_name\":\"Ranveer\",\"participant_email\":\"vivekacademic.0412@gmail.com\",\"participant_phone\":\"6367205305\",\"video_rating\":\"5\",\"answer_text\":null,\"submission_file\":{\"file_name\":\"java-certificate (2).pdf\",\"mime\":\"application\\/pdf\",\"size\":457911},\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-02 02:15:39', '2026-04-02 02:15:39'),
(117, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-02 22:45:37', '2026-04-02 22:45:37'),
(118, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-03 07:41:27', '2026-04-03 07:41:27'),
(119, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-03 23:05:01', '2026-04-03 23:05:01'),
(120, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-03 23:20:22', '2026-04-03 23:20:22'),
(121, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-03 23:26:05', '2026-04-03 23:26:05'),
(122, 1, 'Review Videos', 'update', 'Updated a review video.', 'App\\Models\\DemoReviewVideo', '5', 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.update', 'PUT', 'http://192.168.1.15:8000/demo-review-videos/5', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"reel\",\"video_url\":\"https:\\/\\/youtu.be\\/n0i98MURRBE\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations Astha for completing your internship Successfully.\\r\\nAstha is now working as Backend Academic Writer at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-03 23:27:47', '2026-04-03 23:27:47'),
(123, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Peep into live skills training @ Academic Mantra Services.', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"reel\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Peep into live skills training @ Academic Mantra Services.\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 00:10:07', '2026-04-04 00:10:07'),
(124, 1, 'Demo Tasks', 'assign', 'Assigned a demo task.', NULL, NULL, 'Demo -> Task Instructions', 'demo-tasks.assign', 'POST', 'http://192.168.1.15:8000/demo-tasks/assign?5=', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"demo_task_id\":\"3\",\"user_id\":\"80\",\"5\":null,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 00:11:07', '2026-04-04 00:11:07'),
(125, 1, 'Demo Tasks', 'update', 'Updated a demo task.', 'App\\Models\\DemoTask', '3', 'Task Instructions', 'demo-tasks.update', 'PUT', 'http://192.168.1.15:8000/demo-tasks/3', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"title\":\"Task Instructions\",\"resource_url\":null,\"ai_video_url\":\"https:\\/\\/drive.google.com\\/file\\/d\\/1dWbTCC6ATyIl8U1rBEDGk0Yhlr8mRvQk\\/view\",\"video_ratio\":\"reel\",\"description\":\"Go to video tool and make a video.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 00:11:53', '2026-04-04 00:11:53'),
(126, 1, 'Demo Tasks', 'update', 'Updated a demo task.', 'App\\Models\\DemoTask', '3', 'Task Instructions', 'demo-tasks.update', 'PUT', 'http://192.168.1.15:8000/demo-tasks/3', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"title\":\"Task Instructions\",\"resource_url\":null,\"ai_video_url\":\"https:\\/\\/drive.google.com\\/file\\/d\\/1dWbTCC6ATyIl8U1rBEDGk0Yhlr8mRvQk\\/view\",\"video_ratio\":\"landscape\",\"description\":\"Go to video tool and make a video.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 00:12:12', '2026-04-04 00:12:12'),
(127, 1, 'Review Videos', 'update', 'Updated a review video.', 'App\\Models\\DemoReviewVideo', '5', 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'demo-review-videos.update', 'PUT', 'http://192.168.1.15:8000/demo-review-videos/5', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"landscape\",\"video_url\":\"https:\\/\\/youtu.be\\/n0i98MURRBE\",\"title\":\"Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services\",\"description\":\"Congratulations Astha for completing your internship Successfully.\\r\\nAstha is now working as Backend Academic Writer at Academic Mantra Services.\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 01:17:05', '2026-04-04 01:17:05'),
(128, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Peep into live skills training @ Academic Mantra Services.', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"landscape\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Peep into live skills training @ Academic Mantra Services.\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 01:17:42', '2026-04-04 01:17:42'),
(129, 80, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'logout', 'POST', 'http://192.168.1.15:8000/logout', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-04 02:40:36', '2026-04-04 02:40:36'),
(130, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-04 02:42:53', '2026-04-04 02:42:53'),
(131, 80, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'logout', 'POST', 'http://192.168.1.15:8000/logout', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-04 02:43:44', '2026-04-04 02:43:44'),
(132, 6, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-04-04 02:44:44', '2026-04-04 02:44:44'),
(133, 6, 'Authentication', 'logout', 'Signed out of the LMS.', 'App\\Models\\User', '6', 'Student | student@lms.test', 'logout', 'POST', 'http://192.168.1.15:8000/logout', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Student\",\"email\":\"student@lms.test\",\"role\":\"student\"}}', '2026-04-04 02:55:11', '2026-04-04 02:55:11'),
(134, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.15:8000/login', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-04 02:55:28', '2026-04-04 02:55:28'),
(135, 1, 'Demo Tasks', 'delete', 'Deleted a demo task assignment.', 'App\\Models\\DemoTaskAssignment', '8', 'Demo -> Task Instructions', 'demo-tasks.assignments.destroy', 'DELETE', 'http://192.168.1.15:8000/demo-tasks/assign/8', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 03:00:32', '2026-04-04 03:00:32'),
(136, 1, 'Demo Tasks', 'update', 'Updated a demo task.', 'App\\Models\\DemoTask', '5', 'placeholder for title', 'demo-tasks.update', 'PUT', 'http://192.168.1.15:8000/demo-tasks/5', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"title\":\"placeholder for title\",\"resource_url\":null,\"ai_video_url\":\"https:\\/\\/app.videoexpress.ai\\/\",\"video_ratio\":\"landscape\",\"description\":\"placeholder for Description\",\"task_video\":{\"file_name\":\"by (1).mp4\",\"mime\":\"video\\/mp4\",\"size\":41259094},\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 03:04:49', '2026-04-04 03:04:49'),
(137, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Peep into live skills training @ Academic Mantra Services.', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"reel\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Peep into live skills training @ Academic Mantra Services.\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 03:06:37', '2026-04-04 03:06:37'),
(138, 1, 'Demo Feature Videos', 'update', 'Updated a demo feature video.', 'App\\Models\\DemoFeatureVideo', '4', 'Peep into live skills training @ Academic Mantra Services.', 'demo-feature-video.update', 'PUT', 'http://192.168.1.15:8000/demo-feature-video/4', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"position\":\"1\",\"video_ratio\":\"landscape\",\"video_url\":\"https:\\/\\/youtu.be\\/FuOYucvtIJM\",\"title\":\"Peep into live skills training @ Academic Mantra Services.\",\"description\":\"Have a look at our Internship Day\",\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-04 03:07:05', '2026-04-04 03:07:05'),
(139, 80, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '80', 'Demo | demo@gmail.com', 'login.attempt', 'POST', 'http://192.168.1.3:8000/login', '192.168.1.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Demo\",\"email\":\"demo@gmail.com\",\"role\":\"demo\"}}', '2026-04-05 23:01:48', '2026-04-05 23:01:48'),
(140, 1, 'Authentication', 'login', 'Signed in to the LMS.', 'App\\Models\\User', '1', 'Super Admin | superadmin@lms.test', 'login.attempt', 'POST', 'http://192.168.1.3:8000/login', '192.168.1.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"remember\":false,\"_actor\":{\"name\":\"Super Admin\",\"email\":\"superadmin@lms.test\",\"role\":\"superadmin\"}}', '2026-04-05 23:02:42', '2026-04-05 23:02:42');

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
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(160) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `slug` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `language` varchar(80) DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `duration_hours` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `subcategory_id`, `title`, `short_description`, `slug`, `description`, `language`, `thumbnail`, `duration_hours`, `created_by`, `created_at`, `updated_at`) VALUES
(54, 15, 19, 'JAVA', 'test java', 'java-1pzl', 'test java', 'hindi', 'course-thumbnails/by0ExDPkT5x8noKB5Xp5DtlQal5QYOnOCtonQZoS.webp', 8, 1, '2026-03-15 23:46:46', '2026-03-15 23:46:46'),
(55, 15, 22, 'test2', 'test', 'test2-rgqc', 'test', 'hindi', 'course-thumbnails/OvCvzCwVSh0ugFgJdYEdQsIseXpBzRQduCr73SVB.webp', 34, 1, '2026-03-16 06:55:57', '2026-03-16 06:55:57'),
(106, 11, 21, 'Digital Marketing training', 'Digital Marketing training course with Live-Internship programs', 'digital-marketing-training-96gw', 'One of the most highly sought-after training programmes in the history of academic Mantra services. The reason is not that we are the best, but because we understand our role in rankings. Our students, mainly Gen Z and Gen Alpha, seek to go beyond the confines of the classroom and traditional training. We all want to participate in programmes that emphasise live training, focusing on practical applications, even though man', 'English', 'course-thumbnails/wvpF2kCFMTIu1WIvs01iX4OKO5lZvh5yswXNkWbP.png', 14, 2, '2026-03-18 00:15:19', '2026-03-18 00:15:19'),
(157, 11, 21, 'SMM MASTERY', 'Introduction to Social Media:\r\nPlatforms, Audiences & Algorithms', 'smm-mastery-aeoo', 'Introduction to Social Media:\r\nPlatforms, Audiences & Algorithms', 'English', 'course-thumbnails/hQtOY1cxYa25RyiQs6DGG5w0HsCbl8LepxR40zGx.png', 18, 1, '2026-03-23 00:47:19', '2026-03-23 00:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `course_categories`
--

CREATE TABLE `course_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_categories`
--

INSERT INTO `course_categories` (`id`, `name`, `slug`, `description`, `thumbnail`, `parent_id`, `created_at`, `updated_at`) VALUES
(11, 'Professional Courses', 'professional-courses-11', 'Professional courses are designed for students who want to develop industry-level skills. These courses focus on practical applications, advanced techniques, and real-world projects to prepare learners for professional work.', 'category-thumbnails/8vdhEXFmDAzc5jG8xCJvDSmC84lPtokFkhyFYYzp.png', NULL, '2026-03-06 02:29:02', '2026-03-06 06:35:51'),
(15, 'Basic Courses', 'basic-courses-15', 'Basic courses are designed for beginners who are new to the subject. These courses focus on fundamental concepts, simple practical exercises, and step-by-step learning to help students build a strong foundation before moving to advanced topics.', 'category-thumbnails/ddaKeGS05jVF0HGITm3YiorbQ0269b8LMFGKsGD1.jpg', NULL, '2026-03-06 02:33:22', '2026-03-06 06:33:05'),
(16, '6 Hours', '6-hours-16', 'Short introduction course covering essential concepts and quick hands-on practice.', 'category-thumbnails/03SLN4Tu8pbVUPMmynTtvmo1al8Ft5JQMvWpaNYu.jpg', 15, '2026-03-06 05:02:15', '2026-03-06 06:33:49'),
(19, '8 Hours', '8-hours-19', 'Beginner course with more examples, exercises, and guided practice.', 'category-thumbnails/6X16JQfBsTDKBiEm3zxS4Ev0eBatHfrjye3jotdZ.jpg', 15, '2026-03-06 05:36:09', '2026-03-06 06:34:16'),
(20, '10 Hours', '10-hours-20', 'Detailed beginner training including practical tasks and small projects.', 'category-thumbnails/RJuyp4UqOClW9psykAFgUvLXzX85q54zKw0Gbrwj.jpg', 15, '2026-03-06 06:22:57', '2026-03-06 06:34:48'),
(21, 'Beginner Professional Course', 'beginner-professional-course-21', 'Designed for learners starting their professional journey with structured guidance and practical exercises.', NULL, 11, '2026-03-06 06:26:42', '2026-03-06 06:36:17'),
(22, '12 Hours', '12-hours-0vdr', 'Complete beginner foundation course with extended exercises and real-world examples.', NULL, 15, '2026-03-06 06:35:13', '2026-03-06 06:35:13'),
(23, 'Intermediate Professional Course', 'intermediate-professional-course-iam7', 'For learners who already understand the basics and want to improve their practical and technical skills.', NULL, 11, '2026-03-06 06:36:42', '2026-03-06 06:36:42'),
(24, 'Advanced Professional Course', 'advanced-professional-course-wxls', 'High-level training focusing on complex projects, advanced techniques, and industry best practices.', NULL, 11, '2026-03-06 06:37:02', '2026-03-06 06:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `course_days`
--

CREATE TABLE `course_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `day_number` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_day_items`
--

CREATE TABLE `course_day_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_day_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(30) NOT NULL,
  `title` varchar(180) NOT NULL,
  `resource_type` varchar(20) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `resource_url` varchar(500) DEFAULT NULL,
  `cloudinary_public_id` varchar(255) DEFAULT NULL,
  `cloudinary_resource_type` varchar(30) DEFAULT NULL,
  `cloudinary_format` varchar(30) DEFAULT NULL,
  `cloudinary_delivery_type` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`id`, `course_id`, `student_id`, `trainer_id`, `assigned_by`, `created_at`, `updated_at`) VALUES
(123, 54, 6, 5, 1, '2026-03-17 02:35:21', '2026-03-17 02:35:21'),
(124, 106, 6, 5, 2, '2026-03-18 00:51:59', '2026-03-18 00:51:59'),
(126, 157, 40, 28, 1, '2026-03-23 00:47:53', '2026-03-23 00:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `course_item_submissions`
--

CREATE TABLE `course_item_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `course_session_item_id` bigint(20) UNSIGNED NOT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `submission_type` varchar(20) NOT NULL,
  `answer_text` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(120) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_status` varchar(255) NOT NULL DEFAULT 'pending_review',
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_progress`
--

CREATE TABLE `course_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `course_session_item_id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_progress`
--

INSERT INTO `course_progress` (`id`, `course_enrollment_id`, `course_session_item_id`, `completed_at`, `created_at`, `updated_at`) VALUES
(1457, 123, 17, '2026-03-25 02:09:52', '2026-03-17 02:43:29', '2026-03-25 02:09:52'),
(1458, 123, 18, '2026-03-24 22:45:47', '2026-03-17 02:43:29', '2026-03-24 22:45:47'),
(1459, 123, 20, '2026-03-17 07:26:26', '2026-03-17 02:43:29', '2026-03-17 07:26:26'),
(1460, 123, 19, '2026-03-17 07:26:26', '2026-03-17 02:43:29', '2026-03-17 07:26:26'),
(1461, 124, 825, '2026-04-04 02:48:36', '2026-03-18 00:55:04', '2026-04-04 02:48:36'),
(1462, 124, 826, '2026-03-20 02:40:10', '2026-03-18 00:55:04', '2026-03-20 02:40:10'),
(1463, 124, 828, '2026-03-19 02:50:30', '2026-03-18 00:55:04', '2026-03-19 02:50:30'),
(1464, 124, 827, '2026-04-04 02:48:18', '2026-03-18 00:55:04', '2026-04-04 02:48:18'),
(1465, 124, 829, '2026-04-04 02:55:03', '2026-03-18 00:55:04', '2026-04-04 02:55:03'),
(1466, 124, 830, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1467, 124, 832, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1468, 124, 831, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1469, 124, 833, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1470, 124, 834, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1471, 124, 836, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1472, 124, 835, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1473, 124, 837, '2026-03-20 04:01:23', '2026-03-18 00:55:04', '2026-03-20 04:01:23'),
(1474, 124, 838, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1475, 124, 840, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1476, 124, 839, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1477, 124, 841, '2026-03-20 04:01:05', '2026-03-18 00:55:04', '2026-03-20 04:01:05'),
(1478, 124, 842, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1479, 124, 844, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1480, 124, 843, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1481, 124, 845, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1482, 124, 846, '2026-03-18 23:00:42', '2026-03-18 00:55:04', '2026-03-18 23:00:42'),
(1483, 124, 848, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1484, 124, 847, NULL, '2026-03-18 00:55:04', '2026-03-18 00:55:04'),
(1501, 126, 1649, '2026-03-30 00:54:49', '2026-03-23 00:58:57', '2026-03-30 00:54:49'),
(1502, 126, 1650, NULL, '2026-03-23 00:58:57', '2026-03-23 00:58:57'),
(1503, 126, 1652, '2026-03-24 04:58:36', '2026-03-23 00:58:57', '2026-03-24 04:58:36'),
(1504, 126, 1651, '2026-03-24 02:48:56', '2026-03-23 00:58:57', '2026-03-24 02:48:56'),
(1505, 126, 1653, '2026-03-23 02:28:28', '2026-03-23 00:58:57', '2026-03-23 02:28:28'),
(1506, 126, 1654, NULL, '2026-03-23 00:58:57', '2026-03-23 00:58:57'),
(1507, 126, 1656, '2026-03-23 02:29:28', '2026-03-23 00:58:57', '2026-03-23 02:29:28'),
(1508, 126, 1655, '2026-03-23 02:29:07', '2026-03-23 00:58:58', '2026-03-23 02:29:07'),
(1509, 126, 1657, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1510, 126, 1658, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1511, 126, 1660, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1512, 126, 1659, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1517, 126, 1665, '2026-03-30 00:55:14', '2026-03-23 00:58:58', '2026-03-30 00:55:14'),
(1518, 126, 1666, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1519, 126, 1668, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1520, 126, 1667, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1521, 126, 1669, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1522, 126, 1670, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1523, 126, 1672, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1524, 126, 1671, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1525, 126, 1673, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1526, 126, 1674, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1527, 126, 1676, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1528, 126, 1675, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1533, 126, 1681, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1534, 126, 1682, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1535, 126, 1684, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1536, 126, 1683, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1537, 126, 1685, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1538, 126, 1686, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1539, 126, 1688, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1540, 126, 1687, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1541, 126, 1689, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1542, 126, 1690, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1543, 126, 1692, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1544, 126, 1691, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1549, 126, 1697, '2026-03-30 06:51:29', '2026-03-23 00:58:58', '2026-03-30 06:51:29'),
(1550, 126, 1698, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1551, 126, 1700, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1552, 126, 1699, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1553, 126, 1701, '2026-03-23 02:40:50', '2026-03-23 00:58:58', '2026-03-23 02:40:50'),
(1554, 126, 1702, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1555, 126, 1704, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1556, 126, 1703, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1557, 126, 1705, '2026-03-23 02:43:19', '2026-03-23 00:58:58', '2026-03-23 02:43:19'),
(1558, 126, 1706, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1559, 126, 1708, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58'),
(1560, 126, 1707, NULL, '2026-03-23 00:58:58', '2026-03-23 00:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `course_sessions`
--

CREATE TABLE `course_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_week_id` bigint(20) UNSIGNED NOT NULL,
  `session_number` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_sessions`
--

INSERT INTO `course_sessions` (`id`, `course_week_id`, `session_number`, `title`, `created_at`, `updated_at`) VALUES
(5, 4, 1, 'session test1', '2026-03-16 02:47:19', '2026-03-16 02:47:19'),
(206, 105, 1, 'session1', '2026-03-16 23:40:49', '2026-03-16 23:40:49'),
(207, 106, 1, 'Session 1', '2026-03-18 00:16:48', '2026-03-18 00:16:48'),
(208, 106, 2, 'Session 2', '2026-03-18 00:17:00', '2026-03-18 00:17:00'),
(209, 106, 3, 'Session 3', '2026-03-18 00:17:17', '2026-03-18 00:17:28'),
(210, 107, 1, 'Session 1', '2026-03-18 00:17:37', '2026-03-18 00:17:37'),
(211, 107, 2, 'Session 2', '2026-03-18 00:17:52', '2026-03-18 00:17:52'),
(212, 107, 3, 'Session 3', '2026-03-18 00:18:06', '2026-03-18 00:18:06'),
(413, 208, 1, 'Session 1', '2026-03-23 00:51:56', '2026-03-23 00:51:56'),
(414, 208, 2, 'Session 2', '2026-03-23 00:52:10', '2026-03-23 00:52:10'),
(415, 208, 3, 'Session 3', '2026-03-23 00:52:23', '2026-03-23 00:52:23'),
(417, 209, 4, 'Session 4', '2026-03-23 00:52:51', '2026-03-23 01:02:23'),
(418, 209, 5, 'Session 5', '2026-03-23 00:53:06', '2026-03-23 01:02:36'),
(419, 209, 6, 'Session 6', '2026-03-23 00:53:29', '2026-03-23 01:02:48'),
(421, 210, 7, 'Session 7', '2026-03-23 00:53:57', '2026-03-23 01:03:02'),
(422, 210, 8, 'Session 8', '2026-03-23 00:55:01', '2026-03-23 01:03:16'),
(423, 210, 9, 'Session 9', '2026-03-23 00:55:17', '2026-03-23 01:03:38'),
(425, 211, 10, 'Session 10', '2026-03-23 00:56:03', '2026-03-23 01:03:57'),
(426, 211, 11, 'Session 11', '2026-03-23 00:56:17', '2026-03-23 01:04:11'),
(427, 211, 12, 'Session 12', '2026-03-23 00:56:57', '2026-03-23 01:04:26');

-- --------------------------------------------------------

--
-- Table structure for table `course_session_items`
--

CREATE TABLE `course_session_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_session_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(30) NOT NULL,
  `title` varchar(180) NOT NULL,
  `resource_type` varchar(20) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `resource_url` varchar(500) DEFAULT NULL,
  `is_live` tinyint(1) NOT NULL DEFAULT 0,
  `live_at` timestamp NULL DEFAULT NULL,
  `cloudinary_public_id` varchar(255) DEFAULT NULL,
  `cloudinary_resource_type` varchar(30) DEFAULT NULL,
  `cloudinary_format` varchar(30) DEFAULT NULL,
  `cloudinary_delivery_type` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_session_items`
--

INSERT INTO `course_session_items` (`id`, `course_session_id`, `item_type`, `title`, `resource_type`, `content`, `resource_url`, `is_live`, `live_at`, `cloudinary_public_id`, `cloudinary_resource_type`, `cloudinary_format`, `cloudinary_delivery_type`, `created_at`, `updated_at`) VALUES
(17, 5, 'intro', 'Intro PPT / Video', 'video', 'edit', NULL, 0, NULL, 'lms/course-session-items/phpF768_hwmvc2.tmp', 'raw', 'pdf', 'upload', '2026-03-16 02:47:20', '2026-03-16 05:38:51'),
(18, 5, 'main_video', 'Main Video', 'video', 'test', NULL, 0, NULL, 'lms/course-session-items/php902D_zpaxqp', 'video', 'mp4', 'upload', '2026-03-16 02:47:20', '2026-03-17 07:28:09'),
(19, 5, 'task', 'Task', NULL, 'test for java', NULL, 0, NULL, 'lms/course-session-items/php47B3_s7ymva.tmp', 'raw', 'zip', 'upload', '2026-03-16 02:47:20', '2026-03-17 07:24:46'),
(20, 5, 'quiz', 'Quiz', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2026-03-16 02:47:20', '2026-03-16 02:47:20'),
(821, 206, 'intro', 'Intro PPT / Video', 'video_or_ppt', 'test', NULL, 0, NULL, 'lms/course-session-items/php8EF3_kdnnv2.tmp', 'raw', 'pdf', 'upload', '2026-03-16 23:40:49', '2026-03-16 23:41:54'),
(822, 206, 'main_video', 'Main Video', 'video', 'test  video', NULL, 0, NULL, 'lms/course-session-items/php460A_q7lfzn', 'video', 'mp4', 'upload', '2026-03-16 23:40:49', '2026-03-16 23:43:56'),
(823, 206, 'task', 'Task', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2026-03-16 23:40:49', '2026-03-16 23:40:49'),
(824, 206, 'quiz', 'Quiz', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2026-03-16 23:40:49', '2026-03-16 23:40:49'),
(825, 207, 'intro', 'Intro PPT / Video', 'video_or_ppt', 'test', NULL, 0, NULL, 'lms/course-session-items/php171F_ek1yiq.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:16:48', '2026-03-18 00:19:01'),
(826, 207, 'main_video', 'Main Video', 'video', 'test video', NULL, 0, NULL, 'lms/course-session-items/php65C8_xaxl13', 'video', 'mp4', 'upload', '2026-03-18 00:16:48', '2026-03-18 00:20:45'),
(827, 207, 'task', 'Task', NULL, 'tast task', NULL, 0, NULL, 'lms/course-session-items/php6130_gxkoe0.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:16:48', '2026-03-18 00:21:29'),
(828, 207, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php36D_nxed0f.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:16:48', '2026-03-18 00:22:11'),
(829, 208, 'intro', 'Intro PPT / Video', 'ppt', 'test PPT', NULL, 0, NULL, 'lms/course-session-items/phpC537_xzvjtc.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:17:00', '2026-03-18 00:23:03'),
(830, 208, 'main_video', 'Main Video', 'video', 'test video', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:00', '2026-03-18 00:23:41'),
(831, 208, 'task', 'Task', NULL, 'test task', 'https://www.academicmantraservices.com/ai-integrated-digital-marketing-training-courses', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:00', '2026-03-18 00:24:14'),
(832, 208, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', 'https://drive.google.com/file/d/1dWbTCC6ATyIl8U1rBEDGk0Yhlr8mRvQk/view', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:00', '2026-03-18 00:24:59'),
(833, 209, 'intro', 'Intro PPT / Video', 'video', 'test intro', NULL, 0, NULL, 'lms/course-session-items/php8AD0_tg81s7', 'video', 'mp4', 'upload', '2026-03-18 00:17:17', '2026-03-18 00:28:08'),
(834, 209, 'main_video', 'Main Video', 'video', 'test video', NULL, 0, NULL, 'lms/course-session-items/phpC301_h9acut', 'video', 'mp4', 'upload', '2026-03-18 00:17:17', '2026-03-18 00:34:38'),
(835, 209, 'task', 'Task', NULL, 'test task', NULL, 0, NULL, 'lms/course-session-items/php58A_qhqdtq.tmp', 'raw', 'zip', 'upload', '2026-03-18 00:17:17', '2026-03-18 00:35:50'),
(836, 209, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', 'https://www.academicmantraservices.com/ai-integrated-digital-marketing-training-courses', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:17', '2026-03-18 00:37:17'),
(837, 210, 'intro', 'Intro PPT / Video', 'ppt', 'test PPT', NULL, 0, NULL, 'lms/course-session-items/php549C_sa1qzf.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:17:37', '2026-03-18 00:37:50'),
(838, 210, 'main_video', 'Main Video', 'video', 'tesst video', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:37', '2026-03-18 00:48:47'),
(839, 210, 'task', 'Task', NULL, 'test task', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:37', '2026-03-18 00:49:07'),
(840, 210, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:37', '2026-03-18 00:50:51'),
(841, 211, 'intro', 'Intro PPT / Video', 'ppt', 'test PPT', NULL, 0, NULL, 'lms/course-session-items/phpF9AD_kwzca1.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:17:52', '2026-03-18 00:40:51'),
(842, 211, 'main_video', 'Main Video', 'video', 'test video', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:17:52', '2026-03-18 00:48:32'),
(843, 211, 'task', 'Task', NULL, 'test task', NULL, 0, NULL, 'lms/course-session-items/php328B_br6wo2.tmp', 'raw', 'xlsx', 'upload', '2026-03-18 00:17:52', '2026-03-18 00:49:42'),
(844, 211, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/phpF78E_e2eylm.tmp', 'raw', 'pdf', 'upload', '2026-03-18 00:17:52', '2026-03-18 00:51:37'),
(845, 212, 'intro', 'Intro PPT / Video', 'video_or_ppt', 'test PPT', 'https://www.academicmantraservices.com/ai-integrated-digital-marketing-training-courses', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:18:06', '2026-03-18 00:43:27'),
(846, 212, 'main_video', 'Main Video', 'video', 'test video', NULL, 0, NULL, 'lms/course-session-items/php4A9E_tjfpao', 'video', 'mp4', 'upload', '2026-03-18 00:18:06', '2026-03-18 00:47:03'),
(847, 212, 'task', 'Task', NULL, 'test task', NULL, 0, NULL, 'lms/course-session-items/phpFFB_jz2lyq.tmp', 'raw', 'docx', 'upload', '2026-03-18 00:18:06', '2026-03-18 00:50:38'),
(848, 212, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', 'https://www.youtube.com/', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:18:06', '2026-03-18 00:51:06'),
(1649, 413, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php21F6_dvhi4n.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:51:56', '2026-03-23 01:05:20'),
(1650, 413, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:51:56', '2026-03-23 01:38:43'),
(1651, 413, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpF05E_dwpi2d.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:51:56', '2026-03-23 01:07:07'),
(1652, 413, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php75A_fu5tjt.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:51:56', '2026-03-23 01:22:32'),
(1653, 414, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/phpBD92_npfhbz.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:52:10', '2026-03-23 01:08:01'),
(1654, 414, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:52:10', '2026-03-23 01:38:54'),
(1655, 414, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php147D_lamqky.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:10', '2026-03-23 01:08:21'),
(1656, 414, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/phpC1A2_h1ddn8.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:10', '2026-03-23 01:23:18'),
(1657, 415, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php7888_drcrpe.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:52:23', '2026-03-23 01:08:48'),
(1658, 415, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:52:23', '2026-03-23 01:39:05'),
(1659, 415, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpD639_arlalo.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:23', '2026-03-23 01:09:11'),
(1660, 415, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php19F5_dvszc0.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:23', '2026-03-23 01:23:41'),
(1665, 417, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php680A_lxijxq.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:52:51', '2026-03-23 01:09:49'),
(1666, 417, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:52:51', '2026-03-23 01:39:28'),
(1667, 417, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpDF7E_y2e2on.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:51', '2026-03-23 01:10:20'),
(1668, 417, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php47E1_kaqlfd.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:52:51', '2026-03-23 02:30:31'),
(1669, 418, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/phpA4C8_pavxkd.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:53:06', '2026-03-23 02:30:55'),
(1670, 418, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:53:06', '2026-03-23 01:42:57'),
(1671, 418, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php6CEB_sgabzi.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:06', '2026-03-23 02:31:46'),
(1672, 418, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/phpC56C_b52llx.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:06', '2026-03-23 02:32:08'),
(1673, 419, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php3CC0_qimpwo.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:53:29', '2026-03-23 02:32:47'),
(1674, 419, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:53:29', '2026-03-23 01:42:43'),
(1675, 419, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpD6EE_sghi3m.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:29', '2026-03-23 02:33:18'),
(1676, 419, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php84C3_qgyrnl.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:29', '2026-03-23 02:34:04'),
(1681, 421, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/phpFFEF_xpwm64.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:53:57', '2026-03-23 02:34:37'),
(1682, 421, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:53:57', '2026-03-23 01:42:28'),
(1683, 421, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php5CC6_bgvp0p.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:57', '2026-03-23 02:34:58'),
(1684, 421, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/phpD785_mrzlke.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:53:57', '2026-03-23 02:35:29'),
(1685, 422, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php2E41_kxx11h.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:55:01', '2026-03-23 02:35:54'),
(1686, 422, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:55:01', '2026-03-23 01:42:00'),
(1687, 422, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php83F3_atycfk.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:55:01', '2026-03-23 02:36:14'),
(1688, 422, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php1CF_ncekzv.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:55:01', '2026-03-23 02:36:46'),
(1689, 423, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php6627_ukztns.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:55:17', '2026-03-23 02:37:15'),
(1690, 423, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:55:17', '2026-03-23 01:41:47'),
(1691, 423, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpCD20_bayyc8.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:55:18', '2026-03-23 02:37:37'),
(1692, 423, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php305F_tbvntr.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:55:18', '2026-03-23 02:38:03'),
(1697, 425, 'intro', 'Intro PPT / Video', 'video_or_ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php8806_mezimz.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:56:03', '2026-03-23 02:38:27'),
(1698, 425, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:56:03', '2026-03-23 01:41:34'),
(1699, 425, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/phpF76A_ljv5xt.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:03', '2026-03-23 02:38:54'),
(1700, 425, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php5097_clqfem.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:03', '2026-03-23 02:39:17'),
(1701, 426, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/php2117_nohohh.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:56:17', '2026-03-23 02:40:11'),
(1702, 426, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:56:17', '2026-03-23 01:41:05'),
(1703, 426, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php884E_khqdjs.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:17', '2026-03-23 02:40:37'),
(1704, 426, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/php7000_mkjaqc.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:17', '2026-03-23 02:41:37'),
(1705, 427, 'intro', 'Intro PPT / Video', 'ppt', NULL, NULL, 0, NULL, 'lms/course-session-items/phpEF05_wrebb7.tmp', 'raw', 'pptx', 'upload', '2026-03-23 00:56:57', '2026-03-23 02:42:12'),
(1706, 427, 'main_video', 'Main Video', 'video', NULL, 'https://www.youtube.com/watch?v=XGa4onZP66Q', 0, NULL, NULL, NULL, NULL, NULL, '2026-03-23 00:56:57', '2026-03-23 01:40:50'),
(1707, 427, 'task', 'Task', NULL, NULL, NULL, 0, NULL, 'lms/course-session-items/php467D_lnrxnb.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:57', '2026-03-23 02:42:31'),
(1708, 427, 'quiz', 'Quiz', NULL, 'Quiz: Write a short answer based on this session.', NULL, 0, NULL, 'lms/course-session-items/phpAF79_mnkbdy.tmp', 'raw', 'docx', 'upload', '2026-03-23 00:56:57', '2026-03-23 02:42:57');

-- --------------------------------------------------------

--
-- Table structure for table `course_weeks`
--

CREATE TABLE `course_weeks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `week_number` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_weeks`
--

INSERT INTO `course_weeks` (`id`, `course_id`, `week_number`, `title`, `created_at`, `updated_at`) VALUES
(4, 54, 1, 'test week', '2026-03-16 02:47:02', '2026-03-16 02:47:02'),
(105, 55, 1, 'week 1', '2026-03-16 23:40:36', '2026-03-16 23:40:36'),
(106, 106, 1, 'week 1', '2026-03-18 00:15:46', '2026-03-18 00:16:28'),
(107, 106, 2, 'week 2', '2026-03-18 00:16:12', '2026-03-18 00:16:12'),
(208, 157, 1, 'Week 1', '2026-03-23 00:49:46', '2026-03-23 00:49:46'),
(209, 157, 2, 'Week 2', '2026-03-23 00:49:59', '2026-03-23 00:49:59'),
(210, 157, 3, 'Week 3', '2026-03-23 00:50:36', '2026-03-23 00:50:36'),
(211, 157, 4, 'Week 4', '2026-03-23 00:50:49', '2026-03-23 00:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `demo_feature_videos`
--

CREATE TABLE `demo_feature_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(180) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `youtube_url` varchar(500) DEFAULT NULL,
  `youtube_id` varchar(32) DEFAULT NULL,
  `video_ratio` varchar(20) NOT NULL DEFAULT 'landscape',
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `position` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_feature_videos`
--

INSERT INTO `demo_feature_videos` (`id`, `title`, `description`, `file_path`, `file_name`, `file_mime`, `file_size`, `youtube_url`, `youtube_id`, `video_ratio`, `uploaded_by`, `created_at`, `updated_at`, `position`) VALUES
(4, 'Peep into live skills training @ Academic Mantra Services.', 'Have a look at our Internship Day', '', NULL, NULL, NULL, 'https://youtu.be/FuOYucvtIJM', 'FuOYucvtIJM', 'landscape', 1, '2026-03-30 06:12:11', '2026-04-04 03:07:05', 1),
(5, 'Peep into live skills training @Academic Mantra Services', 'Have a look at our Internship Day', '', NULL, NULL, NULL, 'https://youtu.be/2-xE_NJ2iXU', '2-xE_NJ2iXU', 'landscape', 1, '2026-03-30 06:13:16', '2026-04-01 07:20:06', 2);

-- --------------------------------------------------------

--
-- Table structure for table `demo_review_videos`
--

CREATE TABLE `demo_review_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `youtube_url` varchar(500) NOT NULL,
  `youtube_id` varchar(32) NOT NULL,
  `video_ratio` varchar(20) NOT NULL DEFAULT 'landscape',
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_review_videos`
--

INSERT INTO `demo_review_videos` (`id`, `position`, `title`, `description`, `youtube_url`, `youtube_id`, `video_ratio`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(5, 1, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Congratulations Astha for completing your internship Successfully.\r\nAstha is now working as Backend Academic Writer at Academic Mantra Services.', 'https://youtu.be/n0i98MURRBE', 'n0i98MURRBE', 'landscape', 1, '2026-03-30 05:52:21', '2026-04-04 01:17:04'),
(6, 2, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Congratulations, Saurav, for completing your internship successfully.\r\nSaurav is now working as a Digital Marketing Executive at Academic Mantra Services.', 'https://youtu.be/jVzeHpsTH8c', 'jVzeHpsTH8c', 'landscape', 1, '2026-03-30 05:55:03', '2026-03-30 05:55:03'),
(7, 3, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Congratulations, Pihu, for completing your internship successfully.\r\nPihu is now working as an HR Executive at Job Suraksha.', 'https://youtu.be/5dYOb6RcTqI', '5dYOb6RcTqI', 'landscape', 1, '2026-03-30 05:56:31', '2026-03-30 05:56:31'),
(8, 4, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Congratulations, Prabh, for completing your internship successfully.\r\nPrabh is now working as a Digital Marketing Executive at Academic Mantra Services.', 'https://youtu.be/VfFl1bdJBVI', 'VfFl1bdJBVI', 'landscape', 1, '2026-03-30 05:58:43', '2026-03-30 05:58:43'),
(9, 5, 'Student Reviews || Enhance Skills with Academic Mantra Services', 'Shivani has joined our live internship for the past 10 days as a content writer,\r\nYou can also kickstart your career with the Academic Mantra Services Internship Program. Enrol Today!', 'https://youtu.be/eNEALxE24Xs', 'eNEALxE24Xs', 'landscape', 1, '2026-03-30 06:00:14', '2026-03-30 06:00:14'),
(10, 6, 'Student Reviews || Enhance Skills with Academic Mantra Services', 'Congratulations, Urvashi, for completing your HR internship successfully.\r\nUrvashi is now working as an HR Executive at Job Suraksha.', 'https://youtu.be/R-oC2E8f5-s', 'R-oC2E8f5-s', 'landscape', 1, '2026-03-30 06:05:23', '2026-03-30 06:05:23'),
(11, 7, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Congratulations, Shivangi, for completing your internship successfully.\r\nShivangi is now working as a Backend Academic Writer at Academic Mantra Services.', 'https://youtu.be/wn_Os8j7W30', 'wn_Os8j7W30', 'landscape', 1, '2026-03-30 06:06:56', '2026-03-30 06:06:56'),
(12, 8, 'Student Reviews || Success Stories || Enhance Skills with Academic Mantra Services', 'Arshdeep has joined our 3-month live internship as an HR,\r\nYou can also kickstart your career with the Academic Mantra Services Internship Program. Enrol Today!', 'https://youtu.be/jgY_f7pjvjI', 'jgY_f7pjvjI', 'landscape', 1, '2026-03-30 06:09:00', '2026-03-30 06:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `demo_tasks`
--

CREATE TABLE `demo_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_url` varchar(255) DEFAULT NULL,
  `resource_file_path` varchar(255) DEFAULT NULL,
  `resource_file_name` varchar(255) DEFAULT NULL,
  `resource_file_mime` varchar(255) DEFAULT NULL,
  `resource_file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `task_video_path` varchar(255) DEFAULT NULL,
  `task_video_name` varchar(255) DEFAULT NULL,
  `task_video_mime` varchar(255) DEFAULT NULL,
  `task_video_size` bigint(20) UNSIGNED DEFAULT NULL,
  `video_ratio` varchar(20) NOT NULL DEFAULT 'reel',
  `ai_video_url` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_tasks`
--

INSERT INTO `demo_tasks` (`id`, `title`, `description`, `resource_url`, `resource_file_path`, `resource_file_name`, `resource_file_mime`, `resource_file_size`, `task_video_path`, `task_video_name`, `task_video_mime`, `task_video_size`, `video_ratio`, `ai_video_url`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 'Task Instructions', 'Go to video tool and make a video.', NULL, 'demo-task-resources/resource_69ba95be9cb9e0.05867218-AT3..zip', 'AT3..zip', 'application/x-zip-compressed', 1892007, 'demo-task-videos/task_video_69bb977b4a0e72.22525034-cld-sample-video.mp4', 'cld-sample-video.mp4', 'video/mp4', 35988643, 'landscape', 'https://drive.google.com/file/d/1dWbTCC6ATyIl8U1rBEDGk0Yhlr8mRvQk/view', 1, '2026-03-18 06:16:47', '2026-04-04 00:12:12'),
(5, 'placeholder for title', 'placeholder for Description', NULL, NULL, NULL, NULL, NULL, 'demo-task-videos/task_video_69d0cd2957dd75.76436868-by-1-.mp4', 'by (1).mp4', 'video/mp4', 41259094, 'landscape', 'https://app.videoexpress.ai/', 1, '2026-03-30 23:58:46', '2026-04-04 03:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `demo_task_assignments`
--

CREATE TABLE `demo_task_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `demo_task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_task_assignments`
--

INSERT INTO `demo_task_assignments` (`id`, `demo_task_id`, `user_id`, `assigned_by`, `assigned_at`, `created_at`, `updated_at`) VALUES
(7, 5, 80, 1, '2026-04-01 07:44:05', '2026-04-01 07:44:05', '2026-04-01 07:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `demo_task_submissions`
--

CREATE TABLE `demo_task_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `demo_task_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `participant_name` varchar(255) DEFAULT NULL,
  `participant_email` varchar(255) DEFAULT NULL,
  `participant_phone` varchar(40) DEFAULT NULL,
  `video_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_mime` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_task_submissions`
--

INSERT INTO `demo_task_submissions` (`id`, `demo_task_assignment_id`, `participant_name`, `participant_email`, `participant_phone`, `video_rating`, `answer_text`, `file_path`, `file_name`, `file_mime`, `file_size`, `submitted_at`, `created_at`, `updated_at`) VALUES
(7, 7, 'Ranveer', 'ranveer@gmail.com', '6367205305', NULL, 'test', 'demo-task-submissions/7/submission_69cdf84e903ec3.05799595-java-certificate.pdf', 'java-certificate.pdf', 'application/pdf', 457911, '2026-04-01 23:32:06', '2026-04-01 23:32:06', '2026-04-01 23:32:06'),
(8, 7, 'test', 'test@gmail.com', '88809809809', NULL, 'test1', 'demo-task-submissions/7/submission_69cdf9959fa6a0.68253655-MEM23004A-_H_A2-1-1-.docx', 'MEM23004A _H_A2 (1) (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 2476904, '2026-04-01 23:37:33', '2026-04-01 23:37:33', '2026-04-01 23:37:33'),
(9, 7, 'test2', 'test2@gmail.com', '809890880809', NULL, 'test2', NULL, NULL, NULL, NULL, '2026-04-01 23:48:15', '2026-04-01 23:48:15', '2026-04-01 23:48:15'),
(10, 7, 'test3', 'test2a@gmail.com', '989890809809', NULL, 'test3', NULL, NULL, NULL, NULL, '2026-04-01 23:56:26', '2026-04-01 23:56:26', '2026-04-01 23:56:26'),
(11, 7, 'test', 'test@gmail.com', '7698798', NULL, 'test', NULL, NULL, NULL, NULL, '2026-04-02 00:00:31', '2026-04-02 00:00:31', '2026-04-02 00:00:31'),
(12, 7, 'w', 'y@gmail.com', '7780798009', NULL, 'test', NULL, NULL, NULL, NULL, '2026-04-02 00:04:33', '2026-04-02 00:04:33', '2026-04-02 00:04:33'),
(13, 7, 'sa', 'vivekacademic.0412@gmail.com', '88809809809', NULL, 'test', NULL, NULL, NULL, NULL, '2026-04-02 00:06:46', '2026-04-02 00:06:46', '2026-04-02 00:06:46'),
(14, 7, 'task3', 'task3@gmail.com', '80980808', NULL, 'test', NULL, NULL, NULL, NULL, '2026-04-02 00:15:36', '2026-04-02 00:15:36', '2026-04-02 00:15:36'),
(15, 7, 'test', 'vivekacademic.0412@gmail.com', '88809809809', NULL, 'test', NULL, NULL, NULL, NULL, '2026-04-02 00:32:32', '2026-04-02 00:32:32', '2026-04-02 00:32:32'),
(16, 7, 'Ranveer', 'vivekacademic.0412@gmail.com', '8987708709879', 4, 'test', NULL, NULL, NULL, NULL, '2026-04-02 01:17:55', '2026-04-02 01:17:55', '2026-04-02 01:17:55'),
(17, 7, 'test', 'vivekacademic.0412@gmail.com', '6367205305', 3, 'test', NULL, NULL, NULL, NULL, '2026-04-02 01:25:49', '2026-04-02 01:25:49', '2026-04-02 01:25:49'),
(18, 7, 'Ranveer', 'vivekacademic.0412@gmail.com', '6367205305', 5, NULL, 'demo-task-submissions/7/submission_69ce1ea0dde606.34544453-java-certificate-2-.pdf', 'java-certificate (2).pdf', 'application/pdf', 457911, '2026-04-02 02:15:36', '2026-04-02 02:15:37', '2026-04-02 02:15:37');

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
(4, '2026_03_05_000100_add_is_active_to_users_table', 1),
(5, '2026_03_05_000200_create_course_categories_table', 1),
(6, '2026_03_05_000300_create_courses_table', 1),
(7, '2026_03_05_000400_create_course_days_table', 1),
(8, '2026_03_05_000500_create_course_day_items_table', 1),
(9, '2026_03_05_000600_create_course_enrollments_table', 1),
(10, '2026_03_05_000700_create_course_progress_table', 1),
(11, '2026_03_06_001000_add_thumbnail_to_course_categories_table', 2),
(12, '2026_03_06_001100_add_parent_id_to_course_categories_table', 2),
(13, '2026_03_06_002000_add_course_metadata_fields_to_courses_table', 3),
(14, '2026_03_06_003000_add_cloudinary_fields_to_course_day_items_table', 4),
(15, '2026_03_16_000100_create_course_weeks_table', 5),
(16, '2026_03_16_000200_create_course_sessions_table', 5),
(17, '2026_03_16_000300_create_course_session_items_table', 5),
(18, '2026_03_16_000400_update_course_progress_table', 6),
(19, '2026_03_16_001200_add_avatar_to_users_table', 7),
(20, '2026_03_18_000100_create_demo_tasks_table', 8),
(21, '2026_03_18_000200_create_demo_task_assignments_table', 9),
(22, '2026_03_18_000300_create_demo_task_submissions_table', 10),
(23, '2026_03_18_000500_create_demo_feature_videos_table', 11),
(24, '2026_03_18_000400_add_ai_video_url_to_demo_tasks_table', 12),
(25, '2026_03_18_000600_add_resource_file_fields_to_demo_tasks_table', 12),
(26, '2026_03_19_000100_add_position_to_demo_feature_videos_table', 13),
(27, '2026_03_19_000200_add_task_video_fields_to_demo_tasks_table', 14),
(28, '2026_03_19_000300_create_demo_review_videos_table', 15),
(29, '2026_03_17_000100_create_course_item_submissions_table', 16),
(30, '2026_03_17_000200_add_live_fields_to_course_session_items_table', 16),
(31, '2026_03_17_000300_create_notifications_table', 16),
(32, '2026_03_25_000100_add_review_status_to_course_item_submissions_table', 16),
(33, '2026_03_25_010000_create_activity_logs_table', 17),
(34, '2026_03_30_000100_add_youtube_fields_to_demo_feature_videos_table', 18),
(35, '2026_04_02_000100_add_participant_fields_to_demo_task_submissions_table', 19),
(36, '2026_04_02_000200_add_video_rating_to_demo_task_submissions_table', 20),
(37, '2026_04_04_000100_add_video_ratio_to_demo_media_tables', 21);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('04a82a33-7153-40fc-a825-1bf7042552bc', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 56, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('14604fbd-1485-4870-8b19-b089eff7c90a', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 40, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', '2026-03-25 02:29:43', '2026-03-25 01:43:20', '2026-03-25 02:29:43'),
('20f44678-609a-4d7a-b417-63c5bc5c8823', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 59, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('289f753b-c59b-4a7b-a261-993394caa6af', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 66, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('29061fad-c0b6-46eb-8aea-d5c5f294e975', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 77, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('2af53f5d-330d-455d-a582-a7ccae2bb925', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 69, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('2b91d351-e2d4-4104-b293-1a7963432390', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$a9e', 'App\\Models\\User', 6, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"course_students\",\"course_id\":54,\"broadcasted_at\":\"2026-03-25 07:14:43\"}', '2026-03-25 02:06:56', '2026-03-25 01:44:43', '2026-03-25 02:06:56'),
('2c01e406-6f16-4495-96a3-2d673fcab366', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 41, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('2e7b5970-28ea-4385-96e3-c338ea8ce7e2', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 67, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('351b3a1c-278d-49a0-9d8d-c2b32128b73b', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 63, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('37f57bb6-2bb8-433c-aac6-f47961eee366', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 48, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('3cf4da07-5b92-4b4c-bc6b-34e7dee97084', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 61, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('3da06c6b-405b-4832-9f40-25743a7b49e6', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 57, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('41ebb71c-e7bc-46a8-98dc-abb263c514ac', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 58, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('4d619f3f-a736-48df-8492-50ef6d487355', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 44, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('55ab0699-27cc-4c6f-8f3e-92848cb278e2', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 72, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('608e8c0b-7447-4778-a858-e01a2b1e09aa', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 74, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('617fd355-21ab-4d84-af32-be369172582b', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 52, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('6199da0c-68a2-44a6-80c0-eea1ef1ea991', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 6, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', '2026-03-25 02:06:56', '2026-03-25 01:43:20', '2026-03-25 02:06:56'),
('65e6adf3-c544-4a73-a1ff-f35f0316296d', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\CourseEnrollmentController.php:435$3e4f', 'App\\Models\\User', 6, '{\"title\":\"New course assigned\",\"message\":\"SMM MASTERY has been assigned to your account. Trainer: Not assigned yet.\",\"sender_name\":\"Super Admin\",\"audience\":\"course_assignment\",\"notification_kind\":\"course_assignment\",\"course_id\":157,\"enrollment_id\":127,\"course_title\":\"SMM MASTERY\",\"trainer_name\":\"Not assigned yet\",\"action_label\":\"Open My Courses\",\"action_route\":\"http:\\/\\/127.0.0.1:8000\\/my-courses\",\"assigned_at\":\"2026-03-25 08:24:41\",\"is_updated\":false,\"is_reminder\":false}', NULL, '2026-03-25 02:54:51', '2026-03-25 02:54:51'),
('6f6591f6-fd95-4ea4-9288-a2c8e6be952a', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 42, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('724ea404-8575-4cd8-b23a-04501e616410', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 79, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('a0118a5e-5351-4f35-aa51-b85850234265', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 50, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('a03e42a7-bb00-47a4-9d5e-ceeadc9cd95d', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 51, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('ac3af14e-d69d-4899-b842-06596cf02f6b', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 53, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('adfac397-3de7-4b48-955d-d14221ff0b11', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 64, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('b01a05fc-0e4a-4d28-9781-a8898adacbbd', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 60, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('b40270e0-dfa5-45b7-b499-59c7cd5a4636', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 78, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('bb70b5d8-28b6-4ccb-95a4-66209c9a59a7', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 55, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('bd42867a-23f1-4aaf-9ae1-f9a89028505c', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 73, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('c310f304-31d5-4bab-862a-e97e2b642416', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 71, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('c6145353-afad-4808-a1d9-adca130ff4d9', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 88, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20'),
('c98ce62c-662f-489a-8189-14d8e83f389b', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 43, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('cc370726-486a-4727-a6e6-73e681c1d89e', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 46, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('cf472a65-ba97-4692-add6-f0005c60def2', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 65, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('d68764bf-a34c-4b72-8603-e5b3cd2370b6', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 70, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('e334ef56-c6fd-4b50-a950-5a78a770b6d8', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 47, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('e376b6e3-370a-4626-8a07-e3973e2b9bf4', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 76, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('e55baf56-85fd-48a1-95e6-77d344fb2aec', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 45, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('eb36db65-29ea-49e3-b1b4-1b329ae523fe', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 62, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('f032efc1-ff70-4f67-84db-6c74ebae25da', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 75, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:21\"}', NULL, '2026-03-25 01:43:21', '2026-03-25 01:43:21'),
('f263922b-0ff2-4ad0-877a-50a59368f8b8', 'Illuminate\\Notifications\\Notification@anonymous\0C:\\xampp\\htdocs\\LmsAm\\app\\Http\\Controllers\\BroadcastNotificationController.php:68$49b', 'App\\Models\\User', 49, '{\"title\":\"test\",\"message\":\"test\",\"sender_name\":\"Super Admin\",\"audience\":\"students\",\"course_id\":157,\"broadcasted_at\":\"2026-03-25 07:13:20\"}', NULL, '2026-03-25 01:43:20', '2026-03-25 01:43:20');

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
('5qf4Xmbruw9RBGTTmDj43KnsGoG8rBKipdta2iCQ', NULL, '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicmJZN2pjbGNpa1VTTTBFM3hrMTNFOWJ2YmtNSGpsSXZhd2NFNHNZRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1775290237),
('7U3IhZprfDXnC382W4ahEoxCDPH1yBloEM4nc8Ig', NULL, '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHQ1MUE4d1U2M3paalp2bzMzcFJkMnc4YllqYkxCSEhLZ25mMEl6MSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xOTIuMTY4LjEuMTU6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1775290432),
('blP1jcYSYsBnIwnh420429hMJSrjqCse1NnvANuV', 1, '192.168.1.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTTBkdGtBMGlJa2o1WUVuc3lpQ29uR3NBVlZFN2FOc2FCa0JOdExWaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xOTIuMTY4LjEuMzo4MDAwL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775449964),
('lRmRDwgp5oYwgHAg8YAdMsinIlvIBWJPiCCGDbA4', 80, '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicnVoMnd1THNmY2F3VEE0TDNFOVczdmtCQzdrRGJyUjgwQnpMb3RRdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xOTIuMTY4LjEuMTU6ODAwMC9kZW1vLXRhc2tzLzUvdmlkZW8iO3M6NToicm91dGUiO3M6MTY6ImRlbW8tdGFza3MudmlkZW8iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4MDt9', 1775291815),
('QG281ziIBYfwBpmfG09kspSCYh6vxfqCC6XQHKul', 80, '192.168.1.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibEh0VDlTTm1JNTRIVnpRSEdtdVFrWThJVWh0VjBWaEhrZHlGenhiVyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vMTkyLjE2OC4xLjM6ODAwMC9kZW1vLXRhc2tzLzUvdmlkZW8iO3M6NToicm91dGUiO3M6MTY6ImRlbW8tdGFza3MudmlkZW8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4MDt9', 1775449925),
('Qg6cnGXwx5kVJS9vJA7nGwi6fIaUCWET8vpQmsex', 1, '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNEdiRmVPOU51N3NoRFQwM3NndWtkTlQxUDYwNjIzYlFnQ200dEc1aiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xOTIuMTY4LjEuMTU6ODAwMC9kZW1vLWZlYXR1cmUtdmlkZW8iO3M6NToicm91dGUiO3M6MjQ6ImRlbW8tZmVhdHVyZS12aWRlby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1775291826);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@lms.test', 'avatars/rhp8sJnVxqqSw34u3ycOIRZ5uuKFJJXbLy8M8UUq.png', 'superadmin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', '3bLdK5gIIVnyYuD4QJoQYPE1iHjE3Su9xLzvFO57xI5YAqBXz61oRBLyxRGB', '2026-03-05 07:54:30', '2026-03-30 06:46:21'),
(2, 'Admin', 'admin@lms.test', 'avatars/seed-2.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(3, 'Manager HR', 'manager.hr@lms.test', 'avatars/seed-3.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(4, 'IT', 'it@lms.test', 'avatars/seed-4.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(5, 'Trainer', 'trainer@lms.test', 'avatars/seed-1.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(6, 'Student', 'student@lms.test', 'avatars/TLSAOLezsSoBMxOwssWajae9f8tjwM2UfEQ6fHnC.png', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(7, 'Admin 1', 'admin1@lms.test', 'avatars/seed-3.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(8, 'Admin 2', 'admin2@lms.test', 'avatars/seed-4.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(9, 'Admin 3', 'admin3@lms.test', 'avatars/seed-1.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(10, 'Admin 4', 'admin4@lms.test', 'avatars/seed-2.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(11, 'Admin 5', 'admin5@lms.test', 'avatars/seed-3.svg', 'admin', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(12, 'Manager Hr 1', 'manager_hr1@lms.test', 'avatars/seed-4.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(13, 'Manager Hr 2', 'manager_hr2@lms.test', 'avatars/seed-1.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(14, 'Manager Hr 3', 'manager_hr3@lms.test', 'avatars/seed-2.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(15, 'Manager Hr 4', 'manager_hr4@lms.test', 'avatars/seed-3.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(16, 'Manager Hr 5', 'manager_hr5@lms.test', 'avatars/seed-4.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(17, 'Manager Hr 6', 'manager_hr6@lms.test', 'avatars/seed-1.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(18, 'Manager Hr 7', 'manager_hr7@lms.test', 'avatars/seed-2.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(19, 'Manager Hr 8', 'manager_hr8@lms.test', 'avatars/seed-3.svg', 'manager_hr', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(20, 'It 1', 'it1@lms.test', 'avatars/seed-4.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(21, 'It 2', 'it2@lms.test', 'avatars/seed-1.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(22, 'It 3', 'it3@lms.test', 'avatars/seed-2.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(23, 'It 4', 'it4@lms.test', 'avatars/seed-3.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(24, 'It 5', 'it5@lms.test', 'avatars/seed-4.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(25, 'It 6', 'it6@lms.test', 'avatars/seed-1.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(26, 'It 7', 'it7@lms.test', 'avatars/seed-2.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(27, 'It 8', 'it8@lms.test', 'avatars/seed-3.svg', 'it', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(28, 'Trainer 1', 'trainer1@lms.test', 'avatars/seed-1.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(29, 'Trainer 2', 'trainer2@lms.test', 'avatars/seed-2.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(30, 'Trainer 3', 'trainer3@lms.test', 'avatars/seed-3.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(31, 'Trainer 4', 'trainer4@lms.test', 'avatars/seed-4.svg', 'trainer', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(32, 'Trainer 5', 'trainer5@lms.test', 'avatars/seed-1.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(33, 'Trainer 6', 'trainer6@lms.test', 'avatars/seed-2.svg', 'trainer', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(34, 'Trainer 7', 'trainer7@lms.test', 'avatars/seed-3.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(35, 'Trainer 8', 'trainer8@lms.test', 'avatars/seed-4.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(36, 'Trainer 9', 'trainer9@lms.test', 'avatars/seed-1.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(37, 'Trainer 10', 'trainer10@lms.test', 'avatars/seed-2.svg', 'trainer', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(38, 'Trainer 11', 'trainer11@lms.test', 'avatars/seed-3.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(39, 'Trainer 12', 'trainer12@lms.test', 'avatars/seed-4.svg', 'trainer', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(40, 'Student 1', 'student1@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(41, 'Student 2', 'student2@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(42, 'Student 3', 'student3@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(43, 'Student 4', 'student4@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(44, 'Student 5', 'student5@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(45, 'Student 6', 'student6@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(46, 'Student 7', 'student7@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:30', '2026-03-18 04:44:36'),
(47, 'Student 8', 'student8@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(48, 'Student 9', 'student9@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(49, 'Student 10', 'student10@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(50, 'Student 11', 'student11@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(51, 'Student 12', 'student12@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(52, 'Student 13', 'student13@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(53, 'Student 14', 'student14@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(54, 'Student 15', 'student15@lms.test', 'avatars/seed-3.svg', 'student', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(55, 'Student 16', 'student16@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(56, 'Student 17', 'student17@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(57, 'Student 18', 'student18@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(58, 'Student 19', 'student19@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(59, 'Student 20', 'student20@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(60, 'Student 21', 'student21@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(61, 'Student 22', 'student22@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(62, 'Student 23', 'student23@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(63, 'Student 24', 'student24@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(64, 'Student 25', 'student25@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(65, 'Student 26', 'student26@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(66, 'Student 27', 'student27@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(67, 'Student 28', 'student28@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(68, 'Student 29', 'student29@lms.test', 'avatars/seed-1.svg', 'student', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(69, 'Student 30', 'student30@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(70, 'Student 31', 'student31@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(71, 'Student 32', 'student32@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(72, 'Student 33', 'student33@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(73, 'Student 34', 'student34@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(74, 'Student 35', 'student35@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(75, 'Student 36', 'student36@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(76, 'Student 37', 'student37@lms.test', 'avatars/seed-1.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(77, 'Student 38', 'student38@lms.test', 'avatars/seed-2.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(78, 'Student 39', 'student39@lms.test', 'avatars/seed-3.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(79, 'Student 40', 'student40@lms.test', 'avatars/seed-4.svg', 'student', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-05 07:54:31', '2026-03-18 04:44:36'),
(80, 'Demo', 'demo@gmail.com', 'avatars/73TDrF5qyniKTXQGpLG9MYd9XEzB4ujiEJaWj88s.png', 'demo', 1, NULL, '$2y$12$7oIJ8K5WqPoSSr.uUeMez.0GozvdA1fju.3uXO0/Le6cHlDXjken2', NULL, '2026-03-18 04:38:13', '2026-03-30 06:47:31'),
(81, 'Demo User', 'demo@lms.test', 'avatars/seed-3.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(82, 'Demo 1', 'demo1@lms.test', 'avatars/seed-2.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(83, 'Demo 2', 'demo2@lms.test', 'avatars/seed-3.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(84, 'Demo 3', 'demo3@lms.test', 'avatars/seed-4.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(85, 'Demo 4', 'demo4@lms.test', 'avatars/seed-1.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(86, 'Demo 5', 'demo5@lms.test', 'avatars/seed-2.svg', 'demo', 1, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(87, 'Demo 6', 'demo6@lms.test', 'avatars/seed-3.svg', 'demo', 0, NULL, '$2y$12$Yv7yIx7IakqDNOcgb9IIIuYsS3o4eW.VuLq7Pn5nbhcSR7jSV5dOi', NULL, '2026-03-18 04:44:36', '2026-03-18 04:44:36'),
(88, 'RANVEER', 'vivekacademic.0412@gmail.com', NULL, 'student', 1, NULL, '$2y$12$kXjj7kE4TZnPjOSDAKJVP.pkQDK6GD46ADbhXBHkTMbz2/Is9R4Wm', NULL, '2026-03-23 07:06:46', '2026-03-23 07:06:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_module_created_at_index` (`module`,`created_at`),
  ADD KEY `activity_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `activity_logs_module_index` (`module`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_route_name_index` (`route_name`);

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
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_category_id_title_unique` (`category_id`,`title`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_created_by_foreign` (`created_by`),
  ADD KEY `courses_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `course_categories`
--
ALTER TABLE `course_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_categories_name_unique` (`name`),
  ADD UNIQUE KEY `course_categories_slug_unique` (`slug`),
  ADD KEY `course_categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `course_days`
--
ALTER TABLE `course_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_days_course_id_day_number_unique` (`course_id`,`day_number`);

--
-- Indexes for table `course_day_items`
--
ALTER TABLE `course_day_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_day_items_course_day_id_item_type_unique` (`course_day_id`,`item_type`);

--
-- Indexes for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_enrollments_course_id_student_id_unique` (`course_id`,`student_id`),
  ADD KEY `course_enrollments_student_id_foreign` (`student_id`),
  ADD KEY `course_enrollments_trainer_id_foreign` (`trainer_id`),
  ADD KEY `course_enrollments_assigned_by_foreign` (`assigned_by`);

--
-- Indexes for table `course_item_submissions`
--
ALTER TABLE `course_item_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_item_submissions_course_enrollment_id_foreign` (`course_enrollment_id`),
  ADD KEY `course_item_submissions_course_session_item_id_foreign` (`course_session_item_id`),
  ADD KEY `course_item_submissions_submitted_by_foreign` (`submitted_by`),
  ADD KEY `course_item_submissions_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `course_item_submissions_review_status_index` (`review_status`);

--
-- Indexes for table `course_progress`
--
ALTER TABLE `course_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment_item_progress` (`course_enrollment_id`,`course_session_item_id`),
  ADD KEY `course_progress_course_session_item_id_foreign` (`course_session_item_id`);

--
-- Indexes for table `course_sessions`
--
ALTER TABLE `course_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_sessions_course_week_id_session_number_unique` (`course_week_id`,`session_number`);

--
-- Indexes for table `course_session_items`
--
ALTER TABLE `course_session_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_session_items_course_session_id_item_type_unique` (`course_session_id`,`item_type`);

--
-- Indexes for table `course_weeks`
--
ALTER TABLE `course_weeks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_weeks_course_id_week_number_unique` (`course_id`,`week_number`);

--
-- Indexes for table `demo_feature_videos`
--
ALTER TABLE `demo_feature_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demo_feature_videos_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `demo_feature_videos_position_index` (`position`);

--
-- Indexes for table `demo_review_videos`
--
ALTER TABLE `demo_review_videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `demo_review_videos_position_unique` (`position`),
  ADD KEY `demo_review_videos_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `demo_tasks`
--
ALTER TABLE `demo_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demo_tasks_created_by_foreign` (`created_by`);

--
-- Indexes for table `demo_task_assignments`
--
ALTER TABLE `demo_task_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demo_task_assignments_demo_task_id_foreign` (`demo_task_id`),
  ADD KEY `demo_task_assignments_user_id_foreign` (`user_id`),
  ADD KEY `demo_task_assignments_assigned_by_foreign` (`assigned_by`);

--
-- Indexes for table `demo_task_submissions`
--
ALTER TABLE `demo_task_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demo_task_submissions_demo_task_assignment_id_foreign` (`demo_task_assignment_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `course_categories`
--
ALTER TABLE `course_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `course_days`
--
ALTER TABLE `course_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `course_day_items`
--
ALTER TABLE `course_day_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=413;

--
-- AUTO_INCREMENT for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `course_item_submissions`
--
ALTER TABLE `course_item_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_progress`
--
ALTER TABLE `course_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1565;

--
-- AUTO_INCREMENT for table `course_sessions`
--
ALTER TABLE `course_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=429;

--
-- AUTO_INCREMENT for table `course_session_items`
--
ALTER TABLE `course_session_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1713;

--
-- AUTO_INCREMENT for table `course_weeks`
--
ALTER TABLE `course_weeks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `demo_feature_videos`
--
ALTER TABLE `demo_feature_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `demo_review_videos`
--
ALTER TABLE `demo_review_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `demo_tasks`
--
ALTER TABLE `demo_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `demo_task_assignments`
--
ALTER TABLE `demo_task_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `demo_task_submissions`
--
ALTER TABLE `demo_task_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `course_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `course_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_categories`
--
ALTER TABLE `course_categories`
  ADD CONSTRAINT `course_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `course_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_days`
--
ALTER TABLE `course_days`
  ADD CONSTRAINT `course_days_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_day_items`
--
ALTER TABLE `course_day_items`
  ADD CONSTRAINT `course_day_items_course_day_id_foreign` FOREIGN KEY (`course_day_id`) REFERENCES `course_days` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD CONSTRAINT `course_enrollments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollments_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_item_submissions`
--
ALTER TABLE `course_item_submissions`
  ADD CONSTRAINT `course_item_submissions_course_enrollment_id_foreign` FOREIGN KEY (`course_enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_item_submissions_course_session_item_id_foreign` FOREIGN KEY (`course_session_item_id`) REFERENCES `course_session_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_item_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `course_item_submissions_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_progress`
--
ALTER TABLE `course_progress`
  ADD CONSTRAINT `course_progress_course_enrollment_id_foreign` FOREIGN KEY (`course_enrollment_id`) REFERENCES `course_enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_progress_course_session_item_id_foreign` FOREIGN KEY (`course_session_item_id`) REFERENCES `course_session_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_sessions`
--
ALTER TABLE `course_sessions`
  ADD CONSTRAINT `course_sessions_course_week_id_foreign` FOREIGN KEY (`course_week_id`) REFERENCES `course_weeks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_session_items`
--
ALTER TABLE `course_session_items`
  ADD CONSTRAINT `course_session_items_course_session_id_foreign` FOREIGN KEY (`course_session_id`) REFERENCES `course_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_weeks`
--
ALTER TABLE `course_weeks`
  ADD CONSTRAINT `course_weeks_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demo_feature_videos`
--
ALTER TABLE `demo_feature_videos`
  ADD CONSTRAINT `demo_feature_videos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demo_review_videos`
--
ALTER TABLE `demo_review_videos`
  ADD CONSTRAINT `demo_review_videos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `demo_tasks`
--
ALTER TABLE `demo_tasks`
  ADD CONSTRAINT `demo_tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demo_task_assignments`
--
ALTER TABLE `demo_task_assignments`
  ADD CONSTRAINT `demo_task_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demo_task_assignments_demo_task_id_foreign` FOREIGN KEY (`demo_task_id`) REFERENCES `demo_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demo_task_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demo_task_submissions`
--
ALTER TABLE `demo_task_submissions`
  ADD CONSTRAINT `demo_task_submissions_demo_task_assignment_id_foreign` FOREIGN KEY (`demo_task_assignment_id`) REFERENCES `demo_task_assignments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
