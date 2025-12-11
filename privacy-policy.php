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

    <title>Privacy Policy - <?php echo htmlspecialchars($site_name); ?> | Data Protection</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_name); ?>, Privacy Policy, Data Protection, Personal Information" />
    <meta name="description" content="Read <?php echo htmlspecialchars($site_name); ?>'s privacy policy to understand how we collect, use, and protect your personal information.">
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
                            <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                        </ol>
                    </div>
                </nav>
                <h1>Privacy Policy</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-content py-5">
                        <div class="entry-content">
                            <p class="text-muted mb-4"><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

                            <div class="alert alert-info">
                                <i class="fas fa-shield-alt"></i> <strong>Your Privacy Matters:</strong> We are committed to protecting your personal information and respecting your privacy. This policy explains how we collect, use, and safeguard your data.
                            </div>

                            <h2 class="mb-3">1. Information We Collect</h2>
                            <p>We collect information that you provide directly to us, including:</p>
                            <ul>
                                <li><strong>Personal Information:</strong> Name, email address, phone number, shipping address, billing address</li>
                                <li><strong>Account Information:</strong> Username, password (encrypted), account preferences</li>
                                <li><strong>Payment Information:</strong> Payment method details (processed securely through payment gateways)</li>
                                <li><strong>Order Information:</strong> Purchase history, order details, product preferences</li>
                                <li><strong>Communication Data:</strong> Messages, feedback, customer service interactions</li>
                            </ul>
                            <p>We also automatically collect certain information when you visit our website:</p>
                            <ul>
                                <li>IP address, browser type, device information</li>
                                <li>Website usage data, pages visited, time spent on pages</li>
                                <li>Cookies and similar tracking technologies</li>
                            </ul>

                            <h2 class="mb-3 mt-4">2. How We Use Your Information</h2>
                            <p>We use the information we collect to:</p>
                            <ul>
                                <li>Process and fulfill your orders</li>
                                <li>Send order confirmations and shipping updates</li>
                                <li>Respond to your inquiries and provide customer support</li>
                                <li>Send promotional emails and newsletters (with your consent)</li>
                                <li>Improve our website, products, and services</li>
                                <li>Detect and prevent fraud and security issues</li>
                                <li>Comply with legal obligations</li>
                                <li>Personalize your shopping experience</li>
                            </ul>

                            <h2 class="mb-3 mt-4">3. Information Sharing</h2>
                            <p>We do not sell your personal information. We may share your information with:</p>
                            <ul>
                                <li><strong>Service Providers:</strong> Payment processors, shipping companies, email service providers (who are bound by confidentiality agreements)</li>
                                <li><strong>Legal Requirements:</strong> When required by law, court order, or government regulation</li>
                                <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets (with notice to users)</li>
                                <li><strong>With Your Consent:</strong> When you explicitly authorize us to share your information</li>
                            </ul>

                            <h2 class="mb-3 mt-4">4. Data Security</h2>
                            <p>We implement appropriate technical and organizational measures to protect your personal information:</p>
                            <ul>
                                <li>SSL encryption for data transmission</li>
                                <li>Secure servers and databases</li>
                                <li>Regular security audits and updates</li>
                                <li>Access controls and authentication</li>
                                <li>Employee training on data protection</li>
                            </ul>
                            <p>However, no method of transmission over the internet is 100% secure. While we strive to protect your data, we cannot guarantee absolute security.</p>

                            <h2 class="mb-3 mt-4">5. Cookies and Tracking Technologies</h2>
                            <p>We use cookies and similar technologies to:</p>
                            <ul>
                                <li>Remember your preferences and settings</li>
                                <li>Analyze website traffic and usage patterns</li>
                                <li>Provide personalized content and advertisements</li>
                                <li>Improve website functionality</li>
                            </ul>
                            <p>You can control cookies through your browser settings. However, disabling cookies may affect website functionality.</p>

                            <h2 class="mb-3 mt-4">6. Your Rights</h2>
                            <p>You have the right to:</p>
                            <ul>
                                <li><strong>Access:</strong> Request a copy of your personal information</li>
                                <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                                <li><strong>Deletion:</strong> Request deletion of your personal information (subject to legal requirements)</li>
                                <li><strong>Objection:</strong> Object to processing of your personal information</li>
                                <li><strong>Data Portability:</strong> Request transfer of your data to another service</li>
                                <li><strong>Withdraw Consent:</strong> Withdraw consent for marketing communications</li>
                            </ul>
                            <p>To exercise these rights, please contact us at support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</p>

                            <h2 class="mb-3 mt-4">7. Data Retention</h2>
                            <p>We retain your personal information for as long as necessary to:</p>
                            <ul>
                                <li>Fulfill the purposes for which it was collected</li>
                                <li>Comply with legal obligations</li>
                                <li>Resolve disputes and enforce agreements</li>
                            </ul>
                            <p>When data is no longer needed, we securely delete or anonymize it.</p>

                            <h2 class="mb-3 mt-4">8. Children's Privacy</h2>
                            <p>Our website is not intended for children under 18 years of age. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.</p>

                            <h2 class="mb-3 mt-4">9. Third-Party Links</h2>
                            <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to review their privacy policies.</p>

                            <h2 class="mb-3 mt-4">10. International Data Transfers</h2>
                            <p>Your information may be transferred to and processed in countries other than your country of residence. We ensure appropriate safeguards are in place to protect your data in accordance with this privacy policy.</p>

                            <h2 class="mb-3 mt-4">11. Marketing Communications</h2>
                            <p>If you opt-in to receive marketing communications, you can unsubscribe at any time by:</p>
                            <ul>
                                <li>Clicking the unsubscribe link in our emails</li>
                                <li>Updating your account preferences</li>
                                <li>Contacting us directly</li>
                            </ul>

                            <h2 class="mb-3 mt-4">12. Changes to This Policy</h2>
                            <p>We may update this privacy policy from time to time. We will notify you of significant changes by:</p>
                            <ul>
                                <li>Posting the updated policy on this page</li>
                                <li>Updating the "Last Updated" date</li>
                                <li>Sending an email notification (for material changes)</li>
                            </ul>
                            <p>Your continued use of our website after changes constitutes acceptance of the updated policy.</p>

                            <h2 class="mb-3 mt-4">13. Contact Us</h2>
                            <p>If you have questions, concerns, or requests regarding this privacy policy or your personal information, please contact us:</p>
                            <ul>
                                <li><strong>Email:</strong> support@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Privacy Officer:</strong> privacy@<?php echo strtolower(str_replace(' ', '', $site_name)); ?>.in</li>
                                <li><strong>Contact Form:</strong> <a href="<?php echo getBaseUrl(); ?>contact">Submit a Request</a></li>
                            </ul>

                            <div class="alert alert-success mt-4">
                                <i class="fas fa-lock"></i> <strong>Your Trust is Important:</strong> We are committed to maintaining the highest standards of data protection and privacy. If you have any concerns, please don't hesitate to reach out to us.
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

