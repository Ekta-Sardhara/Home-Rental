-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 03:08 PM
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
-- Database: `home_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `message_text` text DEFAULT NULL,
  `reply_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_name`, `sender_email`, `message_text`, `reply_text`, `created_at`) VALUES
(1, 'trusha', 'trusha@gmail.com', 'hello', 'hello, what can i help you mem', '2026-03-22 12:57:43'),
(2, 'trusha', 'trusha@gmail.com', 'hello', NULL, '2026-03-22 14:30:00'),
(3, 'ekta', 'sardharaekta@gmail.com', 'hello', 'hyy\r\n', '2026-03-22 14:37:54'),
(4, 'ekta', 'sardharaekta@gmail.com', 'hello', NULL, '2026-03-23 10:43:10');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `status` enum('paid','unpaid') DEFAULT 'unpaid',
  `payment_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `method` varchar(50) DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `property_id`, `amount`, `status`, `payment_id`, `created_at`, `method`, `proof`) VALUES
(6, 16, 19, 15000, 'paid', 'PAY8616', '2026-03-23 18:04:22', NULL, NULL),
(7, 19, 20, 6000, 'paid', 'PAY6685', '2026-03-23 18:41:34', NULL, NULL),
(8, 16, 18, 8000, 'paid', 'PAY8943', '2026-03-24 03:18:02', NULL, NULL),
(9, 16, 17, 12000, 'paid', 'PAY4302', '2026-03-24 03:32:43', NULL, NULL),
(12, 17, 23, 8000, 'paid', 'PAY8928', '2026-03-31 05:13:13', NULL, NULL),
(15, 17, 22, 50000, 'paid', 'PAY8009', '2026-03-31 05:45:48', NULL, NULL),
(18, 16, 32, 5000, 'paid', 'PAY3376', '2026-04-02 05:58:48', NULL, NULL),
(19, 16, 40, 9000, 'paid', 'PAY7284', '2026-04-05 15:32:02', NULL, NULL),
(20, 32, 35, 6000, 'paid', 'PAY9764', '2026-04-06 08:20:44', NULL, NULL),
(21, 17, 36, 6000, 'paid', 'PAY8329', '2026-04-08 17:49:44', NULL, NULL),
(22, 33, 41, 7000, 'paid', 'PAY3122', '2026-04-17 11:15:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `type` enum('flat','house') DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `availability_from` date DEFAULT NULL,
  `availability_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `city`, `type`, `rent`, `description`, `image`, `owner_id`, `location`, `price`, `title`, `availability_from`, `availability_to`) VALUES
(18, NULL, NULL, NULL, NULL, NULL, 'uploads/1774283859_1774186579_download.jfif', 20, 'surat', 8000, '1 BHK flat', NULL, NULL),
(19, NULL, NULL, NULL, NULL, NULL, 'uploads/1774283888_OIP.jfif', 20, 'surat', 15000, '3 BHK flat', NULL, NULL),
(20, NULL, NULL, NULL, NULL, NULL, 'uploads/1774290898_1774161995_product-jpeg-1000x1000.jpg', 20, 'surat', 6000, 'mini row house', NULL, NULL),
(22, NULL, NULL, NULL, NULL, NULL, 'uploads/1774503779_c-1000-designbundle-modern-luxury-villa-exterior-01-21-11-a348e63f3ba570b1f89c9308db3d9db862fa817e21cba30a70c3893b200d3634.jpg', 24, 'surat', 50000, 'morden villa', NULL, NULL),
(23, NULL, NULL, NULL, NULL, NULL, 'uploads/1774933744_1774111844_gall-2-3BHK_MASTER_BEDROOM.jpg', 23, 'surat', 8000, '1 BHK flat', NULL, NULL),
(24, NULL, NULL, NULL, NULL, NULL, 'uploads/1774934604_1774262138_OIP.jfif', 23, 'surat vesu', 12000, '3 BHK flat', NULL, NULL),
(25, NULL, NULL, NULL, NULL, NULL, 'uploads/1774935439_3_bhk_independent_house-for-sale-jalaramnager_1-Surat-others.avif', 23, 'surat', 15000, 'Big row house', NULL, NULL),
(26, NULL, NULL, NULL, NULL, NULL, 'uploads/1774935481_download (1).jfif', 23, 'surat', 11000, 'Mini Row House', NULL, NULL),
(27, NULL, NULL, NULL, NULL, NULL, 'uploads/1774935556_download (3).jfif', 20, 'surat', 14000, 'Big Row House', NULL, NULL),
(35, NULL, NULL, NULL, NULL, NULL, 'uploads/1775397723_1775108634_download (5).jfif', 23, 'surat', 6000, 'mini row house ', NULL, NULL),
(36, NULL, NULL, NULL, NULL, NULL, 'uploads/1775400711_real image.webp', 31, 'surat', 6000, 'mini row house ', NULL, NULL),
(40, NULL, NULL, NULL, NULL, NULL, 'uploads/1775401825_product-jpeg-1000x1000.jpg', 31, 'surat', 9000, 'row house ', NULL, NULL),
(41, NULL, NULL, NULL, NULL, NULL, 'uploads/1776424337_1774111844_gall-2-3BHK_MASTER_BEDROOM.jpg', 31, 'valsad', 7000, '1 bhk flat', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `property_id`, `tenant_id`, `status`, `duration`) VALUES
(28, 19, 19, 'rejected', NULL),
(29, 19, 16, 'accepted', NULL),
(30, 18, 16, 'accepted', NULL),
(31, 20, 16, 'rejected', NULL),
(32, 20, 19, 'accepted', NULL),
(35, 22, 16, 'rejected', NULL),
(36, 23, 17, 'accepted', NULL),
(37, 23, 16, 'rejected', NULL),
(38, 22, 17, 'accepted', NULL),
(44, 25, 17, 'pending', NULL),
(47, 40, 16, 'accepted', NULL),
(48, 40, 17, 'rejected', NULL),
(49, 40, 26, 'rejected', NULL),
(50, 35, 32, 'accepted', NULL),
(51, 35, 16, 'rejected', NULL),
(52, 36, 16, 'rejected', NULL),
(53, 36, 17, 'accepted', NULL),
(54, 41, 33, 'accepted', NULL),
(55, 41, 16, 'rejected', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','tenant') NOT NULL DEFAULT 'tenant',
  `otp` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `otp`, `is_verified`) VALUES
(5, 'Admin', 'admin@gmail.com', NULL, 'admin123', 'admin', NULL, 1),
(8, 'trusha', 'trusha@gmail.com', '9090897867', '$2y$10$bgJPh8v33WGy/PWBK07YxOrzfqG3XTYx/f1PtEtfKo6qgYcaIrROy', 'tenant', NULL, 0),
(16, 'abc  patel', 'abc@gmail.com', '9089786756', '$2y$10$kETctmkRUe2UeXY7YXyjjuw/Z.HDDdersLzHhehcWZG2pGz9USFAG', 'tenant', NULL, 1),
(17, 'kailash sardhara', 'ksardhara345@gmail.com', '9898802356', '$2y$10$wxqHxu7LmaE0paIbdIqnZOvVnnvUXBGnCD9VD/Ac9fCdyybtx7DSO', 'tenant', NULL, 1),
(19, 'trusha radadiya', 'trushar2985@gmail.com', NULL, '$2y$10$QQxa0xhJRDm7awndwVJfDewOZtOJmONLoRVjAzkZmUl9gUH9d4Zuy', 'tenant', NULL, 1),
(20, 'nirali goswami', 'goswaminirali009@gmail.com', NULL, '$2y$10$9RYfSMcFhGeIKgyC2OgUTeEU.ISnSm801Phh1/VcapfqGS5RB9Ie2', 'owner', NULL, 1),
(23, 'mihir vekariya', 'patelmihir794@gmail.com', NULL, '$2y$10$oLTyUkNEojs8WwYUkbaj8ep6Ijy8Hcblk21AU0qWYqk9cDwifllUq', 'owner', NULL, 1),
(24, 'khushi hapani', 'khushihapani2709@gmail.com', NULL, '$2y$10$eh70z0dn4OB7cVcGy1Ddrek3ZxaxelOmye13CSt8VGgNshSB311le', 'owner', NULL, 1),
(26, 'anshita khokhariya', 'anshitakhokhariya02@gmail.com', '9234356754', '$2y$10$mVLY3JmxyZ1BfmVWawGYo.VK5nXRgG2B23M2FDEMjD1B4znkkIghW', 'tenant', NULL, 1),
(27, 'smit sardhara', 'sardharasmit@gmail.com', NULL, '$2y$10$J12DmENC6IsFHMC6.Q7ZF.mBhugA1up.OX0biUMQIr4Z88czjQh9m', 'owner', '504167', 0),
(31, 'Vipul sardhara', 'vsardhara75@gmail.com', NULL, '$2y$10$MlVP3oW.RHeQauXgxxUyieuGKSSU8/4erIyG6Kism.wvshwqXiyZi', 'owner', NULL, 1),
(32, 'zalak rudani', 'zalakrudani2022@gmail.com', '3456543256', '$2y$10$ax.0Qa7TnNVT9oYDu9Y7tOlqtaaim/I91F8XEMnFHkIWPsTPJ68SG', 'tenant', NULL, 1),
(33, 'xyz', 'xyz@gmail.com', '9510592831', '$2y$10$VHzB6XFbk6AmYSIcR4Hu/e5kMmAXobZXOqWfex0gkIGeGdgv3act.', 'tenant', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `tenant_id` (`tenant_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
