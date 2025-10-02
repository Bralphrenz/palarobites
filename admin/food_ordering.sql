-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 02, 2025 at 03:03 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `client_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `client_ip`, `user_id`, `product_id`, `qty`, `created_at`) VALUES
(45, NULL, 10, 3, 5, '2025-05-22 05:36:36'),
(46, NULL, 10, 21, 1, '2025-05-22 05:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `img_path`) VALUES
(7, 'Pizza', '1746952551_1746945360_Pizza.jpg'),
(9, 'Chicken', '1746959489_raw-whole-chicken.jpg'),
(10, 'Burgers', '1747055328_1746945660_Burgers.jpg'),
(11, 'Cakes', '1747055384_1746946140_Cake.jpg'),
(12, 'Coffee', '1747115762_1746946380_Coffee.jpg'),
(13, 'dessert', '1747891967_Cake.jpg'),
(14, 'Hotcake', '1747892144_1746945660_Burgers.jpg'),
(15, 'Hotcake', '1747892173_1746945660_Burgers.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `is_read`) VALUES
(1, 12, 3, 'hi', '2025-10-01 09:31:20', 0),
(2, 12, 3, 'hello', '2025-10-01 09:48:29', 0),
(3, 12, 3, 'hi', '2025-10-01 09:53:13', 0),
(4, 13, 3, 'hello', '2025-10-01 10:18:19', 0),
(5, 13, 3, 'hi', '2025-10-01 10:18:24', 0),
(6, 13, 3, 'Can I send', '2025-10-01 10:52:03', 0),
(7, 3, 13, 'Ye', '2025-10-01 17:05:27', 1),
(8, 3, 13, 'Privyet', '2025-10-01 20:05:47', 1),
(9, 13, 3, 'Da?', '2025-10-01 20:06:14', 0),
(10, 3, 13, 'Lalalalalals', '2025-10-01 21:02:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `mobile` text NOT NULL,
  `email` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `address`, `mobile`, `email`, `status`, `date_created`) VALUES
(1, 'ligma ligmus', 'Chelyabinsk, Russia', '0765432198', 'ligma@diddy.net', 0, '2025-09-29 19:12:29'),
(2, 'ligma ligmus', 'Chelyabinsk, Russia', '0765432198', 'ligma@diddy.net', 0, '2025-09-29 19:13:16'),
(3, 'jay ann', 'here', '1234567890', 'joemarieebarat123@gmail.com', 0, '2025-09-30 18:15:21'),
(4, 'jay ann', 'here', '1234567890', 'joemarieebarat123@gmail.com', 0, '2025-09-30 18:15:48'),
(5, 'Jon Diddy', 'Ligma Street', '0765432198', 'diddy@kremlin.com', 1, '2025-10-01 17:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `product_id`, `qty`, `date_created`) VALUES
(1, 1, 1, 1, '2025-09-29 19:12:29'),
(2, 1, 9, 2, '2025-09-29 19:12:29'),
(3, 2, 14, 1, '2025-09-29 19:13:16'),
(4, 3, 2, 1, '2025-09-30 18:15:21'),
(5, 3, 1, 1, '2025-09-30 18:15:21'),
(6, 4, 18, 1, '2025-09-30 18:15:48'),
(7, 4, 19, 1, '2025-09-30 18:15:48'),
(8, 5, 1, 4, '2025-10-01 17:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `quantity` int NOT NULL DEFAULT '0',
  `img_path` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0= unavailable, 2 Available',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `img_path`, `status`, `date_created`) VALUES
(1, 7, 'Pepperoni Pizza', 'Wow', 250, 8, '1759349223_1746945360_Pizza.jpg', 1, '2025-05-19 12:37:56'),
(2, 7, 'Margherita Pizza', 'Wow2', 250, 5, '1759341622_1746951248_Margherita Pizza.jpg', 1, '2025-05-19 12:37:56'),
(3, 9, 'Fried Chicken', 'Crispy', 150, 0, '1746951972_1746945480_Chicken.jpg', 1, '2025-05-19 12:37:56'),
(4, 9, 'Grilled chicken', 'Yammy', 240, 0, '1746952134_Grilled chicken.jpg', 1, '2025-05-19 12:37:56'),
(5, 7, 'Hawaiian Pizza', 'Mmm', 250, 0, '1746952916_Hawaiian Pizza.jpg', 1, '2025-05-19 12:37:56'),
(6, 7, 'Veggie Pizza', 'Ulala', 250, 0, '1746952926_Veggie Pizza.jpg', 1, '2025-05-19 12:37:56'),
(7, 9, ' Chicken curry', 'Damn', 280, 0, '1746953847_Chicken curry.jpg', 1, '2025-05-19 12:37:56'),
(8, 9, 'Roasted chicken', 'Damnnn', 280, 0, '1746953890_Roasted chicken.jpg', 1, '2025-05-19 12:37:56'),
(9, 10, 'Cheeseburger', 'Burger withCheese', 70, 0, '1747116237_Cheeseburger.jpg', 1, '2025-05-19 12:37:56'),
(10, 10, 'Chicken burger', 'Burger with Chicken', 70, 0, '1747116284_Chicken burger.jpg', 1, '2025-05-19 12:37:56'),
(11, 10, 'Hamburger', 'Burger With Ham', 70, 0, '1747116311_Hamburger.jpg', 1, '2025-05-19 12:37:56'),
(12, 10, 'Veggie burger', 'Burger with Veggie', 70, 0, '1747116346_Veggie burger.jpg', 1, '2025-05-19 12:37:56'),
(13, 11, 'Carrot Cake', 'cake with carrot', 150, 0, '1747117276_carrot.jpg', 1, '2025-05-19 12:37:56'),
(14, 11, 'Chocolate Cake', 'cake with chocolate', 150, 0, '1747117339_chlate.jpg', 1, '2025-05-19 12:37:56'),
(15, 11, 'Lava cake', 'chocolate vanilla', 180, 0, '1747117449_lava.jpg', 1, '2025-05-19 12:37:56'),
(16, 11, 'Strawberry Cake', 'cake with strawberry flavor', 200, 0, '1747117501_strberry.jpg', 1, '2025-05-19 12:37:56'),
(17, 12, 'Black Coffee', 'Greatest black caramel', 60, 0, '1747117937_black.jpg', 1, '2025-05-19 12:37:56'),
(18, 12, 'Capuccino', 'coffee with cream', 70, 0, '1747117990_capuccino.jpg', 1, '2025-05-19 12:37:56'),
(19, 12, 'Espresso ', 'Smashed beans', 80, 0, '1747504378_espreso.jpg', 1, '2025-05-19 12:37:56'),
(20, 12, 'Hot Americano', 'coffee with vanilla extract', 90, 0, '1747872211_hot americano.jpg', 1, '2025-05-19 12:37:56'),
(21, 13, 'dessert', 'chocolate cake', -1, 0, '1747892040_1746946140_Cake.jpg', 1, '2025-05-22 05:34:00'),
(23, 10, 'a', 'a', 1, 0, '1747892096_1746945480_Chicken.jpg', 1, '2025-05-22 05:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address`, `role`) VALUES
(3, 'Ralph', 'Bends', 'bends@try.com', '$2y$10$3gRVOVQunnUWecMaKC8H0utj80eGxEdxcRasZBZ6VnJ4Knwphi8Zm', '09123456', 'wertyuiop', 'admin'),
(4, 'Ralph', 'Bendss', 'user@example.com', '$2y$10$efDvenHYJ5Fu/xxt1ANbXuRx5/TuzNs/s4k6keUiiFvr2ueE0GmrG', '0987654321', 'User Address', 'user'),
(5, 'Test', 'Me', 'test@test.com', '$2y$10$HqnfLLu76GrK0UOmOT.cm.gH/ADeVQwwoQQz.W2F/0PPlxoYovwpm', '0987654321', 'Purok 6 Songculan, Dauis, Bohol', 'user'),
(6, 'Zeddimy', 'Zed', 'Zeddimy@gmail.com', '$2y$10$WN9KOyNtiVVN1eiPefdy5uMM2bniVAJP.PvVaV5ZIIBagSdadT426', '0983874827', 'bohol', 'user'),
(7, 'polpol', 'popol', 'polpol@gmail.com', '$2y$10$ybe.2dwAEB3a0oqjUZG5Rev3wHzldPkDDWSlR7qDMWDNWHyFEOIg.', '0983874827', 'dsgdtdgrr', 'user'),
(8, 'Val', 'Francis', 'val@asiagate.com', '$2y$10$Qf4TxDRU2J/NBcW3RIIbYeC/339X/k7i6tUhjOnRniNIUmAda.iR2', '0983874827', '1234312', 'user'),
(9, 'Zed', 'zws', 'zed@gmail.com', '$2y$10$S/iF08OX9ZrR0LbFwpLvRuy1P.6MXN.0pi1hQgxnDDj8X2mCHbyfC', '0978678976', 'bingag', 'user'),
(10, 'milagresa', 'tabilo', 'milag@gmail.com', '$2y$10$CcIqSS0YJ685J.A6NQJ8YedT6UA5FRyjR76HR/OF2SlueSk9BgxtC', '1234567891', 'danao', 'user'),
(12, 'jay', 'ann', 'joemarieebarat123@gmail.com', '$2y$10$wc6jpI3js0fWRfo8rb5WK.4ozTTXZzF/s/8HK4xBEMCHYQK0H6L4u', '1234567890', 'here', 'user'),
(13, 'Jon', 'Diddy', 'diddy@cec.edu.ph', '$2y$10$dJ/hOW7bhkOjx2T2wvAWfewgL3KQtEb6DjokCbZ/kXTpZL8shYdfa', '0765432198', 'Ligma Street', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user_info` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
