<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Add Product';

$error = '';
$success = '';

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
    
    // Handle image upload
    $image = 'assets/images/products/placeholder.webp';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/products/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'assets/images/products/' . $file_name;
        }
    }
    
    if (!empty($name) && $price > 0) {
        $query = "INSERT INTO products (name, slug, sku, short_description, description, price, sale_price, stock, category_id, image, featured, best_selling, top_rated, status) 
                  VALUES ('$name', '$slug', '$sku', '$short_description', '$description', $price, " . ($sale_price ? $sale_price : 'NULL') . ", $stock, $category_id, '$image', $featured, $best_selling, $top_rated, $status)";
        
        if (mysqli_query($conn, $query)) {
            $product_id = mysqli_insert_id($conn);
            
            // Link to category
            if ($category_id > 0) {
                mysqli_query($conn, "INSERT INTO product_categories (product_id, category_id) VALUES ($product_id, $category_id)");
            }
            
            header("Location: products.php?added=1");
            exit;
        } else {
            $error = 'Error adding product: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Please fill all required fields!';
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-plus"></i> Add New Product</h5>
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
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" class="form-control" name="sku">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Short Description</label>
                    <textarea class="form-control" name="short_description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="5"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sale Price (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="sale_price">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" class="form-control" name="stock" value="0">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" class="form-control-file" name="image" accept="image/*">
                    <small class="form-text text-muted">Leave empty to use placeholder</small>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured">
                        <label class="form-check-label" for="featured">Featured Product</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="best_selling" id="best_selling">
                        <label class="form-check-label" for="best_selling">Best Selling</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="top_rated" id="top_rated">
                        <label class="form-check-label" for="top_rated">Top Rated</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Product
            </button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

