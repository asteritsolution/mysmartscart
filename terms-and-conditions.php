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

    <title>Terms and Conditions - <?php echo htmlspecialchars($site_name); ?> | Legal Terms</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_name); ?>, Terms and Conditions, Legal Terms, User Agreement" />
    <meta name="description" content="Read the terms and conditions for using <?php echo htmlspecialchars($site_name); ?>. Understand your rights and responsibilities when shopping with us.">
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
                            <li class="breadcrumb-item active" aria-current="page">Terms and Conditions</li>
                        </ol>
                    </div>
                </nav>
                <h1>Terms and Conditions</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-content py-5">
                        <div class="entry-content">
                            <p class="text-muted mb-4"><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

                            <h2 class="mb-3">1. Acceptance of Terms</h2>
                            <p>By accessing and using <?php echo htmlspecialchars($site_name); ?>, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

                            <h2 class="mb-3 mt-4">2. Use License</h2>
                            <p>Permission is granted to temporarily download one copy of the materials on <?php echo htmlspecialchars($site_name); ?>'s website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                            <ul>
                                <li>Modify or copy the materials</li>
                                <li>Use the materials for any commercial purpose or for any public display (commercial or non-commercial)</li>
                                <li>Attempt to decompile or reverse engineer any software contained on <?php echo htmlspecialchars($site_name); ?>'s website</li>
                                <li>Remove any copyright or other proprietary notations from the materials</li>
                            </ul>

                            <h2 class="mb-3 mt-4">3. Account Registration</h2>
                            <p>To make a purchase on our website, you may be required to create an account. You are responsible for:</p>
                            <ul>
                                <li>Maintaining the confidentiality of your account and password</li>
                                <li>All activities that occur under your account</li>
                                <li>Providing accurate and complete information</li>
                                <li>Notifying us immediately of any unauthorized use of your account</li>
                            </ul>

                            <h2 class="mb-3 mt-4">4. Product Information</h2>
                            <p>We strive to provide accurate product descriptions, images, and pricing. However, we do not warrant that product descriptions or other content on this site is accurate, complete, reliable, current, or error-free.</p>

                            <h2 class="mb-3 mt-4">5. Pricing and Payment</h2>
                            <p>All prices are listed in Indian Rupees (₹) unless otherwise stated. We reserve the right to change prices at any time without prior notice. Payment must be made at the time of purchase through our accepted payment methods.</p>

                            <h2 class="mb-3 mt-4">6. Orders and Acceptance</h2>
                            <p>Your order is an offer to purchase products from us. We reserve the right to accept or reject your order for any reason, including product availability, errors in pricing or product information, or fraud prevention.</p>

                            <h2 class="mb-3 mt-4">7. Shipping and Delivery</h2>
                            <p>We will make every effort to deliver products within the estimated timeframe. However, delivery times are estimates and not guaranteed. Risk of loss and title for products pass to you upon delivery to the carrier.</p>

                            <h2 class="mb-3 mt-4">8. Returns and Refunds</h2>
                            <p>Please refer to our <a href="<?php echo getBaseUrl(); ?>return-policy">Return Policy</a> and <a href="<?php echo getBaseUrl(); ?>refund-policy">Refund Policy</a> for detailed information about returns and refunds.</p>

                            <h2 class="mb-3 mt-4">9. Intellectual Property</h2>
                            <p>All content on this website, including text, graphics, logos, images, and software, is the property of <?php echo htmlspecialchars($site_name); ?> or its content suppliers and is protected by copyright and other intellectual property laws.</p>

                            <h2 class="mb-3 mt-4">10. Prohibited Uses</h2>
                            <p>You may not use our website:</p>
                            <ul>
                                <li>In any way that violates any applicable law or regulation</li>
                                <li>To transmit any malicious code or viruses</li>
                                <li>To collect or track personal information of others</li>
                                <li>To spam, phish, or engage in any fraudulent activity</li>
                            </ul>

                            <h2 class="mb-3 mt-4">11. Limitation of Liability</h2>
                            <p>In no event shall <?php echo htmlspecialchars($site_name); ?> or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on <?php echo htmlspecialchars($site_name); ?>'s website.</p>

                            <h2 class="mb-3 mt-4">12. Indemnification</h2>
                            <p>You agree to indemnify and hold harmless <?php echo htmlspecialchars($site_name); ?>, its officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses arising out of your use of the website or violation of these terms.</p>

                            <h2 class="mb-3 mt-4">13. Modifications</h2>
                            <p><?php echo htmlspecialchars($site_name); ?> may revise these terms of service at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.</p>

                            <h2 class="mb-3 mt-4">14. Governing Law</h2>
                            <p>These terms and conditions are governed by and construed in accordance with the laws of India, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>

                            <h2 class="mb-3 mt-4">15. Contact Information</h2>
                            <p>If you have any questions about these Terms and Conditions, please contact us:</p>
                            <ul>
                                <li><strong>Email:</strong> support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Website:</strong> <a href="<?php echo getBaseUrl(); ?>contact">Contact Us</a></li>
                            </ul>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i> <strong>Note:</strong> These terms and conditions may be updated from time to time. Please review this page periodically for any changes.
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

