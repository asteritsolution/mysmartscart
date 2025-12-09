<?php
/**
 * Dynamic Top Notice Bar
 * Include this file in all pages instead of hardcoding the top notice
 */

// Include site settings if not already included
if (!function_exists('getSetting')) {
    require_once __DIR__ . '/../includes/site-settings.php';
}

// Get dynamic settings
$top_notice_text = getSetting('header_top_text', '🔥 <b>MEGA SALE</b> - Up to 70% OFF!');
$top_notice_small = getSetting('header_top_small_text', '* Free Shipping on Orders ₹499+');
?>
<div class="top-notice text-white">
    <div class="container text-center">
        <h5 class="d-inline-block mb-0"><?php echo $top_notice_text; ?></h5>
        <a href="about" class="category">ABOUT US</a>
        <a href="shop" class="category ml-2 mr-3">SHOP NOW</a>
        <small><?php echo htmlspecialchars($top_notice_small); ?></small>
        <button title="Close (Esc)" type="button" class="mfp-close">×</button>
    </div>
    <!-- End .container -->
</div>
<!-- End .top-notice -->

