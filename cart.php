<?php
session_start();
include "config.php";

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

// Handle cart actions
if (isset($_GET['action'])) {
	if ($_GET['action'] == 'add' && isset($_GET['id'])) {
		$product_id = (int) $_GET['id'];
		$quantity = isset($_GET['qty']) ? (int) $_GET['qty'] : 1;
		
		// Check if product exists and is active
		$check_query = "SELECT id, stock FROM products WHERE id = $product_id AND status = 1";
		$check_result = mysqli_query($conn, $check_query);
		
		if (mysqli_num_rows($check_result) > 0) {
			$product_data = mysqli_fetch_assoc($check_result);
			$max_qty = min($quantity, $product_data['stock']);
			
			if (isset($_SESSION['cart'][$product_id])) {
				// Update quantity if product already in cart
				$_SESSION['cart'][$product_id]['quantity'] += $max_qty;
				if ($_SESSION['cart'][$product_id]['quantity'] > $product_data['stock']) {
					$_SESSION['cart'][$product_id]['quantity'] = $product_data['stock'];
				}
			} else {
				// Add new product to cart
				$_SESSION['cart'][$product_id] = ['quantity' => $max_qty];
			}
		}
		
		// Redirect back to product page or cart
		$redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'cart.php';
		// Add success message to session
		$_SESSION['cart_message'] = 'Product added to cart successfully!';
		header("Location: " . $redirect);
		exit;
	}
	
	if ($_GET['action'] == 'remove' && isset($_GET['id'])) {
		$product_id = (int) $_GET['id'];
		if (isset($_SESSION['cart'][$product_id])) {
			unset($_SESSION['cart'][$product_id]);
		}
		header("Location: cart.php");
		exit;
	}

	if ($_GET['action'] == 'update' && isset($_POST['quantity'])) {
		foreach ($_POST['quantity'] as $product_id => $qty) {
			$product_id = (int) $product_id;
			$qty = (int) $qty;
			if ($qty > 0 && isset($_SESSION['cart'][$product_id])) {
				$_SESSION['cart'][$product_id]['quantity'] = $qty;
			} elseif ($qty <= 0) {
				unset($_SESSION['cart'][$product_id]);
			}
		}
		header("Location: cart.php");
		exit;
	}
}

// Get cart products from database
$cart_items = [];
$subtotal = 0;
$placeholder_image = 'assets/images/products/placeholder.webp';

if (!empty($_SESSION['cart'])) {
	$product_ids = array_keys($_SESSION['cart']);
	$ids_string = implode(',', array_map('intval', $product_ids));

	$cart_query = "SELECT * FROM products WHERE id IN ($ids_string) AND status = 1";
	$cart_result = mysqli_query($conn, $cart_query);

	while ($product = mysqli_fetch_assoc($cart_result)) {
		$product_id = $product['id'];
		$quantity = $_SESSION['cart'][$product_id]['quantity'] ?? 1;
		$product['quantity'] = $quantity;
		$product['image'] = !empty($product['image']) ? $product['image'] : $placeholder_image;

		// Calculate price (use sale_price if available)
		$price = !empty($product['sale_price']) && $product['sale_price'] < $product['price']
			? $product['sale_price']
			: $product['price'];
		$product['final_price'] = $price;
		$product['subtotal'] = $price * $quantity;
		$subtotal += $product['subtotal'];

		$cart_items[] = $product;
	}
}

$shipping = 0; // Can be calculated based on location
$total = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Shopping Cart - MySmartSCart | Your Items</title>

	<meta name="keywords" content="MySmartSCart, Shopping Cart, Checkout, Online Shopping" />
	<meta name="description"
		content="Review your shopping cart at MySmartSCart. Checkout securely and enjoy fast delivery across India.">
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
	<link rel="stylesheet" href="assets/css/style.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
</head>

<body>
	<div class="page-wrapper">
		<div class="top-notice text-white">
			<div class="container text-center">
				<h5 class="d-inline-block mb-0">🔥 <b>MEGA SALE</b> - Up to 70% OFF!</h5>
				<a href="about.php" class="category">ABOUT US</a>
				<a href="shop.php" class="category ml-2 mr-3">SHOP NOW</a>
				<small>* Free Shipping on Orders ₹499+</small>
				<button title="Close (Esc)" type="button" class="mfp-close">×</button>
			</div><!-- End .container -->
		</div><!-- End .top-notice -->

		<?php include "common/header.php"; ?>

		<main class="main">
			<div class="container">
				<?php if (isset($_SESSION['cart_message'])) { ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php } ?>
				
				<ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
					<li class="active">
						<a href="cart.php">Shopping Cart</a>
					</li>
					<li>
						<a href="checkout.php">Checkout</a>
					</li>
					<li class="disabled">
						<a href="#">Order Complete</a>
					</li>
				</ul>

				<?php if (empty($cart_items)) { ?>
					<div class="row">
						<div class="col-12">
							<div class="alert alert-info text-center">
								<h4>Your cart is empty</h4>
								<p>Add some products to your cart to continue shopping.</p>
								<a href="shop.php" class="btn btn-primary">Continue Shopping</a>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="row">
						<div class="col-lg-8">
							<div class="cart-table-container">
								<form action="cart.php?action=update" method="POST">
									<table class="table table-cart">
										<thead>
											<tr>
												<th class="thumbnail-col"></th>
												<th class="product-col">Product</th>
												<th class="price-col">Price</th>
												<th class="qty-col">Quantity</th>
												<th class="text-right">Subtotal</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($cart_items as $item) {
												$item_price = number_format($item['final_price'], 2);
												$item_subtotal = number_format($item['subtotal'], 2);
												?>
												<tr class="product-row">
													<td>
														<figure class="product-image-container">
															<a href="product.php?slug=<?php echo htmlspecialchars($item['slug']); ?>"
																class="product-image">
																<img src="<?php echo htmlspecialchars($item['image']); ?>"
																	alt="<?php echo htmlspecialchars($item['name']); ?>">
															</a>
															<a href="cart.php?action=remove&id=<?php echo $item['id']; ?>"
																class="btn-remove icon-cancel" title="Remove Product"></a>
														</figure>
													</td>
													<td class="product-col">
														<h5 class="product-title">
															<a
																href="product.php?slug=<?php echo htmlspecialchars($item['slug']); ?>"><?php echo htmlspecialchars($item['name']); ?></a>
														</h5>
													</td>
													<td>₹<?php echo $item_price; ?></td>
													<td>
														<div class="product-single-qty">
															<input class="horizontal-quantity form-control" type="number"
																name="quantity[<?php echo $item['id']; ?>]"
																value="<?php echo $item['quantity']; ?>" min="1"
																max="<?php echo $item['stock']; ?>">
														</div>
													</td>
													<td class="text-right"><span
															class="subtotal-price">₹<?php echo $item_subtotal; ?></span></td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="5" class="clearfix">
													<div class="float-left">
														<a href="shop.php" class="btn btn-shop">Continue Shopping</a>
													</div>
													<div class="float-right">
														<button type="submit" class="btn btn-shop btn-update-cart">
															Update Cart
														</button>
													</div>
												</td>
											</tr>
										</tfoot>
									</table>
								</form>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="cart-summary">
								<h3>CART TOTALS</h3>

								<table class="table table-totals">
									<tbody>
										<tr>
											<td>Subtotal</td>
											<td>₹<?php echo number_format($subtotal, 2); ?></td>
										</tr>

										<tr>
											<td>Shipping</td>
											<td>Free</td>
										</tr>
									</tbody>

									<tfoot>
										<tr>
											<td>Total</td>
											<td>₹<?php echo number_format($total, 2); ?></td>
										</tr>
									</tfoot>
								</table>

								<div class="checkout-methods">
									<a href="checkout.php" class="btn btn-block btn-dark">Proceed to Checkout
										<i class="fa fa-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="mb-6"></div>
		</main>

		<!-- Start .footer -->
		<?php include "common/footer.php"; ?>
		<!-- End .footer -->
	</div><!-- End .page-wrapper -->

	<div class="loading-overlay">
		<div class="bounce-loader">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>

	<?php include "common/mobile-menu.php"; ?>



	<a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

	<!-- Plugins JS File -->
	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/plugins.min.js"></script>

	<!-- Main JS File -->
	<script src="assets/js/main.min.js"></script>
	<script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e16c3bcad893',t:'MTc2NDE1NDgxMA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
	<script defer
		src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
		integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
		data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
		crossorigin="anonymous"></script>
</body>

</html>