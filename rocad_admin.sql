-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2022 at 09:30 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rocad_admin`
--
CREATE DATABASE IF NOT EXISTS `rocad_admin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rocad_admin`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_date` datetime DEFAULT NULL,
  `user_mail` varchar(32) NOT NULL,
  `passwd` varchar(32) NOT NULL,
  `phone` int(15) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `status` int(1) DEFAULT '1',
  `ranks` varchar(100) DEFAULT NULL,
  `usergroup` int(1) NOT NULL,
  `images` int(100) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `time_date`, `user_mail`, `passwd`, `phone`, `fullname`, `status`, `ranks`, `usergroup`, `images`) VALUES
(1, '2021-03-15 00:00:00', 'munzali@rocad.com', '123', 2147483647, 'Munzali Demo', 2, 'Web Developer', 4, 1657744630),
(13, '2021-03-16 13:03:09', 'auwal@ereg.ng', '123', NULL, 'Auwal Staff', 1, 'Web Developer', 1, 839448361),
(19, '2021-03-18 14:03:19', 'auwal@rocad.com', '123', 2147483647, 'Auwal Sub-admin', 2, 'Staff', 3, 839448361),
(20, '2021-03-15 00:00:00', 'munzali@ereg.ng', '123', 2147483647, 'Munzali Demo', 2, 'Store Keeper', 2, 1657744630),
(21, '2021-03-22 16:03:01', 'admin@rocad.com', '123', NULL, 'Auwal Admin', 1, 'Administrator', 0, 839448361);

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assetname` varchar(50) DEFAULT NULL,
  `partno` varchar(50) DEFAULT NULL,
  `qty` int(9) DEFAULT NULL,
  `asset_type` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `pre_by` int(1) DEFAULT NULL,
  `site` int(9) DEFAULT '1',
  `workfrom` datetime DEFAULT NULL,
  `workto` datetime DEFAULT NULL,
  `worktime` time DEFAULT NULL,
  `workstatus` int(3) DEFAULT NULL,
  `time_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `assetname`, `partno`, `qty`, `asset_type`, `status`, `pre_by`, `site`, `workfrom`, `workto`, `worktime`, `workstatus`, `time_date`) VALUES
(1, 'Buldozer', 'Rc/115', 10, 2, 1, 1, 1, NULL, NULL, NULL, 100, '2021-03-19 14:33:18'),
(2, 'Pick up 4 Tyres', 'Rc/113', 10, 2, 1, 1, 1, NULL, NULL, NULL, 50, '2021-03-19 14:33:21'),
(43, 'Crain', 'RD/09', 2, 2, 1, 0, 1, NULL, NULL, NULL, 0, '2021-03-19 14:33:30'),
(44, 'Cement', '0', 50, 1, 1, 0, 1, NULL, NULL, '12:00:00', NULL, '2021-03-19 14:33:43'),
(45, 'Aspalt', '0', 50, 1, 1, 0, 1, NULL, NULL, NULL, NULL, '2021-03-19 14:19:37'),
(46, 'Rocks', '0', 2, 1, 1, 0, 1, NULL, NULL, NULL, NULL, '2021-03-19 14:19:37'),
(47, 'Pick-up 16 Tyres', 'RD/098', 2, 2, 1, 1, 1, NULL, NULL, NULL, NULL, '2021-03-19 15:02:06'),
(48, 'Tanke/Truck', 'RD/0987', 2, 2, 1, 1, 1, NULL, NULL, NULL, 50, '2021-03-19 15:21:27'),
(49, 'DAF', 'RD/098', 4, 2, 1, 1, 1, NULL, NULL, NULL, 50, '2021-03-22 11:28:12'),
(50, 'Canter', 'R0/12', 2, 2, 1, 1, 1, NULL, NULL, NULL, 100, '2021-03-23 16:29:07'),
(51, 'Tipper', 'TP116', 1, 2, 1, 1, 1, NULL, NULL, NULL, 100, '2021-04-14 10:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `daily_plant_reports`
--

CREATE TABLE IF NOT EXISTS `daily_plant_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `reporting_staff` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `daily_plant_reports`
--

INSERT INTO `daily_plant_reports` (`id`, `location`, `report_date`, `reporting_staff`, `datetime`) VALUES
(1, 1, '2021-06-01', 13, '2021-06-04 15:51:09'),
(2, 2, '2021-06-02', 19, '2021-06-04 15:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `daily_plant_reports_details`
--

CREATE TABLE IF NOT EXISTS `daily_plant_reports_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `machine_no` varchar(100) NOT NULL,
  `machine_name` varchar(255) NOT NULL,
  `site_id` int(11) NOT NULL,
  `report_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `daily_plant_reports_details`
--

INSERT INTO `daily_plant_reports_details` (`id`, `report_id`, `machine_no`, `machine_name`, `site_id`, `report_description`) VALUES
(1, 1, 'RCD0001', 'Buldozer', 2, 'This machine is in good condition.');

-- --------------------------------------------------------

--
-- Table structure for table `new_users`
--

CREATE TABLE IF NOT EXISTS `new_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `user_role` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `new_users`
--

INSERT INTO `new_users` (`id`, `fullname`, `email`, `password`, `phone`, `address`, `gender`, `job_title`, `user_role`, `date_time`, `status`) VALUES
(1, 'Auwal Admin', 'admin@rocad.com', 'JKgILjRCgi8ZtcTj1NV5Pro7pbOP9kXqn9bE9+7nrWUTm1J+KbMwBhO6eQu1xuhwAvHkL/1ch+3LtCIFw4mxHw==', '08033445566', 'No 50 Sokoto Road, Kano', 'Male', 'Administrator', 1, '2021-06-03 03:02:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rocad_site`
--

CREATE TABLE IF NOT EXISTS `rocad_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(200) DEFAULT NULL,
  `site_loc` varchar(50) DEFAULT NULL,
  `site_state` varchar(15) DEFAULT NULL,
  `site_lga` varchar(15) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `pre_by` varchar(50) DEFAULT NULL,
  `time_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rocad_site`
--

INSERT INTO `rocad_site` (`id`, `sitename`, `site_loc`, `site_state`, `site_lga`, `status`, `pre_by`, `time_date`) VALUES
(1, 'Main Office', 'Sokoto Road', 'Kano', 'Tarauni', 1, '1', '2021-03-17 05:12:11'),
(2, 'Yahaya Gusau', 'NNDC Quaters', 'Kano', 'Gwale', 13, '1', '2021-03-10 00:00:00'),
(6, 'Dan Agundi', 'Kano Centeral', 'Kano', 'Nasarawa', 1, '1', '2021-03-18 09:03:47'),
(7, 'Adamawa', 'Numan', 'Adamawa', 'Hong', 1, '1', '2021-03-18 09:03:23'),
(8, '', '', '', '', 1, 'Munzali Demo', '2021-03-31 07:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) DEFAULT NULL,
  `email` varchar(35) DEFAULT NULL,
  `phone` int(12) DEFAULT NULL,
  `addr` varchar(50) DEFAULT NULL,
  `site` int(9) DEFAULT NULL,
  `cat` int(9) DEFAULT NULL,
  `usergroup` int(9) DEFAULT NULL,
  `pre_by` int(9) DEFAULT NULL,
  `time_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `fname`, `email`, `phone`, `addr`, `site`, `cat`, `usergroup`, `pre_by`, `time_date`) VALUES
(1, '', 'm@e', 990, 'jkkjjkj', 2, 1, 0, 0, '2021-03-18 12:03:43'),
(3, 'k', 'm@m.com', 2147483647, 'jkjhjh', 2, 3, 0, 1, '2021-03-18 12:03:20'),
(4, 'k', 'm@m.com', 2147483647, 'nhjjh', 1, 1, 0, 1, '2021-03-18 12:03:41'),
(5, 'm', 'm2@adws', 908998, 'jhhjhj', 1, 1, 0, 1, '2021-03-18 13:03:22'),
(6, 'm', 'm2@adw', 908998, 'jhhjhj', 1, 1, 0, 1, '2021-03-18 13:03:15'),
(7, 'Musa Hassan', 'musa@rocad.comk', 2147483647, 'Gwmmajajjajaja', 2, 2, 0, 1, '2021-03-18 13:03:04'),
(8, 'Musa Hassan', 'musa@rocad.com', 2147483647, 'Gwmmajajjajaja', 2, 2, 0, 1, '2021-03-18 13:03:37'),
(9, 'Musa Hassan', 'munzalihassan27@gmail.com', 807665433, 'hjhjyhhj', 6, 3, 0, 1, '2021-03-18 14:03:25'),
(10, 'Auwal Muhammad', 'auwal@rocad.com', 2147483647, 'No 2', 1, 5, 0, 1, '2021-03-18 14:03:19');

-- --------------------------------------------------------

--
-- Table structure for table `staff_cat`
--

CREATE TABLE IF NOT EXISTS `staff_cat` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pre_by` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `staff_cat`
--

INSERT INTO `staff_cat` (`id`, `cat_name`, `time_date`, `pre_by`) VALUES
(1, 'Full-time', '2021-03-18 11:52:32', 1),
(2, 'Part-time', '2021-03-18 11:52:32', 1),
(3, 'Casual', '2021-03-18 11:52:59', 1),
(4, 'Fixed term', '2021-03-18 11:52:59', 1),
(5, 'Shiftworkers', '2021-03-18 11:53:22', 1),
(6, 'Daily hire and weekly hire', '2021-03-18 11:53:22', 1),
(7, 'Probation', '2021-03-18 11:53:57', 1),
(8, 'Apprentices and trainees', '2021-03-18 11:53:57', 1),
(9, 'Outworkers', '2021-03-18 11:54:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `storeloadingdetails`
--

CREATE TABLE IF NOT EXISTS `storeloadingdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrip` varchar(100) NOT NULL,
  `partno` varchar(50) NOT NULL,
  `unit` int(50) NOT NULL,
  `qty` int(9) NOT NULL,
  `unitprice` int(50) NOT NULL,
  `totalvalue` int(50) NOT NULL,
  `conditions` varchar(100) NOT NULL,
  `preby` int(9) NOT NULL,
  `fromsite` int(9) NOT NULL,
  `tosite` int(9) NOT NULL,
  `method` varchar(100) NOT NULL,
  `reference` int(100) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  `time_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `storeloadingdetails`
--

INSERT INTO `storeloadingdetails` (`id`, `descrip`, `partno`, `unit`, `qty`, `unitprice`, `totalvalue`, `conditions`, `preby`, `fromsite`, `tosite`, `method`, `reference`, `note`, `status`, `time_date`) VALUES
(5, '48', '78', 5, 2, 50, 15, '1', 1, 7, 6, 'By Train', 91695915, 'Welcome', 2, '2021-03-22 14:01:55'),
(6, '45', '8', 8, 8, 8, 8, '1', 1, 7, 6, 'By Train', 59555397, 'Go agead', 2, '2021-03-22 14:02:11'),
(7, '45', '8', 8, 8, 8, 8, '1', 1, 7, 6, 'By Train', 59555397, 'Go agead', 2, '2021-03-22 14:02:15'),
(8, '45', '8', 8, 8, 8, 8, '1', 1, 7, 6, 'By Train', 59555397, 'Go agead', 2, '2021-03-22 14:02:20'),
(9, '1', 'RD/098', 1, 2, 500, 100, '1', 1, 7, 2, 'By Road', 20695126, '', 4, '2021-03-23 16:09:35'),
(10, '1', 'RD/09', 5, 2, 50, 15, '1', 1, 1, 2, 'By Train', 99885709, 'Request of Moving Asset', 0, '2021-03-20 15:54:24'),
(11, '44', '78', 5, 2, 50, 15, '1', 1, 7, 6, 'By Train', 98870963, '', 4, '2021-03-24 13:13:39'),
(12, '2', '123', 5, 1, 1, 2, '1', 19, 7, 1, 'By road', 2040062, 'Go ahead', 2, '2021-03-22 14:02:45'),
(13, '47', '456', 5, 1, 1, 2, '2', 19, 7, 1, 'By road', 2040062, 'Go ahead', 2, '2021-03-22 14:02:52'),
(14, '48', '123', 10, 1, 1, 20, '1', 19, 1, 7, 'By road', 12103501, '', 4, '2021-03-23 16:09:11'),
(15, '43', '456', 5, 2, 2, 10, '2', 19, 1, 7, 'By road', 12103501, '', 4, '2021-03-23 16:09:11'),
(16, '48', 'RC/9099', 5, 1, 1, 20, 'Old', 13, 7, 1, 'By road', 49551003, 'Request of Moving Asset', 0, '2021-03-23 15:53:15'),
(17, '1', 'AR10', 1, 2, 500, 500, 'New', 13, 7, 6, 'By Road', 95506079, 'Request of Moving Asset', 0, '2021-03-24 11:40:22'),
(18, '2', 'AR10', 6, 100, 5, 6, 'Old', 13, 7, 6, 'By Road', 38058686, 'recieved', 2, '2021-03-24 11:48:41'),
(19, '1', '321', 321, 12, 120, 23, 'New', 13, 6, 1, 'By Road', 42008351, 'mmmnnnkkk', 1, '2021-03-24 13:14:42'),
(20, '2', '12', 32, 12, 1, 4, 'Old', 13, 6, 1, 'By Road', 42008351, 'mmmnnnkkk', 1, '2021-03-24 13:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `usergroups` varchar(50) NOT NULL,
  `permission` int(9) NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `usergroups`, `permission`, `time_date`) VALUES
(1, 'Admin', 4, '2021-03-20 16:58:50'),
(2, 'Sub-admin', 3, '2021-03-20 17:17:14'),
(3, 'Staff', 2, '2021-03-20 17:17:30'),
(6, 'Storekeeper', 1, '2021-03-24 11:11:13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
