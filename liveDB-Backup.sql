-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 24, 2023 at 06:56 AM
-- Server version: 8.0.34-cll-lve
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dlamyout_football`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
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
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
(11, 'App\\Models\\UserModel', 2, '_token', '258b0afa10638e7912ec8df1bf0220973383675397602e19c4e2062d4e83cc85', '[\"*\"]', '2023-07-01 10:57:03', NULL, '2023-06-28 00:29:55', '2023-07-01 10:57:03'),
(12, 'App\\Models\\UserModel', 2, '_token', '47789ad265289ccc8db956a43347cde2007440b6f3b7491c1a4a55a5cc33e86e', '[\"*\"]', '2023-07-01 14:45:57', NULL, '2023-07-01 14:42:45', '2023-07-01 14:45:57'),
(13, 'App\\Models\\UserModel', 2, '_token', 'e4f5635970fac105382ea8ecb7b3c37c939b5a495db1ef7af91d37d308fe1315', '[\"*\"]', '2023-07-27 13:27:18', NULL, '2023-07-27 13:27:12', '2023-07-27 13:27:18'),
(18, 'App\\Models\\UserModel', 2, '_token', '2fedb91d5d1b14d59426fdc2f944426afa44fb110692718d0a338abcd1d607d6', '[\"*\"]', '2023-08-10 11:21:22', NULL, '2023-08-10 11:20:08', '2023-08-10 11:21:22'),
(19, 'App\\Models\\UserModel', 2, '_token', '72fa6d5afb12925bc925a3168be2b51088a496c77b37f6965b608c3dd77acfc8', '[\"*\"]', '2023-08-19 07:46:36', NULL, '2023-08-19 07:37:07', '2023-08-19 07:46:36'),
(26, 'App\\Models\\SubUserModel', 5, '_token', '3ce9d2c436298ed640ae3d543419358662764b8760ae47bb339f612f37c8d2b3', '[\"*\"]', '2023-08-24 14:06:15', NULL, '2023-08-24 14:01:53', '2023-08-24 14:06:15'),
(28, 'App\\Models\\UserModel', 2, '_token', '01b6d30b97db4a42604207a4f6231b5663a2c5fcad98330fe5b386b3039f2a3d', '[\"*\"]', '2023-08-24 20:04:42', NULL, '2023-08-24 19:47:48', '2023-08-24 20:04:42'),
(30, 'App\\Models\\UserModel', 2, '_token', '6384e46876aec7636083969735dcb01e4b7d19df20446813ad74a451875a85d5', '[\"*\"]', '2023-08-25 08:29:26', NULL, '2023-08-25 06:43:02', '2023-08-25 08:29:26'),
(32, 'App\\Models\\UserModel', 2, '_token', '2d24e0aa8166fb92c362f5d96af8f659277adebbe5fe4ee690a15beb0b1e349b', '[\"*\"]', '2023-08-26 09:59:09', NULL, '2023-08-26 07:12:03', '2023-08-26 09:59:09'),
(33, 'App\\Models\\UserModel', 2, '_token', 'a25e9dfe0644cd696cd0c69293c92770d39d8421c5f8e8e13b76de85712a42f9', '[\"*\"]', '2023-08-27 17:36:14', NULL, '2023-08-27 17:36:06', '2023-08-27 17:36:14'),
(48, 'App\\Models\\UserModel', 2, '_token', 'bee4015c9c2034192ba52ee162307a52f0df19757a68fa586f12f781d3433b82', '[\"*\"]', '2023-09-01 18:37:50', NULL, '2023-09-01 18:37:23', '2023-09-01 18:37:50'),
(50, 'App\\Models\\UserModel', 2, '_token', '5e6320de66e392c4494c7d19903c0f71b195214eff0a736294981311dc9f66d6', '[\"*\"]', '2023-09-02 04:30:47', NULL, '2023-09-01 19:24:29', '2023-09-02 04:30:47'),
(51, 'App\\Models\\UserModel', 2, '_token', '434cbf27c690f04f33e410ba20de95e100eedc9de748140c9dd6ec27f0a621bb', '[\"*\"]', '2023-09-02 06:22:39', NULL, '2023-09-02 04:48:25', '2023-09-02 06:22:39'),
(53, 'App\\Models\\UserModel', 2, '_token', '15e4b6adfee0a03042fd85d3d5d6079c2305b76bf48f565bca85443ae0b94ffa', '[\"*\"]', '2023-09-02 09:59:34', NULL, '2023-09-02 09:59:22', '2023-09-02 09:59:34'),
(68, 'App\\Models\\SubUserModel', 7, '_token', '9ee1e2adb99868fa6ed63dc2011013ef6e225ba3e6c52ff1fac8bca294abec49', '[\"*\"]', '2023-10-14 12:36:19', NULL, '2023-09-09 06:54:17', '2023-10-14 12:36:19'),
(85, 'App\\Models\\SubUserModel', 8, '_token', '706b807513ffa77a29dd2f0d19e6892bb1de0bfc575a6eecabe9c510df2ad656', '[\"*\"]', '2023-10-14 11:29:14', NULL, '2023-09-23 06:35:41', '2023-10-14 11:29:14'),
(88, 'App\\Models\\SubUserModel', 8, '_token', '56b830c638c0a6c435585288757e933a4194ac77f1c64cfb13f8ea17379b4bde', '[\"*\"]', '2023-10-23 21:48:46', NULL, '2023-09-23 13:02:58', '2023-10-23 21:48:46'),
(89, 'App\\Models\\UserModel', 2, '_token', 'fb75eba33e9c5228a9070ad37e45db2a4ec4a0328c595d19a4fcb6dad2750eb9', '[\"*\"]', '2023-10-13 20:48:58', NULL, '2023-09-23 17:31:53', '2023-10-13 20:48:58'),
(96, 'App\\Models\\UserModel', 2, '_token', '6ab289a6b1783a372fcf0604276f49898c7cfed4d4a01a84d2a056ae31dadc34', '[\"*\"]', '2023-10-23 21:34:32', NULL, '2023-10-23 21:32:02', '2023-10-23 21:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `feedback_id` int NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `feedbackText` text NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `device_ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`feedback_id`, `tour_id`, `name`, `feedbackText`, `created_at`, `device_ip`) VALUES
(2, '01h4299vwq5mkm8nzdpcdkskmv', 'proffictech', 'Over sense de worry am', '2023-09-10 00:20:04', '131.153.147.50'),
(3, '01h4299vwq5mkm8nzdpcdkskmv', 'Chidinma', 'E too make sense die!!! E dey simple and user friendly.\nThree Gbosa to you', '2023-09-10 06:53:54', '131.153.147.50'),
(4, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Lovely ,', '2023-09-10 15:01:20', '131.153.147.50'),
(5, '01h4299vwq5mkm8nzdpcdkskmv', 'Ayodeji', 'Nice and good', '2023-09-12 16:50:31', '131.153.147.50'),
(6, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Cool', '2023-09-12 22:56:51', '131.153.147.50'),
(7, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Omo the app make sense oo', '2023-09-16 10:28:42', '131.153.147.50'),
(8, '01h4299vwq5mkm8nzdpcdkskmv', 'Smart', 'Makes sense', '2023-09-16 10:35:31', '131.153.147.50'),
(9, '01h4299vwq5mkm8nzdpcdkskmv', 'obum', 'I\'m a developer as well and I must say I\'m really impressed with what you\'ve done here. Very responsive, very nice user interface and experience, keep up the good work guys', '2023-09-16 12:45:26', '131.153.147.50'),
(10, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It\'s a simple and good app. It\'s the first of it\'s kind. Kudos \nWe can build more functionalities like including the names of the players, coaches, penalties, yellow card, total team scores so far', '2023-09-16 12:48:46', '131.153.147.50'),
(11, '01h4299vwq5mkm8nzdpcdkskmv', 'Bishop Samuel', 'It\'s nice ....  well done media team', '2023-09-16 13:23:36', '131.153.147.50'),
(12, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It\'s great,', '2023-09-16 14:47:00', '131.153.147.50'),
(13, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Best', '2023-09-16 14:57:44', '131.153.147.50'),
(14, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Dope', '2023-09-16 15:26:12', '131.153.147.50'),
(15, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Great!', '2023-09-16 15:30:33', '131.153.147.50'),
(16, '01h4299vwq5mkm8nzdpcdkskmv', 'Demola', 'Solid site💯💯💯💯', '2023-09-16 19:07:58', '131.153.147.50'),
(17, '01h4299vwq5mkm8nzdpcdkskmv', 'Austin Samuel', 'It\'s a good one', '2023-09-19 04:27:10', '102.88.62.232'),
(18, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Omo e dope like mad', '2023-09-19 09:31:56', '129.205.124.235'),
(19, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'On point!!', '2023-09-20 06:32:32', '102.88.62.26'),
(20, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It\'s nice', '2023-09-23 07:34:12', '105.112.181.131'),
(21, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Live scores and result not showing even fixtures sef', '2023-09-23 08:17:36', '102.89.47.115'),
(22, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'I feel it’s a good start for the Diocese also cause it has helped us who hasn’t been present at the matches to feel amongst the players', '2023-09-23 08:24:03', '102.88.35.157'),
(23, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It\'s awesome', '2023-09-23 08:32:14', '129.205.124.236'),
(24, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It has been amazing. You guys are doing well.', '2023-09-23 08:50:42', '102.88.62.130'),
(25, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Solid app, God bless the creator of the App', '2023-09-23 09:12:20', '102.88.62.249'),
(26, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Great', '2023-09-23 09:15:40', '102.89.41.188'),
(27, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Kilo n see', '2023-09-23 10:02:32', '105.113.72.129'),
(28, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It\'s a good innovative.', '2023-09-23 11:22:04', '105.112.31.253'),
(29, '01h4299vwq5mkm8nzdpcdkskmv', 'Lexie', 'Kudos 👏 men you guys are doing a great job so far, nevertheless going forward there could be improvements', '2023-09-23 13:31:20', '102.88.35.210'),
(30, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'E dey ok', '2023-09-23 15:20:42', '41.190.31.142'),
(31, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Okay', '2023-09-23 18:38:09', '102.90.42.79'),
(32, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Good', '2023-09-23 21:51:52', '102.88.34.175'),
(33, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'E sharp', '2023-09-24 10:46:23', '102.88.34.106'),
(34, '01h4299vwq5mkm8nzdpcdkskmv', 'Golden', 'Dope💯', '2023-09-25 10:41:32', '105.112.183.184'),
(35, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It’s great', '2023-09-28 10:30:24', '216.73.160.10'),
(36, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Oya', '2023-09-28 11:05:29', '105.113.72.224'),
(37, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Oya', '2023-09-28 11:05:29', '105.113.72.224'),
(38, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'The app is dope buh it should be able to show the full stats about a team...GF(GOAL FOR) AND GA(GOAL AGAINST) are absent even if I rotate my screen...also the players should be updated', '2023-09-29 16:17:36', '41.186.78.196'),
(39, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Cool mehn', '2023-09-29 20:27:44', '102.88.62.213'),
(40, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Kool', '2023-09-30 08:07:26', '105.112.79.74'),
(41, '01h4299vwq5mkm8nzdpcdkskmv', 'Ven charles fakiyesi', 'Very good. una weli don  o', '2023-09-30 08:36:09', '197.210.8.114'),
(42, '01h4299vwq5mkm8nzdpcdkskmv', 'Obinna', 'The app make sense, the only thing wey dey miss Na statistics to know top scorers', '2023-09-30 11:21:15', '105.113.70.104'),
(43, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Cool app. Easy to navigate.', '2023-09-30 11:32:30', '105.113.83.71'),
(44, '01h4299vwq5mkm8nzdpcdkskmv', 'Wale moore', 'Very nice', '2023-09-30 12:35:16', '102.88.62.232'),
(45, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'E make sense', '2023-09-30 12:56:38', '197.211.58.60'),
(46, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Nothing.. thanks', '2023-09-30 14:10:17', '197.210.52.229'),
(47, '01h4299vwq5mkm8nzdpcdkskmv', 'I be Somto from Okota Archdeaconry.', 'Omo, dis App make sense die. E dey very easy to navigate and very informative. Una do well.', '2023-09-30 17:18:09', '102.89.22.231'),
(48, '01h4299vwq5mkm8nzdpcdkskmv', 'Victor rashford', 'The app dey ok and I love it', '2023-10-01 08:43:09', '102.88.62.113'),
(49, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Good', '2023-10-01 08:56:29', '129.205.114.26'),
(50, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Good idea', '2023-10-01 15:45:46', '102.88.63.76'),
(51, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It ok', '2023-10-01 20:12:04', '102.88.34.71'),
(52, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'it\'s nice', '2023-10-02 15:51:30', '129.205.113.162'),
(53, '01h4299vwq5mkm8nzdpcdkskmv', 'Obinna David', 'E sharp', '2023-10-07 07:49:26', '105.112.67.179'),
(54, '01h4299vwq5mkm8nzdpcdkskmv', 'Shittu oluwatobi', 'He make sense', '2023-10-07 10:14:23', '197.211.58.63'),
(55, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Make sense', '2023-10-07 11:21:42', '102.88.62.30'),
(56, '01h4299vwq5mkm8nzdpcdkskmv', 'Ifexzy', 'Awesome', '2023-10-07 11:34:42', '102.88.62.237'),
(57, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Ugf', '2023-10-07 11:35:01', '129.205.124.241'),
(58, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Good', '2023-10-07 12:58:07', '197.210.85.130'),
(59, '01h4299vwq5mkm8nzdpcdkskmv', 'Princess', 'I don\'t know yet', '2023-10-07 13:36:13', '102.88.62.251'),
(60, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'Hi DLAM LOC.\n\nI suggest there\'ll be provision on the page for statistics where goal scorers and their tally will be recorded. \nEverything stats including the best and worst discipline team. \n\nThanks', '2023-10-07 14:40:18', '102.88.63.108'),
(61, '01h4299vwq5mkm8nzdpcdkskmv', NULL, 'It can only get better, Kudos to the DLAM Football Association.', '2023-10-14 13:29:40', '154.118.65.185');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_live`
--

CREATE TABLE `tbl_live` (
  `live_id` int NOT NULL,
  `home_team` varchar(100) NOT NULL,
  `away_team` varchar(100) NOT NULL,
  `home_team_score` int NOT NULL DEFAULT '0',
  `away_team_score` int NOT NULL DEFAULT '0',
  `tour_id` varchar(100) NOT NULL,
  `creator` varchar(100) NOT NULL,
  `isPaused` int NOT NULL DEFAULT '0',
  `match_stage` varchar(100) DEFAULT NULL,
  `curr_time` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_prediction`
--

INSERT INTO `tbl_prediction` (`prediction_id`, `first_place`, `second_place`, `third_place`, `created_at`, `full_name`, `email`, `phone_number`, `device_ip`, `tour_id`) VALUES
('01h4rewgeza5g7gs7yvsc048m3', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as4w5160f91sjkx2n4jyg5', '01h4arehbevb541jqqdn7y5y7d', '2023-07-07 15:17:48', 'Nmema patricia', 'default', '09044953569', '105.112.37.202', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h4v9azq3jdsfdpf8npdnq0pn', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as23yzsp0htg2pbqh9384d', '01h4as4ex4y3k8gnfes6gtbkna', '2023-07-08 17:38:34', 'Chidinma', 'default', '09020094499', '41.217.84.124', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h870xp2gzcz8pp63kgw8rye8', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as4ex4y3k8gnfes6gtbkna', '2023-08-19 13:49:28', 'Arinze', 'default', '08169160321', '102.88.34.31', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h877sqjwm047869jcxjc5fp4', '01h4as23yzsp0htg2pbqh9384d', '01h4as5gqqgbjn4av7bgak2tad', '01h4as2w4xzfhrf4dydnj5b596', '2023-08-19 15:49:39', 'Femi Odebiyi', 'default', '08024717968', '105.113.60.213', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h87tsmdgasajg4gxg5jmzqcq', '01h4arfsbeenqgdfvpk4qxbxts', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as2w4xzfhrf4dydnj5b596', '2023-08-19 21:21:38', 'Shittu oluwatobi', 'default', '09056131946', '197.211.58.45', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h898t3pzjsspc9m3atckwjkh', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arehbevb541jqqdn7y5y7d', '01h4as00btdt98nnt8v3r9kzmk', '2023-08-20 10:45:48', 'Jaiyeola Oluwaferanmi Ayomikun', 'default', '08052949408', '102.89.41.73', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h89xxvkddjn7raz4z4c67jkc', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '2023-08-20 16:54:51', 'Ogunsanya Abayomi', 'default', '08022891456', '154.120.109.161', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h8p9q3wzkb54a8baxcrsra6z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as2w4xzfhrf4dydnj5b596', '01h4argvsmaxzw4nkerdpp4vq1', '2023-08-25 12:11:46', 'Muize', 'default', '08089646444', '105.113.81.16', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h8pcbyptwy7e4hn50qeekg4n', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as5gqqgbjn4av7bgak2tad', '2023-08-25 12:58:06', 'Tawose moyoninuoluwa', 'default', '07045003340', '102.134.114.97', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h8vwvq5s49b5fjac4v7jbs5f', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '2023-08-27 16:22:32', 'Ayomide', 'default', '09014364231', '102.23.96.16', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h8z1cd93m6he8y22fw0de69w', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '01h4arp4asbfv9yjvsqk68r0tg', '2023-08-28 21:39:17', 'Ayodeji', 'default', '08038201686', '102.89.47.23', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h94zgs5yh42fw8wse6t6p7y4', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as5gqqgbjn4av7bgak2tad', '2023-08-31 05:02:10', 'Kingsley o', 'default', '08161971237', '131.153.147.50', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98c6961sytvz93f48zw8kkq', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as23yzsp0htg2pbqh9384d', '2023-09-01 12:41:20', 'Smart', 'default', '08061560282', '197.211.58.43', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98shq3xh7hj51n2snp0f269', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as5gqqgbjn4av7bgak2tad', '01h4as2w4xzfhrf4dydnj5b596', '2023-09-01 16:34:47', 'Samuel', 'default', '07036979534', '102.23.96.18', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98sm7eyxh5crmtcy0vnj9f6', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as23yzsp0htg2pbqh9384d', '01h4as2w4xzfhrf4dydnj5b596', '2023-09-01 16:36:09', 'SOWUNMI OLUSEGUN', 'default', '07069472700', '102.89.46.53', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98stp488paab9fhxgkvd9jg', '01h4arp4asbfv9yjvsqk68r0tg', '01h4as5gqqgbjn4av7bgak2tad', '01h4as00btdt98nnt8v3r9kzmk', '2023-09-01 16:39:40', 'Quadri', 'default', '08170224510', '129.205.124.233', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98t4pgsn1za17k2r3srdpvr', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-01 16:45:09', 'Ishola Joseph Olabamijo', 'default', '07030926038', '41.58.59.119', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98tvhj125hhk6w21xncmzpf', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-01 16:57:37', 'Emmanuel marvelous', 'default', '07083286099', '129.205.124.239', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h98x0m36774408n2njnvq6z8', '01h4as5tmte5xry4xeta54wb1t', '01h4arp4asbfv9yjvsqk68r0tg', '01h4as23yzsp0htg2pbqh9384d', '2023-09-01 17:35:21', 'Alexander', 'default', '08052336244', '41.217.74.96', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h994nhvamd3x24x1a4zceh3e', '01h4as2w4xzfhrf4dydnj5b596', '01h4arehbevb541jqqdn7y5y7d', '01h4as4w5160f91sjkx2n4jyg5', '2023-09-01 19:49:07', 'EMMANUEL GODSPOWER', 'default', '08114965140', '129.205.124.232', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9a7nndygfdvefwps11ps6cd', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arp4asbfv9yjvsqk68r0tg', '2023-09-02 06:00:50', 'Victor emetuche', 'default', '08068009657', '102.88.34.130', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9ajftwzcwr93hkk8gj9t855', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-02 09:09:54', 'Marvelous', 'default', '07083286099', '105.113.95.232', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9akt9sb744g9pc0bq2zxd8r', '01h4as23yzsp0htg2pbqh9384d', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4argvsmaxzw4nkerdpp4vq1', '2023-09-02 09:33:05', 'Oluwakayode Soyomokun', 'default', '7085163362', '102.88.35.224', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9am2z81myzt2d55c0zaaf3m', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as4w5160f91sjkx2n4jyg5', '2023-09-02 09:37:49', 'O femi', 'default', '08023420221', '102.23.96.16', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9amsv5rtx7q984a3ngazgqg', '01h4as2w4xzfhrf4dydnj5b596', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4arfsbeenqgdfvpk4qxbxts', '2023-09-02 09:50:19', 'Wale moore', 'default', '07013458193', '102.88.34.129', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9apeew92kcqex0ep0wahhca', '01h4as2w4xzfhrf4dydnj5b596', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arehbevb541jqqdn7y5y7d', '2023-09-02 10:19:03', 'Obinna David', 'default', '08028228980', '102.88.35.195', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9aqx2mzh0de8mpvawy2z8h6', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as00btdt98nnt8v3r9kzmk', '2023-09-02 10:44:31', 'Tyy', 'default', '253', '105.112.109.41', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9ave6s676qahsj5gt6y1dnf', '01h4arfsbeenqgdfvpk4qxbxts', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '2023-09-02 11:46:18', 'oyekan anuoluwapo', 'default', '09132659871', '197.210.71.157', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9azqq5vbdqvrx1vkrmnf8f4', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-02 13:01:24', 'Tunmise', 'default', '08141725793', '197.210.226.174', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9b1bfy5k79nkannn4wpwfgg', '01h4as4w5160f91sjkx2n4jyg5', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '2023-09-02 13:29:40', 'Favour', 'default', '08062536424', '102.89.32.94', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9b2a7s9d5fmys46202k85yn', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as2w4xzfhrf4dydnj5b596', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-02 13:46:28', 'Ogunbanke Oluwasope', 'default', '08082057215', '197.211.58.193', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9c21dax7t5qsz73dvea2haj', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as5gqqgbjn4av7bgak2tad', '01h4as5tmte5xry4xeta54wb1t', '2023-09-02 23:00:53', 'Segun Oyenaya', 'default', '08057647929', '129.205.114.31', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9d6441jg7g8tsb87ry5aqvm', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as5gqqgbjn4av7bgak2tad', '01h4as5tmte5xry4xeta54wb1t', '2023-09-03 09:31:30', 'Igwe uche', 'default', '09038641416', '102.89.45.165', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9dx6je1xhfb5xs9va92m0d2', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arp4asbfv9yjvsqk68r0tg', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-03 16:14:48', 'Akanji ololde', 'default', '08082731019', '102.89.22.159', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9efcj4w2652qegr082zx2b3', '01h4argvsmaxzw4nkerdpp4vq1', '01h4as23yzsp0htg2pbqh9384d', '01h4as4w5160f91sjkx2n4jyg5', '2023-09-03 21:32:38', 'Eze Charles', 'default', '08100710820', '102.89.22.38', '01h4299vwq5mkm8nzdpcdkskmv'),
('01h9fgtkcj3gjvcf27pgc0179d', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as5gqqgbjn4av7bgak2tad', '2023-09-04 07:17:01', 'Nwankerendu Divine', 'default', '08170930121', '102.88.62.201', '01h4299vwq5mkm8nzdpcdkskmv');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_results`
--

INSERT INTO `tbl_results` (`result_id`, `away_team`, `home_team`, `home_score`, `away_score`, `match_stage`, `created_at`, `updated_at`, `tour_id`, `date_played`) VALUES
('01h9arbw05s0f7gcmw45twnmzb', '01h4arfsbeenqgdfvpk4qxbxts', '01h4arehbevb541jqqdn7y5y7d', '1', '2', 'Group_Stage', '2023-09-02 10:52:35', '2023-09-02 10:52:35', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-02'),
('01h9ayjp7q3yg3253de9t5rpap', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arp4asbfv9yjvsqk68r0tg', '0', '8', 'Group_Stage', '2023-09-02 12:41:10', '2023-09-02 12:41:10', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-02'),
('01h9wmbcervbrbw9sth1k8erjx', '01h4as00btdt98nnt8v3r9kzmk', '01h4as2w4xzfhrf4dydnj5b596', '5', '2', 'Group_Stage', '2023-09-09 09:28:45', '2023-09-09 09:28:45', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-09'),
('01h9wv0dtqpxjshhbgtbb5ctdg', '01h4as5gqqgbjn4av7bgak2tad', '01h4as5tmte5xry4xeta54wb1t', '1', '0', 'Group_Stage', '2023-09-09 11:25:06', '2023-09-09 11:25:06', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-09'),
('01h9x0s5tc6g8v0wctqvwymfmz', '01h4as23yzsp0htg2pbqh9384d', '01h4arz4qdwpcpvqy0kafxp8xq', '0', '5', 'Group_Stage', '2023-09-09 13:06:00', '2023-09-09 13:06:00', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-09'),
('01h9x6nwt49y2gn2ndvyy86ede', '01h4as4ex4y3k8gnfes6gtbkna', '01h4as4w5160f91sjkx2n4jyg5', '4', '1', 'Group_Stage', '2023-09-09 14:49:04', '2023-09-09 14:49:04', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-09'),
('01haf0r3bx8r363r68pv296zmq', '01h4as2w4xzfhrf4dydnj5b596', '01h4as23yzsp0htg2pbqh9384d', '0', '0', 'Group_Stage', '2023-09-16 12:51:44', '2023-09-16 12:51:44', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-16'),
('01haf5h862650jwms3kdtxws42', '01h4arp4asbfv9yjvsqk68r0tg', '01h4arfsbeenqgdfvpk4qxbxts', '5', '1', 'Group_Stage', '2023-09-16 14:15:23', '2023-09-16 14:15:23', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-16'),
('01hafa5neakjaj6qq6b7rege0y', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as00btdt98nnt8v3r9kzmk', '3', '0', 'Group_Stage', '2023-09-16 15:36:26', '2023-09-16 15:36:26', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-16'),
('01haff9gwzzdghanefe5081a2t', '01h4arehbevb541jqqdn7y5y7d', '01h4argvsmaxzw4nkerdpp4vq1', '3', '0', 'Group_Stage', '2023-09-16 17:05:55', '2023-09-16 17:05:55', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-16'),
('01hb0mmzjkv7h4ht2z2zngtm0v', '01h4arp4asbfv9yjvsqk68r0tg', '01h4arehbevb541jqqdn7y5y7d', '2', '1', 'Group_Stage', '2023-09-23 09:06:39', '2023-09-23 09:06:39', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-23'),
('01hb0swz821spewyhf355cyasw', '01h4as5tmte5xry4xeta54wb1t', '01h4as4ex4y3k8gnfes6gtbkna', '0', '2', 'Group_Stage', '2023-09-23 10:38:24', '2023-09-23 10:38:24', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-23'),
('01hb0zx05tt74j8hf4n8myx3rp', '01h4as5gqqgbjn4av7bgak2tad', '01h4as4w5160f91sjkx2n4jyg5', '2', '0', 'Group_Stage', '2023-09-23 12:23:16', '2023-09-23 12:23:16', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-23'),
('01hb15fz3etr7tgzm4ewvztk98', '01h4argvsmaxzw4nkerdpp4vq1', '01h4arfsbeenqgdfvpk4qxbxts', '2', '2', 'Group_Stage', '2023-09-23 14:01:00', '2023-09-23 14:01:00', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-23'),
('01hbjpv6v2twfa8fkt4sy164ad', '01h4as00btdt98nnt8v3r9kzmk', '01h4as23yzsp0htg2pbqh9384d', '0', '1', 'Group_Stage', '2023-09-30 09:31:20', '2023-09-30 09:31:20', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-30'),
('01hbk1962tvszvm077hnhp4xqg', '01h4as5gqqgbjn4av7bgak2tad', '01h4as4ex4y3k8gnfes6gtbkna', '1', '4', 'Group_Stage', '2023-09-30 12:33:44', '2023-09-30 12:33:44', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-30'),
('01hbk1a35bj4tjcfcyrv7h182s', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4as2w4xzfhrf4dydnj5b596', '3', '0', 'Group_Stage', '2023-09-30 12:34:13', '2023-09-30 12:34:13', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-30'),
('01hbk7ecyhhpkbyy4384qwczzc', '01h4as4w5160f91sjkx2n4jyg5', '01h4as5tmte5xry4xeta54wb1t', '1', '1', 'Group_Stage', '2023-09-30 14:21:26', '2023-09-30 14:21:26', '01h4299vwq5mkm8nzdpcdkskmv', '2023-09-30'),
('01hc4r2j53sj7ync7kdrm7re4z', '01h4as5gqqgbjn4av7bgak2tad', '01h4argvsmaxzw4nkerdpp4vq1', '1', '2', 'Quarter_Final', '2023-10-07 09:39:09', '2023-10-07 09:39:09', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-07'),
('01hc4xd33bh9326v9pcq0ng48z', '01h4as5tmte5xry4xeta54wb1t', '01h4as2w4xzfhrf4dydnj5b596', '5', '4', 'Quarter_Final', '2023-10-07 11:12:17', '2023-10-07 11:12:17', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-07'),
('01hc51yj4v8qq86r4h10h8ab6e', '01h4arfsbeenqgdfvpk4qxbxts', '01h4as4w5160f91sjkx2n4jyg5', '4', '2', 'Quarter_Final', '2023-10-07 12:31:44', '2023-10-07 12:31:44', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-07'),
('01hc57c9x52fssg6heq13pg1ma', '01h4as23yzsp0htg2pbqh9384d', '01h4as00btdt98nnt8v3r9kzmk', '0', '1', 'Quarter_Final', '2023-10-07 14:06:37', '2023-10-07 14:06:37', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-07'),
('01hcpzfcpd1kn4wn6g9q0cznea', '01h4as2w4xzfhrf4dydnj5b596', '01h4as5gqqgbjn4av7bgak2tad', '8', '9', 'Semi_Final', '2023-10-14 11:34:49', '2023-10-14 11:34:49', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-14'),
('01hcq6dvc9hyt8aqw6nvj63ksq', '01h4as23yzsp0htg2pbqh9384d', '01h4as4w5160f91sjkx2n4jyg5', '1', '3', 'Semi_Final', '2023-10-14 13:36:19', '2023-10-14 13:36:19', '01h4299vwq5mkm8nzdpcdkskmv', '2023-10-14');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standings_cup`
--

CREATE TABLE `tbl_standings_cup` (
  `standing_id` varchar(100) NOT NULL,
  `team_id` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `group_in` varchar(100) DEFAULT NULL,
  `played` int NOT NULL DEFAULT '0',
  `won` int NOT NULL DEFAULT '0',
  `draw` int NOT NULL DEFAULT '0',
  `lose` int NOT NULL DEFAULT '0',
  `goal_diff` int DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `extra_col` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_standings_cup`
--

INSERT INTO `tbl_standings_cup` (`standing_id`, `team_id`, `tour_id`, `group_in`, `played`, `won`, `draw`, `lose`, `goal_diff`, `points`, `created_at`, `updated_at`, `extra_col`) VALUES
('01h4arehczjgp3kwjsrvtzep4p', '01h4arehbevb541jqqdn7y5y7d', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 3, 1, 0, 2, -3, 3, '2023-07-02 07:35:34', '2023-09-23 09:06:39', NULL),
('01h4arfse0zxvxg2dcw4kjhj5z', '01h4arfsbeenqgdfvpk4qxbxts', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 3, 2, 1, 0, 5, 7, '2023-07-02 07:36:15', '2023-09-23 14:01:00', NULL),
('01h4argvykma6fq042sfdn4cgc', '01h4argvsmaxzw4nkerdpp4vq1', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 3, 2, 1, 0, 11, 7, '2023-07-02 07:36:50', '2023-09-23 14:01:00', NULL),
('01h4arp4cvtm2rkcpbprpzbc5k', '01h4arp4asbfv9yjvsqk68r0tg', '01h4299vwq5mkm8nzdpcdkskmv', 'A', 3, 0, 0, 3, -13, 0, '2023-07-02 07:39:42', '2023-09-23 09:06:39', NULL),
('01h4arz4rz4a21zh8fr1qpnj3m', '01h4arz4qdwpcpvqy0kafxp8xq', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 3, 0, 0, 3, -11, 0, '2023-07-02 07:44:38', '2023-09-30 12:34:13', NULL),
('01h4as00ge560kk2k6byme0v6j', '01h4as00btdt98nnt8v3r9kzmk', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 3, 2, 0, 1, 1, 6, '2023-07-02 07:45:06', '2023-09-30 09:31:20', NULL),
('01h4as240svm1zfsyag7tp4t91', '01h4as23yzsp0htg2pbqh9384d', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 3, 1, 1, 1, 4, 4, '2023-07-02 07:46:15', '2023-09-30 09:31:20', NULL),
('01h4as2w9t3khef8yktxqjf1kn', '01h4as2w4xzfhrf4dydnj5b596', '01h4299vwq5mkm8nzdpcdkskmv', 'B', 3, 2, 1, 0, 6, 7, '2023-07-02 07:46:40', '2023-09-30 12:34:13', NULL),
('01h4as4eyh6ntrr9nr8pwccd1t', '01h4as4ex4y3k8gnfes6gtbkna', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 3, 0, 0, 3, -8, 0, '2023-07-02 07:47:32', '2023-09-30 12:33:44', NULL),
('01h4as4w65yax6dx6p30scmv4z', '01h4as4w5160f91sjkx2n4jyg5', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 3, 2, 1, 0, 5, 7, '2023-07-02 07:47:45', '2023-09-30 14:21:26', NULL),
('01h4as5gskm34a2kgnyvxsf978', '01h4as5gqqgbjn4av7bgak2tad', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 3, 1, 0, 2, 0, 3, '2023-07-02 07:48:07', '2023-09-30 12:33:44', NULL),
('01h4as5tp940hxh80rfdhtgv7p', '01h4as5tmte5xry4xeta54wb1t', '01h4299vwq5mkm8nzdpcdkskmv', 'C', 3, 2, 1, 0, 3, 7, '2023-07-02 07:48:17', '2023-09-30 14:21:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_standings_league`
--

CREATE TABLE `tbl_standings_league` (
  `standing_id` varchar(100) NOT NULL,
  `team_id` varchar(100) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `group_in` varchar(100) DEFAULT NULL,
  `played` int NOT NULL DEFAULT '0',
  `won` int NOT NULL DEFAULT '0',
  `draw` int NOT NULL DEFAULT '0',
  `lose` int NOT NULL DEFAULT '0',
  `goal_diff` int DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `extra_col` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subusers`
--

CREATE TABLE `tbl_subusers` (
  `subuser_id` int NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT '1',
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_subusers`
--

INSERT INTO `tbl_subusers` (`subuser_id`, `firstname`, `lastname`, `email`, `password`, `user_id`, `created_at`, `is_active`, `role`) VALUES
(7, NULL, NULL, 'yaba@dlamfa.com', '$2y$10$C5rNw0UDtAEVeXjKx5jVqOMc5eJe9vEtdQF83GqMvPATIsyTy6BQy', '2', '2023-08-28 12:16:45', '1', 'sub'),
(8, NULL, NULL, 'cms@dlamfa.com', '$2y$10$qZG3kWuIsZcPQDeSgEuxLODXMm064P2BOYOsn7Wkbk4ePuTKsPn12', '2', '2023-08-28 12:17:22', '1', 'sub');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teams`
--

CREATE TABLE `tbl_teams` (
  `team_id` varchar(100) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `tour_id` varchar(100) NOT NULL,
  `match_played` int NOT NULL DEFAULT '0',
  `group_in` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL,
  `team_brief` text,
  `team_badge` varchar(100) DEFAULT NULL,
  `team_color` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_teams`
--

INSERT INTO `tbl_teams` (`team_id`, `team_name`, `tour_id`, `match_played`, `group_in`, `address`, `manager`, `created_at`, `updated_at`, `team_brief`, `team_badge`, `team_color`) VALUES
('01h4arehbevb541jqqdn7y5y7d', 'Cathedral group', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'A', NULL, NULL, '2023-07-02 07:35:33', '2023-09-23 09:06:39', NULL, NULL, '#FFD700'),
('01h4arfsbeenqgdfvpk4qxbxts', 'Ijeshatedo', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'A', NULL, NULL, '2023-07-02 07:36:14', '2023-09-23 14:01:00', NULL, NULL, '#B6D0E2'),
('01h4argvsmaxzw4nkerdpp4vq1', 'Mushin', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'A', NULL, NULL, '2023-07-02 07:36:50', '2023-09-23 14:01:00', NULL, NULL, '#A020F0'),
('01h4arp4asbfv9yjvsqk68r0tg', 'Odi-olowo', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'A', NULL, NULL, '2023-07-02 07:39:42', '2023-09-23 09:06:39', NULL, NULL, '#FFA500'),
('01h4arz4qdwpcpvqy0kafxp8xq', 'Aguda', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'B', NULL, NULL, '2023-07-02 07:44:38', '2023-09-30 12:34:13', NULL, NULL, '#FFC0CB'),
('01h4as00btdt98nnt8v3r9kzmk', 'Idi-oro', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'B', NULL, NULL, '2023-07-02 07:45:06', '2023-09-30 09:31:20', NULL, NULL, '#4169e1'),
('01h4as23yzsp0htg2pbqh9384d', 'Ikate', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'B', NULL, NULL, '2023-07-02 07:46:15', '2023-09-30 09:31:20', NULL, NULL, '#722F37'),
('01h4as2w4xzfhrf4dydnj5b596', 'Orile-Coker', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'B', NULL, NULL, '2023-07-02 07:46:40', '2023-09-30 12:34:13', NULL, NULL, '#FAFA33'),
('01h4as4ex4y3k8gnfes6gtbkna', 'ASF', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'C', NULL, NULL, '2023-07-02 07:47:32', '2023-09-30 12:33:44', NULL, NULL, NULL),
('01h4as4w5160f91sjkx2n4jyg5', 'Okota', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'C', NULL, NULL, '2023-07-02 07:47:45', '2023-09-30 14:21:26', NULL, NULL, '#FF0000'),
('01h4as5gqqgbjn4av7bgak2tad', 'Surulere', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'C', NULL, NULL, '2023-07-02 07:48:07', '2023-09-30 12:33:44', NULL, NULL, '#00FF00'),
('01h4as5tmte5xry4xeta54wb1t', 'Yaba', '01h4299vwq5mkm8nzdpcdkskmv', 3, 'C', NULL, NULL, '2023-07-02 07:48:17', '2023-09-30 14:21:26', NULL, NULL, '#FFFF00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tournament`
--

CREATE TABLE `tbl_tournament` (
  `tour_id` varchar(100) NOT NULL,
  `tour_title` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `tour_type` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `user_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `no_of_leagues` int DEFAULT '0',
  `no_of_cups` int DEFAULT '0',
  `role` varchar(100) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `email`, `password`, `firstname`, `lastname`, `no_of_leagues`, `no_of_cups`, `role`) VALUES
(2, 'admin@dlamfa.com', '$2y$10$Ne65TNHnR8dh64PFYDWaTO0eYuXmNSyqvwExPIXMXifL8ttl8F0VW', NULL, NULL, 8, 6, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int UNSIGNED NOT NULL,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
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
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`feedback_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tbl_live`
--
ALTER TABLE `tbl_live`
  MODIFY `live_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_subusers`
--
ALTER TABLE `tbl_subusers`
  MODIFY `subuser_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
