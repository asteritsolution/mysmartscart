-- Orders Table for KRC Woollens
-- This table stores order information

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(50) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `company` VARCHAR(255) DEFAULT NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `postcode` VARCHAR(20) NOT NULL,
  `country` VARCHAR(100) DEFAULT 'India',
  `subtotal` DECIMAL(10,2) NOT NULL,
  `shipping_cost` DECIMAL(10,2) DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT 'cod',
  `payment_status` VARCHAR(20) DEFAULT 'pending' COMMENT 'pending, paid, failed',
  `order_status` VARCHAR(20) DEFAULT 'pending' COMMENT 'pending, processing, shipped, delivered, cancelled',
  `order_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  KEY `order_status` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `product_sku` VARCHAR(100) DEFAULT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

