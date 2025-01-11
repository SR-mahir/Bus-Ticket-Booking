-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 01:24 AM
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
-- Database: `bus_ticket_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `bus_id` int(11) NOT NULL,
  `bus_number` varchar(10) NOT NULL,
  `bus_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`bus_id`, `bus_number`, `bus_name`) VALUES
(1, '101', 'Bus 1'),
(2, '102', 'Bus 2'),
(3, '103', 'Bus 3');

-- --------------------------------------------------------

--
-- Table structure for table `busseats`
--

CREATE TABLE `busseats` (
  `seat_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `busseats`
--

INSERT INTO `busseats` (`seat_id`, `bus_id`, `seat_number`, `status`) VALUES
(1, 1, '1A', 'available'),
(2, 1, '1B', 'available'),
(3, 1, '1C', 'booked'),
(4, 1, '2A', 'available'),
(5, 1, '2B', 'booked'),
(6, 1, '2C', 'available'),
(7, 1, '3A', 'available'),
(8, 1, '3B', 'available'),
(9, 1, '3C', 'available'),
(10, 1, '4A', 'available'),
(11, 1, '4B', 'available'),
(12, 1, '4C', 'available'),
(13, 1, '5A', 'available'),
(14, 1, '5B', 'available'),
(15, 1, '5C', 'available'),
(16, 1, '6A', 'available'),
(17, 1, '6B', 'available'),
(18, 1, '6C', 'available'),
(19, 1, '7A', 'available'),
(20, 1, '7B', 'available'),
(21, 2, '1A', 'available'),
(22, 2, '1B', 'available'),
(23, 2, '1C', 'available'),
(24, 2, '2A', 'available'),
(25, 2, '2B', 'available'),
(26, 2, '2C', 'available'),
(27, 2, '3A', 'available'),
(28, 2, '3B', 'available'),
(29, 2, '3C', 'available'),
(30, 2, '4A', 'available'),
(31, 2, '4B', 'available'),
(32, 2, '4C', 'available'),
(33, 2, '5A', 'available'),
(34, 2, '5B', 'available'),
(35, 2, '5C', 'available'),
(36, 2, '6A', 'available'),
(37, 2, '6B', 'available'),
(38, 2, '6C', 'available'),
(39, 3, '1A', 'available'),
(40, 3, '1B', 'available'),
(41, 3, '1C', 'available'),
(42, 3, '2A', 'available'),
(43, 3, '2B', 'available'),
(44, 3, '2C', 'available'),
(45, 3, '3A', 'available'),
(46, 3, '3B', 'available'),
(47, 3, '3C', 'available'),
(48, 3, '4A', 'available'),
(49, 3, '4B', 'available'),
(50, 3, '4C', 'available'),
(51, 3, '5A', 'available'),
(52, 3, '5B', 'available'),
(53, 3, '5C', 'available'),
(54, 3, '6A', 'available'),
(55, 3, '6B', 'available'),
(56, 3, '6C', 'available'),
(57, 3, '7A', 'available'),
(58, 3, '7B', 'available'),
(59, 3, '7C', 'available'),
(60, 3, '8A', 'available'),
(61, 3, '8B', 'available'),
(62, 3, '8C', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 75.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `bus_id`, `seat_number`, `price`) VALUES
(78, 1, 1, '1C', 75.00),
(79, 1, 1, '2B', 75.00);

-- --------------------------------------------------------

--
-- Table structure for table `logged_in`
--

CREATE TABLE `logged_in` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logged_in`
--

INSERT INTO `logged_in` (`id`, `user_id`, `login_time`) VALUES
(1, 1, '2025-01-04 19:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`) VALUES
(1, 2, 2, 'sdadas'),
(2, 2, 2, 'sdadas'),
(3, 4, 3, 'dsadasfagfz'),
(4, 4, 4, 'Hi'),
(5, 1, 3, 'dsadasd'),
(6, 1, 2, 'dsdas');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `address`, `phone`, `role`) VALUES
(1, 'Galib', 'galib@gmail.com', '$2y$10$MKhi2ttpac2dAffwmzFQged5kPdXOsTGyfgXTe7wSA/JtoCTQIJS6', 'Galib', '01701001482', 'admin'),
(2, 'saqlaeen', 'saqlaeen@gmail.com', '$2y$10$dMZgsNhqcjCHaxIYRSw/RemJdtr0waX5IGhTmEqUDFcD2MeZdabum', 'Galib', '01701001482', 'user'),
(3, 'Shahriar', 'shahriar@gmail.com', '$2y$10$XA4qC/dNOcHv3XDR4rNjXO4lohewCQ6wsjjGGgSbUj/U/4FoWFrj6', 'Niketan', '01701001482', 'user'),
(4, 'Mim Alom', 'mimalom@gmail.com', '$2y$10$PcbPKpmx/OtSfNxko7hwm.KEcoUmUY0PQM3g5xRXim/wINvq5tQ8O', 'Badda', '01701001482', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`bus_id`);

--
-- Indexes for table `busseats`
--
ALTER TABLE `busseats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `logged_in`
--
ALTER TABLE `logged_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `bus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `busseats`
--
ALTER TABLE `busseats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `logged_in`
--
ALTER TABLE `logged_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `busseats`
--
ALTER TABLE `busseats`
  ADD CONSTRAINT `busseats_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`bus_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`bus_id`);

--
-- Constraints for table `logged_in`
--
ALTER TABLE `logged_in`
  ADD CONSTRAINT `logged_in_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
