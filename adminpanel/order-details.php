<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Order Details';

$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    header("Location: orders.php");
    exit;
}

// Get order
$order_query = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = $id LIMIT 1";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    header("Location: orders.php");
    exit;
}

// Get order items
$items_query = "SELECT oi.*, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $id";
$items_result = mysqli_query($conn, $items_query);
$items = [];
while ($row = mysqli_fetch_assoc($items_result)) {
    $items[] = $row;
}

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-file-invoice"></i> Order Details - #<?php echo htmlspecialchars($order['order_number']); ?></h5>
        <a href="orders.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>Customer Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <strong>Shipping Address</strong>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                        <?php echo htmlspecialchars($order['address_line1']); ?><br>
                        <?php if (!empty($order['address_line2'])): ?>
                        <?php echo htmlspecialchars($order['address_line2']); ?><br>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postcode']); ?><br>
                        <?php echo htmlspecialchars($order['country']); ?>
                    </address>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-3">
        <div class="card-header bg-success text-white">
            <strong>Order Items</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <img src="../<?php echo htmlspecialchars($item['image'] ?: 'assets/images/products/placeholder.webp'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                <?php echo htmlspecialchars($item['product_name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['product_sku'] ?? 'N/A'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo formatCurrency($item['price']); ?></td>
                            <td><strong><?php echo formatCurrency($item['subtotal']); ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                            <td><strong><?php echo formatCurrency($order['subtotal']); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Shipping:</strong></td>
                            <td><strong><?php echo formatCurrency($order['shipping_cost']); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                            <td><strong><?php echo formatCurrency($order['total']); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Order Information</strong>
                </div>
                <div class="card-body">
                    <p><strong>Order Date:</strong> <?php echo formatDate($order['created_at']); ?></p>
                    <p><strong>Order Status:</strong> <?php echo getStatusBadge($order['order_status']); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo getStatusBadge($order['payment_status']); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
                </div>
            </div>
        </div>
        
        <?php if (!empty($order['order_notes'])): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Order Notes</strong>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($order['order_notes'])); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

