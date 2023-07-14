-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2023 at 03:50 PM
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
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '0000_00_00_000000_create_websockets_statistics_entries_table', 2);

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
(1, 'App\\Models\\UserModel', 2, '_token', '4895d06c32cb42b790a1124895267a6a5b5c5a62d361612521c5a56b4e4bb34b', '[\"*\"]', '2023-07-02 08:59:36', NULL, '2023-06-05 20:19:36', '2023-07-02 08:59:36'),
(10, 'App\\Models\\UserModel', 2, '_token', '4b2388506d2589045ebc2b5fd21ac33af8b89c84b262468aaaa394189480d0d0', '[\"*\"]', '2023-06-26 21:36:20', NULL, '2023-06-26 09:40:05', '2023-06-26 21:36:20'),
(24, 'App\\Models\\UserModel', 2, '_token', '61c72992d0cd93c9402414f1dd0efbc78ebfedb818717549e828a5440a519d52', '[\"*\"]', '2023-07-14 12:49:59', NULL, '2023-07-14 12:29:09', '2023-07-14 12:49:59');

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
-- Table structure for table `tbl_prediction`
--

CREATE TABLE `tbl_prediction` (
  `prediction_id` varchar(100) NOT NULL,
  `first_place` varchar(100) NOT NULL,
  `second_place` varchar(100) NOT NULL,
  `third_place` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `device_ip` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_prediction`
--

INSERT INTO `tbl_prediction` (`prediction_id`, `first_place`, `second_place`, `third_place`, `created_at`, `full_name`, `email`, `phone_number`, `device_ip`, `tour_id`) VALUES
('01h4tqdmhadpj7srpfj1a91zs5', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arz4qdwpcpvqy0kafxp8xq', '2023-07-08 12:25:26', 'Emeka', 'default', '08139590011', '127.0.0.1', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h5a10a7d70hhshkcc9dhr31q', '01h4arfsbeenqgdfvpk4qxbxts', '01h4arp4asbfv9yjvsqk68r0tg', '01h4argvsmaxzw4nkerdpp4vq1', '2023-07-14 11:01:32', 'aaaaaaaaaaaaaaaa', 'default', '22222222222222222222', '127.0.0.1', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h5a1b6x6r248bamjbvwk8z4c', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '01h4arp4asbfv9yjvsqk68r0tg', '2023-07-14 11:07:29', 'Emeka', 'default', '080', '127.0.0.1', '01h4299vwq5mkm8nzdpcdkskmv');

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
('01h4arehczjgp3kwjsrvtzep4p', '01h4arehbevb541jqqdn7y5y7d', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:35:34', '2023-07-02 11:44:15', NULL),
('01h4arfse0zxvxg2dcw4kjhj5z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:36:15', '2023-07-02 07:36:15', NULL),
('01h4argvykma6fq042sfdn4cgc', '01h4argvsmaxzw4nkerdpp4vq1', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:36:50', '2023-07-02 07:36:50', NULL),
('01h4arp4cvtm2rkcpbprpzbc5k', '01h4arp4asbfv9yjvsqk68r0tg', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:39:42', '2023-07-02 11:44:15', NULL),
('01h4arz4rz4a21zh8fr1qpnj3m', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-02 07:44:38', '2023-07-02 07:44:38', NULL),
('01h4as00ge560kk2k6byme0v6j', '01h4as00btdt98nnt8v3r9kzmk', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-02 07:45:06', '2023-07-02 07:45:06', NULL),
('01h4as240svm1zfsyag7tp4t91', '01h4as23yzsp0htg2pbqh9384d', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-02 07:46:15', '2023-07-02 07:46:15', NULL),
('01h4as2w9t3khef8yktxqjf1kn', '01h4as2w4xzfhrf4dydnj5b596', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 0, 0, 0, 0, 0, 0, '2023-07-02 07:46:40', '2023-07-02 07:46:40', NULL),
('01h4as4eyh6ntrr9nr8pwccd1t', '01h4as4ex4y3k8gnfes6gtbkna', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-02 07:47:32', '2023-07-02 07:47:32', NULL),
('01h4as4w65yax6dx6p30scmv4z', '01h4as4w5160f91sjkx2n4jyg5', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-02 07:47:45', '2023-07-02 07:47:45', NULL),
('01h4as5gskm34a2kgnyvxsf978', '01h4as5gqqgbjn4av7bgak2tad', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-02 07:48:07', '2023-07-02 07:48:07', NULL),
('01h4as5tp940hxh80rfdhtgv7p', '01h4as5tmte5xry4xeta54wb1t', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 0, 0, 0, 0, 0, 0, '2023-07-02 07:48:17', '2023-07-02 07:48:17', NULL);

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
-- Table structure for table `tbl_subusers`
--

CREATE TABLE `tbl_subusers` (
  `subuser_id` int(100) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT '1',
  `role` varchar(100) NOT NULL
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
  `team_brief` text DEFAULT NULL,
  `team_badge` varchar(100) DEFAULT NULL,
  `team_color` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_teams`
--

INSERT INTO `tbl_teams` (`team_id`, `team_name`, `tour_id`, `match_played`, `group_in`, `address`, `manager`, `created_at`, `updated_at`, `team_brief`, `team_badge`, `team_color`) VALUES
('01h4arehbevb541jqqdn7y5y7d', 'Cathedral group', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:35:33', '2023-07-02 11:44:15', NULL, NULL, '#FFD700'),
('01h4arfsbeenqgdfvpk4qxbxts', 'Ijeshatedo', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:36:14', '2023-07-02 07:36:14', NULL, NULL, '#B6D0E2'),
('01h4argvsmaxzw4nkerdpp4vq1', 'Mushin', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:36:50', '2023-07-02 07:36:50', NULL, NULL, '#A020F0'),
('01h4arp4asbfv9yjvsqk68r0tg', 'Odi-olowo', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:39:42', '2023-07-02 11:44:15', NULL, NULL, '#FFA500'),
('01h4arz4qdwpcpvqy0kafxp8xq', 'Aguda', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-02 07:44:38', '2023-07-02 07:44:38', NULL, NULL, '#FFC0CB'),
('01h4as00btdt98nnt8v3r9kzmk', 'Idi-oro', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-02 07:45:06', '2023-07-02 07:45:06', NULL, NULL, '#4169e1'),
('01h4as23yzsp0htg2pbqh9384d', 'Ikate', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-02 07:46:15', '2023-07-02 07:46:15', NULL, NULL, '#722F37'),
('01h4as2w4xzfhrf4dydnj5b596', 'Orile-Coker', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'B', NULL, NULL, '2023-07-02 07:46:40', '2023-07-02 07:46:40', NULL, NULL, '#FAFA33'),
('01h4as4ex4y3k8gnfes6gtbkna', 'ASF', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-02 07:47:32', '2023-07-02 07:47:32', NULL, NULL, NULL),
('01h4as4w5160f91sjkx2n4jyg5', 'Okota', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-02 07:47:45', '2023-07-02 07:47:45', NULL, NULL, '#FF0000'),
('01h4as5gqqgbjn4av7bgak2tad', 'Surulere', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-02 07:48:07', '2023-07-02 07:48:07', NULL, NULL, '#00FF00'),
('01h4as5tmte5xry4xeta54wb1t', 'Yaba', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'C', NULL, NULL, '2023-07-02 07:48:17', '2023-07-02 07:48:17', NULL, NULL, '#FFFF00');

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
(2, 'dlam@admin.com', '$2y$10$BNIN4vFOpuGYFJG67pyx5OIMhN6zdL3jR/1MUU5ufHwk2E6MP3yXW', NULL, NULL, 7, 6, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `tbl_prediction`
--
ALTER TABLE `tbl_prediction`
  ADD PRIMARY KEY (`prediction_id`);

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
-- Indexes for table `tbl_subusers`
--
ALTER TABLE `tbl_subusers`
  ADD PRIMARY KEY (`subuser_id`);

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
-- Indexes for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_live`
--
ALTER TABLE `tbl_live`
  MODIFY `live_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_subusers`
--
ALTER TABLE `tbl_subusers`
  MODIFY `subuser_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
