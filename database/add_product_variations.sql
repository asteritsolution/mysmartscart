-- Add color and size fields to products table
-- These will store JSON arrays of available colors and sizes

USE mysmartscart;

-- Add colors field (JSON array of color names)
ALTER TABLE `products` 
ADD COLUMN `colors` TEXT DEFAULT NULL COMMENT 'JSON array of available colors' AFTER `gallery_images`;

-- Add sizes field (JSON array of size names)
ALTER TABLE `products` 
ADD COLUMN `sizes` TEXT DEFAULT NULL COMMENT 'JSON array of available sizes' AFTER `colors`;

-- Example of how data will be stored:
-- colors: '["Black", "Blue", "Red", "Green"]'
-- sizes: '["Small", "Medium", "Large", "XL"]'

