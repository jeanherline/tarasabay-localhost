-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2023 at 12:51 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
  `user_id` int(11) DEFAULT NULL,
  `seat_id` int(11) NOT NULL,
  `booking_status` varchar(15) NOT NULL,
  `cancellation_reason` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `seat_id`, `booking_status`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(7, 14, 15, 'Cancelled', NULL, '2023-06-11 09:07:50', '2023-06-11 09:44:41'),
(9, 14, 6, 'Cancelled', NULL, '2023-06-11 09:42:07', '2023-06-11 09:42:39'),
(10, 14, 9, 'Cancelled', NULL, '2023-06-11 09:55:12', '2023-06-11 09:57:04'),
(11, 14, 13, 'Cancelled', 'sad', '2023-06-11 09:57:25', '2023-06-11 10:08:58'),
(12, 14, 14, 'Approved', NULL, '2023-06-11 10:11:11', '2023-06-11 10:47:54');

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
(9, 15, 'download (6).jpg', 'Toyota', 'M12', 'Red', 'Crossover', 4, 'Active', '../assets/img/qr_codes/car_9.png', '2023-06-07 13:22:08', '2023-06-10 08:48:46'),
(10, 15, 'download (6).jpg', 'BMW', 'KD2', 'Black', 'Coupe', 1, 'Denied', NULL, '2023-06-10 01:07:59', '2023-06-10 22:15:04'),
(14, 15, 'download (6).jpg', 'Jeep', 'HD3', 'Gray', 'Crew Cab', 5, 'Active', '../assets/img/qr_codes/car_14.png', '2023-06-10 01:11:41', '2023-06-11 03:15:01'),
(15, 15, 'download (6).jpg', 'Jeep', 'M12', 'Gray', 'Coupe', 3, 'Active', NULL, '2023-06-10 06:41:50', '2023-06-10 08:48:46'),
(16, 15, '1200px-2017_Toyota_Camry_(ASV50R)_SX_sedan_(2018-11-02)_01.jpg', 'Honda', 'D3X', 'White', 'Sedan', 4, 'Pending', NULL, '2023-06-10 22:14:00', '2023-06-10 22:14:00');

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
(9, 9, 'download (11).jpg', '12345678', 'download (12).jpg', '12345678', 'Aircraft-Delivery-Receipt.jpg', 'LSD-123', '2023-08-22', '2023-06-07 13:22:08', '2023-06-09 11:18:04'),
(10, 10, 'or-385b.jpg', '123456789', 'download (3).jpg', '123456789', 'or-385b.jpg', 'LSD-456', '2023-06-30', '2023-06-10 01:07:59', '2023-06-10 01:07:59'),
(14, 14, 'or-385b.jpg', '123456789', 'cr-dca7.jpg', '123456789', 'download (3).jpg', 'LSD-456', '2024-06-22', '2023-06-10 01:11:41', '2023-06-10 08:44:20'),
(15, 15, 'or-385b.jpg', '123', 'or-385b.jpg', '123', '', 'LSD-12345', '2026-06-21', '2023-06-10 06:41:50', '2023-06-10 06:41:50'),
(16, 16, 'or-385b.jpg', '4567', 'download (3).jpg', '4567', '', 'ASD-123', '2026-06-11', '2023-06-10 22:14:00', '2023-06-10 22:14:00');

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

--
-- Dumping data for table `cico`
--

INSERT INTO `cico` (`cico_id`, `user_id`, `trans_type`, `gcash_mobile_number`, `peso_amount`, `ticket_amount`, `processing_fee`, `convenience_fee`, `reference_number`, `method_type`, `trans_stat`, `created_at`, `updated_at`) VALUES
(8, 15, 'Cash-In', '09293010483', '250.00', 200, '0.00', '50.00', '12345678', 'GCash', 'Successful', '2023-06-09 17:25:04', '2023-06-09 17:25:04'),
(11, 15, 'Cash-Out', '09293010483', '100.00', 100, '20.00', '0.00', '123456789', 'GCash', 'Successful', '2023-06-09 18:03:33', '2023-06-09 18:03:52'),
(12, 14, 'Cash-In', '09293010483', '100.00', 80, '0.00', '20.00', '12345678', 'GCash', 'Successful', '2023-06-10 04:13:55', '2023-06-10 04:13:55'),
(13, 15, 'Cash-In', '09293010483', '500.00', 450, '0.00', '50.00', '12345678', 'GCash', 'Successful', '2023-06-11 03:21:35', '2023-06-11 03:21:35'),
(14, 15, 'Cash-Out', '09293010483', '100.00', 100, '20.00', '0.00', '123456789', 'GCash', 'Successful', '2023-06-11 03:21:54', '2023-06-11 03:22:40');

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
(12, 15, 'Speech/Language Disability', 'or-385b.jpg', '10_2021-07-23_18-27-24.jpg', 'lto-restrictions-1570692181.jpg', '2023-06-28', 1, 'or-385b.jpg', '2023-06-21', 4, 1, 1, 'Active', '2023-06-06 18:52:00', '2023-06-11 03:15:18');

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
(28, 15, 'Jean Reinhart', '', '09567956164', '1082 Ibayo Street, Pinaod', '2023-06-06 18:52:00', '2023-06-10 06:40:26'),
(29, 14, 'Jeanherline Santiago', '', '09567956164', '1082 Ibayo Street', '2023-06-10 04:12:40', '2023-06-11 08:45:11'),
(30, 16, 'Julia Martinez', 'Other', '09567956164', '1082 Ibayo Street, Pinaod', '2023-06-10 04:16:40', '2023-06-10 04:16:40'),
(31, 17, 'Bruce Willis', '', '09567956164', 'America', '2023-06-10 08:06:29', '2023-06-10 08:06:45');

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

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `car_id`, `pickup_loc`, `dropoff_loc`, `departure`, `est_arrival_time`, `route_status`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(3, 9, 'Baliwag', 'Manila', '2023-06-09 08:30:00', '10:30:00', 'Active', '', '2023-06-08 14:52:19', '2023-06-10 11:20:34'),
(4, 15, 'Cabanatuan', 'Bustos', '2023-06-14 17:30:00', '20:00:00', 'Cancelled', 'Sick', '2023-06-10 09:33:56', '2023-06-10 22:08:52'),
(5, 9, 'San Rafael', 'Cebu City', '2023-06-11 10:30:00', '12:30:00', 'Active', NULL, '2023-06-10 09:35:52', '2023-06-11 09:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

CREATE TABLE `seat` (
  `seat_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `seat_type` varchar(255) NOT NULL,
  `fare` varchar(255) NOT NULL,
  `seat_status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`seat_id`, `route_id`, `seat_type`, `fare`, `seat_status`, `created_at`, `updated_at`) VALUES
(6, 3, 'Front Passenger Seat', '250', 'Available', '2023-06-08 14:52:19', '2023-06-11 09:42:39'),
(7, 3, 'Second Row Left Window Seat', '230', 'Available', '2023-06-08 14:52:19', '2023-06-11 09:36:38'),
(8, 3, 'Second Row Middle Seat', '200', 'Available', '2023-06-08 14:52:19', '2023-06-10 09:13:41'),
(9, 3, 'Second Row Right Window Seat', '230', 'Available', '2023-06-08 14:52:19', '2023-06-11 09:57:04'),
(10, 4, 'Front Passenger Seat', '250', 'Cancelled', '2023-06-10 09:33:56', '2023-06-10 22:08:52'),
(11, 4, 'Second Row Left Window Seat', '200', 'Cancelled', '2023-06-10 09:33:56', '2023-06-10 22:08:52'),
(12, 4, 'Second Row Right Window Seat', '200', 'Cancelled', '2023-06-10 09:33:56', '2023-06-10 22:08:52'),
(13, 5, 'Front Passenger Seat', '200', 'Available', '2023-06-10 09:35:52', '2023-06-11 10:06:11'),
(14, 5, 'Second Row Left Window Seat', '200', 'Taken', '2023-06-10 09:35:52', '2023-06-11 10:11:11'),
(15, 5, 'Second Row Middle Seat', '150', 'Available', '2023-06-10 09:35:52', '2023-06-11 09:44:41'),
(16, 5, 'Second Row Right Window Seat', '200', 'Available', '2023-06-10 09:35:52', '2023-06-10 09:35:52');

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
(14, 15, 'Driver\'s License', 'LF-4324', '2023-06-20', '2023-06-04 14:39:50', '2023-06-04 14:39:50'),
(15, 16, 'National ID', 'FDS-4324', '2023-06-21', '2023-06-08 14:56:53', '2023-06-08 14:56:53'),
(16, 17, 'Voter\'s ID', '12345678', '2023-06-24', '2023-06-08 16:35:10', '2023-06-08 16:35:10');

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
  `ticket_balance` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
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
(14, 'download.png', 'Jyner', '', 'Lyn', 5, 'Filipino', 'Female', '2003-03-29', 'jynerline@gmail.com', '$2y$10$iWP4Hq9nSnb9uYXtDdlOrOJnsRtqsPOt8JELyKoFoXplXXJWLXGGC', '590', 'Passenger', 0, 'download (1).jpg', 1, '2023-06-03 20:55:49', '2023-06-11 08:45:11'),
(15, 'test.png', 'Jeanherline', 'Lopez', 'Santiago', 12, 'Filipino', 'Female', '2023-06-15', 'jeanherlinesantiago0329@gmail.com', '$2y$10$4mwq.s/du3mDcpUOVzFHY.olkpUtTjT7RGoE7YOhpZtxh1D9g/9Ta', '390', 'Driver', 1, '31vaccine-card1-videoSixteenByNine3000-v2.jpg', 1, '2023-06-04 14:39:50', '2023-06-11 03:21:54'),
(16, 'download.jpg', 'Jean', '', 'Reinhart', 12, 'American', 'Male', '1995-06-21', 'cinemi1486@ozatvn.com', '$2y$10$sPauKoqU//HyC7mGSnDafOADwxzKy/mx1JRtb/l/1QcnAkcMEKoPq', '0', 'Main Admin', 1, 'download (1).jpg', 1, '2023-06-08 14:56:53', '2023-06-10 04:16:40'),
(17, '340472658_1294457027813960_1344329139963814927_n.jpg', 'Jyner', '', 'Lin', 12, 'Argentinean', 'Female', '1966-06-21', 'imureyzdh1@ezztt.com', '$2y$10$UFabKhJtNaJP5E4KRTNiRudKmGeahkgjTBE/BFX5xjaMVYOe5LAGW', '', 'City Admin', 0, 'download (4).jpg', 1, '2023-06-08 16:35:10', '2023-06-10 08:06:45');

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
-- Dumping data for table `user_temp`
--

INSERT INTO `user_temp` (`temp_id`, `first_name`, `last_name`, `city_id`, `email`, `password`, `identity_type`, `user_identity_num`, `identity_expiration`, `referral_code`, `token`, `created_at`, `updated_at`) VALUES
(21, 'John Lloyd', 'Cruz', 3, 'imureyzdh1@ezztt.com', '$2y$10$Q3Ug7FGVtn2.gZisM5lAYeppYRHyLhkZ6z0kqb87E.k2XHcgrJZPa', 'Voter\'s ID', '12345678', '2023-06-24', NULL, '024daf2f45162a9b70ef705ee855edde', '2023-06-08 16:21:36', '2023-06-08 16:21:36'),
(22, 'John Lloyd', 'Cruz', 3, 'imureyzdh1@ezztt.com', '$2y$10$WFoMx0HmX22PJLZhfpQXhOvg.9V7LcLlrat.LE6a8ZjV1cpYemhvq', 'Voter\'s ID', '12345678', '2023-06-24', NULL, '0dc1eb2c33f50be01b8b843f8eaf15bf', '2023-06-08 16:22:06', '2023-06-08 16:22:06'),
(23, 'John Lloyd', 'Cruz', 3, 'imureyzdh1@ezztt.com', '$2y$10$cwmUKr5FtsIOXbcgnJi1oeLXdwOtEVfClSgpk7s5Vht.B/pihh9fa', 'Voter\'s ID', '12345678', '2023-06-24', NULL, '10845e3e84913a4eb11d70e6465c7712', '2023-06-08 16:26:53', '2023-06-08 16:26:53'),
(24, 'John Lloyd', 'Cruz', 3, 'imureyzdh1@ezztt.com', '$2y$10$qiqYdDEpyaDuCcW21gUsf.JBHLOxznN4E8RZGpYz.8GS3QnN/sFVO', 'Voter\'s ID', '12345678', '2023-06-24', NULL, '4064c2684dfc0e2bc2ddeb996e995d50', '2023-06-08 16:28:42', '2023-06-08 16:28:42'),
(25, 'John Lloyd', 'Cruz', 3, 'imureyzdh1@ezztt.com', '$2y$10$HxW4N10MyeZTtI.GJlNOXe6Ejj/MfQSzPPMyX078ATu1JFhTD6xOS', 'Voter\'s ID', '12345678', '2023-06-24', NULL, 'b197be57ee86320263f02dd2d9ebac63', '2023-06-08 16:29:32', '2023-06-08 16:29:32');

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `car_identification`
--
ALTER TABLE `car_identification`
  MODIFY `car_identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cico`
--
ALTER TABLE `cico`
  MODIFY `cico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `emergency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seat`
--
ALTER TABLE `seat`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_identification`
--
ALTER TABLE `user_identification`
  MODIFY `user_identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_temp`
--
ALTER TABLE `user_temp`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
