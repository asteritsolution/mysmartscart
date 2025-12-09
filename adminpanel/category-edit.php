<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Edit Category';

$error = '';
$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    header("Location: categories.php");
    exit;
}

// Get category
$category_query = "SELECT * FROM categories WHERE id = $id LIMIT 1";
$category_result = mysqli_query($conn, $category_query);
$category = mysqli_fetch_assoc($category_result);

if (!$category) {
    header("Location: categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $slug = mysqli_real_escape_string($conn, strtolower(str_replace(' ', '-', $_POST['slug'] ?? $name)));
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    
    if (!empty($name)) {
        $query = "UPDATE categories SET 
                  name = '$name', 
                  slug = '$slug', 
                  description = '$description', 
                  sort_order = $sort_order, 
                  status = $status 
                  WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            header("Location: categories.php?updated=1");
            exit;
        } else {
            $error = 'Error updating category: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Please fill all required fields!';
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-edit"></i> Edit Category</h5>
        <a href="categories.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo $category['sort_order']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo $category['status'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Category
            </button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

