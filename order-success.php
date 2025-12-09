<?php
session_start();
include "config.php";

// Check if order was placed
if (!isset($_SESSION['order_number']) || empty($_SESSION['order_number'])) {
    header("Location: shop.php");
    exit;
}

$order_number = $_SESSION['order_number'];
$order_id = isset($_SESSION['order_id']) ? (int) $_SESSION['order_id'] : 0;

// Get order details
$order = null;
if ($order_id > 0) {
    $order_query = "SELECT * FROM orders WHERE id = $order_id LIMIT 1";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);
}

// Get order items
$order_items = [];
if ($order_id > 0) {
    $items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
    $items_result = mysqli_query($conn, $items_query);
    while ($item = mysqli_fetch_assoc($items_result)) {
        $order_items[] = $item;
    }
}

// Clear order session after displaying
unset($_SESSION['order_number']);
unset($_SESSION['order_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Order Success - MySmartSCart | Thank You</title>

    <meta name="keywords" content="MySmartSCart, Order Success, Thank You" />
    <meta name="description" content="Your order has been placed successfully at MySmartSCart.">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">

    <script>
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700', 'Shadows+Into+Light:400'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/demo7.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>

        <?php include "common/header.php"; ?>

        <main class="main">
            <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
                <div class="container">
                    <h1 class="page-title">Order Success</h1>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                            </div>
                            <h2 class="mb-3">Thank You for Your Order!</h2>
                            <p class="lead mb-4">Your order has been placed successfully.</p>
                            
                            <?php if ($order) { ?>
                            <div class="order-details-box bg-light p-4 mb-4 text-left">
                                <h4 class="mb-3">Order Details</h4>
                                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                                <p><strong>Order Date:</strong> <?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></p>
                                <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total'], 2); ?></p>
                                <p><strong>Payment Method:</strong> Cash on Delivery (COD)</p>
                                <p><strong>Order Status:</strong> <span class="badge badge-info"><?php echo ucfirst($order['order_status']); ?></span></p>
                                
                                <hr>
                                
                                <h5 class="mb-3">Shipping Address</h5>
                                <p>
                                    <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                                    <?php echo htmlspecialchars($order['address_line1']); ?><br>
                                    <?php if (!empty($order['address_line2'])) { ?>
                                    <?php echo htmlspecialchars($order['address_line2']); ?><br>
                                    <?php } ?>
                                    <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postcode']); ?><br>
                                    <?php echo htmlspecialchars($order['country']); ?><br>
                                    Phone: <?php echo htmlspecialchars($order['phone']); ?>
                                </p>
                                
                                <?php if (!empty($order_items)) { ?>
                                <hr>
                                <h5 class="mb-3">Order Items</h5>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th class="text-right">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order_items as $item) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td class="text-right">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"><strong>Subtotal</strong></td>
                                            <td class="text-right"><strong>₹<?php echo number_format($order['subtotal'], 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Shipping</strong></td>
                                            <td class="text-right"><strong>₹<?php echo number_format($order['shipping_cost'], 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Total</strong></td>
                                            <td class="text-right"><strong>₹<?php echo number_format($order['total'], 2); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php } ?>
                            </div>
                            <?php } else { ?>
                            <div class="alert alert-info">
                                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order_number); ?></p>
                                <p>We have received your order and will process it shortly.</p>
                            </div>
                            <?php } ?>
                            
                            <div class="mt-4">
                                <a href="shop.php" class="btn btn-dark mr-2">Continue Shopping</a>
                                <a href="index.php" class="btn btn-outline-dark">Go to Homepage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End .main -->

        <!-- Start .footer -->
        <?php include "common/footer.php"; ?>
        <!-- End .footer -->
    </div>
    <!-- End .page-wrapper -->

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
</body>

</html>

