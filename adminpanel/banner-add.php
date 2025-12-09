<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Add Banner';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $link = mysqli_real_escape_string($conn, $_POST['link'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/banners/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'assets/images/banners/' . $file_name;
        }
    }
    
    if (!empty($image)) {
        $query = "INSERT INTO banners (title, image, link, sort_order, status) 
                  VALUES ('$title', '$image', '$link', $sort_order, $status)";
        
        if (mysqli_query($conn, $query)) {
            header("Location: banners.php?added=1");
            exit;
        } else {
            $error = 'Error adding banner: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Please upload an image!';
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-plus"></i> Add New Banner</h5>
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
                    <input type="text" class="form-control" name="title">
                </div>
                
                <div class="form-group">
                    <label>Link URL</label>
                    <input type="text" class="form-control" name="link" placeholder="shop.php">
                    <small class="form-text text-muted">Where the banner should link to</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Banner Image <span class="text-danger">*</span></label>
                    <input type="file" class="form-control-file" name="image" accept="image/*" required>
                    <small class="form-text text-muted">Recommended size: 1920x600px</small>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Banner
            </button>
            <a href="banners.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

