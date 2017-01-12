-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2016 at 09:28 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE IF NOT EXISTS `artists` (
`artist_id` int(10) unsigned NOT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(40) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `customers` (
`customer_id` int(10) unsigned NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` char(40) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `orders` (
`order_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `total` decimal(10,2) unsigned NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `order_contents` (
`oc_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `print_id` int(10) unsigned NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `price` decimal(6,2) unsigned NOT NULL,
  `ship_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `prints` (
`print_id` int(10) unsigned NOT NULL,
  `artist_id` int(10) unsigned NOT NULL,
  `print_name` varchar(60) NOT NULL,
  `price` decimal(6,2) unsigned NOT NULL,
  `size` varchar(60) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_name` varchar(60) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `artists`
 ADD PRIMARY KEY (`artist_id`), ADD UNIQUE KEY `full_name` (`last_name`,`first_name`,`middle_name`);

ALTER TABLE `customers`
 ADD PRIMARY KEY (`customer_id`), ADD UNIQUE KEY `email` (`email`), ADD KEY `login` (`email`,`pass`);
ALTER TABLE `orders`
 ADD PRIMARY KEY (`order_id`), ADD KEY `customer_id` (`customer_id`), ADD KEY `order_date` (`order_date`);

ALTER TABLE `order_contents`
 ADD PRIMARY KEY (`oc_id`), ADD KEY `order_id` (`order_id`), ADD KEY `print_id` (`print_id`);

--
-- Indexes for table `prints`
--
ALTER TABLE `prints`
 ADD PRIMARY KEY (`print_id`), ADD KEY `artist_id` (`artist_id`), ADD KEY `print_name` (`print_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
MODIFY `artist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
MODIFY `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `order_contents`
--
ALTER TABLE `order_contents`
MODIFY `oc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `prints`
--
ALTER TABLE `prints`
MODIFY `print_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
