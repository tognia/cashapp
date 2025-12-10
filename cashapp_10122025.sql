-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 10 déc. 2025 à 06:23
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cashapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`user_id`, `firstname`, `lastname`, `email`, `username`, `password`) VALUES
(1, 'Billy', 'Revellame', 'revellame28@gmail.com', 'bluedcoffee', 'admin'),
(2, 'Clark', 'Banaag', 'banaaghub.com', 'clarkpogi', 'a1Bz20ydqelm8m1wql7c6f5bdc16b3748b481fb5ea98bd4ace'),
(3, 'admin', 'admin', 'admin@admin.com', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(50) NOT NULL,
  `libelle_agence` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ville` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id`, `code_agence`, `libelle_agence`, `email`, `tel`, `ville`) VALUES
(5, 'Eleveur', 'Eleveur', 'ngnokamoise@yahoo.fr', '000002222', 'Yaounde');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`) VALUES
(1, 'Raspberry Pi'),
(2, 'Arduino'),
(3, 'Gizduino'),
(4, 'Sensor'),
(5, 'Module'),
(6, 'Capacitor'),
(7, 'Resistor'),
(8, 'Transistor'),
(9, 'Others'),
(10, 'Banana Pi');

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `membership_number` varchar(100) NOT NULL,
  `prod_name` varchar(550) NOT NULL,
  `expected_date` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `address`, `contact`, `email`, `membership_number`, `prod_name`, `expected_date`, `note`) VALUES
(1, 'BRIZER PLC 909090', 'Kumba Douala Deido 87779', '00237 698 95 56 43', '9htylememe@gmail.com', '000052', 'Chocolat Cerelac au Lait', '10 05 2021', '13');

-- --------------------------------------------------------

--
-- Structure de la table `delta`
--

DROP TABLE IF EXISTS `delta`;
CREATE TABLE IF NOT EXISTS `delta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_updated` varchar(50) NOT NULL,
  `operation` varchar(50) NOT NULL,
  `data` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`log_id`, `user_id`, `action`, `date`) VALUES
(1, 1, 'added a new product 12 of flmjkrmklm', '2017-11-04 18:25:35'),
(2, 1, 'added a new product 34 of gdrgneknkl', '2017-11-04 18:26:04'),
(3, 1, 'added a new product 78 of bdkj', '2017-11-04 18:26:48'),
(4, 0, 'added a new product 133 of Arduino Meta', '2017-11-05 13:00:22'),
(5, 1, 'added a new product 477 of Sugo Peanuts', '2017-11-05 18:15:15'),
(6, 0, 'added a new product 123 of kmyygk', '2017-11-06 11:21:42'),
(7, 5, 'has logged in the system at ', '2017-11-06 21:53:21'),
(8, 1, '(Administrator) has logged in the system at ', '2017-11-06 21:56:17'),
(9, 5, 'has logged in the system at ', '2017-11-06 22:25:17'),
(10, 1, '(Administrator) has logged in the system at ', '2017-11-06 22:25:38'),
(11, 2, '(Administrator) has logged in the system at ', '2017-11-06 23:22:24'),
(12, 5, 'has logged in the system at ', '2017-11-07 00:08:10'),
(13, 1, '(Administrator) has logged in the system at ', '2017-11-07 10:14:23'),
(14, 1, '(Administrator) has logged in the system at ', '2017-11-07 10:33:43'),
(15, 1, '(Administrator) has logged in the system at ', '2017-11-07 10:36:37'),
(16, 1, '(Administrator) has logged in the system at ', '2017-11-07 10:39:08'),
(17, 1, '(Administrator) has logged in the system at ', '2017-11-07 10:39:41'),
(18, 4, 'has logged in the system at ', '2017-11-07 11:04:22'),
(19, 1, '(Administrator) has logged in the system at ', '2017-11-07 11:04:30'),
(20, 4, 'has logged in the system at ', '2017-11-07 11:44:36'),
(21, 4, 'has logged in the system at ', '2017-11-07 18:32:28'),
(22, 1, '(Administrator) has logged in the system at ', '2017-11-07 18:32:49'),
(23, 4, 'has logged in the system at ', '2017-11-07 18:34:55'),
(24, 1, '(Administrator) has logged in the system at ', '2017-11-07 18:39:23'),
(25, 1, 'added a new product 33 of San Marino Corned Tuna', '2017-11-07 18:40:25'),
(26, 1, 'added a new product 453 of 4535', '2017-11-07 18:43:34'),
(27, 1, '(Administrator) has logged in the system at ', '2017-11-07 19:16:29'),
(28, 1, '(Administrator) has logged in the system at ', '2017-11-07 19:17:07'),
(29, 4, 'has logged in the system at ', '2017-11-07 19:27:49'),
(30, 1, '(Administrator) has logged in the system at ', '2017-11-07 19:28:00'),
(31, 1, 'added 2 of Arduino Metad', '2017-11-07 19:28:43'),
(32, 1, '(Administrator) has logged in the system at ', '2017-11-07 22:40:11'),
(33, 1, 'added a new product 2 of 540 microfarad capacitor', '2017-11-07 22:42:03'),
(34, 1, '(Administrator) has logged in the system at ', '2017-11-07 23:43:49'),
(35, 4, 'has logged in the system at ', '2017-11-08 12:31:38'),
(36, 1, '(Administrator) has logged in the system at ', '2017-11-08 12:45:41'),
(37, 1, '(Administrator) has logged in the system at ', '2017-11-08 13:46:56'),
(38, 4, 'has logged in the system at ', '2017-11-08 13:56:15'),
(39, 4, 'has logged in the system at ', '2017-11-08 14:39:44'),
(40, 1, '(Administrator) has logged in the system at ', '2017-11-08 14:54:05'),
(41, 1, 'added 5 of 540 microfarad capacitor', '2017-11-08 15:04:55'),
(42, 4, 'has logged in the system at ', '2017-11-08 15:21:00'),
(43, 1, '(Administrator) has logged in the system at ', '2017-11-08 15:29:08'),
(44, 1, '(Administrator) has logged in the system at ', '2017-11-08 15:34:28'),
(45, 1, '(Administrator) has logged in the system at ', '2017-11-08 15:38:21'),
(46, 6, 'has logged in the system at ', '2017-11-08 19:29:55'),
(47, 1, '(Administrator) has logged in the system at ', '2017-11-08 19:32:24'),
(48, 6, 'has logged in the system at ', '2017-11-08 20:13:57'),
(49, 6, 'has logged in the system at ', '2017-11-08 20:20:43'),
(50, 1, '(Administrator) has logged in the system at ', '2017-11-08 20:46:23'),
(51, 6, 'has logged in the system at ', '2017-11-08 20:59:18'),
(52, 1, '(Administrator) has logged in the system at ', '2017-11-08 21:32:10'),
(53, 6, 'has logged in the system at ', '2017-11-08 21:34:41'),
(54, 1, '(Administrator) has logged in the system at ', '2017-11-08 21:39:31'),
(55, 1, 'added a new product 34 of Arduino Uno', '2017-11-08 21:40:51'),
(56, 6, 'has logged in the system at ', '2017-11-08 22:18:15'),
(57, 6, 'has logged in the system at ', '2017-11-08 22:19:58'),
(58, 1, '(Administrator) has logged in the system at ', '2017-11-08 22:56:12'),
(59, 6, 'has logged in the system at ', '2017-11-08 22:59:17'),
(60, 6, 'has logged in the system at ', '2017-11-09 15:21:55'),
(61, 6, 'has logged in the system at ', '2017-11-09 15:45:14'),
(62, 6, 'has logged in the system at ', '2017-11-09 15:46:39'),
(63, 6, 'has logged in the system at ', '2017-11-09 15:57:59'),
(64, 6, 'has logged in the system at ', '2017-11-09 16:34:47'),
(65, 6, 'has logged in the system at ', '2017-11-09 17:02:52'),
(66, 6, 'has logged in the system at ', '2017-11-09 19:54:15'),
(67, 6, 'has logged in the system at ', '2017-11-09 21:21:45'),
(68, 1, '(Administrator) has logged in the system at ', '2017-11-10 00:23:49'),
(69, 6, 'has logged in the system at ', '2017-11-10 00:24:25'),
(70, 1, '(Administrator) has logged in the system at ', '2017-11-10 00:54:01'),
(71, 6, 'has logged in the system at ', '2017-11-10 00:54:22'),
(72, 4, 'has logged in the system at ', '2017-11-10 01:38:17'),
(73, 6, 'has logged in the system at ', '2017-11-10 11:00:43'),
(74, 6, 'has logged in the system at ', '2017-11-10 23:53:20'),
(75, 6, 'has logged in the system at ', '2017-11-11 00:00:46'),
(76, 6, 'has logged in the system at ', '2017-11-11 00:10:29'),
(77, 6, 'has logged in the system at ', '2017-11-11 00:26:10'),
(78, 1, '(Administrator) has logged in the system at ', '2017-11-11 01:38:51'),
(79, 6, 'has logged in the system at ', '2017-11-12 01:36:32'),
(80, 6, 'has logged in the system at ', '2017-11-12 21:22:19'),
(81, 1, '(Administrator) has logged in the system at ', '2017-11-12 21:25:48'),
(82, 1, '(Administrator) has logged in the system at ', '2017-11-12 21:26:22'),
(83, 2, '(Administrator) has logged in the system at ', '2017-11-12 21:29:04'),
(84, 6, 'has logged in the system at ', '2017-11-12 21:45:12'),
(85, 2, '(Administrator) has logged in the system at ', '2017-11-12 21:47:14'),
(86, 6, 'has logged in the system at ', '2017-11-12 23:14:12'),
(87, 1, '(Administrator) has logged in the system at ', '2017-11-12 23:19:55'),
(88, 6, 'has logged in the system at ', '2017-11-12 23:22:32'),
(89, 6, 'has logged in the system at ', '2017-11-13 00:17:25'),
(90, 1, '(Administrator) has logged in the system at ', '2017-11-13 00:28:25'),
(91, 1, 'added a new product 150 of Arduino Uno Rec3-1', '2017-11-13 00:31:30'),
(92, 1, 'added a new product 400 of Aruino Mega', '2017-11-13 00:32:19'),
(93, 1, 'added a new product 344 of Arduino Uno 2', '2017-11-13 00:33:17'),
(94, 1, 'added a new product 234 of Raspberry Pi 3', '2017-11-13 00:34:22'),
(95, 1, 'added a new product 456 of Flame Sensor', '2017-11-13 00:35:28'),
(96, 6, 'has logged in the system at ', '2017-11-13 00:38:32'),
(97, 1, '(Administrator) has logged in the system at ', '2017-11-13 08:45:06'),
(98, 6, 'has logged in the system at ', '2017-11-13 08:47:34'),
(99, 1, '(Administrator) has logged in the system at ', '2017-11-13 08:53:46'),
(100, 7, 'has logged in the system at ', '2017-11-13 08:56:45'),
(101, 1, '(Administrator) has logged in the system at ', '2017-11-13 10:40:50'),
(102, 6, 'has logged in the system at ', '2017-11-13 10:42:37'),
(103, 1, '(Administrator) has logged in the system at ', '2017-11-13 10:55:02'),
(104, 6, 'has logged in the system at ', '2017-11-13 10:55:19'),
(105, 1, '(Administrator) has logged in the system at ', '2017-11-13 11:15:27'),
(106, 6, 'has logged in the system at ', '2017-11-13 11:15:38'),
(107, 1, '(Administrator) has logged in the system at ', '2017-11-13 11:31:48'),
(108, 6, 'has logged in the system at ', '2017-11-13 11:55:12'),
(109, 1, '(Administrator) has logged in the system at ', '2017-11-13 11:57:27'),
(110, 6, 'has logged in the system at ', '2017-11-13 11:59:22'),
(111, 1, '(Administrator) has logged in the system at ', '2017-11-13 12:00:16'),
(112, 6, 'has logged in the system at ', '2017-11-13 12:04:41'),
(113, 8, 'has logged in the system at ', '2017-11-13 13:05:00'),
(114, 2, '(Administrator) has logged in the system at ', '2017-11-13 13:16:17'),
(115, 2, 'added a new product 700 of Sensor', '2017-11-13 13:20:38'),
(116, 2, 'added 900 of Arduino Uno 2', '2017-11-13 13:20:57'),
(117, 6, 'has logged in the system at ', '2017-11-13 19:58:52'),
(118, 8, 'has logged in the system at ', '2017-11-13 20:00:59'),
(119, 1, '(Administrator) has logged in the system at ', '2017-11-13 20:01:58'),
(120, 1, '(Administrator) has logged in the system at ', '2017-11-13 21:47:41'),
(121, 6, 'has logged in the system at ', '2017-11-13 21:49:55'),
(122, 1, '(Administrator) has logged in the system at ', '2017-11-13 21:52:28'),
(123, 1, '(Administrator) has logged in the system at ', '2017-11-14 16:01:08'),
(124, 6, 'has logged in the system at ', '2017-11-17 01:43:42'),
(125, 6, 'has logged in the system at ', '2017-11-17 02:15:46'),
(126, 8, 'has logged in the system at ', '2017-11-21 20:19:39'),
(127, 8, 'has logged in the system at ', '2017-11-25 23:31:53'),
(128, 9, 'has logged in the system at ', '2018-10-12 19:52:39'),
(129, 9, 'has logged in the system at ', '2018-10-13 01:18:49'),
(130, 9, 'added a new product 26 of X9 THOR - Gaming Mouse', '2018-10-13 01:32:00'),
(131, 9, 'has logged in the system at ', '2018-10-13 01:50:19'),
(132, 9, 'has logged in the system at ', '2021-05-12 21:46:37'),
(133, 10, 'has logged in the system at ', '2021-05-14 19:17:36'),
(134, 10, 'has logged in the system at ', '2021-05-14 19:20:52'),
(135, 10, 'has logged in the system at ', '2021-05-20 16:48:26'),
(136, 13, 'has logged in the system at ', '2021-05-26 00:28:11');

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_num` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `shipping_add` varchar(500) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(100) NOT NULL,
  `totalprice` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `total_qty` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(30) NOT NULL,
  PRIMARY KEY (`order_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `due` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `or_no` int(11) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(50) NOT NULL,
  `prod_desc` varchar(500) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `prod_cost` decimal(10,2) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `prod_serial` varchar(50) NOT NULL,
  `prod_pic1` varchar(500) NOT NULL,
  `prod_pic2` varchar(500) NOT NULL,
  `prod_pic3` varchar(500) NOT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`prod_id`, `prod_name`, `prod_desc`, `prod_qty`, `prod_cost`, `prod_price`, `category`, `supplier`, `prod_serial`, `prod_pic1`, `prod_pic2`, `prod_pic3`) VALUES
(11, 'Arduino Uno Rec3-1', 'Small Arduino Uno Blue', 0, '123.00', '125.00', 'Arduino', 'Alcatroz, Inc.', '1122330099', 'arduino mega 2560-1.jpg', 'Arduino Uno Rev3-1.jpg', '1.png'),
(12, 'Aruino Mega', 'ATMega Arduino', 391, '133.00', '155.00', 'Arduino', 'Alcatroz, Inc.', '341156780', 'Arduinomega2560-3.jpg', 'arduino mega 2560-1.jpg', '2.png'),
(14, 'Raspberry Pi 3', 'Model B+', 221, '700.00', '760.00', 'Raspberry Pi', 'PICC', '45422791', 'raspi2.jpg', 'raspi.jpg', 'raspi3.png'),
(15, 'Flame Sensor', 'Flame Sensor 3 Pins', 455, '450.00', '455.00', 'Sensor', 'QUEZELCO', '456523702', 'flame2.jpg', 'flamesensor1.jpg', 'flamesensor.png'),
(16, 'Sensor', 'Able to sense product', 700, '1500.00', '1500.00', 'Sensor', 'QUEZELCO', '890', 'ultrasonic sensor.png', 'motion sensor2.jpg', 'flamesensor1.jpg'),
(17, 'X9 THOR - Gaming Mouse', '7D Macro Programmable Gaming Mouse, Sensor: A714 Instan, LED: RGB 16.8 million colors, Interface : USB, DPI: 4800dpi, Cable Length: 1.8m nylon braided, Supported OS: Windows Vista, Win7/8/10, Mac OS X 10.5 or later, Linux, Chrome OS', 25, '1000.00', '2200.00', 'Others', 'Alcatroz, Inc.', '1353', 'x9thor.jpg', 'x92.jpg', 'x93.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `mode_of_payment` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sales_details`
--

DROP TABLE IF EXISTS `sales_details`;
CREATE TABLE IF NOT EXISTS `sales_details` (
  `sales_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`sales_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active | 0=Inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `name`, `status`) VALUES
(1, 'Musique', 1),
(2, 'Abnégation', 1),
(3, 'Football', 1),
(4, 'Fort', 1);

-- --------------------------------------------------------

--
-- Structure de la table `supliers`
--

DROP TABLE IF EXISTS `supliers`;
CREATE TABLE IF NOT EXISTS `supliers` (
  `suplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `suplier_name` varchar(100) NOT NULL,
  `suplier_address` varchar(100) NOT NULL,
  `suplier_contact` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `note` varchar(500) NOT NULL,
  PRIMARY KEY (`suplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `supliers`
--

INSERT INTO `supliers` (`suplier_id`, `suplier_name`, `suplier_address`, `suplier_contact`, `contact_person`, `note`) VALUES
(1, 'OKOK chaud Manioc', 'Yaounde Ngousso', '69989 45 62', '661 55 55 55', '17'),
(2, 'No Name', 'RAS', '000000', 'Basic 1', '10'),
(3, 'DOVV', 'ESSOS', '0022233', 'DOVV Banana', '1');

-- --------------------------------------------------------

--
-- Structure de la table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `supp_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(100) NOT NULL,
  `supp_address` varchar(200) NOT NULL,
  `supp_contact` varchar(50) NOT NULL,
  `supp_email` varchar(50) NOT NULL,
  PRIMARY KEY (`supp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `supplier`
--

INSERT INTO `supplier` (`supp_id`, `supp_name`, `supp_address`, `supp_contact`, `supp_email`) VALUES
(1, 'PICC', 'Manila, Phils.', '(987)-884-12', 'picc@email.moto!'),
(2, 'QUEZELCO', 'Infanta, Quezon', '45643534567879', 'emal'),
(4, 'Alcatroz, Inc.', 'Sta. Mesa Manila', '9435398928', 'none');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(200) NOT NULL,
  `cat_parent` varchar(150) NOT NULL,
  `cat_level` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_name` (`cat_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_category`
--

INSERT INTO `tbl_category` (`cat_id`, `cat_name`, `cat_parent`, `cat_level`) VALUES
(6, 'Accessoires et autre appareillage terminal', 'Appareillage Terminal', 3),
(7, 'Savon', 'Aucune', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin`
--

DROP TABLE IF EXISTS `tbl_commandes_magasin`;
CREATE TABLE IF NOT EXISTS `tbl_commandes_magasin` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `cashier_name` varchar(100) NOT NULL,
  `shop` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_commandes_magasin`
--

INSERT INTO `tbl_commandes_magasin` (`invoice_id`, `cashier_name`, `shop`, `order_date`, `time_order`, `total`) VALUES
(138, 'respo', 'bev_oyomabang', '2024-03-23', '23:47', 49000);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin_details`
--

DROP TABLE IF EXISTS `tbl_commandes_magasin_details`;
CREATE TABLE IF NOT EXISTS `tbl_commandes_magasin_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `shop` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_commandes_magasin_details`
--

INSERT INTO `tbl_commandes_magasin_details` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `shop`) VALUES
(133, 0, 4, '000002', 'Multiprise 7777', 2, 'U', 15000, 30000, '2024-03-23', 'bev_oyomabang'),
(134, 0, 1, '000001', 'Prise Le Grand', 2, 'U', 3000, 6000, '2024-03-23', 'bev_oyomabang'),
(135, 0, 4, '000002', 'Multiprise 7777', 1, 'U', 15000, 15000, '2024-03-23', 'bev_oyomabang'),
(136, 0, 4, '000002', 'Multiprise 7777', 2, 'U', 15000, 30000, '2024-03-23', 'bev_oyomabang'),
(137, 138, 1, '000001', 'Prise Le Grand', 3, 'U', 3000, 9000, '2024-03-23', 'bev_oyomabang'),
(138, 138, 7, '00003', 'Multiprises APC', 2, 'U', 20000, 40000, '2024-03-23', 'bev_oyomabang');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice`
--

DROP TABLE IF EXISTS `tbl_invoice`;
CREATE TABLE IF NOT EXISTS `tbl_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `cashier_name` varchar(100) NOT NULL,
  `user` varchar(100) NOT NULL,
  `id_client` varchar(150) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  `paid` float NOT NULL,
  `due` float NOT NULL,
  `remise` float NOT NULL,
  `tva` float NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `cashier_name`, `user`, `id_client`, `order_date`, `time_order`, `total`, `paid`, `due`, `remise`, `tva`, `payment_mode`) VALUES
(155, 'OKO', 'operator2', 'common', '2025-12-10', '07:02:00', 5000, 25000, 20000, 0, 807.13, 'especes'),
(156, 'OKO', 'operator2', 'common', '2025-12-10', '07:16:00', 5000, 10000, 5000, 0, 807.13, 'especes');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client`
--

DROP TABLE IF EXISTS `tbl_invoice_client`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_client` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  `Status` varchar(20) NOT NULL,
  `moyen_paiement` varchar(50) NOT NULL,
  `date_paiement` date NOT NULL,
  `time_paiement` time NOT NULL,
  `infos_paiement` varchar(200) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client_deleted`
--

DROP TABLE IF EXISTS `tbl_invoice_client_deleted`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_client_deleted` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `total` float NOT NULL,
  `delete_date` date NOT NULL,
  `delete_time` varchar(50) NOT NULL,
  `delete_moyen` varchar(50) NOT NULL,
  `delete_infos` varchar(200) NOT NULL,
  `observations` varchar(200) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_client_deleted`
--

INSERT INTO `tbl_invoice_client_deleted` (`invoice_id`, `id_client`, `name_client`, `total`, `delete_date`, `delete_time`, `delete_moyen`, `delete_infos`, `observations`) VALUES
(187, 'topsi', 'FRANCIS DUJARDIN SECEC', 190000, '2021-06-03', '13:04', '', 'dadadaedada', 'delivered'),
(188, 'topsi', 'FRANCIS DUJARDIN SECEC', 261400, '2021-06-03', '13:15', '', 'daeaeaedeaeaea', 'deleted'),
(189, 'topsi', 'FRANCIS DUJARDIN SECEC', 70000, '2021-06-07', '12:28', '', '', 'ordered'),
(190, 'topsi', 'FRANCIS DUJARDIN SECEC', 152100, '2021-06-07', '12:46', '', '', 'delivered');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client_delivered`
--

DROP TABLE IF EXISTS `tbl_invoice_client_delivered`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_client_delivered` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `total` float NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` varchar(50) NOT NULL,
  `delivery_moyen` varchar(50) NOT NULL,
  `delivery_infos` varchar(200) NOT NULL,
  `observations` varchar(200) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_client_delivered`
--

INSERT INTO `tbl_invoice_client_delivered` (`invoice_id`, `id_client`, `name_client`, `total`, `delivery_date`, `delivery_time`, `delivery_moyen`, `delivery_infos`, `observations`) VALUES
(187, 'topsi', 'FRANCIS DUJARDIN SECEC', 190000, '2021-06-03', '13:04', '', 'dadadaedada', 'delivered'),
(188, 'topsi', 'FRANCIS DUJARDIN SECEC', 261400, '2021-06-03', '13:15', '', 'daeaeaedeaeaea', 'deleted'),
(189, 'topsi', 'FRANCIS DUJARDIN SECEC', 70000, '2021-06-07', '12:28', '', '', 'ordered'),
(190, 'topsi', 'FRANCIS DUJARDIN SECEC', 152100, '2021-06-07', '12:46', '', '', 'delivered');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail`
--

DROP TABLE IF EXISTS `tbl_invoice_detail`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `remise` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_detail`
--

INSERT INTO `tbl_invoice_detail` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `remise`) VALUES
(140, 144, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-02', 0),
(141, 144, 92, 'PAO12345', 'POTATO', 1, 'U', 3000, 3000, '2025-12-02', 0),
(142, 145, 91, 'CA000233', 'ZOBAZO', 5, 'U', 5000, 25000, '2025-12-02', 0),
(143, 145, 92, 'PAO12345', 'POTATO', 27, 'U', 3000, 81000, '2025-12-02', 0),
(144, 146, 91, 'CA000233', 'ZOBAZO', 8, 'U', 5000, 40000, '2025-12-02', 0),
(145, 147, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-02', 0),
(146, 148, 91, 'CA000233', 'ZOBAZO', 3, 'U', 5000, 15000, '2025-12-02', 0),
(147, 149, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-02', 0),
(148, 150, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-03', 0),
(149, 151, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-03', 0),
(150, 152, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-03', 0),
(151, 153, 91, 'CA000233', 'ZOBAZO', 3, 'U', 5000, 15000, '2025-12-06', 0),
(152, 154, 91, 'CA000233', 'ZOBAZO', 9, 'U', 5000, 45000, '2025-12-08', 0),
(153, 155, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-10', 0),
(154, 156, 91, 'CA000233', 'ZOBAZO', 1, 'U', 5000, 5000, '2025-12-10', 0);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail_client`
--

DROP TABLE IF EXISTS `tbl_invoice_detail_client`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_detail_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `Status` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_detail_client`
--

INSERT INTO `tbl_invoice_detail_client` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `Status`) VALUES
(114, 187, 0, 'CODEOO', 'Feuilles contre plaquet', 2, 'Kg', 95000, 190000, '2021-06-03', 'deleted'),
(117, 188, 0, 'CODEOO', 'Prise reseaux', 4, 'Kg', 65000, 260000, '2021-06-03', 'paid'),
(118, 188, 0, 'CODEOO', 'Rallonge', 2, 'Kg', 700, 1400, '2021-06-03', 'paid'),
(119, 189, 0, 'CODEOO', 'Round Cable Clips', 2, 'Kg', 35000, 70000, '2021-06-07', 'delivered'),
(120, 190, 0, 'CODEOO', 'Rallonge', 3, 'Kg', 700, 2100, '2021-06-07', 'delivered'),
(121, 190, 0, 'CODEOO', 'Plaque Interrupteur', 2, 'Kg', 75000, 150000, '2021-06-07', 'delivered'),
(122, 191, 0, 'TT0040', 'Feuilles contre plaquet', 3, 'Kg', 95000, 285000, '2021-06-11', 'ordered'),
(123, 191, 0, 'RR0022', 'Round Cable Clips', 2, 'Kg', 35000, 70000, '2021-06-11', 'ordered'),
(124, 192, 0, 'TT0040', 'Feuilles contre plaquet', 4, 'Kg', 95000, 380000, '2021-06-11', 'ordered'),
(125, 192, 0, 'dsdsds', 'Plaque Interrupteur', 2, 'Kg', 75000, 150000, '2021-06-11', 'ordered'),
(126, 193, 0, 'TT0040', 'Feuilles contre plaquet', 1, 'U', 95000, 95000, '2021-06-11', 'ordered'),
(127, 194, 0, 'TT0040', 'Feuilles contre plaquet', 4, 'm', 95000, 380000, '2021-06-12', 'ordered'),
(128, 195, 0, 'TT0040', 'Feuilles contre plaquet', 2, 'm', 95000, 190000, '2021-07-06', 'ordered'),
(129, 195, 0, 'DA0001', 'Prise reseaux', 4, 'U', 65000, 260000, '2021-07-06', 'ordered'),
(130, 196, 0, 'TT0040', 'Feuilles contre plaquet', 2, 'm', 95000, 190000, '2021-07-07', 'ordered'),
(131, 196, 0, 'DA0001', 'Prise reseaux', 1, 'U', 65000, 65000, '2021-07-07', 'ordered'),
(132, 198, 0, 'TTTZHZHZ8', 'Table chauffante', 4, 'U', 25000, 100000, '2021-07-10', 'ordered'),
(133, 198, 0, 'TT0040', 'Feuilles contre plaquet', 1, 'm', 95000, 95000, '2021-07-10', 'ordered'),
(134, 199, 0, 'TT0040', 'Feuilles contre plaquet', 1, 'm', 95000, 95000, '2021-07-13', 'ordered'),
(135, 199, 0, 'DA0001', 'Prise reseaux', 1, 'U', 65000, 65000, '2021-07-13', 'ordered'),
(136, 200, 0, 'TT0045', 'Rallonge', 3, 'Kg', 700, 2100, '2021-07-13', 'ordered'),
(137, 200, 0, 'TTTZHZHZ8', 'Table chauffante', 2, 'U', 25000, 50000, '2021-07-13', 'ordered'),
(138, 201, 0, 'DA0001', 'Prise reseaux', 2, 'U', 65000, 130000, '2021-07-13', 'ordered'),
(139, 201, 0, 'dsdsds', 'Plaque Interrupteur', 1, 'Kg', 75000, 75000, '2021-07-13', 'ordered'),
(140, 201, 0, 'TTTZHZHZ8', 'Table chauffante', 3, 'U', 25000, 75000, '2021-07-13', 'ordered'),
(141, 202, 0, 'TT0040', 'Feuilles contre plaquet', 2, 'm', 95000, 190000, '2021-07-13', 'ordered'),
(142, 202, 0, 'DA0001', 'Prise reseaux', 2, 'U', 65000, 130000, '2021-07-13', 'ordered'),
(143, 203, 0, 'TT0040', 'Feuilles contre plaquet', 2, 'm', 95000, 190000, '2021-07-13', 'ordered'),
(144, 203, 0, 'TT0045', 'Rallonge', 4, 'Kg', 700, 2800, '2021-07-13', 'ordered'),
(145, 204, 0, 'DA0001', 'Prise reseaux', 5, 'U', 65000, 325000, '2021-09-12', 'ordered');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product`
--

DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` char(50) NOT NULL,
  `product_sku` varchar(30) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `product_brand` varchar(50) NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `purchase_price` float(10,0) NOT NULL,
  `sell_price` float(10,0) NOT NULL,
  `min_price` int(11) NOT NULL,
  `discount` float NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `product_satuan` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `place_in_storeroom` varchar(50) NOT NULL,
  `place_in_store` varchar(50) NOT NULL,
  `img` varchar(200) NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`,`product_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_storeroom`, `place_in_store`, `img`) VALUES
(35, 'CA000233', '002CA000233', 'ZOBAZO', 'Accessoires et autre appareillage terminal', 'oko', 'OKOK chaud Manioc', 2500, 5000, 4000, 0, 200, 5, 'U', 'CA000233CA000233CA000233', 'RAS', 'RAS', '691ea6088d957.jpg'),
(36, 'PAO12345', 'PAO123450001', 'POTATO', 'Accessoires et autre appareillage terminal', 'BOUF', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 100, 10, 'U', 'POTATO BOUF', 'RAS', 'RAS', '692b284821d47.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product_receipt`
--

DROP TABLE IF EXISTS `tbl_product_receipt`;
CREATE TABLE IF NOT EXISTS `tbl_product_receipt` (
  `receipt_id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_date` date NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_sku` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `received_quantity` int(11) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `receipt_price` float(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`receipt_id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_product_receipt`
--

INSERT INTO `tbl_product_receipt` (`receipt_id`, `receipt_date`, `product_id`, `product_code`, `product_sku`, `product_name`, `received_quantity`, `supplier_name`, `receipt_price`, `user_id`, `notes`) VALUES
(1, '2025-11-24', 35, '', '', '', 20, 'OKOK chaud Manioc', 2500.00, 6, NULL),
(2, '2025-11-25', 35, '', '', '', 10, 'OKOK chaud Manioc', 2500.00, 17, NULL),
(3, '2025-11-26', 35, '', '', '', 49, 'OKOK chaud Manioc', 2500.00, 17, NULL),
(4, '2025-11-26', 35, '', '', '', 1, 'OKOK chaud Manioc', 2500.00, 17, NULL),
(5, '2025-11-29', 35, 'CA000233', '002CA000233', 'ZOBAZO', 60, 'OKOK chaud Manioc', 2500.00, 17, NULL),
(6, '2025-11-29', 35, 'CA000233', '002CA000233', 'ZOBAZO', 50, 'OKOK chaud Manioc', 2500.00, 17, NULL),
(7, '2025-12-02', 36, 'PAO12345', 'PAO123450001', 'POTATO', 98, 'OKOK chaud Manioc', 1500.00, 20, NULL),
(8, '2025-12-02', 35, 'CA000233', '002CA000233', 'ZOBAZO', 200, 'OKOK chaud Manioc', 2500.00, 20, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product_shipment`
--

DROP TABLE IF EXISTS `tbl_product_shipment`;
CREATE TABLE IF NOT EXISTS `tbl_product_shipment` (
  `shipment_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_date` date NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_sku` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `shipped_quantity` int(11) NOT NULL,
  `code_agence` varchar(30) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `delivery_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`shipment_id`),
  KEY `fk_ship_product_id` (`product_id`),
  KEY `fk_ship_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_product_shipment`
--

INSERT INTO `tbl_product_shipment` (`shipment_id`, `shipment_date`, `product_id`, `product_code`, `product_sku`, `product_name`, `shipped_quantity`, `code_agence`, `user_id`, `delivery_status`, `notes`) VALUES
(10, '2025-11-29', 91, 'CA000233', '002CA000233', 'ZOBAZO', 50, 'Eleveur', '17', 'accepted', ''),
(11, '2025-11-29', 92, 'PAO12345', 'PAO123450001', 'POTATO', 45, 'Eleveur', '17', 'accepted', ''),
(12, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 20, 'Eleveur', '19', 'accepted', ''),
(13, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 30, 'Eleveur', '19', 'accepted', ''),
(14, '2025-11-30', 92, 'PAO12345', 'PAO123450001', 'POTATO', 10, 'Eleveur', '19', 'accepted', ''),
(15, '2025-11-30', 92, 'PAO12345', 'PAO123450001', 'POTATO', 5, 'Eleveur', '19', 'accepted', ''),
(16, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 25, 'Eleveur', '17', 'accepted', ''),
(17, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 5, 'Eleveur', '17', 'accepted', ''),
(18, '2025-11-30', 92, 'PAO12345', 'PAO123450001', 'POTATO', 5, 'Eleveur', '17', 'accepted', ''),
(19, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 5, 'Eleveur', '17', 'accepted', ''),
(20, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 5, 'Eleveur', '17', 'accepted', ''),
(21, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 6, 'Eleveur', '17', 'accepted', ''),
(22, '2025-11-30', 91, 'CA000233', '002CA000233', 'ZOBAZO', 4, 'Eleveur', '17', 'accepted', ''),
(23, '2025-11-30', 92, 'PAO12345', 'PAO123450001', 'POTATO', 5, 'Eleveur', '17', 'accepted', ''),
(24, '2025-11-30', 92, 'PAO12345', 'PAO123450001', 'POTATO', 3, 'Eleveur', '17', 'accepted', '');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_provision`
--

DROP TABLE IF EXISTS `tbl_provision`;
CREATE TABLE IF NOT EXISTS `tbl_provision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `qte` int(11) NOT NULL,
  `user` varchar(25) NOT NULL,
  `observations` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_satuan`
--

DROP TABLE IF EXISTS `tbl_satuan`;
CREATE TABLE IF NOT EXISTS `tbl_satuan` (
  `kd_satuan` int(2) NOT NULL AUTO_INCREMENT,
  `nm_satuan` varchar(20) NOT NULL,
  PRIMARY KEY (`kd_satuan`),
  UNIQUE KEY `nm_satuan` (`nm_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`kd_satuan`, `nm_satuan`) VALUES
(16, 'Kg'),
(17, 'm'),
(19, 'pack'),
(18, 'U');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shop_item`
--

DROP TABLE IF EXISTS `tbl_shop_item`;
CREATE TABLE IF NOT EXISTS `tbl_shop_item` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_code` varchar(30) NOT NULL,
  `product_code` char(50) NOT NULL,
  `product_sku` varchar(30) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `product_brand` varchar(50) NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `purchase_price` float(10,0) NOT NULL,
  `sell_price` float(10,0) NOT NULL,
  `min_price` float(10,0) NOT NULL,
  `discount` float(10,0) NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `product_satuan` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `place_in_store` varchar(50) NOT NULL,
  `img` varchar(200) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_shop_item`
--

INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(91, 'Eleveur', 'CA000233', '002CA000233', 'ZOBAZO', 'Accessoires et autre appareillage terminal', 'oko', 'OKOK chaud Manioc', 2500, 5000, 4000, 0, 64, 0, 'U', 'CA000233CA000233CA000233', 'RAS', '691ea6088d957.jpg'),
(92, 'Eleveur', 'PAO12345', 'PAO123450001', 'POTATO', 'Accessoires et autre appareillage terminal', 'BOUF', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 0, 0, 'U', 'POTATO BOUF', 'RAS', '692b284821d47.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shop_product`
--

DROP TABLE IF EXISTS `tbl_shop_product`;
CREATE TABLE IF NOT EXISTS `tbl_shop_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_min` int(11) NOT NULL,
  `prix_vente` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tbl_shop_product`
--

INSERT INTO `tbl_shop_product` (`id`, `code_agence`, `code_produit`, `stock`, `stock_min`, `prix_vente`) VALUES
(1, 'bev_oyomabang', 'TT0040', 10, 5, 15000);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(80) NOT NULL,
  `password` varchar(50) NOT NULL,
  `magasin` varchar(20) NOT NULL,
  `role` varchar(15) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `magasin`, `role`, `is_active`) VALUES
(6, 'tnh', 'Tognia', '7c222fb2927d828af22f592134e8932480637c0d', 'Eleveur', 'Admin', 1),
(17, 'storekeeper', 'PEACEFULL', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'storekeeper', 1),
(18, 'operator2', 'OKO', '7c222fb2927d828af22f592134e8932480637c0d', 'Eleveur', 'Operator', 1),
(19, 'respo', 'OBOBOGO', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'Responsable', 1),
(20, 'admin', 'BOULANGERIE ELEVEUR', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'Admin', 1);

-- --------------------------------------------------------

--
-- Structure de la table `temp_trans`
--

DROP TABLE IF EXISTS `temp_trans`;
CREATE TABLE IF NOT EXISTS `temp_trans` (
  `temp_trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`temp_trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `or_no` int(11) NOT NULL,
  `prod_serial` varchar(50) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `trans_qty` int(11) NOT NULL,
  `ppi` decimal(10,0) NOT NULL,
  `cust_fullname` varchar(100) NOT NULL,
  `transdate` datetime NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `middlename`, `lastname`, `address`, `email`, `contact`, `username`, `password`, `type`) VALUES
(15, 'BOBIBO', 'BOBIBO', 'BOBIBO', 'BOBIBO', 'awarenessera40@gmail.com', '000065', 'htognia', '7c222fb2927d828af22f592134e8932480637c0d', 'particulier');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `tbl_product_receipt`
--
ALTER TABLE `tbl_product_receipt`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
