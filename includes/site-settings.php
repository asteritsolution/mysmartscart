<?php
/**
 * MySmartSCart - Site Settings Helper
 * Fetches and caches site settings from database
 */

// Ensure config is loaded
if (!isset($conn)) {
    require_once __DIR__ . '/../config.php';
}

// Global variable to store settings (cache in memory)
$GLOBALS['site_settings_cache'] = null;

/**
 * Get all site settings
 * @return array Associative array of settings
 */
function getSiteSettings() {
    global $conn;
    
    // Return cached settings if available
    if ($GLOBALS['site_settings_cache'] !== null) {
        return $GLOBALS['site_settings_cache'];
    }
    
    $settings = [];
    
    // Check if table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'site_settings'");
    if (mysqli_num_rows($table_check) == 0) {
        // Return default settings if table doesn't exist
        return getDefaultSettings();
    }
    
    $query = "SELECT setting_key, setting_value FROM site_settings";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } else {
        // Return defaults if no settings found
        return getDefaultSettings();
    }
    
    // Cache settings
    $GLOBALS['site_settings_cache'] = $settings;
    
    return $settings;
}

/**
 * Get a single setting value
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed Setting value or default
 */
function getSetting($key, $default = '') {
    $settings = getSiteSettings();
    return isset($settings[$key]) && !empty($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Get default settings
 * @return array Default settings array
 */
function getDefaultSettings() {
    return [
        // General
        'site_name' => 'MySmartSCart',
        'site_tagline' => 'Shop Smart, Live Smart!',
        'site_description' => 'Your one-stop destination for trendy products at unbeatable prices.',
        'site_keywords' => 'online shopping, dropshipping, ecommerce',
        
        // Branding
        'site_logo' => 'assets/images/logo.png',
        'site_logo_dark' => 'assets/images/logo-dark.png',
        'site_favicon' => 'assets/images/icons/favicon.ico',
        'footer_logo' => 'assets/images/logo.png',
        
        // Header
        'header_top_text' => '🔥 <b>MEGA SALE</b> - Up to 70% OFF!',
        'header_top_small_text' => '* Free Shipping on Orders ₹499+',
        'header_show_currency' => '1',
        'header_show_language' => '1',
        'header_phone' => '+91 XXXXXXXXXX',
        
        // Social
        'social_facebook' => '#',
        'social_twitter' => '#',
        'social_instagram' => '#',
        'social_youtube' => '',
        'social_whatsapp' => '',
        
        // Footer
        'footer_about_text' => 'MySmartSCart brings you the best deals on trending products across India.',
        'footer_copyright' => '© MySmartSCart {year}. All Rights Reserved.',
        'footer_newsletter_text' => 'Subscribe to get exclusive deals!',
        'footer_quick_links' => '[]',
        'footer_why_choose_us' => '[]',
        
        // Theme
        'color_primary' => '#f68b28',
        'color_secondary' => '#1a237e',
        'color_header_top' => '#f68b28',
        'color_top_notice' => '#1a237e',
        
        // Other
        'show_payment_icons' => '1',
        'google_analytics_id' => '',
        'facebook_pixel_id' => ''
    ];
}

/**
 * Update a setting value
 * @param string $key Setting key
 * @param mixed $value New value
 * @return bool Success status
 */
function updateSetting($key, $value) {
    global $conn;
    
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);
    
    // Check if setting exists
    $check = mysqli_query($conn, "SELECT id FROM site_settings WHERE setting_key = '$key'");
    
    if (mysqli_num_rows($check) > 0) {
        $query = "UPDATE site_settings SET setting_value = '$value' WHERE setting_key = '$key'";
    } else {
        $query = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value')";
    }
    
    $result = mysqli_query($conn, $query);
    
    // Clear cache
    $GLOBALS['site_settings_cache'] = null;
    
    return $result;
}

/**
 * Get header main navigation menu
 * @return array Menu items (only enabled items)
 */
function getHeaderMenu() {
    $menu = getSetting('header_menu', '[]');
    $decoded = json_decode($menu, true);
    
    if (!is_array($decoded) || empty($decoded)) {
        // Return default menu
        return [
            ['title' => 'Home', 'url' => 'index.php', 'icon' => '', 'target' => '_self', 'enabled' => 1],
            ['title' => 'Shop', 'url' => 'shop.php', 'icon' => '', 'target' => '_self', 'enabled' => 1],
            ['title' => 'About Us', 'url' => 'about.php', 'icon' => '', 'target' => '_self', 'enabled' => 1],
            ['title' => 'Contact', 'url' => 'contact.php', 'icon' => '', 'target' => '_self', 'enabled' => 1]
        ];
    }
    
    // Filter only enabled items
    return array_filter($decoded, function($item) {
        return isset($item['enabled']) ? $item['enabled'] : true;
    });
}

/**
 * Get header top links
 * @return array Links (only enabled items)
 */
function getHeaderTopLinks() {
    $links = getSetting('header_top_links', '[]');
    $decoded = json_decode($links, true);
    
    if (!is_array($decoded) || empty($decoded)) {
        // Return default links
        return [
            ['title' => 'My Wishlist', 'url' => 'wishlist.php', 'enabled' => 1],
            ['title' => 'About Us', 'url' => 'about.php', 'enabled' => 1],
            ['title' => 'Contact Us', 'url' => 'contact.php', 'enabled' => 1],
            ['title' => 'Cart', 'url' => 'cart.php', 'enabled' => 1]
        ];
    }
    
    // Filter only enabled items
    return array_filter($decoded, function($item) {
        return isset($item['enabled']) ? $item['enabled'] : true;
    });
}

/**
 * Get footer quick links as array
 * @return array Links array (only enabled items)
 */
function getFooterQuickLinks() {
    $links = getSetting('footer_quick_links', '[]');
    $decoded = json_decode($links, true);
    
    if (!is_array($decoded) || empty($decoded)) {
        // Return default links
        return [
            ['title' => 'About Us', 'url' => 'about', 'enabled' => 1],
            ['title' => 'Shop All', 'url' => 'shop', 'enabled' => 1],
            ['title' => 'Contact Us', 'url' => 'contact', 'enabled' => 1],
            ['title' => 'My Wishlist', 'url' => 'wishlist', 'enabled' => 1],
            ['title' => 'Shopping Cart', 'url' => 'cart', 'enabled' => 1],
            ['title' => 'My Account', 'url' => 'dashboard', 'enabled' => 1]
        ];
    }
    
    // Filter only enabled items
    return array_filter($decoded, function($item) {
        return isset($item['enabled']) ? $item['enabled'] : true;
    });
}

/**
 * Get "Why Choose Us" items as array
 * @return array Items array (only enabled items)
 */
function getWhyChooseUs() {
    $items = getSetting('footer_why_choose_us', '[]');
    $decoded = json_decode($items, true);
    
    if (!is_array($decoded) || empty($decoded)) {
        // Return default items
        return [
            ['title' => 'Best Prices', 'subtitle' => 'Up to 70% OFF', 'url' => 'shop', 'enabled' => 1],
            ['title' => 'Fast Delivery', 'subtitle' => '3-7 Business Days', 'url' => 'shop', 'enabled' => 1],
            ['title' => 'Secure Shopping', 'subtitle' => '100% Safe & Secure', 'url' => 'about', 'enabled' => 1]
        ];
    }
    
    // Filter only enabled items
    return array_filter($decoded, function($item) {
        return isset($item['enabled']) ? $item['enabled'] : true;
    });
}

/**
 * Get copyright text with year replaced
 * @return string Copyright text
 */
function getCopyrightText() {
    $text = getSetting('footer_copyright', '© MySmartSCart {year}. All Rights Reserved.');
    return str_replace('{year}', date('Y'), $text);
}

/**
 * Get social media links
 * @return array Social links
 */
function getSocialLinks() {
    return [
        'facebook' => getSetting('social_facebook', '#'),
        'twitter' => getSetting('social_twitter', '#'),
        'instagram' => getSetting('social_instagram', '#'),
        'youtube' => getSetting('social_youtube', ''),
        'whatsapp' => getSetting('social_whatsapp', '')
    ];
}

/**
 * Clear settings cache
 */
function clearSettingsCache() {
    $GLOBALS['site_settings_cache'] = null;
}

/**
 * Generate SEO-friendly product URL
 * @param string $slug Product slug
 * @return string SEO-friendly URL
 */
function getProductUrl($slug) {
    if (empty($slug)) {
        return '#';
    }
    // SEO-friendly format: product/slug
    return 'product/' . htmlspecialchars($slug);
}

/**
 * Generate SEO-friendly category URL
 * @param string $slug Category slug
 * @return string SEO-friendly URL
 */
function getCategoryUrl($slug) {
    if (empty($slug)) {
        return 'shop.php';
    }
    // SEO-friendly format: category/slug
    return 'category/' . htmlspecialchars($slug);
}

/**
 * Generate SEO-friendly shop URL with category
 * @param string $slug Category slug
 * @return string SEO-friendly URL
 */
function getShopUrl($category_slug = '') {
    if (empty($category_slug)) {
        return 'shop.php';
    }
    // SEO-friendly format: shop/category-slug
    return 'shop/' . htmlspecialchars($category_slug);
}

/**
 * Get base URL for the site (fixes CSS/JS path issues with SEO-friendly URLs)
 * @return string Base URL
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Get the actual script path (not the rewritten path)
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    // Remove the filename to get directory
    $base_path = dirname($script_name);
    
    // Normalize path separators
    $base_path = str_replace('\\', '/', $base_path);
    
    // Handle root and current directory cases
    if ($base_path == '/' || $base_path == '.' || empty($base_path)) {
        $base_path = '/mysmartscart/';
    } else {
        // Ensure it ends with / and starts with /
        $base_path = rtrim($base_path, '/') . '/';
        if (substr($base_path, 0, 1) != '/') {
            $base_path = '/' . $base_path;
        }
    }
    
    return $protocol . '://' . $host . $base_path;
}

/**
 * Get clean URL without .php extension
 * @param string $page Page name (e.g., 'shop.php', 'about.php', 'contact')
 * @return string Clean URL
 */
function getPageUrl($page) {
    // Remove .php if present
    $page = str_replace('.php', '', $page);
    
    // Special cases
    if ($page == 'index' || empty($page)) {
        return '/mysmartscart/';
    }
    
    return $page;
}
?>

