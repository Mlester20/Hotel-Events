-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2026 at 07:43 AM
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
-- Database: `hotel_events`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `log_activity` (IN `p_user_id` INT, IN `p_role` VARCHAR(20), IN `p_action` VARCHAR(100), IN `p_module` VARCHAR(50), IN `p_reference_id` INT, IN `p_reference_table` VARCHAR(50), IN `p_description` TEXT, IN `p_ip_address` VARCHAR(45), IN `p_status` VARCHAR(10))   BEGIN
  INSERT INTO `activities_log`
    (user_id, role, action, module, reference_id, reference_table,
     description, ip_address, status)
  VALUES
    (p_user_id, p_role, p_action, p_module, p_reference_id, p_reference_table,
     p_description, p_ip_address, p_status);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activities_log`
--

CREATE TABLE `activities_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('admin','front_desk','user') NOT NULL DEFAULT 'user',
  `action` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `reference_table` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('success','failed') NOT NULL DEFAULT 'success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities_log`
--

INSERT INTO `activities_log` (`id`, `user_id`, `role`, `action`, `module`, `reference_id`, `reference_table`, `description`, `ip_address`, `status`, `created_at`) VALUES
(1, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 05:10:31'),
(2, 6, 'front_desk', 'LOGIN', 'AUTH', NULL, NULL, 'Front Desk 1 logged in', '::1', 'success', '2026-04-16 05:12:00'),
(3, 4, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 05:14:12'),
(4, 4, 'user', 'BOOK_ROOM', 'BOOKINGS', 9, 'bookings', 'New room booking BK-US5GHDLODJ8Z created. Total: ₱2500.00', NULL, 'success', '2026-04-16 05:14:33'),
(5, 6, 'front_desk', 'LOGIN', 'AUTH', NULL, NULL, 'Front Desk 1 logged in', '::1', 'success', '2026-04-16 05:17:18'),
(6, 4, 'front_desk', 'BOOKING_CONFIRMED', 'BOOKINGS', 9, 'bookings', 'Booking BK-US5GHDLODJ8Z changed from pending to confirmed', NULL, 'success', '2026-04-16 05:17:23'),
(7, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 05:18:19'),
(8, NULL, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Failed login attempt: frontdesk@gmail.com', '::1', 'failed', '2026-04-16 05:26:05'),
(9, 6, 'front_desk', 'LOGIN', 'AUTH', NULL, NULL, 'Front Desk 1 logged in', '::1', 'success', '2026-04-16 05:26:13'),
(10, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 05:27:23'),
(11, 6, 'front_desk', 'LOGIN', 'AUTH', NULL, NULL, 'Front Desk 1 logged in', '::1', 'success', '2026-04-16 05:33:05'),
(12, 4, 'front_desk', 'UPDATE_PAYMENT', 'BOOKINGS', 8, 'bookings', 'Payment status of BK-L5HM6I5WNEGL updated to partially_paid', NULL, 'success', '2026-04-16 06:08:22'),
(13, 4, 'front_desk', 'UPDATE_PAYMENT', 'BOOKINGS', 9, 'bookings', 'Payment status of BK-US5GHDLODJ8Z updated to partially_paid', NULL, 'success', '2026-04-16 06:08:24'),
(14, 4, 'front_desk', 'UPDATE_PAYMENT', 'BOOKINGS', 9, 'bookings', 'Payment status of BK-US5GHDLODJ8Z updated to paid', NULL, 'success', '2026-04-16 06:08:40'),
(15, 4, 'front_desk', 'UPDATE_PAYMENT', 'BOOKINGS', 8, 'bookings', 'Payment status of BK-L5HM6I5WNEGL updated to paid', NULL, 'success', '2026-04-16 06:08:42'),
(16, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 06:09:17'),
(17, 4, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-16 06:10:57'),
(18, 4, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-17 13:56:59'),
(19, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-17 14:37:01'),
(20, 4, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-17 14:37:38'),
(21, 6, 'front_desk', 'LOGIN', 'AUTH', NULL, NULL, 'Front Desk 1 logged in', '::1', 'success', '2026-04-18 04:48:49'),
(22, 2, 'admin', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-18 05:15:43'),
(23, 4, 'user', 'LOGIN', 'AUTH', NULL, NULL, 'Mark Lester Raguindin logged in', '::1', 'success', '2026-04-18 05:25:03'),
(24, 4, 'user', 'BOOK_EVENT', 'EVENT_BOOKINGS', 2, 'event_bookings', 'New event booking BK-F0TGLOMZZJN4 for 35 guests. Total: ₱23500.00', NULL, 'success', '2026-04-18 05:25:51'),
(25, 4, 'user', 'BOOK_EVENT', 'EVENT_BOOKINGS', 3, 'event_bookings', 'New event booking BK-HXDQR97K3JZW for 35 guests. Total: ₱23500.00', NULL, 'success', '2026-04-18 05:27:18'),
(26, 4, 'user', 'BOOK_EVENT', 'EVENT_BOOKINGS', 4, 'event_bookings', 'New event booking BK-S1K58DJCBCEU for 25 guests. Total: ₱23500.00', NULL, 'success', '2026-04-18 05:28:50'),
(27, 4, 'user', 'BOOK_EVENT', 'EVENT_BOOKINGS', 5, 'event_bookings', 'New event booking BK-J1TGUFW4D757 for 25 guests. Total: ₱23500.00', NULL, 'success', '2026-04-18 05:30:11'),
(28, 4, 'user', 'BOOK_EVENT', 'EVENT_BOOKINGS', 6, 'event_bookings', 'New event booking BK-P7WNOYF85YLC for 24 guests. Total: ₱23500.00', NULL, 'success', '2026-04-18 05:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `booking_type` enum('per_hour','per_day','overnight') NOT NULL DEFAULT 'per_day',
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partially_paid','paid') NOT NULL DEFAULT 'unpaid',
  `special_requests` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `check_in_time`, `check_out_time`, `booking_type`, `total_price`, `status`, `payment_status`, `special_requests`, `is_read`, `created_at`) VALUES
(8, 'BK-L5HM6I5WNEGL', 4, 6, '2026-04-14', '2026-04-15', '14:00:00', '15:00:00', 'per_hour', 300.00, 'confirmed', 'paid', NULL, 1, '2026-04-13 13:39:14'),
(9, 'BK-US5GHDLODJ8Z', 4, 6, '2026-04-27', '2026-04-28', NULL, NULL, 'per_day', 2500.00, 'confirmed', 'paid', NULL, 1, '2026-04-16 05:14:33');

--
-- Triggers `bookings`
--
DELIMITER $$
CREATE TRIGGER `trg_booking_created` AFTER INSERT ON `bookings` FOR EACH ROW BEGIN
  INSERT INTO `activities_log`
    (user_id, role, action, module, reference_id, reference_table, description, status)
  VALUES (
    NEW.user_id,
    'user',
    'BOOK_ROOM',
    'BOOKINGS',
    NEW.id,
    'bookings',
    CONCAT('New room booking ', NEW.booking_id, ' created. Total: ₱', NEW.total_price),
    'success'
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_booking_payment_change` AFTER UPDATE ON `bookings` FOR EACH ROW BEGIN
  -- Check kung payment_status ang nagbago
  IF OLD.payment_status <> NEW.payment_status THEN
    INSERT INTO `activities_log`
      (user_id, role, action, module, reference_id, reference_table, description, status)
    VALUES (
      NEW.user_id,
      'front_desk',
      'UPDATE_PAYMENT',
      'BOOKINGS',
      NEW.id,
      'bookings',
      CONCAT('Payment status of ', NEW.booking_id, ' updated to ', NEW.payment_status),
      'success'
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_booking_status_change` AFTER UPDATE ON `bookings` FOR EACH ROW BEGIN
  IF OLD.status <> NEW.status THEN
    INSERT INTO `activities_log`
      (user_id, role, action, module, reference_id, reference_table, description, status)
    VALUES (
      NEW.user_id,
      'front_desk',
      CONCAT('BOOKING_', UPPER(NEW.status)),
      'BOOKINGS',
      NEW.id,
      'bookings',
      CONCAT('Booking ', NEW.booking_id, ' changed from ', OLD.status, ' to ', NEW.status),
      'success'
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `image`, `location`, `capacity`, `price`, `created_at`, `updated_at`) VALUES
(10, 'Elegant Garden Wedding Ceremony', 'A romantic outdoor wedding setup featuring floral arch designs, aisle decorations, and ambient string lights. Includes ceremony seating arrangement, sound system for vows, and optional wedding coordinator assistance.', '8df9a34688de3656b8e6e732bd02e702.jpg', 'Hotel Garden / Outdoor Lawn Area', 35, 23500.00, '2026-04-12 12:45:36', '2026-04-12 13:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `event_bookings`
--

CREATE TABLE `event_bookings` (
  `id` int(11) NOT NULL,
  `event_booking_id` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `booking_date_start` date DEFAULT NULL,
  `booking_date_end` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `number_of_guests` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partially_paid','paid') NOT NULL DEFAULT 'unpaid',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_bookings`
--

INSERT INTO `event_bookings` (`id`, `event_booking_id`, `user_id`, `event_id`, `booking_date_start`, `booking_date_end`, `start_time`, `end_time`, `number_of_guests`, `total_price`, `status`, `payment_status`, `special_requests`, `created_at`, `updated_at`) VALUES
(6, 'BK-P7WNOYF85YLC', 4, 10, '2026-04-18', '2026-04-18', '14:00:00', NULL, 24, 23500.00, 'pending', 'unpaid', '', '2026-04-18 05:41:54', '2026-04-18 05:41:54');

--
-- Triggers `event_bookings`
--
DELIMITER $$
CREATE TRIGGER `trg_event_booking_created` AFTER INSERT ON `event_bookings` FOR EACH ROW BEGIN
  INSERT INTO `activities_log`
    (user_id, role, action, module, reference_id, reference_table, description, status)
  VALUES (
    NEW.user_id,
    'user',
    'BOOK_EVENT',
    'EVENT_BOOKINGS',
    NEW.id,
    'event_bookings',
    CONCAT('New event booking ', NEW.event_booking_id, ' for ', NEW.number_of_guests, ' guests. Total: ₱', NEW.total_price),
    'success'
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `home_descriptions`
--

CREATE TABLE `home_descriptions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_descriptions`
--

INSERT INTO `home_descriptions` (`id`, `title`, `content`, `is_active`, `created_at`) VALUES
(3, 'Hotel & Events', 'Nestled in the heart of the city, our hotel offers a perfect blend of comfort, elegance, and convenience. Designed for both leisure and business travelers, the hotel features modern rooms equipped with premium amenities, ensuring a relaxing and memorable stay. Guests can enjoy 24/7 front desk assistance, complimentary high-speed Wi-Fi, an on-site restaurant serving local and international cuisine, and well-appointed function rooms for meetings and events. With its strategic location and warm hospitality, our hotel provides an ideal home away from home.', 1, '2026-04-09 07:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) DEFAULT NULL,
  `batch` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`, `created_at`) VALUES
(1, '003_create_table_rooms.php', 1, '2026-04-04 15:00:38'),
(3, '004_create_table_bookings.php', 2, '2026-04-05 10:05:43'),
(4, '005_create_table_home_descriptions.php', 3, '2026-04-05 13:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(100) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `price_day` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price_hourly` decimal(10,2) DEFAULT NULL,
  `price_overnight` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type_id`, `amenities`, `images`, `price_day`, `created_at`, `price_hourly`, `price_overnight`) VALUES
(6, '1', 1, '[\"Tv\",\"Wifi\",\"Aircon\"]', '[\"1775723791_1769062263_room1.jpg\",\"1775723791_1770301994_room1.jpg\"]', 2500.00, '2026-04-09 08:36:31', 300.00, 800.00);

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `title`, `details`, `created_at`, `updated_at`) VALUES
(1, 'Double Room', 'Double Room', '2026-04-03 14:22:54', '2026-04-14 07:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `role` enum('admin','front_desk','user') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `profile`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'Mark Lester Raguindin', 'admin@gmail.com', '$2y$10$vxMruMfxBK5ao8GyxcgN5O48YBv6M9uCwhZN2xR1MoUBRv/yCOLvu', 'pfp_69d1251221c5c6.52241209.jpg', 'admin', 1, NULL, '2026-04-03 04:29:22', '2026-04-05 13:19:59'),
(4, 'Mark Lester Raguindin', 'suguitanmark123@gmail.com', '$2y$10$KnzBxrmkQbn08kCyBi/ebuUxvoj4BHJTbacJZ71O/j64lfgkthWS6', 'pfp_69d2579aad4163.88781799.jpg', 'user', 1, NULL, '2026-04-05 12:24:36', '2026-04-05 13:21:45'),
(6, 'Front Desk 1', 'frontdesk@gmail.com', '$2y$10$TYMLHHsasDMWE3VcoU921.WIvCvp97rDIyIYBZ8/vRyUhC4iC1HsG', 'pfp_69de4b08aff095.58551970.jpg', 'front_desk', 1, NULL, '2026-04-14 07:39:58', '2026-04-14 14:11:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities_log`
--
ALTER TABLE `activities_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_module` (`module`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_booking_id` (`event_booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_event_bookings_event_id` (`event_id`);

--
-- Indexes for table `home_descriptions`
--
ALTER TABLE `home_descriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities_log`
--
ALTER TABLE `activities_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_bookings`
--
ALTER TABLE `event_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `home_descriptions`
--
ALTER TABLE `home_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities_log`
--
ALTER TABLE `activities_log`
  ADD CONSTRAINT `activities_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD CONSTRAINT `event_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_event_bookings_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
