-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 04:19 AM
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
-- Database: `im102_lab3_salomon`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronics'),
(2, 'Food'),
(3, 'Office Supplies'),
(4, 'Clothing'),
(5, 'Home Appliances');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `supplier_id`, `created_at`, `added_by`) VALUES
(1, 'Laptop', 'Ryzen 5 Laptop with 16GB RAM', 35000.00, 10, 1, 1, '2026-06-24 01:15:57', 1),
(2, 'Mouse', 'Wireless Optical Mouse', 500.00, 50, 1, 1, '2026-06-24 01:15:57', 1),
(3, 'Keyboard', 'Mechanical Gaming Keyboard', 1200.00, 25, 1, 1, '2026-06-24 01:15:57', 1),
(4, 'Monitor', '24-inch LED Monitor Full HD', 6500.00, 15, 1, 1, '2026-06-24 01:15:57', 1),
(5, 'Bread', 'Fresh White Bread Loaf', 50.00, 100, 2, 2, '2026-06-24 01:15:57', 1),
(6, 'Cake', 'Chocolate Cake with Cream', 450.00, 20, 2, 2, '2026-06-24 01:15:57', 1),
(7, 'Cookies', 'Chocolate Chip Cookies Pack', 120.00, 40, 2, 2, '2026-06-24 01:15:57', 1),
(8, 'Notebook', '200-page Spiral Notebook', 80.00, 60, 3, 3, '2026-06-24 01:15:57', 1),
(9, 'Ballpen', 'Blue Ink Ballpen Box', 20.00, 200, 3, 3, '2026-06-24 01:15:57', 1),
(10, 'T-Shirt', 'Cotton T-Shirt Unisex', 250.00, 35, 4, 3, '2026-06-24 01:15:57', 1),
(11, 'Jeans', 'Denim Jeans Regular Fit', 800.00, 18, 4, 3, '2026-06-24 01:15:57', 1),
(12, 'haha', 'asda', 123.00, 21, 5, 1, '2026-06-24 01:42:25', 1),
(13, 'puncher', 'a', 123.00, 11, 1, 3, '2026-06-25 01:47:20', 1),
(31, 'asd', 'asd', 12.00, 1, 5, 2, '2026-06-25 02:15:23', 4);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`) VALUES
(1, 'IQuinzTech', 'Mark Quinz', '09123456789'),
(2, 'Gaton\'s Bakeshop', 'Allyssa Gaton', '09187654321'),
(3, 'Home Essentials', 'Salman Salmon', '09991234567');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$X2RWYPrpoEKeYA4pqQJFlO2lJ.AWvwxRZlPlsP3pf1HCiJQkQdFSG', 'admin', '2026-06-24 01:17:23'),
(2, 'staff', 'staff@gmail.com', '$2y$10$NPk43ZVs6JL3UTfnNeaH7esQVrXTZ/jpSyMn26xIHpsb7agrvRJNK', 'staff', '2026-06-24 01:25:54'),
(3, 'test', 'test@gmail.com', '$2y$10$KPyIPwjWST/4LVUuXSicOuRg42ElUcxWoQlz9IVyxjMTmGrnPwwfG', 'staff', '2026-06-24 02:10:41'),
(4, 'admin2', 'admin2@gmail.com', '$2y$10$lt2VKOnI4tMq.zpuzJ1ytOfJSmJz7ZXJvKzgZ9ys0MRPnrvtfYMoq', 'admin', '2026-06-25 01:56:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
