-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 12 juil. 2024 à 21:09
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.20

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
(3, 'SDME', '000', '00', '00', '00'),
(4, 'REXEL', 'Paris - France', '000000', '0000000', '7'),
(5, 'Securlite', 'France', '000000', 'NO', '10'),
(6, 'PRISMA', 'France', '000000', 'NO', '10'),
(7, 'SARLAM', 'France', '000000', 'NO', '10'),
(8, 'EPSILON', 'France', '000000', 'NO', '10'),
(9, 'EBENOID', 'France', '000000', 'NO', '10'),
(10, 'Roger Pradier', 'France', '000000', 'NO', '10'),
(11, 'MAZDA', 'France', '000000', 'NO', '10'),
(12, 'LUMINA', 'France', '000000', 'NO', '10'),
(13, 'ARIC', 'RAS', 'RAS', '0000000', '10'),
(14, 'Philips', 'RAS', '000000', 'ffff', '10'),
(15, 'Hager', 'RAS', '000000', 'ffff', '10'),
(16, 'LUXOMAT', 'RAS', '000000', 'ffff', '10'),
(17, 'TIMEGUARD', 'RAS', '000000', 'ffff', '10'),
(18, 'Résistex', 'RAS', '000000', 'ffff', '10'),
(19, 'Schneider', 'RAS', '000000', 'ffff', '10'),
(20, 'URA', 'RAS', '000000', 'ffff', '10'),
(21, 'Workzone', 'RAS', '000000', 'ffff', '10'),
(22, 'T2S', 'RAS', '000000', 'ffff', '10'),
(23, 'R3 Force Universal', 'RAS', '000000', 'ffff', '10'),
(24, 'EUROHM', 'RAS', '000000', 'ffff', '10'),
(25, 'Legrand', 'RAS', '000000', 'ffff', '10'),
(26, 'Arnould', 'RAS', '000000', 'ffff', '10');

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
(24, 'FILS ET CABLES', 'Aucune', 3),
(25, 'CONDUITS,CANALISATIONS', 'Aucune', 3),
(27, 'Luminaires', 'Aucune', 3),
(29, 'Détecteur de Mouvements', 'Aucune', 3),
(30, 'Détecteur de Présence', 'Aucune', 3),
(31, 'Coffret Electrique', 'Aucune', 3),
(32, '', 'Aucune', 3),
(33, 'Prises', 'Aucune', 3),
(34, 'Disjoncteur', 'Aucune', 3),
(35, 'Bloc Secours', 'Aucune', 3),
(36, 'Grille de protection', 'Aucune', 3),
(37, 'Jeu de Tournevis', 'Aucune', 3),
(38, 'Gilet', 'Aucune', 3),
(39, 'Mèche', 'Aucune', 3),
(40, 'Poussoir Porte-Etiquette', 'Aucune', 3),
(41, 'Interrupteur', 'Aucune', 3),
(42, 'Plaque', 'Aucune', 3),
(43, 'Porte Coffret', 'Aucune', 3),
(44, 'Chargeur', 'Aucune', 3),
(45, 'Support', 'Aucune', 3),
(46, 'Sortie de Cables', 'Aucune', 3),
(47, 'Sortie de Cable', 'Aucune', 3),
(48, 'Bouton Poussoir', 'Aucune', 3),
(49, 'Obturateur', 'Aucune', 3),
(50, 'Inverseur', 'Aucune', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin`
--

CREATE TABLE `tbl_commandes_magasin` (
  `invoice_id` int(11) NOT NULL,
  `cashier_name` varchar(100) NOT NULL,
  `shop` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `time_order` varchar(50) NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_commandes_magasin`
--

INSERT INTO `tbl_commandes_magasin` (`invoice_id`, `cashier_name`, `shop`, `order_date`, `time_order`, `total`) VALUES
(1, 'respo', 'bev_oyomabang', '2024-03-24', '17:41', 280);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_commandes_magasin_details`
--

CREATE TABLE `tbl_commandes_magasin_details` (
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
  `shop` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_commandes_magasin_details`
--

INSERT INTO `tbl_commandes_magasin_details` (`id`, `invoice_id`, `product_id`, `product_code`, `product_name`, `qty`, `product_satuan`, `price`, `total`, `order_date`, `shop`) VALUES
(1, 1, 16, 'H07 VU 2.5 BLEU C100', 'FILS', 1, 'm', 280, 280, '2024-03-24', 'bev_oyomabang');

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
(27, '000400504009', '000400504009', 'Spot à encastrer', 'Luminaires', 'Securlite', 'Securlite', 10, 14, 12, 1, 2, 1, 'U', 'Spot - Luminaire', 'RAS', 'RAS', '666af45d37415.png'),
(28, '8018367017205', '8018367017205', 'Hublot Superdelta Ton Bianco', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 0, 3, 1, 'U', 'Luminaires - IP 54', 'RAS', 'RAS', '666afcd32dff5.png'),
(29, '8018367057423', '8018367057423', 'Hublot Chip Tondo 25 Nero', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 2, 4, 1, 'U', 'IP 44', 'RAS', 'RAS', '666afef19c425.png'),
(30, '3292297445504', '3292297445504', 'Hublot Chartres Ovale - Détecteur', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 1, 1, 'U', 'IP 55 - Détection Mouvements', 'RAS', 'RAS', '666b0257f338d.png'),
(31, '3072820115315', '3072820115315', 'Hublot 11531 SCALA 400 2 lampes', 'Luminaires', 'EPSILON', 'EPSILON', 100, 120, 115, 0, 1, 1, 'U', '2 lampes - 2x42w E27 ES', 'RAS', 'RAS', '666b058079030.png'),
(32, '3292290502983', '3292290502983', 'Hublot Chartres Infini - Extra plat LED', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 15, 1, 'U', 'IP55', 'RAS', 'RAS', '666b0825ca187.png'),
(33, '3292294202704', '3292294202704', 'Hublot Sarlam Plasti Verre Tout attaché', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 120, 0, 7, 1, 'U', 'IP 44 Hublot Sarlam Plasti Verre Tout attaché', 'RAS', 'RAS', '666b0cd407525.png'),
(34, '3292297243766', '3292297243766', 'Hublot Sarlam Chartres T2 Anti Vandales', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 5, 1, 'U', 'IP 54 Hublot Sarlam Chartres T2 Anti Vandales', 'RAS', 'RAS', '666b0ff686bda.png'),
(35, '011329229514273030000005', '011329229514273030000005', 'Hublot SARLAM Chartres Rond', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 15, 1, 'U', 'IP 54 - Hublot SARLAM Chartres Rond', 'RAS', 'RAS', '666b123ef0302.png'),
(36, '3292291898177', '3292291898177', 'Applique SARLAM Prisma Line Plus', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 1, 1, 'Kg', 'IP 24 - Applique Prisma Line Plus', 'RAS', 'RAS', '666b1457033dc.png'),
(37, '3292291908173', '3292291908173', 'Applique SARLAM Prisma Line', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 11, 1, 'U', 'Applique SARLAM Prisma Line IP 24', 'RAS', 'RAS', '666b1725f1f17.png'),
(38, '3292291185116', '3292291185116', 'Applique SARLAM Fluolux', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 1, 1, 'U', 'Applique SARLAM Fluolux 18WEL Simple IP 44', 'RAS', 'RAS', '666b19a590349.png'),
(39, '3477870553016', '3477870553016', 'Applique Ebenoid Diffuseur Circuit LED', 'Luminaires', 'EBENOID', 'EBENOID', 100, 150, 125, 0, 1, 1, 'U', 'Applique Ebenoid Diffuseur Circuit LED 9W / 3000K Appareil Classe II IP 24', 'RAS', 'RAS', '666b1c043550a.png'),
(40, 'PVT11400', 'PVT11400', 'Applique Extérieur  Roger Pradier', 'Luminaires', 'Roger Pradier', 'Roger Pradier', 100, 150, 125, 0, 6, 1, 'U', 'Applique Extérieur  Roger Pradier', 'RAS', 'RAS', '666b1e94ec4df.png'),
(41, '3139180250138', '3139180250138', 'Applique Extérieiur Mazda', 'Luminaires', 'MAZDA', 'MAZDA', 100, 150, 125, 0, 4, 1, 'U', 'Applique Extérieiur Mazda IP54', 'RAS', 'RAS', '666b22b1b739d.png'),
(42, '8018367017083', '8018367017083', 'Applique Extérieure Ovale Prisma Super Delta', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 2, 1, 'U', 'Applique Extérieure Ovale Prisma Super Delta Bianco - IP54', 'RAS', 'RAS', '666b262ec2163.png'),
(43, '8018367059045', '8018367059045', 'Hublot Prisma UNICA 28 SATIN', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 3, 1, 'U', 'Hublot Prisma UNICA 28 SATIN - IP 44', 'RAS', 'RAS', '666b29088d632.png'),
(44, '8018367057416', '8018367057416', 'Hublot Prisma Chip Ton 25G', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 3, 1, 'U', 'Hublot Prisma Chip Ton 25G - IP44', 'RAS', 'RAS', '666b2c50726f4.png'),
(45, '6111213005936', '6111213005936', 'Hublot Rond En Verre E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 1, 5, 1, 'U', 'Hublot Rond En Verre E27 - IP44', 'RAS', 'RAS', '666b2ea230a36.png'),
(46, '6111213014655', '6111213014655', 'Hublot Rond Plastique E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 0, 5, 1, 'U', 'Hublot Rond Plastique E27 - IP44', 'RAS', 'RAS', '666b30b2c81e5.png'),
(47, '3170070500810', '50081', 'Lampe à LED Flat 13W Aric ', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 2000, 0, 5, 1, 'U', 'Flat 13 W - IP 20 - IK02 - 650°', 'RAS', 'RAS', '6686a65298ea4.png'),
(48, '8718699386474', '108213490', 'Luminaire Encastré Panel Pour Application Tertiaire', 'Luminaires', 'Philips', 'Philips', 1000, 2000, 1500, 0, 3, 1, 'U', 'L 595 mm x W 595 mm x H 36 mm IP20', 'RAS', 'RAS', '6686b5ebd5a10.png'),
(49, '3542220523701', '052370', 'Détecteur de Mouvements Infrarouge', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 2, 1, 'U', '230 V - 50 Hz / 10A AC1 Max. 1000 W 360°', 'RAS', 'RAS', '6686ba998b92b.png'),
(50, '3542220522100', '52210', 'Détecteur Infrarouge Standard bl', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 1, 1, 'U', '230V AC - 50/60Hz - 15000W 10A AC1 - 200°', 'RAS', 'RAS', '6686bd0dbc717.png'),
(51, '4007529921492', '92149', 'Détecteur de Mouvement PD4N-1C', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 1, 1, 'U', '2300W - 1150VA - 360° - 230V', 'RAS', 'RAS', '6686bf44e5673.png'),
(52, '4007529922703', '92270', 'Détecteur de Mouvement PD4N-1C-K C', 'FILS ET CABLES', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 3, 1, 'U', '230V - 360° - IP44 - 2300W ', 'RAS', 'RAS', '6686c16c898ab.png'),
(53, '4007529921409', '92140', 'Détecteur de Présence PD4-RC M-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 1, 1, 'U', 'IP54 - 230V - 50-60Hz - 230VAC/2300W/10A', 'RAS', 'RAS', '6686c43eb7000.png'),
(54, '4007529924400', '92440', 'Détecteur de Présence ou Mouvement Master', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 2, 1, 'U', 'MASTER - AC 230V - R1:230VAC/2300W/10A\r\nR2: 230VAC/24VDC/3A', 'RAS', 'RAS', '6686c8112bb5d.png'),
(55, '4007529921423', '92142', 'Détecteur de Présence Esclave PD4-RC S-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 3, 1, 'U', 'IP54 - PD4-RC/ESCLAVE MONTAGE APPARENT - OPTOCOUPLER: 230VAC / 2W, 50-60Hz', 'RAS', 'RAS', '6686ca7b88cd5.png'),
(56, '3250615944054', '594405', 'Coffret VDI Semi-Equipe', 'Coffret Electrique', 'Hager', 'Hager', 1000, 2000, 1500, 0, 1, 1, 'U', '2R-20M\r\nGRADE 2TV\r\n\r\n250 mm\r\n250 mm\r\n103 mm', 'RAS', 'RAS', '6686cc6453eff.png'),
(57, '4003468181065', 'TG103010', 'Détecteur de Mouvement TIMEGUARD', 'Détecteur de Mouvements', 'TIMEGUARD', 'TIMEGUARD', 1000, 2000, 2500, 0, 6, 1, 'U', '1000w - 230 V AC - 360°', 'RAS', 'RAS', '668bb6ee0038d.png'),
(58, '3170070530572', '53057', 'Applique salle de Bain - Cuisine', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 1500, 0, 3, 1, 'U', 'ANGLE 120° - 220 - 240 V AC ; 50/60 Hz', 'RAS', 'RAS', '668bb965c55a1.png'),
(59, '3168106025413', '60254-1', 'Applique salle de Bain - Cuisine résistex', 'Luminaires', 'Résistex', 'Résistex', 1000, 2000, 1500, 0, 6, 1, 'U', 'Neofluo 8 W', 'RAS', 'RAS', '668bbc320a582.png'),
(60, '4007529910021', '91002', 'Détecteur de mouvement LC-Click-N 200', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 4, 1, 'U', '230 v - Angle 200° - Max 12 m - 1000W', 'RAS', 'RAS', '668bbfa781cc4.png'),
(61, '4007529910083', '91008', 'Détecteur de Mouvement LC-plus 280', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 1, 1, 'U', 'Angle 280° - 230v - Max 16m - 2000 W - 1000 VA', 'RAS', 'RAS', '668bc1dccdb4b.jpg'),
(62, '13606481465822', '146582', 'Prise 2P+T FR Affleurante Connect Auto', 'Prises', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 56, 1, 'U', '16A - 250 V', 'RAS', 'RAS', '668bc5c649632.png'),
(63, '23303430207256', '20725', 'Disjoncteur 10 A - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 28, 1, 'U', '10 A', 'RAS', 'RAS', '668bc82f2c3cb.png'),
(64, '23303430207270', '20727', 'Disjoncteur - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 9, 1, 'U', '3000A - 230V', 'RAS', 'RAS', '668bc9c47e3a1.png'),
(65, '3606481381330', '22616', 'Disjoncteur modulaire 1P+N ', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 9, 1, 'U', '16A', 'RAS', 'RAS', '668bcf22f12a7.png'),
(66, '113000', '113000', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 6, 1, 'U', 'Contrôle manuel - Uralight - Habitation incadescent', 'RAS', 'RAS', '668bd0c99c22f.png'),
(67, '3613400163144', '110236', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 1, 1, 'U', 'Contrôle Manuel', 'RAS', 'RAS', '668bd259b39a2.png'),
(69, '3613400166831', '111013', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 3, 1, 'U', '230V - 50/60 Hz 0,8W', 'RAS', 'RAS', '668bd688112f9.png'),
(70, '1950305', '1950305', 'Grille de Protection', 'Grille de protection', 'NO name', 'URA', 1000, 2000, 1500, 0, 2, 1, 'U', 'IP 9', 'RAS', 'RAS', '668bd8c39b678.png'),
(71, '26014531', '1453', 'Jeu de Tournevis 8', 'Jeu de Tournevis', 'Workzone', 'Workzone', 1000, 2000, 1500, 0, 10, 1, 'U', 'Pack de 8 tournevis', 'RAS', 'RAS', '668bdca74982d.png'),
(72, 'GI545ECOJF', 'HC20131015', 'Gilet Jaune', 'Gilet', 'T2S', 'T2S', 1000, 2000, 1500, 0, 8, 1, 'U', 'Gilet Série 543', 'RAS', 'RAS', '668bdf4b03aaf.png'),
(73, 'hager_0001', 'hager_0001', 'Jeu de Tournevis 6 Hager', 'Jeu de Tournevis', 'Hager', 'Hager', 1000, 2000, 1500, 0, 1, 1, 'U', '6 Tournevis', 'RAS', 'RAS', '668be1caca031.png'),
(74, '3439510540664', '054066', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 2, 1, 'U', 'Dia 12 200/260', 'RAS', 'RAS', '668be9638fca9.png'),
(75, '3439510540534', '054053', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 3, 1, 'U', 'Mèche', 'RAS', 'RAS', '668bea09753a9.png'),
(76, '3250617102568', '710256', 'Prise TV-FM-SAT', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 20, 1, 'U', 'Prise TV-FM-SAT 1 Câble\r\n', 'RAS', 'RAS', '668bf2bd44508.png'),
(77, '3250617100250', 'WE025', 'Poussoir_Porte-étiquette', 'Poussoir Porte-Etiquette', 'Hager', 'Hager', 1000, 2000, 1500, 0, 10, 1, 'U', '10A 2 -50V - Essensya', 'RAS', 'RAS', '668bf78507b18.png'),
(78, '3250617101004', 'WE100', 'Prise 2P+T Hager', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 60, 1, 'U', '16A 250V', 'RAS', 'RAS', '668bfa2a6d3fb.png'),
(79, '3250617100014', 'WE001', 'Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 90, 1, 'U', '10A - 250V', 'RAS', 'RAS', '668bfcb250393.png'),
(80, '3250617100403', 'WE040', 'Double Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 40, 1, 'U', '10A - 250V', 'RAS', 'RAS', '668bfe484a20a.png'),
(81, '3663752049917', '60840', 'Prise à Saillie Etanche PC 2P+T', 'Prises', 'EUROHM', 'EUROHM', 1000, 2000, 1500, 0, 20, 1, 'U', 'PC 2 P+T', 'RAS', 'RAS', '668c00d143393.png'),
(82, '3250617104036', 'WE403', 'Plaque 3 Postes Horizontale - Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 10, 1, 'U', 'Plaque 3 Postes Horizontale - Verticale', 'RAS', 'RAs', '668c03d44f771.png'),
(83, '3250617104029', 'WE402', 'Plaque 2 postes Horizontale + Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 30, 1, 'U', 'Plaque 2 postes Horizontale + Verticale', 'RAS', 'RAS', '668c05a0443e6.jpg'),
(84, '3250617104012', 'WE401', 'Plaque Plate', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 140, 1, 'U', 'Plaque Plate', 'RAS', 'RAS', '668c0a87d849c.jpg'),
(85, '3245064012126', '401212', 'Coffret Saillie Electrique 2R-13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 1, 1, 'U', 'Coffret Electrique 2 rangées', 'RAS', 'RAS', '668c0f80a430e.jpg'),
(86, '3245064013321', '401332', 'Porte Opaque Coffret', 'Porte Coffret', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 1, 1, 'U', 'Porte Opaque Coffret', 'RAS', 'RAS', '668c12e0c316c.jpg'),
(87, '3245064012133', '401213', 'Coffret Saillie DRIVIA 3R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 1, 1, 'U', 'Coffret 3Rangées - 13M', 'RAS', 'RAS', '668c15cc3b7eb.jpg'),
(88, '3245064012140', '401214', 'Coffret saillie 4R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 1, 1, 'U', 'Coffret saillie 4Rangées - 13M', 'RAS', 'RAS', '668c18ca8cb43.jpg'),
(90, '3245066647111', '664711', 'Interrupteur volets roulants - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 20, 1, 'U', '6AX 250V', 'RAS', 'RAS', '668fa458e56af.jpg'),
(91, '3245066647098', '664709', 'Interrupteur Va-et-vient + poussoir - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 20, 1, 'U', '10AX 250V  - 6A 250V-', 'RAS', 'RAS', '668fa613a78de.jpg'),
(92, '3414970011114', '664735', 'Prise 2P+T avec éclips - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 100, 1, 'U', '16A 250V', 'RAS', 'RAS', '668faa5425ca5.jpg'),
(93, '3245066647029', '664702', 'Interrupteur Double va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 100, 1, 'U', '10AX 250V', 'RAS', 'RAS', '668fac0fe3253.jpg'),
(94, '3414970011060', '664701', 'Interrupteur Va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 100, 1, 'U', '10AX 250V', 'RAS', 'RAS', '668fad950dfdf.jpg'),
(95, '3245066647517', '664751', 'Prise TV étoile blindée - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 40, 1, 'U', 'Interrupteur Va-et-vient - Pur', 'RAS', 'RAS', '668faeef33b99.jpg'),
(96, '3414970011084', '664710', 'Interrupteur va-et-vient à voyant - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', '10AX 250V', 'RAS', 'RAS', '668fb0991152c.jpg'),
(97, '3414971006201', '600343', 'Chargeur USB Universel Type A', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 6, 1, 'U', 'Input : 100-240V - 50/60Hz-0.5A\r\nOutput : 5.0V  3.0A 15.0W', 'RAS', 'RAS', '668fb302c2906.jpg'),
(98, '3414970534668', '068111', 'Enjoliveur Prise 2P+T Surface - Blance', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 80, 1, 'U', 'Enjoliveur Prise 2P+T Surface - Blance', 'RAS', 'RAS', '668fb4e397f9d.jpg'),
(99, '011324506068001230000010', '68001', 'Interrupteur - Commande Simple 1 ', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 20, 1, 'U', 'Interrupteur - Commande Simple 1 ', 'RAS', 'RAS', '668fb68a47116.jpg'),
(100, '3414971390201', '068556', 'Enjoliveur Prise Double USB - Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 5, 1, 'U', 'Enjoliveur Prise Double USB - Titane', 'RAS', 'RAS', '668fb88541fa0.jpg'),
(101, '3414970487179', '067106', 'Chargeur Usb + 2P+T FB précâblé', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 2, 1, 'U', 'Input : 220-240V 50-60Hz - \r\nOutput: 5V- 3A', 'RAS', 'RAS', '668fba9f212d2.jpg'),
(102, '3414970338679', '067111', 'Prise 2P+T', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 120, 1, 'U', 'Prise 2P+T', 'RAS', 'RAS', '668fbbf71c685.jpg'),
(103, '3414970338648', '067001', 'Va-et-vient', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 60, 1, 'U', '10AX-250V', 'RAS', 'RAS', '668fbdb52102f.jpg'),
(104, '3414970534682', '068411', 'Enjoliveur 2P+T Surface Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', 'Enjoliveur 2P+T Surface Titane', 'RAS', 'RAS', '668fbf69cdd13.jpg'),
(105, '3414971376007', '068551', 'Enjoliveur RJ45-Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 10, 1, 'U', 'Enjoliveur RJ45-Titane', 'RAS', 'RAS', '668fc13fcce2d.jpg'),
(106, '3414971377257', '068901', 'Plaque', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 10, 1, 'U', 'Plaque', 'RAS', 'RAS', '668fc28ba6b46.jpg'),
(107, '3414971388246', '068902', 'Plaque Double', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 10, 1, 'U', 'Plaque Double', 'RAS', 'RAS', '668fc3d2400ef.jpg'),
(108, '3414971390997', '068903', 'Plaque Legrand Triple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 5, 1, 'U', 'Plaque Legrand Triple', 'RAS', 'RAS', '668fc4dcba6fb.jpg'),
(109, '3414971483132', '080253', 'Support 3 Postes', 'Support', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 5, 1, 'U', 'Support 3 Postes', 'RAS', 'RAS', '668fc65cf1ad2.jpg'),
(110, '3414970648426', '080251', 'Support 1 Poste', 'Support', 'Legrans', 'Legrand', 1000, 2000, 1500, 0, 80, 1, 'U', 'Support 1 Poste', 'RAS', 'RAS', '668fc7ae634a8.jpg'),
(111, '3414971575974', '077111L', 'Prise 2 P+T Legrand', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 40, 1, 'U', '16A - 250V', 'RAS', 'RAS', '668fc957e2665.jpg'),
(112, '3414971575110', '077001L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', '1M 10AX 250V', 'RAS', 'RAS', '668fcadf3cbb2.jpg'),
(113, '3414971575172', '077011L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 70, 1, 'U', '2M 10AX - 250v', 'RAS', 'RAS', '668fcd06ab329.jpg'),
(114, '3414971531758', '076565', 'Prise RJ45', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 20, 1, 'U', '2M FTP', 'RAS', 'RAS', '668fce225821f.jpg'),
(115, '3414971019577', '600802', 'Plaque Dooxie Blanc', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 130, 1, 'U', 'Plaque Dooxie Blanc', 'RAS', 'RAS', '668fcff12d930.jpg'),
(116, '3414971130975', '600801', 'Plaque 1 Poste Legrand Dooxie', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 260, 1, 'U', 'Blanc 23 W 28', 'RAS', 'RAS', '668fd1a353347.jpg'),
(117, '3414971006119', '600335', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 70, 1, 'U', '16A - 250 V', 'RAS', 'RAS', '668fd30da5929.jpg'),
(118, '3414971203044', '600011', 'Interrupteur ou Va-et-vient Lumineux Voyant fourni', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 10, 1, 'U', '10 AX - 250V', 'RAS', 'RAS', '668fd44666494.jpg'),
(119, '3414971004290', '600002', 'Intrerrupteur - Double va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 40, 1, 'U', '10 AX - 250V', 'RAS', 'RAS', '668fd56e29f06.jpg'),
(120, '3414971006966', '600635', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 50, 1, 'U', 'Prise 2P+T Surface avec Bornes Auto', 'RAS', 'RAS', '668fd8265eef1.jpg'),
(121, '3414971004269', '600001', 'Interrupteur ou va-et-vient Legrand', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 80, 1, 'U', '10 AX - 250 V', 'RAS', 'RAS', '668fe1f903da6.jpg'),
(122, '3414971203617', '600376', 'Prise RJ45 CAT 6 FTP', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', 'Prise RJ45 CAT 6 FTP', 'RAS', 'RAS', '668fe52a9269b.jpg'),
(123, '3414971093263', '600351', 'Prise TV Male  Etoile Blindée', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 10, 1, 'U', 'Prise TV Male  Etoile Blindée Blanc', 'RAS', 'RAS', '668feac449350.jpg'),
(124, '3414971203488', '600353', 'Prise TV - R  - SAT Etoile Blindé', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', 'Prise TV - R  - SAT Etoile Blindé Blanc', 'RAS', 'RAS', '668fecb0b6b8c.jpg'),
(125, '3414971284197', '600325', 'Sortie de Cable associable', 'Sortie de Cables', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 30, 1, 'U', 'Dia. 12mm maxi - 3x2,5 mm 250V', 'RAS', 'RAS', '668fee38685d4.jpg'),
(126, '3414971384132', '665004', 'Plaque Quadruple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 40, 1, 'U', 'Plaque Quadruple Blanc', 'RAS', 'RAS', '668fefb0563a3.jpg'),
(127, '3245066650098', '665009', 'Plaque 1 Poste Legrand', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 100, 1, 'U', 'Plaque 1 Poste Legrand', 'RAS', 'RAs', '668ff1079a36d.jpg'),
(128, '3233625002679', '64403', 'Plaque 3 postes Arnould', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 5, 1, 'U', 'Plaque 3 postes Arnould Blanc', 'RAS', 'RAs', '668ff2d6ed253.jpg'),
(129, '3233625002310', '64402', 'Plaque 2 postes Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 70, 1, 'U', 'Plaque 2 postes Blanc', 'RAS', 'RAs', '668ff4b3dd360.jpg'),
(130, '3233625003034', '64401', 'Plaque 1 poste Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 60, 1, 'U', 'Plaque 1 poste Blanc', 'RAS', 'RAS', '668ff65be3fa2.jpg'),
(131, '3233625001283', '64031', 'Prise 2P+T BA Arnould 16A', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 160, 1, 'U', 'Prise 2P+T BA Arnould 16A', 'RAS', 'RAS', '668ff791c6905.jpg'),
(132, '3233625001375', '64001', 'Interrupteur Va-et-vient Arnould', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 104, 1, 'U', 'Interrupteur Va-et-vient Arnould Blanc', 'RAS', 'RAS', '668ff983afa91.jpg'),
(133, '3233625000538', '64002', 'Interrupteur Va-et-Vient 10AX double', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 14, 1, 'U', 'Interrupteur Va-et-Vient 10AX double', 'RAS', 'RAS', '668ffb5fe260b.jpg'),
(134, '3233625000354', '64077', 'Prise TV R SAT étoile Blanc', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 34, 1, 'U', 'Prise TV R SAT étoile Blanc', 'RAS', 'RAS', '668ffc7d127ba.jpg'),
(135, '3233620606445', '60644', 'Sortie de Câble Blanc Lumière', 'Sortie de Cable', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 10, 1, 'U', 'Sortie de Câble Blanc Lumière', 'RAS', 'RAS', '668ffe28015b9.jpg'),
(136, '3233620601105', '60110', 'Bouton Poussoir', 'Bouton Poussoir', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 10, 1, 'U', 'Bouton Poussoir', 'RAS', 'RAS', '668fff8d29f2c.jpg'),
(137, '3233620606995', '60699', 'Obturateur Lumière', 'Obturateur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 20, 1, 'U', 'Obturateur Lumière Blanc', 'RAS', 'RAS', '6690010c6f00a.jpg'),
(138, '3233620605134', '60513', 'Interrupteur Va et vient Témoin sans Neutre', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 15, 1, 'U', 'Interrupteur Va et vient Témoin sans Neutre 4AX 250V - Lumière', 'RAS', 'RAS', '66900236334f6.jpg'),
(139, '3233620606735', '60673', 'Prise TV FM Espace - Lumière', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 30, 1, 'U', 'Prise TV FM Espace - Lumière', 'RAS', 'RAS', '669003b5f1063.jpg'),
(140, '3233620601204', '60120', 'Espace - Inverseur Fixe Volet roulant 10A', 'Inverseur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 15, 1, 'U', 'Espace - Inverseur Fixe Volet roulant 10A 250V Lumière', 'RAS', 'RAS', '66900788ab2af.jpg');

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
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `tbl_shop_item`
--

INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(64, 'bev_oyomabang', '000400504009', '000400504009', 'Spot à encastrer', 'Luminaires', 'Securlite', 'Securlite', 10, 14, 12, 1, 0, 0, 'U', 'Spot - Luminaire', 'RAS', '666af45d37415.png'),
(65, 'bev_Biyemassi', '000400504009', '000400504009', 'Spot à encastrer', 'Luminaires', 'Securlite', 'Securlite', 10, 14, 12, 1, 0, 0, 'U', 'Spot - Luminaire', 'RAS', '666af45d37415.png'),
(66, 'leti_nkolbisson', '000400504009', '000400504009', 'Spot à encastrer', 'Luminaires', 'Securlite', 'Securlite', 10, 14, 12, 1, 0, 0, 'U', 'Spot - Luminaire', 'RAS', '666af45d37415.png'),
(67, 'bev_oyomabang', '8018367017205', '8018367017205', 'Hublot Superdelta Ton Bianco', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 0, 0, 1, 'U', 'Luminaires - IP 54', 'RAS', '666afcd32dff5.png'),
(68, 'bev_Biyemassi', '8018367017205', '8018367017205', 'Hublot Superdelta Ton Bianco', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 0, 0, 1, 'U', 'Luminaires - IP 54', 'RAS', '666afcd32dff5.png'),
(69, 'leti_nkolbisson', '8018367017205', '8018367017205', 'Hublot Superdelta Ton Bianco', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 0, 0, 1, 'U', 'Luminaires - IP 54', 'RAS', '666afcd32dff5.png'),
(70, 'bev_oyomabang', '8018367057423', '8018367057423', 'Hublot Chip Tondo 25 Nero', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 2, 0, 1, 'U', 'IP 44', 'RAS', '666afef19c425.png'),
(71, 'bev_Biyemassi', '8018367057423', '8018367057423', 'Hublot Chip Tondo 25 Nero', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 2, 0, 1, 'U', 'IP 44', 'RAS', '666afef19c425.png'),
(72, 'leti_nkolbisson', '8018367057423', '8018367057423', 'Hublot Chip Tondo 25 Nero', 'Luminaires', 'PRISMA', 'PRISMA', 10, 100, 150, 2, 0, 1, 'U', 'IP 44', 'RAS', '666afef19c425.png'),
(73, 'bev_oyomabang', '3292297445504', '3292297445504', 'Hublot Chartres Ovale - Détecteur', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 55 - Détection Mouvements', 'RAS', '666b0257f338d.png'),
(74, 'bev_Biyemassi', '3292297445504', '3292297445504', 'Hublot Chartres Ovale - Détecteur', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 55 - Détection Mouvements', 'RAS', '666b0257f338d.png'),
(75, 'leti_nkolbisson', '3292297445504', '3292297445504', 'Hublot Chartres Ovale - Détecteur', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 55 - Détection Mouvements', 'RAS', '666b0257f338d.png'),
(76, 'bev_oyomabang', '3072820115315', '3072820115315', 'Hublot 11531 SCALA 400 2 lampes', 'Luminaires', 'EPSILON', 'EPSILON', 100, 120, 115, 0, 0, 1, 'U', '2 lampes - 2x42w E27 ES', 'RAS', '666b058079030.png'),
(77, 'bev_Biyemassi', '3072820115315', '3072820115315', 'Hublot 11531 SCALA 400 2 lampes', 'Luminaires', 'EPSILON', 'EPSILON', 100, 120, 115, 0, 0, 1, 'U', '2 lampes - 2x42w E27 ES', 'RAS', '666b058079030.png'),
(78, 'leti_nkolbisson', '3072820115315', '3072820115315', 'Hublot 11531 SCALA 400 2 lampes', 'Luminaires', 'EPSILON', 'EPSILON', 100, 120, 115, 0, 0, 1, 'U', '2 lampes - 2x42w E27 ES', 'RAS', '666b058079030.png'),
(79, 'bev_oyomabang', '3292290502983', '3292290502983', 'Hublot Chartres Infini - Extra plat LED', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP55', 'RAS', '666b0825ca187.png'),
(80, 'bev_Biyemassi', '3292290502983', '3292290502983', 'Hublot Chartres Infini - Extra plat LED', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP55', 'RAS', '666b0825ca187.png'),
(81, 'leti_nkolbisson', '3292290502983', '3292290502983', 'Hublot Chartres Infini - Extra plat LED', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP55', 'RAS', '666b0825ca187.png'),
(82, 'bev_oyomabang', '3292294202704', '3292294202704', 'Hublot Sarlam Plasti Verre Tout attaché', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 120, 0, 0, 1, 'U', 'IP 44 Hublot Sarlam Plasti Verre Tout attaché', 'RAS', '666b0cd407525.png'),
(83, 'bev_Biyemassi', '3292294202704', '3292294202704', 'Hublot Sarlam Plasti Verre Tout attaché', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 120, 0, 0, 1, 'U', 'IP 44 Hublot Sarlam Plasti Verre Tout attaché', 'RAS', '666b0cd407525.png'),
(84, 'leti_nkolbisson', '3292294202704', '3292294202704', 'Hublot Sarlam Plasti Verre Tout attaché', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 120, 0, 0, 1, 'U', 'IP 44 Hublot Sarlam Plasti Verre Tout attaché', 'RAS', '666b0cd407525.png'),
(85, 'bev_oyomabang', '3292297243766', '3292297243766', 'Hublot Sarlam Chartres T2 Anti Vandales', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 Hublot Sarlam Chartres T2 Anti Vandales', 'RAS', '666b0ff686bda.png'),
(86, 'bev_Biyemassi', '3292297243766', '3292297243766', 'Hublot Sarlam Chartres T2 Anti Vandales', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 Hublot Sarlam Chartres T2 Anti Vandales', 'RAS', '666b0ff686bda.png'),
(87, 'leti_nkolbisson', '3292297243766', '3292297243766', 'Hublot Sarlam Chartres T2 Anti Vandales', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 Hublot Sarlam Chartres T2 Anti Vandales', 'RAS', '666b0ff686bda.png'),
(88, 'bev_oyomabang', '011329229514273030000005', '011329229514273030000005', 'Hublot SARLAM Chartres Rond', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 - Hublot SARLAM Chartres Rond', 'RAS', '666b123ef0302.png'),
(89, 'bev_Biyemassi', '011329229514273030000005', '011329229514273030000005', 'Hublot SARLAM Chartres Rond', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 - Hublot SARLAM Chartres Rond', 'RAS', '666b123ef0302.png'),
(90, 'leti_nkolbisson', '011329229514273030000005', '011329229514273030000005', 'Hublot SARLAM Chartres Rond', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'IP 54 - Hublot SARLAM Chartres Rond', 'RAS', '666b123ef0302.png'),
(91, 'bev_oyomabang', '3292291898177', '3292291898177', 'Applique SARLAM Prisma Line Plus', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'Kg', 'IP 24 - Applique Prisma Line Plus', 'RAS', '666b1457033dc.png'),
(92, 'bev_Biyemassi', '3292291898177', '3292291898177', 'Applique SARLAM Prisma Line Plus', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'Kg', 'IP 24 - Applique Prisma Line Plus', 'RAS', '666b1457033dc.png'),
(93, 'leti_nkolbisson', '3292291898177', '3292291898177', 'Applique SARLAM Prisma Line Plus', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'Kg', 'IP 24 - Applique Prisma Line Plus', 'RAS', '666b1457033dc.png'),
(94, 'bev_oyomabang', '3292291908173', '3292291908173', 'Applique SARLAM Prisma Line', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Prisma Line IP 24', 'RAS', '666b1725f1f17.png'),
(95, 'bev_Biyemassi', '3292291908173', '3292291908173', 'Applique SARLAM Prisma Line', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Prisma Line IP 24', 'RAS', '666b1725f1f17.png'),
(96, 'leti_nkolbisson', '3292291908173', '3292291908173', 'Applique SARLAM Prisma Line', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Prisma Line IP 24', 'RAS', '666b1725f1f17.png'),
(97, 'bev_oyomabang', '3292291185116', '3292291185116', 'Applique SARLAM Fluolux', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Fluolux 18WEL Simple IP 44', 'RAS', '666b19a590349.png'),
(98, 'bev_Biyemassi', '3292291185116', '3292291185116', 'Applique SARLAM Fluolux', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Fluolux 18WEL Simple IP 44', 'RAS', '666b19a590349.png'),
(99, 'leti_nkolbisson', '3292291185116', '3292291185116', 'Applique SARLAM Fluolux', 'Luminaires', 'SARLAM', 'SARLAM', 100, 150, 125, 0, 0, 1, 'U', 'Applique SARLAM Fluolux 18WEL Simple IP 44', 'RAS', '666b19a590349.png'),
(100, 'bev_oyomabang', '3477870553016', '3477870553016', 'Applique Ebenoid Diffuseur Circuit LED', 'Luminaires', 'EBENOID', 'EBENOID', 100, 150, 125, 0, 0, 1, 'U', 'Applique Ebenoid Diffuseur Circuit LED 9W / 3000K Appareil Classe II IP 24', 'RAS', '666b1c043550a.png'),
(101, 'bev_Biyemassi', '3477870553016', '3477870553016', 'Applique Ebenoid Diffuseur Circuit LED', 'Luminaires', 'EBENOID', 'EBENOID', 100, 150, 125, 0, 0, 1, 'U', 'Applique Ebenoid Diffuseur Circuit LED 9W / 3000K Appareil Classe II IP 24', 'RAS', '666b1c043550a.png'),
(102, 'leti_nkolbisson', '3477870553016', '3477870553016', 'Applique Ebenoid Diffuseur Circuit LED', 'Luminaires', 'EBENOID', 'EBENOID', 100, 150, 125, 0, 0, 1, 'U', 'Applique Ebenoid Diffuseur Circuit LED 9W / 3000K Appareil Classe II IP 24', 'RAS', '666b1c043550a.png'),
(103, 'bev_oyomabang', 'PVT11400', 'PVT11400', 'Applique Extérieur  Roger Pradier', 'Luminaires', 'Roger Pradier', 'Roger Pradier', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieur  Roger Pradier', 'RAS', '666b1e94ec4df.png'),
(104, 'bev_Biyemassi', 'PVT11400', 'PVT11400', 'Applique Extérieur  Roger Pradier', 'Luminaires', 'Roger Pradier', 'Roger Pradier', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieur  Roger Pradier', 'RAS', '666b1e94ec4df.png'),
(105, 'leti_nkolbisson', 'PVT11400', 'PVT11400', 'Applique Extérieur  Roger Pradier', 'Luminaires', 'Roger Pradier', 'Roger Pradier', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieur  Roger Pradier', 'RAS', '666b1e94ec4df.png'),
(106, 'bev_oyomabang', '3139180250138', '3139180250138', 'Applique Extérieiur Mazda', 'Luminaires', 'MAZDA', 'MAZDA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieiur Mazda IP54', 'RAS', '666b22b1b739d.png'),
(107, 'bev_Biyemassi', '3139180250138', '3139180250138', 'Applique Extérieiur Mazda', 'Luminaires', 'MAZDA', 'MAZDA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieiur Mazda IP54', 'RAS', '666b22b1b739d.png'),
(108, 'leti_nkolbisson', '3139180250138', '3139180250138', 'Applique Extérieiur Mazda', 'Luminaires', 'MAZDA', 'MAZDA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieiur Mazda IP54', 'RAS', '666b22b1b739d.png'),
(109, 'bev_oyomabang', '8018367017083', '8018367017083', 'Applique Extérieure Ovale Prisma Super Delta', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieure Ovale Prisma Super Delta Bianco - IP54', 'RAS', '666b262ec2163.png'),
(110, 'bev_Biyemassi', '8018367017083', '8018367017083', 'Applique Extérieure Ovale Prisma Super Delta', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieure Ovale Prisma Super Delta Bianco - IP54', 'RAS', '666b262ec2163.png'),
(111, 'leti_nkolbisson', '8018367017083', '8018367017083', 'Applique Extérieure Ovale Prisma Super Delta', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Applique Extérieure Ovale Prisma Super Delta Bianco - IP54', 'RAS', '666b262ec2163.png'),
(112, 'bev_oyomabang', '8018367059045', '8018367059045', 'Hublot Prisma UNICA 28 SATIN', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma UNICA 28 SATIN - IP 44', 'RAS', '666b29088d632.png'),
(113, 'bev_Biyemassi', '8018367059045', '8018367059045', 'Hublot Prisma UNICA 28 SATIN', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma UNICA 28 SATIN - IP 44', 'RAS', '666b29088d632.png'),
(114, 'leti_nkolbisson', '8018367059045', '8018367059045', 'Hublot Prisma UNICA 28 SATIN', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma UNICA 28 SATIN - IP 44', 'RAS', '666b29088d632.png'),
(115, 'bev_oyomabang', '8018367057416', '8018367057416', 'Hublot Prisma Chip Ton 25G', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma Chip Ton 25G - IP44', 'RAS', '666b2c50726f4.png'),
(116, 'bev_Biyemassi', '8018367057416', '8018367057416', 'Hublot Prisma Chip Ton 25G', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma Chip Ton 25G - IP44', 'RAS', '666b2c50726f4.png'),
(117, 'leti_nkolbisson', '8018367057416', '8018367057416', 'Hublot Prisma Chip Ton 25G', 'Luminaires', 'PRISMA', 'PRISMA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Prisma Chip Ton 25G - IP44', 'RAS', '666b2c50726f4.png'),
(118, 'bev_oyomabang', '6111213005936', '6111213005936', 'Hublot Rond En Verre E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 1, 0, 1, 'U', 'Hublot Rond En Verre E27 - IP44', 'RAS', '666b2ea230a36.png'),
(119, 'bev_Biyemassi', '6111213005936', '6111213005936', 'Hublot Rond En Verre E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 1, 0, 1, 'U', 'Hublot Rond En Verre E27 - IP44', 'RAS', '666b2ea230a36.png'),
(120, 'leti_nkolbisson', '6111213005936', '6111213005936', 'Hublot Rond En Verre E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 1, 0, 1, 'U', 'Hublot Rond En Verre E27 - IP44', 'RAS', '666b2ea230a36.png'),
(121, 'bev_oyomabang', '6111213014655', '6111213014655', 'Hublot Rond Plastique E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Rond Plastique E27 - IP44', 'RAS', '666b30b2c81e5.png'),
(122, 'bev_Biyemassi', '6111213014655', '6111213014655', 'Hublot Rond Plastique E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Rond Plastique E27 - IP44', 'RAS', '666b30b2c81e5.png'),
(123, 'leti_nkolbisson', '6111213014655', '6111213014655', 'Hublot Rond Plastique E27', 'Luminaires', 'LUMINA', 'LUMINA', 100, 150, 125, 0, 0, 1, 'U', 'Hublot Rond Plastique E27 - IP44', 'RAS', '666b30b2c81e5.png'),
(124, 'bev_oyomabang', '3170070500810', '50081', 'Lampe à LED Flat 13W Aric ', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 2000, 0, 0, 1, 'U', 'Flat 13 W - IP 20 - IK02 - 650°', 'RAS', '6686a65298ea4.png'),
(125, 'bev_Biyemassi', '3170070500810', '50081', 'Lampe à LED Flat 13W Aric ', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 2000, 0, 0, 1, 'U', 'Flat 13 W - IP 20 - IK02 - 650°', 'RAS', '6686a65298ea4.png'),
(126, 'leti_nkolbisson', '3170070500810', '50081', 'Lampe à LED Flat 13W Aric ', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 2000, 0, 0, 1, 'U', 'Flat 13 W - IP 20 - IK02 - 650°', 'RAS', '6686a65298ea4.png'),
(127, 'bev_oyomabang', '8718699386474', '108213490', 'Luminaire Encastré Panel Pour Application Tertiaire', 'Luminaires', 'Philips', 'Philips', 1000, 2000, 1500, 0, 0, 1, 'U', 'L 595 mm x W 595 mm x H 36 mm IP20', 'RAS', '6686b5ebd5a10.png'),
(128, 'bev_Biyemassi', '8718699386474', '108213490', 'Luminaire Encastré Panel Pour Application Tertiaire', 'Luminaires', 'Philips', 'Philips', 1000, 2000, 1500, 0, 0, 1, 'U', 'L 595 mm x W 595 mm x H 36 mm IP20', 'RAS', '6686b5ebd5a10.png'),
(129, 'leti_nkolbisson', '8718699386474', '108213490', 'Luminaire Encastré Panel Pour Application Tertiaire', 'Luminaires', 'Philips', 'Philips', 1000, 2000, 1500, 0, 0, 1, 'U', 'L 595 mm x W 595 mm x H 36 mm IP20', 'RAS', '6686b5ebd5a10.png'),
(130, 'bev_oyomabang', '3542220523701', '052370', 'Détecteur de Mouvements Infrarouge', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230 V - 50 Hz / 10A AC1 Max. 1000 W 360°', 'RAS', '6686ba998b92b.png'),
(131, 'bev_Biyemassi', '3542220523701', '052370', 'Détecteur de Mouvements Infrarouge', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230 V - 50 Hz / 10A AC1 Max. 1000 W 360°', 'RAS', '6686ba998b92b.png'),
(132, 'leti_nkolbisson', '3542220523701', '052370', 'Détecteur de Mouvements Infrarouge', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230 V - 50 Hz / 10A AC1 Max. 1000 W 360°', 'RAS', '6686ba998b92b.png'),
(133, 'bev_oyomabang', '3542220522100', '52210', 'Détecteur Infrarouge Standard bl', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230V AC - 50/60Hz - 15000W 10A AC1 - 200°', 'RAS', '6686bd0dbc717.png'),
(134, 'bev_Biyemassi', '3542220522100', '52210', 'Détecteur Infrarouge Standard bl', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230V AC - 50/60Hz - 15000W 10A AC1 - 200°', 'RAS', '6686bd0dbc717.png'),
(135, 'leti_nkolbisson', '3542220522100', '52210', 'Détecteur Infrarouge Standard bl', 'Détecteur de Mouvements', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '230V AC - 50/60Hz - 15000W 10A AC1 - 200°', 'RAS', '6686bd0dbc717.png'),
(136, 'bev_oyomabang', '4007529921492', '92149', 'Détecteur de Mouvement PD4N-1C', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '2300W - 1150VA - 360° - 230V', 'RAS', '6686bf44e5673.png'),
(137, 'bev_Biyemassi', '4007529921492', '92149', 'Détecteur de Mouvement PD4N-1C', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '2300W - 1150VA - 360° - 230V', 'RAS', '6686bf44e5673.png'),
(138, 'leti_nkolbisson', '4007529921492', '92149', 'Détecteur de Mouvement PD4N-1C', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '2300W - 1150VA - 360° - 230V', 'RAS', '6686bf44e5673.png'),
(139, 'bev_oyomabang', '4007529922703', '92270', 'Détecteur de Mouvement PD4N-1C-K C', 'FILS ET CABLES', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 360° - IP44 - 2300W ', 'RAS', '6686c16c898ab.png'),
(140, 'bev_Biyemassi', '4007529922703', '92270', 'Détecteur de Mouvement PD4N-1C-K C', 'FILS ET CABLES', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 360° - IP44 - 2300W ', 'RAS', '6686c16c898ab.png'),
(141, 'leti_nkolbisson', '4007529922703', '92270', 'Détecteur de Mouvement PD4N-1C-K C', 'FILS ET CABLES', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 360° - IP44 - 2300W ', 'RAS', '6686c16c898ab.png'),
(142, 'bev_oyomabang', '4007529921409', '92140', 'Détecteur de Présence PD4-RC M-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - 230V - 50-60Hz - 230VAC/2300W/10A', 'RAS', '6686c43eb7000.png'),
(143, 'bev_Biyemassi', '4007529921409', '92140', 'Détecteur de Présence PD4-RC M-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - 230V - 50-60Hz - 230VAC/2300W/10A', 'RAS', '6686c43eb7000.png'),
(144, 'leti_nkolbisson', '4007529921409', '92140', 'Détecteur de Présence PD4-RC M-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - 230V - 50-60Hz - 230VAC/2300W/10A', 'RAS', '6686c43eb7000.png'),
(145, 'bev_oyomabang', '4007529924400', '92440', 'Détecteur de Présence ou Mouvement Master', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'MASTER - AC 230V - R1:230VAC/2300W/10A\r\nR2: 230VAC/24VDC/3A', 'RAS', '6686c8112bb5d.png'),
(146, 'bev_Biyemassi', '4007529924400', '92440', 'Détecteur de Présence ou Mouvement Master', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'MASTER - AC 230V - R1:230VAC/2300W/10A\r\nR2: 230VAC/24VDC/3A', 'RAS', '6686c8112bb5d.png'),
(147, 'leti_nkolbisson', '4007529924400', '92440', 'Détecteur de Présence ou Mouvement Master', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'MASTER - AC 230V - R1:230VAC/2300W/10A\r\nR2: 230VAC/24VDC/3A', 'RAS', '6686c8112bb5d.png'),
(148, 'bev_oyomabang', '4007529921423', '92142', 'Détecteur de Présence Esclave PD4-RC S-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - PD4-RC/ESCLAVE MONTAGE APPARENT - OPTOCOUPLER: 230VAC / 2W, 50-60Hz', 'RAS', '6686ca7b88cd5.png'),
(149, 'bev_Biyemassi', '4007529921423', '92142', 'Détecteur de Présence Esclave PD4-RC S-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - PD4-RC/ESCLAVE MONTAGE APPARENT - OPTOCOUPLER: 230VAC / 2W, 50-60Hz', 'RAS', '6686ca7b88cd5.png'),
(150, 'leti_nkolbisson', '4007529921423', '92142', 'Détecteur de Présence Esclave PD4-RC S-AP', 'Détecteur de Présence', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP54 - PD4-RC/ESCLAVE MONTAGE APPARENT - OPTOCOUPLER: 230VAC / 2W, 50-60Hz', 'RAS', '6686ca7b88cd5.png'),
(151, 'bev_oyomabang', '3250615944054', '594405', 'Coffret VDI Semi-Equipe', 'Coffret Electrique', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '2R-20M\r\nGRADE 2TV\r\n\r\n250 mm\r\n250 mm\r\n103 mm', 'RAS', '6686cc6453eff.png'),
(152, 'bev_Biyemassi', '3250615944054', '594405', 'Coffret VDI Semi-Equipe', 'Coffret Electrique', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '2R-20M\r\nGRADE 2TV\r\n\r\n250 mm\r\n250 mm\r\n103 mm', 'RAS', '6686cc6453eff.png'),
(153, 'leti_nkolbisson', '3250615944054', '594405', 'Coffret VDI Semi-Equipe', 'Coffret Electrique', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '2R-20M\r\nGRADE 2TV\r\n\r\n250 mm\r\n250 mm\r\n103 mm', 'RAS', '6686cc6453eff.png'),
(154, 'bev_oyomabang', '4003468181065', 'TG103010', 'Détecteur de Mouvement TIMEGUARD', 'Détecteur de Mouvements', 'TIMEGUARD', 'TIMEGUARD', 1000, 2000, 2500, 0, 0, 1, 'U', '1000w - 230 V AC - 360°', 'RAS', '668bb6ee0038d.png'),
(155, 'bev_Biyemassi', '4003468181065', 'TG103010', 'Détecteur de Mouvement TIMEGUARD', 'Détecteur de Mouvements', 'TIMEGUARD', 'TIMEGUARD', 1000, 2000, 2500, 0, 0, 1, 'U', '1000w - 230 V AC - 360°', 'RAS', '668bb6ee0038d.png'),
(156, 'leti_nkolbisson', '4003468181065', 'TG103010', 'Détecteur de Mouvement TIMEGUARD', 'Détecteur de Mouvements', 'TIMEGUARD', 'TIMEGUARD', 1000, 2000, 2500, 0, 0, 1, 'U', '1000w - 230 V AC - 360°', 'RAS', '668bb6ee0038d.png'),
(157, 'bev_oyomabang', '3170070530572', '53057', 'Applique salle de Bain - Cuisine', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 1500, 0, 0, 0, 'U', 'ANGLE 120° - 220 - 240 V AC ; 50/60 Hz', 'RAS', '668bb965c55a1.png'),
(158, 'bev_Biyemassi', '3170070530572', '53057', 'Applique salle de Bain - Cuisine', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 1500, 0, 0, 0, 'U', 'ANGLE 120° - 220 - 240 V AC ; 50/60 Hz', 'RAS', '668bb965c55a1.png'),
(159, 'leti_nkolbisson', '3170070530572', '53057', 'Applique salle de Bain - Cuisine', 'Luminaires', 'ARIC', 'ARIC', 1000, 2000, 1500, 0, 0, 0, 'U', 'ANGLE 120° - 220 - 240 V AC ; 50/60 Hz', 'RAS', '668bb965c55a1.png'),
(160, 'bev_oyomabang', '3168106025413', '60254-1', 'Applique salle de Bain - Cuisine résistex', 'Luminaires', 'Résistex', 'Résistex', 1000, 2000, 1500, 0, 0, 0, 'U', 'Neofluo 8 W', 'RAS', '668bbc320a582.png'),
(161, 'bev_Biyemassi', '3168106025413', '60254-1', 'Applique salle de Bain - Cuisine résistex', 'Luminaires', 'Résistex', 'Résistex', 1000, 2000, 1500, 0, 0, 0, 'U', 'Neofluo 8 W', 'RAS', '668bbc320a582.png'),
(162, 'leti_nkolbisson', '3168106025413', '60254-1', 'Applique salle de Bain - Cuisine résistex', 'Luminaires', 'Résistex', 'Résistex', 1000, 2000, 1500, 0, 0, 0, 'U', 'Neofluo 8 W', 'RAS', '668bbc320a582.png'),
(163, 'bev_oyomabang', '4007529910021', '91002', 'Détecteur de mouvement LC-Click-N 200', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '230 v - Angle 200° - Max 12 m - 1000W', 'RAS', '668bbfa781cc4.png'),
(164, 'bev_Biyemassi', '4007529910021', '91002', 'Détecteur de mouvement LC-Click-N 200', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '230 v - Angle 200° - Max 12 m - 1000W', 'RAS', '668bbfa781cc4.png'),
(165, 'leti_nkolbisson', '4007529910021', '91002', 'Détecteur de mouvement LC-Click-N 200', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 1, 'U', '230 v - Angle 200° - Max 12 m - 1000W', 'RAS', '668bbfa781cc4.png'),
(166, 'bev_oyomabang', '4007529910083', '91008', 'Détecteur de Mouvement LC-plus 280', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'Angle 280° - 230v - Max 16m - 2000 W - 1000 VA', 'RAS', '668bc1dccdb4b.jpg'),
(167, 'bev_Biyemassi', '4007529910083', '91008', 'Détecteur de Mouvement LC-plus 280', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'Angle 280° - 230v - Max 16m - 2000 W - 1000 VA', 'RAS', '668bc1dccdb4b.jpg'),
(168, 'leti_nkolbisson', '4007529910083', '91008', 'Détecteur de Mouvement LC-plus 280', 'Détecteur de Mouvements', 'LUXOMAT', 'LUXOMAT', 1000, 2000, 1500, 0, 0, 0, 'U', 'Angle 280° - 230v - Max 16m - 2000 W - 1000 VA', 'RAS', '668bc1dccdb4b.jpg'),
(169, 'bev_oyomabang', '13606481465822', '146582', 'Prise 2P+T FR Affleurante Connect Auto', 'Prises', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 1, 'U', '16A - 250 V', 'RAS', '668bc5c649632.png'),
(170, 'bev_Biyemassi', '13606481465822', '146582', 'Prise 2P+T FR Affleurante Connect Auto', 'Prises', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 1, 'U', '16A - 250 V', 'RAS', '668bc5c649632.png'),
(171, 'leti_nkolbisson', '13606481465822', '146582', 'Prise 2P+T FR Affleurante Connect Auto', 'Prises', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 1, 'U', '16A - 250 V', 'RAS', '668bc5c649632.png'),
(172, 'bev_oyomabang', '23303430207256', '20725', 'Disjoncteur 10 A - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '10 A', 'RAS', '668bc82f2c3cb.png'),
(173, 'bev_Biyemassi', '23303430207256', '20725', 'Disjoncteur 10 A - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '10 A', 'RAS', '668bc82f2c3cb.png'),
(174, 'leti_nkolbisson', '23303430207256', '20725', 'Disjoncteur 10 A - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '10 A', 'RAS', '668bc82f2c3cb.png'),
(175, 'bev_oyomabang', '23303430207270', '20727', 'Disjoncteur - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '3000A - 230V', 'RAS', '668bc9c47e3a1.png'),
(176, 'bev_Biyemassi', '23303430207270', '20727', 'Disjoncteur - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '3000A - 230V', 'RAS', '668bc9c47e3a1.png'),
(177, 'leti_nkolbisson', '23303430207270', '20727', 'Disjoncteur - Circuit Breaker', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '3000A - 230V', 'RAS', '668bc9c47e3a1.png'),
(178, 'bev_oyomabang', '3606481381330', '22616', 'Disjoncteur modulaire 1P+N ', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '16A', 'RAS', '668bcf22f12a7.png'),
(179, 'bev_Biyemassi', '3606481381330', '22616', 'Disjoncteur modulaire 1P+N ', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '16A', 'RAS', '668bcf22f12a7.png'),
(180, 'leti_nkolbisson', '3606481381330', '22616', 'Disjoncteur modulaire 1P+N ', 'Disjoncteur', 'Schneider', 'Schneider', 1000, 2000, 1500, 0, 0, 0, 'U', '16A', 'RAS', '668bcf22f12a7.png'),
(181, 'bev_oyomabang', '113000', '113000', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle manuel - Uralight - Habitation incadescent', 'RAS', '668bd0c99c22f.png'),
(182, 'bev_Biyemassi', '113000', '113000', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle manuel - Uralight - Habitation incadescent', 'RAS', '668bd0c99c22f.png'),
(183, 'leti_nkolbisson', '113000', '113000', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle manuel - Uralight - Habitation incadescent', 'RAS', '668bd0c99c22f.png'),
(184, 'bev_oyomabang', '3613400163144', '110236', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle Manuel', 'RAS', '668bd259b39a2.png'),
(185, 'bev_Biyemassi', '3613400163144', '110236', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle Manuel', 'RAS', '668bd259b39a2.png'),
(186, 'leti_nkolbisson', '3613400163144', '110236', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'Contrôle Manuel', 'RAS', '668bd259b39a2.png'),
(190, 'bev_oyomabang', '3613400166831', '111013', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 50/60 Hz 0,8W', 'RAS', '668bd688112f9.png'),
(191, 'bev_Biyemassi', '3613400166831', '111013', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 50/60 Hz 0,8W', 'RAS', '668bd688112f9.png'),
(192, 'leti_nkolbisson', '3613400166831', '111013', 'Bloc Secours', 'Bloc Secours', 'URA', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', '230V - 50/60 Hz 0,8W', 'RAS', '668bd688112f9.png'),
(193, 'bev_oyomabang', '1950305', '1950305', 'Grille de Protection', 'Grille de protection', 'NO name', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP 9', 'RAS', '668bd8c39b678.png'),
(194, 'bev_Biyemassi', '1950305', '1950305', 'Grille de Protection', 'Grille de protection', 'NO name', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP 9', 'RAS', '668bd8c39b678.png'),
(195, 'leti_nkolbisson', '1950305', '1950305', 'Grille de Protection', 'Grille de protection', 'NO name', 'URA', 1000, 2000, 1500, 0, 0, 0, 'U', 'IP 9', 'RAS', '668bd8c39b678.png'),
(196, 'bev_oyomabang', '26014531', '1453', 'Jeu de Tournevis 8', 'Jeu de Tournevis', 'Workzone', 'Workzone', 1000, 2000, 1500, 0, 0, 1, 'U', 'Pack de 8 tournevis', 'RAS', '668bdca74982d.png'),
(197, 'bev_Biyemassi', '26014531', '1453', 'Jeu de Tournevis 8', 'Jeu de Tournevis', 'Workzone', 'Workzone', 1000, 2000, 1500, 0, 0, 1, 'U', 'Pack de 8 tournevis', 'RAS', '668bdca74982d.png'),
(198, 'leti_nkolbisson', '26014531', '1453', 'Jeu de Tournevis 8', 'Jeu de Tournevis', 'Workzone', 'Workzone', 1000, 2000, 1500, 0, 0, 1, 'U', 'Pack de 8 tournevis', 'RAS', '668bdca74982d.png'),
(199, 'bev_oyomabang', 'GI545ECOJF', 'HC20131015', 'Gilet Jaune', 'Gilet', 'T2S', 'T2S', 1000, 2000, 1500, 0, 0, 0, 'U', 'Gilet Série 543', 'RAS', '668bdf4b03aaf.png'),
(200, 'bev_Biyemassi', 'GI545ECOJF', 'HC20131015', 'Gilet Jaune', 'Gilet', 'T2S', 'T2S', 1000, 2000, 1500, 0, 0, 0, 'U', 'Gilet Série 543', 'RAS', '668bdf4b03aaf.png'),
(201, 'leti_nkolbisson', 'GI545ECOJF', 'HC20131015', 'Gilet Jaune', 'Gilet', 'T2S', 'T2S', 1000, 2000, 1500, 0, 0, 0, 'U', 'Gilet Série 543', 'RAS', '668bdf4b03aaf.png'),
(202, 'bev_oyomabang', 'hager_0001', 'hager_0001', 'Jeu de Tournevis 6 Hager', 'Jeu de Tournevis', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '6 Tournevis', 'RAS', '668be1caca031.png'),
(203, 'bev_Biyemassi', 'hager_0001', 'hager_0001', 'Jeu de Tournevis 6 Hager', 'Jeu de Tournevis', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '6 Tournevis', 'RAS', '668be1caca031.png'),
(204, 'leti_nkolbisson', 'hager_0001', 'hager_0001', 'Jeu de Tournevis 6 Hager', 'Jeu de Tournevis', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '6 Tournevis', 'RAS', '668be1caca031.png'),
(205, 'bev_oyomabang', '3439510540664', '054066', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 1, 'U', 'Dia 12 200/260', 'RAS', '668be9638fca9.png'),
(206, 'bev_Biyemassi', '3439510540664', '054066', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 1, 'U', 'Dia 12 200/260', 'RAS', '668be9638fca9.png'),
(207, 'leti_nkolbisson', '3439510540664', '054066', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 1, 'U', 'Dia 12 200/260', 'RAS', '668be9638fca9.png'),
(208, 'bev_oyomabang', '3439510540534', '054053', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 0, 'U', 'Mèche', 'RAS', '668bea09753a9.png'),
(209, 'bev_Biyemassi', '3439510540534', '054053', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 0, 'U', 'Mèche', 'RAS', '668bea09753a9.png'),
(210, 'leti_nkolbisson', '3439510540534', '054053', 'Mèche', 'Mèche', 'R3 Force Universal', 'R3 Force Universal', 1000, 2000, 1500, 0, 0, 0, 'U', 'Mèche', 'RAS', '668bea09753a9.png'),
(211, 'bev_oyomabang', '3250617102568', '710256', 'Prise TV-FM-SAT', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV-FM-SAT 1 Câble\r\n', 'RAS', '668bf2bd44508.png'),
(212, 'bev_Biyemassi', '3250617102568', '710256', 'Prise TV-FM-SAT', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV-FM-SAT 1 Câble\r\n', 'RAS', '668bf2bd44508.png'),
(213, 'leti_nkolbisson', '3250617102568', '710256', 'Prise TV-FM-SAT', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV-FM-SAT 1 Câble\r\n', 'RAS', '668bf2bd44508.png'),
(214, 'bev_oyomabang', '3250617100250', 'WE025', 'Poussoir_Porte-étiquette', 'Poussoir Porte-Etiquette', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A 2 -50V - Essensya', 'RAS', '668bf78507b18.png'),
(215, 'bev_Biyemassi', '3250617100250', 'WE025', 'Poussoir_Porte-étiquette', 'Poussoir Porte-Etiquette', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A 2 -50V - Essensya', 'RAS', '668bf78507b18.png'),
(216, 'leti_nkolbisson', '3250617100250', 'WE025', 'Poussoir_Porte-étiquette', 'Poussoir Porte-Etiquette', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A 2 -50V - Essensya', 'RAS', '668bf78507b18.png'),
(217, 'bev_oyomabang', '3250617101004', 'WE100', 'Prise 2P+T Hager', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668bfa2a6d3fb.png'),
(218, 'bev_Biyemassi', '3250617101004', 'WE100', 'Prise 2P+T Hager', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668bfa2a6d3fb.png'),
(219, 'leti_nkolbisson', '3250617101004', 'WE100', 'Prise 2P+T Hager', 'Prises', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668bfa2a6d3fb.png'),
(220, 'bev_oyomabang', '3250617100014', 'WE001', 'Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 1, 'U', '10A - 250V', 'RAS', '668bfcb250393.png'),
(221, 'bev_Biyemassi', '3250617100014', 'WE001', 'Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 1, 'U', '10A - 250V', 'RAS', '668bfcb250393.png'),
(222, 'leti_nkolbisson', '3250617100014', 'WE001', 'Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 1, 'U', '10A - 250V', 'RAS', '668bfcb250393.png'),
(223, 'bev_oyomabang', '3250617100403', 'WE040', 'Double Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A - 250V', 'RAS', '668bfe484a20a.png'),
(224, 'bev_Biyemassi', '3250617100403', 'WE040', 'Double Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A - 250V', 'RAS', '668bfe484a20a.png'),
(225, 'leti_nkolbisson', '3250617100403', 'WE040', 'Double Interrupteur Va et vient', 'Interrupteur', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', '10A - 250V', 'RAS', '668bfe484a20a.png'),
(226, 'bev_oyomabang', '3663752049917', '60840', 'Prise à Saillie Etanche PC 2P+T', 'Prises', 'EUROHM', 'EUROHM', 1000, 2000, 1500, 0, 0, 1, 'U', 'PC 2 P+T', 'RAS', '668c00d143393.png'),
(227, 'bev_Biyemassi', '3663752049917', '60840', 'Prise à Saillie Etanche PC 2P+T', 'Prises', 'EUROHM', 'EUROHM', 1000, 2000, 1500, 0, 0, 1, 'U', 'PC 2 P+T', 'RAS', '668c00d143393.png'),
(228, 'leti_nkolbisson', '3663752049917', '60840', 'Prise à Saillie Etanche PC 2P+T', 'Prises', 'EUROHM', 'EUROHM', 1000, 2000, 1500, 0, 0, 1, 'U', 'PC 2 P+T', 'RAS', '668c00d143393.png'),
(229, 'bev_oyomabang', '3250617104036', 'WE403', 'Plaque 3 Postes Horizontale - Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 3 Postes Horizontale - Verticale', 'RAs', '668c03d44f771.png'),
(230, 'bev_Biyemassi', '3250617104036', 'WE403', 'Plaque 3 Postes Horizontale - Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 3 Postes Horizontale - Verticale', 'RAs', '668c03d44f771.png'),
(231, 'leti_nkolbisson', '3250617104036', 'WE403', 'Plaque 3 Postes Horizontale - Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 3 Postes Horizontale - Verticale', 'RAs', '668c03d44f771.png'),
(232, 'bev_oyomabang', '3250617104029', 'WE402', 'Plaque 2 postes Horizontale + Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 2 postes Horizontale + Verticale', 'RAS', '668c05a0443e6.jpg'),
(233, 'bev_Biyemassi', '3250617104029', 'WE402', 'Plaque 2 postes Horizontale + Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 2 postes Horizontale + Verticale', 'RAS', '668c05a0443e6.jpg'),
(234, 'leti_nkolbisson', '3250617104029', 'WE402', 'Plaque 2 postes Horizontale + Verticale', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 2 postes Horizontale + Verticale', 'RAS', '668c05a0443e6.jpg'),
(235, 'bev_oyomabang', '3250617104012', 'WE401', 'Plaque Plate', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Plate', 'RAS', '668c0a87d849c.jpg'),
(236, 'bev_Biyemassi', '3250617104012', 'WE401', 'Plaque Plate', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Plate', 'RAS', '668c0a87d849c.jpg'),
(237, 'leti_nkolbisson', '3250617104012', 'WE401', 'Plaque Plate', 'Plaque', 'Hager', 'Hager', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Plate', 'RAS', '668c0a87d849c.jpg'),
(238, 'bev_oyomabang', '3245064012126', '401212', 'Coffret Saillie Electrique 2R-13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret Electrique 2 rangées', 'RAS', '668c0f80a430e.jpg'),
(239, 'bev_Biyemassi', '3245064012126', '401212', 'Coffret Saillie Electrique 2R-13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret Electrique 2 rangées', 'RAS', '668c0f80a430e.jpg'),
(240, 'leti_nkolbisson', '3245064012126', '401212', 'Coffret Saillie Electrique 2R-13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret Electrique 2 rangées', 'RAS', '668c0f80a430e.jpg'),
(241, 'bev_oyomabang', '3245064013321', '401332', 'Porte Opaque Coffret', 'Porte Coffret', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Porte Opaque Coffret', 'RAS', '668c12e0c316c.jpg'),
(242, 'bev_Biyemassi', '3245064013321', '401332', 'Porte Opaque Coffret', 'Porte Coffret', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Porte Opaque Coffret', 'RAS', '668c12e0c316c.jpg'),
(243, 'leti_nkolbisson', '3245064013321', '401332', 'Porte Opaque Coffret', 'Porte Coffret', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Porte Opaque Coffret', 'RAS', '668c12e0c316c.jpg'),
(244, 'bev_oyomabang', '3245064012133', '401213', 'Coffret Saillie DRIVIA 3R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret 3Rangées - 13M', 'RAS', '668c15cc3b7eb.jpg'),
(245, 'bev_Biyemassi', '3245064012133', '401213', 'Coffret Saillie DRIVIA 3R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret 3Rangées - 13M', 'RAS', '668c15cc3b7eb.jpg'),
(246, 'leti_nkolbisson', '3245064012133', '401213', 'Coffret Saillie DRIVIA 3R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 1, 'U', 'Coffret 3Rangées - 13M', 'RAS', '668c15cc3b7eb.jpg'),
(247, 'bev_oyomabang', '3245064012140', '401214', 'Coffret saillie 4R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Coffret saillie 4Rangées - 13M', 'RAS', '668c18ca8cb43.jpg'),
(248, 'bev_Biyemassi', '3245064012140', '401214', 'Coffret saillie 4R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Coffret saillie 4Rangées - 13M', 'RAS', '668c18ca8cb43.jpg'),
(249, 'leti_nkolbisson', '3245064012140', '401214', 'Coffret saillie 4R - 13M', 'Coffret Electrique', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Coffret saillie 4Rangées - 13M', 'RAS', '668c18ca8cb43.jpg'),
(253, 'bev_oyomabang', '3245066647111', '664711', 'Interrupteur volets roulants - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '6AX 250V', 'RAS', '668fa458e56af.jpg'),
(254, 'bev_Biyemassi', '3245066647111', '664711', 'Interrupteur volets roulants - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '6AX 250V', 'RAS', '668fa458e56af.jpg'),
(255, 'leti_nkolbisson', '3245066647111', '664711', 'Interrupteur volets roulants - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '6AX 250V', 'RAS', '668fa458e56af.jpg'),
(256, 'bev_oyomabang', '3245066647098', '664709', 'Interrupteur Va-et-vient + poussoir - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V  - 6A 250V-', 'RAS', '668fa613a78de.jpg'),
(257, 'bev_Biyemassi', '3245066647098', '664709', 'Interrupteur Va-et-vient + poussoir - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V  - 6A 250V-', 'RAS', '668fa613a78de.jpg'),
(258, 'leti_nkolbisson', '3245066647098', '664709', 'Interrupteur Va-et-vient + poussoir - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V  - 6A 250V-', 'RAS', '668fa613a78de.jpg'),
(259, 'bev_oyomabang', '3414970011114', '664735', 'Prise 2P+T avec éclips - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668faa5425ca5.jpg'),
(260, 'bev_Biyemassi', '3414970011114', '664735', 'Prise 2P+T avec éclips - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668faa5425ca5.jpg'),
(261, 'leti_nkolbisson', '3414970011114', '664735', 'Prise 2P+T avec éclips - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A 250V', 'RAS', '668faa5425ca5.jpg'),
(262, 'bev_oyomabang', '3245066647029', '664702', 'Interrupteur Double va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fac0fe3253.jpg'),
(263, 'bev_Biyemassi', '3245066647029', '664702', 'Interrupteur Double va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fac0fe3253.jpg'),
(264, 'leti_nkolbisson', '3245066647029', '664702', 'Interrupteur Double va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fac0fe3253.jpg'),
(265, 'bev_oyomabang', '3414970011060', '664701', 'Interrupteur Va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fad950dfdf.jpg'),
(266, 'bev_Biyemassi', '3414970011060', '664701', 'Interrupteur Va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fad950dfdf.jpg'),
(267, 'leti_nkolbisson', '3414970011060', '664701', 'Interrupteur Va-et-vient - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fad950dfdf.jpg'),
(268, 'bev_oyomabang', '3245066647517', '664751', 'Prise TV étoile blindée - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient - Pur', 'RAS', '668faeef33b99.jpg'),
(269, 'bev_Biyemassi', '3245066647517', '664751', 'Prise TV étoile blindée - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient - Pur', 'RAS', '668faeef33b99.jpg'),
(270, 'leti_nkolbisson', '3245066647517', '664751', 'Prise TV étoile blindée - Pur', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient - Pur', 'RAS', '668faeef33b99.jpg'),
(271, 'bev_oyomabang', '3414970011084', '664710', 'Interrupteur va-et-vient à voyant - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fb0991152c.jpg'),
(272, 'bev_Biyemassi', '3414970011084', '664710', 'Interrupteur va-et-vient à voyant - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fb0991152c.jpg'),
(273, 'leti_nkolbisson', '3414970011084', '664710', 'Interrupteur va-et-vient à voyant - Pur', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX 250V', 'RAS', '668fb0991152c.jpg'),
(274, 'bev_oyomabang', '3414971006201', '600343', 'Chargeur USB Universel Type A', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 100-240V - 50/60Hz-0.5A\r\nOutput : 5.0V  3.0A 15.0W', 'RAS', '668fb302c2906.jpg'),
(275, 'bev_Biyemassi', '3414971006201', '600343', 'Chargeur USB Universel Type A', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 100-240V - 50/60Hz-0.5A\r\nOutput : 5.0V  3.0A 15.0W', 'RAS', '668fb302c2906.jpg'),
(276, 'leti_nkolbisson', '3414971006201', '600343', 'Chargeur USB Universel Type A', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 100-240V - 50/60Hz-0.5A\r\nOutput : 5.0V  3.0A 15.0W', 'RAS', '668fb302c2906.jpg'),
(277, 'bev_oyomabang', '3414970534668', '068111', 'Enjoliveur Prise 2P+T Surface - Blance', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise 2P+T Surface - Blance', 'RAS', '668fb4e397f9d.jpg'),
(278, 'bev_Biyemassi', '3414970534668', '068111', 'Enjoliveur Prise 2P+T Surface - Blance', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise 2P+T Surface - Blance', 'RAS', '668fb4e397f9d.jpg'),
(279, 'leti_nkolbisson', '3414970534668', '068111', 'Enjoliveur Prise 2P+T Surface - Blance', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise 2P+T Surface - Blance', 'RAS', '668fb4e397f9d.jpg'),
(280, 'bev_oyomabang', '011324506068001230000010', '68001', 'Interrupteur - Commande Simple 1 ', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur - Commande Simple 1 ', 'RAS', '668fb68a47116.jpg'),
(281, 'bev_Biyemassi', '011324506068001230000010', '68001', 'Interrupteur - Commande Simple 1 ', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur - Commande Simple 1 ', 'RAS', '668fb68a47116.jpg'),
(282, 'leti_nkolbisson', '011324506068001230000010', '68001', 'Interrupteur - Commande Simple 1 ', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur - Commande Simple 1 ', 'RAS', '668fb68a47116.jpg'),
(283, 'bev_oyomabang', '3414971390201', '068556', 'Enjoliveur Prise Double USB - Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise Double USB - Titane', 'RAS', '668fb88541fa0.jpg'),
(284, 'bev_Biyemassi', '3414971390201', '068556', 'Enjoliveur Prise Double USB - Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise Double USB - Titane', 'RAS', '668fb88541fa0.jpg'),
(285, 'leti_nkolbisson', '3414971390201', '068556', 'Enjoliveur Prise Double USB - Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur Prise Double USB - Titane', 'RAS', '668fb88541fa0.jpg'),
(286, 'bev_oyomabang', '3414970487179', '067106', 'Chargeur Usb + 2P+T FB précâblé', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 220-240V 50-60Hz - \r\nOutput: 5V- 3A', 'RAS', '668fba9f212d2.jpg'),
(287, 'bev_Biyemassi', '3414970487179', '067106', 'Chargeur Usb + 2P+T FB précâblé', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 220-240V 50-60Hz - \r\nOutput: 5V- 3A', 'RAS', '668fba9f212d2.jpg'),
(288, 'leti_nkolbisson', '3414970487179', '067106', 'Chargeur Usb + 2P+T FB précâblé', 'Chargeur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Input : 220-240V 50-60Hz - \r\nOutput: 5V- 3A', 'RAS', '668fba9f212d2.jpg'),
(289, 'bev_oyomabang', '3414970338679', '067111', 'Prise 2P+T', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T', 'RAS', '668fbbf71c685.jpg'),
(290, 'bev_Biyemassi', '3414970338679', '067111', 'Prise 2P+T', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T', 'RAS', '668fbbf71c685.jpg'),
(291, 'leti_nkolbisson', '3414970338679', '067111', 'Prise 2P+T', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T', 'RAS', '668fbbf71c685.jpg'),
(292, 'bev_oyomabang', '3414970338648', '067001', 'Va-et-vient', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX-250V', 'RAS', '668fbdb52102f.jpg'),
(293, 'bev_Biyemassi', '3414970338648', '067001', 'Va-et-vient', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX-250V', 'RAS', '668fbdb52102f.jpg'),
(294, 'leti_nkolbisson', '3414970338648', '067001', 'Va-et-vient', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10AX-250V', 'RAS', '668fbdb52102f.jpg'),
(295, 'bev_oyomabang', '3414970534682', '068411', 'Enjoliveur 2P+T Surface Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur 2P+T Surface Titane', 'RAS', '668fbf69cdd13.jpg'),
(296, 'bev_Biyemassi', '3414970534682', '068411', 'Enjoliveur 2P+T Surface Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur 2P+T Surface Titane', 'RAS', '668fbf69cdd13.jpg'),
(297, 'leti_nkolbisson', '3414970534682', '068411', 'Enjoliveur 2P+T Surface Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur 2P+T Surface Titane', 'RAS', '668fbf69cdd13.jpg'),
(298, 'bev_oyomabang', '3414971376007', '068551', 'Enjoliveur RJ45-Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur RJ45-Titane', 'RAS', '668fc13fcce2d.jpg'),
(299, 'bev_Biyemassi', '3414971376007', '068551', 'Enjoliveur RJ45-Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur RJ45-Titane', 'RAS', '668fc13fcce2d.jpg'),
(300, 'leti_nkolbisson', '3414971376007', '068551', 'Enjoliveur RJ45-Titane', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Enjoliveur RJ45-Titane', 'RAS', '668fc13fcce2d.jpg'),
(301, 'bev_oyomabang', '3414971377257', '068901', 'Plaque', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque', 'RAS', '668fc28ba6b46.jpg'),
(302, 'bev_Biyemassi', '3414971377257', '068901', 'Plaque', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque', 'RAS', '668fc28ba6b46.jpg'),
(303, 'leti_nkolbisson', '3414971377257', '068901', 'Plaque', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque', 'RAS', '668fc28ba6b46.jpg'),
(304, 'bev_oyomabang', '3414971388246', '068902', 'Plaque Double', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Double', 'RAS', '668fc3d2400ef.jpg'),
(305, 'bev_Biyemassi', '3414971388246', '068902', 'Plaque Double', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Double', 'RAS', '668fc3d2400ef.jpg');
INSERT INTO `tbl_shop_item` (`product_id`, `shop_code`, `product_code`, `product_sku`, `product_name`, `product_category`, `product_brand`, `supplier`, `purchase_price`, `sell_price`, `min_price`, `discount`, `stock`, `min_stock`, `product_satuan`, `description`, `place_in_store`, `img`) VALUES
(306, 'leti_nkolbisson', '3414971388246', '068902', 'Plaque Double', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Double', 'RAS', '668fc3d2400ef.jpg'),
(307, 'bev_oyomabang', '3414971390997', '068903', 'Plaque Legrand Triple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Legrand Triple', 'RAS', '668fc4dcba6fb.jpg'),
(308, 'bev_Biyemassi', '3414971390997', '068903', 'Plaque Legrand Triple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Legrand Triple', 'RAS', '668fc4dcba6fb.jpg'),
(309, 'leti_nkolbisson', '3414971390997', '068903', 'Plaque Legrand Triple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Legrand Triple', 'RAS', '668fc4dcba6fb.jpg'),
(310, 'bev_oyomabang', '3414971483132', '080253', 'Support 3 Postes', 'Support', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 3 Postes', 'RAS', '668fc65cf1ad2.jpg'),
(311, 'bev_Biyemassi', '3414971483132', '080253', 'Support 3 Postes', 'Support', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 3 Postes', 'RAS', '668fc65cf1ad2.jpg'),
(312, 'leti_nkolbisson', '3414971483132', '080253', 'Support 3 Postes', 'Support', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 3 Postes', 'RAS', '668fc65cf1ad2.jpg'),
(313, 'bev_oyomabang', '3414970648426', '080251', 'Support 1 Poste', 'Support', 'Legrans', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 1 Poste', 'RAS', '668fc7ae634a8.jpg'),
(314, 'bev_Biyemassi', '3414970648426', '080251', 'Support 1 Poste', 'Support', 'Legrans', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 1 Poste', 'RAS', '668fc7ae634a8.jpg'),
(315, 'leti_nkolbisson', '3414970648426', '080251', 'Support 1 Poste', 'Support', 'Legrans', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Support 1 Poste', 'RAS', '668fc7ae634a8.jpg'),
(316, 'bev_oyomabang', '3414971575974', '077111L', 'Prise 2 P+T Legrand', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250V', 'RAS', '668fc957e2665.jpg'),
(317, 'bev_Biyemassi', '3414971575974', '077111L', 'Prise 2 P+T Legrand', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250V', 'RAS', '668fc957e2665.jpg'),
(318, 'leti_nkolbisson', '3414971575974', '077111L', 'Prise 2 P+T Legrand', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250V', 'RAS', '668fc957e2665.jpg'),
(319, 'bev_oyomabang', '3414971575110', '077001L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '1M 10AX 250V', 'RAS', '668fcadf3cbb2.jpg'),
(320, 'bev_Biyemassi', '3414971575110', '077001L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '1M 10AX 250V', 'RAS', '668fcadf3cbb2.jpg'),
(321, 'leti_nkolbisson', '3414971575110', '077001L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '1M 10AX 250V', 'RAS', '668fcadf3cbb2.jpg'),
(322, 'bev_oyomabang', '3414971575172', '077011L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M 10AX - 250v', 'RAS', '668fcd06ab329.jpg'),
(323, 'bev_Biyemassi', '3414971575172', '077011L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M 10AX - 250v', 'RAS', '668fcd06ab329.jpg'),
(324, 'leti_nkolbisson', '3414971575172', '077011L', 'Interrupteur Va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M 10AX - 250v', 'RAS', '668fcd06ab329.jpg'),
(325, 'bev_oyomabang', '3414971531758', '076565', 'Prise RJ45', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M FTP', 'RAS', '668fce225821f.jpg'),
(326, 'bev_Biyemassi', '3414971531758', '076565', 'Prise RJ45', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M FTP', 'RAS', '668fce225821f.jpg'),
(327, 'leti_nkolbisson', '3414971531758', '076565', 'Prise RJ45', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '2M FTP', 'RAS', '668fce225821f.jpg'),
(328, 'bev_oyomabang', '3414971019577', '600802', 'Plaque Dooxie Blanc', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Dooxie Blanc', 'RAS', '668fcff12d930.jpg'),
(329, 'bev_Biyemassi', '3414971019577', '600802', 'Plaque Dooxie Blanc', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Dooxie Blanc', 'RAS', '668fcff12d930.jpg'),
(330, 'leti_nkolbisson', '3414971019577', '600802', 'Plaque Dooxie Blanc', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Dooxie Blanc', 'RAS', '668fcff12d930.jpg'),
(331, 'bev_oyomabang', '3414971130975', '600801', 'Plaque 1 Poste Legrand Dooxie', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Blanc 23 W 28', 'RAS', '668fd1a353347.jpg'),
(332, 'bev_Biyemassi', '3414971130975', '600801', 'Plaque 1 Poste Legrand Dooxie', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Blanc 23 W 28', 'RAS', '668fd1a353347.jpg'),
(333, 'leti_nkolbisson', '3414971130975', '600801', 'Plaque 1 Poste Legrand Dooxie', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Blanc 23 W 28', 'RAS', '668fd1a353347.jpg'),
(334, 'bev_oyomabang', '3414971006119', '600335', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250 V', 'RAS', '668fd30da5929.jpg'),
(335, 'bev_Biyemassi', '3414971006119', '600335', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250 V', 'RAS', '668fd30da5929.jpg'),
(336, 'leti_nkolbisson', '3414971006119', '600335', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '16A - 250 V', 'RAS', '668fd30da5929.jpg'),
(337, 'bev_oyomabang', '3414971203044', '600011', 'Interrupteur ou Va-et-vient Lumineux Voyant fourni', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd44666494.jpg'),
(338, 'bev_Biyemassi', '3414971203044', '600011', 'Interrupteur ou Va-et-vient Lumineux Voyant fourni', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd44666494.jpg'),
(339, 'leti_nkolbisson', '3414971203044', '600011', 'Interrupteur ou Va-et-vient Lumineux Voyant fourni', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd44666494.jpg'),
(340, 'bev_oyomabang', '3414971004290', '600002', 'Intrerrupteur - Double va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd56e29f06.jpg'),
(341, 'bev_Biyemassi', '3414971004290', '600002', 'Intrerrupteur - Double va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd56e29f06.jpg'),
(342, 'leti_nkolbisson', '3414971004290', '600002', 'Intrerrupteur - Double va-et-vient', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250V', 'RAS', '668fd56e29f06.jpg'),
(343, 'bev_oyomabang', '3414971006966', '600635', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T Surface avec Bornes Auto', 'RAS', '668fd8265eef1.jpg'),
(344, 'bev_Biyemassi', '3414971006966', '600635', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T Surface avec Bornes Auto', 'RAS', '668fd8265eef1.jpg'),
(345, 'leti_nkolbisson', '3414971006966', '600635', 'Prise 2P+T Surface avec Bornes Auto', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T Surface avec Bornes Auto', 'RAS', '668fd8265eef1.jpg'),
(346, 'bev_oyomabang', '3414971004269', '600001', 'Interrupteur ou va-et-vient Legrand', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250 V', 'RAS', '668fe1f903da6.jpg'),
(347, 'bev_Biyemassi', '3414971004269', '600001', 'Interrupteur ou va-et-vient Legrand', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250 V', 'RAS', '668fe1f903da6.jpg'),
(348, 'leti_nkolbisson', '3414971004269', '600001', 'Interrupteur ou va-et-vient Legrand', 'Interrupteur', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', '10 AX - 250 V', 'RAS', '668fe1f903da6.jpg'),
(349, 'bev_oyomabang', '3414971203617', '600376', 'Prise RJ45 CAT 6 FTP', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise RJ45 CAT 6 FTP', 'RAS', '668fe52a9269b.jpg'),
(350, 'bev_Biyemassi', '3414971203617', '600376', 'Prise RJ45 CAT 6 FTP', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise RJ45 CAT 6 FTP', 'RAS', '668fe52a9269b.jpg'),
(351, 'leti_nkolbisson', '3414971203617', '600376', 'Prise RJ45 CAT 6 FTP', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise RJ45 CAT 6 FTP', 'RAS', '668fe52a9269b.jpg'),
(352, 'bev_oyomabang', '3414971093263', '600351', 'Prise TV Male  Etoile Blindée', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV Male  Etoile Blindée Blanc', 'RAS', '668feac449350.jpg'),
(353, 'bev_Biyemassi', '3414971093263', '600351', 'Prise TV Male  Etoile Blindée', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV Male  Etoile Blindée Blanc', 'RAS', '668feac449350.jpg'),
(354, 'leti_nkolbisson', '3414971093263', '600351', 'Prise TV Male  Etoile Blindée', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV Male  Etoile Blindée Blanc', 'RAS', '668feac449350.jpg'),
(355, 'bev_oyomabang', '3414971203488', '600353', 'Prise TV - R  - SAT Etoile Blindé', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV - R  - SAT Etoile Blindé Blanc', 'RAS', '668fecb0b6b8c.jpg'),
(356, 'bev_Biyemassi', '3414971203488', '600353', 'Prise TV - R  - SAT Etoile Blindé', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV - R  - SAT Etoile Blindé Blanc', 'RAS', '668fecb0b6b8c.jpg'),
(357, 'leti_nkolbisson', '3414971203488', '600353', 'Prise TV - R  - SAT Etoile Blindé', 'Prises', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV - R  - SAT Etoile Blindé Blanc', 'RAS', '668fecb0b6b8c.jpg'),
(358, 'bev_oyomabang', '3414971284197', '600325', 'Sortie de Cable associable', 'Sortie de Cables', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Dia. 12mm maxi - 3x2,5 mm 250V', 'RAS', '668fee38685d4.jpg'),
(359, 'bev_Biyemassi', '3414971284197', '600325', 'Sortie de Cable associable', 'Sortie de Cables', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Dia. 12mm maxi - 3x2,5 mm 250V', 'RAS', '668fee38685d4.jpg'),
(360, 'leti_nkolbisson', '3414971284197', '600325', 'Sortie de Cable associable', 'Sortie de Cables', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Dia. 12mm maxi - 3x2,5 mm 250V', 'RAS', '668fee38685d4.jpg'),
(361, 'bev_oyomabang', '3414971384132', '665004', 'Plaque Quadruple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Quadruple Blanc', 'RAS', '668fefb0563a3.jpg'),
(362, 'bev_Biyemassi', '3414971384132', '665004', 'Plaque Quadruple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Quadruple Blanc', 'RAS', '668fefb0563a3.jpg'),
(363, 'leti_nkolbisson', '3414971384132', '665004', 'Plaque Quadruple', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque Quadruple Blanc', 'RAS', '668fefb0563a3.jpg'),
(364, 'bev_oyomabang', '3245066650098', '665009', 'Plaque 1 Poste Legrand', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 Poste Legrand', 'RAs', '668ff1079a36d.jpg'),
(365, 'bev_Biyemassi', '3245066650098', '665009', 'Plaque 1 Poste Legrand', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 Poste Legrand', 'RAs', '668ff1079a36d.jpg'),
(366, 'leti_nkolbisson', '3245066650098', '665009', 'Plaque 1 Poste Legrand', 'Plaque', 'Legrand', 'Legrand', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 Poste Legrand', 'RAs', '668ff1079a36d.jpg'),
(367, 'bev_oyomabang', '3233625002679', '64403', 'Plaque 3 postes Arnould', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 3 postes Arnould Blanc', 'RAs', '668ff2d6ed253.jpg'),
(368, 'bev_Biyemassi', '3233625002679', '64403', 'Plaque 3 postes Arnould', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 3 postes Arnould Blanc', 'RAs', '668ff2d6ed253.jpg'),
(369, 'leti_nkolbisson', '3233625002679', '64403', 'Plaque 3 postes Arnould', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 3 postes Arnould Blanc', 'RAs', '668ff2d6ed253.jpg'),
(370, 'bev_oyomabang', '3233625002310', '64402', 'Plaque 2 postes Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 2 postes Blanc', 'RAs', '668ff4b3dd360.jpg'),
(371, 'bev_Biyemassi', '3233625002310', '64402', 'Plaque 2 postes Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 2 postes Blanc', 'RAs', '668ff4b3dd360.jpg'),
(372, 'leti_nkolbisson', '3233625002310', '64402', 'Plaque 2 postes Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 1, 'U', 'Plaque 2 postes Blanc', 'RAs', '668ff4b3dd360.jpg'),
(373, 'bev_oyomabang', '3233625003034', '64401', 'Plaque 1 poste Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 poste Blanc', 'RAS', '668ff65be3fa2.jpg'),
(374, 'bev_Biyemassi', '3233625003034', '64401', 'Plaque 1 poste Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 poste Blanc', 'RAS', '668ff65be3fa2.jpg'),
(375, 'leti_nkolbisson', '3233625003034', '64401', 'Plaque 1 poste Blanc', 'Plaque', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Plaque 1 poste Blanc', 'RAS', '668ff65be3fa2.jpg'),
(376, 'bev_oyomabang', '3233625001283', '64031', 'Prise 2P+T BA Arnould 16A', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T BA Arnould 16A', 'RAS', '668ff791c6905.jpg'),
(377, 'bev_Biyemassi', '3233625001283', '64031', 'Prise 2P+T BA Arnould 16A', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T BA Arnould 16A', 'RAS', '668ff791c6905.jpg'),
(378, 'leti_nkolbisson', '3233625001283', '64031', 'Prise 2P+T BA Arnould 16A', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise 2P+T BA Arnould 16A', 'RAS', '668ff791c6905.jpg'),
(379, 'bev_oyomabang', '3233625001375', '64001', 'Interrupteur Va-et-vient Arnould', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient Arnould Blanc', 'RAS', '668ff983afa91.jpg'),
(380, 'bev_Biyemassi', '3233625001375', '64001', 'Interrupteur Va-et-vient Arnould', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient Arnould Blanc', 'RAS', '668ff983afa91.jpg'),
(381, 'leti_nkolbisson', '3233625001375', '64001', 'Interrupteur Va-et-vient Arnould', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-vient Arnould Blanc', 'RAS', '668ff983afa91.jpg'),
(382, 'bev_oyomabang', '3233625000538', '64002', 'Interrupteur Va-et-Vient 10AX double', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-Vient 10AX double', 'RAS', '668ffb5fe260b.jpg'),
(383, 'bev_Biyemassi', '3233625000538', '64002', 'Interrupteur Va-et-Vient 10AX double', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-Vient 10AX double', 'RAS', '668ffb5fe260b.jpg'),
(384, 'leti_nkolbisson', '3233625000538', '64002', 'Interrupteur Va-et-Vient 10AX double', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va-et-Vient 10AX double', 'RAS', '668ffb5fe260b.jpg'),
(385, 'bev_oyomabang', '3233625000354', '64077', 'Prise TV R SAT étoile Blanc', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV R SAT étoile Blanc', 'RAS', '668ffc7d127ba.jpg'),
(386, 'bev_Biyemassi', '3233625000354', '64077', 'Prise TV R SAT étoile Blanc', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV R SAT étoile Blanc', 'RAS', '668ffc7d127ba.jpg'),
(387, 'leti_nkolbisson', '3233625000354', '64077', 'Prise TV R SAT étoile Blanc', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV R SAT étoile Blanc', 'RAS', '668ffc7d127ba.jpg'),
(388, 'bev_oyomabang', '3233620606445', '60644', 'Sortie de Câble Blanc Lumière', 'Sortie de Cable', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Sortie de Câble Blanc Lumière', 'RAS', '668ffe28015b9.jpg'),
(389, 'bev_Biyemassi', '3233620606445', '60644', 'Sortie de Câble Blanc Lumière', 'Sortie de Cable', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Sortie de Câble Blanc Lumière', 'RAS', '668ffe28015b9.jpg'),
(390, 'leti_nkolbisson', '3233620606445', '60644', 'Sortie de Câble Blanc Lumière', 'Sortie de Cable', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Sortie de Câble Blanc Lumière', 'RAS', '668ffe28015b9.jpg'),
(391, 'bev_oyomabang', '3233620601105', '60110', 'Bouton Poussoir', 'Bouton Poussoir', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Bouton Poussoir', 'RAS', '668fff8d29f2c.jpg'),
(392, 'bev_Biyemassi', '3233620601105', '60110', 'Bouton Poussoir', 'Bouton Poussoir', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Bouton Poussoir', 'RAS', '668fff8d29f2c.jpg'),
(393, 'leti_nkolbisson', '3233620601105', '60110', 'Bouton Poussoir', 'Bouton Poussoir', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Bouton Poussoir', 'RAS', '668fff8d29f2c.jpg'),
(394, 'bev_oyomabang', '3233620606995', '60699', 'Obturateur Lumière', 'Obturateur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Obturateur Lumière Blanc', 'RAS', '6690010c6f00a.jpg'),
(395, 'bev_Biyemassi', '3233620606995', '60699', 'Obturateur Lumière', 'Obturateur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Obturateur Lumière Blanc', 'RAS', '6690010c6f00a.jpg'),
(396, 'leti_nkolbisson', '3233620606995', '60699', 'Obturateur Lumière', 'Obturateur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Obturateur Lumière Blanc', 'RAS', '6690010c6f00a.jpg'),
(397, 'bev_oyomabang', '3233620605134', '60513', 'Interrupteur Va et vient Témoin sans Neutre', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va et vient Témoin sans Neutre 4AX 250V - Lumière', 'RAS', '66900236334f6.jpg'),
(398, 'bev_Biyemassi', '3233620605134', '60513', 'Interrupteur Va et vient Témoin sans Neutre', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va et vient Témoin sans Neutre 4AX 250V - Lumière', 'RAS', '66900236334f6.jpg'),
(399, 'leti_nkolbisson', '3233620605134', '60513', 'Interrupteur Va et vient Témoin sans Neutre', 'Interrupteur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Interrupteur Va et vient Témoin sans Neutre 4AX 250V - Lumière', 'RAS', '66900236334f6.jpg'),
(400, 'bev_oyomabang', '3233620606735', '60673', 'Prise TV FM Espace - Lumière', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV FM Espace - Lumière', 'RAS', '669003b5f1063.jpg'),
(401, 'bev_Biyemassi', '3233620606735', '60673', 'Prise TV FM Espace - Lumière', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV FM Espace - Lumière', 'RAS', '669003b5f1063.jpg'),
(402, 'leti_nkolbisson', '3233620606735', '60673', 'Prise TV FM Espace - Lumière', 'Prises', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Prise TV FM Espace - Lumière', 'RAS', '669003b5f1063.jpg'),
(403, 'bev_oyomabang', '3233620601204', '60120', 'Espace - Inverseur Fixe Volet roulant 10A', 'Inverseur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Espace - Inverseur Fixe Volet roulant 10A 250V Lumière', 'RAS', '66900788ab2af.jpg'),
(404, 'bev_Biyemassi', '3233620601204', '60120', 'Espace - Inverseur Fixe Volet roulant 10A', 'Inverseur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Espace - Inverseur Fixe Volet roulant 10A 250V Lumière', 'RAS', '66900788ab2af.jpg'),
(405, 'leti_nkolbisson', '3233620601204', '60120', 'Espace - Inverseur Fixe Volet roulant 10A', 'Inverseur', 'Arnould', 'Arnould', 1000, 2000, 1500, 0, 0, 0, 'U', 'Espace - Inverseur Fixe Volet roulant 10A 250V Lumière', 'RAS', '66900788ab2af.jpg');

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
-- Index pour la table `tbl_commandes_magasin`
--
ALTER TABLE `tbl_commandes_magasin`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Index pour la table `tbl_commandes_magasin_details`
--
ALTER TABLE `tbl_commandes_magasin_details`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `suplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `tbl_commandes_magasin`
--
ALTER TABLE `tbl_commandes_magasin`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_commandes_magasin_details`
--
ALTER TABLE `tbl_commandes_magasin_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT pour la table `tbl_invoice_detail_client`
--
ALTER TABLE `tbl_invoice_detail_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406;

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
