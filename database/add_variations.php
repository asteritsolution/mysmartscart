<?php
include '../config.php';

echo "<h2>Adding Colors and Sizes Columns to Products Table</h2>";

// Check if colors column exists
$check_colors = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'colors'");
if (mysqli_num_rows($check_colors) == 0) {
    $sql = "ALTER TABLE products ADD COLUMN colors TEXT DEFAULT NULL COMMENT 'JSON array of available colors' AFTER gallery_images";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>✓ Colors column added successfully</p>";
    } else {
        echo "<p style='color:red;'>✗ Error adding colors column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ Colors column already exists</p>";
}

// Check if sizes column exists
$check_sizes = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'sizes'");
if (mysqli_num_rows($check_sizes) == 0) {
    $sql = "ALTER TABLE products ADD COLUMN sizes TEXT DEFAULT NULL COMMENT 'JSON array of available sizes' AFTER colors";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>✓ Sizes column added successfully</p>";
    } else {
        echo "<p style='color:red;'>✗ Error adding sizes column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ Sizes column already exists</p>";
}

echo "<hr>";
echo "<h3>Example Usage:</h3>";
echo "<p>In admin panel, when adding/editing a product:</p>";
echo "<ul>";
echo "<li><strong>Colors:</strong> Enter: <code>Black, Blue, Red, Green</code></li>";
echo "<li><strong>Sizes:</strong> Enter: <code>Small, Medium, Large, XL</code></li>";
echo "</ul>";
echo "<p>Colors and sizes will only show on product page if they are added from backend.</p>";
echo "<p><a href='../adminpanel/products.php'>Go to Products</a></p>";
?>

