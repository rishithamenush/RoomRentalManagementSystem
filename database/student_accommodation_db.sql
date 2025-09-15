
-- Student Accommodation Platform Database Schema
-- Version: 2.0
-- Created: 2024

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `role` enum('student','owner','admin') NOT NULL DEFAULT 'student',
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('pending','active','suspended','rejected') NOT NULL DEFAULT 'pending',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  KEY `idx_role_status` (`role`, `status`),
  KEY `idx_email_verified` (`email_verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `user_id` int(30) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `university` varchar(255) NOT NULL,
  `student_id_doc` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner_profiles`
--

CREATE TABLE `owner_profiles` (
  `user_id` int(30) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `nic_doc` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `business_name` varchar(255) DEFAULT NULL,
  `business_registration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_profiles`
--

CREATE TABLE `admin_profiles` (
  `user_id` int(30) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `owner_id` int(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `price_lkr` decimal(10,2) NOT NULL,
  `gender_pref` enum('male','female','any') NOT NULL DEFAULT 'any',
  `room_type` enum('single','shared','studio','apartment') NOT NULL,
  `facilities` json DEFAULT NULL,
  `availability_status` enum('active','inactive','under_review','rejected') NOT NULL DEFAULT 'under_review',
  `description` text NOT NULL,
  `avg_rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_owner_id` (`owner_id`),
  KEY `idx_availability_status` (`availability_status`),
  KEY `idx_price_lkr` (`price_lkr`),
  KEY `idx_gender_pref` (`gender_pref`),
  KEY `idx_room_type` (`room_type`),
  KEY `idx_location` (`lat`, `lng`),
  KEY `idx_rating` (`avg_rating`),
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `listing_id` int(30) NOT NULL,
  `url` varchar(500) NOT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `position` int(11) NOT NULL DEFAULT 0,
  `alt_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_position` (`position`),
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `listing_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `owner_note` text DEFAULT NULL,
  `student_note` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`start_date`, `end_date`),
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `booking_id` int(30) NOT NULL,
  `amount_lkr` decimal(10,2) NOT NULL,
  `method` enum('bank_transfer','cash','online_gateway') NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `receipt_no` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_status` (`status`),
  KEY `idx_paid_at` (`paid_at`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_threads`
--

CREATE TABLE `message_threads` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `listing_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `owner_id` int(30) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_owner_id` (`owner_id`),
  KEY `idx_last_message` (`last_message_at`),
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `thread_id` int(30) NOT NULL,
  `sender_id` int(30) NOT NULL,
  `body` text NOT NULL,
  `att_url` varchar(500) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_thread_id` (`thread_id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_created_at` (`created_at`),
  FOREIGN KEY (`thread_id`) REFERENCES `message_threads`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `by_student_id` int(30) NOT NULL,
  `against_owner_id` int(30) DEFAULT NULL,
  `listing_id` int(30) DEFAULT NULL,
  `booking_id` int(30) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('under_review','resolved','rejected') NOT NULL DEFAULT 'under_review',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolver_admin_id` int(30) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_by_student_id` (`by_student_id`),
  KEY `idx_against_owner_id` (`against_owner_id`),
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  FOREIGN KEY (`by_student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`against_owner_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`resolver_admin_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `by_user_id` int(30) NOT NULL,
  `target_user_id` int(30) DEFAULT NULL,
  `listing_id` int(30) DEFAULT NULL,
  `booking_id` int(30) DEFAULT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_by_user_id` (`by_user_id`),
  KEY `idx_target_user_id` (`target_user_id`),
  KEY `idx_listing_id` (`listing_id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_rating` (`rating`),
  FOREIGN KEY (`by_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`target_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `actor_user_id` int(30) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity` varchar(50) NOT NULL,
  `entity_id` int(30) DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_actor_user_id` (`actor_user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity` (`entity`, `entity_id`),
  KEY `idx_created_at` (`created_at`),
  FOREIGN KEY (`actor_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'LKR',
  `default_location` varchar(100) DEFAULT 'Malabe',
  `max_listing_images` int(11) DEFAULT 10,
  `booking_advance_days` int(11) DEFAULT 30,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Insert default admin user
--

INSERT INTO `users` (`id`, `role`, `email`, `phone`, `password_hash`, `status`, `email_verified`) VALUES
(1, 'admin', 'admin@studentaccommodation.lk', '+94771234567', '0192023a7bbd73250516f069df18b500', 'active', 1);

INSERT INTO `admin_profiles` (`user_id`, `full_name`, `department`, `permissions`) VALUES
(1, 'System Administrator', 'IT', '["all"]');

-- --------------------------------------------------------

--
-- Insert default system settings
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`, `currency`, `default_location`) VALUES
(1, 'Student Accommodation Platform', 'admin@studentaccommodation.lk', '+94771234567', 'default-cover.jpg', 'A secure platform connecting students with quality accommodation options in Sri Lanka.', 'LKR', 'Malabe');

-- --------------------------------------------------------

--
-- Create indexes for better performance
--

-- Spatial index for location-based searches (if using MySQL 8.0+)
-- ALTER TABLE `listings` ADD SPATIAL INDEX `idx_location_spatial` (`lat`, `lng`);

-- Full-text search index for listings
ALTER TABLE `listings` ADD FULLTEXT(`title`, `description`, `address`);

-- Composite indexes for common queries
CREATE INDEX `idx_listings_search` ON `listings` (`availability_status`, `price_lkr`, `gender_pref`, `room_type`);
CREATE INDEX `idx_bookings_dates` ON `bookings` (`listing_id`, `start_date`, `end_date`, `status`);
CREATE INDEX `idx_messages_thread` ON `messages` (`thread_id`, `created_at`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
