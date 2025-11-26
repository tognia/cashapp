-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 19 nov. 2025 à 08:23
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
(1, 'bev_oyomabang', 'Bevilec Oyomabang', 'trajectoirei@live.fr', '672569213', 'Yaounde'),
(2, 'bev_Biyemassi', 'Bevilec Carrefour Biyemassi', 'trajectoirei@live.fr', '699456700', 'Yaounde'),
(3, 'leti_nkolbisson', 'plus elec cameroun Sarl', 'yakamamelie23@gmail.com', '655762258', 'Yaounde'),
(5, 'Eleveur', 'Eleveur', '', '000002222', 'Yaounde');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `tbl_product_shipment` (
  `shipment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `shipment_date` DATE NOT NULL,
  `product_id` INT(11) NOT NULL,
  `shipped_quantity` INT(11) NOT NULL,
  `destination_agence_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `delivery_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
  `notes` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`shipment_id`),
  KEY `fk_ship_product_id` (`product_id`),
  KEY `fk_ship_user_id` (`user_id`),
  KEY `fk_ship_agence_id` (`destination_agence_id`),
  
  -- Clés étrangères (Foreign Keys)
  CONSTRAINT `fk_ship_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ship_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  -- ATTENTION: Le moteur de stockage de 'agence' est MyISAM, qui ne supporte pas les FOREIGN KEYs. 
  -- Pour des raisons de cohérence de données, il est fortement recommandé de changer 'agence' en InnoDB.
  CONSTRAINT `fk_ship_agence_id` FOREIGN KEY (`destination_agence_id`) REFERENCES `agence` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-----------------------------------------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `prod_id`, `prod_qty`, `total_qty`, `total`, `user_id`, `order_id`) VALUES
(53, 13, 1, '338', '434.00', 6, '1'),
(54, 13, 3, '335', '1302.00', 6, '1'),
(55, 13, 1, '334', '434.00', 6, '1'),
(56, 11, 1, '149', '125.00', 6, '1'),
(57, 12, 1, '397', '155.00', 6, '1'),
(58, 11, 1, '149', '125.00', 6, '1'),
(59, 13, 1, '329', '434.00', 6, '1'),
(60, 13, 1, '328', '434.00', 6, '1'),
(61, 13, 1, '327', '434.00', 6, '1'),
(62, 12, 2, '395', '310.00', 6, '1'),
(63, 13, 2, '325', '868.00', 6, '1'),
(64, 13, 1, '324', '434.00', 6, '1'),
(65, 11, 1, '148', '125.00', 6, '1'),
(66, 13, 1, '323', '434.00', 6, '1'),
(67, 11, 1, '147', '125.00', 6, '1'),
(68, 12, 1, '394', '155.00', 6, '1'),
(69, 12, 1, '393', '155.00', 6, '1'),
(70, 13, 1, '322', '434.00', 7, '1'),
(71, 11, 1, '146', '125.00', 7, '1'),
(72, 13, 1, '321', '434.00', 7, '1'),
(73, 13, 1, '320', '434.00', 7, '1'),
(74, 13, 1, '319', '434.00', 7, '1'),
(75, 13, 1, '318', '434.00', 6, '1'),
(76, 13, 3, '315', '1302.00', 6, '1'),
(77, 13, 1, '314', '434.00', 6, '1'),
(78, 13, 1, '313', '434.00', 6, '1'),
(79, 14, 1, '233', '760.00', 6, '1'),
(80, 13, 1, '311', '434.00', 6, '1'),
(81, 13, 2, '309', '868.00', 6, '1'),
(83, 14, 1, '233', '760.00', 6, '1'),
(84, 13, 1, '308', '434.00', 6, '1'),
(85, 15, 1, '455', '455.00', 6, '1'),
(86, 11, 1, '145', '125.00', 6, '1'),
(87, 13, 1, '306', '434.00', 6, '1'),
(88, 13, 1, '304', '434.00', 6, '1'),
(89, 13, 1, '303', '434.00', 6, '1'),
(90, 13, 1, '302', '434.00', 6, '1'),
(91, 14, 1, '232', '760.00', 6, '1'),
(92, 13, 1, '300', '434.00', 6, '1'),
(93, 14, 10, '222', '7600.00', 8, '1'),
(94, 13, 200, '0', '86800.00', 8, '1'),
(95, 13, 300, '0', '130200.00', 8, '1'),
(96, 11, 1, '144', '125.00', 6, '1'),
(97, 11, 144, '0', '18000.00', 6, '1'),
(98, 15, 1, '', '455.00', 5, ''),
(99, 15, 1, '', '455.00', 6, ''),
(100, 16, 1, '', '1500.00', 6, ''),
(101, 12, 1, '392', '155.00', 8, '1'),
(102, 12, 1, '391', '155.00', 8, '1'),
(103, 15, 1, '', '455.00', 8, ''),
(104, 14, 1, '221', '760.00', 9, '1'),
(105, 17, 1, '25', '2200.00', 9, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_category`
--

INSERT INTO `tbl_category` (`cat_id`, `cat_name`, `cat_parent`, `cat_level`) VALUES
(4, 'Appareillage Terminal', 'Appareillage Terminal et raccordement batiment', 2),
(5, 'Appareillage Terminal et raccordement batiment', 'Aucune', 1),
(6, 'Accessoires et autre appareillage terminal', 'Appareillage Terminal', 3),
(7, 'Antivandale', 'Appareillage Terminal', 3),
(8, 'Boite Encastrement', 'Appareillage Terminal et raccordement batiment', 2),
(9, 'Accessoires et autre boite encastrement', 'Boite Encastrement', 3),
(10, 'Boite beton', 'Boite Encastrement', 3),
(11, 'Communication et securite', 'Aucune', 1),
(12, 'Acces', 'Communication et securite', 2),
(13, 'Communication  du batiment', 'Communication et securite', 2),
(14, 'Accessoires de fermeture', 'Acces', 3),
(15, 'Automatisme ouverture', 'Acces', 3),
(16, 'Audiovisuel et sonorisation', 'Communication du batiment', 3),
(17, 'Detection de presence et mouvement', 'Communication du batiment', 3),
(18, 'stylo', 'Aucune', 1),
(19, 'bic', 'stylo', 2),
(20, 'schneider', 'stylo', 2),
(21, 'crystal', 'bic', 3),
(22, 'Raclette Europe', 'Acune', 3),
(23, '', 'Aucune', 3),
(24, 'Luminaires', 'Aucune', 3),
(25, 'ZOOO', 'Aucune', 3),
(26, 'Lampes ZOOM', 'Aucune', 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `cashier_name`, `id_client`, `order_date`, `time_order`, `total`, `paid`, `due`, `remise`, `tva`, `payment_mode`) VALUES
(93, 'tnh', 'topsi', '2021-04-22', '19:44', 65000, 70000, -5000, 0, 0, ''),
(95, 'tnh', '', '2021-04-26', '05:01', 705000, 710000, -5000, 0, 0, ''),
(96, 'tnh', '', '2021-04-26', '10:40', 573500, 580000, -6500, 0, 0, ''),
(97, 'tnh', '', '2021-04-26', '18:57', 475000, 480000, -5000, 0, 0, ''),
(98, 'tnh', 'okok', '2021-06-06', '18:31', 320000, 320000, 0, 0, 0, ''),
(99, 'tnh', 'okok', '2021-06-09', '16:44', 301400, 305000, -3600, 0, 0, ''),
(100, 'tnh', 'common', '2021-07-03', '16:51', 195000, 190000, 5000, 0, 0, ''),
(101, 'tnh', 'common', '2021-07-03', '16:54', 2800, 5000, -2200, 0, 0, ''),
(103, 'tnh', 'common', '2021-07-07', '13:28', 22500, 25000, -2500, 0, 0, ''),
(105, 'tnh', 'common', '2021-07-19', '16:33', 90250, 100000, -9750, 4750, 0, ''),
(106, 'tnh', 'common', '2021-07-19', '16:35', 216000, 220000, -4000, 24000, 0, ''),
(108, 'good', 'common', '2021-09-07', '06:11', 475000, 480000, -5000, 0, 0, ''),
(109, 'caissier', 'common', '2021-09-08', '08:33', 2375, 2500, -125, 0, 0, ''),
(110, 'caissier', 'common', '2021-09-08', '08:40', 1625, 2000, -375, 0, 0, ''),
(111, 'caissier', 'common', '2021-09-08', '12:54', 875, 1000, -125, 0, 0, ''),
(122, 'caissier', 'common', '2021-09-10', '11:40', 3250, 5000, -1750, 0, 0, ''),
(124, 'operator1', 'common', '2021-09-12', '15:49', 8075000, 8075000, 8075000, 425001, 0, ''),
(125, 'operator1', 'common', '2022-06-09', '04:49', 3500, 4000, -500, 0, 0, ''),
(126, 'operator1', 'common', '2022-07-31', '17:42', 302100, 305000, -2900, 0, 0, ''),
(127, 'operator1', 'tnh', '2023-05-27', '14:35', 230000, 230000, 0, 0, 0, ''),
(128, 'operator1', 'okok', '2023-05-27', '14:37', 119450, 125000, -5550, 6250, 0, ''),
(129, 'operator1', 'nmoise', '2023-08-05', '07:28', 145000, 150000, -5000, 0, 0, ''),
(130, 'operator1', 'common', '2023-08-22', '22:23', 6000, 10000, -4000, 0, 0, ''),
(131, 'operator1', 'common', '2023-08-26', '18:57', 18000, 25000, -7000, 0, 0, ''),
(132, 'operator1', 'common', '2023-12-31', '08:36', 18000, 20000, -2000, 0, 0, ''),
(133, 'operator1', 'common', '2023-12-31', '12:09', 38000, 40000, -2000, 0, 0, ''),
(134, 'operator1', 'common', '2023-12-31', '17:01', 23000, 25000, -2000, 0, 0, ''),
(135, 'operator1', 'common', '2023-12-31', '17:03', 41000, 42000, -1000, 0, 0, ''),
(136, 'operator1', 'common', '2024-03-03', '06:23', 50255, 51000, 745, 4600, 0, ''),
(137, 'operator1', 'common', '2024-03-06', '05:46', 21465, 25000, 3535, 0, 3465, 'especes');

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
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_client`
--

INSERT INTO `tbl_invoice_client` (`invoice_id`, `id_client`, `name_client`, `order_date`, `time_order`, `total`, `Status`, `moyen_paiement`, `date_paiement`, `time_paiement`, `infos_paiement`) VALUES
(187, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-03', '13:04', 190000, 'deleted', '', '2021-06-05', '22:07:00', 'dadadaedada'),
(188, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-03', '13:15', 261400, 'deleted', '', '2021-06-05', '21:38:00', 'daeaeaedeaeaea'),
(189, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-07', '12:28', 70000, 'ordered', '', '2021-06-07', '12:28:00', ''),
(190, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-07', '12:46', 152100, 'delivered', '', '2021-06-07', '12:46:00', ''),
(191, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-11', '07:28', 355000, 'ordered', '', '2021-06-11', '07:28:00', ''),
(192, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-11', '14:04', 530000, 'ordered', '', '2021-06-11', '14:04:00', ''),
(193, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-11', '14:47', 95000, 'ordered', '', '2021-06-11', '14:47:00', ''),
(194, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-06-12', '15:14', 380000, 'ordered', '', '2021-06-12', '15:14:00', ''),
(195, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-06', '15:35', 450000, 'ordered', '', '2021-07-06', '15:35:00', ''),
(196, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-07', '10:16', 255000, 'ordered', '', '2021-07-07', '10:16:00', ''),
(197, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-10', '17:44', 75000, 'ordered', '', '2021-07-10', '17:44:00', ''),
(198, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-10', '17:51', 195000, 'ordered', '', '2021-07-10', '17:51:00', ''),
(199, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-13', '10:51', 160000, 'ordered', '', '2021-07-13', '10:51:00', ''),
(200, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-13', '11:05', 52100, 'ordered', '', '2021-07-13', '11:05:00', ''),
(201, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-13', '11:13', 280000, 'ordered', '', '2021-07-13', '11:13:00', ''),
(202, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-13', '11:21', 320000, 'ordered', '', '2021-07-13', '11:21:00', ''),
(203, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-07-13', '11:23', 192800, 'ordered', '', '2021-07-13', '11:23:00', ''),
(204, 'topsi', 'FRANCIS DUJARDIN SECEC', '2021-09-12', '14:32', 325000, 'ordered', '', '2021-09-12', '14:32:00', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_invoice_detail`
--

INSERT INTO `tbl_invoice_detail` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `remise`) VALUES
(68, 93, 15, 'DA0001', 'Bola Lampu Philips', 1, 'Kg', 65000, 65000, '2021-04-22', 0),
(70, 95, 12, 'TT0040', 'Triplek Sedang', 4, 'Kg', 95000, 380000, '2021-04-26', 0),
(71, 95, 15, 'DA0001', 'Bola Lampu Philips', 5, 'Kg', 65000, 325000, '2021-04-26', 0),
(72, 96, 16, 'TT0045', 'dadzada', 5, 'Kg', 700, 3500, '2021-04-26', 0),
(73, 96, 12, 'TT0040', 'Triplek Sedang', 6, 'Kg', 95000, 570000, '2021-04-26', 0),
(74, 97, 12, 'TT0040', 'Triplek Sedang', 5, 'Kg', 95000, 475000, '2021-04-26', 0),
(75, 98, 12, 'TT0040', 'Feuilles contre plaquet', 3, 'm', 95000, 285000, '2021-06-06', 0),
(76, 98, 14, 'RR0022', 'Round Cable Clips', 1, 'm', 35000, 35000, '2021-06-06', 0),
(77, 99, 17, 'dsdsds', 'Plaque Interrupteur', 4, 'Kg', 75000, 300000, '2021-06-09', 0),
(78, 99, 16, 'TT0045', 'Rallonge', 2, 'Kg', 700, 1400, '2021-06-09', 0),
(79, 100, 15, 'DA0001', 'Prise reseaux', 3, 'U', 65000, 195000, '2021-07-03', 0),
(80, 101, 16, 'TT0045', 'Rallonge', 4, 'Kg', 700, 2800, '2021-07-03', 0),
(81, 103, 21, '3333297301220', 'Prise Multifonctions', 3, 'U', 7500, 22500, '2021-07-07', 0),
(85, 105, 12, 'TT0040', 'Feuilles contre plaquet', 1, 'm', 95000, 90250, '2021-07-19', 4750),
(86, 106, 22, 'TTTZHZHZ8', 'Table chauffante', 2, 'U', 25000, 45000, '2021-07-19', 5000),
(87, 106, 12, 'TT0040', 'Feuilles contre plaquet', 2, 'm', 95000, 171000, '2021-07-19', 19000),
(89, 108, 12, 'TT0040', 'Feuilles contre plaquet', 5, 'm', 95000, 475000, '2021-09-07', 0),
(90, 109, 24, '670921', 'bic crystal new', 10, 'U', 150, 1500, '2021-09-08', 0),
(91, 109, 26, '6448889', 'bic ', 5, 'U', 175, 875, '2021-09-08', 0),
(92, 110, 26, '6448889', 'bic ', 5, 'U', 175, 875, '2021-09-08', 0),
(93, 110, 24, '670921', 'bic crystal new', 5, 'U', 150, 750, '2021-09-08', 0),
(94, 111, 49, '6448889', 'bic ', 5, 'U', 175, 875, '2021-09-08', 0),
(107, 122, 49, '6448889', 'bic ', 10, 'U', 175, 1750, '2021-09-10', 0),
(108, 122, 43, '670921', 'bic crystal', 10, 'U', 150, 1500, '2021-09-10', 0),
(110, 124, 32, 'TT0040', 'Feuilles contre plaquet', 68, 'm', 125000, 8075000, '2021-09-12', 425001),
(111, 125, 35, 'TT0045', 'Rallonge', 5, 'Kg', 700, 3500, '2022-06-09', 0),
(112, 126, 37, '000010', 'Prise', 3, 'Kg', 100000, 300000, '2022-07-31', 0),
(113, 126, 35, 'TT0045', 'Rallonge', 3, 'Kg', 700, 2100, '2022-07-31', 0),
(114, 127, 34, 'DA0001', 'Prise reseaux', 2, 'U', 65000, 130000, '2023-05-27', 0),
(115, 127, 37, '000010', 'Prise', 1, 'Kg', 100000, 100000, '2023-05-27', 0),
(116, 128, 32, 'TT0040', 'Feuilles contre plaquet', 1, 'm', 125000, 118750, '2023-05-27', 6250),
(117, 128, 35, 'TT0045', 'Rallonge', 1, 'Kg', 700, 700, '2023-05-27', 0),
(118, 129, 39, '3333297301220', 'Prise Multifonctions', 2, 'U', 7500, 15000, '2023-08-05', 0),
(119, 129, 34, 'DA0001', 'Prise reseaux', 2, 'U', 65000, 130000, '2023-08-05', 0),
(120, 130, 1, '000001', 'Prise Le Grand', 2, 'U', 3000, 6000, '2023-08-22', 0),
(121, 131, 1, '000001', 'Prise Le Grand', 1, 'U', 3000, 3000, '2023-08-26', 0),
(122, 131, 4, '000002', 'Multiprise', 1, 'U', 15000, 15000, '2023-08-26', 0),
(123, 132, 1, '000001', 'Prise Le Grand', 1, 'U', 3000, 3000, '2023-12-31', 0),
(124, 132, 4, '000002', 'Multiprise', 1, 'U', 15000, 15000, '2023-12-31', 0),
(125, 134, 7, '00003', 'Multiprises APC', 1, 'U', 20000, 20000, '2023-12-31', 0),
(126, 134, 1, '000001', 'Prise Le Grand', 1, 'U', 3000, 3000, '2023-12-31', 0),
(127, 135, 7, '00003', 'Multiprises APC', 1, 'U', 20000, 20000, '2023-12-31', 0),
(128, 135, 1, '000001', 'Prise Le Grand', 2, 'U', 3000, 6000, '2023-12-31', 0),
(129, 135, 4, '000002', 'Multiprise', 1, 'U', 15000, 15000, '2023-12-31', 0),
(130, 136, 1, '000001', 'Prise Le Grand', 2, 'U', 3000, 5400, '2024-03-03', 600),
(131, 136, 7, '00003', 'Multiprises APC', 2, 'U', 20000, 36000, '2024-03-03', 4000),
(132, 137, 1, '000001', 'Prise Le Grand', 6, 'U', 3000, 18000, '2024-03-06', 0);

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

CREATE TABLE IF NOT EXISTS `tbl_product_receipt` (
  `receipt_id` INT(11) NOT NULL AUTO_INCREMENT,
  `receipt_date` DATE NOT NULL,
  `product_id` INT(11) NOT NULL,
  `received_quantity` INT(11) NOT NULL,
  `supplier_name` VARCHAR(200) NOT NULL,
  `receipt_price` FLOAT(10,2) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `notes` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`receipt_id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_storeroom`, `place_in_store`, `img`) VALUES
(7, '000002', 'ddddd', 'Multiprise Peaceful Journey 2025', 'Antivandale', 'dsdsdsds 000', 'OKOK chaud Manioc', 10000, 185000, 12000, 0, 44, 7, 'U', 'Multiprise 2024 000 Peaceful', 'dddddqq 000', 'sdsdsdsd 000', '66db80b1d06b6.jpg'),
(8, '00003', '00003', 'Multiprises APC Close 2035', 'Accessoires et autre appareillage terminal', 'APC', 'OKOK chaud Manioc', 10000, 20000, 18005, 0, 10, 5, 'U', 'So Good 2000 Close', 'A444', 'A45555', '65914ae7ef50a.jpg'),
(9, '000122222', '55556666', 'Raclette Super Boom95', 'Raclette Europe', 'kkkckckck', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 55, 20, 'Kg', 'Raclette Europe de bon qualitÃ© Allemande.', 'kdkdkdkkd', 'kdkdkdkdk', '66085a4136e61.jpg'),
(10, 'H07VU25ROUGEC100', '00000', 'H07 VU 2.5 ROUGE C100 La PAZ', 'Accessoires et autre appareillage terminal', 'EUROPE', 'OKOK chaud Manioc', 196, 297, 287, 1, 18000, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '1', '660bf499859bd.jpg'),
(11, 'H07VU25BLEUC100', '0000', 'FILS BLEU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 13000, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750VFILS RIGIDE 2.5 BLEU ', '1', '2', '65bea2d4062ca.jpg'),
(22, 'CABLECUIVRENU25²T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 500, 100, 'm', 'CABLE CUIVRE NU', '7', '7', '65bec6d13a6f1.jpg'),
(23, 'CABLEVR35²ROUGET500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 500, 100, 'm', 'CABLE ALIMENTION ', '8', '8', '65beca39c061f.jpg'),
(24, 'CABLEVR35²BLEUT500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 500, 100, 'm', 'CABLE ALIMENTATION', '8', '8', '65becaed3a331.jpg'),
(25, 'ICTA32AF', 'ICTA32AF', 'GAINE ICTA32', 'CONDUITS,CANALISATIONS', 'COURANT', 'SDME', 550, 750, 745, 745, 5000, 500, 'm', 'GAINE ICTA 32', '1', '2', '65edc50d7842f.jpg'),
(30, '56666', '66666', 'Ampoule Bouillie', 'Luminaires', 'JDJDJJ', 'OKOK chaud Manioc', 200, 500, 300, 0, 53, 5, 'U', 'NNNCNC', 'jdjdjJ', 'JSJSJ', '669ed3f307250.jpg'),
(33, '0000071', '0000071', 'LE BOBOLO', 'Accessoires et autre appareillage terminal', 'MOATE', 'MOATE', 100, 200, 150, 0, 15, 10, 'U', 'BOBOLO', 'RAS', 'RAS', '66a15f7c756bc.jpg'),
(34, '000072', '000072', 'Namwondo', 'Accessoires et autre appareillage terminal', 'MOATE-NAM', 'MOATE-NAM', 100, 200, 150, 0, 45, 2, 'U', 'Namwondo', 'RAS', 'RAS', '66a160b921832.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`kd_satuan`, `nm_satuan`) VALUES
(16, 'Kg'),
(17, 'm'),
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
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_shop_item`
--

INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(1, 'bev_oyomabang', '000001', '00000', 'Prise Le Grand SO Good', 'Accessoires et autre appareillage terminal', 'Ã¹mdmdmd', 'OKOK chaud Manioc', 2000, 3000, 2500, 0, 55, 21, 'U', 'Prise Le Grand 2024', '445555', '64d81e70aac50.jpg'),
(2, 'bev_Biyemassi', '000001', '00000', 'Prise Le Grand SO Good', 'Accessoires et autre appareillage terminal', 'Ã¹mdmdmd', 'OKOK chaud Manioc', 2000, 3000, 2500, 0, 0, 21, 'U', 'Prise Le Grand 2024', '445555', '64d81e70aac50.jpg'),
(3, 'leti_nkolbisson', '000001', '00000', 'Prise Le Grand SO Good', 'Accessoires et autre appareillage terminal', 'Ã¹mdmdmd', 'OKOK chaud Manioc', 2000, 3000, 2500, 0, 0, 21, 'U', 'Prise Le Grand 2024', '445555', '64d81e70aac50.jpg'),
(4, 'bev_oyomabang', '000002', 'ddddd', 'Multiprise Peaceful Journey 2025', 'Antivandale', 'dsdsdsds 000', 'OKOK chaud Manioc', 10000, 185000, 12000, 0, 7, 7, 'U', 'Multiprise 2024 000 Peaceful', 'sdsdsdsd', '66db80b1d06b6.jpg'),
(5, 'bev_Biyemassi', '000002', 'ddddd', 'Multiprise Peaceful Journey 2025', 'Antivandale', 'dsdsdsds 000', 'OKOK chaud Manioc', 10000, 185000, 12000, 0, 0, 7, 'U', 'Multiprise 2024 000 Peaceful', 'sdsdsdsd', '66db80b1d06b6.jpg'),
(6, 'leti_nkolbisson', '000002', 'ddddd', 'Multiprise Peaceful Journey 2025', 'Antivandale', 'dsdsdsds 000', 'OKOK chaud Manioc', 10000, 185000, 12000, 0, 0, 7, 'U', 'Multiprise 2024 000 Peaceful', 'sdsdsdsd', '66db80b1d06b6.jpg'),
(7, 'bev_oyomabang', '00003', '00003', 'Multiprises APC Close 2035', 'Accessoires et autre appareillage terminal', 'APC', 'OKOK chaud Manioc', 10000, 20000, 18005, 0, 8, 5, 'U', 'So Good 2000 Close', 'A45555', '65914ae7ef50a.jpg'),
(8, 'bev_Biyemassi', '00003', '00003', 'Multiprises APC Close 2035', 'Accessoires et autre appareillage terminal', 'APC', 'OKOK chaud Manioc', 10000, 20000, 18005, 0, 0, 5, 'U', 'So Good 2000 Close', 'A45555', '65914ae7ef50a.jpg'),
(9, 'leti_nkolbisson', '00003', '00003', 'Multiprises APC Close 2035', 'Accessoires et autre appareillage terminal', 'APC', 'OKOK chaud Manioc', 10000, 20000, 18005, 0, 0, 5, 'U', 'So Good 2000 Close', 'A45555', '65914ae7ef50a.jpg'),
(10, 'bev_oyomabang', '000122222', '55556666', 'Raclette Super Boom95', 'Raclette Europe', 'kkkckckck', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 0, 20, 'Kg', 'Raclette Europe de bon qualitÃ© Allemande.', 'kdkdkdkdk', '65e32c7f46e26.jpg'),
(11, 'bev_Biyemassi', '000122222', '55556666', 'Raclette Super Boom95', 'Raclette Europe', 'kkkckckck', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 0, 20, 'Kg', 'Raclette Europe de bon qualitÃ© Allemande.', 'kdkdkdkdk', '65e32c7f46e26.jpg'),
(12, 'leti_nkolbisson', '000122222', '55556666', 'Raclette Super Boom95', 'Raclette Europe', 'kkkckckck', 'OKOK chaud Manioc', 1500, 3000, 2500, 0, 0, 20, 'Kg', 'Raclette Europe de bon qualitÃ© Allemande.', 'kdkdkdkdk', '65e32c7f46e26.jpg'),
(13, 'bev_oyomabang', '\0H07VU25ROUGEC100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 4995, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(14, 'bev_Biyemassi', '\0H07VU25ROUGEC100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(15, 'leti_nkolbisson', '\0H07VU25ROUGEC100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(16, 'bev_oyomabang', 'H07VU25BLEUC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 4950, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(17, 'bev_Biyemassi', 'H07VU25BLEUC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 0, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(18, 'leti_nkolbisson', 'H07VU25BLEUC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 0, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(19, 'bev_oyomabang', 'H07JAUNE', '0000', 'FILS Vert/Jaune bbabab95', 'Accessoires et autre appareillage terminal', 'EUROPE', 'OKOK chaud Manioc', 196, 295, 285, 0, 0, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(20, 'bev_Biyemassi', 'H07JAUNE', '0000', 'FILS Vert/Jaune bbabab95', 'Accessoires et autre appareillage terminal', 'EUROPE', 'OKOK chaud Manioc', 196, 295, 285, 0, 0, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(21, 'leti_nkolbisson', 'H07JAUNE', '0000', 'FILS Vert/Jaune bbabab95', 'Accessoires et autre appareillage terminal', 'EUROPE', 'OKOK chaud Manioc', 196, 295, 285, 0, 0, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(22, 'bev_oyomabang', '\0H07VU15ROUGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(23, 'bev_Biyemassi', '\0H07VU15ROUGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(24, 'leti_nkolbisson', '\0H07VU15ROUGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(25, 'bev_oyomabang', '\0H07VU15BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(26, 'bev_Biyemassi', '\0H07VU15BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(27, 'leti_nkolbisson', '\0H07VU15BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(28, 'bev_oyomabang', '\0H07VU15VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(29, 'bev_Biyemassi', '\0H07VU15VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(30, 'leti_nkolbisson', '\0H07VU15VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(31, 'bev_oyomabang', 'H07VU15VIOLETC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(32, 'bev_Biyemassi', 'H07VU15VIOLETC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(33, 'leti_nkolbisson', 'H07VU15VIOLETC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(34, 'bev_oyomabang', 'H07VU15ORANGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(35, 'bev_Biyemassi', 'H07VU15ORANGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(36, 'leti_nkolbisson', 'H07VU15ORANGEC100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(37, 'bev_oyomabang', 'U1000R2V3G25C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(38, 'bev_Biyemassi', 'U1000R2V3G25C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(39, 'leti_nkolbisson', 'U1000R2V3G25C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(40, 'bev_oyomabang', 'U1000R2V3G15C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(41, 'bev_Biyemassi', 'U1000R2V3G15C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(42, 'leti_nkolbisson', 'U1000R2V3G15C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(43, 'bev_oyomabang', 'CAT64PF/UTPC100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(44, 'bev_Biyemassi', 'CAT64PF/UTPC100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(45, 'leti_nkolbisson', 'CAT64PF/UTPC100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(46, 'bev_oyomabang', '\0CABLECOAXIAL17VATCC1', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(47, 'bev_Biyemassi', '\0CABLECOAXIAL17VATCC1', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(48, 'leti_nkolbisson', '\0CABLECOAXIAL17VATCC1', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(49, 'bev_oyomabang', 'CABLECUIVRENU25Â²T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(50, 'bev_Biyemassi', 'CABLECUIVRENU25Â²T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(51, 'leti_nkolbisson', 'CABLECUIVRENU25Â²T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(52, 'bev_oyomabang', '\0CABLEVR35Â²ROUGET500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(53, 'bev_Biyemassi', '\0CABLEVR35Â²ROUGET500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(54, 'leti_nkolbisson', '\0CABLEVR35Â²ROUGET500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(55, 'bev_oyomabang', 'CABLEVR35Â²BLEUT500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg'),
(56, 'bev_Biyemassi', 'CABLEVR35Â²BLEUT500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg'),
(57, 'leti_nkolbisson', 'CABLEVR35Â²BLEUT500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg'),
(58, 'bev_oyomabang', 'ICTA32AF', 'ICTA32AF', 'GAINE ICTA32', 'CONDUITS,CANALISATIONS', 'COURANT', 'SDME', 550, 750, 745, 745, 0, 0, 'm', 'GAINE ICTA 32', '2', '65edc50d7842f.jpg'),
(59, 'bev_Biyemassi', 'ICTA32AF', 'ICTA32AF', 'GAINE ICTA32', 'CONDUITS,CANALISATIONS', 'COURANT', 'SDME', 550, 750, 745, 745, 0, 0, 'm', 'GAINE ICTA 32', '2', '65edc50d7842f.jpg'),
(60, 'leti_nkolbisson', 'ICTA32AF', 'ICTA32AF', 'GAINE ICTA32', 'CONDUITS,CANALISATIONS', 'COURANT', 'SDME', 550, 750, 745, 745, 0, 0, 'm', 'GAINE ICTA 32', '2', '65edc50d7842f.jpg'),
(61, 'bev_oyomabang', 'HHKKK/001', 'dkdkdkdkd', 'ZOOOOOOOOOOOO237O', 'Accessoires et autre appareillage terminal', 'jjjej', 'OKOK chaud Manioc', 500, 1000, 900, 0, 0, 5, 'Kg', 'ndndndnd', 'D55', '660becc3e7ebc.jpg'),
(62, 'bev_Biyemassi', 'HHKKK/001', 'dkdkdkdkd', 'ZOOOOOOOOOOOO237O', 'Accessoires et autre appareillage terminal', 'jjjej', 'OKOK chaud Manioc', 500, 1000, 900, 0, 0, 5, 'Kg', 'ndndndnd', 'D55', '660becc3e7ebc.jpg'),
(63, 'leti_nkolbisson', 'HHKKK/001', 'dkdkdkdkd', 'ZOOOOOOOOOOOO237O', 'Accessoires et autre appareillage terminal', 'jjjej', 'OKOK chaud Manioc', 500, 1000, 900, 0, 0, 5, 'Kg', 'ndndndnd', 'D55', '660becc3e7ebc.jpg'),
(64, 'bev_oyomabang', '000022222', '2222222', 'Patapouf', '', 'polo', 'OKOK chaud Manioc', 150, 500, 250, 0, 0, 0, 'U', 'skdkdk', '44444', '66866aad0ca82.png'),
(65, 'bev_Biyemassi', '000022222', '2222222', 'Patapouf', '', 'polo', 'OKOK chaud Manioc', 150, 500, 250, 0, 0, 0, 'U', 'skdkdk', '44444', '66866aad0ca82.png'),
(66, 'leti_nkolbisson', '000022222', '2222222', 'Patapouf', '', 'polo', 'OKOK chaud Manioc', 150, 500, 250, 0, 0, 0, 'U', 'skdkdk', '44444', '66866aad0ca82.png'),
(67, 'bev_oyomabang', '44545455', '45545454', 'Lampe', 'Luminaires', 'jkjk', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'kdkdk', 'klllkkl', '66866b1bdfb0d.png'),
(68, 'bev_Biyemassi', '44545455', '45545454', 'Lampe', 'Luminaires', 'jkjk', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'kdkdk', 'klllkkl', '66866b1bdfb0d.png'),
(69, 'leti_nkolbisson', '44545455', '45545454', 'Lampe', 'Luminaires', 'jkjk', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'kdkdk', 'klllkkl', '66866b1bdfb0d.png'),
(70, 'bev_oyomabang', '555555', '6666666', 'Ampoule', '', 'Zoook', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'Pouozzo', 'papapap', '66866f065391c.png'),
(71, 'bev_Biyemassi', '555555', '6666666', 'Ampoule', '', 'Zoook', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'Pouozzo', 'papapap', '66866f065391c.png'),
(72, 'leti_nkolbisson', '555555', '6666666', 'Ampoule', '', 'Zoook', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 0, 'U', 'Pouozzo', 'papapap', '66866f065391c.png'),
(73, 'bev_oyomabang', '56666', '66666', 'Ampoule Bouillie', 'Luminaires', 'JDJDJJ', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 5, 'U', 'NNNCNC', 'JSJSJ', '669ed3f307250.jpg'),
(74, 'bev_Biyemassi', '56666', '66666', 'Ampoule Bouillie', 'Luminaires', 'JDJDJJ', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 5, 'U', 'NNNCNC', 'JSJSJ', '669ed3f307250.jpg'),
(75, 'leti_nkolbisson', '56666', '66666', 'Ampoule Bouillie', 'Luminaires', 'JDJDJJ', 'OKOK chaud Manioc', 200, 500, 300, 0, 0, 5, 'U', 'NNNCNC', 'JSJSJ', '669ed3f307250.jpg'),
(76, 'bev_oyomabang', '85858585', '5858585', 'AA Ampoule', 'Accessoires et autre appareillage terminal', 'Zu', 'OKOK chaud Manioc', 200, 600, 400, 0, 0, 0, 'Kg', 'hddhdh', 'gfrfrfrfr', '668674409c43d.png'),
(77, 'bev_Biyemassi', '85858585', '5858585', 'AA Ampoule', 'Accessoires et autre appareillage terminal', 'Zu', 'OKOK chaud Manioc', 200, 600, 400, 0, 0, 0, 'Kg', 'hddhdh', 'gfrfrfrfr', '668674409c43d.png'),
(78, 'leti_nkolbisson', '85858585', '5858585', 'AA Ampoule', 'Accessoires et autre appareillage terminal', 'Zu', 'OKOK chaud Manioc', 200, 600, 400, 0, 0, 0, 'Kg', 'hddhdh', 'gfrfrfrfr', '668674409c43d.png'),
(79, 'bev_oyomabang', '5858585', '8585858', 'ZOOM LAMPE', 'Lampes ZOOM', 'dededed', 'OKOK chaud Manioc', 200, 800, 400, 0, 0, 0, 'U', 'dhdddh', 'dededed', '668674b756885.png'),
(80, 'bev_Biyemassi', '5858585', '8585858', 'ZOOM LAMPE', 'Lampes ZOOM', 'dededed', 'OKOK chaud Manioc', 200, 800, 400, 0, 0, 0, 'U', 'dhdddh', 'dededed', '668674b756885.png'),
(81, 'leti_nkolbisson', '5858585', '8585858', 'ZOOM LAMPE', 'Lampes ZOOM', 'dededed', 'OKOK chaud Manioc', 200, 800, 400, 0, 0, 0, 'U', 'dhdddh', 'dededed', '668674b756885.png'),
(82, 'bev_oyomabang', '0000071', '0000071', 'LE BOBOLO', 'Accessoires et autre appareillage terminal', 'MOATE', 'MOATE', 100, 200, 150, 0, 0, 0, 'U', 'BOBOLO', 'RAS', '66a15f7c756bc.jpg'),
(83, 'bev_Biyemassi', '0000071', '0000071', 'LE BOBOLO', 'Accessoires et autre appareillage terminal', 'MOATE', 'MOATE', 100, 200, 150, 0, 0, 0, 'U', 'BOBOLO', 'RAS', '66a15f7c756bc.jpg'),
(84, 'leti_nkolbisson', '0000071', '0000071', 'LE BOBOLO', 'Accessoires et autre appareillage terminal', 'MOATE', 'MOATE', 100, 200, 150, 0, 0, 0, 'U', 'BOBOLO', 'RAS', '66a15f7c756bc.jpg'),
(85, 'bev_oyomabang', '000072', '000072', 'Namwondo', 'Accessoires et autre appareillage terminal', 'MOATE-NAM', 'MOATE-NAM', 100, 200, 150, 0, 0, 0, 'U', 'Namwondo', 'RAS', '66a160b921832.jpg'),
(86, 'bev_Biyemassi', '000072', '000072', 'Namwondo', 'Accessoires et autre appareillage terminal', 'MOATE-NAM', 'MOATE-NAM', 100, 200, 150, 0, 0, 0, 'U', 'Namwondo', 'RAS', '66a160b921832.jpg'),
(87, 'leti_nkolbisson', '000072', '000072', 'Namwondo', 'Accessoires et autre appareillage terminal', 'MOATE-NAM', 'MOATE-NAM', 100, 200, 150, 0, 0, 0, 'U', 'Namwondo', 'RAS', '66a160b921832.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `magasin`, `role`, `is_active`) VALUES
(6, 'tnh', 'Tognia', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Admin', 1),
(7, 'operator1', 'KEBO KAKI', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Operator', 1),
(8, 'good', 'GOAOAOAO JZJZJZHJ', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Admin', 1),
(9, 'respo', 'NVBZ', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Responsable', 1),
(12, 'laeticia', 'YAKAM Laeticia', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Admin', 1),
(13, 'amelia', 'yakam', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Responsable', 1),
(15, 'OnceAgain', 'OBOBOGO', '7c222fb2927d828af22f592134e8932480637c0d', 'Eleveur', 'Responsable', 1);

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
(10, 'HENRI', 'TNH', 'TOGNIA', 'NGOUSSO Fabrique', 'nubizunltd@gmail.com', 'TNH', 'tnh', 'a1Bz20ydqelm8m1wql25d55ad283aa400af464c76d713c07ad', ''),
(11, 'PEACE', 'BE', 'STILL', 'OMNISPORT', 'tognia@gmail.com', '672569213', 'okok', '7c222fb2927d828af22f592134e8932480637c0d', 'particulier'),
(12, 'FRANCIS', 'DUJARDIN', 'SECEC', 'RUE 78', 'trajectoirei@live.fr', '677777777', 'topsi', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'entreprise'),
(13, 'zfzfzfzfzf', 'fzfzfzfzfz', 'zfzfzfzfzfzf', 'NGOUSSO Fabrique', 'trajectoirei@live.fr', '69999999', 'lepile', '7c222fb2927d828af22f592134e8932480637c0d', 'particulier'),
(14, 'MOISE', ' ', 'NGNOKAM', 'Ngousso Fabrique', 'ngnokamoise@yahoo.fr', '699878271', 'nmoise', '7c222fb2927d828af22f592134e8932480637c0d', 'particulier'),
(15, 'BOBIBO', 'BOBIBO', 'BOBIBO', 'BOBIBO', 'awarenessera40@gmail.com', '000065', 'htognia', '7c222fb2927d828af22f592134e8932480637c0d', 'particulier');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
