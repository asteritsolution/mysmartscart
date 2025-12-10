<?php
include 'config.php';

echo "<!DOCTYPE html><html><head><title>Add Colors & Sizes Columns</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
echo ".success{color:green;padding:10px;background:#d4edda;border:1px solid #c3e6cb;border-radius:5px;margin:10px 0;}";
echo ".error{color:red;padding:10px;background:#f8d7da;border:1px solid #f5c6cb;border-radius:5px;margin:10px 0;}";
echo ".info{color:blue;padding:10px;background:#d1ecf1;border:1px solid #bee5eb;border-radius:5px;margin:10px 0;}";
echo "h2{color:#333;} ul{line-height:1.8;}</style></head><body>";

echo "<h2>Adding Colors and Sizes Columns to Products Table</h2>";

// Check if colors column exists
$check_colors = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'colors'");
if (mysqli_num_rows($check_colors) == 0) {
    $sql = "ALTER TABLE products ADD COLUMN colors TEXT DEFAULT NULL COMMENT 'JSON array of available colors' AFTER gallery_images";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='success'>✓ Colors column added successfully</div>";
    } else {
        echo "<div class='error'>✗ Error adding colors column: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='info'>ℹ Colors column already exists</div>";
}

// Check if sizes column exists
$check_sizes = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'sizes'");
if (mysqli_num_rows($check_sizes) == 0) {
    $sql = "ALTER TABLE products ADD COLUMN sizes TEXT DEFAULT NULL COMMENT 'JSON array of available sizes' AFTER colors";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='success'>✓ Sizes column added successfully</div>";
    } else {
        echo "<div class='error'>✗ Error adding sizes column: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='info'>ℹ Sizes column already exists</div>";
}

echo "<hr>";
echo "<h3>✅ Setup Complete!</h3>";
echo "<p><strong>How to use:</strong></p>";
echo "<ul>";
echo "<li>Go to <a href='adminpanel/products.php' target='_blank'>Admin Panel → Products</a></li>";
echo "<li>Add or Edit any product</li>";
echo "<li>In the <strong>Available Colors</strong> field, enter: <code>Black, Blue, Red, Green</code> (comma-separated)</li>";
echo "<li>In the <strong>Available Sizes</strong> field, enter: <code>Small, Medium, Large, XL</code> (comma-separated)</li>";
echo "<li>Save the product</li>";
echo "<li>On the product page, colors and sizes will only show if you added them!</li>";
echo "</ul>";

echo "<p><strong>Note:</strong> If you leave colors/sizes empty, they won't show on the product page.</p>";

echo "<p><a href='adminpanel/products.php' style='display:inline-block;padding:10px 20px;background:#1a237e;color:white;text-decoration:none;border-radius:5px;margin-top:20px;'>Go to Products</a></p>";

echo "</body></html>";
?>

