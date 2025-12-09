-- KRC Woollens Ranikhet - Actual Products
-- This file contains all 38 products with their prices

USE krcwoollen;

-- First, delete all existing products
DELETE FROM `product_categories`;
DELETE FROM `products`;

-- Insert Categories
INSERT INTO `categories` (`name`, `slug`, `image`, `status`, `sort_order`) VALUES
('Food Items', 'food-items', NULL, 1, 1),
('Ladies Wear', 'ladies-wear', NULL, 1, 2),
('Gents Wear', 'gents-wear', NULL, 1, 3),
('Tweed & Fabric', 'tweed-fabric', NULL, 1, 4),
('Accessories', 'accessories', NULL, 1, 5),
('Toys', 'toys', NULL, 1, 6)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Insert Products
-- Food Items
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Apple Cider Vinegar', 'apple-cider-vinegar', 'KRC-FOOD-001', 'Pure apple cider vinegar', 300.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Hand made soap', 'hand-made-soap', 'KRC-FOOD-002', 'Natural handmade soap', 130.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Apricot Oil', 'apricot-oil', 'KRC-FOOD-003', 'Pure apricot oil', 630.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Apricot Chutney', 'apricot-chutney', 'KRC-FOOD-004', 'Delicious apricot chutney', 180.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Plum Chutney', 'plum-chutney', 'KRC-FOOD-005', 'Tasty plum chutney', 180.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Himkhadya Gahat Dal', 'himkhadya-gahat-dal', 'KRC-FOOD-006', 'Organic Himkhadya Gahat Dal', 250.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Himkhadya Kidney Beans', 'himkhadya-kidney-beans', 'KRC-FOOD-007', 'Organic Himkhadya Kidney Beans', 280.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Himkahdya Kaala Bhat', 'himkahdya-kaala-bhat', 'KRC-FOOD-008', 'Organic Himkahdya Kaala Bhat', 170.00, 50, 'in_stock', 1, NULL, 1, NOW()),
('Plum Jam', 'plum-jam', 'KRC-FOOD-009', 'Homemade plum jam', 285.00, 50, 'in_stock', 1, NULL, 1, NOW());

-- Ladies Wear
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Ladies Cardigan', 'ladies-cardigan', 'KRC-LADIES-001', 'Warm ladies cardigan', 2500.00, 20, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Jacket', 'ladies-jacket', 'KRC-LADIES-002', 'Stylish ladies jacket', 2907.00, 20, 'in_stock', 2, NULL, 1, NOW()),
('Ladies S/L Coat', 'ladies-sl-coat', 'KRC-LADIES-003', 'Ladies short/long coat', 2566.00, 20, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Coat Superfine', 'ladies-coat-superfine', 'KRC-LADIES-004', 'Superfine quality ladies coat', 5176.00, 15, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Coat Almora', 'ladies-coat-almora', 'KRC-LADIES-005', 'Traditional Almora style ladies coat', 4362.00, 15, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Coat Kumaoni', 'ladies-coat-kumaoni', 'KRC-LADIES-006', 'Kumaoni style ladies coat', 4336.00, 15, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Long Coat Superfine', 'ladies-long-coat-superfine', 'KRC-LADIES-007', 'Long superfine ladies coat', 6127.00, 10, 'in_stock', 2, NULL, 1, NOW()),
('Ladies Long Coat Almora', 'ladies-long-coat-almora', 'KRC-LADIES-008', 'Long Almora style ladies coat', 5101.00, 10, 'in_stock', 2, NULL, 1, NOW());

-- Gents Wear
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Gents S/L Coat Superfine', 'gents-sl-coat-superfine', 'KRC-GENTS-001', 'Superfine gents short/long coat', 3084.00, 20, 'in_stock', 3, NULL, 1, NOW()),
('Gents S/L Coat Almora', 'gents-sl-coat-almora', 'KRC-GENTS-002', 'Almora style gents short/long coat', 2566.00, 20, 'in_stock', 3, NULL, 1, NOW()),
('Gents Coat Superfine', 'gents-coat-superfine', 'KRC-GENTS-003', 'Superfine quality gents coat', 5176.00, 15, 'in_stock', 3, NULL, 1, NOW()),
('Gents Coat Almora', 'gents-coat-almora', 'KRC-GENTS-004', 'Traditional Almora style gents coat', 4262.00, 15, 'in_stock', 3, NULL, 1, NOW()),
('Gents Coat Kumaoni', 'gents-coat-kumaoni', 'KRC-GENTS-005', 'Kumaoni style gents coat', 4236.00, 15, 'in_stock', 3, NULL, 1, NOW());

-- Tweed & Fabric
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Tweed Cloth Superfine', 'tweed-cloth-superfine', 'KRC-TWEED-001', 'Superfine quality tweed cloth', 3277.00, 25, 'in_stock', 4, NULL, 1, NOW()),
('Tweed Cloth Almora', 'tweed-cloth-almora', 'KRC-TWEED-002', 'Almora style tweed cloth', 2251.00, 25, 'in_stock', 4, NULL, 1, NOW()),
('Tweed 2 Mtr Superfine', 'tweed-2-mtr-superfine', 'KRC-TWEED-003', '2 meter superfine tweed', 1634.00, 30, 'in_stock', 4, NULL, 1, NOW()),
('Tweed 2 Mtr Almora', 'tweed-2-mtr-almora', 'KRC-TWEED-004', '2 meter Almora tweed', 1116.00, 30, 'in_stock', 4, NULL, 1, NOW()),
('Tweed 3.5 Mtr Almora', 'tweed-35-mtr-almora', 'KRC-TWEED-005', '3.5 meter Almora tweed', 1762.00, 25, 'in_stock', 4, NULL, 1, NOW()),
('Tweed 3.5 Mtr Superfine', 'tweed-35-mtr-superfine', 'KRC-TWEED-006', '3.5 meter superfine tweed', 2576.00, 25, 'in_stock', 4, NULL, 1, NOW());

-- Accessories
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Ponchu', 'ponchu', 'KRC-ACC-001', 'Traditional ponchu', 1470.00, 30, 'in_stock', 5, NULL, 1, NOW()),
('Gents Shawl Kumaoni', 'gents-shawl-kumaoni', 'KRC-ACC-002', 'Kumaoni style gents shawl', 1539.00, 25, 'in_stock', 5, NULL, 1, NOW()),
('Scarf Superfine', 'scarf-superfine', 'KRC-ACC-003', 'Superfine quality scarf', 688.00, 40, 'in_stock', 5, NULL, 1, NOW()),
('Shawl Bageshwari', 'shawl-bageshwari', 'KRC-ACC-004', 'Bageshwari style shawl', 1268.00, 25, 'in_stock', 5, NULL, 1, NOW()),
('Shawl Dharchuli', 'shawl-dharchuli', 'KRC-ACC-005', 'Dharchuli style shawl', 1457.00, 25, 'in_stock', 5, NULL, 1, NOW()),
('Shawl Kinari', 'shawl-kinari', 'KRC-ACC-006', 'Kinari style shawl', 1398.00, 25, 'in_stock', 5, NULL, 1, NOW()),
('Superfine Plain Shawl', 'superfine-plain-shawl', 'KRC-ACC-007', 'Plain superfine shawl', 1217.00, 30, 'in_stock', 5, NULL, 1, NOW()),
('Stole', 'stole', 'KRC-ACC-008', 'Elegant stole', 1105.00, 35, 'in_stock', 5, NULL, 1, NOW()),
('Muffler', 'muffler', 'KRC-ACC-009', 'Warm muffler', 1500.00, 40, 'in_stock', 5, NULL, 1, NOW());

-- Toys
INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `image`, `status`, `created_at`) VALUES
('Woollen Toy', 'woollen-toy', 'KRC-TOY-001', 'Handmade woollen toy', 580.00, 50, 'in_stock', 6, NULL, 1, NOW());

-- Link products to categories using product_categories table
-- Get category IDs
SET @food_id = (SELECT id FROM categories WHERE slug = 'food-items');
SET @ladies_id = (SELECT id FROM categories WHERE slug = 'ladies-wear');
SET @gents_id = (SELECT id FROM categories WHERE slug = 'gents-wear');
SET @tweed_id = (SELECT id FROM categories WHERE slug = 'tweed-fabric');
SET @acc_id = (SELECT id FROM categories WHERE slug = 'accessories');
SET @toy_id = (SELECT id FROM categories WHERE slug = 'toys');

-- Link Food Items
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @food_id FROM products WHERE category_id = 1;

-- Link Ladies Wear
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @ladies_id FROM products WHERE category_id = 2;

-- Link Gents Wear
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @gents_id FROM products WHERE category_id = 3;

-- Link Tweed & Fabric
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @tweed_id FROM products WHERE category_id = 4;

-- Link Accessories
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @acc_id FROM products WHERE category_id = 5;

-- Link Toys
INSERT INTO `product_categories` (`product_id`, `category_id`)
SELECT id, @toy_id FROM products WHERE category_id = 6;

