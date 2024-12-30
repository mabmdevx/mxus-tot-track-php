-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 08:34 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tot_track`
--

-- --------------------------------------------------------

--
-- Table structure for table `tt_babies`
--

CREATE TABLE `tt_babies` (
  `tt_baby_id` bigint(20) NOT NULL,
  `tt_baby_uuid` varchar(255) NOT NULL,
  `tt_baby_name` varchar(255) NOT NULL,
  `tt_user_id` bigint(20) NOT NULL,
  `tt_selected_baby_per_user` tinyint(1) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tt_babies`
--

INSERT INTO `tt_babies` (`tt_baby_id`, `tt_baby_uuid`, `tt_baby_name`, `tt_user_id`, `tt_selected_baby_per_user`, `created_on`, `updated_on`) VALUES
(1, '52114e76-df39-426c-8297-4d2492012631', 'BabyA', 1, 1, '2024-12-29 10:12:49', '2024-12-30 00:42:47')

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tt_babies`
--
ALTER TABLE `tt_babies`
  ADD PRIMARY KEY (`tt_baby_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tt_babies`
--
ALTER TABLE `tt_babies`
  MODIFY `tt_baby_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tt_users`
--

CREATE TABLE `tt_users` (
  `tt_user_id` bigint(20) NOT NULL,
  `tt_user_uuid` varchar(255) NOT NULL,
  `tt_username` varchar(255) NOT NULL,
  `tt_name` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tt_users`
--

INSERT INTO `tt_users` (`tt_user_id`, `tt_user_uuid`, `tt_username`, `tt_name`, `created_on`, `updated_on`) VALUES
(1, 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'admin', 'admin', '2024-12-29 23:49:39', '2024-12-29 22:50:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tt_users`
--
ALTER TABLE `tt_users`
  ADD PRIMARY KEY (`tt_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tt_users`
--
ALTER TABLE `tt_users`
  MODIFY `tt_user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

ALTER TABLE `tt_events` ADD `tt_baby_id` BIGINT NOT NULL DEFAULT '1' AFTER `tt_event_id`; 

ALTER TABLE `tt_event_sessions` ADD `tt_baby_id` BIGINT NOT NULL DEFAULT '1' AFTER `tt_es_id`; 


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;