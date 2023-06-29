-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2023 at 02:58 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tournament_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_08_19_000000_create_failed_jobs_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\UserModel', 2, '_token', '4895d06c32cb42b790a1124895267a6a5b5c5a62d361612521c5a56b4e4bb34b', '[\"*\"]', '2023-06-28 22:41:56', NULL, '2023-06-05 20:19:36', '2023-06-28 22:41:56'),
(10, 'App\\Models\\UserModel', 2, '_token', '4b2388506d2589045ebc2b5fd21ac33af8b89c84b262468aaaa394189480d0d0', '[\"*\"]', '2023-06-26 21:36:20', NULL, '2023-06-26 09:40:05', '2023-06-26 21:36:20'),
(11, 'App\\Models\\UserModel', 2, '_token', '258b0afa10638e7912ec8df1bf0220973383675397602e19c4e2062d4e83cc85', '[\"*\"]', '2023-06-28 23:56:29', NULL, '2023-06-28 00:29:55', '2023-06-28 23:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `goup_id` int(100) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `highest_count` int(10) NOT NULL DEFAULT 4,
  `current_count` int(10) NOT NULL DEFAULT 0,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_live`
--

CREATE TABLE `tbl_live` (
  `shedule_id` int(100) NOT NULL,
  `vs_1` int(10) NOT NULL,
  `vs_2` int(10) NOT NULL,
  `vs_1_score` int(10) NOT NULL DEFAULT 0,
  `vs_2_score` int(10) NOT NULL DEFAULT 0,
  `venue` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `isStarted` int(10) NOT NULL DEFAULT 0,
  `isPaused` int(10) NOT NULL DEFAULT 0,
  `isEnded` int(10) NOT NULL DEFAULT 0,
  `curr_time` int(100) NOT NULL DEFAULT 0,
  `extra_col` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_results`
--

CREATE TABLE `tbl_results` (
  `result_id` varchar(100) NOT NULL,
  `away_team` varchar(100) NOT NULL,
  `home_team` varchar(100) NOT NULL,
  `home_score` varchar(100) NOT NULL,
  `away_score` varchar(100) NOT NULL,
  `match_stage` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `date_played` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_results`
--

INSERT INTO `tbl_results` (`result_id`, `away_team`, `home_team`, `home_score`, `away_score`, `match_stage`, `created_at`, `updated_at`, `tour_id`, `date_played`) VALUES
('01h42abd7xnk2dg90dtr99019n', '01h429ez0j1h6213feb3xc4cbz', '01h429ee5sxt2hehm5vfck58ar', '3', '5', 'Group_Stage', '2023-06-29 00:55:15', '2023-06-29 00:55:15', '01h4299vwq5mkm8nzdpcdkskmv', '2023-06-29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedules`
--

CREATE TABLE `tbl_schedules` (
  `schedule_id` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `kick_off` varchar(100) NOT NULL,
  `home_team` varchar(100) NOT NULL,
  `away_team` varchar(100) NOT NULL,
  `match_stage` varchar(100) DEFAULT NULL,
  `created` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_schedules`
--

INSERT INTO `tbl_schedules` (`schedule_id`, `tour_id`, `venue`, `kick_off`, `home_team`, `away_team`, `match_stage`, `created`) VALUES
('01h429naq0w4f0bmg9tk6tpk14', '01h4299vwq5mkm8nzdpcdkskmv', 'the stadium, olumpic, Surulere', '2023-07-21 00:42:00', '01h429ee5sxt2hehm5vfck58ar', '01h429ez0j1h6213feb3xc4cbz', 'Group_Stage', '2023-06-29 00:43:12'),
('01h429q5c1kdbj0zn68qw6svab', '01h4299vwq5mkm8nzdpcdkskmv', 'the same venue of yesterday', '2023-07-29 00:42:00', '01h429hyjctzazk5hs70kymc9w', '01h429jeeb0j7aspynfevzf40d', 'Group_Stage', '2023-06-29 00:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standings_cup`
--

CREATE TABLE `tbl_standings_cup` (
  `standing_id` varchar(100) NOT NULL,
  `team_id` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `group_in` varchar(100) DEFAULT NULL,
  `played` int(10) NOT NULL DEFAULT 0,
  `won` int(10) NOT NULL DEFAULT 0,
  `draw` int(10) NOT NULL DEFAULT 0,
  `lose` int(10) NOT NULL DEFAULT 0,
  `goal_diff` int(100) DEFAULT 0,
  `points` int(10) NOT NULL DEFAULT 0,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `extra_col` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_standings_cup`
--

INSERT INTO `tbl_standings_cup` (`standing_id`, `team_id`, `tour_id`, `group_in`, `played`, `won`, `draw`, `lose`, `goal_diff`, `points`, `created_at`, `updated_at`, `extra_col`) VALUES
('01h429ee6qjbq99z3zk6gspmnr', '01h429ee5sxt2hehm5vfck58ar', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 1, 0, 0, 1, -2, 0, '2023-06-29 00:39:26', '2023-06-29 00:56:29', NULL),
('01h429ez215wwhcprxagk3xjff', '01h429ez0j1h6213feb3xc4cbz', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 1, 1, 0, 0, 2, 3, '2023-06-29 00:39:43', '2023-06-29 00:56:29', NULL),
('01h429fvzfkc86ycvme47002de', '01h429fvy9hk89kw15jfc4eh39', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-06-29 00:40:13', '2023-06-29 00:40:13', NULL),
('01h429ge8k8z0m0hev31qaymyx', '01h429ge72hz5hb8bd8ckrs1hq', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-06-29 00:40:32', '2023-06-29 00:40:32', NULL),
('01h429hknzey225m2epmpa2esq', '01h429hkm5dzwt8h5br47tr9jc', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-06-29 00:41:10', '2023-06-29 00:41:10', NULL),
('01h429hykmt6mfhb6c61qnxwn2', '01h429hyjctzazk5hs70kymc9w', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-06-29 00:41:21', '2023-06-29 00:41:21', NULL),
('01h429jeezk8g86v3rjc46a9ke', '01h429jeeb0j7aspynfevzf40d', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-06-29 00:41:38', '2023-06-29 00:41:38', NULL),
('01h429k8fptr0z4rvgt1vkkxj4', '01h429k8e0awmke1bwz816c49v', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-06-29 00:42:04', '2023-06-29 00:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standings_league`
--

CREATE TABLE `tbl_standings_league` (
  `standing_id` varchar(100) NOT NULL,
  `team_id` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `group_in` varchar(100) DEFAULT NULL,
  `played` int(10) NOT NULL DEFAULT 0,
  `won` int(10) NOT NULL DEFAULT 0,
  `draw` int(10) NOT NULL DEFAULT 0,
  `lose` int(10) NOT NULL DEFAULT 0,
  `goal_diff` int(100) DEFAULT 0,
  `points` int(10) NOT NULL DEFAULT 0,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `extra_col` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teams`
--

CREATE TABLE `tbl_teams` (
  `team_id` varchar(100) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `match_played` int(100) NOT NULL DEFAULT 0,
  `group_in` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `team_brief` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_teams`
--

INSERT INTO `tbl_teams` (`team_id`, `team_name`, `tour_id`, `match_played`, `group_in`, `address`, `manager`, `created_at`, `updated_at`, `team_brief`) VALUES
('01h429ee5sxt2hehm5vfck58ar', 'Aguda Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 1, 'A', NULL, NULL, '2023-06-29 00:39:26', '2023-06-29 00:56:29', NULL),
('01h429ez0j1h6213feb3xc4cbz', 'Ikate Arch', '01h4299vwq5mkm8nzdpcdkskmv', 1, 'A', NULL, NULL, '2023-06-29 00:39:43', '2023-06-29 00:56:29', NULL),
('01h429fvy9hk89kw15jfc4eh39', 'Munshin Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-06-29 00:40:13', '2023-06-29 00:40:13', NULL),
('01h429ge72hz5hb8bd8ckrs1hq', 'Idioro Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-06-29 00:40:32', '2023-06-29 00:40:32', NULL),
('01h429hkm5dzwt8h5br47tr9jc', 'Itire Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-06-29 00:41:10', '2023-06-29 00:41:10', NULL),
('01h429hyjctzazk5hs70kymc9w', 'AYF', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-06-29 00:41:21', '2023-06-29 00:41:21', NULL),
('01h429jeeb0j7aspynfevzf40d', 'Isolo Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-06-29 00:41:37', '2023-06-29 00:41:37', NULL),
('01h429k8e0awmke1bwz816c49v', 'Ebutemeta Arch.', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-06-29 00:42:04', '2023-06-29 00:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tournament`
--

CREATE TABLE `tbl_tournament` (
  `tour_id` varchar(100) NOT NULL,
  `tour_title` varchar(255) NOT NULL,
  `user_id` int(100) NOT NULL,
  `tour_type` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_tournament`
--

INSERT INTO `tbl_tournament` (`tour_id`, `tour_title`, `user_id`, `tour_type`, `created_at`, `updated_at`) VALUES
('01h4299vwq5mkm8nzdpcdkskmv', 'Bishop\'s cup (Male Category)', 2, 'cup', '2023-06-29 00:36:56', '2023-06-29 00:36:56'),
('01h429avf0ykmdah080bdj5t43', 'Bishop\'s Cup (Female Category)', 2, 'cup', '2023-06-29 00:37:29', '2023-06-29 00:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `no_of_leagues` int(100) DEFAULT 0,
  `no_of_cups` int(100) DEFAULT 0,
  `role` varchar(100) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `email`, `password`, `firstname`, `lastname`, `no_of_leagues`, `no_of_cups`, `role`) VALUES
(2, 'email.com', '$2y$10$BNIN4vFOpuGYFJG67pyx5OIMhN6zdL3jR/1MUU5ufHwk2E6MP3yXW', NULL, NULL, 7, 6, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`goup_id`);

--
-- Indexes for table `tbl_live`
--
ALTER TABLE `tbl_live`
  ADD PRIMARY KEY (`shedule_id`);

--
-- Indexes for table `tbl_results`
--
ALTER TABLE `tbl_results`
  ADD PRIMARY KEY (`result_id`);

--
-- Indexes for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `tbl_standings_cup`
--
ALTER TABLE `tbl_standings_cup`
  ADD PRIMARY KEY (`standing_id`);

--
-- Indexes for table `tbl_standings_league`
--
ALTER TABLE `tbl_standings_league`
  ADD PRIMARY KEY (`standing_id`);

--
-- Indexes for table `tbl_teams`
--
ALTER TABLE `tbl_teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `tbl_tournament`
--
ALTER TABLE `tbl_tournament`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `goup_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_live`
--
ALTER TABLE `tbl_live`
  MODIFY `shedule_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
