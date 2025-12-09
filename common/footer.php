<?php
// Include site settings if not already included
if (!function_exists('getSetting')) {
    require_once __DIR__ . '/../includes/site-settings.php';
}

// Get dynamic values
$site_name = getSetting('site_name', 'MySmartSCart');
$footer_logo = getSetting('footer_logo', 'assets/images/logo.png');
$footer_about_text = getSetting('footer_about_text', 'MySmartSCart brings you the best deals on trending products across India.');
$footer_newsletter_text = getSetting('footer_newsletter_text', 'Subscribe to get exclusive deals!');
$copyright_text = getCopyrightText();
$show_payment_icons = getSetting('show_payment_icons', '1') == '1';
$social_links = getSocialLinks();
$quick_links = getFooterQuickLinks();
$why_choose_us = getWhyChooseUs();
?>
<footer class="footer bg-dark position-relative">
    <div class="footer-middle">
        <div class="container position-static">
            <div class="row">
                <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                    <div class="widget">
                        <h4 class="widget-title">About <?php echo htmlspecialchars($site_name); ?></h4>
                        <a href="/mysmartscart/">
                            <img src="<?php echo htmlspecialchars($footer_logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?>" class="logo-footer">
                        </a>
                        <p class="m-b-4 ls-0"><strong>Your Smart Shopping Destination</strong><br>
                        <?php echo htmlspecialchars($footer_about_text); ?></p>
                        <div class="social-icons">
                            <?php if (!empty($social_links['facebook']) && $social_links['facebook'] != '#'): ?>
                            <a href="<?php echo htmlspecialchars($social_links['facebook']); ?>" class="social-icon social-facebook icon-facebook" target="_blank"
                                title="Facebook"></a>
                            <?php endif; ?>
                            <?php if (!empty($social_links['twitter']) && $social_links['twitter'] != '#'): ?>
                            <a href="<?php echo htmlspecialchars($social_links['twitter']); ?>" class="social-icon social-twitter icon-twitter" target="_blank"
                                title="Twitter"></a>
                            <?php endif; ?>
                            <?php if (!empty($social_links['instagram']) && $social_links['instagram'] != '#'): ?>
                            <a href="<?php echo htmlspecialchars($social_links['instagram']); ?>" class="social-icon social-instagram icon-instagram" target="_blank"
                                title="Instagram"></a>
                            <?php endif; ?>
                            <?php if (!empty($social_links['youtube'])): ?>
                            <a href="<?php echo htmlspecialchars($social_links['youtube']); ?>" class="social-icon social-youtube icon-youtube" target="_blank"
                                title="YouTube"></a>
                            <?php endif; ?>
                            <?php if (!empty($social_links['whatsapp'])): ?>
                            <a href="https://wa.me/<?php echo htmlspecialchars(preg_replace('/[^0-9]/', '', $social_links['whatsapp'])); ?>" class="social-icon social-whatsapp" target="_blank"
                                title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <?php endif; ?>
                        </div>
                        <!-- End .social-icons -->
                    </div>
                    <!-- End .widget -->
                </div>
                <!-- End .col-lg-3 -->

                <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                    <div class="widget mb-2">
                        <h4 class="widget-title pb-1">Quick Links</h4>

                        <ul class="links footerlinkmain">
                            <?php foreach ($quick_links as $link): ?>
                            <li><a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- End .widget -->
                </div>
                <!-- End .col-lg-3 -->

                <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                    <div class="widget widget-post">
                        <h4 class="widget-title pb-1">Why Choose Us</h4>

                        <ul class="links">
                            <?php foreach ($why_choose_us as $item): ?>
                            <li><a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>"><?php echo htmlspecialchars($item['title']); ?><br /><span class="font1"><?php echo htmlspecialchars($item['subtitle'] ?? ''); ?></span></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- End .widget -->
                </div>
                <!-- End .col-lg-3 -->

                <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                    <div class="widget widget-newsletter">
                        <h4 class="widget-title">Get Exclusive Deals</h4>
                        <p class="mb-2 ls-0"><?php echo htmlspecialchars($footer_newsletter_text); ?>
                        </p>
                        <form action="#" class="mb-0">
                            <input type="email" class="form-control" placeholder="Email address" required>

                            <input type="submit" class="btn btn-primary ls-10 shadow-none" value="Subscribe">
                        </form>
                    </div>
                    <!-- End .widget -->
                </div>
                <!-- End .col-lg-3 -->
            </div>
            <!-- End .row -->
        </div>
        <!-- End .container -->
    </div>
    <!-- End .footer-middle -->

    <div class="container">
        <div class="footer-bottom d-sm-flex align-items-center">
            <div class="footer-left">
                <span class="footer-copyright"><?php echo $copyright_text; ?></span>
            </div>

            <?php if ($show_payment_icons): ?>
            <div class="footer-right ml-auto mt-1 mt-sm-0">
                <div class="payment-icons mr-0">
                    <span class="payment-icon visa"
                        style="background-image: url(assets/images/payments/payment-visa.svg)"></span>
                    <span class="payment-icon paypal"
                        style="background-image: url(assets/images/payments/payment-paypal.svg)"></span>
                    <span class="payment-icon stripe"
                        style="background-image: url(assets/images/payments/payment-stripe.png)"></span>
                    <span class="payment-icon verisign"
                        style="background-image:  url(assets/images/payments/payment-verisign.svg)"></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- End .footer-bottom -->
</footer>
