-- =====================================================
-- MySmartSCart Database Optimization for 10,000+ Products
-- Run this SQL to optimize your database for speed
-- =====================================================

-- Add indexes for faster product queries
ALTER TABLE `products` ADD INDEX `idx_status` (`status`);
ALTER TABLE `products` ADD INDEX `idx_featured` (`featured`);
ALTER TABLE `products` ADD INDEX `idx_best_selling` (`best_selling`);
ALTER TABLE `products` ADD INDEX `idx_top_rated` (`top_rated`);
ALTER TABLE `products` ADD INDEX `idx_created_at` (`created_at`);
ALTER TABLE `products` ADD INDEX `idx_price` (`price`);
ALTER TABLE `products` ADD INDEX `idx_sale_price` (`sale_price`);
ALTER TABLE `products` ADD INDEX `idx_status_featured` (`status`, `featured`);
ALTER TABLE `products` ADD INDEX `idx_status_created` (`status`, `created_at`);
ALTER TABLE `products` ADD INDEX `idx_category_status` (`category_id`, `status`);

-- Composite index for common queries
ALTER TABLE `products` ADD INDEX `idx_listing` (`status`, `category_id`, `created_at`);

-- Add indexes for categories
ALTER TABLE `categories` ADD INDEX `idx_status` (`status`);
ALTER TABLE `categories` ADD INDEX `idx_parent` (`parent_id`);
ALTER TABLE `categories` ADD INDEX `idx_sort` (`sort_order`);
ALTER TABLE `categories` ADD INDEX `idx_status_parent` (`status`, `parent_id`);

-- Add indexes for product_categories junction table
ALTER TABLE `product_categories` ADD UNIQUE INDEX `idx_unique_product_category` (`product_id`, `category_id`);

-- Add indexes for orders
ALTER TABLE `orders` ADD INDEX `idx_user_id` (`user_id`);
ALTER TABLE `orders` ADD INDEX `idx_order_status` (`order_status`);
ALTER TABLE `orders` ADD INDEX `idx_created_at` (`created_at`);
ALTER TABLE `orders` ADD INDEX `idx_user_status` (`user_id`, `order_status`);

-- Add indexes for order_items
ALTER TABLE `order_items` ADD INDEX `idx_order_id` (`order_id`);
ALTER TABLE `order_items` ADD INDEX `idx_product_id` (`product_id`);

-- Add indexes for users
ALTER TABLE `users` ADD INDEX `idx_status` (`status`);
ALTER TABLE `users` ADD INDEX `idx_email_status` (`email`, `status`);

-- Add indexes for banners
ALTER TABLE `banners` ADD INDEX `idx_status_sort` (`status`, `sort_order`);

-- Optimize table storage
OPTIMIZE TABLE `products`;
OPTIMIZE TABLE `categories`;
OPTIMIZE TABLE `product_categories`;
OPTIMIZE TABLE `orders`;
OPTIMIZE TABLE `order_items`;
OPTIMIZE TABLE `users`;
OPTIMIZE TABLE `banners`;

-- =====================================================
-- Add thumbnail column for faster image loading
-- =====================================================
ALTER TABLE `products` ADD COLUMN `thumbnail` VARCHAR(255) DEFAULT NULL AFTER `image`;

-- =====================================================
-- Create cache table for storing computed data
-- =====================================================
CREATE TABLE IF NOT EXISTS `cache` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cache_key` VARCHAR(255) NOT NULL,
    `cache_value` LONGTEXT NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cache_key` (`cache_key`),
    KEY `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Create product views table for analytics
-- =====================================================
CREATE TABLE IF NOT EXISTS `product_views` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) NOT NULL,
    `view_count` INT(11) DEFAULT 0,
    `last_viewed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

