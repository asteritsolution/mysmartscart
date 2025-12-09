<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Orders';

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_id'])) {
    $order_id = (int) $_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET order_status = '$status' WHERE id = $order_id");
    header("Location: orders.php?updated=1");
    exit;
}

// Get all orders
$orders_query = "SELECT o.*, u.first_name, u.last_name, u.email 
                 FROM orders o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
$orders = [];
while ($row = mysqli_fetch_assoc($orders_result)) {
    $orders[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['updated'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Order status updated successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <h5><i class="fas fa-shopping-cart"></i> Orders Management</h5>
    
    <div class="table-responsive mt-4">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7" class="text-center">No orders found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                    <td>
                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                        <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                    </td>
                    <td><?php echo formatDate($order['created_at']); ?></td>
                    <td><strong><?php echo formatCurrency($order['total']); ?></strong></td>
                    <td><?php echo getStatusBadge($order['payment_status']); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View
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

