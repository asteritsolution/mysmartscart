-- Cleanup SQL - Run this first if you get tablespace errors
-- This will properly remove tables and their tablespaces

USE krcwoollen;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `banners`;

SET FOREIGN_KEY_CHECKS = 1;

-- After running this, you can run database.sql to create fresh tables

