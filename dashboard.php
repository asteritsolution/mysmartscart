<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'dashboard.php';
    header("Location: login");
    exit;
}

// Get logged in user details
$user_id = (int) $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = $user_id AND status = 1 LIMIT 1";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    // User not found or inactive, logout and redirect
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    header("Location: login");
    exit;
}

// Get user orders
$orders_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10";
$orders_result = mysqli_query($conn, $orders_query);
$orders = [];
while ($order = mysqli_fetch_assoc($orders_result)) {
    $orders[] = $order;
}

// Get order count
$order_count_query = "SELECT COUNT(*) as total FROM orders WHERE user_id = $user_id";
$order_count_result = mysqli_query($conn, $order_count_query);
$order_count = mysqli_fetch_assoc($order_count_result)['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>My Account - MySmartSCart | Dashboard</title>

	<meta name="keywords" content="MySmartSCart, My Account, Dashboard, Orders" />
	<meta name="description" content="Manage your account, view orders, and update your information at MySmartSCart.">
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
	<link rel="stylesheet" type="text/css" href="assets/vendor/simple-line-icons/css/simple-line-icons.min.css">
	<link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
	<div class="page-wrapper">
		<?php include "common/top-notice.php"; ?>
		<?php include "common/header.php"; ?>

		<main class="main">
			<div class="page-header">
				<div class="container d-flex flex-column align-items-center">
					<nav aria-label="breadcrumb" class="breadcrumb-nav">
						<div class="container">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="category.html">Shop</a></li>
								<li class="breadcrumb-item active" aria-current="page">
									My Account
								</li>
							</ol>
						</div>
					</nav>

					<h1>My Account</h1>
				</div>
			</div>

			<div class="container account-container custom-account-container">
				<div class="row">
					<div class="sidebar widget widget-dashboard mb-lg-0 mb-3 col-lg-3 order-0">
						<h2 class="text-uppercase">My Account</h2>
						<ul class="nav nav-tabs list flex-column mb-0" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard"
									role="tab" aria-controls="dashboard" aria-selected="true">Dashboard</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab"
									aria-controls="order" aria-selected="true">Orders</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" id="download-tab" data-toggle="tab" href="#download" role="tab"
									aria-controls="download" aria-selected="false">Downloads</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab"
									aria-controls="address" aria-selected="false">Addresses</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab"
									aria-controls="edit" aria-selected="false">Account
									details</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="shop-address-tab" data-toggle="tab" href="#shipping" role="tab"
									aria-controls="edit" aria-selected="false">Shopping Addres</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="wishlist.php">Wishlist</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="logout.php">Logout</a>
							</li>
						</ul>
					</div>
					<div class="col-lg-9 order-lg-last order-1 tab-content">
						<div class="tab-pane fade show active" id="dashboard" role="tabpanel">
							<div class="dashboard-content">
								<p>
									Hello <strong class="text-dark"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong> (not
									<strong class="text-dark"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>?
									<a href="logout.php" class="btn btn-link ">Log out</a>)
								</p>

								<p>
									From your account dashboard you can view your
									<a class="btn btn-link link-to-tab" href="#order">recent orders</a>,
									manage your
									<a class="btn btn-link link-to-tab" href="#address">shipping and billing
										addresses</a>, and
									<a class="btn btn-link link-to-tab" href="#edit">edit your password and account
										details.</a>
								</p>

								<div class="mb-4"></div>

								<div class="row row-lg">
									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="#order" class="link-to-tab"><i
													class="sicon-social-dropbox"></i></a>
											<div class="feature-box-content">
												<h3>ORDERS</h3>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="#download" class="link-to-tab"><i
													class="sicon-cloud-download"></i></a>
											<div class=" feature-box-content">
												<h3>DOWNLOADS</h3>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="#address" class="link-to-tab"><i
													class="sicon-location-pin"></i></a>
											<div class="feature-box-content">
												<h3>ADDRESSES</h3>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="#edit" class="link-to-tab"><i class="icon-user-2"></i></a>
											<div class="feature-box-content p-0">
												<h3>ACCOUNT DETAILS</h3>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="wishlist.php"><i class="sicon-heart"></i></a>
											<div class="feature-box-content">
												<h3>WISHLIST</h3>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4">
										<div class="feature-box text-center pb-4">
											<a href="logout.php"><i class="sicon-logout"></i></a>
											<div class="feature-box-content">
												<h3>LOGOUT</h3>
											</div>
										</div>
									</div>
								</div><!-- End .row -->
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="order" role="tabpanel">
							<div class="order-content">
								<h3 class="account-sub-title d-none d-md-block"><i
										class="sicon-social-dropbox align-middle mr-3"></i>Orders</h3>
								<div class="order-table-container text-center">
									<table class="table table-order text-left">
										<thead>
											<tr>
												<th class="order-id">ORDER</th>
												<th class="order-date">DATE</th>
												<th class="order-status">STATUS</th>
												<th class="order-price">TOTAL</th>
												<th class="order-action">ACTIONS</th>
											</tr>
										</thead>
										<tbody>
											<?php if (empty($orders)) { ?>
											<tr>
												<td class="text-center p-0" colspan="5">
													<p class="mb-5 mt-5">
														No Order has been made yet.
													</p>
												</td>
											</tr>
											<?php } else { 
												foreach ($orders as $order) {
													$order_date = date('M d, Y', strtotime($order['created_at']));
													$status_class = '';
													switch($order['order_status']) {
														case 'pending': $status_class = 'badge-warning'; break;
														case 'processing': $status_class = 'badge-info'; break;
														case 'shipped': $status_class = 'badge-primary'; break;
														case 'delivered': $status_class = 'badge-success'; break;
														case 'cancelled': $status_class = 'badge-danger'; break;
														default: $status_class = 'badge-secondary';
													}
											?>
											<tr>
												<td class="order-id">#<?php echo htmlspecialchars($order['order_number']); ?></td>
												<td class="order-date"><?php echo $order_date; ?></td>
												<td class="order-status">
													<span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($order['order_status']); ?></span>
												</td>
												<td class="order-price">₹<?php echo number_format($order['total'], 2); ?></td>
												<td class="order-action">
													<a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-dark">View</a>
												</td>
											</tr>
											<?php } } ?>
										</tbody>
									</table>
									<hr class="mt-0 mb-3 pb-2" />

									<a href="shop.php" class="btn btn-dark">Go Shop</a>
								</div>
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="download" role="tabpanel">
							<div class="download-content">
								<h3 class="account-sub-title d-none d-md-block"><i
										class="sicon-cloud-download align-middle mr-3"></i>Downloads</h3>
								<div class="download-table-container">
									<p>No downloads available yet.</p> <a href="shop.php"
										class="btn btn-primary text-transform-none mb-2">GO SHOP</a>
								</div>
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="address" role="tabpanel">
							<h3 class="account-sub-title d-none d-md-block mb-1"><i
									class="sicon-location-pin align-middle mr-3"></i>Addresses</h3>
							<div class="addresses-content">
								<p class="mb-4">
									The following addresses will be used on the checkout page by
									default.
								</p>

								<div class="row">
									<div class="address col-md-6">
										<div class="heading d-flex">
											<h4 class="text-dark mb-0">Billing address</h4>
										</div>

										<div class="address-box">
											<?php if (!empty($user['address_line1'])) { ?>
											<address>
												<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?><br>
												<?php echo htmlspecialchars($user['address_line1']); ?><br>
												<?php if (!empty($user['address_line2'])) { ?>
												<?php echo htmlspecialchars($user['address_line2']); ?><br>
												<?php } ?>
												<?php echo htmlspecialchars($user['city'] . ', ' . $user['state'] . ' ' . $user['postcode']); ?><br>
												<?php echo htmlspecialchars($user['country']); ?><br>
												Phone: <?php echo htmlspecialchars($user['phone'] ?? ''); ?>
											</address>
											<?php } else { ?>
											You have not set up this type of address yet.
											<?php } ?>
										</div>

										<a href="#billing" class="btn btn-default address-action link-to-tab"><?php echo !empty($user['address_line1']) ? 'Edit' : 'Add'; ?> Address</a>
									</div>

									<div class="address col-md-6 mt-5 mt-md-0">
										<div class="heading d-flex">
											<h4 class="text-dark mb-0">
												Shipping address
											</h4>
										</div>

										<div class="address-box">
											<?php if (!empty($user['address_line1'])) { ?>
											<address>
												<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?><br>
												<?php echo htmlspecialchars($user['address_line1']); ?><br>
												<?php if (!empty($user['address_line2'])) { ?>
												<?php echo htmlspecialchars($user['address_line2']); ?><br>
												<?php } ?>
												<?php echo htmlspecialchars($user['city'] . ', ' . $user['state'] . ' ' . $user['postcode']); ?><br>
												<?php echo htmlspecialchars($user['country']); ?><br>
												Phone: <?php echo htmlspecialchars($user['phone'] ?? ''); ?>
											</address>
											<?php } else { ?>
											You have not set up this type of address yet.
											<?php } ?>
										</div>

										<a href="#shipping" class="btn btn-default address-action link-to-tab"><?php echo !empty($user['address_line1']) ? 'Edit' : 'Add'; ?> Address</a>
									</div>
								</div>
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="edit" role="tabpanel">
							<h3 class="account-sub-title d-none d-md-block mt-0 pt-1 ml-1"><i
									class="icon-user-2 align-middle mr-3 pr-1"></i>Account Details</h3>
							<div class="account-content">
								<form action="account-update.php" method="POST">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="acc-name">First name <span class="required">*</span></label>
												<input type="text" class="form-control"
													id="acc-name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="acc-lastname">Last name <span
														class="required">*</span></label>
												<input type="text" class="form-control" id="acc-lastname"
													name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />
											</div>
										</div>
									</div>

									<div class="form-group mb-2">
										<label for="acc-text">Display name <span class="required">*</span></label>
										<input type="text" class="form-control" id="acc-text" name="display_name"
											value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" required />
										<p>This will be how your name will be displayed in the account section and
											in
											reviews</p>
									</div>


									<div class="form-group mb-4">
										<label for="acc-email">Email address <span class="required">*</span></label>
										<input type="email" class="form-control" id="acc-email" name="email"
											value="<?php echo htmlspecialchars($user['email']); ?>" required />
									</div>
									
									<div class="form-group mb-4">
										<label for="acc-phone">Phone Number</label>
										<input type="tel" class="form-control" id="acc-phone" name="phone"
											value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" />
									</div>

									<div class="change-password">
										<h3 class="text-uppercase mb-2">Password Change</h3>

										<div class="form-group">
											<label for="acc-password">Current Password (leave blank to leave
												unchanged)</label>
											<input type="password" class="form-control" id="acc-password"
												name="acc-password" />
										</div>

										<div class="form-group">
											<label for="acc-password">New Password (leave blank to leave
												unchanged)</label>
											<input type="password" class="form-control" id="acc-new-password"
												name="acc-new-password" />
										</div>

										<div class="form-group">
											<label for="acc-password">Confirm New Password</label>
											<input type="password" class="form-control" id="acc-confirm-password"
												name="acc-confirm-password" />
										</div>
									</div>

									<div class="form-footer mt-3 mb-0">
										<button type="submit" class="btn btn-dark mr-0">
											Save changes
										</button>
									</div>
								</form>
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="billing" role="tabpanel">
							<div class="address account-content mt-0 pt-2">
								<h4 class="title">Billing address</h4>

								<form class="mb-2" action="address-update.php" method="POST">
									<input type="hidden" name="address_type" value="billing">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>First name <span class="required">*</span></label>
												<input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>Last name <span class="required">*</span></label>
												<input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />
											</div>
										</div>
									</div>

									<div class="select-custom">
										<label>Country / Region <span class="required">*</span></label>
										<select name="country" class="form-control" required>
											<option value="India" <?php echo (($user['country'] ?? 'India') == 'India') ? 'selected' : ''; ?>>India</option>
										</select>
									</div>

									<div class="form-group">
										<label>Street address <span class="required">*</span></label>
										<input type="text" class="form-control" name="address_line1"
											placeholder="House number and street name" value="<?php echo htmlspecialchars($user['address_line1'] ?? ''); ?>" required />
										<input type="text" class="form-control" name="address_line2"
											placeholder="Apartment, suite, unit, etc. (optional)" value="<?php echo htmlspecialchars($user['address_line2'] ?? ''); ?>" />
									</div>

									<div class="form-group">
										<label>Town / City <span class="required">*</span></label>
										<input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required />
									</div>

									<div class="select-custom">
										<label>State <span class="required">*</span></label>
										<select name="state" class="form-control" required>
											<option value="">Select State</option>
											<?php
											$states = ['Uttarakhand', 'Uttar Pradesh', 'Delhi', 'Haryana', 'Punjab', 'Himachal Pradesh', 'Rajasthan', 'Maharashtra', 'Gujarat', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Other'];
											$user_state = $user['state'] ?? '';
											foreach ($states as $state) {
												$selected = ($user_state == $state) ? 'selected' : '';
												echo "<option value=\"$state\" $selected>$state</option>";
											}
											?>
										</select>
									</div>

									<div class="form-group">
										<label>Postcode / ZIP <span class="required">*</span></label>
										<input type="text" class="form-control" name="postcode" pattern="[0-9]{6}" value="<?php echo htmlspecialchars($user['postcode'] ?? ''); ?>" required />
									</div>

									<div class="form-group mb-3">
										<label>Phone <span class="required">*</span></label>
										<input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required />
									</div>

									<div class="form-group mb-3">
										<label>Email address <span class="required">*</span></label>
										<input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
											required />
									</div>

									<div class="form-footer mb-0">
										<div class="form-footer-right">
											<button type="submit" class="btn btn-dark py-4">
												Save Address
											</button>
										</div>
									</div>
								</form>
							</div>
						</div><!-- End .tab-pane -->

						<div class="tab-pane fade" id="shipping" role="tabpanel">
							<div class="address account-content mt-0 pt-2">
								<h4 class="title mb-3">Shipping Address</h4>

								<form class="mb-2" action="address-update.php" method="POST">
									<input type="hidden" name="address_type" value="shipping">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>First name <span class="required">*</span></label>
												<input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>Last name <span class="required">*</span></label>
												<input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />
											</div>
										</div>
									</div>

									<div class="select-custom">
										<label>Country / Region <span class="required">*</span></label>
										<select name="country" class="form-control" required>
											<option value="India" <?php echo (($user['country'] ?? 'India') == 'India') ? 'selected' : ''; ?>>India</option>
										</select>
									</div>

									<div class="form-group">
										<label>Street address <span class="required">*</span></label>
										<input type="text" class="form-control" name="address_line1"
											placeholder="House number and street name" value="<?php echo htmlspecialchars($user['address_line1'] ?? ''); ?>" required />
										<input type="text" class="form-control" name="address_line2"
											placeholder="Apartment, suite, unit, etc. (optional)" value="<?php echo htmlspecialchars($user['address_line2'] ?? ''); ?>" />
									</div>

									<div class="form-group">
										<label>Town / City <span class="required">*</span></label>
										<input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required />
									</div>

									<div class="select-custom">
										<label>State <span class="required">*</span></label>
										<select name="state" class="form-control" required>
											<option value="">Select State</option>
											<?php
											$states = ['Uttarakhand', 'Uttar Pradesh', 'Delhi', 'Haryana', 'Punjab', 'Himachal Pradesh', 'Rajasthan', 'Maharashtra', 'Gujarat', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Other'];
											$user_state = $user['state'] ?? '';
											foreach ($states as $state) {
												$selected = ($user_state == $state) ? 'selected' : '';
												echo "<option value=\"$state\" $selected>$state</option>";
											}
											?>
										</select>
									</div>

									<div class="form-group">
										<label>Postcode / ZIP <span class="required">*</span></label>
										<input type="text" class="form-control" name="postcode" pattern="[0-9]{6}" value="<?php echo htmlspecialchars($user['postcode'] ?? ''); ?>" required />
									</div>

									<div class="form-footer mb-0">
										<div class="form-footer-right">
											<button type="submit" class="btn btn-dark py-4">
												Save Address
											</button>
										</div>
									</div>
								</form>
							</div>
						</div><!-- End .tab-pane -->
					</div><!-- End .tab-content -->
				</div><!-- End .row -->
			</div><!-- End .container -->

			<div class="mb-5"></div><!-- margin -->
		</main><!-- End .main -->

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
	<script defer
		src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
		integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
		data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
		crossorigin="anonymous"></script>
	<script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e1649c35d893',t:'MTc2NDE1NDgwOQ=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>
</html>