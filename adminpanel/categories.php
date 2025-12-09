<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Categories';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    mysqli_query($conn, "DELETE FROM product_categories WHERE category_id = $id");
    header("Location: categories.php?deleted=1");
    exit;
}

// Get all categories
$categories_query = "SELECT c.*, COUNT(p.id) as product_count 
                    FROM categories c 
                    LEFT JOIN products p ON c.id = p.category_id 
                    GROUP BY c.id 
                    ORDER BY c.sort_order, c.name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($row = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Category deleted successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-tags"></i> Categories Management</h5>
        <a href="category-add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="7" class="text-center">No categories found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                    <td><code><?php echo htmlspecialchars($category['slug']); ?></code></td>
                    <td><span class="badge badge-info"><?php echo $category['product_count']; ?></span></td>
                    <td><?php echo $category['sort_order']; ?></td>
                    <td><?php echo $category['status'] == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
                    <td>
                        <a href="category-edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=1&id=<?php echo $category['id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this category?')">
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

