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

    <title>Shipping Policy - <?php echo htmlspecialchars($site_name); ?> | Delivery Information</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_name); ?>, Shipping Policy, Delivery Information, Shipping Charges" />
    <meta name="description" content="Learn about <?php echo htmlspecialchars($site_name); ?>'s shipping policy, delivery times, shipping charges, and tracking information.">
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
                            <li class="breadcrumb-item active" aria-current="page">Shipping Policy</li>
                        </ol>
                    </div>
                </nav>
                <h1>Shipping Policy</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-content py-5">
                        <div class="entry-content">
                            <p class="text-muted mb-4"><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

                            <div class="alert alert-success">
                                <i class="fas fa-truck"></i> <strong>Fast & Reliable Shipping:</strong> We deliver to locations across India with secure packaging and timely delivery.
                            </div>

                            <h2 class="mb-3">1. Shipping Locations</h2>
                            <p>We currently ship to:</p>
                            <ul>
                                <li><strong>All Major Cities:</strong> Delhi, Mumbai, Bangalore, Chennai, Kolkata, Hyderabad, Pune, and more</li>
                                <li><strong>Tier 2 & 3 Cities:</strong> Most cities and towns across India</li>
                                <li><strong>Remote Areas:</strong> Some remote locations may have limited service or additional charges</li>
                            </ul>
                            <p>At checkout, enter your pin code to check if we deliver to your location. If your area is not serviceable, we'll notify you during checkout.</p>

                            <h2 class="mb-3 mt-4">2. Shipping Charges</h2>
                            <p>Shipping charges are calculated based on:</p>
                            <ul>
                                <li>Delivery location (pin code)</li>
                                <li>Order value</li>
                                <li>Product weight and dimensions</li>
                                <li>Shipping method selected</li>
                            </ul>
                            <p><strong>Free Shipping:</strong> We offer free shipping on orders above <strong>₹499</strong> (standard delivery).</p>
                            <p><strong>Shipping Charges:</strong> For orders below ₹499, standard shipping charges apply (typically ₹50-100 depending on location).</p>
                            <p><strong>Express Delivery:</strong> Additional charges apply for express/same-day delivery options.</p>

                            <h2 class="mb-3 mt-4">3. Delivery Timeframes</h2>
                            <p>Estimated delivery times (from order confirmation):</p>
                            <ul>
                                <li><strong>Standard Delivery:</strong> 5-7 business days</li>
                                <li><strong>Express Delivery:</strong> 2-3 business days (available in select cities)</li>
                                <li><strong>Same-Day Delivery:</strong> Available in major metros (orders placed before 12 PM)</li>
                            </ul>
                            <p><strong>Note:</strong> Delivery times are estimates and may vary due to:</p>
                            <ul>
                                <li>Location and accessibility</li>
                                <li>Weather conditions</li>
                                <li>Holidays and festivals</li>
                                <li>Custom clearance (for international shipments, if applicable)</li>
                                <li>Force majeure events</li>
                            </ul>

                            <h2 class="mb-3 mt-4">4. Order Processing</h2>
                            <p>Once you place an order:</p>
                            <ol>
                                <li><strong>Order Confirmation:</strong> You'll receive an email confirmation immediately after placing the order</li>
                                <li><strong>Processing:</strong> We process orders within 24-48 hours (excluding weekends and holidays)</li>
                                <li><strong>Packing:</strong> Items are carefully packed to prevent damage during transit</li>
                                <li><strong>Shipping:</strong> You'll receive a shipping confirmation email with tracking details</li>
                                <li><strong>In Transit:</strong> Track your order using the provided tracking number</li>
                                <li><strong>Delivery:</strong> Product is delivered to your specified address</li>
                            </ol>

                            <h2 class="mb-3 mt-4">5. Order Tracking</h2>
                            <p>Track your order easily:</p>
                            <ul>
                                <li><strong>Email Notification:</strong> You'll receive tracking details via email once your order ships</li>
                                <li><strong>Order History:</strong> Log in to your account and go to "My Orders" to track your shipment</li>
                                <li><strong>Tracking Number:</strong> Use the tracking number on the courier company's website</li>
                                <li><strong>SMS Updates:</strong> Receive SMS updates on your registered mobile number</li>
                            </ul>

                            <h2 class="mb-3 mt-4">6. Multiple Items in One Order</h2>
                            <p>If your order contains multiple items:</p>
                            <ul>
                                <li>Items may be shipped together or separately, depending on availability</li>
                                <li>If items ship separately, you'll receive separate tracking numbers</li>
                                <li>Shipping charges are calculated for the entire order, not per item</li>
                                <li>If one item is delayed, other items may ship first</li>
                            </ul>

                            <h2 class="mb-3 mt-4">7. Delivery Address</h2>
                            <p>Please ensure your delivery address is:</p>
                            <ul>
                                <li>Complete and accurate (including pin code, landmark, apartment number)</li>
                                <li>Accessible during delivery hours</li>
                                <li>Updated in your account if you've moved</li>
                            </ul>
                            <p><strong>Address Changes:</strong> You can update your delivery address before the order ships. Once shipped, address changes may not be possible or may incur additional charges.</p>

                            <h2 class="mb-3 mt-4">8. Delivery Attempts</h2>
                            <p>Our delivery partners will attempt delivery:</p>
                            <ul>
                                <li><strong>First Attempt:</strong> During regular delivery hours</li>
                                <li><strong>Second Attempt:</strong> If first attempt is unsuccessful (may be next day)</li>
                                <li><strong>Third Attempt:</strong> Final attempt before returning to sender</li>
                            </ul>
                            <p><strong>If Unavailable:</strong> If you're not available, the delivery partner may:</p>
                            <ul>
                                <li>Leave the package with a neighbor or security guard (with your permission)</li>
                                <li>Leave it at your doorstep (if safe and authorized)</li>
                                <li>Schedule a re-delivery attempt</li>
                            </ul>

                            <h2 class="mb-3 mt-4">9. Failed Deliveries</h2>
                            <p>If delivery fails due to:</p>
                            <ul>
                                <li><strong>Incorrect Address:</strong> We'll contact you to update the address</li>
                                <li><strong>Unreachable:</strong> Multiple delivery attempts failed</li>
                                <li><strong>Refused:</strong> Package was refused at delivery</li>
                            </ul>
                            <p>The order may be returned to us, and a refund will be processed (minus shipping charges).</p>

                            <h2 class="mb-3 mt-4">10. International Shipping</h2>
                            <p>Currently, we primarily ship within India. For international shipping:</p>
                            <ul>
                                <li>Please contact us at support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li>International shipping charges and delivery times vary by country</li>
                                <li>Customs duties and taxes are the customer's responsibility</li>
                            </ul>

                            <h2 class="mb-3 mt-4">11. Packaging</h2>
                            <p>We ensure secure packaging:</p>
                            <ul>
                                <li>Products are packed in appropriate boxes or envelopes</li>
                                <li>Fragile items are wrapped with extra protection</li>
                                <li>Original product packaging is preserved when possible</li>
                                <li>All packages are sealed securely</li>
                            </ul>

                            <h2 class="mb-3 mt-4">12. Delivery Delays</h2>
                            <p>If your order is delayed:</p>
                            <ul>
                                <li>Check the tracking information for updates</li>
                                <li>Contact us if the delay is significant (beyond estimated timeframe)</li>
                                <li>We'll investigate and keep you informed</li>
                                <li>If the delay is due to our error, we'll compensate accordingly</li>
                            </ul>

                            <h2 class="mb-3 mt-4">13. Damaged or Lost Shipments</h2>
                            <p>If your package is:</p>
                            <ul>
                                <li><strong>Damaged:</strong> Please refuse delivery or contact us immediately. We'll arrange a replacement or refund.</li>
                                <li><strong>Lost:</strong> Contact us with your order number. We'll investigate and resolve the issue.</li>
                            </ul>
                            <p>We'll work with the courier company to resolve the issue and ensure you receive your order or a full refund.</p>

                            <h2 class="mb-3 mt-4">14. Contact Us</h2>
                            <p>For shipping inquiries or assistance, please contact us:</p>
                            <ul>
                                <li><strong>Email:</strong> support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Phone:</strong> Check our <a href="<?php echo getBaseUrl(); ?>contact">Contact Page</a> for phone support</li>
                                <li><strong>Contact Form:</strong> <a href="<?php echo getBaseUrl(); ?>contact">Submit a Request</a></li>
                            </ul>
                            <p>Please include your order number in all communications for faster assistance.</p>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> Please ensure someone is available to receive the package during delivery hours. Keep your order confirmation and tracking information handy until delivery is complete.
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

