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
    
    // Merge with defaults to ensure all keys exist
    return array_merge(getDefaultSettings(), $settings);
}

/**
 * Get a specific setting value
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed Setting value or default
 */
function getSetting($key, $default = '') {
    $settings = getSiteSettings();
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Get default settings (used when database table doesn't exist)
 * @return array Default settings
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
    
    // Check if table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'site_settings'");
    if (mysqli_num_rows($table_check) == 0) {
        return false;
    }
    
    // Check if setting exists
    $check_query = "SELECT id FROM site_settings WHERE setting_key = '" . mysqli_real_escape_string($conn, $key) . "' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing
        $update_query = "UPDATE site_settings SET setting_value = '" . mysqli_real_escape_string($conn, $value) . "' WHERE setting_key = '" . mysqli_real_escape_string($conn, $key) . "'";
    } else {
        // Insert new
        $update_query = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('" . mysqli_real_escape_string($conn, $key) . "', '" . mysqli_real_escape_string($conn, $value) . "')";
    }
    
    $result = mysqli_query($conn, $update_query);
    
    // Clear cache
    $GLOBALS['site_settings_cache'] = null;
    
    return $result;
}

/**
 * Get header menu items
 * @return array Menu items
 */
function getHeaderMenu() {
    $menu_json = getSetting('header_menu', '[]');
    $menu = json_decode($menu_json, true);
    
    if (!is_array($menu) || empty($menu)) {
        // Return default menu
        return [
            ['title' => 'Home', 'url' => '', 'target' => '_self'],
            ['title' => 'Shop', 'url' => 'shop', 'target' => '_self'],
            ['title' => 'About', 'url' => 'about', 'target' => '_self'],
            ['title' => 'Contact', 'url' => 'contact', 'target' => '_self']
        ];
    }
    
    return $menu;
}

/**
 * Get header top links
 * @return array Links
 */
function getHeaderTopLinks() {
    $links_json = getSetting('header_top_links', '[]');
    $links = json_decode($links_json, true);
    
    if (!is_array($links) || empty($links)) {
        return [];
    }
    
    return $links;
}

/**
 * Get footer quick links
 * @return array Links
 */
function getFooterQuickLinks() {
    $links_json = getSetting('footer_quick_links', '[]');
    $links = json_decode($links_json, true);
    
    if (!is_array($links) || empty($links)) {
        return [
            ['title' => 'About Us', 'url' => 'about'],
            ['title' => 'Contact', 'url' => 'contact'],
            ['title' => 'Terms & Conditions', 'url' => 'terms-and-conditions'],
            ['title' => 'Privacy Policy', 'url' => 'privacy-policy']
        ];
    }
    
    return $links;
}

/**
 * Get "Why Choose Us" items
 * @return array Items
 */
function getWhyChooseUs() {
    $items_json = getSetting('footer_why_choose_us', '[]');
    $items = json_decode($items_json, true);
    
    if (!is_array($items) || empty($items)) {
        return [
            ['title' => 'Free Shipping', 'icon' => 'fas fa-shipping-fast'],
            ['title' => 'Secure Payment', 'icon' => 'fas fa-lock'],
            ['title' => '24/7 Support', 'icon' => 'fas fa-headset']
        ];
    }
    
    return $items;
}

/**
 * Get copyright text
 * @return string Copyright text
 */
function getCopyrightText() {
    $copyright = getSetting('footer_copyright', '© MySmartSCart {year}. All Rights Reserved.');
    $copyright = str_replace('{year}', date('Y'), $copyright);
    return $copyright;
}

/**
 * Get social links
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
 * Generate SEO-friendly product URL
 * @param string $slug Product slug
 * @return string SEO-friendly URL
 */
function getProductUrl($slug) {
    return 'product/' . htmlspecialchars($slug);
}

/**
 * Generate SEO-friendly category URL
 * @param string $slug Category slug
 * @return string SEO-friendly URL
 */
function getCategoryUrl($slug) {
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
 * Auto-detects folder path for both localhost and live server
 * @return string Base URL
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Method 1: Use SCRIPT_NAME to detect actual folder
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Remove filename to get directory path
    $script_dir = dirname($script_name);
    $script_dir = str_replace('\\', '/', $script_dir);
    
    // Method 2: Use REQUEST_URI to detect folder (more reliable for live server)
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    // Remove query string
    $request_uri = strtok($request_uri, '?');
    
    // Extract folder name from request URI if it exists
    $folder_name = '';
    if (preg_match('#^/([^/]+)/#', $request_uri, $matches)) {
        $excluded = ['adminpanel', 'assets', 'api', 'common', 'includes', 'database', 'mailing', 'sitemap.xml'];
        if (!in_array($matches[1], $excluded)) {
            $folder_name = $matches[1];
        }
    }
    
    // If no folder detected from REQUEST_URI, try SCRIPT_NAME
    if (empty($folder_name) && preg_match('#^/([^/]+)/#', $script_dir, $matches)) {
        $excluded = ['adminpanel', 'assets', 'api', 'common', 'includes', 'database', 'mailing'];
        if (!in_array($matches[1], $excluded) && $matches[1] != '.') {
            $folder_name = $matches[1];
        }
    }
    
    // Build base path
    if (!empty($folder_name)) {
        $base_path = '/' . $folder_name . '/';
    } else {
        // Root installation
        $base_path = '/';
    }
    
    return $protocol . '://' . $host . $base_path;
}
