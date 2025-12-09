-- Quick script to delete all old products
-- Run this in phpMyAdmin SQL tab

USE krcwoollen;

-- Delete all products and their category links
DELETE FROM `product_categories`;
DELETE FROM `products`;

-- After running this, run update_products.php or krc_products.sql to add new products

