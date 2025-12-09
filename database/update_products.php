<?php
/**
 * KRC Woollens Products Update Script
 * Run this file to replace all existing products with the actual KRC Woollens products
 * Make sure to backup your database before running this!
 */

include '../config.php';

echo "<h2>KRC Woollens Products Update</h2>";
echo "<p>This script will delete all existing products and add the new KRC Woollens products.</p>";

// Delete existing products
mysqli_query($conn, "DELETE FROM `product_categories`");
mysqli_query($conn, "DELETE FROM `products`");
echo "<p style='color:green;'>✓ Existing products deleted</p>";

// Insert/Update Categories
$categories = [
    ['Food Items', 'food-items', NULL, 1, 1],
    ['Ladies Wear', 'ladies-wear', NULL, 1, 2],
    ['Gents Wear', 'gents-wear', NULL, 1, 3],
    ['Tweed & Fabric', 'tweed-fabric', NULL, 1, 4],
    ['Accessories', 'accessories', NULL, 1, 5],
    ['Toys', 'toys', NULL, 1, 6]
];

foreach ($categories as $cat) {
    $name = mysqli_real_escape_string($conn, $cat[0]);
    $slug = mysqli_real_escape_string($conn, $cat[1]);
    $image = $cat[2] ? "'" . mysqli_real_escape_string($conn, $cat[2]) . "'" : 'NULL';
    
    // Check if category exists
    $check = mysqli_query($conn, "SELECT id FROM categories WHERE slug = '$slug'");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE `categories` SET `name`='$name', `status`={$cat[3]}, `sort_order`={$cat[4]} WHERE slug='$slug'";
    } else {
        $sql = "INSERT INTO `categories` (`name`, `slug`, `image`, `status`, `sort_order`) 
                VALUES ('$name', '$slug', $image, {$cat[3]}, {$cat[4]})";
    }
    mysqli_query($conn, $sql);
}
echo "<p style='color:green;'>✓ Categories updated</p>";

// Get category IDs
$cat_ids = [];
$result = mysqli_query($conn, "SELECT id, slug FROM categories");
while ($row = mysqli_fetch_assoc($result)) {
    $cat_ids[$row['slug']] = $row['id'];
}

// Insert Products (38 products)
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

$count = 0;
foreach ($products as $product) {
    $name = mysqli_real_escape_string($conn, $product[0]);
    $slug = mysqli_real_escape_string($conn, $product[1]);
    $sku = mysqli_real_escape_string($conn, $product[2]);
    $desc = mysqli_real_escape_string($conn, $product[3]);
    $price = $product[4];
    $cat_id = $product[5];
    
    $sql = "INSERT INTO `products` (`name`, `slug`, `sku`, `short_description`, `price`, `stock`, `stock_status`, `category_id`, `status`) 
            VALUES ('$name', '$slug', '$sku', '$desc', $price, 50, 'in_stock', $cat_id, 1)";
    
    if (mysqli_query($conn, $sql)) {
        $product_id = mysqli_insert_id($conn);
        if ($product_id) {
            $sql_link = "INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES ($product_id, $cat_id)";
            mysqli_query($conn, $sql_link);
            $count++;
        }
    }
}

echo "<p style='color:green;'>✓ Successfully inserted $count products</p>";
echo "<hr>";
echo "<h3 style='color:green;'>✓ Products update completed successfully!</h3>";
echo "<p><strong>Note:</strong> Images need to be added through admin panel. All products are ready with names, prices, and descriptions.</p>";
echo "<p><a href='../index.php'>Go to Homepage</a> | <a href='../shop.php'>View Shop</a></p>";
?>

