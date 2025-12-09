<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Login / Register - KRC Woollens Ranikhet | A Rehabilitation Project Since 1977</title>

	<meta name="keywords" content="KRC Woollens, Login, Register, Account" />
	<meta name="description" content="Login or create an account at KRC Woollens Ranikhet to complete your purchase.">
	<meta name="author" content="KRC Woollens Ranikhet">

	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">


	<script>
		WebFontConfig = {
			google: { families: [ 'Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700', 'Shadows+Into+Light:400' ] }
		};
		( function ( d ) {
			var wf = d.createElement( 'script' ), s = d.scripts[ 0 ];
			wf.src = 'assets/js/webfont.js';
			wf.async = true;
			s.parentNode.insertBefore( wf, s );
		} )( document );
	</script>

	<!-- Plugins CSS File -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

	<!-- Main CSS File -->
	<link rel="stylesheet" href="assets/css/demo7.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
</head>

<body>
	<div class="page-wrapper">
		<div class="top-notice text-white">
			<div class="container text-center">
				<h5 class="d-inline-block mb-0">Supporting <b>Army Families</b> Since 1977</h5>
				<a href="about.php" class="category">OUR STORY</a>
				<a href="shop.php" class="category ml-2 mr-3">SHOP NOW</a>
				<small>* A Rehabilitation Project</small>
				<button title="Close (Esc)" type="button" class="mfp-close">×</button>
			</div><!-- End .container -->
		</div><!-- End .top-notice -->

		<?php include "common/header.php"; ?>
		
		<main class="main">
			<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
				<div class="container">
					<h1 class="page-title">My Account</h1>
				</div>
			</div>

			<div class="container login-container">
				<?php if (isset($_SESSION['login_error'])) { ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php } ?>
				
				<?php if (isset($_SESSION['register_errors'])) { ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<ul class="mb-0">
						<?php foreach ($_SESSION['register_errors'] as $error) {
							echo '<li>' . htmlspecialchars($error) . '</li>';
						}
						unset($_SESSION['register_errors']); ?>
					</ul>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php } ?>
				
				<?php if (isset($_SESSION['register_success'])) { ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php } ?>
				
				<div class="row">
					<div class="col-lg-10 mx-auto">
						<div class="row">
							<div class="col-md-6">
								<div class="heading mb-1">
									<h2 class="title">Login</h2>
								</div>

								<form action="login-handler.php" method="POST">
									<input type="hidden" name="login" value="1">
									<label for="login-email">
										Email address
										<span class="required">*</span>
									</label>
									<input type="email" class="form-input form-wide" id="login-email" name="email" required />

									<label for="login-password">
										Password
										<span class="required">*</span>
									</label>
									<input type="password" class="form-input form-wide" id="login-password" name="password" required />

									<div class="form-footer">
										<div class="custom-control custom-checkbox mb-0">
											<input type="checkbox" class="custom-control-input" id="remember-me" name="remember" />
											<label class="custom-control-label mb-0" for="remember-me">Remember
												me</label>
										</div>

										<a href="forgot-password.php"
											class="forget-password text-dark form-footer-right">Forgot
											Password?</a>
									</div>
									<button type="submit" class="btn btn-dark btn-md w-100">
										LOGIN
									</button>
								</form>
							</div>
							<div class="col-md-6">
								<div class="heading mb-1">
									<h2 class="title">Register</h2>
								</div>

								<form action="register-handler.php" method="POST">
									<input type="hidden" name="register" value="1">
									
									<div class="row">
										<div class="col-md-6">
											<label for="register-firstname">
												First Name
												<span class="required">*</span>
											</label>
											<input type="text" class="form-input form-wide" id="register-firstname" name="first_name" required />
										</div>
										<div class="col-md-6">
											<label for="register-lastname">
												Last Name
												<span class="required">*</span>
											</label>
											<input type="text" class="form-input form-wide" id="register-lastname" name="last_name" required />
										</div>
									</div>
									
									<label for="register-email">
										Email address
										<span class="required">*</span>
									</label>
									<input type="email" class="form-input form-wide" id="register-email" name="email" required />
									
									<label for="register-phone">
										Phone Number
									</label>
									<input type="tel" class="form-input form-wide" id="register-phone" name="phone" />

									<label for="register-password">
										Password
										<span class="required">*</span>
									</label>
									<input type="password" class="form-input form-wide" id="register-password" name="password" required />
									
									<label for="register-confirm-password">
										Confirm Password
										<span class="required">*</span>
									</label>
									<input type="password" class="form-input form-wide" id="register-confirm-password" name="confirm_password" required />

									<div class="form-footer mb-2">
										<button type="submit" class="btn btn-dark btn-md w-100 mr-0">
											Register
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
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
	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/plugins.min.js"></script>

	<!-- Main JS File -->
	<script src="assets/js/main.min.js"></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a48e1a32db4d893',t:'MTc2NDE1NDgxOQ=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>
</html>