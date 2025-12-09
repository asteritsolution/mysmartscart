<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Dashboard';

// Get statistics
$stats = [];

// Total Products
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$stats['products'] = mysqli_fetch_assoc($result)['total'];

// Total Categories
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories");
$stats['categories'] = mysqli_fetch_assoc($result)['total'];

// Total Orders
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$stats['orders'] = mysqli_fetch_assoc($result)['total'];

// Total Revenue
$result = mysqli_query($conn, "SELECT SUM(total) as total FROM orders WHERE order_status != 'cancelled'");
$revenue = mysqli_fetch_assoc($result)['total'] ?? 0;

// Pending Orders
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'");
$stats['pending_orders'] = mysqli_fetch_assoc($result)['total'];

// Total Users
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$stats['users'] = mysqli_fetch_assoc($result)['total'];

// Recent Orders
$recent_orders_query = "SELECT o.*, u.first_name, u.last_name, u.email 
                        FROM orders o 
                        LEFT JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC 
                        LIMIT 10";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);
$recent_orders = [];
while ($row = mysqli_fetch_assoc($recent_orders_result)) {
    $recent_orders[] = $row;
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <h3><?php echo $stats['products']; ?></h3>
            <p>Total Products</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3><?php echo $stats['orders']; ?></h3>
            <p>Total Orders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <h3><?php echo formatCurrency($revenue); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h3><?php echo $stats['users']; ?></h3>
            <p>Total Users</p>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-clock"></i> Recent Orders</h5>
                <a href="orders.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_orders)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                            <td>
                                <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                                <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                            </td>
                            <td><?php echo formatDate($order['created_at']); ?></td>
                            <td><strong><?php echo formatCurrency($order['total']); ?></strong></td>
                            <td><?php echo getStatusBadge($order['order_status']); ?></td>
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
    </div>
</div>

<?php include 'includes/footer.php'; ?>

