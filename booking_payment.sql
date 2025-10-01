-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 07:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking payment`
--

CREATE TABLE `booking payment` (
  `Name` varchar(50) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Address` varchar(90) NOT NULL,
  `Service` varchar(70) NOT NULL,
  `Price` varchar(30) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking payment`
--

INSERT INTO `booking payment` (`Name`, `Email`, `Phone`, `Address`, `Service`, `Price`, `Date`) VALUES
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Regular Cleaning - Large Room', '2500 Shillings', '2024-07-24'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Mattress Cleaning - Double Mattress', '2500 Shillings', '2024-07-19'),
('brian', 'kaigaibrian219@gmail.com', '2147483647', '', 'Deep Cleaning - Standard Room', '5000 Shillings', '2024-07-19'),
('brian', 'kaigaibrian219@gmail.com', '2147483647', '', 'Move Out Cleaning', '9000 Shillings', '2024-07-19'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Curtain Cleaning - Standard Curtains', '1000 Shillings', '2024-07-25'),
('James', 'james@gmail.com', '791359189', '', 'Move Out Cleaning', '9000 Shillings', '2024-07-24'),
('James', 'james@gmail.com', '797084980', '', 'Curtain Cleaning - Standard Curtains', '1000 Shillings', '2024-07-31'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Mattress Cleaning - Double Mattress', '2500 Shillings', '2024-07-24'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Mattress Cleaning - King Size Mattress', '3500 Shillings', '2024-07-31'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Move In Cleaning', '7500 Shillings', '2024-07-31'),
('brian', 'kaigaibrian219@gmail.com', '7913591', '', 'Regular Cleaning - Large Room', '2500 Shillings', '2024-07-30'),
('brian', 'kaigaibrian219@gmail.com', '2147483647', '', 'Regular Cleaning - Large Room', '2500 Shillings', '2024-07-31'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Regular Cleaning - Whole House', '5000 Shillings', '2024-07-17'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Regular Cleaning - Whole House', '5000 Shillings', '2024-07-17'),
('brian', 'kaigaibrian219@gmail.com', '791359189', '', 'Regular Cleaning - Whole House', '5000 Shillings', '2024-07-10'),
('brian', 'kaigaibrian219@gmail.com', '2147483647', '', 'Carpet Cleaning - Whole House', '9000 Shillings', '2024-07-10'),
('brian', 'kaigaibrian219@gmail.com', '2147483647', '', 'Curtain Cleaning - Standard Curtains', '1000 Shillings', '2024-07-17'),
('KAIGAI', 'kaigaibrian219@gmail.com', '2147483647', '', 'Tile Cleaning - Standard Room', '2500 Shillings', '2024-07-15');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
