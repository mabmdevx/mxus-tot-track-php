-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2024 at 11:12 PM
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
CREATE DATABASE IF NOT EXISTS `tot_track` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tot_track`;

-- --------------------------------------------------------

--
-- Table structure for table `tt_events`
--

CREATE TABLE `tt_events` (
  `tt_event_id` bigint(10) NOT NULL,
  `tt_event_date` date DEFAULT NULL,
  `tt_event_time` time DEFAULT NULL,
  `tt_event_val_raw` varchar(255) NOT NULL,
  `tt_event_val_parsed` varchar(255) NOT NULL,
  `tt_event_notes` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tt_event_sessions`
--

CREATE TABLE `tt_event_sessions` (
  `tt_es_id` bigint(10) NOT NULL,
  `tt_es_event_id_start` int(11) NOT NULL DEFAULT 0,
  `tt_es_event_id_end` int(11) NOT NULL DEFAULT 0,
  `tt_es_date` date NOT NULL,
  `tt_es_type` varchar(255) NOT NULL,
  `tt_es_time_start` datetime NOT NULL,
  `tt_es_time_end` datetime DEFAULT NULL,
  `tt_es_time_duration` int(11) NOT NULL DEFAULT 0,
  `tt_es_feed_side` int(11) NOT NULL DEFAULT 0,
  `tt_es_feed_self_detach` tinyint(1) NOT NULL DEFAULT 0,
  `tt_es_dc_type` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tt_events`
--
ALTER TABLE `tt_events`
  ADD PRIMARY KEY (`tt_event_id`);

--
-- Indexes for table `tt_event_sessions`
--
ALTER TABLE `tt_event_sessions`
  ADD PRIMARY KEY (`tt_es_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tt_events`
--
ALTER TABLE `tt_events`
  MODIFY `tt_event_id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tt_event_sessions`
--
ALTER TABLE `tt_event_sessions`
  MODIFY `tt_es_id` bigint(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
