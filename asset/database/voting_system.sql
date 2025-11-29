-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 02:12 PM
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
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT 'default_photo.jpg',
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `position`, `photo`, `bio`, `created_at`, `is_active`) VALUES
(30, 'Kiko Barzaga', 'President', 'default_photo.jpg', '', '2025-11-12 15:38:52', 1),
(31, 'Thirdy Barzaga', 'President', 'default_photo.jpg', '', '2025-11-12 15:39:02', 1),
(32, 'aljur abrenica', 'Vice President', 'default_photo.jpg', '', '2025-11-12 15:39:26', 1),
(33, 'Sarah Discaya', 'Vice President', 'default_photo.jpg', '', '2025-11-12 15:39:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `election_settings`
--

CREATE TABLE `election_settings` (
  `id` int(11) NOT NULL,
  `election_title` varchar(255) NOT NULL,
  `election_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `election_settings`
--

INSERT INTO `election_settings` (`id`, `election_title`, `election_description`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 'Test Election 2025', 'election', '2025-11-16', '2025-11-29', 1, '2025-11-10 20:30:44');

-- --------------------------------------------------------

--
-- Table structure for table `suspension_logs`
--

CREATE TABLE `suspension_logs` (
  `id` int(11) NOT NULL,
  `voter_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `suspended_by` int(11) DEFAULT NULL,
  `suspended_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suspension_logs`
--

INSERT INTO `suspension_logs` (`id`, `voter_id`, `reason`, `suspended_by`, `suspended_at`) VALUES
(5, 14, 'test', 1, '2025-11-16 13:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `name`, `email`, `password`, `address`, `phone`, `age`, `created_at`, `status`) VALUES
(8, 'Margolis, jane a', 'margolis@gmail.com', '123', 'blk 32', '091231', 23, '2025-11-12 16:12:47', 'active'),
(13, 'Doe, John T', 'john@gmail.com', '123', 'BLOCK 92 adjg', '091231', 19, '2025-11-16 12:59:38', 'active'),
(14, 'Test, test T', 'test@gmail.com', '123', 'BLOCK 32', '0911231', 23, '2025-11-16 13:00:33', 'active'),
(15, 'Garcia, Ced E', 'ced1@gmail.com', '123', 'BLOCK 112', '0911231', 19, '2025-11-16 13:01:01', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `position` varchar(50) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `candidate_id`, `position`, `voted_at`) VALUES
(50, 15, 31, 'President', '2025-11-16 13:01:14'),
(51, 15, 33, 'Vice President', '2025-11-16 13:01:14'),
(52, 14, 31, 'President', '2025-11-16 13:02:52'),
(53, 14, 33, 'Vice President', '2025-11-16 13:02:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election_settings`
--
ALTER TABLE `election_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suspension_logs`
--
ALTER TABLE `suspension_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voter_id` (`voter_id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `election_settings`
--
ALTER TABLE `election_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suspension_logs`
--
ALTER TABLE `suspension_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `voters`
--
ALTER TABLE `voters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
