<?php
session_start();
include "config.php";
require_once "includes/site-settings.php";

$site_favicon = getSetting('site_favicon', 'assets/images/icons/favicon.png');
$site_name = getSetting('site_name', 'MySmartSCart');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Return Policy - <?php echo htmlspecialchars($site_name); ?> | Easy Returns</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_name); ?>, Return Policy, Product Returns, Exchange Policy" />
    <meta name="description" content="Learn about <?php echo htmlspecialchars($site_name); ?>'s return policy. Easy returns and exchanges for eligible products within the return window.">
    <meta name="author" content="<?php echo htmlspecialchars($site_name); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($site_favicon); ?>">

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
    
    <!-- Performance Optimizations -->
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <?php include "common/header.php"; ?>

    <main class="main">
        <div class="page-header">
            <div class="container d-flex flex-column align-items-center">
                <nav aria-label="breadcrumb" class="breadcrumb-nav">
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo getBaseUrl(); ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Return Policy</li>
                        </ol>
                    </div>
                </nav>
                <h1>Return Policy</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-content py-5">
                        <div class="entry-content">
                            <p class="text-muted mb-4"><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

                            <div class="alert alert-success">
                                <i class="fas fa-undo-alt"></i> <strong>Easy Returns:</strong> We want you to love your purchase! If you're not completely satisfied, we offer hassle-free returns within our return window.
                            </div>

                            <h2 class="mb-3">1. Return Window</h2>
                            <p>You can return eligible products within <strong>7-15 days</strong> from the date of delivery. The exact return period may vary by product category:</p>
                            <ul>
                                <li><strong>Electronics:</strong> 7 days from delivery</li>
                                <li><strong>Fashion & Apparel:</strong> 15 days from delivery</li>
                                <li><strong>Home & Kitchen:</strong> 10 days from delivery</li>
                                <li><strong>Beauty & Personal Care:</strong> 7 days from delivery (unopened only)</li>
                                <li><strong>Books & Media:</strong> 7 days from delivery</li>
                            </ul>
                            <p><strong>Note:</strong> The return period starts from the date you receive the product, not the order date.</p>

                            <h2 class="mb-3 mt-4">2. Return Eligibility</h2>
                            <p>To be eligible for return, products must meet the following conditions:</p>
                            <ul>
                                <li>Product must be in original, unused condition</li>
                                <li>All original tags, labels, and packaging must be intact</li>
                                <li>Product must not be damaged, altered, or washed (for clothing)</li>
                                <li>All accessories, manuals, and free gifts must be included</li>
                                <li>Product must be in original packaging (if applicable)</li>
                            </ul>

                            <h2 class="mb-3 mt-4">3. Non-Returnable Items</h2>
                            <p>The following items cannot be returned:</p>
                            <ul>
                                <li>Personalized, customized, or made-to-order products</li>
                                <li>Perishable goods (food items, beverages, flowers)</li>
                                <li>Intimate or sanitary goods (underwear, innerwear, cosmetics if opened)</li>
                                <li>Digital products (software, e-books, downloadable content)</li>
                                <li>Gift cards and vouchers</li>
                                <li>Items damaged due to misuse, abuse, or normal wear and tear</li>
                                <li>Products without original packaging or tags</li>
                                <li>Items purchased during special sales (unless defective)</li>
                            </ul>

                            <h2 class="mb-3 mt-4">4. How to Initiate a Return</h2>
                            <p>Follow these simple steps to return a product:</p>
                            <ol>
                                <li><strong>Log In:</strong> Sign in to your account on our website</li>
                                <li><strong>Go to Orders:</strong> Navigate to "My Orders" or "Order History"</li>
                                <li><strong>Select Order:</strong> Find the order containing the item you want to return</li>
                                <li><strong>Request Return:</strong> Click "Return" or "Return Item" button</li>
                                <li><strong>Select Reason:</strong> Choose the reason for return from the dropdown menu</li>
                                <li><strong>Submit Request:</strong> Submit your return request</li>
                                <li><strong>Wait for Approval:</strong> We'll review your request (usually within 24-48 hours)</li>
                                <li><strong>Receive Return Authorization:</strong> Once approved, you'll receive a Return Authorization (RA) number and return instructions</li>
                            </ol>
                            <p><strong>Alternative Method:</strong> You can also contact us directly via email or phone with your order number to initiate a return.</p>

                            <h2 class="mb-3 mt-4">5. Return Shipping</h2>
                            <p><strong>Free Return Shipping:</strong> We provide free return shipping for:</p>
                            <ul>
                                <li>Defective or damaged products</li>
                                <li>Wrong products received</li>
                                <li>Products not as described</li>
                                <li>Size exchanges (for eligible items)</li>
                            </ul>
                            <p><strong>Customer Pays Return Shipping:</strong> For other return reasons (change of mind, wrong size ordered, etc.), return shipping charges may apply. The charges will be deducted from your refund amount.</p>

                            <h2 class="mb-3 mt-4">6. Packaging for Return</h2>
                            <p>Please pack the return item securely:</p>
                            <ul>
                                <li>Use the original packaging if available</li>
                                <li>Include all original tags, labels, and accessories</li>
                                <li>Pack securely to prevent damage during transit</li>
                                <li>Include the Return Authorization (RA) number inside the package</li>
                                <li>Use a trackable shipping method</li>
                            </ul>

                            <h2 class="mb-3 mt-4">7. Return Processing Time</h2>
                            <p>Once we receive your returned item:</p>
                            <ul>
                                <li><strong>Inspection:</strong> 2-3 business days</li>
                                <li><strong>Approval/Rejection:</strong> You'll be notified via email</li>
                                <li><strong>Refund Processing:</strong> 5-7 business days after approval</li>
                                <li><strong>Total Time:</strong> Typically 7-10 business days from receipt of return</li>
                            </ul>

                            <h2 class="mb-3 mt-4">8. Refunds</h2>
                            <p>Once your return is approved:</p>
                            <ul>
                                <li>Refund will be processed to your original payment method</li>
                                <li>Refund amount will include the product price</li>
                                <li>Original shipping charges may be refunded (if product was defective/wrong)</li>
                                <li>Return shipping charges (if applicable) will be deducted</li>
                            </ul>
                            <p>For detailed refund information, please see our <a href="<?php echo getBaseUrl(); ?>refund-policy">Refund Policy</a>.</p>

                            <h2 class="mb-3 mt-4">9. Exchanges</h2>
                            <p>We offer exchanges for:</p>
                            <ul>
                                <li>Size exchanges (for clothing and footwear)</li>
                                <li>Color exchanges (subject to availability)</li>
                                <li>Defective products (replacement with same product)</li>
                            </ul>
                            <p><strong>Exchange Process:</strong></p>
                            <ol>
                                <li>Initiate a return request and select "Exchange" as the reason</li>
                                <li>Specify the desired size/color/variant</li>
                                <li>Once approved, return the original item</li>
                                <li>We'll ship the replacement once we receive the return</li>
                            </ol>
                            <p><strong>Note:</strong> Exchanges are subject to product availability. If the desired variant is unavailable, we'll process a refund instead.</p>

                            <h2 class="mb-3 mt-4">10. Return Rejection</h2>
                            <p>Your return may be rejected if:</p>
                            <ul>
                                <li>Product is not in original condition</li>
                                <li>Return period has expired</li>
                                <li>Product is not eligible for return (see Non-Returnable Items)</li>
                                <li>Missing accessories, tags, or packaging</li>
                                <li>Product shows signs of use, damage, or wear</li>
                            </ul>
                            <p>If your return is rejected, we'll notify you and return the item to you at your expense.</p>

                            <h2 class="mb-3 mt-4">11. Contact Us</h2>
                            <p>For return inquiries or assistance, please contact us:</p>
                            <ul>
                                <li><strong>Email:</strong> support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Phone:</strong> Check our <a href="<?php echo getBaseUrl(); ?>contact">Contact Page</a> for phone support</li>
                                <li><strong>Contact Form:</strong> <a href="<?php echo getBaseUrl(); ?>contact">Submit a Request</a></li>
                            </ul>
                            <p>Please include your order number and Return Authorization (RA) number (if applicable) in all communications.</p>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i> <strong>Tip:</strong> Keep your order confirmation and tracking information until your return is processed. This will help us assist you more efficiently.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "common/footer.php"; ?>

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
</body>

</html>

