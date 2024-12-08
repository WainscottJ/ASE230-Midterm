-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: DEC 7, 2024 at 7:57 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Assignment 11 / Final `
--

-- --------------------------------------------------------

--
-- Table structure for table `OrderItem Table`
--

CREATE TABLE `OrderItem Table` (
  `Order_Item_id` int(11) NOT NULL,
  `Order_id` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Order Table`
--

CREATE TABLE `Order Table` (
  `Order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ordered_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Product Tabe`
--

CREATE TABLE `Product Tabe` (
  `Product_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review table`
--

CREATE TABLE `review table` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `review_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users table`
--

CREATE TABLE `users table` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `OrderItem Table`
--
ALTER TABLE `OrderItem Table`
  ADD PRIMARY KEY (`Order_Item_id`),
  ADD KEY `Foreign Key` (`Order_id`),
  ADD KEY `Product_ID` (`Product_id`);

--
-- Indexes for table `Order Table`
--
ALTER TABLE `Order Table`
  ADD PRIMARY KEY (`Order_id`),
  ADD KEY `User` (`user_id`);

--
-- Indexes for table `Product Tabe`
--
ALTER TABLE `Product Tabe`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `review table`
--
ALTER TABLE `review table`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users table`
--
ALTER TABLE `users table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `OrderItem Table`
--
ALTER TABLE `OrderItem Table`
  ADD CONSTRAINT `Foreign Key` FOREIGN KEY (`Order_id`) REFERENCES `Order Table` (`Order_id`),
  ADD CONSTRAINT `Product_ID` FOREIGN KEY (`Product_id`) REFERENCES `Product Tabe` (`Product_id`);

--
-- Constraints for table `Order Table`
--
ALTER TABLE `Order Table`
  ADD CONSTRAINT `User` FOREIGN KEY (`user_id`) REFERENCES `users table` (`user_id`);

--
-- Constraints for table `review table`
--
ALTER TABLE `review table`
  ADD CONSTRAINT `product` FOREIGN KEY (`product_id`) REFERENCES `Product Tabe` (`Product_id`),
  ADD CONSTRAINT `review table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users table` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

