-- =====================================================
-- MySmartSCart - Site Settings Table
-- Run this SQL to create the site settings table
-- =====================================================

-- Drop table if exists (for fresh install)
DROP TABLE IF EXISTS `site_settings`;

-- Create site_settings table
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','color','boolean') DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `setting_label` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_setting_key` (`setting_key`),
  KEY `idx_setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`, `setting_group`, `setting_label`) VALUES

-- General Settings
('site_name', 'MySmartSCart', 'text', 'general', 'Site Name'),
('site_tagline', 'Shop Smart, Live Smart!', 'text', 'general', 'Site Tagline'),
('site_description', 'Your one-stop destination for trendy products at unbeatable prices. Fast delivery across India!', 'textarea', 'general', 'Site Description'),
('site_keywords', 'online shopping, dropshipping, ecommerce, buy online, best deals, india', 'textarea', 'general', 'SEO Keywords'),

-- Logo & Favicon
('site_logo', 'assets/images/logo.png', 'image', 'branding', 'Site Logo'),
('site_logo_dark', 'assets/images/logo-dark.png', 'image', 'branding', 'Site Logo (Dark)'),
('site_favicon', 'assets/images/icons/favicon.ico', 'image', 'branding', 'Favicon'),
('footer_logo', 'assets/images/logo.png', 'image', 'branding', 'Footer Logo'),

-- Header Settings
('header_top_text', '🔥 <b>MEGA SALE</b> - Up to 70% OFF!', 'text', 'header', 'Top Bar Announcement Text'),
('header_top_small_text', '* Free Shipping on Orders ₹499+', 'text', 'header', 'Top Bar Small Text'),
('header_show_currency', '1', 'boolean', 'header', 'Show Currency Selector'),
('header_show_language', '1', 'boolean', 'header', 'Show Language Selector'),
('header_phone', '+91 XXXXXXXXXX', 'text', 'header', 'Contact Phone (Header)'),

-- Social Media Links
('social_facebook', 'https://facebook.com/mysmartscart', 'text', 'social', 'Facebook URL'),
('social_twitter', 'https://twitter.com/mysmartscart', 'text', 'social', 'Twitter URL'),
('social_instagram', 'https://instagram.com/mysmartscart', 'text', 'social', 'Instagram URL'),
('social_youtube', '', 'text', 'social', 'YouTube URL'),
('social_whatsapp', '', 'text', 'social', 'WhatsApp Number'),

-- Footer Settings
('footer_about_text', 'MySmartSCart brings you the best deals on trending products across India. From electronics to fashion, home essentials to gadgets - we have got everything you need at unbeatable prices with fast delivery!', 'textarea', 'footer', 'Footer About Text'),
('footer_copyright', '© MySmartSCart {year}. All Rights Reserved. | mysmartscart.in', 'text', 'footer', 'Copyright Text'),
('footer_newsletter_text', 'Subscribe to get exclusive deals, discounts & new arrivals straight to your inbox!', 'textarea', 'footer', 'Newsletter Text'),

-- Quick Links (JSON format)
('footer_quick_links', '[{"title":"About Us","url":"about.php"},{"title":"Shop All","url":"shop.php"},{"title":"Contact Us","url":"contact.php"},{"title":"My Wishlist","url":"wishlist.php"},{"title":"Shopping Cart","url":"cart.php"},{"title":"My Account","url":"dashboard.php"},{"title":"Track Order","url":"#"},{"title":"FAQs","url":"#"}]', 'textarea', 'footer', 'Footer Quick Links (JSON)'),

-- Why Choose Us Section
('footer_why_choose_us', '[{"title":"Best Prices","subtitle":"Up to 70% OFF","url":"shop.php"},{"title":"Fast Delivery","subtitle":"3-7 Business Days","url":"shop.php"},{"title":"Secure Shopping","subtitle":"100% Safe & Secure","url":"about.php"}]', 'textarea', 'footer', 'Why Choose Us (JSON)'),

-- Theme Colors
('color_primary', '#f68b28', 'color', 'theme', 'Primary Color (Orange)'),
('color_secondary', '#1a237e', 'color', 'theme', 'Secondary Color (Royal Blue)'),
('color_header_top', '#f68b28', 'color', 'theme', 'Header Top Bar Color'),
('color_top_notice', '#1a237e', 'color', 'theme', 'Top Notice Bar Color'),

-- Payment Icons
('show_payment_icons', '1', 'boolean', 'footer', 'Show Payment Icons'),

-- Analytics
('google_analytics_id', '', 'text', 'analytics', 'Google Analytics ID'),
('facebook_pixel_id', '', 'text', 'analytics', 'Facebook Pixel ID'),

-- Header Menu (JSON)
('header_menu', '[{"title":"Home","url":"index.php","icon":"","target":"_self","enabled":1},{"title":"Shop","url":"shop.php","icon":"","target":"_self","enabled":1},{"title":"About Us","url":"about.php","icon":"","target":"_self","enabled":1},{"title":"Contact","url":"contact.php","icon":"","target":"_self","enabled":1}]', 'textarea', 'menu', 'Header Navigation Menu (JSON)'),

-- Header Top Links (JSON)
('header_top_links', '[{"title":"My Wishlist","url":"wishlist.php","enabled":1},{"title":"About Us","url":"about.php","enabled":1},{"title":"Contact Us","url":"contact.php","enabled":1},{"title":"Cart","url":"cart.php","enabled":1}]', 'textarea', 'menu', 'Header Top Links (JSON)');

-- Add index for faster lookups
ALTER TABLE `site_settings` ADD INDEX `idx_group_key` (`setting_group`, `setting_key`);

