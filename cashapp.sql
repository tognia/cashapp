-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 13 déc. 2025 à 16:46
-- Version du serveur : 8.0.31
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cashapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int NOT NULL AUTO_INCREMENT,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(50) NOT NULL,
  `libelle_agence` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ville` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id`, `code_agence`, `libelle_agence`, `email`, `tel`, `ville`) VALUES
(5, 'Eleveur', 'Eleveur', 'ngnokamoise@yahoo.fr', '673236929', 'Yaounde');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int NOT NULL AUTO_INCREMENT,
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
  `customer_id` int NOT NULL AUTO_INCREMENT,
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

-- --------------------------------------------------------

--
-- Structure de la table `delta`
--

DROP TABLE IF EXISTS `delta`;
CREATE TABLE IF NOT EXISTS `delta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_updated` varchar(50) NOT NULL,
  `operation` varchar(50) NOT NULL,
  `data` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
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
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `track_num` int NOT NULL,
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
  `order_details_id` int NOT NULL AUTO_INCREMENT,
  `prod_id` int NOT NULL,
  `prod_qty` int NOT NULL,
  `total_qty` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `user_id` int NOT NULL,
  `order_id` varchar(30) NOT NULL,
  PRIMARY KEY (`order_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `cust_id` int NOT NULL,
  `sales_id` int NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `user_id` int NOT NULL,
  `due` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `or_no` int NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `prod_id` int NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(50) NOT NULL,
  `prod_desc` varchar(500) NOT NULL,
  `prod_qty` int NOT NULL,
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

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `sales_id` int NOT NULL AUTO_INCREMENT,
  `cust_id` int NOT NULL,
  `user_id` int NOT NULL,
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
  `sales_details_id` int NOT NULL AUTO_INCREMENT,
  `sales_id` int NOT NULL,
  `prod_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int NOT NULL,
  PRIMARY KEY (`sales_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active | 0=Inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
  `suplier_id` int NOT NULL AUTO_INCREMENT,
  `suplier_name` varchar(100) NOT NULL,
  `suplier_address` varchar(100) NOT NULL,
  `suplier_contact` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `note` varchar(500) NOT NULL,
  PRIMARY KEY (`suplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `supliers`
--

INSERT INTO `supliers` (`suplier_id`, `suplier_name`, `suplier_address`, `suplier_contact`, `contact_person`, `note`) VALUES
(4, 'ECONOMIC FOOD & SERVICE SA', 'YAOUNDE', '690201978', 'ECONOMIC FOOD & SERVICE SA', 'ECONOMIC FOOD & SERVICE SA'),
(5, 'ETS K. ALIMANTATION GENERALE', 'YAOUNDE', '653339849', 'ETS K. ALIMANTATION GENERALE', 'ETS K. ALIMANTATION GENERALE'),
(6, 'SOCOGEN SARL', 'YAOUNDE', '222237749', 'SOCOGEN SARL', 'SOCOGEN SARL');

-- --------------------------------------------------------

--
-- Structure de la table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `supp_id` int NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(100) NOT NULL,
  `supp_address` varchar(200) NOT NULL,
  `supp_contact` varchar(50) NOT NULL,
  `supp_email` varchar(50) NOT NULL,
  PRIMARY KEY (`supp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `cat_id` int NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(200) NOT NULL,
  `cat_parent` varchar(150) NOT NULL,
  `cat_level` int NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_name` (`cat_name`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_category`
--

INSERT INTO `tbl_category` (`cat_id`, `cat_name`, `cat_parent`, `cat_level`) VALUES
(10, 'SAVONS', 'Aucune', 3),
(11, 'SAVONS TOILETTE', 'SAVONS', 3),
(12, 'SAVONS MENAGE', 'SAVONS', 3),
(13, 'SARDINES', 'Aucune', 3),
(14, 'CHOCOLATS', 'Aucune', 3),
(15, 'HUILES', 'Aucune', 3),
(16, 'HUILE CUISINE', 'HUILES', 3),
(17, 'LAIT DE TOILETTES', 'LAITS', 3),
(18, 'SPAGHETTI', 'Aucune', 3),
(19, 'LAITS', 'Aucune', 3),
(20, 'CHOCOLATS EN POUDRE', 'CHOCOLATS', 3),
(21, 'LAIT EN POUDRE', 'LAITS', 3),
(22, 'DETERGENTS', 'Aucune', 3),
(23, 'JAVELS', 'Aucune', 3),
(24, 'SAVONS LIQUIDE', 'SAVONS', 3),
(25, 'SOUPLINE', 'SAVONS', 3),
(26, 'PHOSPHATINE', 'Aucune', 3),
(27, 'MAYONNAISES', 'Aucune', 3),
(28, 'MACARONI', 'Aucune', 3),
(29, 'BISCUITS', 'Aucune', 3),
(30, 'SUCRES', 'Aucune', 3),
(31, 'EPONGES', 'Aucune', 3),
(32, 'RIZ', 'Aucune', 3),
(33, 'PAPIERS HYGIENIQUE', 'Aucune', 3),
(34, 'INSECTICIDES', 'Aucune', 3),
(35, 'DENTIFRICES', 'Aucune', 3),
(36, 'BROSSES', 'Aucune', 3),
(37, 'BROSSES A DENT', 'BROSSES', 3),
(38, 'BROSSES A LINGE', 'BROSSES', 3),
(39, 'FROMAGES', 'Aucune', 3),
(40, 'COUCHES', 'Aucune', 3),
(41, 'CAFE', 'Aucune', 3),
(42, 'CUBES', 'Aucune', 3),
(43, 'BEURRES', 'Aucune', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin`
--

DROP TABLE IF EXISTS `tbl_commandes_magasin`;
CREATE TABLE IF NOT EXISTS `tbl_commandes_magasin` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `cashier_name` varchar(100) NOT NULL,
  `shop` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin_details`
--

DROP TABLE IF EXISTS `tbl_commandes_magasin_details`;
CREATE TABLE IF NOT EXISTS `tbl_commandes_magasin_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `shop` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice`
--

DROP TABLE IF EXISTS `tbl_invoice`;
CREATE TABLE IF NOT EXISTS `tbl_invoice` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
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
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client`
--

DROP TABLE IF EXISTS `tbl_invoice_client`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_client` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
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
  `invoice_id` int NOT NULL AUTO_INCREMENT,
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

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client_delivered`
--

DROP TABLE IF EXISTS `tbl_invoice_client_delivered`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_client_delivered` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
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

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail`
--

DROP TABLE IF EXISTS `tbl_invoice_detail`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `remise` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail_client`
--

DROP TABLE IF EXISTS `tbl_invoice_detail_client`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_detail_client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `Status` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product`
--

DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_code` char(50) NOT NULL,
  `product_sku` varchar(30) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `product_brand` varchar(50) NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `purchase_price` float(10,0) NOT NULL,
  `sell_price` float(10,0) NOT NULL,
  `min_price` int NOT NULL,
  `discount` float NOT NULL,
  `stock` int NOT NULL,
  `min_stock` int NOT NULL,
  `product_satuan` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `place_in_storeroom` varchar(50) NOT NULL,
  `place_in_store` varchar(50) NOT NULL,
  `img` varchar(200) NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`,`product_name`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_storeroom`, `place_in_store`, `img`) VALUES
(38, 'SAV_AZ001', 'SAV_AZ001', 'SAVON AZUR 400g', 'SAVONS MENAGE', 'AZUR', 'ECONOMIC FOOD & SERVICE SA', 342, 400, 400, 0, 60, 10, 'g', 'SAVON AZUR 400g', 'RAS', 'RAS', '693d669be2c05.png'),
(39, 'SAV_AZ002', 'SAV_AZ002', 'SAVON AZUR 700g', 'SAVONS MENAGE', 'AZUR', 'ECONOMIC FOOD & SERVICE SA', 597, 700, 700, 0, 36, 10, 'g', 'SAV_AZ002', 'RAS', 'RAS', '693d67d66c7fa.png'),
(40, 'SAV_MAYOR001', 'SAV_MAYOR001', 'SAVON MAYOR 360g', 'SAVONS MENAGE', 'MAYOR', 'ECONOMIC FOOD & SERVICE SA', 292, 350, 350, 0, 60, 10, 'g', 'SAVON MAYOR 360g', 'RAS', 'RAS', '693d688e3b610.png'),
(41, '8994420204013', '8994420204013', 'SARDINE ROMA PIMENTEE 125g', 'SARDINES', 'ROMA', 'ECONOMIC FOOD & SERVICE SA', 370, 500, 500, 0, 100, 10, 'g', 'SARDINE ROMA PIMENTEE 125g', 'RAS', 'RAS', '693d69d561161.png'),
(42, '8994420204365', '8994420204365', 'SARDINE AKADI NON PIMENTEE 125g', 'SARDINES', 'AKADI', 'ECONOMIC FOOD & SERVICE SA', 380, 500, 500, 0, 100, 10, 'g', 'SARDINE AKADI NON PIMENTEE 125g', 'RAS', 'RAS', '693d6a99755ba.png'),
(43, '6171100011709', '6171100011709', 'TARTINA 1kg', 'CHOCOLATS', 'chococam', 'ECONOMIC FOOD & SERVICE SA', 2750, 3000, 3000, 0, 12, 5, 'Kg', 'no price', 'RAS', 'RAS', '693d6c4970033.png'),
(44, '6171100015943', '6171100015943', 'tartina 380', 'CHOCOLATS', 'CHOCOCAM', 'ECONOMIC FOOD & SERVICE SA', 1312, 1500, 1500, 0, 24, 5, 'g', 'no price', 'RAS', 'RAS', '693d6ed936c9f.png'),
(45, '6171100014526', '6171100014526', 'TARTINA 740g', 'CHOCOLATS', 'CHOCOCAM', 'ECONOMIC FOOD & SERVICE SA', 1988, 2200, 2200, 0, 24, 5, 'g', 'no price', 'RAS', 'RAS', '693d703beee4b.png'),
(46, '6171100000017', '6171100000017', 'HUILE MAYOR 1L', 'HUILE CUISINE', 'MAYOR', 'ECONOMIC FOOD & SERVICE SA', 1400, 1500, 1500, 0, 75, 10, 'l', 'HUILE MAYOR 1L', 'RAS', 'RAS', '693d715d930c9.png'),
(47, '6171100080293', '6171100080293', 'SPAGHETTI MAMA 250g', 'SPAGHETTI', 'SPAGHETTI', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 40, 10, 'g', 'SPAGHETTI MAMA 250g', 'RAS', 'RAS', '693d72999bb27.png'),
(48, '6289713636865', '6289713636865', 'SPAGHETTI MAMA 500g', 'SPAGHETTI', 'spaghetti', 'ECONOMIC FOOD & SERVICE SA', 350, 450, 450, 0, 20, 5, 'g', 'SPAGHETTI MAMA 500g', 'RAS', 'RAS', '693d7356bd2e4.png'),
(49, '3760277891387', '3760277891387', 'LA PASTA 500g', 'SPAGHETTI', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 425, 500, 500, 0, 20, 5, 'g', 'LA PASTA 500g', 'RAS', 'RAS', '693d74385062b.png'),
(50, '6033000681140', '6033000681140', 'LAIT NIDO NUTRIPAK 20g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 125, 200, 200, 0, 180, 10, 'g', 'LAIT NIDO NUTRIPAK 20g', 'RAS', 'RAS', '693d75a682a08.png'),
(51, '6171100032063', '6171100032063', 'LAIT NIDO NUTRIPAK 12g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 79, 100, 100, 0, 260, 10, 'g', 'LAIT NIDO NUTRIPAK 12g', 'RAS', 'RAS', '693d76978c3ca.png'),
(52, '6033000681041', '6033000681041', 'LAIT NIDO CHOCO 30g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 160, 10, 'g', 'LAIT NIDO CHOCO 30g', 'RAS', 'RAS', '693d777d59227.png'),
(53, '6009188007829', '6009188007829', 'LAIT NIDO 1+ 400g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 3125, 3500, 3500, 0, 12, 5, 'g', 'LAIT NIDO 1+ 400g', 'RAS', 'RAS', '693d781fc50d6.png'),
(54, '6009188008062', '6009188008062', 'LAIT NIDO 400g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 3083, 3500, 3500, 0, 12, 5, 'g', 'LAIT NIDO 400g', 'RAS', 'RAS', '693d78e6dfd4c.png'),
(55, '6171100014403', '6171100014403', 'MATINAL 200g', 'CHOCOLATS EN POUDRE', 'MATINAL', 'ECONOMIC FOOD & SERVICE SA', 1229, 1500, 1500, 0, 24, 10, 'g', 'MATINAL 200g', 'RAS', 'RAS', '693d7985b66a6.png'),
(56, '6171100014427', '6171100014427', 'MATINAL 800g', 'CHOCOLATS EN POUDRE', 'MATINAL', 'ECONOMIC FOOD & SERVICE SA', 3583, 4000, 4000, 0, 6, 2, 'g', 'MATINAL 800g', 'RAS', 'RAS', '693d7a000be55.png'),
(57, '6904542608486', '6904542608486', 'DETERGENT OZIL 25g', 'DETERGENTS', 'OZIL', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 156, 10, 'g', 'DETERGENT OZIL 25g', 'RAS', 'RAS', '693d7afc9fdb5.png'),
(58, '1010010003310', '1010010003310', 'DETERGENT BRIL 25g', 'DETERGENTS', 'BRIL', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 156, 10, 'g', 'DETERGENT BRIL 25g', 'RAS', 'RAS', '693d7b946ba3d.png'),
(59, '6212801002105', '6212801002105', 'DETERGENT OLA 25g', 'DETERGENTS', 'OLA', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 156, 10, 'g', 'DETERGENT OLA 25g', 'RAS', 'RAS', '693d7c59f3109.png'),
(60, '6210290024219', '6210290024219', 'DETERGENT MADAR 400g', 'DETERGENTS', 'MADAR', 'ECONOMIC FOOD & SERVICE SA', 575, 700, 700, 0, 20, 5, 'g', 'DETERGENT MADAR 400g', 'RAS', 'RAS', '693d7d0c7d8fc.png'),
(61, '6181006000394', '6181006000394', 'JAVEL LA CROIX 800ml', 'JAVELS', 'LACROIX', 'ECONOMIC FOOD & SERVICE SA', 967, 1100, 1100, 0, 15, 5, 'ml', 'JAVEL LA CROIX 800ml', 'RAS', 'RAS', '693d7d985f571.png'),
(62, '8718951272927', '8718951272927', 'PAX CITRON 800ml', 'DETERGENTS', 'PAX', 'ECONOMIC FOOD & SERVICE SA', 1633, 1800, 1800, 0, 15, 5, 'ml', 'PAX CITRON 800ml', 'RAS', 'RAS', '693d7e3d4ecd5.png'),
(63, '8718951664289', '8718951664289', 'SOUPLINE GD AIR 1.5L', 'SOUPLINE', 'SOUPLINE', 'ECONOMIC FOOD & SERVICE SA', 3497, 3600, 3600, 0, 12, 5, 'l', 'SOUPLINE GD AIR 1.5L', 'RAS', 'RAS', '693d7ee15fbd3.png'),
(64, '3041091879219', '3041091879219', 'PHOSPHATINE LACTEE FRUITS 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 12, 5, 'g', 'PHOSPHATINE LACTEE FRUITS 190g', 'RAS', 'RAS', '693d7fb9b081d.png'),
(65, '3041091879233', '3041091879233', 'PHOSPHATINE LACTEE ?ULTICEREALES 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 12, 5, 'g', 'PHOSPHATINE LACTEE ?ULTICEREALES 190g', 'RAS', 'RAS', '693d80456059f.png'),
(66, '3041091879257', '', 'PHOSPHATINE LACTEE SAV BISCUITE 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 12, 5, 'g', 'PHOSPHATINE LACTEE SAV BISCUITE 190g', 'RAS', 'RAS', '693d80c4d1504.png'),
(67, '8718182020137', '8718182020137', 'MAYONNAISE ARMANTI 500 ML', 'MAYONNAISES', 'ARMANTI', 'ECONOMIC FOOD & SERVICE SA', 1333, 1500, 1500, 0, 12, 5, 'ml', 'MAYONNAISE ARMANTI 500 ML', 'RAS', 'RAS', '693d82b4aece5.png'),
(68, '8718182022827', '8718182022827', 'MAYONNAISE BROLI 250 ML', 'MAYONNAISES', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 1000, 1200, 1200, 0, 12, 5, 'ml', 'MAYONNAISE BROLI 250 ML', 'RAS', 'RAS', '693d83e3885c6.png'),
(69, '8718182020083', '8718182020083', 'MAYONNAISE BROLI 500 ML', 'MAYONNAISES', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 1250, 1500, 1500, 0, 12, 5, 'ml', 'MAYONNAISE BROLI 500 ML', 'RAS', 'RAS', '693d846cd48b0.png'),
(70, '6281842197132', '6281842197132', 'MACARONI MAMA 250G', 'MACARONI', 'MAMA', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 40, 10, 'g', 'MACARONI MAMA 250G', 'RAS', 'RAS', '693d8533eb107.png'),
(71, '8901719101014', '', 'BISCUIT PARLE G 32g', 'BISCUITS', 'PARLE G', 'ECONOMIC FOOD & SERVICE SA', 10, 50, 50, 0, 480, 30, 'g', 'BISCUIT PARLE G 32g', 'RAS', 'RAS', '693d8681af147.png'),
(72, '4600597494303', '4600597494303', 'BISCUIT PETIT FOOTBALL 16G', 'BISCUITS', 'RIO', 'ECONOMIC FOOD & SERVICE SA', 18, 25, 25, 0, 250, 20, 'g', 'BISCUIT PETIT FOOTBALL 16G', 'RAS', 'RAS', '693d8886ada8c.png'),
(73, 'EPON_METAL001', 'EPON_METAL001', 'EPONGE METALLIQUE IDEAL', 'EPONGES', 'IDEL', 'ECONOMIC FOOD & SERVICE SA', 50, 100, 100, 0, 360, 10, 'g', 'EPONGE METALLIQUE IDEAL', 'RAS', 'RAS', '693d8bdab2b1e.png'),
(74, '8718182020892', '8718182020892', 'RIZ BROLI PARFUME 5kg', 'RIZ', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 7200, 8000, 8000, 0, 5, 2, 'g', 'RIZ BROLI PARFUME 5kg', 'RAS', 'RAS', '693d8cfc32daa.png'),
(75, '3590124001016', '3590124001016', 'PAPIER HYGIENIQUE SITA', 'PAPIERS HYGIENIQUE', 'SITA', 'ECONOMIC FOOD & SERVICE SA', 240, 300, 300, 0, 96, 10, 'g', 'PAPIER HYGIENIQUE SITA', 'RAS', 'RAS', '693d8db699cd3.png'),
(76, '8901719912627', '8901719912627', 'BISCUIT FABIO 51g', 'BISCUITS', 'FABIO', 'ECONOMIC FOOD & SERVICE SA', 82, 100, 100, 0, 72, 10, 'g', 'BISCUIT FABIO 51g', 'RAS', 'RAS', '693d8ee207289.png'),
(77, '6925420056332', '6925420056332', 'INSECTICIDE SPIRAL TIGER', 'INSECTICIDES', 'TIGER', 'ECONOMIC FOOD & SERVICE SA', 233, 300, 300, 0, 60, 10, 'pack', 'INSECTICIDE SPIRAL TIGER', 'ras', 'ras', '693d900b5eb31.png'),
(78, '6171100014281', '6171100014281', 'MAMBO NOIR 25g', 'CHOCOLATS', 'MAMBO', 'ECONOMIC FOOD & SERVICE SA', 168, 200, 200, 0, 200, 10, 'g', 'MAMBO NOIR 25g', 'ras', 'ras', '693d918095b1c.png'),
(79, '8718182020144', '8718182020144', 'MAYONAISE ARMANTINE 1kg', 'MAYONNAISES', 'MAYONAISE', 'ECONOMIC FOOD & SERVICE SA', 2667, 3000, 3000, 0, 6, 2, 'Kg', 'MAYONAISE ARMANTINE 1kg', 'ras', 'ras', '693d9252e9f29.png'),
(80, '6920354817816', '6920354817816', 'COLGATE HERBAL 70g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 702, 1000, 1600, 0, 12, 3, 'g', 'COLGATE HERBAL 70g', 'ras', 'ras', '693d93f2323bd.png'),
(81, '6920354817809', '6920354817809', 'COLGATE HERBAL 35g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 396, 500, 500, 0, 24, 5, 'g', 'COLGATE HERBAL 35g', 'ras', 'ras', '693d94880de72.png'),
(82, '6920354817830', '6920354817830', 'COLGATE HERBAL 175g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1417, 1700, 1500, 0, 12, 3, 'g', 'COLGATE HERBAL 175g', 'ras', 'ras', '693d955dd26c8.png'),
(83, '6920354817823', '6920354817823', 'COLGATE HERBAL 140g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1317, 1500, 1500, 0, 12, 3, 'g', 'COLGATE HERBAL 140g', 'ras', 'ras', '693d964c0510f.png'),
(84, '8901314115010', '8901314115010', 'COLGATE MAX FRESH 130g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 932, 1200, 1200, 0, 12, 3, 'g', 'COLGATE MAX FRESH 130g', 'ras', 'ras', '693d9743a07d7.png'),
(85, '8718951350885', '8718951350885', 'COLGATE CHARBON 120g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1375, 1500, 1500, 0, 12, 3, 'g', 'COLGATE CHARBON 120g', 'ras', 'ras', '693d97a9db021.png');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product_receipt`
--

DROP TABLE IF EXISTS `tbl_product_receipt`;
CREATE TABLE IF NOT EXISTS `tbl_product_receipt` (
  `receipt_id` int NOT NULL AUTO_INCREMENT,
  `receipt_date` date NOT NULL,
  `product_id` int NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_sku` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `received_quantity` int NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `receipt_price` float(10,2) NOT NULL,
  `user_id` int NOT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`receipt_id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product_shipment`
--

DROP TABLE IF EXISTS `tbl_product_shipment`;
CREATE TABLE IF NOT EXISTS `tbl_product_shipment` (
  `shipment_id` int NOT NULL AUTO_INCREMENT,
  `shipment_date` date NOT NULL,
  `product_id` int NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_sku` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `shipped_quantity` int NOT NULL,
  `code_agence` varchar(30) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `delivery_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`shipment_id`),
  KEY `fk_ship_product_id` (`product_id`),
  KEY `fk_ship_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_provision`
--

DROP TABLE IF EXISTS `tbl_provision`;
CREATE TABLE IF NOT EXISTS `tbl_provision` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `qte` int NOT NULL,
  `user` varchar(25) NOT NULL,
  `observations` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_satuan`
--

DROP TABLE IF EXISTS `tbl_satuan`;
CREATE TABLE IF NOT EXISTS `tbl_satuan` (
  `kd_satuan` int NOT NULL AUTO_INCREMENT,
  `nm_satuan` varchar(20) NOT NULL,
  PRIMARY KEY (`kd_satuan`),
  UNIQUE KEY `nm_satuan` (`nm_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`kd_satuan`, `nm_satuan`) VALUES
(21, 'g'),
(16, 'Kg'),
(22, 'l'),
(17, 'm'),
(23, 'ml'),
(19, 'pack'),
(18, 'U');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shop_item`
--

DROP TABLE IF EXISTS `tbl_shop_item`;
CREATE TABLE IF NOT EXISTS `tbl_shop_item` (
  `product_id` int NOT NULL AUTO_INCREMENT,
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
  `stock` int NOT NULL,
  `min_stock` int NOT NULL,
  `product_satuan` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `place_in_store` varchar(50) NOT NULL,
  `img` varchar(200) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_shop_item`
--

INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(94, 'Eleveur', 'SAV_AZ001', 'SAV_AZ001', 'SAVON AZUR 400g', 'SAVONS MENAGE', 'AZUR', 'ECONOMIC FOOD & SERVICE SA', 342, 400, 400, 0, 0, 0, 'g', 'SAVON AZUR 400g', 'RAS', '693d669be2c05.png'),
(95, 'Eleveur', 'SAV_AZ002', 'SAV_AZ002', 'SAVON AZUR 700g', 'SAVONS MENAGE', 'AZUR', 'ECONOMIC FOOD & SERVICE SA', 597, 700, 700, 0, 0, 0, 'g', 'SAV_AZ002', 'RAS', '693d67d66c7fa.png'),
(96, 'Eleveur', 'SAV_MAYOR001', 'SAV_MAYOR001', 'SAVON MAYOR 360g', 'SAVONS MENAGE', 'MAYOR', 'ECONOMIC FOOD & SERVICE SA', 292, 350, 350, 0, 0, 0, 'g', 'SAVON MAYOR 360g', 'RAS', '693d688e3b610.png'),
(97, 'Eleveur', '8994420204013', '8994420204013', 'SARDINE ROMA PIMENTEE 125g', 'SARDINES', 'ROMA', 'ECONOMIC FOOD & SERVICE SA', 370, 500, 500, 0, 0, 0, 'g', 'SARDINE ROMA PIMENTEE 125g', 'RAS', '693d69d561161.png'),
(98, 'Eleveur', '8994420204365', '8994420204365', 'SARDINE AKADI NON PIMENTEE 125g', 'SARDINES', 'AKADI', 'ECONOMIC FOOD & SERVICE SA', 380, 500, 500, 0, 0, 0, 'g', 'SARDINE AKADI NON PIMENTEE 125g', 'RAS', '693d6a99755ba.png'),
(99, 'Eleveur', '6171100011709', '6171100011709', 'TARTINA 1kg', 'CHOCOLATS', 'chococam', 'ECONOMIC FOOD & SERVICE SA', 2750, 3000, 3000, 0, 0, 5, 'Kg', 'no price', 'RAS', '693d6c4970033.png'),
(100, 'Eleveur', '6171100015943', '6171100015943', 'tartina 380', 'CHOCOLATS', 'CHOCOCAM', 'ECONOMIC FOOD & SERVICE SA', 1312, 1500, 1500, 0, 0, 0, 'g', 'no price', 'RAS', '693d6ed936c9f.png'),
(101, 'Eleveur', '6171100014526', '6171100014526', 'TARTINA 740g', 'CHOCOLATS', 'CHOCOCAM', 'ECONOMIC FOOD & SERVICE SA', 1988, 2200, 2200, 0, 0, 0, 'g', 'no price', 'RAS', '693d703beee4b.png'),
(102, 'Eleveur', '6171100000017', '6171100000017', 'HUILE MAYOR 1L', 'HUILE CUISINE', 'MAYOR', 'ECONOMIC FOOD & SERVICE SA', 1400, 1500, 1500, 0, 0, 10, 'l', 'HUILE MAYOR 1L', 'RAS', '693d715d930c9.png'),
(103, 'Eleveur', '6171100080293', '6171100080293', 'SPAGHETTI MAMA 250g', 'SPAGHETTI', 'SPAGHETTI', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 0, 0, 'g', 'SPAGHETTI MAMA 250g', 'RAS', '693d72999bb27.png'),
(104, 'Eleveur', '6289713636865', '6289713636865', 'SPAGHETTI MAMA 500g', 'SPAGHETTI', 'spaghetti', 'ECONOMIC FOOD & SERVICE SA', 350, 450, 450, 0, 0, 0, 'g', 'SPAGHETTI MAMA 500g', 'RAS', '693d7356bd2e4.png'),
(105, 'Eleveur', '3760277891387', '3760277891387', 'LA PASTA 500g', 'SPAGHETTI', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 425, 500, 500, 0, 0, 0, 'g', 'LA PASTA 500g', 'RAS', '693d74385062b.png'),
(106, 'Eleveur', '6033000681140', '6033000681140', 'LAIT NIDO NUTRIPAK 20g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 125, 200, 200, 0, 0, 0, 'g', 'LAIT NIDO NUTRIPAK 20g', 'RAS', '693d75a682a08.png'),
(107, 'Eleveur', '6171100032063', '6171100032063', 'LAIT NIDO NUTRIPAK 12g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 79, 100, 100, 0, 0, 0, 'g', 'LAIT NIDO NUTRIPAK 12g', 'RAS', '693d76978c3ca.png'),
(108, 'Eleveur', '6033000681041', '6033000681041', 'LAIT NIDO CHOCO 30g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 0, 0, 'g', 'LAIT NIDO CHOCO 30g', 'RAS', '693d777d59227.png'),
(109, 'Eleveur', '6009188007829', '6009188007829', 'LAIT NIDO 1+ 400g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 3125, 3500, 3500, 0, 0, 0, 'g', 'LAIT NIDO 1+ 400g', 'RAS', '693d781fc50d6.png'),
(110, 'Eleveur', '6009188008062', '6009188008062', 'LAIT NIDO 400g', 'LAIT EN POUDRE', 'NIDO', 'ECONOMIC FOOD & SERVICE SA', 3083, 3500, 3500, 0, 0, 0, 'g', 'LAIT NIDO 400g', 'RAS', '693d78e6dfd4c.png'),
(111, 'Eleveur', '6171100014403', '6171100014403', 'MATINAL 200g', 'CHOCOLATS EN POUDRE', 'MATINAL', 'ECONOMIC FOOD & SERVICE SA', 1229, 1500, 1500, 0, 0, 0, 'g', 'MATINAL 200g', 'RAS', '693d7985b66a6.png'),
(112, 'Eleveur', '6171100014427', '6171100014427', 'MATINAL 800g', 'CHOCOLATS EN POUDRE', 'MATINAL', 'ECONOMIC FOOD & SERVICE SA', 3583, 4000, 4000, 0, 0, 0, 'g', 'MATINAL 800g', 'RAS', '693d7a000be55.png'),
(113, 'Eleveur', '6904542608486', '6904542608486', 'DETERGENT OZIL 25g', 'DETERGENTS', 'OZIL', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 0, 0, 'g', 'DETERGENT OZIL 25g', 'RAS', '693d7afc9fdb5.png'),
(114, 'Eleveur', '1010010003310', '1010010003310', 'DETERGENT BRIL 25g', 'DETERGENTS', 'BRIL', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 0, 0, 'g', 'DETERGENT BRIL 25g', 'RAS', '693d7b946ba3d.png'),
(115, 'Eleveur', '6212801002105', '6212801002105', 'DETERGENT OLA 25g', 'DETERGENTS', 'OLA', 'ECONOMIC FOOD & SERVICE SA', 37, 50, 50, 0, 0, 0, 'g', 'DETERGENT OLA 25g', 'RAS', '693d7c59f3109.png'),
(116, 'Eleveur', '6210290024219', '6210290024219', 'DETERGENT MADAR 400g', 'DETERGENTS', 'MADAR', 'ECONOMIC FOOD & SERVICE SA', 575, 700, 700, 0, 0, 0, 'g', 'DETERGENT MADAR 400g', 'RAS', '693d7d0c7d8fc.png'),
(117, 'Eleveur', '6181006000394', '6181006000394', 'JAVEL LA CROIX 800ml', 'JAVELS', 'LACROIX', 'ECONOMIC FOOD & SERVICE SA', 967, 1100, 1100, 0, 0, 5, 'ml', 'JAVEL LA CROIX 800ml', 'RAS', '693d7d985f571.png'),
(118, 'Eleveur', '8718951272927', '8718951272927', 'PAX CITRON 800ml', 'DETERGENTS', 'PAX', 'ECONOMIC FOOD & SERVICE SA', 1633, 1800, 1800, 0, 0, 5, 'ml', 'PAX CITRON 800ml', 'RAS', '693d7e3d4ecd5.png'),
(119, 'Eleveur', '8718951664289', '8718951664289', 'SOUPLINE GD AIR 1.5L', 'SOUPLINE', 'SOUPLINE', 'ECONOMIC FOOD & SERVICE SA', 3497, 3600, 3600, 0, 0, 0, 'l', 'SOUPLINE GD AIR 1.5L', 'RAS', '693d7ee15fbd3.png'),
(120, 'Eleveur', '3041091879219', '3041091879219', 'PHOSPHATINE LACTEE FRUITS 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 0, 0, 'g', 'PHOSPHATINE LACTEE FRUITS 190g', 'RAS', '693d7fb9b081d.png'),
(121, 'Eleveur', '3041091879233', '3041091879233', 'PHOSPHATINE LACTEE ?ULTICEREALES 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 0, 0, 'g', 'PHOSPHATINE LACTEE ?ULTICEREALES 190g', 'RAS', '693d80456059f.png'),
(122, 'Eleveur', '3041091879257', '', 'PHOSPHATINE LACTEE SAV BISCUITE 190g', 'PHOSPHATINE', 'PHOSPHATINE', 'ECONOMIC FOOD & SERVICE SA', 1042, 1500, 1500, 0, 0, 0, 'g', 'PHOSPHATINE LACTEE SAV BISCUITE 190g', 'RAS', '693d80c4d1504.png'),
(123, 'Eleveur', '8718182020137', '8718182020137', 'MAYONNAISE ARMANTI 500 ML', 'MAYONNAISES', 'ARMANTI', 'ECONOMIC FOOD & SERVICE SA', 1333, 1500, 1500, 0, 0, 0, 'ml', 'MAYONNAISE ARMANTI 500 ML', 'RAS', '693d82b4aece5.png'),
(124, 'Eleveur', '8718182022827', '8718182022827', 'MAYONNAISE BROLI 250 ML', 'MAYONNAISES', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 1000, 1200, 1200, 0, 0, 0, 'ml', 'MAYONNAISE BROLI 250 ML', 'RAS', '693d83e3885c6.png'),
(125, 'Eleveur', '8718182020083', '8718182020083', 'MAYONNAISE BROLI 500 ML', 'MAYONNAISES', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 1250, 1500, 1500, 0, 0, 0, 'ml', 'MAYONNAISE BROLI 500 ML', 'RAS', '693d846cd48b0.png'),
(126, 'Eleveur', '6281842197132', '6281842197132', 'MACARONI MAMA 250G', 'MACARONI', 'MAMA', 'ECONOMIC FOOD & SERVICE SA', 175, 250, 250, 0, 0, 0, 'g', 'MACARONI MAMA 250G', 'RAS', '693d8533eb107.png'),
(127, 'Eleveur', '8901719101014', '', 'BISCUIT PARLE G 32g', 'BISCUITS', 'PARLE G', 'ECONOMIC FOOD & SERVICE SA', 10, 50, 50, 0, 0, 0, 'g', 'BISCUIT PARLE G 32g', 'RAS', '693d8681af147.png'),
(128, 'Eleveur', '4600597494303', '4600597494303', 'BISCUIT PETIT FOOTBALL 16G', 'BISCUITS', 'RIO', 'ECONOMIC FOOD & SERVICE SA', 18, 25, 25, 0, 0, 0, 'g', 'BISCUIT PETIT FOOTBALL 16G', 'RAS', '693d8886ada8c.png'),
(129, 'Eleveur', 'EPON_METAL001', 'EPON_METAL001', 'EPONGE METALLIQUE IDEAL', 'EPONGES', 'IDEL', 'ECONOMIC FOOD & SERVICE SA', 50, 100, 100, 0, 0, 0, 'g', 'EPONGE METALLIQUE IDEAL', 'RAS', '693d8bdab2b1e.png'),
(130, 'Eleveur', '8718182020892', '8718182020892', 'RIZ BROLI PARFUME 5kg', 'RIZ', 'BROLI', 'ECONOMIC FOOD & SERVICE SA', 7200, 8000, 8000, 0, 0, 0, 'g', 'RIZ BROLI PARFUME 5kg', 'RAS', '693d8cfc32daa.png'),
(131, 'Eleveur', '3590124001016', '3590124001016', 'PAPIER HYGIENIQUE SITA', 'PAPIERS HYGIENIQUE', 'SITA', 'ECONOMIC FOOD & SERVICE SA', 240, 300, 300, 0, 0, 0, 'g', 'PAPIER HYGIENIQUE SITA', 'RAS', '693d8db699cd3.png'),
(132, 'Eleveur', '8901719912627', '8901719912627', 'BISCUIT FABIO 51g', 'BISCUITS', 'FABIO', 'ECONOMIC FOOD & SERVICE SA', 82, 100, 100, 0, 0, 0, 'g', 'BISCUIT FABIO 51g', 'RAS', '693d8ee207289.png'),
(133, 'Eleveur', '6925420056332', '6925420056332', 'INSECTICIDE SPIRAL TIGER', 'INSECTICIDES', 'TIGER', 'ECONOMIC FOOD & SERVICE SA', 233, 300, 300, 0, 0, 0, 'pack', 'INSECTICIDE SPIRAL TIGER', 'ras', '693d900b5eb31.png'),
(134, 'Eleveur', '6171100014281', '6171100014281', 'MAMBO NOIR 25g', 'CHOCOLATS', 'MAMBO', 'ECONOMIC FOOD & SERVICE SA', 168, 200, 200, 0, 0, 0, 'g', 'MAMBO NOIR 25g', 'ras', '693d918095b1c.png'),
(135, 'Eleveur', '8718182020144', '8718182020144', 'MAYONAISE ARMANTINE 1kg', 'MAYONNAISES', 'MAYONAISE', 'ECONOMIC FOOD & SERVICE SA', 2667, 3000, 3000, 0, 0, 0, 'Kg', 'MAYONAISE ARMANTINE 1kg', 'ras', '693d9252e9f29.png'),
(136, 'Eleveur', '6920354817816', '6920354817816', 'COLGATE HERBAL 70g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 702, 1000, 1600, 0, 0, 3, 'g', 'COLGATE HERBAL 70g', 'ras', '693d93f2323bd.png'),
(137, 'Eleveur', '6920354817809', '6920354817809', 'COLGATE HERBAL 35g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 396, 500, 500, 0, 0, 0, 'g', 'COLGATE HERBAL 35g', 'ras', '693d94880de72.png'),
(138, 'Eleveur', '6920354817830', '6920354817830', 'COLGATE HERBAL 175g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1417, 1700, 1500, 0, 0, 3, 'g', 'COLGATE HERBAL 175g', 'ras', '693d955dd26c8.png'),
(139, 'Eleveur', '6920354817823', '6920354817823', 'COLGATE HERBAL 140g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1317, 1500, 1500, 0, 0, 0, 'g', 'COLGATE HERBAL 140g', 'ras', '693d964c0510f.png'),
(140, 'Eleveur', '8901314115010', '8901314115010', 'COLGATE MAX FRESH 130g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 932, 1200, 1200, 0, 0, 0, 'g', 'COLGATE MAX FRESH 130g', 'ras', '693d9743a07d7.png'),
(141, 'Eleveur', '8718951350885', '8718951350885', 'COLGATE CHARBON 120g', 'DENTIFRICES', 'COLGATE', 'ECONOMIC FOOD & SERVICE SA', 1375, 1500, 1500, 0, 0, 0, 'g', 'COLGATE CHARBON 120g', 'ras', '693d97a9db021.png');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shop_product`
--

DROP TABLE IF EXISTS `tbl_shop_product`;
CREATE TABLE IF NOT EXISTS `tbl_shop_product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `stock` int NOT NULL,
  `stock_min` int NOT NULL,
  `prix_vente` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

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
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(80) NOT NULL,
  `password` varchar(50) NOT NULL,
  `magasin` varchar(20) NOT NULL,
  `role` varchar(15) NOT NULL,
  `is_active` tinyint NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `magasin`, `role`, `is_active`) VALUES
(6, 'tnh', 'Tognia', '7c222fb2927d828af22f592134e8932480637c0d', 'Eleveur', 'Admin', 1),
(17, 'storekeeper', 'PEACEFULL', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'storekeeper', 1),
(18, 'operator2', 'OKO', '7c222fb2927d828af22f592134e8932480637c0d', 'Eleveur', 'Operator', 1),
(19, 'respo', 'OBOBOGO', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'Responsable', 1),
(20, 'admin', 'BOULANGERIE ELEVEUR', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'Admin', 1),
(21, 'caisse1', 'CAISSE', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Eleveur', 'Operator', 1);

-- --------------------------------------------------------

--
-- Structure de la table `temp_trans`
--

DROP TABLE IF EXISTS `temp_trans`;
CREATE TABLE IF NOT EXISTS `temp_trans` (
  `temp_trans_id` int NOT NULL AUTO_INCREMENT,
  `prod_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int NOT NULL,
  PRIMARY KEY (`temp_trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `trans_id` int NOT NULL AUTO_INCREMENT,
  `or_no` int NOT NULL,
  `prod_serial` varchar(50) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `trans_qty` int NOT NULL,
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
  `user_id` int NOT NULL AUTO_INCREMENT,
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
