<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Banners';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM banners WHERE id = $id");
    header("Location: banners.php?deleted=1");
    exit;
}

// Get all banners
$banners_query = "SELECT * FROM banners ORDER BY sort_order, id DESC";
$banners_result = mysqli_query($conn, $banners_query);
$banners = [];
while ($row = mysqli_fetch_assoc($banners_result)) {
    $banners[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Banner deleted successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-images"></i> Banners Management</h5>
        <a href="banner-add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Banner
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Link</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($banners)): ?>
                <tr>
                    <td colspan="7" class="text-center">No banners found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($banners as $banner): ?>
                <tr>
                    <td><?php echo $banner['id']; ?></td>
                    <td>
                        <img src="../<?php echo htmlspecialchars($banner['image']); ?>" 
                             alt="<?php echo htmlspecialchars($banner['title'] ?? 'Banner'); ?>" 
                             style="width: 100px; height: 60px; object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><strong><?php echo htmlspecialchars($banner['title'] ?? 'No Title'); ?></strong></td>
                    <td><a href="../<?php echo htmlspecialchars($banner['link'] ?? '#'); ?>" target="_blank"><?php echo htmlspecialchars($banner['link'] ?? 'N/A'); ?></a></td>
                    <td><?php echo $banner['sort_order']; ?></td>
                    <td><?php echo $banner['status'] == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
                    <td>
                        <a href="banner-edit.php?id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=1&id=<?php echo $banner['id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this banner?')">
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

