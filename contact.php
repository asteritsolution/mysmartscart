<?php
session_start();
include "config.php";
require_once "includes/site-settings.php";

// Fetch contact settings from database
$contact_query = "SELECT * FROM contact_settings ORDER BY id DESC LIMIT 1";
$contact_result = mysqli_query($conn, $contact_query);
$contact = mysqli_fetch_assoc($contact_result);

// If no contact settings exist, use defaults
if (!$contact) {
    $contact = [
        'address' => 'New Delhi, India',
        'phone' => '+91 XXXXXXXXXX',
        'email' => 'support@mysmartscart.in',
        'description' => 'Have questions or need help with your order? Our customer support team is here to assist you. Reach out to us anytime!',
        'map_latitude' => '28.6139',
        'map_longitude' => '77.2090',
        'business_hours_monday_friday' => 'Monday - Friday 10am to 7pm',
        'business_hours_saturday' => 'Saturday - 10am to 5pm',
        'business_hours_sunday' => 'Sunday - Closed'
    ];
}

// Ensure latitude and longitude are valid numbers
$contact['map_latitude'] = is_numeric($contact['map_latitude']) ? $contact['map_latitude'] : '28.6139';
$contact['map_longitude'] = is_numeric($contact['map_longitude']) ? $contact['map_longitude'] : '77.2090';

// Get form data if there were errors
$form_data = isset($_SESSION['contact_form_data']) ? $_SESSION['contact_form_data'] : [];
unset($_SESSION['contact_form_data']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Contact Us - MySmartSCart | Customer Support</title>

    <meta name="keywords" content="MySmartSCart, Contact, Customer Support, Help, Get in Touch" />
    <meta name="description"
        content="Contact MySmartSCart customer support. We're here to help you with orders, queries, and any assistance you need.">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <?php $site_favicon = getSetting('site_favicon', 'assets/images/icons/favicon.png'); ?>
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
    
    <!-- Leaflet CSS (FREE Map - No API Key) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Performance Optimizations -->
    <link rel="stylesheet" href="assets/css/optimizations.css">
    
    <style>
        #map {
            height: 400px;
            width: 100%;
            z-index: 1;
        }
        .map-popup {
            font-family: inherit;
            line-height: 1.6;
        }
        .map-popup address {
            margin: 8px 0;
            font-style: normal;
            color: #666;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>
        <?php include "common/header.php"; ?>
      
        <main class="main contact-two">
            <nav aria-label="breadcrumb" class="breadcrumb-nav mb-0">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                    </ol>
                </div><!-- End .container -->
            </nav>

            <div id="map" data-lat="<?php echo htmlspecialchars($contact['map_latitude']); ?>"
                data-lng="<?php echo htmlspecialchars($contact['map_longitude']); ?>"
                data-address="<?php echo htmlspecialchars($contact['address']); ?>"></div><!-- End #map -->

            <div class="container">
                <?php
                // Display success/error messages
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> ' . (isset($_SESSION['contact_success']) ? $_SESSION['contact_success'] : 'Thank you for contacting us! We will get back to you soon.') . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>';
                    unset($_SESSION['contact_success']);
                }

                if (isset($_GET['error']) && $_GET['error'] == 1) {
                    if (isset($_SESSION['contact_error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> ' . $_SESSION['contact_error'] . '
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                              </div>';
                        unset($_SESSION['contact_error']);
                    }

                    if (isset($_SESSION['contact_errors']) && is_array($_SESSION['contact_errors'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Please fix the following errors:</strong><ul class="mb-0">';
                        foreach ($_SESSION['contact_errors'] as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                              </div>';
                        unset($_SESSION['contact_errors']);
                    }
                }
                ?>

                <div class="row ">
                    <div class="col-md-6">
                        <h2 class="mb-1 pb-2"><strong>Contact Us</strong></h2>

                        <form action="contact-handler.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required-field">
                                        <label for="contact-name">Your name</label>
                                        <input type="text" class="form-control" id="contact-name" name="contact-name"
                                            value="<?php echo isset($form_data['contact-name']) ? htmlspecialchars($form_data['contact-name']) : ''; ?>"
                                            required>
                                    </div><!-- End .form-group -->
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group required-field">
                                        <label for="contact-email">Your email address</label>
                                        <input type="email" class="form-control" id="contact-email" name="contact-email"
                                            value="<?php echo isset($form_data['contact-email']) ? htmlspecialchars($form_data['contact-email']) : ''; ?>"
                                            required>
                                    </div><!-- End .form-group -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact-subject">Subject</label>
                                <input type="text" class="form-control" id="contact-subject" name="contact-subject"
                                    value="<?php echo isset($form_data['contact-subject']) ? htmlspecialchars($form_data['contact-subject']) : ''; ?>">
                            </div><!-- End .form-group -->

                            <div class="form-group mb-0">
                                <label for="contact-message">Your Message</label>
                                <textarea cols="30" rows="5" id="contact-message" class="form-control"
                                    name="contact-message"
                                    required><?php echo isset($form_data['contact-message']) ? htmlspecialchars($form_data['contact-message']) : ''; ?></textarea>
                            </div><!-- End .form-group -->

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary ls-10">Send Message</button>
                            </div><!-- End .form-footer -->
                        </form>
                    </div><!-- End .col-md-6 -->

                    <div class="col-md-6">
                        <h2 class="contact-title"><strong>Get in touch</strong></h2>
                        <p><?php echo htmlspecialchars($contact['description']); ?></p>

                        <hr class="mt-3 mb-0" />

                        <div class="contact-info mb-2">
                            <h2 class="contact-title"><strong>The Office</strong></h2>

                            <div class="porto-sicon-box d-flex align-items-center">
                                <div class="porto-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h3 class="porto-sicon-title">
                                    <strong>Address:</strong> <?php echo htmlspecialchars($contact['address']); ?>
                                </h3>
                            </div>
                            <div class="porto-sicon-box  d-flex align-items-center">
                                <div class="porto-icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <h3 class="porto-sicon-title">
                                    <strong>Phone:</strong> <a
                                        href="tel:<?php echo htmlspecialchars($contact['phone']); ?>"><?php echo htmlspecialchars($contact['phone']); ?></a>
                                </h3>
                            </div>
                            <div class="porto-sicon-box  d-flex align-items-center">
                                <div class="porto-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <h3 class="porto-sicon-title">
                                    <strong>Email:</strong> <a
                                        href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a>
                                </h3>
                            </div>
                        </div><!-- End .contact-info -->

                        <hr class="mt-1 mb-0" />

                        <div class="contact-time">
                            <h2 class="contact-title"><strong>Business Hours</strong></h2>

                            <div class="porto-sicon-box d-flex align-items-center">
                                <div class="porto-icon">
                                    <i class="far fa-clock"></i>
                                </div>
                                <h3 class="porto-sicon-title">
                                    <?php echo htmlspecialchars($contact['business_hours_monday_friday']); ?></h3>
                            </div>

                            <div class="porto-sicon-box  d-flex align-items-center">
                                <div class="porto-icon">
                                    <i class="far fa-clock"></i>
                                </div>
                                <h3 class="porto-sicon-title">
                                    <?php echo htmlspecialchars($contact['business_hours_saturday']); ?></h3>
                            </div>

                            <div class="porto-sicon-box d-flex align-items-center">
                                <div class="porto-icon"><i class="far fa-clock"></i></div>
                                <h3 class="porto-sicon-title">
                                    <?php echo htmlspecialchars($contact['business_hours_sunday']); ?></h3>
                            </div>
                        </div>
                    </div><!-- End .col-md-6 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
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
    <script src="assets/js/nouislider.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
    
    <!-- Leaflet JS (FREE Map - No API Key Required) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="assets/js/map-leaflet.js"></script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e1a1ac7ee1dd',t:'MTc2NDE1NDgxOA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

</html>