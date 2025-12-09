<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Products';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    mysqli_query($conn, "DELETE FROM product_categories WHERE product_id = $id");
    header("Location: products.php?deleted=1");
    exit;
}

// Get all products
$products_query = "SELECT p.*, c.name as category_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   ORDER BY p.id DESC";
$products_result = mysqli_query($conn, $products_query);
$products = [];
while ($row = mysqli_fetch_assoc($products_result)) {
    $products[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Product deleted successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-box"></i> Products Management</h5>
        <a href="product-add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="9" class="text-center">No products found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <img src="../<?php echo htmlspecialchars($product['image'] ?: 'assets/images/products/placeholder.webp'); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                    <td><strong><?php echo formatCurrency($product['price']); ?></strong></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td><?php echo $product['status'] == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
                    <td>
                        <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=1&id=<?php echo $product['id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

