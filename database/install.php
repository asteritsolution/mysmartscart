<?php
// Database Installation Script
// Run this file once to setup your database

// Database Configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'krcwoollen';

// Connect to MySQL server (without selecting database)
$conn = mysqli_connect($db_host, $db_user, $db_pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>KRC Woollen Database Installation</h2>";
echo "<p>Setting up database...</p>";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Database created/selected successfully</p>";
} else {
    die("<p style='color:red;'>Error creating database: " . mysqli_error($conn) . "</p>");
}

// Select database
mysqli_select_db($conn, $db_name);

// Disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Function to safely drop table with tablespace handling
function safeDropTable($conn, $table) {
    // Check if table exists
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        // Try to discard tablespace first (may fail if table doesn't use tablespace)
        $discard = "ALTER TABLE `$table` DISCARD TABLESPACE";
        @mysqli_query($conn, $discard); // Suppress error if it fails
        
        // Now try to drop the table
        $sql = "DROP TABLE `$table`";
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            // If drop fails due to tablespace, try renaming first then dropping
            $tempName = $table . '_old_' . time();
            $rename = "RENAME TABLE `$table` TO `$tempName`";
            if (mysqli_query($conn, $rename)) {
                $sql = "DROP TABLE `$tempName`";
                if (mysqli_query($conn, $sql)) {
                    return true;
                }
            }
            return false;
        }
    }
    return true; // Table doesn't exist, so it's already "dropped"
}

// Drop existing tables with tablespace handling
$tables = ['order_items', 'orders', 'product_categories', 'products', 'categories', 'banners', 'users'];
foreach ($tables as $table) {
    if (safeDropTable($conn, $table)) {
        echo "<p style='color:green;'>✓ Dropped table: $table</p>";
    } else {
        echo "<p style='color:orange;'>⚠ Could not drop table $table: " . mysqli_error($conn) . "</p>";
        // Try one more time with simple drop
        $sql = "DROP TABLE IF EXISTS `$table`";
        mysqli_query($conn, $sql);
    }
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// Create banners table
$sql = "CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'banners' created successfully</p>";
} else {
    $error = mysqli_error($conn);
    // If tablespace error, try workaround
    if (strpos($error, 'Tablespace') !== false) {
        echo "<p style='color:orange;'>⚠ Tablespace issue detected. Trying workaround...</p>";
        // Try creating with different name then renaming
        $tempName = 'banners_temp_' . time();
        $sqlTemp = str_replace('`banners`', "`$tempName`", $sql);
        if (mysqli_query($conn, $sqlTemp)) {
            // Drop old problematic table forcefully
            mysqli_query($conn, "DROP TABLE IF EXISTS `banners`");
            // Rename temp to actual
            mysqli_query($conn, "RENAME TABLE `$tempName` TO `banners`");
            echo "<p style='color:green;'>✓ Table 'banners' created successfully (using workaround)</p>";
        } else {
            die("<p style='color:red;'>Error creating banners table: " . mysqli_error($conn) . "</p>");
        }
    } else {
        die("<p style='color:red;'>Error creating banners table: " . $error . "</p>");
    }
}

// Insert sample banner data
$sql = "INSERT INTO `banners` (`title`, `image`, `link`, `status`, `sort_order`) VALUES
('Banner 1', 'assets/images/demoes/demo7/banners/banner-1.jpg', 'shop.php', 1, 1),
('Banner 2', 'assets/images/demoes/demo7/banners/banner-1.jpg', 'shop.php', 1, 2),
('Banner 3', 'assets/images/demoes/demo7/banners/banner-1.jpg', 'shop.php', 1, 3)";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Sample banner data inserted</p>";
} else {
    echo "<p style='color:orange;'>⚠ Could not insert banner data: " . mysqli_error($conn) . "</p>";
}

// Create categories table
$sql = "CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0 COMMENT '0 for main category',
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'categories' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating categories table: " . mysqli_error($conn) . "</p>");
}

// Create products table
$sql = "CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `stock_status` varchar(20) DEFAULT 'in_stock',
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL COMMENT 'JSON array of image paths',
  `featured` tinyint(1) DEFAULT 0 COMMENT '1=Featured Product',
  `best_selling` tinyint(1) DEFAULT 0 COMMENT '1=Best Selling Product',
  `top_rated` tinyint(1) DEFAULT 0 COMMENT '1=Top Rated Product',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'products' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating products table: " . mysqli_error($conn) . "</p>");
}

// Create product_categories table
$sql = "CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'product_categories' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating product_categories table: " . mysqli_error($conn) . "</p>");
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address_line1` VARCHAR(255) DEFAULT NULL,
  `address_line2` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `postcode` VARCHAR(20) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT 'India',
  `status` TINYINT(1) DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'users' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating users table: " . mysqli_error($conn) . "</p>");
}

// Insert default admin user (password: admin123)
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_email = 'admin@krcwoollens.com';
$check_admin = mysqli_query($conn, "SELECT id FROM users WHERE email = '$admin_email'");
if (mysqli_num_rows($check_admin) == 0) {
    $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `password`, `status`) 
            VALUES ('Admin', 'User', '$admin_email', '9876543210', '$admin_password', 1)";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>✓ Default admin user created (email: admin@krcwoollens.com, password: admin123)</p>";
    }
}

// Create orders table
$sql = "CREATE TABLE IF NOT EXISTS `orders` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'orders' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating orders table: " . mysqli_error($conn) . "</p>");
}

// Create order_items table
$sql = "CREATE TABLE IF NOT EXISTS `order_items` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "<p style='color:green;'>✓ Table 'order_items' created successfully</p>";
} else {
    die("<p style='color:red;'>Error creating order_items table: " . mysqli_error($conn) . "</p>");
}

// Delete existing products and categories links first
mysqli_query($conn, "DELETE FROM `product_categories`");
mysqli_query($conn, "DELETE FROM `products`");
echo "<p style='color:green;'>✓ Existing products removed</p>";

// Insert KRC Woollens Categories
$categories = [
    ['Food Items', 'food-items', NULL, 1, 1],
    ['Ladies Wear', 'ladies-wear', NULL, 1, 2],
    ['Gents Wear', 'gents-wear', NULL, 1, 3],
    ['Tweed & Fabric', 'tweed-fabric', NULL, 1, 4],
    ['Accessories', 'accessories', NULL, 1, 5],
    ['Toys', 'toys', NULL, 1, 6]
];

// Delete old categories first (optional - comment out if you want to keep old categories)
// mysqli_query($conn, "DELETE FROM `categories` WHERE slug NOT IN ('food-items', 'ladies-wear', 'gents-wear', 'tweed-fabric', 'accessories', 'toys')");

foreach ($categories as $cat) {
    $name = mysqli_real_escape_string($conn, $cat[0]);
    $slug = mysqli_real_escape_string($conn, $cat[1]);
    $image = $cat[2] ? "'" . mysqli_real_escape_string($conn, $cat[2]) . "'" : 'NULL';
    
    // Check if category exists, if yes update, if no insert
    $check = mysqli_query($conn, "SELECT id FROM categories WHERE slug = '$slug'");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE `categories` SET `name`='$name', `status`={$cat[3]}, `sort_order`={$cat[4]} WHERE slug='$slug'";
    } else {
        $sql = "INSERT INTO `categories` (`name`, `slug`, `image`, `status`, `sort_order`) 
                VALUES ('$name', '$slug', $image, {$cat[3]}, {$cat[4]})";
    }
    @mysqli_query($conn, $sql);
}
echo "<p style='color:green;'>✓ KRC Woollens categories inserted/updated</p>";

// Get category IDs
$cat_ids = [];
$result = mysqli_query($conn, "SELECT id, slug FROM categories");
while ($row = mysqli_fetch_assoc($result)) {
    $cat_ids[$row['slug']] = $row['id'];
}

// Insert KRC Woollens Products (38 products)
$products = [
    // Food Items (9 products)
    ['Apple Cider Vinegar', 'apple-cider-vinegar', 'KRC-FOOD-001', 'Pure apple cider vinegar', 300.00, $cat_ids['food-items']],
    ['Hand made soap', 'hand-made-soap', 'KRC-FOOD-002', 'Natural handmade soap', 130.00, $cat_ids['food-items']],
    ['Apricot Oil', 'apricot-oil', 'KRC-FOOD-003', 'Pure apricot oil', 630.00, $cat_ids['food-items']],
    ['Apricot Chutney', 'apricot-chutney', 'KRC-FOOD-004', 'Delicious apricot chutney', 180.00, $cat_ids['food-items']],
    ['Plum Chutney', 'plum-chutney', 'KRC-FOOD-005', 'Tasty plum chutney', 180.00, $cat_ids['food-items']],
    ['Himkhadya Gahat Dal', 'himkhadya-gahat-dal', 'KRC-FOOD-006', 'Organic Himkhadya Gahat Dal', 250.00, $cat_ids['food-items']],
    ['Himkhadya Kidney Beans', 'himkhadya-kidney-beans', 'KRC-FOOD-007', 'Organic Himkhadya Kidney Beans', 280.00, $cat_ids['food-items']],
    ['Himkahdya Kaala Bhat', 'himkahdya-kaala-bhat', 'KRC-FOOD-008', 'Organic Himkahdya Kaala Bhat', 170.00, $cat_ids['food-items']],
    ['Plum Jam', 'plum-jam', 'KRC-FOOD-009', 'Homemade plum jam', 285.00, $cat_ids['food-items']],
    
    // Ladies Wear (8 products)
    ['Ladies Cardigan', 'ladies-cardigan', 'KRC-LADIES-001', 'Warm ladies cardigan', 2500.00, $cat_ids['ladies-wear']],
    ['Ladies Jacket', 'ladies-jacket', 'KRC-LADIES-002', 'Stylish ladies jacket', 2907.00, $cat_ids['ladies-wear']],
    ['Ladies S/L Coat', 'ladies-sl-coat', 'KRC-LADIES-003', 'Ladies short/long coat', 2566.00, $cat_ids['ladies-wear']],
    ['Ladies Coat Superfine', 'ladies-coat-superfine', 'KRC-LADIES-004', 'Superfine quality ladies coat', 5176.00, $cat_ids['ladies-wear']],
    ['Ladies Coat Almora', 'ladies-coat-almora', 'KRC-LADIES-005', 'Traditional Almora style ladies coat', 4362.00, $cat_ids['ladies-wear']],
    ['Ladies Coat Kumaoni', 'ladies-coat-kumaoni', 'KRC-LADIES-006', 'Kumaoni style ladies coat', 4336.00, $cat_ids['ladies-wear']],
    ['Ladies Long Coat Superfine', 'ladies-long-coat-superfine', 'KRC-LADIES-007', 'Long superfine ladies coat', 6127.00, $cat_ids['ladies-wear']],
    ['Ladies Long Coat Almora', 'ladies-long-coat-almora', 'KRC-LADIES-008', 'Long Almora style ladies coat', 5101.00, $cat_ids['ladies-wear']],
    
    // Gents Wear (5 products)
    ['Gents S/L Coat Superfine', 'gents-sl-coat-superfine', 'KRC-GENTS-001', 'Superfine gents short/long coat', 3084.00, $cat_ids['gents-wear']],
    ['Gents S/L Coat Almora', 'gents-sl-coat-almora', 'KRC-GENTS-002', 'Almora style gents short/long coat', 2566.00, $cat_ids['gents-wear']],
    ['Gents Coat Superfine', 'gents-coat-superfine', 'KRC-GENTS-003', 'Superfine quality gents coat', 5176.00, $cat_ids['gents-wear']],
    ['Gents Coat Almora', 'gents-coat-almora', 'KRC-GENTS-004', 'Traditional Almora style gents coat', 4262.00, $cat_ids['gents-wear']],
    ['Gents Coat Kumaoni', 'gents-coat-kumaoni', 'KRC-GENTS-005', 'Kumaoni style gents coat', 4236.00, $cat_ids['gents-wear']],
    
    // Tweed & Fabric (6 products)
    ['Tweed Cloth Superfine', 'tweed-cloth-superfine', 'KRC-TWEED-001', 'Superfine quality tweed cloth', 3277.00, $cat_ids['tweed-fabric']],
    ['Tweed Cloth Almora', 'tweed-cloth-almora', 'KRC-TWEED-002', 'Almora style tweed cloth', 2251.00, $cat_ids['tweed-fabric']],
    ['Tweed 2 Mtr Superfine', 'tweed-2-mtr-superfine', 'KRC-TWEED-003', '2 meter superfine tweed', 1634.00, $cat_ids['tweed-fabric']],
    ['Tweed 2 Mtr Almora', 'tweed-2-mtr-almora', 'KRC-TWEED-004', '2 meter Almora tweed', 1116.00, $cat_ids['tweed-fabric']],
    ['Tweed 3.5 Mtr Almora', 'tweed-35-mtr-almora', 'KRC-TWEED-005', '3.5 meter Almora tweed', 1762.00, $cat_ids['tweed-fabric']],
    ['Tweed 3.5 Mtr Superfine', 'tweed-35-mtr-superfine', 'KRC-TWEED-006', '3.5 meter superfine tweed', 2576.00, $cat_ids['tweed-fabric']],
    
    // Accessories (9 products)
    ['Ponchu', 'ponchu', 'KRC-ACC-001', 'Traditional ponchu', 1470.00, $cat_ids['accessories']],
    ['Gents Shawl Kumaoni', 'gents-shawl-kumaoni', 'KRC-ACC-002', 'Kumaoni style gents shawl', 1539.00, $cat_ids['accessories']],
    ['Scarf Superfine', 'scarf-superfine', 'KRC-ACC-003', 'Superfine quality scarf', 688.00, $cat_ids['accessories']],
    ['Shawl Bageshwari', 'shawl-bageshwari', 'KRC-ACC-004', 'Bageshwari style shawl', 1268.00, $cat_ids['accessories']],
    ['Shawl Dharchuli', 'shawl-dharchuli', 'KRC-ACC-005', 'Dharchuli style shawl', 1457.00, $cat_ids['accessories']],
    ['Shawl Kinari', 'shawl-kinari', 'KRC-ACC-006', 'Kinari style shawl', 1398.00, $cat_ids['accessories']],
    ['Superfine Plain Shawl', 'superfine-plain-shawl', 'KRC-ACC-007', 'Plain superfine shawl', 1217.00, $cat_ids['accessories']],
    ['Stole', 'stole', 'KRC-ACC-008', 'Elegant stole', 1105.00, $cat_ids['accessories']],
    ['Muffler', 'muffler', 'KRC-ACC-009', 'Warm muffler', 1500.00, $cat_ids['accessories']],
    
    // Toys (1 product)
    ['Woollen Toy', 'woollen-toy', 'KRC-TOY-001', 'Handmade woollen toy', 580.00, $cat_ids['toys']]
];

foreach ($products as $product) {
    $name = mysqli_real_escape_string($conn, $product[0]);
    $slug = mysqli_real_escape_string($conn, $product[1]);
    $sku = mysqli_real_escape_string($conn, $product[2]);
    $desc = mysqli_real_escape_string($conn, $product[3]);
    $price = $product[4];
    $cat_id = $product[5];
    
    $sql = "INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `status`) 
            VALUES ('$name', '$slug', '$sku', '$desc', $price, 50, 'in_stock', $cat_id, 1)";
    @mysqli_query($conn, $sql);
    
    // Link to product_categories
    $product_id = mysqli_insert_id($conn);
    if ($product_id) {
        $sql_link = "INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES ($product_id, $cat_id)";
        @mysqli_query($conn, $sql_link);
    }
}
echo "<p style='color:green;'>✓ All 38 KRC Woollens products inserted successfully</p>";

mysqli_close($conn);

echo "<hr>";
echo "<h3 style='color:green;'>✓ Database installation completed successfully!</h3>";
echo "<p>You can now use the website. Delete this file (install.php) for security.</p>";
?>

