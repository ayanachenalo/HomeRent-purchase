-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 04:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `house_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'ayana', 'ayana1234'),
(2, 'ayana', 'ayana1234');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `house_type` enum('Apartment','Villa','Single Room','Studio') DEFAULT 'Villa',
  `status` enum('Available','Rented') DEFAULT 'Available',
  `main_image` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `owner_id`, `title`, `description`, `price`, `city`, `address`, `house_type`, `status`, `main_image`, `video_path`, `created_at`) VALUES
(2, 2, 'grocery', 'mana daabboo bareedadha karaa makiinatti dhiyoodha', 4000.00, 'Nekemte', NULL, 'Villa', 'Available', 'IMG_1776539353_127.jpg', NULL, '2026-04-18 19:09:13'),
(3, 2, 'mana jireenya', 'balballi isaa karaa lamaanin karaatti baha', 5000.00, 'bule horaa', NULL, 'Villa', 'Available', 'IMG_1776539798_298.jpg', NULL, '2026-04-18 19:16:38'),
(5, 2, 'mana jireenya', 'mana bareedadha', 200.00, 'Ambo', NULL, 'Villa', 'Available', 'IMG_1776788716_320.jpg', NULL, '2026-04-21 16:25:16'),
(6, 2, 'hotela', 'hoteela gaaridha', 2000.00, 'najo', NULL, 'Villa', 'Available', 'IMG_1776788763_256.jpg', NULL, '2026-04-21 16:26:03'),
(7, 2, 'mana jireenya', 'kmana bareeda', 4000.00, 'Ambo', NULL, 'Villa', 'Available', 'IMG_1776788838_459.jpg', NULL, '2026-04-21 16:27:18'),
(9, 2, 'cafteeria bareeda', 'kafteeria bareedaa fi bakka prkin qaba', 30000.00, 'finfinne', NULL, 'Villa', 'Rented', 'IMG_1776793684_158.jpg', 'VID_1776793684_822.mp4', '2026-04-21 17:48:04'),
(11, 2, '   Good hotel', 'hoteela bareeda karra konkolaata irradha bakka gabaa qabudha', 40000.00, 'bale robe', NULL, 'Villa', 'Available', 'IMG_1778697894_952.jpg', 'VID_1778697894_697.mp4', '2026-05-13 18:44:54'),
(12, 2, 'resturaant', 'restuurantiif nii taha odeeffannoo dabalattaaf nii bilbiluu dandeess gurgurachuun barbaada', 2000000.00, 'Gimbi', NULL, 'Villa', 'Available', 'IMG_1778698238_178.jpg', 'VID_1778698238_897.mp4', '2026-05-13 18:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `house_id`, `message`, `status`, `created_at`) VALUES
(1, 2, 2, 7, 'mana keessan barbaaden ture', '1', '2026-04-27 18:17:01'),
(2, 1, 2, 6, 'mana keessanin barbaade', 'unread', '2026-04-27 18:19:55'),
(3, 2, 2, 6, 'adsdahgdjsdvbghjkl;', 'unread', '2026-04-27 18:21:23'),
(4, 2, 2, 6, 'adsdahgdjsdvbghjkl;', 'unread', '2026-04-27 18:26:50'),
(5, 2, 2, 6, 'aasdvfghj', 'unread', '2026-04-27 18:38:48'),
(6, 3, 2, 3, 'mana jireenyaaf tahun barbaade\r\n', '1', '2026-04-27 18:54:41'),
(7, 3, 2, 3, 'mana jireenyaaf tahun barbaade\r\n', '1', '2026-04-27 18:55:25'),
(8, 3, 2, 3, 'dsfgljhgfd', '1', '2026-04-27 18:57:46'),
(9, 2, 2, 7, 'azsxtdycfgvhbjkl;', '1', '2026-04-27 19:22:09'),
(10, 2, 2, 8, 'asfhjkl/', '1', '2026-04-28 06:19:03'),
(11, 2, 1, 6, 'maal rakkoo qabare fudhadhu intalo koo kottuu fudhuu', '1', '2026-04-28 06:39:59'),
(12, 2, 2, 6, 'wertyuiolmcvbnm', 'unread', '2026-04-28 07:05:47'),
(13, 1, 2, 2, 'dafghjkl;a\'sdfvbkhjkml', 'unread', '2026-04-30 16:27:18'),
(14, 1, 2, 4, 'sdfghj', '1', '2026-04-30 16:27:28'),
(15, 1, 2, 5, 'mana keessa natti sTI KAM DHUFEE SIN ARGU DANDA\'A', '1', '2026-05-01 11:52:38'),
(16, 1, 2, 8, 'gfhgsjdnjgjb', '1', '2026-05-02 12:22:41'),
(17, 1, 2, 7, 'asdgvbvx', '1', '2026-05-02 12:24:19'),
(18, 8, 2, 2, 'mana keessan barbaadeenture', '1', '2026-05-02 18:14:30'),
(19, 8, 2, 1, 'mana keessan', 'unread', '2026-05-02 18:18:26'),
(20, 8, 2, 6, 'mana keessan', 'unread', '2026-05-02 18:19:26'),
(21, 1, 2, 8, 'mana keessan na barbaada', '1', '2026-05-02 18:20:53'),
(22, 1, 2, 6, 'tole beqa dhufeen fudhadha', 'unread', '2026-05-02 18:28:05'),
(23, 2, 2, 8, 'mana sana dhufte fudhatmo nama biraa kennu', '1', '2026-05-02 20:10:49'),
(24, 1, 2, 7, 'azdsgxfchgvmb', '1', '2026-05-02 20:20:15'),
(25, 2, 2, 7, 'mana haadhe godhu dur sin barbaada', '1', '2026-05-02 20:44:59'),
(26, 2, 2, 6, 'gaddhj', 'unread', '2026-05-02 20:54:44'),
(27, 2, 1, 5, 'gdbajjm', '1', '2026-05-02 20:54:56'),
(28, 1, 2, 7, 'amma ergaa barreessun danda\'a', '1', '2026-05-04 06:35:44'),
(29, 1, 2, 7, 'rakko hin qabu fudhadhu ', '1', '2026-05-04 07:37:11'),
(30, 6, 2, 6, 'abeet manni kee nii bareeda', '1', '2026-05-04 11:04:21'),
(31, 1, 2, 5, 'sdrtfvygbuhnjmk,l;', '1', '2026-05-04 11:05:48'),
(32, 2, 1, 5, 'wegsrtgdfhvnmtsgrbfx', '1', '2026-05-04 11:06:29'),
(33, 2, 1, 7, 'raoo hin qabu guyya jimaata manan jira kottu', '1', '2026-05-04 12:13:29'),
(34, 1, 2, 5, 'xvcxxv ', '1', '2026-05-05 11:50:59'),
(35, 2, 1, 5, 'tole nii fudhata rakkoo hin qabu', '1', '2026-05-05 11:53:14');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `house_id`, `user_email`, `reason`, `description`, `status`, `created_at`) VALUES
(1, 0, 'talile@gmail.com', 'Scam', 'Worku edisa nama janajana asirratti ', 'Pending', '2026-05-12 20:11:08'),
(2, 4, 'talile@gmail.com', 'Scam', 'manni kun sirri miti', 'Pending', '2026-05-12 20:28:31'),
(3, 4, 'talile@gmail.com', 'Scam', 'bduamsb', 'Pending', '2026-05-12 20:45:53'),
(8, 0, 'talile@gmail.com', 'Wrong Info', 'asdfghjkl;', 'Pending', '2026-05-12 22:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('tenant','owner','admin') NOT NULL DEFAULT 'tenant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone_number`, `password`, `role`, `created_at`) VALUES
(1, 'talile chaloma', 'talile@gmail.com', '094977', '$2y$10$GuUpYczUyUuX5S3l9P8Rhu6twQFYvOlozn6tMZmotkOq18Rz0Vvly', 'tenant', '2026-04-17 17:07:33'),
(2, 'ayana canalo', 'ayana@gmail.com', '093208', '$2y$10$Kftdf67kpK/3g9KTOj5Xgu1w3du.fQFfp8Xw65zKiEK1Sf7/uYFba', 'owner', '2026-04-17 17:39:39'),
(3, 'Worku  edisa', 'w@gmail.com', '0919', '$2y$10$fDHtbkub3hO8dXEXPaakE.sQR8u8cEhJLXxeSzcjpnUtSYVfI4O6C', 'owner', '2026-04-27 18:48:21'),
(6, 'guutu diinsa', 'Gutu@gmail.com', '0932080134', '$2y$10$b9Qj1bjLr.tAq1McNCFyxua9GWMUCtuwOy.9mklZfYhyvfPze9D2K', 'owner', '2026-05-02 16:53:38'),
(8, 'caala tolasa', 'caala@gmail.com', '0938672310', '$2y$10$n0fDtWn3C1HhXrajah9.gOoq3kaoJnH.dLtUKlA0UBsg8Js1wSF.W', 'tenant', '2026-05-02 17:09:05'),
(10, 'dagi tolera', 'dagi@gmail.com', '0965732315', '$2y$10$9h9k7PfNIJNPUSK.QGonMuW0JCsHF4KudkgTNwVgt7dnq37DLvv4W', 'tenant', '2026-05-02 17:21:55'),
(11, 'robera temasgen', 'd@gmail.com', '0932547628', '$2y$10$1jfdqw6WfyWzRl6Eog2hNeyv.mtF4RZuZqBmLMwWydr623hU/T6Bq', 'tenant', '2026-05-02 17:23:38'),
(12, 'Ebisa caala', 'e@gmail.com', '0932080143', '$2y$10$6yPQuYOjGq/oEGOIp7aTQeculr89aOWt7LiTzlqLgwUEg9o2P34na', 'tenant', '2026-05-02 17:25:59'),
(14, 'daga caala', 'da@gmail.com', '09678493', '$2y$10$mdr.MiFhdenXj1HUnfZtDe5sQj9tqd/fOZcLtUCKTR3LjSK8b3IVK', 'tenant', '2026-05-02 17:34:22'),
(16, 'caala', 'c@gmmail.com', '0932080143', '$2y$10$Um3Y1Q78/Lk6K47TnqNtnuevsqVxOjpJUcCw0ZBiAs2gXZmhd839u', 'owner', '2026-05-05 11:57:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
