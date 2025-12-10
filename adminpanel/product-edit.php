<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Edit Product';

$error = '';
$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    header("Location: products.php");
    exit;
}

// Get product
$product_query = "SELECT * FROM products WHERE id = $id LIMIT 1";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    header("Location: products.php");
    exit;
}

// Get categories
$categories_query = "SELECT * FROM categories WHERE status = 1 ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($row = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $slug = mysqli_real_escape_string($conn, strtolower(str_replace(' ', '-', $_POST['name'] ?? '')));
    $sku = mysqli_real_escape_string($conn, $_POST['sku'] ?? '');
    $short_description = mysqli_real_escape_string($conn, $_POST['short_description'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $sale_price = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
    $stock = intval($_POST['stock'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $best_selling = isset($_POST['best_selling']) ? 1 : 0;
    $top_rated = isset($_POST['top_rated']) ? 1 : 0;
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Handle colors and sizes (comma-separated values)
    $colors_array = [];
    if (!empty($_POST['colors'])) {
        $colors_input = trim($_POST['colors']);
        $colors_array = array_filter(array_map('trim', explode(',', $colors_input)));
    }
    $colors_json = !empty($colors_array) ? json_encode($colors_array) : null;
    
    $sizes_array = [];
    if (!empty($_POST['sizes'])) {
        $sizes_input = trim($_POST['sizes']);
        $sizes_array = array_filter(array_map('trim', explode(',', $sizes_input)));
    }
    $sizes_json = !empty($sizes_array) ? json_encode($sizes_array) : null;
    
    $image = $product['image'];
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/products/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'assets/images/products/' . $file_name;
        }
    }
    
    if (!empty($name) && $price > 0) {
        $sale_price_sql = $sale_price ? $sale_price : 'NULL';
        $colors_sql = $colors_json ? "'" . mysqli_real_escape_string($conn, $colors_json) . "'" : 'NULL';
        $sizes_sql = $sizes_json ? "'" . mysqli_real_escape_string($conn, $sizes_json) . "'" : 'NULL';
        
        $query = "UPDATE products SET 
                  name = '$name', 
                  slug = '$slug', 
                  sku = '$sku', 
                  short_description = '$short_description', 
                  description = '$description', 
                  price = $price, 
                  sale_price = $sale_price_sql, 
                  stock = $stock, 
                  category_id = $category_id, 
                  image = '$image', 
                  colors = $colors_sql,
                  sizes = $sizes_sql,
                  featured = $featured, 
                  best_selling = $best_selling, 
                  top_rated = $top_rated, 
                  status = $status 
                  WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            // Update category link
            mysqli_query($conn, "DELETE FROM product_categories WHERE product_id = $id");
            if ($category_id > 0) {
                mysqli_query($conn, "INSERT INTO product_categories (product_id, category_id) VALUES ($id, $category_id)");
            }
            
            header("Location: products.php?updated=1");
            exit;
        } else {
            $error = 'Error updating product: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Please fill all required fields!';
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-edit"></i> Edit Product</h5>
        <a href="products.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" class="form-control" name="sku" value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Short Description</label>
                    <textarea class="form-control" name="short_description" rows="3"><?php echo htmlspecialchars($product['short_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="5"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $product['price']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sale Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="sale_price" value="<?php echo $product['sale_price'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" class="form-control" name="stock" value="<?php echo $product['stock']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Available Colors</label>
                            <?php 
                            $current_colors = [];
                            if (!empty($product['colors'])) {
                                $colors_json = json_decode($product['colors'], true);
                                if (is_array($colors_json)) {
                                    $current_colors = $colors_json;
                                }
                            }
                            ?>
                            <input type="text" class="form-control" name="colors" 
                                   value="<?php echo htmlspecialchars(implode(', ', $current_colors)); ?>" 
                                   placeholder="e.g., Black, Blue, Red, Green">
                            <small class="form-text text-muted">Enter colors separated by commas. Leave empty if product has no color options.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Available Sizes</label>
                            <?php 
                            $current_sizes = [];
                            if (!empty($product['sizes'])) {
                                $sizes_json = json_decode($product['sizes'], true);
                                if (is_array($sizes_json)) {
                                    $current_sizes = $sizes_json;
                                }
                            }
                            ?>
                            <input type="text" class="form-control" name="sizes" 
                                   value="<?php echo htmlspecialchars(implode(', ', $current_sizes)); ?>" 
                                   placeholder="e.g., Small, Medium, Large, XL">
                            <small class="form-text text-muted">Enter sizes separated by commas. Leave empty if product has no size options.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="../<?php echo htmlspecialchars($product['image'] ?: 'assets/images/products/placeholder.webp'); ?>" 
                         alt="Product Image" 
                         style="width: 100%; max-width: 200px; border-radius: 5px; margin-bottom: 10px;">
                    <input type="file" class="form-control-file" name="image" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured" <?php echo $product['featured'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featured">Featured Product</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="best_selling" id="best_selling" <?php echo $product['best_selling'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="best_selling">Best Selling</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="top_rated" id="top_rated" <?php echo $product['top_rated'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="top_rated">Top Rated</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo $product['status'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

