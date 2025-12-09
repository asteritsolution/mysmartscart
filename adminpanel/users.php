<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Users';

// Handle status toggle
if (isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM users WHERE id = $id"))['status'];
    $new_status = $current == 1 ? 0 : 1;
    mysqli_query($conn, "UPDATE users SET status = $new_status WHERE id = $id");
    header("Location: users.php?updated=1");
    exit;
}

// Get all users
$users_query = "SELECT u.*, COUNT(o.id) as order_count 
                FROM users u 
                LEFT JOIN orders o ON u.id = o.user_id 
                GROUP BY u.id 
                ORDER BY u.created_at DESC";
$users_result = mysqli_query($conn, $users_query);
$users = [];
while ($row = mysqli_fetch_assoc($users_result)) {
    $users[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['updated'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    User status updated successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <h5><i class="fas fa-users"></i> Users Management</h5>
    
    <div class="table-responsive mt-4">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8" class="text-center">No users found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                    <td><span class="badge badge-info"><?php echo $user['order_count']; ?></span></td>
                    <td><?php echo formatDate($user['created_at']); ?></td>
                    <td><?php echo $user['status'] == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
                    <td>
                        <a href="?toggle_status=1&id=<?php echo $user['id']; ?>" 
                           class="btn btn-sm btn-outline-<?php echo $user['status'] == 1 ? 'warning' : 'success'; ?>"
                           title="<?php echo $user['status'] == 1 ? 'Deactivate' : 'Activate'; ?>">
                            <i class="fas fa-<?php echo $user['status'] == 1 ? 'ban' : 'check'; ?>"></i>
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

