-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2023 at 02:06 PM
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
(1, 'App\\Models\\UserModel', 2, '_token', '4895d06c32cb42b790a1124895267a6a5b5c5a62d361612521c5a56b4e4bb34b', '[\"*\"]', '2023-06-30 22:09:34', NULL, '2023-06-05 20:19:36', '2023-06-30 22:09:34'),
(10, 'App\\Models\\UserModel', 2, '_token', '4b2388506d2589045ebc2b5fd21ac33af8b89c84b262468aaaa394189480d0d0', '[\"*\"]', '2023-06-26 21:36:20', NULL, '2023-06-26 09:40:05', '2023-06-26 21:36:20'),
(11, 'App\\Models\\UserModel', 2, '_token', '258b0afa10638e7912ec8df1bf0220973383675397602e19c4e2062d4e83cc85', '[\"*\"]', '2023-07-01 10:57:03', NULL, '2023-06-28 00:29:55', '2023-07-01 10:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_live`
--

CREATE TABLE `tbl_live` (
  `live_id` int(100) NOT NULL,
  `home_team` varchar(100) NOT NULL,
  `away_team` varchar(100) NOT NULL,
  `home_team_score` int(10) NOT NULL DEFAULT 0,
  `away_team_score` int(10) NOT NULL DEFAULT 0,
  `tour_id` varchar(100) NOT NULL,
  `isPaused` int(10) NOT NULL DEFAULT 0,
  `match_stage` varchar(100) DEFAULT NULL,
  `curr_time` int(100) NOT NULL DEFAULT 0
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
('01h48mv7tz2fcm46f8v49j86bt', '01h48mv7svfz0pz5yaq581d4yp', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-01 11:54:07', '2023-07-01 11:54:07', NULL),
('01h48mvqkgfqfc7yf8y91hf1we', '01h48mvqhmsa2z85kx4kc2tjqh', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-01 11:54:23', '2023-07-01 11:54:23', NULL),
('01h48mw1a9rbax9b6v42bytza6', '01h48mw192jzjwavagtbwagfcf', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-01 11:54:33', '2023-07-01 11:54:33', NULL),
('01h48mwk8n731ehrh1rjbwxtxd', '01h48mwk7p34x3p7e2tsw5r7gv', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-01 11:54:51', '2023-07-01 11:54:51', NULL),
('01h48mxk6kk7mnw34787tx1qe1', '01h48mxk59mjzftgtvq84nb15f', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-01 11:55:24', '2023-07-01 11:55:24', NULL),
('01h48my1zx93rj740y4j1dsyhh', '01h48my1z159zk60edw51q5vtb', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-01 11:55:39', '2023-07-01 11:55:39', NULL),
('01h48myetp201stj1hxqwpg5ya', '01h48myesasrqk850y16k7z0et', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-01 11:55:52', '2023-07-01 11:55:52', NULL),
('01h48myvw3r6drw80z3bz4w7ge', '01h48myvsg0vh5yewwsm12gqpx', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-01 11:56:05', '2023-07-01 11:56:05', NULL),
('01h48mz6rry4vmgb3b1qf5drqw', '01h48mz6qmphh4rfm7801ecqsa', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-01 11:56:17', '2023-07-01 11:56:17', NULL),
('01h48mzghmjy4687tgrxkt8se8', '01h48mzggttf56twkyqr3hvvy1', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-01 11:56:27', '2023-07-01 11:56:27', NULL),
('01h48n01zztfhse1y9jwaf73xc', '01h48n01xehxage2aezkmbn63b', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-01 11:56:44', '2023-07-01 11:56:44', NULL),
('01h48n0kxzdwq6br8s528p4dhd', '01h48n0kwfdv6zt0axctb5g9qz', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-01 11:57:03', '2023-07-01 11:57:03', NULL);

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
('01h48mv7svfz0pz5yaq581d4yp', 'Odiolowo', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-01 11:54:07', '2023-07-01 11:54:07', NULL),
('01h48mvqhmsa2z85kx4kc2tjqh', 'Ijeshatedo', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-01 11:54:23', '2023-07-01 11:54:23', NULL),
('01h48mw192jzjwavagtbwagfcf', 'Munshin', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-01 11:54:33', '2023-07-01 11:54:33', NULL),
('01h48mwk7p34x3p7e2tsw5r7gv', 'Cathedral', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-01 11:54:51', '2023-07-01 11:54:51', NULL),
('01h48mxk59mjzftgtvq84nb15f', 'Orile Coker', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-01 11:55:24', '2023-07-01 11:55:24', NULL),
('01h48my1z159zk60edw51q5vtb', 'Idioro', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-01 11:55:39', '2023-07-01 11:55:39', NULL),
('01h48myesasrqk850y16k7z0et', 'Aguda', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-01 11:55:52', '2023-07-01 11:55:52', NULL),
('01h48myvsg0vh5yewwsm12gqpx', 'Ikate', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-01 11:56:05', '2023-07-01 11:56:05', NULL),
('01h48mz6qmphh4rfm7801ecqsa', 'Yaba', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-01 11:56:17', '2023-07-01 11:56:17', NULL),
('01h48mzggttf56twkyqr3hvvy1', 'Surulere', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-01 11:56:27', '2023-07-01 11:56:27', NULL),
('01h48n01xehxage2aezkmbn63b', 'Okota', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-01 11:56:44', '2023-07-01 11:56:44', NULL),
('01h48n0kwfdv6zt0axctb5g9qz', 'ASF', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-01 11:57:03', '2023-07-01 11:57:03', NULL);

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
('01h4299vwq5mkm8nzdpcdkskmv', 'DLAM FOOTBALL ACADEMY', 2, 'cup', '2023-06-29 00:36:56', '2023-06-29 00:36:56');

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
-- Indexes for table `tbl_live`
--
ALTER TABLE `tbl_live`
  ADD PRIMARY KEY (`live_id`);

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
-- AUTO_INCREMENT for table `tbl_live`
--
ALTER TABLE `tbl_live`
  MODIFY `live_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
