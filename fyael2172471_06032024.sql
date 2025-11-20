-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 06 mars 2024 à 05:09
-- Version du serveur : 10.11.4-MariaDB-1~deb12u1
-- Version de PHP : 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fyael2172471`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `agence` (
  `id` int(11) NOT NULL,
  `code_agence` varchar(50) NOT NULL,
  `libelle_agence` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ville` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id`, `code_agence`, `libelle_agence`, `email`, `tel`, `ville`) VALUES
(1, 'bev_oyomabang', 'Bevilec Oyomabang', 'trajectoirei@live.fr', '672569213', 'Yaounde'),
(2, 'bev_Biyemassi', 'Bevilec Carrefour Biyemassi', 'trajectoirei@live.fr', '699456700', 'Yaounde'),
(3, 'leti_nkolbisson', 'plus elec cameroun Sarl', 'yakamamelie23@gmail.com', '655762258', 'Yaounde');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `membership_number` varchar(100) NOT NULL,
  `prod_name` varchar(550) NOT NULL,
  `expected_date` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `address`, `contact`, `email`, `membership_number`, `prod_name`, `expected_date`, `note`) VALUES
(1, 'BRIZER PLC 909090', 'Kumba Douala Deido 87779', '00237 698 95 56 43', '9htylememe@gmail.com', '000052', 'Chocolat Cerelac au Lait', '10 05 2021', '13');

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
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
  `tax` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `order_details_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `total_qty` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `prod_id`, `prod_qty`, `total_qty`, `total`, `user_id`, `order_id`) VALUES
(53, 13, 1, '338', 434.00, 6, '1'),
(54, 13, 3, '335', 1302.00, 6, '1'),
(55, 13, 1, '334', 434.00, 6, '1'),
(56, 11, 1, '149', 125.00, 6, '1'),
(57, 12, 1, '397', 155.00, 6, '1'),
(58, 11, 1, '149', 125.00, 6, '1'),
(59, 13, 1, '329', 434.00, 6, '1'),
(60, 13, 1, '328', 434.00, 6, '1'),
(61, 13, 1, '327', 434.00, 6, '1'),
(62, 12, 2, '395', 310.00, 6, '1'),
(63, 13, 2, '325', 868.00, 6, '1'),
(64, 13, 1, '324', 434.00, 6, '1'),
(65, 11, 1, '148', 125.00, 6, '1'),
(66, 13, 1, '323', 434.00, 6, '1'),
(67, 11, 1, '147', 125.00, 6, '1'),
(68, 12, 1, '394', 155.00, 6, '1'),
(69, 12, 1, '393', 155.00, 6, '1'),
(70, 13, 1, '322', 434.00, 7, '1'),
(71, 11, 1, '146', 125.00, 7, '1'),
(72, 13, 1, '321', 434.00, 7, '1'),
(73, 13, 1, '320', 434.00, 7, '1'),
(74, 13, 1, '319', 434.00, 7, '1'),
(75, 13, 1, '318', 434.00, 6, '1'),
(76, 13, 3, '315', 1302.00, 6, '1'),
(77, 13, 1, '314', 434.00, 6, '1'),
(78, 13, 1, '313', 434.00, 6, '1'),
(79, 14, 1, '233', 760.00, 6, '1'),
(80, 13, 1, '311', 434.00, 6, '1'),
(81, 13, 2, '309', 868.00, 6, '1'),
(83, 14, 1, '233', 760.00, 6, '1'),
(84, 13, 1, '308', 434.00, 6, '1'),
(85, 15, 1, '455', 455.00, 6, '1'),
(86, 11, 1, '145', 125.00, 6, '1'),
(87, 13, 1, '306', 434.00, 6, '1'),
(88, 13, 1, '304', 434.00, 6, '1'),
(89, 13, 1, '303', 434.00, 6, '1'),
(90, 13, 1, '302', 434.00, 6, '1'),
(91, 14, 1, '232', 760.00, 6, '1'),
(92, 13, 1, '300', 434.00, 6, '1'),
(93, 14, 10, '222', 7600.00, 8, '1'),
(94, 13, 200, '0', 86800.00, 8, '1'),
(95, 13, 300, '0', 130200.00, 8, '1'),
(96, 11, 1, '144', 125.00, 6, '1'),
(97, 11, 144, '0', 18000.00, 6, '1'),
(98, 15, 1, '', 455.00, 5, ''),
(99, 15, 1, '', 455.00, 6, ''),
(100, 16, 1, '', 1500.00, 6, ''),
(101, 12, 1, '392', 155.00, 8, '1'),
(102, 12, 1, '391', 155.00, 8, '1'),
(103, 15, 1, '', 455.00, 8, ''),
(104, 14, 1, '221', 760.00, 9, '1'),
(105, 17, 1, '25', 2200.00, 9, '1');

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `due` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `or_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `prod_id` int(11) NOT NULL,
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
  `prod_pic3` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`prod_id`, `prod_name`, `prod_desc`, `prod_qty`, `prod_cost`, `prod_price`, `category`, `supplier`, `prod_serial`, `prod_pic1`, `prod_pic2`, `prod_pic3`) VALUES
(11, 'Arduino Uno Rec3-1', 'Small Arduino Uno Blue', 0, 123.00, 125.00, 'Arduino', 'Alcatroz, Inc.', '1122330099', 'arduino mega 2560-1.jpg', 'Arduino Uno Rev3-1.jpg', '1.png'),
(12, 'Aruino Mega', 'ATMega Arduino', 391, 133.00, 155.00, 'Arduino', 'Alcatroz, Inc.', '341156780', 'Arduinomega2560-3.jpg', 'arduino mega 2560-1.jpg', '2.png'),
(14, 'Raspberry Pi 3', 'Model B+', 221, 700.00, 760.00, 'Raspberry Pi', 'PICC', '45422791', 'raspi2.jpg', 'raspi.jpg', 'raspi3.png'),
(15, 'Flame Sensor', 'Flame Sensor 3 Pins', 455, 450.00, 455.00, 'Sensor', 'QUEZELCO', '456523702', 'flame2.jpg', 'flamesensor1.jpg', 'flamesensor.png'),
(16, 'Sensor', 'Able to sense product', 700, 1500.00, 1500.00, 'Sensor', 'QUEZELCO', '890', 'ultrasonic sensor.png', 'motion sensor2.jpg', 'flamesensor1.jpg'),
(17, 'X9 THOR - Gaming Mouse', '7D Macro Programmable Gaming Mouse, Sensor: A714 Instan, LED: RGB 16.8 million colors, Interface : USB, DPI: 4800dpi, Cable Length: 1.8m nylon braided, Supported OS: Windows Vista, Win7/8/10, Mac OS X 10.5 or later, Linux, Chrome OS', 25, 1000.00, 2200.00, 'Others', 'Alcatroz, Inc.', '1353', 'x9thor.jpg', 'x92.jpg', 'x93.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

CREATE TABLE `sales` (
  `sales_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `mode_of_payment` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sales_details`
--

CREATE TABLE `sales_details` (
  `sales_details_id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `supliers`
--

CREATE TABLE `supliers` (
  `suplier_id` int(11) NOT NULL,
  `suplier_name` varchar(100) NOT NULL,
  `suplier_address` varchar(100) NOT NULL,
  `suplier_contact` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `note` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `supliers`
--

INSERT INTO `supliers` (`suplier_id`, `suplier_name`, `suplier_address`, `suplier_contact`, `contact_person`, `note`) VALUES
(2, 'MOISE NGNOKAM', 'Ngousso Fabrique', '688885555', 'HENRI TOGNIA', '10'),
(3, 'SDME', '000', '00', '00', '00'),
(4, 'REXEL', 'Paris - France', '000000', '0000000', '7');

-- --------------------------------------------------------

--
-- Structure de la table `supplier`
--

CREATE TABLE `supplier` (
  `supp_id` int(11) NOT NULL,
  `supp_name` varchar(100) NOT NULL,
  `supp_address` varchar(200) NOT NULL,
  `supp_contact` varchar(50) NOT NULL,
  `supp_email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `tbl_category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(200) NOT NULL,
  `cat_parent` varchar(150) NOT NULL,
  `cat_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(22, 'Armoire Electrique', 'Aucune', 3),
(23, 'GAINES ICTA', 'Aucune', 3),
(24, 'FILS ET CABLES', 'Aucune', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `cashier_name` varchar(100) NOT NULL,
  `id_client` varchar(150) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  `paid` float NOT NULL,
  `due` float NOT NULL,
  `remise` float NOT NULL,
  `tva` float NOT NULL,
  `payment_mode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `cashier_name`, `id_client`, `order_date`, `time_order`, `total`, `paid`, `due`, `remise`, `tva`, `payment_mode`) VALUES
(131, 'operator1', 'common', '2024-01-14', '16:52', 1475, 1500, -25, 0, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client`
--

CREATE TABLE `tbl_invoice_client` (
  `invoice_id` int(11) NOT NULL,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL,
  `Status` varchar(20) NOT NULL,
  `moyen_paiement` varchar(50) NOT NULL,
  `date_paiement` date NOT NULL,
  `time_paiement` time NOT NULL,
  `infos_paiement` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client_deleted`
--

CREATE TABLE `tbl_invoice_client_deleted` (
  `invoice_id` int(11) NOT NULL,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `total` float NOT NULL,
  `delete_date` date NOT NULL,
  `delete_time` varchar(50) NOT NULL,
  `delete_moyen` varchar(50) NOT NULL,
  `delete_infos` varchar(200) NOT NULL,
  `observations` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_client_delivered`
--

CREATE TABLE `tbl_invoice_client_delivered` (
  `invoice_id` int(11) NOT NULL,
  `id_client` varchar(100) NOT NULL,
  `name_client` varchar(150) NOT NULL,
  `total` float NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` varchar(50) NOT NULL,
  `delivery_moyen` varchar(50) NOT NULL,
  `delivery_infos` varchar(200) NOT NULL,
  `observations` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail`
--

CREATE TABLE `tbl_invoice_detail` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `remise` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_invoice_detail`
--

INSERT INTO `tbl_invoice_detail` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `remise`) VALUES
(121, 131, 13, 'H07 VU 2.5 ROUGE C100', 'FILS ', 5, 'm', 295, 1475, '2024-01-14', 0);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_invoice_detail_client`
--

CREATE TABLE `tbl_invoice_detail_client` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` char(25) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_satuan` varchar(20) NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  `order_date` date NOT NULL,
  `Status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
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
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_storeroom`, `place_in_store`, `img`) VALUES
(10, 'H07 VU 2.5 ROUGE C100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 18000, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '1', '65a40054475c6.jpg'),
(11, 'H07 VU 2.5 BLEU C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 18000, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750VFILS RIGIDE 2.5 BLEU ', '1', '2', '65bea2d4062ca.jpg'),
(12, 'H07 VU 2.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 18000, 2000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '2', '65beb0643352b.jpg'),
(13, 'H07 VU 1.5 ROUGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 8000, 1000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '2', '65beb33509b28.jpg'),
(14, 'H07 VU 1.5 BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 8000, 1000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '2', '65beb3b6e4e72.jpg'),
(15, 'H07 VU 1.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 8000, 1000, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '2', '65beb445842a9.jpg'),
(16, 'H07 VU 1.5 VIOLET C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 3000, 500, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '2', '65beb6d13aa7e.jpg'),
(17, 'H07 VU 1.5 ORANGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 3000, 500, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '2', '65beb76d1eb9d.jpg'),
(18, 'U1000 R2V 3G2.5 C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 880, 900, 880, 0, 1500, 500, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '3', '65bebc1d80e7c.jpg'),
(19, 'U1000 R2V 3G1.5 C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 1500, 500, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '4', '65bebdca4460b.jpg'),
(20, 'CAT6 4P F/UTP C100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 500, 100, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '5', '65bec1426a054.jpg'),
(21, 'CABLE COAXIAL 17 VATC C100', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 500, 100, 'm', 'CABLE TV', '6', '6', '65bec5315fdb6.jpg'),
(22, 'CABLE CUIVRE NU 25² T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 500, 100, 'm', 'CABLE CUIVRE NU', '7', '7', '65bec6d13a6f1.jpg'),
(23, 'CABLE VR 35² ROUGE T500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 500, 100, 'm', 'CABLE ALIMENTION ', '8', '8', '65beca39c061f.jpg'),
(24, 'CABLE VR 35² BLEU T500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 500, 100, 'm', 'CABLE ALIMENTATION', '8', '8', '65becaed3a331.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_provision`
--

CREATE TABLE `tbl_provision` (
  `id` int(11) NOT NULL,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `qte` int(11) NOT NULL,
  `user` varchar(25) NOT NULL,
  `observations` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_satuan`
--

CREATE TABLE `tbl_satuan` (
  `kd_satuan` int(2) NOT NULL,
  `nm_satuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

CREATE TABLE `tbl_shop_item` (
  `product_id` int(11) NOT NULL,
  `shop_code` varchar(30) NOT NULL,
  `product_code` char(25) NOT NULL,
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
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_shop_item`
--

INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(10, 'bev_oyomabang', '0000', '', 'GAINE ICTA', 'Accessoires et autre appareillage terminal', 'COURANT', 'SDME', 50, 70, 68, 0, 0, 0, 'm', 'GAINE 25', '3', '6565083aa9979.jpeg'),
(11, 'bev_Biyemassi', '0000', '', 'GAINE ICTA', 'Accessoires et autre appareillage terminal', 'COURANT', 'SDME', 50, 70, 68, 0, 0, 0, 'm', 'GAINE 25', '3', '6565083aa9979.jpeg'),
(12, 'leti_nkolbisson', '0000', '', 'GAINE ICTA', 'Accessoires et autre appareillage terminal', 'COURANT', 'SDME', 50, 70, 68, 0, 0, 0, 'm', 'GAINE 25', '3', '6565083aa9979.jpeg'),
(13, 'bev_oyomabang', 'H07 VU 2.5 ROUGE C100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 4995, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(14, 'bev_Biyemassi', 'H07 VU 2.5 ROUGE C100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(15, 'leti_nkolbisson', 'H07 VU 2.5 ROUGE C100', '00000', 'FILS ', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 1, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '1', '65a40054475c6.jpg'),
(16, 'bev_oyomabang', 'H07 VU 2.5 BLEU C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 0, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(17, 'bev_Biyemassi', 'H07 VU 2.5 BLEU C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 0, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(18, 'leti_nkolbisson', 'H07 VU 2.5 BLEU C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 267, 280, 275, 0, 0, 0, 'm', 'FILS RIGIDE 2.5 BLEU ', '2', '65bea2d4062ca.jpg'),
(19, 'bev_oyomabang', 'H07 VU 2.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(20, 'bev_Biyemassi', 'H07 VU 2.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(21, 'leti_nkolbisson', 'H07 VU 2.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 196, 295, 285, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb0643352b.jpg'),
(22, 'bev_oyomabang', 'H07 VU 1.5 ROUGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(23, 'bev_Biyemassi', 'H07 VU 1.5 ROUGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(24, 'leti_nkolbisson', 'H07 VU 1.5 ROUGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb33509b28.jpg'),
(25, 'bev_oyomabang', 'H07 VU 1.5 BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(26, 'bev_Biyemassi', 'H07 VU 1.5 BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(27, 'leti_nkolbisson', 'H07 VU 1.5 BLEU', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb3b6e4e72.jpg'),
(28, 'bev_oyomabang', 'H07 VU 1.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(29, 'bev_Biyemassi', 'H07 VU 1.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(30, 'leti_nkolbisson', 'H07 VU 1.5 VERT/JAUNE', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb445842a9.jpg'),
(31, 'bev_oyomabang', 'H07 VU 1.5 VIOLET C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(32, 'bev_Biyemassi', 'H07 VU 1.5 VIOLET C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(33, 'leti_nkolbisson', 'H07 VU 1.5 VIOLET C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb6d13aa7e.jpg'),
(34, 'bev_oyomabang', 'H07 VU 1.5 ORANGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(35, 'bev_Biyemassi', 'H07 VU 1.5 ORANGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(36, 'leti_nkolbisson', 'H07 VU 1.5 ORANGE C100', '0000', 'FILS', 'FILS ET CABLES', 'EUROPE', 'REXEL', 126, 180, 175, 0, 0, 0, 'm', 'CONDUCTEURS POUR EQUIPEMENTS DES INSTALLATIONS DOMESTIQUES FIXES ET PROTEGEES,LOCAUX D\'HABITATIONS,BUREAUX DANS OU SUR DES DISPOSITIFS D\'ECLAIRAGE ET DE COMMAND,POUR DES TENSIONS JUSQU4A 750V', '2', '65beb76d1eb9d.jpg'),
(37, 'bev_oyomabang', 'U1000 R2V 3G2.5 C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(38, 'bev_Biyemassi', 'U1000 R2V 3G2.5 C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(39, 'leti_nkolbisson', 'U1000 R2V 3G2.5 C100', '0000', 'CABLES', 'FILS ET CABLES', 'EUROPE', 'REXEL', 740, 890, 880, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '3', '65bebc1d80e7c.jpg'),
(40, 'bev_oyomabang', 'U1000 R2V 3G1.5 C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(41, 'bev_Biyemassi', 'U1000 R2V 3G1.5 C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(42, 'leti_nkolbisson', 'U1000 R2V 3G1.5 C100', '0000', 'CABLE', 'FILS ET CABLES', 'EUROPE', 'REXEL', 630, 685, 680, 0, 0, 0, 'm', 'CABLES INDUSTRIELS\r\nBASSE TENSION - ÉNERGIE\r\nRIGIDE - CUIVRE\r\nU 1000 R2V\r\nNF C 32-321\r\nAME M!tal : Cuivre nu. Forme : ronde. Souplesse : S < 4 mm2 classe 1 - massif ; S > 6 mm2 classe 2 - c\"bl!. Temp!rature maximale # l\'\"me : 90$C en permanence. 250$C en court-circuit.\r\nISOLATION PRC. Rep!rage :\r\nREVÊTEMENT D’ASSEMBLAGE Gaine thermoplastique ou ruban synth!tique suivant section.\r\nGAINE EXTÉRIEURE PVC. Couleur : noire. Marquage : U 1000 R2V - Nb Cond. (X ou G) S en mm2\r\n- USE - N$ usine. X : c\"ble sans V / J (Ex : 2 X 1,5). G : c\"ble avec V / J (Ex : 4 G 2,5)\r\nUTILISATIONS Installations industrielles, colonnes montantes d\'immeubles. D!conseill! dans des terrains inond!s plus de deux mois par an et tranch!es formant drain. Enterr!, pr!voir une protection m!canique contre les chocs. Ne peut %tre utilis! sous contraintes m!caniques # temp!rature permanente au dessous de -10$C.\r\nPOSE : Rayon de courbure mini : 6 D. Temp!rature mini de pose : -10$C.\r\nCARACTÉRISTIQUES TECHNIQUES\r\nSection\r\nmm2', '4', '65bebdca4460b.jpg'),
(43, 'bev_oyomabang', 'CAT6 4P F/UTP C100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(44, 'bev_Biyemassi', 'CAT6 4P F/UTP C100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(45, 'leti_nkolbisson', 'CAT6 4P F/UTP C100', '0000', 'CABLE RJ45', 'FILS ET CABLES', 'EUROPE', 'REXEL', 525, 600, 598, 0, 0, 0, 'm', 'REF : CX6-xSH\r\nEd. 2\r\nTM 08/11\r\nCable 100 ? F/UTP x paires catégorie 6 – 350 MHz\r\nLow Smoke Zero Halogen\r\nwww.cae-groupe.fr\r\nCe document est confidentiel, et est la propriété de CAE Groupe. CAE Groupe possède un copyright, et le\r\ndocument ne doit pas être copié ou changé sous aucune forme, complètement ou en partie sans permission\r\nécrite de CAE Groupe. Les caractéristiques portées sur cette fiche ne sont pas contractuelles, et sont\r\nsusceptibles d’être modifiées sans préavis.\r\nINFORMATIONS PRODUIT\r\nApplication\r\nCe Câble écranté F/UTP (Foiled twisted pairs) qui s’utilise dans une configuration horizontale ou verticale (Rocade), il\r\nconstitue la base d’un réseau V.D.I (Voix-Donnée-Image) à très haut-débit.\r\nSon Blindage avec un fort coefficient de recouvrement lui permet une utilisation en environnement perturbé et lui assure un\r\nbon fonctionnement jusqu’à 350 Mhz. Sa structure interne lui assure des marges importantes avec l’ensemble des\r\nstandards actuels.\r\nCe câble est utilisé dans l', '5', '65bec1426a054.jpg'),
(46, 'bev_oyomabang', 'CABLE COAXIAL 17 VATC C10', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(47, 'bev_Biyemassi', 'CABLE COAXIAL 17 VATC C10', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(48, 'leti_nkolbisson', 'CABLE COAXIAL 17 VATC C10', '0000', 'CABLE VATC  TV', 'FILS ET CABLES', 'EUROPE', 'REXEL', 90, 115, 112, 0, 0, 0, 'm', 'CABLE TV', '6', '65bec5315fdb6.jpg'),
(49, 'bev_oyomabang', 'CABLE CUIVRE NU 25² T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(50, 'bev_Biyemassi', 'CABLE CUIVRE NU 25² T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(51, 'leti_nkolbisson', 'CABLE CUIVRE NU 25² T500', '0000', 'CUIVRE NU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 300, 380, 360, 0, 0, 0, 'm', 'CABLE CUIVRE NU', '7', '65bec6d13a6f1.jpg'),
(52, 'bev_oyomabang', 'CABLE VR 35² ROUGE T500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(53, 'bev_Biyemassi', 'CABLE VR 35² ROUGE T500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(54, 'leti_nkolbisson', 'CABLE VR 35² ROUGE T500', '0000', 'CABLE ALIMENTATION', 'FILS ET CABLES', 'EUROPE', 'REXEL', 675, 700, 695, 0, 0, 0, 'm', 'CABLE ALIMENTION ', '8', '65beca39c061f.jpg'),
(55, 'bev_oyomabang', 'CABLE VR 35² BLEU T500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg'),
(56, 'bev_Biyemassi', 'CABLE VR 35² BLEU T500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg'),
(57, 'leti_nkolbisson', 'CABLE VR 35² BLEU T500', '0000', 'CABLE ALIMENTATION ALU', 'FILS ET CABLES', 'EUROPE', 'REXEL', 475, 550, 540, 0, 0, 0, 'm', 'CABLE ALIMENTATION', '8', '65becaed3a331.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_shop_product`
--

CREATE TABLE `tbl_shop_product` (
  `id` int(11) NOT NULL,
  `code_agence` varchar(30) NOT NULL,
  `code_produit` varchar(30) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_min` int(11) NOT NULL,
  `prix_vente` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `tbl_shop_product`
--

INSERT INTO `tbl_shop_product` (`id`, `code_agence`, `code_produit`, `stock`, `stock_min`, `prix_vente`) VALUES
(1, 'bev_oyomabang', 'TT0040', 10, 5, 15000);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(80) NOT NULL,
  `password` varchar(50) NOT NULL,
  `magasin` varchar(20) NOT NULL,
  `role` varchar(15) NOT NULL,
  `is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `magasin`, `role`, `is_active`) VALUES
(6, 'tnh', 'Tognia', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Admin', 1),
(7, 'operator1', 'KEBO KAKI', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Operator', 1),
(15, 'byakam', 'BERTRAND YAKAM', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Admin', 1),
(16, 'respo', 'BABA ONO', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Responsable', 1),
(17, 'operator2', 'Padro', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_Biyemassi', 'Operator', 1),
(18, 'respo1', 'TNH TEST', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Responsable', 1),
(19, 'operator3', 'OPERATOR 1', '7c222fb2927d828af22f592134e8932480637c0d', 'bev_oyomabang', 'Operator', 1);

-- --------------------------------------------------------

--
-- Structure de la table `temp_trans`
--

CREATE TABLE `temp_trans` (
  `temp_trans_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `trans_id` int(11) NOT NULL,
  `or_no` int(11) NOT NULL,
  `prod_serial` varchar(50) NOT NULL,
  `prod_name` varchar(100) NOT NULL,
  `trans_qty` int(11) NOT NULL,
  `ppi` decimal(10,0) NOT NULL,
  `cust_fullname` varchar(100) NOT NULL,
  `transdate` datetime NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Index pour la table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Index pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_details_id`);

--
-- Index pour la table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Index pour la table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Index pour la table `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`sales_details_id`);

--
-- Index pour la table `supliers`
--
ALTER TABLE `supliers`
  ADD PRIMARY KEY (`suplier_id`);

--
-- Index pour la table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supp_id`);

--
-- Index pour la table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Index pour la table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Index pour la table `tbl_invoice_client`
--
ALTER TABLE `tbl_invoice_client`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Index pour la table `tbl_invoice_client_deleted`
--
ALTER TABLE `tbl_invoice_client_deleted`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Index pour la table `tbl_invoice_client_delivered`
--
ALTER TABLE `tbl_invoice_client_delivered`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Index pour la table `tbl_invoice_detail`
--
ALTER TABLE `tbl_invoice_detail`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_invoice_detail_client`
--
ALTER TABLE `tbl_invoice_detail_client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_code` (`product_code`,`product_name`);

--
-- Index pour la table `tbl_provision`
--
ALTER TABLE `tbl_provision`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  ADD PRIMARY KEY (`kd_satuan`),
  ADD UNIQUE KEY `nm_satuan` (`nm_satuan`);

--
-- Index pour la table `tbl_shop_item`
--
ALTER TABLE `tbl_shop_item`
  ADD PRIMARY KEY (`product_id`);

--
-- Index pour la table `tbl_shop_product`
--
ALTER TABLE `tbl_shop_product`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `temp_trans`
--
ALTER TABLE `temp_trans`
  ADD PRIMARY KEY (`temp_trans_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`trans_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `customer`
--
ALTER TABLE `customer`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `sales_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `supliers`
--
ALTER TABLE `supliers`
  MODIFY `suplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_client`
--
ALTER TABLE `tbl_invoice_client`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_client_deleted`
--
ALTER TABLE `tbl_invoice_client_deleted`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_client_delivered`
--
ALTER TABLE `tbl_invoice_client_delivered`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_detail`
--
ALTER TABLE `tbl_invoice_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_detail_client`
--
ALTER TABLE `tbl_invoice_detail_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `tbl_provision`
--
ALTER TABLE `tbl_provision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  MODIFY `kd_satuan` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `tbl_shop_item`
--
ALTER TABLE `tbl_shop_item`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `tbl_shop_product`
--
ALTER TABLE `tbl_shop_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `temp_trans`
--
ALTER TABLE `temp_trans`
  MODIFY `temp_trans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
