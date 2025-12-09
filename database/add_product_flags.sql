-- Migration script to add best_selling and top_rated fields to products table
-- Run this if you already have a database and want to add these fields

USE krcwoollen;

-- Add best_selling column
ALTER TABLE `products` 
ADD COLUMN `best_selling` tinyint(1) DEFAULT 0 COMMENT '1=Best Selling Product' AFTER `featured`;

-- Add top_rated column
ALTER TABLE `products` 
ADD COLUMN `top_rated` tinyint(1) DEFAULT 0 COMMENT '1=Top Rated Product' AFTER `best_selling`;

-- Update existing products (optional - you can set these manually via admin panel)
-- Example: Set some products as best selling
-- UPDATE products SET best_selling = 1 WHERE id IN (1, 2, 3);
-- UPDATE products SET top_rated = 1 WHERE id IN (4, 5, 6);

