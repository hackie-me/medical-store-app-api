-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2022 at 06:28 PM
-- Server version: 8.0.27
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_hash` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'https://api.multiavatar.com/stefan.svg',
  `remember_token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mail_hash_unique` (`mail_hash`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone`, `username`, `status`, `email`, `mail_hash`, `email_verified_at`, `password`, `social_id`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(56, 'yash', 'Kanajariya', 'kalyanpur', 'jamanagar', 'gujarat', '361320', '95105d22501', 'yadsh', 'pending', 'yasdh@mail.com', '52f78afeadcc3de04f693224d3dd0116', NULL, '$2y$10$zN512wNd/9Bw8zUCDp2UNOwhw/kA1nFyw0CxhZ1ZhRq.5llv50GWS', NULL, 'https://api.multiavatar.com/stefan.svg', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2Njg1MjI1MjgsImRhdGEiOnsidXNlcmlkIjo1NiwiZmlyc3RfbmFtZSI6Inlhc2giLCJsYXN0X25hbWUiOiJLYW5hamFyaXlhIiwiYWRkcmVzcyI6ImthbHlhbnB1ciIsImNpdHkiOiJqYW1hbmFnYXIiLCJzdGF0ZSI6Imd1amFyYXQiLCJ6aXAiOiIzNjEzMjAiLCJwaG9uZSI6Ijk1MTA1ZDIyNTAxIiwidXNlcm5hbWUiOiJ5YWRzaCIsInN0YXR1cyI6InBlbmRpbmciLCJlbWFpbCI6Inlhc2RoQG1haWwuY29tIiwibWFpbF9oYXNoIjoiNTJmNzhhZmVhZGNjM2RlMDRmNjkzMjI0ZDNkZDAxMTYiLCJlbWFpbF92ZXJpZmllZF9hdCI6bnVsbCwicGFzc3dvcmQiOiIkMnkkMTAkek41MTJ3TmQvOUJ3OHpVQ0RwMlVOT3dody9rQTFuRnl3MEN4aFoxWmhScS41bGx2NTBHV1MiLCJzb2NpYWxfaWQiOm51bGwsImF2YXRhciI6Imh0dHBzOi8vYXBpLm11bHRpYXZhdGFyLmNvbS9zdGVmYW4uc3ZnIiwicmVtZW1iZXJfdG9rZW4iOiIiLCJjcmVhdGVkX2F0IjoiMjAyMi0xMS0xNSAxOTo1ODo0OCIsInVwZGF0ZWRfYXQiOiIyMDIyLTExLTE1IDE5OjU4OjQ4In19.9GwCXNOxvjRmHO49QQeLidCJ4kCu9eHAgU4NE4111yFogDutPkm7TDEI3jtg1DrS-W_Vj0ys5_JLCULa86WdWA', '2022-11-15 14:28:48', '2022-11-15 14:28:48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
