-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2023 at 03:51 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cp_trasbay`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `booking_status` varchar(15) NOT NULL,
  `cancellation_status` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `car_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_photo` varchar(255) DEFAULT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `seat_count` int(2) NOT NULL,
  `car_status` varchar(10) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`car_id`, `user_id`, `car_photo`, `brand`, `model`, `color`, `type`, `seat_count`, `car_status`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:11:37', '2023-06-07 13:11:37'),
(2, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:13:34', '2023-06-07 13:13:34'),
(3, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:15:22', '2023-06-07 13:15:22'),
(4, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:16:11', '2023-06-07 13:16:11'),
(5, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:17:18', '2023-06-07 13:17:18'),
(6, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:19:08', '2023-06-07 13:19:08'),
(7, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:21:37', '2023-06-07 13:21:37'),
(8, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:21:53', '2023-06-07 13:21:53'),
(9, 15, 'download.jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Pending', NULL, '2023-06-07 13:22:08', '2023-06-07 13:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `car_identification`
--

CREATE TABLE `car_identification` (
  `car_identity_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `or_photo` varchar(255) NOT NULL,
  `or_number` varchar(20) NOT NULL,
  `cr_photo` varchar(255) NOT NULL,
  `cr_number` varchar(20) NOT NULL,
  `sales_invoice` varchar(255) DEFAULT NULL,
  `plate_number` varchar(10) NOT NULL,
  `plate_expiration` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_identification`
--

INSERT INTO `car_identification` (`car_identity_id`, `car_id`, `or_photo`, `or_number`, `cr_photo`, `cr_number`, `sales_invoice`, `plate_number`, `plate_expiration`, `created_at`, `updated_at`) VALUES
(1, 1, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', '', 'LSD-4324', '2023-08-22', '2023-06-07 13:11:37', '2023-06-07 13:11:37'),
(2, 2, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:13:34', '2023-06-07 13:13:34'),
(3, 3, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:15:22', '2023-06-07 13:15:22'),
(4, 4, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:16:11', '2023-06-07 13:16:11'),
(5, 5, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:17:18', '2023-06-07 13:17:18'),
(6, 6, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:19:08', '2023-06-07 13:19:08'),
(7, 7, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:21:37', '2023-06-07 13:21:37'),
(8, 8, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:21:53', '2023-06-07 13:21:53'),
(9, 9, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-4324', '2023-08-22', '2023-06-07 13:22:08', '2023-06-07 13:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `cico`
--

CREATE TABLE `cico` (
  `cico_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trans_type` varchar(15) NOT NULL,
  `gcash_mobile_number` varchar(20) NOT NULL,
  `peso_amount` decimal(10,2) NOT NULL,
  `ticket_amount` int(11) NOT NULL,
  `processing_fee` decimal(10,2) NOT NULL,
  `convenience_fee` decimal(10,2) NOT NULL,
  `reference_number` varchar(20) NOT NULL,
  `method_type` varchar(20) NOT NULL,
  `trans_stat` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `province`, `region`, `created_at`, `updated_at`) VALUES
(1, 'Manila', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(2, 'Quezon City', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(3, 'Caloocan', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(4, 'Davao City', 'Davao del Sur', 'Davao Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(5, 'Cebu City', 'Cebu', 'Central Visayas', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(6, 'Zamboanga City', 'Zamboanga del Sur', 'Zamboanga Peninsula', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(7, 'Taguig', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(8, 'Pasig', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(9, 'Makati', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(10, 'Mandaluyong', 'Metro Manila', 'National Capital Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(11, 'Bacolod', 'Negros Occidental', 'Western Visayas', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(12, 'Iloilo City', 'Iloilo', 'Western Visayas', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(13, 'Baguio City', 'Benguet', 'Cordillera Administrative Region', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(14, 'General Santos City', 'South Cotabato', 'SOCCSKSARGEN', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(15, 'Tacloban City', 'Leyte', 'Eastern Visayas', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(16, 'Malolos', 'Bulacan', 'Central Luzon', '2023-06-03 12:33:58', '2023-06-03 12:33:58'),
(17, 'San Fernando', 'Pampanga', 'Central Luzon', '2023-06-03 12:33:58', '2023-06-03 12:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE `code` (
  `code_id` int(11) NOT NULL,
  `referral_code` varchar(255) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `referred_id` int(11) DEFAULT NULL,
  `refer_status` varchar(20) NOT NULL DEFAULT 'Unused',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `code`
--

INSERT INTO `code` (`code_id`, `referral_code`, `referrer_id`, `referred_id`, `refer_status`, `created_at`, `updated_at`) VALUES
(8, 'ETH80P4Q', 14, 15, 'Used', '2023-06-04 14:34:47', '2023-06-04 14:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `driver_identification`
--

CREATE TABLE `driver_identification` (
  `driver_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `disability` varchar(50) NOT NULL,
  `pwd_docx` varchar(255) DEFAULT NULL,
  `license_front` varchar(255) NOT NULL,
  `license_back` varchar(255) NOT NULL,
  `license_expiration` date NOT NULL,
  `is_above_60` tinyint(1) NOT NULL,
  `nbi_police_cbi` varchar(255) NOT NULL,
  `cbi_date_issued` date DEFAULT NULL,
  `years_experience` int(2) NOT NULL,
  `consents` tinyint(1) NOT NULL DEFAULT 1,
  `declarations` tinyint(1) NOT NULL DEFAULT 1,
  `driver_stat` varchar(10) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_identification`
--

INSERT INTO `driver_identification` (`driver_id`, `user_id`, `disability`, `pwd_docx`, `license_front`, `license_back`, `license_expiration`, `is_above_60`, `nbi_police_cbi`, `cbi_date_issued`, `years_experience`, `consents`, `declarations`, `driver_stat`, `created_at`, `updated_at`) VALUES
(12, 15, 'Dyslexia', '2.jpg', 'TaraSabay Carpooling App.png', '2.jpg', '2023-06-15', 1, 'NBI Clearance', '0000-00-00', 3, 1, 1, 'Pending', '2023-06-06 18:52:00', '2023-06-06 19:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `emergency`
--

CREATE TABLE `emergency` (
  `emergency_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `relationship` varchar(20) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency`
--

INSERT INTO `emergency` (`emergency_id`, `user_id`, `name`, `relationship`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(24, 15, 'Jeanherline Santiago', 'Father', '+639293010483', 'La Felinila St.', '2023-06-06 18:02:47', '2023-06-06 18:02:47'),
(25, 15, 'Jeanherline Santiago', 'Father', '+639293010483', 'La Felinila St.', '2023-06-06 18:06:33', '2023-06-06 18:06:33'),
(26, 15, 'Jeanherline Santiago', 'Father', '+639293010483', 'La Felinila St.', '2023-06-06 18:07:49', '2023-06-06 18:07:49'),
(27, 15, 'Jeanherline Santiago', 'Father', '+639293010483', 'La Felinila St.', '2023-06-06 18:08:01', '2023-06-06 18:08:01'),
(28, 15, 'Suzette Bishop Love Angeles', 'Spouse', '09654105485', '1082 Ibayo Street, Pinaod, San Ildefonso, Bulacan', '2023-06-06 18:52:00', '2023-06-06 18:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `ticket_amount` decimal(6,2) NOT NULL,
  `payment_to` varchar(30) NOT NULL,
  `payment_status` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `pickup_loc` varchar(255) NOT NULL,
  `dropoff_loc` varchar(255) NOT NULL,
  `departure` datetime NOT NULL DEFAULT current_timestamp(),
  `est_arrival_time` time NOT NULL,
  `route_status` varchar(15) NOT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

CREATE TABLE `seat` (
  `seat_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `seat_type` varchar(15) NOT NULL,
  `fare` decimal(6,2) NOT NULL,
  `seat_status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_identification`
--

CREATE TABLE `user_identification` (
  `user_identity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `identity_type` varchar(50) NOT NULL,
  `user_identity_num` varchar(50) NOT NULL,
  `identity_expiration` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_identification`
--

INSERT INTO `user_identification` (`user_identity_id`, `user_id`, `identity_type`, `user_identity_num`, `identity_expiration`, `created_at`, `updated_at`) VALUES
(13, 14, 'National ID', 'FD23-432S-DSA', '2023-06-19', '2023-06-03 20:55:49', '2023-06-03 20:55:49'),
(14, 15, 'Driver\'s License', 'LF-4324', '2023-06-20', '2023-06-04 14:39:50', '2023-06-04 14:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `city_id` int(11) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ticket_balance` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `is_vaxxed` tinyint(1) NOT NULL DEFAULT 0,
  `vax_card` varchar(255) DEFAULT NULL,
  `is_agree` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_id`, `profile_photo`, `first_name`, `middle_name`, `last_name`, `city_id`, `nationality`, `gender`, `birthdate`, `email`, `password`, `ticket_balance`, `role`, `is_vaxxed`, `vax_card`, `is_agree`, `created_at`, `updated_at`) VALUES
(14, 'download.png', 'Jyner', '', 'Lin', 5, 'Filipino', 'Female', '2003-03-29', 'jynerline@gmail.com', '$2y$10$iWP4Hq9nSnb9uYXtDdlOrOJnsRtqsPOt8JELyKoFoXplXXJWLXGGC', '510', 'Passenger', 1, 'download (1).jpg', 1, '2023-06-03 20:55:49', '2023-06-07 13:23:58'),
(15, 'download.jpg', 'Jeanherline', 'Lopez', 'Santiago', 12, 'Filipino', 'Female', '2023-06-15', 'jeanherlinesantiago0329@gmail.com', '$2y$10$4mwq.s/du3mDcpUOVzFHY.olkpUtTjT7RGoE7YOhpZtxh1D9g/9Ta', '10', 'Driver', 1, '31vaccine-card1-videoSixteenByNine3000-v2.jpg', 1, '2023-06-04 14:39:50', '2023-06-07 13:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_temp`
--

CREATE TABLE `user_temp` (
  `temp_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `city_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `identity_type` varchar(50) NOT NULL,
  `user_identity_num` varchar(50) NOT NULL,
  `identity_expiration` date NOT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `ID_user` (`user_id`),
  ADD KEY `seat_ID` (`seat_id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `uID` (`user_id`);

--
-- Indexes for table `car_identification`
--
ALTER TABLE `car_identification`
  ADD PRIMARY KEY (`car_identity_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cico`
--
ALTER TABLE `cico`
  ADD PRIMARY KEY (`cico_id`),
  ADD KEY `u_id` (`user_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `code`
--
ALTER TABLE `code`
  ADD PRIMARY KEY (`code_id`),
  ADD KEY `fk_referred_id` (`referred_id`),
  ADD KEY `fk_referrer_id` (`referrer_id`);

--
-- Indexes for table `driver_identification`
--
ALTER TABLE `driver_identification`
  ADD PRIMARY KEY (`driver_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `emergency`
--
ALTER TABLE `emergency`
  ADD PRIMARY KEY (`emergency_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `book_id` (`booking_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`),
  ADD KEY `carID` (`car_id`);

--
-- Indexes for table `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `user_identification`
--
ALTER TABLE `user_identification`
  ADD PRIMARY KEY (`user_identity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_city_id` (`city_id`);

--
-- Indexes for table `user_temp`
--
ALTER TABLE `user_temp`
  ADD PRIMARY KEY (`temp_id`),
  ADD KEY `fk_city_id_temp` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `car_identification`
--
ALTER TABLE `car_identification`
  MODIFY `car_identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cico`
--
ALTER TABLE `cico`
  MODIFY `cico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `code`
--
ALTER TABLE `code`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `driver_identification`
--
ALTER TABLE `driver_identification`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `emergency`
--
ALTER TABLE `emergency`
  MODIFY `emergency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat`
--
ALTER TABLE `seat`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_identification`
--
ALTER TABLE `user_identification`
  MODIFY `user_identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_temp`
--
ALTER TABLE `user_temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `ID_user` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `seat_ID` FOREIGN KEY (`seat_id`) REFERENCES `seat` (`seat_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `uID` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `car_identification`
--
ALTER TABLE `car_identification`
  ADD CONSTRAINT `car_id` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cico`
--
ALTER TABLE `cico`
  ADD CONSTRAINT `u_id` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `code`
--
ALTER TABLE `code`
  ADD CONSTRAINT `fk_referred_id` FOREIGN KEY (`referred_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_referrer_id` FOREIGN KEY (`referrer_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `driver_identification`
--
ALTER TABLE `driver_identification`
  ADD CONSTRAINT `IDuser` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `emergency`
--
ALTER TABLE `emergency`
  ADD CONSTRAINT `userID` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `book_id` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `booking_id` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `carID` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `seat`
--
ALTER TABLE `seat`
  ADD CONSTRAINT `route_id` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user_identification`
--
ALTER TABLE `user_identification`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `fk_city_id` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user_temp`
--
ALTER TABLE `user_temp`
  ADD CONSTRAINT `fk_city_id_temp` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
