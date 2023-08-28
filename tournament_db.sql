-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2023 at 02:25 PM
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
(24, 'App\\Models\\UserModel', 2, '_token', '61c72992d0cd93c9402414f1dd0efbc78ebfedb818717549e828a5440a519d52', '[\"*\"]', '2023-07-14 12:49:59', NULL, '2023-07-14 12:29:09', '2023-07-14 12:49:59'),
(26, 'App\\Models\\UserModel', 2, '_token', 'f6d19d60d11b5e83b061cf59fda31601164065a6082bb57d6b80ba3527eb9466', '[\"*\"]', '2023-08-07 14:11:27', NULL, '2023-08-07 12:23:12', '2023-08-07 14:11:27'),
(27, 'App\\Models\\UserModel', 2, '_token', 'ad0b6ef83704b259a195d10c1c76481b599c7f47bb2fee0fd55a49c30b36cdd4', '[\"*\"]', '2023-08-09 10:44:49', NULL, '2023-08-08 09:21:06', '2023-08-09 10:44:49'),
(28, 'App\\Models\\UserModel', 2, '_token', 'd0b6e97d7712c5abe506375b8a671d1f6532e198a927d9cbafaab970673fce4d', '[\"*\"]', '2023-08-09 10:51:36', NULL, '2023-08-08 11:43:31', '2023-08-09 10:51:36'),
(29, 'App\\Models\\UserModel', 2, '_token', 'a478d2d48ba17fabaf5b0a1781738fa0cd46471e5cb3226911839aa91d42b627', '[\"*\"]', '2023-08-10 11:51:20', NULL, '2023-08-09 12:40:52', '2023-08-10 11:51:20'),
(31, 'App\\Models\\UserModel', 2, '_token', 'a264366ff47e946f4ffe83ad5cc4aa28da66af139e3758a0aad9e7beb64e868b', '[\"*\"]', '2023-08-25 06:31:46', NULL, '2023-08-25 04:57:25', '2023-08-25 06:31:46'),
(34, 'App\\Models\\SubUserModel', 2, '_token', 'ea0a5f196eda280375c550bb2dbc6d02be6d88735ae9e98b06f43dffe2ccf75f', '[\"*\"]', '2023-08-26 09:51:51', NULL, '2023-08-26 09:26:26', '2023-08-26 09:51:51'),
(35, 'App\\Models\\UserModel', 2, '_token', 'c2ad7860dfe8f41d22e9774c2d6241fc24d3e38b3fb1706de623e6384ce0df9c', '[\"*\"]', '2023-08-26 10:55:11', NULL, '2023-08-26 10:43:11', '2023-08-26 10:55:11'),
(49, 'App\\Models\\UserModel', 2, '_token', '9568b1f080a7d8bc5202dcb3287567545ae1421f490147091799722de999a9dd', '[\"*\"]', '2023-08-28 10:55:26', NULL, '2023-08-28 10:51:26', '2023-08-28 10:55:26');

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
  `creator` varchar(100) NOT NULL,
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
  `schedule_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tour_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kick_off` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_team` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `away_team` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `match_stage` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_schedules`
--

INSERT INTO `tbl_schedules` (`schedule_id`, `tour_id`, `venue`, `kick_off`, `home_team`, `away_team`, `match_stage`, `created`) VALUES
('01h86f75fbw3a0a9ybjhwn6gzy', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba.', '2023-09-02T08:00:00.000Z', '01h4arehbevb541jqqdn7y5y7d', '01h4arfsbeenqgdfvpk4qxbxts', 'Group_Stage', '2023-08-19 08:40:04'),
('01h86f9kgddq8hpnp3z7scn04f', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba.', '2023-09-02T11:00:00.000Z', '01h4arp4asbfv9yjvsqk68r0tg', '01h4argvsmaxzw4nkerdpp4vq1', 'Group_Stage', '2023-08-19 08:41:24'),
('01h86fgb8216wwc6wdvn613mns', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba.', '2023-09-09T09:00:00.000Z', '01h4as2w4xzfhrf4dydnj5b596', '01h4as00btdt98nnt8v3r9kzmk', 'Group_Stage', '2023-08-19 08:45:05'),
('01h86fk3pwchw0c94jjgyphyvy', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-09T09:00:00.000Z', '01h4as5tmte5xry4xeta54wb1t', '01h4as5gqqgbjn4av7bgak2tad', 'Group_Stage', '2023-08-19 08:46:36'),
('01h86g9jmbnca0x2gfe4nyqfh5', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-09T11:00:00.000Z', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as23yzsp0htg2pbqh9384d', 'Group_Stage', '2023-08-19 08:58:52'),
('01h86gazgafnbwx5c23hx4sbm1', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba', '2023-09-09T11:00:00.000Z', '01h4as4w5160f91sjkx2n4jyg5', '01h4as4ex4y3k8gnfes6gtbkna', 'Group_Stage', '2023-08-19 08:59:38'),
('01h86gc6czftsv10wrpavcgmyf', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-16T09:00:00.000Z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4arp4asbfv9yjvsqk68r0tg', 'Group_Stage', '2023-08-19 09:00:18'),
('01h86gea8tzgt6nkfdryaa01fp', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba', '2023-09-16T09:00:00.000Z', '01h4as23yzsp0htg2pbqh9384d', '01h4as2w4xzfhrf4dydnj5b596', 'Group_Stage', '2023-08-19 09:01:27'),
('01h86gfmhbr4xmv2ppkbzbqd51', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-16T11:00:00.000Z', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arehbevb541jqqdn7y5y7d', 'Group_Stage', '2023-08-19 09:02:10'),
('01h86ggzy71jwc3vrykrtwh6r1', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba', '2023-09-16T11:00:00.000Z', '01h4as00btdt98nnt8v3r9kzmk', '01h4arz4qdwpcpvqy0kafxp8xq', 'Group_Stage', '2023-08-19 09:02:55'),
('01h86gnwchjhsrfq8ky1y1gfee', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-23T09:00:00.000Z', '01h4as4ex4y3k8gnfes6gtbkna', '01h4as5tmte5xry4xeta54wb1t', 'Group_Stage', '2023-08-19 09:05:35'),
('01h86gq7em2q3pcj6r99zmaehc', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba.', '2023-09-23T09:00:00.000Z', '01h4as4w5160f91sjkx2n4jyg5', '01h4as5gqqgbjn4av7bgak2tad', 'Group_Stage', '2023-08-19 09:06:19'),
('01h86gsyqqe610jty1pbzabdp4', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba', '2023-09-23T11:00:00.000Z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', 'Group_Stage', '2023-08-19 09:07:49'),
('01h86gtwn01vsqsapk7aajsadm', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-23T11:00:00.000Z', '01h4arehbevb541jqqdn7y5y7d', '01h4arp4asbfv9yjvsqk68r0tg', 'Group_Stage', '2023-08-19 09:08:19'),
('01h86gyj7azt51exeydn52tzk3', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba.', '2023-09-30T09:00:00.000Z', '01h4as23yzsp0htg2pbqh9384d', '01h4as00btdt98nnt8v3r9kzmk', 'Group_Stage', '2023-08-19 09:10:20'),
('01h86gzq60dfkf02q1590vzhcg', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-30T09:00:00.000Z', '01h4as2w4xzfhrf4dydnj5b596', '01h4arz4qdwpcpvqy0kafxp8xq', 'Group_Stage', '2023-08-19 09:10:57'),
('01h86h12jje45fs553bze6pmvf', '01h4299vwq5mkm8nzdpcdkskmv', 'Igbobi College, Yaba', '2023-09-30T11:00:00.000Z', '01h4as4ex4y3k8gnfes6gtbkna', '01h4as5gqqgbjn4av7bgak2tad', 'Group_Stage', '2023-08-19 09:11:42'),
('01h86h26rqmn9y1s7bzfv0eqrd', '01h4299vwq5mkm8nzdpcdkskmv', 'CMS Grammar school, Bariga.', '2023-09-30T11:00:00.000Z', '01h4as5tmte5xry4xeta54wb1t', '01h4as4w5160f91sjkx2n4jyg5', 'Group_Stage', '2023-08-19 09:12:19');

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
('01h4arfse0zxvxg2dcw4kjhj5z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:36:15', '2023-07-14 21:20:02', NULL),
('01h4argvykma6fq042sfdn4cgc', '01h4argvsmaxzw4nkerdpp4vq1', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 0, 0, 0, 0, 0, 0, '2023-07-02 07:36:50', '2023-07-14 21:20:02', NULL),
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
('01h4arfsbeenqgdfvpk4qxbxts', 'Ijeshatedo', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:36:14', '2023-07-14 21:20:00', NULL, NULL, '#B6D0E2'),
('01h4argvsmaxzw4nkerdpp4vq1', 'Mushin', '01h4299vwq5mkm8nzdpcdkskmv', 0, 'A', NULL, NULL, '2023-07-02 07:36:50', '2023-07-14 21:20:02', NULL, NULL, '#A020F0'),
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
(2, 'admin@dlamfa.com', '$2y$10$BNIN4vFOpuGYFJG67pyx5OIMhN6zdL3jR/1MUU5ufHwk2E6MP3yXW', NULL, NULL, 7, 6, 'admin');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_live`
--
ALTER TABLE `tbl_live`
  MODIFY `live_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_subusers`
--
ALTER TABLE `tbl_subusers`
  MODIFY `subuser_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
