-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2020 at 06:35 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `check_consign`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_access`
--

CREATE TABLE `tbl_access` (
  `id_access` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL,
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `add` tinyint(1) NOT NULL DEFAULT '0',
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `print` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_access`
--

INSERT INTO `tbl_access` (`id_access`, `id_menu`, `id_profile`, `view`, `add`, `edit`, `delete`, `print`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1, 1, 1),
(3, 3, 1, 1, 1, 1, 1, 1),
(4, 4, 1, 1, 1, 1, 1, 1),
(5, 5, 1, 1, 1, 1, 1, 1),
(6, 6, 1, 1, 1, 1, 1, 1),
(7, 7, 1, 1, 1, 1, 1, 1),
(8, 8, 1, 1, 1, 1, 1, 1),
(9, 9, 1, 1, 1, 1, 1, 1),
(10, 10, 2, 1, 1, 1, 1, 1),
(11, 1, 2, 1, 1, 1, 1, 1),
(12, 2, 2, 1, 1, 1, 1, 1),
(13, 3, 2, 1, 1, 1, 1, 1),
(14, 4, 2, 1, 1, 1, 1, 1),
(15, 5, 2, 1, 1, 1, 1, 1),
(16, 6, 2, 1, 1, 1, 1, 1),
(17, 7, 2, 1, 1, 1, 1, 1),
(18, 8, 2, 1, 1, 1, 1, 1),
(19, 9, 2, 1, 1, 1, 1, 1),
(20, 10, 2, 1, 1, 1, 1, 1),
(21, 1, 3, 1, 1, 1, 1, 1),
(22, 2, 3, 0, 0, 0, 0, 0),
(23, 3, 3, 0, 0, 0, 0, 0),
(24, 4, 3, 0, 0, 0, 0, 0),
(25, 5, 3, 0, 0, 0, 0, 0),
(26, 6, 3, 0, 0, 0, 0, 0),
(27, 7, 3, 0, 0, 0, 0, 0),
(28, 8, 3, 0, 0, 0, 0, 0),
(29, 9, 3, 0, 0, 0, 0, 0),
(30, 10, 3, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_check`
--

CREATE TABLE `tbl_check` (
  `id_check` int(11) NOT NULL,
  `reference` varchar(32) NOT NULL,
  `subject` text NOT NULL,
  `location_code` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date_open` datetime NOT NULL,
  `date_close` datetime DEFAULT NULL,
  `import` tinyint(1) NOT NULL DEFAULT '0',
  `employee` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not close, 1 = closed',
  `pause` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = pause, 0 = not pause',
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remark` text,
  `allow_input_qty` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_check_detail`
--

CREATE TABLE `tbl_check_detail` (
  `id_check_detail` int(11) NOT NULL,
  `id_check` int(11) NOT NULL,
  `barcode` varchar(32) NOT NULL,
  `qty` tinyint(1) NOT NULL DEFAULT '1',
  `id_employee` int(5) NOT NULL DEFAULT '1',
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_config`
--

CREATE TABLE `tbl_config` (
  `id_config` int(11) NOT NULL,
  `config_name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_config`
--

INSERT INTO `tbl_config` (`id_config`, `config_name`, `value`, `id_employee`, `date_upd`) VALUES
(1, 'ALLOW_UNDER_ZERO', '0', 1, '2015-04-08 08:54:16'),
(2, 'MULTI_LANG', '1', 1, '2015-04-21 02:24:07'),
(3, 'PER_PAGE', '50', 0, '2016-02-04 08:16:58'),
(4, 'PREFIX_ORDER', 'IV', 1, '2016-02-10 04:12:27'),
(5, 'COM_CODE', 'AA', 1, '2016-03-21 08:11:10'),
(6, 'PAPER_SIZE', '78', 1, '2016-02-11 02:48:17'),
(7, 'PRINT_COPY', '2', 1, '2016-02-11 03:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `id_employee` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `province` varchar(255) NOT NULL,
  `post_code` varchar(12) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `birthday` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`id_employee`, `code`, `first_name`, `last_name`, `address`, `province`, `post_code`, `phone`, `email`, `birthday`, `start_date`, `date_add`, `date_upd`, `active`) VALUES
(1, '001', 'Admin', 'Warrix', '', '', '', '', '', '0000-00-00', '0000-00-00', '2015-03-19 12:29:29', '2019-07-01 04:08:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id_item` int(11) NOT NULL,
  `barcode` varchar(32) NOT NULL,
  `item_code` varchar(32) NOT NULL,
  `item_name` varchar(150) NOT NULL,
  `style` varchar(32) DEFAULT NULL,
  `cost` decimal(17,2) NOT NULL DEFAULT '0.00',
  `price` decimal(17,2) NOT NULL DEFAULT '0.00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` int(3) NOT NULL DEFAULT '1',
  `last_sync` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items_import`
--

CREATE TABLE `tbl_items_import` (
  `id` int(11) NOT NULL,
  `id_check` int(11) NOT NULL,
  `location_code` varchar(20) NOT NULL,
  `barcode` varchar(32) NOT NULL,
  `item_code` varchar(32) NOT NULL,
  `qty` int(15) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_group`
--

CREATE TABLE `tbl_item_group` (
  `id_group` int(11) NOT NULL,
  `group_name` varchar(30) NOT NULL,
  `group_type` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_item_group`
--

INSERT INTO `tbl_item_group` (`id_group`, `group_name`, `group_type`) VALUES
(1, 'Warrix', 1),
(2, 'Club', 2),
(3, 'Kool', 3),
(4, 'Toy', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `barcode` varchar(32) NOT NULL,
  `reference` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `style` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profile`
--

CREATE TABLE `tbl_profile` (
  `id_profile` int(11) NOT NULL,
  `profile_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_profile`
--

INSERT INTO `tbl_profile` (`id_profile`, `profile_name`) VALUES
(1, 'Supper Admin'),
(2, 'admin'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop`
--

CREATE TABLE `tbl_shop` (
  `shop_code` varchar(20) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `allow_input_qty` enum('N','Y') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `id_employee`, `id_profile`, `user_name`, `password`, `date_add`, `date_upd`, `last_login`, `active`) VALUES
(1, 1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2015-03-19 00:00:00', '2016-02-08 16:43:48', '2020-09-01 20:23:43', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `tbl_access`
--
ALTER TABLE `tbl_access`
  ADD PRIMARY KEY (`id_access`);

--
-- Indexes for table `tbl_check`
--
ALTER TABLE `tbl_check`
  ADD PRIMARY KEY (`id_check`);

--
-- Indexes for table `tbl_check_detail`
--
ALTER TABLE `tbl_check_detail`
  ADD PRIMARY KEY (`id_check_detail`),
  ADD KEY `id_check` (`id_check`),
  ADD KEY `barcode` (`barcode`);

--
-- Indexes for table `tbl_config`
--
ALTER TABLE `tbl_config`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`id_employee`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `last_sync` (`last_sync`);

--
-- Indexes for table `tbl_items_import`
--
ALTER TABLE `tbl_items_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_item_group`
--
ALTER TABLE `tbl_item_group`
  ADD PRIMARY KEY (`id_group`),
  ADD UNIQUE KEY `type` (`group_type`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`barcode`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Indexes for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  ADD PRIMARY KEY (`id_profile`);

--
-- Indexes for table `tbl_shop`
--
ALTER TABLE `tbl_shop`
  ADD PRIMARY KEY (`shop_code`),
  ADD KEY `allow_input_qty` (`allow_input_qty`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_access`
--
ALTER TABLE `tbl_access`
  MODIFY `id_access` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_check`
--
ALTER TABLE `tbl_check`
  MODIFY `id_check` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_check_detail`
--
ALTER TABLE `tbl_check_detail`
  MODIFY `id_check_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_config`
--
ALTER TABLE `tbl_config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `id_employee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_items_import`
--
ALTER TABLE `tbl_items_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_item_group`
--
ALTER TABLE `tbl_item_group`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  MODIFY `id_profile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
