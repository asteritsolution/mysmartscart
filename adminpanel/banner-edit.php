<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Edit Banner';

$error = '';
$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    header("Location: banners.php");
    exit;
}

// Get banner
$banner_query = "SELECT * FROM banners WHERE id = $id LIMIT 1";
$banner_result = mysqli_query($conn, $banner_query);
$banner = mysqli_fetch_assoc($banner_result);

if (!$banner) {
    header("Location: banners.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $link = mysqli_real_escape_string($conn, $_POST['link'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    
    $image = $banner['image'];
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/demoes/demo7/banners/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'assets/images/demoes/demo7/banners/' . $file_name;
        }
    }
    
    $query = "UPDATE banners SET 
              title = '$title', 
              image = '$image', 
              link = '$link', 
              sort_order = $sort_order, 
              status = $status 
              WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: banners.php?updated=1");
        exit;
    } else {
        $error = 'Error updating banner: ' . mysqli_error($conn);
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-edit"></i> Edit Banner</h5>
        <a href="banners.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Banners
        </a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Banner Title</label>
                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($banner['title'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Link URL</label>
                    <input type="text" class="form-control" name="link" value="<?php echo htmlspecialchars($banner['link'] ?? ''); ?>">
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo $banner['sort_order']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo $banner['status'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="../<?php echo htmlspecialchars($banner['image']); ?>" 
                         alt="Banner Image" 
                         style="width: 100%; max-width: 300px; border-radius: 5px; margin-bottom: 10px;">
                    <input type="file" class="form-control-file" name="image" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Banner
            </button>
            <a href="banners.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

