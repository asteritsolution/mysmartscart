-- Contact Settings Table for KRC Woollens
-- Run this SQL to create the contact settings table

CREATE TABLE IF NOT EXISTS `contact_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `map_latitude` decimal(10,8) DEFAULT NULL,
  `map_longitude` decimal(11,8) DEFAULT NULL,
  `business_hours_monday_friday` varchar(255) DEFAULT NULL,
  `business_hours_saturday` varchar(255) DEFAULT NULL,
  `business_hours_sunday` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default contact settings
INSERT INTO `contact_settings` (`address`, `phone`, `email`, `description`, `map_latitude`, `map_longitude`, `business_hours_monday_friday`, `business_hours_saturday`, `business_hours_sunday`) VALUES
('Ranikhet, Uttarakhand, India', '+91 1234567890', 'info@krcwoollens.com', 'Get in touch with KRC Woollens Ranikhet. We are here to support army families and help them achieve financial independence through our rehabilitation project.', 29.6408, 79.4322, 'Monday - Friday 9am to 5pm', 'Saturday - 9am to 2pm', 'Sunday - Closed');

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'unread' COMMENT 'unread, read, replied',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

