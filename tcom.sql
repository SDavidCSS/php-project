-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2023 at 08:23 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tcom`
--

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT 0,
  `offer_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `product_amount` int(11) NOT NULL,
  `valid_until` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `customer_name`, `comment`, `discount_percent`, `offer_price`, `discount_price`, `product_amount`, `valid_until`, `created_at`) VALUES
(35, 'John Wick', '', 10, '4020.00', '3618.00', 6, '2023-02-28', '2023-02-27 23:47:15'),
(37, 'David Sooky', 'Teszt', 15, '4800.00', '4080.00', 8, '2023-02-16', '2023-02-28 00:02:05');

-- --------------------------------------------------------

--
-- Table structure for table `offer_products`
--

CREATE TABLE `offer_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer_products`
--

INSERT INTO `offer_products` (`id`, `product_id`, `offer_id`, `amount`, `total_price`, `created_at`) VALUES
(128, 1, 35, 2, '2160.00', '2023-02-27 23:47:16'),
(137, 5, 35, 2, '810.00', '2023-02-27 23:58:22'),
(139, 2, 35, 2, '648.00', '2023-02-27 23:58:43'),
(140, 1, 37, 2, '2040.00', '2023-02-28 00:02:05'),
(142, 3, 37, 1, '510.00', '2023-02-28 00:02:06'),
(143, 2, 37, 5, '1530.00', '2023-02-28 00:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `price`, `created_at`) VALUES
(1, 'Iphone', '/images/iphone.png', '1200.00', '2023-02-25 10:45:34'),
(2, 'Motorola', '/images/motorola.png', '360.00', '2023-02-25 10:45:34'),
(3, 'Playstation 5', '/images/ps5.png', '600.00', '2023-02-25 10:45:34'),
(4, 'Samsung', '/images/samsung.png', '720.00', '2023-02-25 10:45:34'),
(5, 'LG TV', '/images/tv.png', '450.00', '2023-02-25 10:45:34'),
(6, 'Samsung TV', '/images/tv.png', '620.00', '2023-02-25 10:45:34'),
(7, 'Sony TV', '/images/tv.png', '850.00', '2023-02-25 10:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(2, 'David', '$2y$10$Bk9ZLjzamfLcqsBQR.JwNu.iZrr8T6QaE80Wj3KYpfsC.rOsicwMa', 'sookydavid@hotmail.com', '2023-02-25 09:56:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_products`
--
ALTER TABLE `offer_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_products_ibfk_1` (`product_id`),
  ADD KEY `offer_products_ibfk_2` (`offer_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `offer_products`
--
ALTER TABLE `offer_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `offer_products`
--
ALTER TABLE `offer_products`
  ADD CONSTRAINT `offer_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_products_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
