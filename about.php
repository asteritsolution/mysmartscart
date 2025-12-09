<?php
session_start();
include "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>About Us - MySmartSCart | Your Trusted Online Shopping Partner</title>

    <meta name="keywords" content="MySmartSCart, about us, online shopping India, trusted ecommerce, best deals" />
    <meta name="description"
        content="Learn about MySmartSCart - Your trusted online shopping destination. We bring you the best products at unbeatable prices with fast delivery across India.">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">


    <script>
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700', 'Poppins:300,400,500,600,700,800', 'Playfair+Display:900', 'Shadows+Into+Light:400'] }
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

        <main class="main about">
            <div class="page-header page-header-bg"
                style="background-image: url('assets/images/demoes/demo7/banners/banner-top.jpg');">
                <div class="container text-left">
                    <h1 class="font4 text-white"><span class="text-white">ABOUT</span>MYSMARTSCART</h1>
                </div><!-- End .container -->
            </div><!-- End .page-header -->

            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">About Us</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div class="about-section">
                <div class="container">
                    <h2 class="title">WELCOME TO MYSMARTSCART</h2>
                    <p class="lead"><strong>Your Smart Shopping Destination</strong></p>
                    
                    <p class="mb-4" style="font-size: 1.1rem; line-height: 1.8;">
                        <strong>MySmartSCart</strong> is your one-stop online shopping destination bringing you the latest 
                        and trendiest products at unbeatable prices. We believe that quality products should be accessible 
                        to everyone, and that's exactly what we deliver - premium products without the premium price tag!
                    </p>
                    
                    <p class="mb-4" style="font-size: 1.1rem; line-height: 1.8;">
                        From cutting-edge electronics and stylish fashion to innovative gadgets and home essentials, 
                        we curate the best products from around the world and bring them right to your doorstep 
                        across India. Our mission is simple: <strong>Smart Shopping, Happy Customers!</strong>
                    </p>

                    <h3 class="mt-5 mb-3"><i class="fas fa-star text-primary mr-2"></i>Why Shop With Us?</h3>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center p-4 bg-light rounded h-100">
                                <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                                <h5>Best Prices</h5>
                                <p class="mb-0">Up to 70% OFF on trending products. We guarantee the best deals!</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center p-4 bg-light rounded h-100">
                                <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                                <h5>Fast Delivery</h5>
                                <p class="mb-0">Quick delivery within 3-7 business days across India.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center p-4 bg-light rounded h-100">
                                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <h5>Secure Shopping</h5>
                                <p class="mb-0">100% secure payment options. Your data is always protected.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center p-4 bg-light rounded h-100">
                                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                                <h5>24/7 Support</h5>
                                <p class="mb-0">Dedicated customer support ready to help you anytime.</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="mt-5 mb-3"><i class="fas fa-box-open text-primary mr-2"></i>What We Offer</h3>
                    <ul class="list-unstyled ml-3 mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-primary mr-2"></i> <strong>Electronics & Gadgets</strong> - Latest tech at best prices</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary mr-2"></i> <strong>Fashion & Accessories</strong> - Trendy styles for everyone</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary mr-2"></i> <strong>Home & Kitchen</strong> - Essential items for your home</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary mr-2"></i> <strong>Beauty & Personal Care</strong> - Premium skincare & grooming</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary mr-2"></i> <strong>Sports & Fitness</strong> - Gear up for an active lifestyle</li>
                    </ul>

                    <h3 class="mt-5 mb-3"><i class="fas fa-heart text-primary mr-2"></i>Our Promise</h3>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        At MySmartSCart, we're committed to providing an exceptional shopping experience. 
                        We carefully select each product to ensure quality, and we're always here to make 
                        your shopping journey smooth and enjoyable. Your satisfaction is our top priority!
                    </p>

                    <div class="bg-light p-4 rounded mt-5 text-center" style="border-left: 4px solid #0066cc;">
                        <h4 class="mb-3" style="color: #0066cc;"><i class="fas fa-gift mr-2"></i>Start Shopping Today!</h4>
                        <p class="mb-4" style="font-size: 1.15rem;">
                            <strong>Discover amazing deals and exclusive offers!</strong><br>
                            Join thousands of happy customers who shop smart with MySmartSCart.
                        </p>
                        <a href="shop.php" class="btn btn-primary btn-lg">Shop Now</a>
                        <a href="contact.php" class="btn btn-outline-primary btn-lg ml-2">Contact Us</a>
                    </div>
                </div><!-- End .container -->
            </div><!-- End .about-section -->

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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>
    <script src="assets/js/nouislider.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
</body>

</html>
