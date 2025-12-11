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

    <title>Refund Policy - <?php echo htmlspecialchars($site_name); ?> | Money Back Guarantee</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_name); ?>, Refund Policy, Money Back, Return Money" />
    <meta name="description" content="Learn about <?php echo htmlspecialchars($site_name); ?>'s refund policy. Understand when and how you can get your money back for eligible purchases.">
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
                            <li class="breadcrumb-item active" aria-current="page">Refund Policy</li>
                        </ol>
                    </div>
                </nav>
                <h1>Refund Policy</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-content py-5">
                        <div class="entry-content">
                            <p class="text-muted mb-4"><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <strong>Our Commitment:</strong> We want you to be completely satisfied with your purchase. If you're not happy, we'll work with you to make it right.
                            </div>

                            <h2 class="mb-3">1. Eligibility for Refund</h2>
                            <p>You may be eligible for a refund if:</p>
                            <ul>
                                <li>You return the product within the specified return period (usually 7-15 days from delivery)</li>
                                <li>The product is in its original condition (unused, unwashed, with tags attached)</li>
                                <li>The product is defective, damaged, or not as described</li>
                                <li>You received the wrong product</li>
                                <li>The product was not delivered within the promised timeframe</li>
                            </ul>

                            <h2 class="mb-3 mt-4">2. Non-Refundable Items</h2>
                            <p>The following items are generally not eligible for refund:</p>
                            <ul>
                                <li>Personalized or customized products</li>
                                <li>Perishable goods (food items, beverages)</li>
                                <li>Intimate or sanitary goods</li>
                                <li>Digital products (software, e-books, downloadable content)</li>
                                <li>Gift cards</li>
                                <li>Items damaged due to misuse or normal wear and tear</li>
                            </ul>

                            <h2 class="mb-3 mt-4">3. Refund Process</h2>
                            <p>To request a refund, please follow these steps:</p>
                            <ol>
                                <li><strong>Contact Us:</strong> Email us at support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in or use our <a href="<?php echo getBaseUrl(); ?>contact">Contact Form</a> with your order number</li>
                                <li><strong>Return Request:</strong> We'll review your request and provide a Return Authorization (RA) number if approved</li>
                                <li><strong>Return Shipment:</strong> Pack the item securely in its original packaging and ship it back to us using the provided return address</li>
                                <li><strong>Inspection:</strong> Once we receive the item, we'll inspect it to ensure it meets our return criteria</li>
                                <li><strong>Refund Processing:</strong> If approved, we'll process your refund within 5-7 business days</li>
                            </ol>

                            <h2 class="mb-3 mt-4">4. Refund Methods</h2>
                            <p>Refunds will be issued to the original payment method used for the purchase:</p>
                            <ul>
                                <li><strong>Credit/Debit Cards:</strong> Refund will be credited to your card within 5-10 business days</li>
                                <li><strong>UPI:</strong> Refund will be processed to your UPI account within 3-5 business days</li>
                                <li><strong>Net Banking:</strong> Refund will be credited to your bank account within 5-7 business days</li>
                                <li><strong>Wallet Payments:</strong> Refund will be credited to your wallet within 24-48 hours</li>
                                <li><strong>Cash on Delivery (COD):</strong> Refund will be processed via bank transfer or UPI (you'll need to provide bank details)</li>
                            </ul>

                            <h2 class="mb-3 mt-4">5. Refund Amount</h2>
                            <p>The refund amount will include:</p>
                            <ul>
                                <li>Full product price (as paid)</li>
                                <li>Original shipping charges (if product was defective or wrong item received)</li>
                            </ul>
                            <p><strong>Note:</strong> Return shipping charges may be deducted from the refund amount unless the return is due to our error (wrong product, defective item, etc.).</p>

                            <h2 class="mb-3 mt-4">6. Processing Time</h2>
                            <p>Refund processing times:</p>
                            <ul>
                                <li><strong>Return Approval:</strong> 1-2 business days after we receive your return request</li>
                                <li><strong>Item Inspection:</strong> 2-3 business days after we receive the returned item</li>
                                <li><strong>Refund Processing:</strong> 5-7 business days after approval</li>
                                <li><strong>Total Time:</strong> Typically 10-15 business days from return request to refund credit</li>
                            </ul>

                            <h2 class="mb-3 mt-4">7. Cancellation Refunds</h2>
                            <p>If you cancel an order before it ships:</p>
                            <ul>
                                <li>Full refund will be processed immediately</li>
                                <li>Refund will appear in your account within 3-5 business days</li>
                            </ul>
                            <p>If you cancel an order after it has shipped:</p>
                            <ul>
                                <li>You may need to return the product to receive a refund</li>
                                <li>Return shipping charges may apply</li>
                            </ul>

                            <h2 class="mb-3 mt-4">8. Partial Refunds</h2>
                            <p>In certain cases, we may offer partial refunds:</p>
                            <ul>
                                <li>If the product has minor defects but is still usable</li>
                                <li>If you want to keep the product despite minor issues</li>
                                <li>If the product is slightly different from the description but functional</li>
                            </ul>

                            <h2 class="mb-3 mt-4">9. Refund for Undelivered Orders</h2>
                            <p>If your order is not delivered within the promised timeframe:</p>
                            <ul>
                                <li>Contact us immediately</li>
                                <li>We'll investigate the delivery status</li>
                                <li>If the order is lost or undelivered, full refund will be processed</li>
                                <li>Refund will be processed within 5-7 business days</li>
                            </ul>

                            <h2 class="mb-3 mt-4">10. Dispute Resolution</h2>
                            <p>If you're not satisfied with our refund decision:</p>
                            <ul>
                                <li>Contact our customer support team for review</li>
                                <li>Provide any additional information or evidence</li>
                                <li>We'll conduct a thorough review and respond within 3-5 business days</li>
                            </ul>

                            <h2 class="mb-3 mt-4">11. Contact Us</h2>
                            <p>For refund inquiries or assistance, please contact us:</p>
                            <ul>
                                <li><strong>Email:</strong> support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Phone:</strong> Check our <a href="<?php echo getBaseUrl(); ?>contact">Contact Page</a> for phone support</li>
                                <li><strong>Contact Form:</strong> <a href="<?php echo getBaseUrl(); ?>contact">Submit a Request</a></li>
                            </ul>
                            <p>Please include your order number in all communications for faster processing.</p>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> Please retain your order confirmation and tracking information until your refund is processed. This will help us assist you more efficiently.
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

