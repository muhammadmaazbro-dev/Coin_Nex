-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 08:17 PM
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
-- Database: `coin_nex`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--
-- CREATE DATABASE IF NOT EXISTS coin_nex;

-- use coin_nex;
CREATE TABLE `activity_logs` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action_type` enum('view','create','edit','delete') NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action_type`, `entity_type`, `entity_id`, `description`, `created_at`) VALUES
(1, 17, 'view', 'report', NULL, 'Viewed September 2026 Monthly Report', '2026-09-24 15:43:25'),
(2, 17, 'view', 'budget', NULL, 'Inspected Category Budgets for Food & Transport', '2026-09-24 15:28:25'),
(3, 17, 'create', 'transaction', NULL, 'Logged Expense: Campus Cafe lunch (Rs. 500)', '2026-09-24 14:53:25'),
(4, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:01:01'),
(5, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:01:46'),
(6, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:02:47'),
(7, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:08:25'),
(8, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:19:32'),
(9, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:21:16'),
(10, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:21:16'),
(11, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:21:16'),
(12, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:21:16'),
(13, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:22:02'),
(14, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:23:22'),
(15, 18, 'create', 'transaction', 68, 'Logged Income: family (Rs. 10,000)', '2026-09-24 16:24:19'),
(16, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:24:24'),
(17, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:24:30'),
(18, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:24:40'),
(19, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:24:44'),
(20, 18, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:25:00'),
(21, 18, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:25:03'),
(22, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:25:11'),
(23, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:26:53'),
(24, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:26:54'),
(25, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:31:03'),
(26, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:31:03'),
(27, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:31:20'),
(28, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:31:42'),
(29, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:32:02'),
(30, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:33:06'),
(31, 17, 'create', 'transaction', 69, 'Logged Expense: Test Coffee Cup (Rs. 350)', '2026-09-24 16:33:06'),
(32, 17, 'edit', 'transaction', 69, 'Edited Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-24 16:33:06'),
(33, 17, 'delete', 'transaction', 69, 'Deleted Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-24 16:33:06'),
(34, 17, 'create', 'csv_import', NULL, 'Imported 1 transactions from CSV', '2026-09-24 16:33:06'),
(35, 17, 'delete', 'transaction', 70, 'Deleted Expense: Library Photocopy (Rs. 150)', '2026-09-24 16:33:06'),
(36, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:33:06'),
(37, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:33:24'),
(38, 17, 'create', 'transaction', 71, 'Logged Expense: Test Coffee Cup (Rs. 350)', '2026-09-24 16:33:24'),
(39, 17, 'edit', 'transaction', 71, 'Edited Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-24 16:33:24'),
(40, 17, 'delete', 'transaction', 71, 'Deleted Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-24 16:33:24'),
(41, 17, 'create', 'csv_import', NULL, 'Imported 1 transactions from CSV', '2026-09-24 16:33:24'),
(42, 17, 'delete', 'transaction', 72, 'Deleted Expense: Library Photocopy (Rs. 150)', '2026-09-24 16:33:24'),
(43, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:33:25'),
(44, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:35:01'),
(45, 18, 'create', 'transaction', 73, 'Logged Income: family (Rs. 10,000)', '2026-09-24 16:35:39'),
(46, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:36:09'),
(47, 18, 'create', 'transaction', 74, 'Logged Expense: burger (Rs. 150)', '2026-09-24 16:36:33'),
(48, 18, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:36:47'),
(49, 18, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-24 16:37:01'),
(50, 18, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:37:11'),
(51, 1, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-24 16:38:33'),
(52, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:30:11'),
(53, 17, 'create', 'transaction', 75, 'Logged Expense: Test Coffee Cup (Rs. 350)', '2026-09-25 01:30:11'),
(54, 17, 'edit', 'transaction', 75, 'Edited Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-25 01:30:11'),
(55, 17, 'delete', 'transaction', 75, 'Deleted Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-25 01:30:11'),
(56, 17, 'create', 'csv_import', NULL, 'Imported 1 transactions from CSV', '2026-09-25 01:30:11'),
(57, 17, 'delete', 'transaction', 76, 'Deleted Expense: Library Photocopy (Rs. 150)', '2026-09-25 01:30:11'),
(58, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 01:30:12'),
(59, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:32:34'),
(60, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:32:50'),
(61, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:34:40'),
(62, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:40:01'),
(63, 17, 'create', 'transaction', 77, 'Logged Expense: Test Coffee Cup (Rs. 350)', '2026-09-25 01:40:02'),
(64, 17, 'edit', 'transaction', 77, 'Edited Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-25 01:40:02'),
(65, 17, 'delete', 'transaction', 77, 'Deleted Expense: Test Coffee Cup Updated (Rs. 400)', '2026-09-25 01:40:02'),
(66, 17, 'create', 'csv_import', NULL, 'Imported 1 transactions from CSV', '2026-09-25 01:40:02'),
(67, 17, 'delete', 'transaction', 78, 'Deleted Expense: Library Photocopy (Rs. 150)', '2026-09-25 01:40:02'),
(68, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 01:40:02'),
(69, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:46:09'),
(70, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:46:28'),
(71, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:46:30'),
(72, 17, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 01:46:57'),
(73, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:47:45'),
(74, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 01:49:39'),
(75, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:33:38'),
(76, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:34:06'),
(77, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:35:09'),
(78, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:35:23'),
(79, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:39:35'),
(80, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:52:17'),
(81, 19, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:57:19'),
(82, 19, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:57:30'),
(83, 19, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 02:59:45'),
(84, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 03:02:24'),
(85, 17, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 03:14:57'),
(86, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:26:10'),
(87, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:26:19'),
(88, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:26:47'),
(89, 20, 'create', 'transaction', 79, 'Logged Income: sell bike (Rs. 60,000)', '2026-09-25 08:30:09'),
(90, 20, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 08:30:22'),
(91, 20, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 08:35:00'),
(92, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:35:02'),
(93, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:35:06'),
(94, 20, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 08:35:10'),
(95, 21, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 09:07:10'),
(96, 21, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 09:07:23'),
(97, 21, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 09:07:37'),
(98, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 09:58:27'),
(99, 22, 'create', 'transaction', 80, 'Logged Income: family (Rs. 50,000)', '2026-09-25 09:59:10'),
(100, 22, 'create', 'transaction', 81, 'Logged Expense: icecream (Rs. 250)', '2026-09-25 09:59:43'),
(101, 22, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 09:59:52'),
(102, 22, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 10:00:28'),
(103, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:01:38'),
(104, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:01:42'),
(105, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:01:47'),
(106, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:01:50'),
(107, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:02:33'),
(108, 22, 'create', 'transaction', 82, 'Logged Expense: bike (Rs. 300)', '2026-09-25 10:03:46'),
(109, 22, 'create', 'transaction', 83, 'Logged Expense: bike (Rs. 1,000)', '2026-09-25 10:04:37'),
(110, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:04:48'),
(111, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:05:13'),
(112, 22, 'create', 'transaction', 84, 'Logged Expense: bat (Rs. 5,000)', '2026-09-25 10:06:46'),
(113, 22, 'create', 'transaction', 85, 'Logged Expense: football (Rs. 9,000)', '2026-09-25 10:07:21'),
(114, 22, 'create', 'transaction', 86, 'Logged Expense: car cover (Rs. 9,999)', '2026-09-25 10:08:42'),
(115, 22, 'edit', 'transaction', 85, 'Edited Expense: football (Rs. 9,000)', '2026-09-25 10:09:12'),
(116, 22, 'view', 'report', NULL, 'Viewed financial report for September 2026', '2026-09-25 10:09:19'),
(117, 22, 'view', 'dashboard', NULL, 'Visited student dashboard', '2026-09-25 10:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `body`, `created_at`) VALUES
(1, 'Welcome to Coin_Nex 2.0!', 'Monthly reports, AI category auto-suggestions with correction learning, and dynamic savings forecast are now live! Start tracking your student allowance and expenses effortlessly.', '2026-09-24 15:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `budget_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `month` date NOT NULL COMMENT 'First day of the budgeted month',
  `limit_amount` decimal(12,2) NOT NULL,
  `alert_threshold` decimal(5,1) NOT NULL DEFAULT 80.0 COMMENT '% of budget that triggers a warning',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`budget_id`, `user_id`, `category_id`, `month`, `limit_amount`, `alert_threshold`, `created_at`) VALUES
(1, 17, 6, '2026-09-01', 10000.00, 80.0, '2026-09-24 15:53:25'),
(2, 17, 7, '2026-09-01', 6000.00, 80.0, '2026-09-24 15:53:25'),
(3, 17, 8, '2026-09-01', 10000.00, 80.0, '2026-09-24 15:53:25'),
(4, 17, 9, '2026-09-01', 4000.00, 80.0, '2026-09-24 15:53:25'),
(5, 17, 10, '2026-09-01', 2500.00, 80.0, '2026-09-24 15:53:25'),
(6, 17, 11, '2026-09-01', 4500.00, 80.0, '2026-09-24 15:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `icon` varchar(40) NOT NULL DEFAULT '????',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Owner when personal; NULL for system defaults',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `type`, `icon`, `is_default`, `user_id`, `created_at`) VALUES
(1, 'Allowance', 'income', '????', 1, NULL, '2026-09-24 15:53:25'),
(2, 'Part-time Job', 'income', '????', 1, NULL, '2026-09-24 15:53:25'),
(3, 'Scholarship', 'income', '????', 1, NULL, '2026-09-24 15:53:25'),
(4, 'Gift', 'income', '????', 1, NULL, '2026-09-24 15:53:25'),
(5, 'Other Income', 'income', '???', 1, NULL, '2026-09-24 15:53:25'),
(6, 'Food', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(7, 'Transport', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(8, 'Hostel/Rent', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(9, 'Academics', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(10, 'Subscriptions', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(11, 'Entertainment', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(12, 'Miscellaneous', 'expense', '????', 1, NULL, '2026-09-24 15:53:25'),
(13, 'Test Gadgets', 'expense', '🎧', 0, 17, '2026-09-24 16:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `subject` varchar(180) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','resolved') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insights`
--

CREATE TABLE `insights` (
  `insight_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `month` date NOT NULL COMMENT 'First day of the month the insight covers',
  `summary_text` text DEFAULT NULL,
  `tip_text` text DEFAULT NULL,
  `generated_by` enum('engine','ai') NOT NULL DEFAULT 'engine',
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `insights`
--

INSERT INTO `insights` (`insight_id`, `user_id`, `month`, `summary_text`, `tip_text`, `generated_by`, `is_pinned`, `created_at`) VALUES
(1, 17, '2026-09-01', 'September 2026 Overview: Income Rs. 45,000, Expenses Rs. 36,000, Balance Rs. 9,000. Your savings rate is 20.0%, successfully meeting the recommended financial discipline goal! Food is your highest spending category at Rs. 12,000 (33.3% of total spending), up 33% from your 5-month average of Rs. 9,000.', 'Try reducing food delivery & restaurant spending by Rs. 750 per week to boost your savings buffer.', 'engine', 1, '2026-09-24 15:53:25'),
(2, 18, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-24 16:01:01'),
(3, 1, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-24 16:38:33'),
(4, 19, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-25 02:57:19'),
(5, 20, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-25 08:26:10'),
(6, 21, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-25 09:07:10'),
(7, 22, '2026-09-01', 'This month you earned Rs. 0 and spent Rs. 0, leaving a net of Rs. 0. No activity recorded yet — add your first transaction to unlock analysis.', 'Start logging daily — even small records beat a month of guessing.', 'engine', 0, '2026-09-25 09:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `type` enum('budget','info','flag') NOT NULL DEFAULT 'info',
  `related_type` varchar(20) DEFAULT NULL,
  `related_id` int(10) UNSIGNED DEFAULT NULL,
  `notif_key` varchar(64) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `type`, `related_type`, `related_id`, `notif_key`, `link`, `is_read`, `created_at`) VALUES
(1, 17, '???? Food budget alert: You spent Rs. 12,000, exceeding your Rs. 10,000 monthly limit.', 'budget', 'budget', 1, '2026-09|100', 'budgets', 0, '2026-09-24 15:53:25'),
(2, 17, '???? Transport budget notice: You have used 83% (Rs. 5,000 of Rs. 6,000) of your monthly limit.', 'budget', 'budget', 2, '2026-09|80', 'budgets', 0, '2026-09-24 15:53:25'),
(3, 17, '???? Coin_Nex Update: AI expense categorization and monthly reports are ready!', 'info', 'announcement', 1, 'welcome', 'dashboard', 0, '2026-09-24 15:53:25'),
(4, 17, '???? Budget alert: \"Transport\" is at Rs. 5,000 (83% of Rs. 6,000).', 'budget', 'budget', 2, '2026-09|75', NULL, 0, '2026-09-24 16:21:16'),
(5, 17, '???? Budget exceeded! \"Hostel/Rent\" crossed its Rs. 10,000 limit.', 'budget', 'budget', 3, '2026-09|100', NULL, 0, '2026-09-24 16:21:16'),
(6, 17, '???? Budget alert: \"Subscriptions\" is at Rs. 2,000 (80% of Rs. 2,500).', 'budget', 'budget', 5, '2026-09|75', NULL, 0, '2026-09-24 16:21:16'),
(7, 17, '???? Budget alert: \"Entertainment\" is at Rs. 4,000 (88% of Rs. 4,500).', 'budget', 'budget', 6, '2026-09|75', NULL, 0, '2026-09-24 16:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL COMMENT 'SHA-256 of the plain token',
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `tip_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `tip_text` text NOT NULL,
  `rank_score` decimal(6,2) NOT NULL DEFAULT 0.00 COMMENT 'Estimated saving impact ranking',
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_dismissed` tinyint(1) NOT NULL DEFAULT 0,
  `bookmarked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`tip_id`, `user_id`, `category_id`, `tip_text`, `rank_score`, `is_pinned`, `is_dismissed`, `bookmarked`, `created_at`) VALUES
(1, 17, 6, 'Food is your highest spending category (Rs. 12,000 vs Rs. 9,000 historical average). Preparing meals at home 2-3 additional times per week can save ~Rs. 750 per week.', 95.00, 1, 0, 1, '2026-09-24 15:53:25'),
(2, 17, 7, 'You spent Rs. 5,000 on Transport. Using the campus metro shuttle instead of on-demand cabs can save ~Rs. 1,000 this month.', 85.00, 0, 0, 0, '2026-09-24 15:53:25'),
(3, 17, 10, 'Review subscriptions (Rs. 2,000). Canceling unused streaming services or sharing plans saves an easy Rs. 500-800 monthly.', 75.00, 0, 0, 0, '2026-09-24 15:53:25'),
(4, 17, NULL, 'Follow the 50/30/20 student budgeting rule: 50% needs (Hostel/Food), 30% wants, 20% savings.', 65.00, 0, 0, 0, '2026-09-24 15:53:25'),
(5, 20, NULL, 'Add a few transactions and the tip engine will tailor advice to your spending.', 80.00, 0, 0, 0, '2026-09-25 08:26:42'),
(6, 20, NULL, 'Try the 50/30/20 rule: needs 50%, wants 30%, savings 20%.', 74.00, 0, 0, 0, '2026-09-25 08:26:42'),
(7, 20, NULL, 'Keep 3 days of meal money aside in cash for emergencies.', 68.00, 0, 0, 0, '2026-09-25 08:26:42'),
(8, 22, NULL, 'Set a strict mobile & data limit; it is a silent budget killer.', 80.00, 0, 0, 0, '2026-09-25 10:01:05'),
(9, 22, NULL, 'Review weekly, not monthly, so small leaks get caught early.', 74.00, 0, 0, 0, '2026-09-25 10:01:05'),
(10, 22, NULL, 'Use the student discount everywhere you can — cinema, public transport, software.', 68.00, 0, 0, 0, '2026-09-25 10:01:05');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurrence` enum('none','weekly','monthly') NOT NULL DEFAULT 'none',
  `ai_suggested_category` int(10) UNSIGNED DEFAULT NULL,
  `ai_override` tinyint(1) NOT NULL DEFAULT 0,
  `is_duplicate_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `category_id`, `type`, `amount`, `description`, `transaction_date`, `is_recurring`, `recurrence`, `ai_suggested_category`, `ai_override`, `is_duplicate_flag`, `created_at`) VALUES
(1, 17, 1, 'income', 30000.00, 'Monthly family allowance', '2026-04-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(2, 17, 2, 'income', 8000.00, 'Campus library assistant shift', '2026-04-20', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(3, 17, 4, 'income', 4000.00, 'Eid gift from uncle', '2026-04-10', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(4, 17, 8, 'expense', 10000.00, 'Hostel semester accommodation installment', '2026-04-03', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(5, 17, 6, 'expense', 4000.00, 'Campus cafeteria & meals', '2026-04-08', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(6, 17, 6, 'expense', 4500.00, 'Grocery & hostel snacks', '2026-04-22', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(7, 17, 7, 'expense', 4500.00, 'Monthly bus & orange train card', '2026-04-05', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(8, 17, 9, 'expense', 3500.00, 'Course books & lab manual', '2026-04-12', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(9, 17, 11, 'expense', 4500.00, 'Cinema & batch dinner', '2026-04-18', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(10, 17, 10, 'expense', 2000.00, 'Mobile internet bundle & streaming', '2026-04-15', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(11, 17, 1, 'income', 30000.00, 'Monthly family allowance', '2026-05-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(12, 17, 2, 'income', 10000.00, 'Part-time tutoring assignment', '2026-05-25', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(13, 17, 4, 'income', 4000.00, 'Birthday gift', '2026-05-14', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(14, 17, 8, 'expense', 10000.00, 'Hostel room fee & utility share', '2026-05-04', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(15, 17, 6, 'expense', 4800.00, 'Cafeteria lunch & tea', '2026-05-10', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(16, 17, 6, 'expense', 4400.00, 'Hostel grocery supplies', '2026-05-24', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(17, 17, 7, 'expense', 5000.00, 'Fuel & metro commute', '2026-05-06', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(18, 17, 9, 'expense', 3300.00, 'Midterm stationery & assignment printing', '2026-05-16', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(19, 17, 11, 'expense', 5000.00, 'Weekend bowling & pizza', '2026-05-21', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(20, 17, 10, 'expense', 2000.00, 'Data package & Spotify', '2026-05-12', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(21, 17, 1, 'income', 30000.00, 'Monthly family allowance', '2026-06-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(22, 17, 2, 'income', 10000.00, 'Web design freelance gig', '2026-06-22', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(23, 17, 5, 'income', 5000.00, 'Project competition prize', '2026-06-15', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(24, 17, 8, 'expense', 10000.00, 'Hostel accommodation', '2026-06-03', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(25, 17, 6, 'expense', 4600.00, 'Campus meal plan & cafe', '2026-06-09', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(26, 17, 6, 'expense', 4200.00, 'Fruit, yogurt & evening snacks', '2026-06-23', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(27, 17, 7, 'expense', 4800.00, 'Commute & Uber ride', '2026-06-07', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(28, 17, 9, 'expense', 4400.00, 'Course certification exam', '2026-06-14', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(29, 17, 11, 'expense', 5000.00, 'Game zone & hangout', '2026-06-20', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(30, 17, 10, 'expense', 2000.00, 'Subscriptions', '2026-06-11', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(31, 17, 1, 'income', 30000.00, 'Monthly family allowance', '2026-07-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(32, 17, 2, 'income', 9000.00, 'Part-time tutoring', '2026-07-25', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(33, 17, 4, 'income', 4000.00, 'Family allowance bonus', '2026-07-16', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(34, 17, 8, 'expense', 10000.00, 'Hostel monthly rent', '2026-07-04', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(35, 17, 6, 'expense', 5000.00, 'Daily canteen meals & refreshments', '2026-07-11', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(36, 17, 6, 'expense', 4500.00, 'Weekly grocery mart', '2026-07-26', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(37, 17, 7, 'expense', 4500.00, 'Public transit & train ticket', '2026-07-08', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(38, 17, 9, 'expense', 3000.00, 'Notebooks & stationery pack', '2026-07-17', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(39, 17, 11, 'expense', 5000.00, 'Trip to hill station with classmates', '2026-07-22', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(40, 17, 10, 'expense', 2000.00, 'Internet bundle', '2026-07-13', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(41, 17, 1, 'income', 30000.00, 'Monthly family allowance', '2026-08-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(42, 17, 2, 'income', 10000.00, 'Part-time graphic design shift', '2026-08-28', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(43, 17, 4, 'income', 4000.00, 'Summer allowance gift', '2026-08-14', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(44, 17, 8, 'expense', 10000.00, 'Hostel room rent & AC share', '2026-08-03', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(45, 17, 6, 'expense', 4800.00, 'Campus cafe dining', '2026-08-09', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(46, 17, 6, 'expense', 4200.00, 'Breakfast, fruits & groceries', '2026-08-21', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(47, 17, 7, 'expense', 5000.00, 'Bike fuel & rickshaw fares', '2026-08-07', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(48, 17, 9, 'expense', 3500.00, 'Reference books & project binder', '2026-08-15', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(49, 17, 11, 'expense', 6000.00, 'Independence day treat & movies', '2026-08-14', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(50, 17, 10, 'expense', 2000.00, 'Streaming & cloud storage', '2026-08-10', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(51, 17, 1, 'income', 30000.00, 'Family allowance', '2026-09-01', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(52, 17, 2, 'income', 10000.00, 'Part-time job', '2026-09-15', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(53, 17, 4, 'income', 5000.00, 'Gift', '2026-09-12', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(54, 17, 6, 'expense', 3500.00, 'Campus Cafe & daily lunch', '2026-09-04', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(55, 17, 6, 'expense', 4000.00, 'Hostel grocery & pantry items', '2026-09-11', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(56, 17, 6, 'expense', 2500.00, 'Restaurant dinner with friends', '2026-09-18', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(57, 17, 6, 'expense', 2000.00, 'Fast food delivery & snacks', '2026-09-23', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(58, 17, 7, 'expense', 3000.00, 'Monthly Metro & Bus transit card', '2026-09-05', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(59, 17, 7, 'expense', 2000.00, 'Uber rides to university', '2026-09-19', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(60, 17, 8, 'expense', 8500.00, 'Hostel room rent September', '2026-09-02', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(61, 17, 8, 'expense', 1500.00, 'Electricity & Wi-Fi bill share', '2026-09-07', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(62, 17, 9, 'expense', 2000.00, 'University textbooks & course pack', '2026-09-08', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(63, 17, 9, 'expense', 1000.00, 'Assignment printing & lab sheets', '2026-09-21', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(64, 17, 11, 'expense', 2500.00, 'Cinema tickets & popcorn', '2026-09-14', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(65, 17, 11, 'expense', 1500.00, 'Gaming cafe & campus hangout', '2026-09-22', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(66, 17, 10, 'expense', 1200.00, 'Monthly mobile 4G package', '2026-09-06', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(67, 17, 10, 'expense', 800.00, 'Netflix student plan share', '2026-09-10', 0, 'none', NULL, 0, 0, '2026-09-24 15:53:25'),
(68, 18, 1, 'income', 10000.00, 'family', '2025-09-20', 0, 'none', 1, 0, 0, '2026-09-24 16:24:19'),
(73, 18, 1, 'income', 10000.00, 'family', '2026-09-24', 0, 'none', 1, 0, 0, '2026-09-24 16:35:39'),
(74, 18, 6, 'expense', 150.00, 'burger', '2026-09-25', 0, 'none', 6, 0, 0, '2026-09-24 16:36:33'),
(79, 20, 5, 'income', 60000.00, 'sell bike', '2026-09-23', 0, 'none', NULL, 0, 0, '2026-09-25 08:30:09'),
(80, 22, 1, 'income', 50000.00, 'family', '2026-09-20', 0, 'none', 1, 0, 0, '2026-09-25 09:59:10'),
(81, 22, 6, 'expense', 250.00, 'icecream', '2026-09-22', 0, 'none', NULL, 0, 0, '2026-09-25 09:59:43'),
(82, 22, 7, 'expense', 300.00, 'bike', '2026-09-23', 0, 'none', 7, 0, 0, '2026-09-25 10:03:46'),
(83, 22, 7, 'expense', 1000.00, 'bike', '2026-09-23', 0, 'none', 7, 0, 0, '2026-09-25 10:04:37'),
(84, 22, 9, 'expense', 5000.00, 'bat', '2026-09-24', 0, 'none', NULL, 0, 0, '2026-09-25 10:06:46'),
(85, 22, 9, 'expense', 9000.00, 'football', '2026-09-24', 0, 'none', NULL, 0, 0, '2026-09-25 10:07:21'),
(86, 22, 7, 'expense', 9999.00, 'car cover', '2026-09-23', 0, 'none', NULL, 0, 0, '2026-09-25 10:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student',
  `academic_year` varchar(50) DEFAULT NULL,
  `monthly_allowance_baseline` decimal(10,2) DEFAULT 0.00,
  `savings_goal` decimal(10,2) DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'PKR',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `theme` enum('dark','light') NOT NULL DEFAULT 'dark',
  `font_scale` decimal(3,2) NOT NULL DEFAULT 1.00,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `academic_year`, `monthly_allowance_baseline`, `savings_goal`, `currency`, `is_active`, `theme`, `font_scale`, `last_login`, `created_at`) VALUES
(1, 'Coin_Nex Admin', 'admin@coin-nex.com', '$2y$10$eJJeaS/WJRkyHKlfmPJrSuBCBGLzAfDJktO1bCG/6IpK0DqIXkZwy', 'admin', 'Administrator', 0.00, 0.00, 'PKR', 1, 'dark', 1.00, '2026-09-24 16:38:18', '2026-09-24 15:53:25'),
(17, 'Ali Umar', 'umar@gmail.com', '$2y$10$UuKPzlcaAmTWsMsr4oaVg.3OplhVIikVxmRgGmcpugurG.bex76yi', 'student', '3rd Year', 30000.00, 9000.00, 'PKR', 1, 'dark', 1.00, '2026-09-25 01:40:01', '2026-09-24 15:53:25'),
(18, 'ayan', 'ayan@gmail.com', '$2y$10$5LoI4FyrNhYzBNygDYeDBOdGMS3AmYYsc9P3kJ2AWNZ1g5wmYfwaS', 'student', '2 year', 0.00, 0.00, 'PKR', 1, 'dark', 1.00, '2026-09-24 16:23:22', '2026-09-24 16:01:01'),
(19, 'huzaifa', 'huzaifa@gmail.com', '$2y$10$OMvaOCbjgZ4VLnj/EF.FAuZRVjxBHC3wt/L99Zrs3gK2xlKIqWdz.', 'student', '3 year', 0.00, 0.00, 'PKR', 1, 'dark', 1.00, '2026-09-25 02:57:19', '2026-09-25 02:57:19'),
(20, 'rayan', 'rayan@gmail.com', '$2y$10$Hfs5TMHGJaTGQ71Mnm8n.uzPn4f7m5T19E1Vsc4ZIsYwVSwpEdvk2', 'student', '2 year', 0.00, 0.00, 'PKR', 1, 'dark', 1.00, '2026-09-25 08:26:10', '2026-09-25 08:26:10'),
(21, 'zain', 'zain@gmail.com', '$2y$10$r5teLmXsLco7qzpL5hlIh.IIOZ/8g2s0HpDaI8eclrd..3uE5FAeu', 'student', '3 year', 0.00, 0.00, 'PKR', 1, 'dark', 1.00, '2026-09-25 09:07:10', '2026-09-25 09:07:10'),
(22, 'usman', 'usman@gmail.com', '$2y$10$UWa2K/daHpC2ENvuEZYNUuR1QCYenhHGcBAKMtM6Wp4oN8S8Xu432', 'student', '3 year', 50000.00, 5000.00, 'PKR', 1, 'dark', 1.00, '2026-09-25 09:58:27', '2026-09-25 09:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_category_rules`
--

CREATE TABLE `user_category_rules` (
  `rule_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_category_rules`
--

INSERT INTO `user_category_rules` (`rule_id`, `user_id`, `keyword`, `category_id`, `type`, `created_at`) VALUES
(1, 17, 'mcdonald', 6, 'expense', '2026-09-24 15:53:25'),
(2, 17, 'uber', 7, 'expense', '2026-09-24 15:53:25'),
(3, 17, 'netflix', 10, 'expense', '2026-09-24 15:53:25'),
(4, 17, 'gaming', 11, 'expense', '2026-09-24 15:53:25'),
(5, 18, 'family', 1, 'income', '2026-09-24 16:24:19'),
(6, 17, 'test', 6, 'expense', '2026-09-24 16:33:06'),
(7, 17, 'library', 9, 'expense', '2026-09-24 16:33:06'),
(8, 18, 'burger', 6, 'expense', '2026-09-24 16:36:33'),
(9, 20, 'sell', 5, 'income', '2026-09-25 08:30:09'),
(10, 22, 'family', 1, 'income', '2026-09-25 09:59:10'),
(11, 22, 'icecream', 6, 'expense', '2026-09-25 09:59:43'),
(12, 22, 'bike', 7, 'expense', '2026-09-25 10:03:46'),
(13, 22, 'bat', 9, 'expense', '2026-09-25 10:06:46'),
(14, 22, 'football', 9, 'expense', '2026-09-25 10:07:21'),
(15, 22, 'car', 7, 'expense', '2026-09-25 10:08:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_activity_user` (`user_id`,`created_at`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budget_id`),
  ADD UNIQUE KEY `uq_budget_user_cat_month` (`user_id`,`category_id`,`month`),
  ADD KEY `idx_between_category` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `idx_categories_type` (`type`),
  ADD KEY `idx_categories_user` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_status` (`status`,`created_at`);

--
-- Indexes for table `insights`
--
ALTER TABLE `insights`
  ADD PRIMARY KEY (`insight_id`),
  ADD UNIQUE KEY `uq_insight_user_month` (`user_id`,`month`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_notif_user_read` (`user_id`,`is_read`),
  ADD KEY `idx_notif_dedup` (`user_id`,`related_type`,`related_id`,`notif_key`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `idx_reset_user` (`user_id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`tip_id`),
  ADD KEY `idx_tips_user` (`user_id`),
  ADD KEY `fk_tip_category` (`category_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `idx_txn_user_date` (`user_id`,`transaction_date`),
  ADD KEY `idx_txn_category` (`category_id`),
  ADD KEY `idx_txn_type` (`type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `user_category_rules`
--
ALTER TABLE `user_category_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `idx_rule_user_kw` (`user_id`,`keyword`),
  ADD KEY `fk_rule_category` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `budget_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insights`
--
ALTER TABLE `insights`
  MODIFY `insight_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `tip_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_category_rules`
--
ALTER TABLE `user_category_rules`
  MODIFY `rule_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_act_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `fk_budget_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_budget_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insights`
--
ALTER TABLE `insights`
  ADD CONSTRAINT `fk_insight_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_reset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tips`
--
ALTER TABLE `tips`
  ADD CONSTRAINT `fk_tip_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tip_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_txn_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_txn_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_category_rules`
--
ALTER TABLE `user_category_rules`
  ADD CONSTRAINT `fk_rule_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rule_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
