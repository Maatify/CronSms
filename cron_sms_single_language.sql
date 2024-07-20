-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2024 at 10:36 AM
-- Server version: 5.7.41
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maatify`
--

-- --------------------------------------------------------

--
-- Table structure for table `cron_sms`
--

CREATE TABLE `cron_sms` (
    `cron_id` int(11) NOT NULL,
    `type_id` int(11) NOT NULL DEFAULT '1' COMMENT '1=message; 2=confirm; 3=Password',
    `recipient_id` int(11) NOT NULL DEFAULT '0',
    `recipient_type` varchar(64) NOT NULL DEFAULT '',
    `phone` varchar(128) NOT NULL DEFAULT '',
    `message` mediumtext,
    `record_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `sent_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_sms`
--
ALTER TABLE `cron_sms`
  ADD PRIMARY KEY (`cron_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron_sms`
--
ALTER TABLE `cron_sms`
  MODIFY `cron_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
