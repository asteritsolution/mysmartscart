<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Menu Manager';

$success = '';
$error = '';

// Include site settings helper
require_once '../includes/site-settings.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Update Header Menu
    if (isset($_POST['header_menu_title']) && is_array($_POST['header_menu_title'])) {
        $menu = [];
        for ($i = 0; $i < count($_POST['header_menu_title']); $i++) {
            if (!empty($_POST['header_menu_title'][$i])) {
                $menu[] = [
                    'title' => $_POST['header_menu_title'][$i],
                    'url' => $_POST['header_menu_url'][$i] ?? '#',
                    'icon' => $_POST['header_menu_icon'][$i] ?? '',
                    'target' => $_POST['header_menu_target'][$i] ?? '_self',
                    'enabled' => isset($_POST['header_menu_enabled'][$i]) ? 1 : 0
                ];
            }
        }
        updateSetting('header_menu', json_encode($menu));
    }
    
    // Update Header Top Links
    if (isset($_POST['header_top_title']) && is_array($_POST['header_top_title'])) {
        $links = [];
        for ($i = 0; $i < count($_POST['header_top_title']); $i++) {
            if (!empty($_POST['header_top_title'][$i])) {
                $links[] = [
                    'title' => $_POST['header_top_title'][$i],
                    'url' => $_POST['header_top_url'][$i] ?? '#',
                    'enabled' => isset($_POST['header_top_enabled'][$i]) ? 1 : 0
                ];
            }
        }
        updateSetting('header_top_links', json_encode($links));
    }
    
    // Update Footer Quick Links
    if (isset($_POST['footer_link_title']) && is_array($_POST['footer_link_title'])) {
        $links = [];
        for ($i = 0; $i < count($_POST['footer_link_title']); $i++) {
            if (!empty($_POST['footer_link_title'][$i])) {
                $links[] = [
                    'title' => $_POST['footer_link_title'][$i],
                    'url' => $_POST['footer_link_url'][$i] ?? '#',
                    'enabled' => isset($_POST['footer_link_enabled'][$i]) ? 1 : 0
                ];
            }
        }
        updateSetting('footer_quick_links', json_encode($links));
    }
    
    // Update Footer Why Choose Us
    if (isset($_POST['why_title']) && is_array($_POST['why_title'])) {
        $items = [];
        for ($i = 0; $i < count($_POST['why_title']); $i++) {
            if (!empty($_POST['why_title'][$i])) {
                $items[] = [
                    'title' => $_POST['why_title'][$i],
                    'subtitle' => $_POST['why_subtitle'][$i] ?? '',
                    'url' => $_POST['why_url'][$i] ?? '#',
                    'enabled' => isset($_POST['why_enabled'][$i]) ? 1 : 0
                ];
            }
        }
        updateSetting('footer_why_choose_us', json_encode($items));
    }
    
    // Clear cache
    clearSettingsCache();
    
    // Redirect to prevent form resubmission
    header('Location: menu-manager.php?success=1');
    exit;
}

// Get current menus
$header_menu = json_decode(getSetting('header_menu', '[]'), true) ?: [];
$header_top_links = json_decode(getSetting('header_top_links', '[]'), true) ?: [];
$footer_links = json_decode(getSetting('footer_quick_links', '[]'), true) ?: [];
$why_choose_us = json_decode(getSetting('footer_why_choose_us', '[]'), true) ?: [];

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = 'Menus updated successfully!';
}

// Default header menu if empty
if (empty($header_menu)) {
    $header_menu = [
        ['title' => 'Home', 'url' => 'index.php', 'icon' => 'fas fa-home', 'target' => '_self', 'enabled' => 1],
        ['title' => 'Shop', 'url' => 'shop.php', 'icon' => 'fas fa-store', 'target' => '_self', 'enabled' => 1],
        ['title' => 'About Us', 'url' => 'about.php', 'icon' => 'fas fa-info-circle', 'target' => '_self', 'enabled' => 1],
        ['title' => 'Contact', 'url' => 'contact.php', 'icon' => 'fas fa-envelope', 'target' => '_self', 'enabled' => 1]
    ];
}

// Default header top links if empty
if (empty($header_top_links)) {
    $header_top_links = [
        ['title' => 'My Wishlist', 'url' => 'wishlist.php', 'enabled' => 1],
        ['title' => 'About Us', 'url' => 'about.php', 'enabled' => 1],
        ['title' => 'Contact Us', 'url' => 'contact.php', 'enabled' => 1],
        ['title' => 'Cart', 'url' => 'cart.php', 'enabled' => 1]
    ];
}

// Default footer links if empty
if (empty($footer_links)) {
    $footer_links = [
        ['title' => 'About Us', 'url' => 'about.php', 'enabled' => 1],
        ['title' => 'Shop All', 'url' => 'shop.php', 'enabled' => 1],
        ['title' => 'Contact Us', 'url' => 'contact.php', 'enabled' => 1],
        ['title' => 'My Wishlist', 'url' => 'wishlist.php', 'enabled' => 1],
        ['title' => 'Shopping Cart', 'url' => 'cart.php', 'enabled' => 1],
        ['title' => 'My Account', 'url' => 'dashboard.php', 'enabled' => 1]
    ];
}

// Default why choose us if empty
if (empty($why_choose_us)) {
    $why_choose_us = [
        ['title' => 'Best Prices', 'subtitle' => 'Up to 70% OFF', 'url' => 'shop.php', 'enabled' => 1],
        ['title' => 'Fast Delivery', 'subtitle' => '3-7 Business Days', 'url' => 'shop.php', 'enabled' => 1],
        ['title' => 'Secure Shopping', 'subtitle' => '100% Safe & Secure', 'url' => 'about.php', 'enabled' => 1]
    ];
}

include 'includes/header.php';
?>

<style>
/* Professional Design for Menu Manager */
.menu-manager-wrapper {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
}

.menu-sidebar {
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    border-right: 1px solid #e9ecef;
    padding: 25px 0;
    min-height: 600px;
}

.menu-sidebar .nav-pills-custom {
    padding: 0 15px;
}

.menu-sidebar .nav-link {
    color: #495057;
    border-radius: 8px;
    padding: 14px 18px;
    margin-bottom: 8px;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 14px;
    background: transparent;
    display: flex;
    align-items: center;
}

.menu-sidebar .nav-link:hover {
    background: #f0f0f0;
    color: #1a237e;
    border-left-color: #f68b28;
    transform: translateX(3px);
}

.menu-sidebar .nav-link.active {
    background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
    color: #fff;
    border-left-color: #f68b28;
    box-shadow: 0 4px 12px rgba(26, 35, 126, 0.3);
}

.menu-sidebar .nav-link.active i {
    color: #f68b28;
}

.menu-sidebar .nav-link i {
    margin-right: 12px;
    width: 22px;
    text-align: center;
    font-size: 16px;
    color: #6c757d;
    transition: all 0.3s;
}

.menu-content {
    background: #fff;
    padding: 30px;
    min-height: 600px;
}

.menu-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.menu-section:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border-color: #dee2e6;
}

.menu-section h6 {
    color: #1a237e;
    border-bottom: 3px solid #f68b28;
    padding-bottom: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
}

.menu-section h6 i {
    margin-right: 10px;
    color: #f68b28;
    font-size: 18px;
}

.menu-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 18px;
    margin-bottom: 12px;
    cursor: grab;
    transition: all 0.3s ease;
    position: relative;
}

.menu-item:hover {
    background: #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.menu-item.dragging {
    opacity: 0.6;
    cursor: grabbing;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    transform: rotate(2deg);
}

.menu-item .drag-handle {
    color: #6c757d;
    cursor: grab;
    margin-right: 12px;
    font-size: 18px;
    transition: color 0.3s;
}

.menu-item:hover .drag-handle {
    color: #1a237e;
}

.menu-item .form-control-sm {
    font-size: 13px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    transition: all 0.3s;
}

.menu-item .form-control-sm:focus {
    border-color: #1a237e;
    box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.15);
}

.btn-add-menu {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: #fff;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.btn-add-menu:hover {
    background: linear-gradient(135deg, #218838, #1aa179);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    color: #fff;
}

.btn-remove-menu {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    color: #fff;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    padding: 0;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
}

.btn-remove-menu:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    color: #fff;
}

.btn-save-menus {
    background: linear-gradient(135deg, #1a237e, #3949ab);
    border: none;
    color: #fff;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    width: 100%;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(26, 35, 126, 0.3);
}

.btn-save-menus:hover {
    background: linear-gradient(135deg, #283593, #5c6bc0);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(26, 35, 126, 0.4);
    color: #fff;
}

.preview-box {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.preview-box h6 {
    color: #fff;
    font-size: 13px;
    margin-bottom: 15px;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.preview-menu {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.preview-menu a {
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    padding: 8px 14px;
    background: rgba(255,255,255,0.15);
    border-radius: 6px;
    transition: all 0.3s;
    border: 1px solid rgba(255,255,255,0.2);
}

.preview-menu a:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #1a237e;
    border-color: #1a237e;
}

.custom-control-input:focus ~ .custom-control-label::before {
    box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 14px;
}

.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.text-muted {
    font-size: 12px;
    color: #6c757d !important;
}

/* Ensure sidebar is not affected */
.sidebar,
.sidebar * {
    box-sizing: border-box;
}
.sidebar-menu .menu-item {
    display: block !important;
    padding: 12px 20px !important;
    color: rgba(255,255,255,0.8) !important;
    text-decoration: none !important;
    transition: all 0.3s !important;
    border-left: 3px solid transparent !important;
    background: transparent !important;
    border: none !important;
    border-left: 3px solid transparent !important;
    border-radius: 0 !important;
    cursor: pointer !important;
    margin-bottom: 0 !important;
}
.sidebar-menu .menu-item:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    border-left-color: var(--primary-color) !important;
    box-shadow: none !important;
}
.sidebar-menu .menu-item.active {
    background: rgba(102, 126, 234, 0.2) !important;
    color: white !important;
    border-left-color: var(--primary-color) !important;
}
</style>

<div class="content-card p-0">
    <div class="menu-manager-wrapper">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white">
            <div>
                <h5 class="mb-0"><i class="fas fa-bars text-primary"></i> <strong>Menu Manager</strong></h5>
                <small class="text-muted">Drag & drop to reorder menus • Click and drag items to change order</small>
            </div>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
            <i class="fas fa-check-circle"></i> <strong>Success!</strong> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>
        
        <form method="POST" id="menuForm">
            <div class="row m-0">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 p-0">
                    <div class="menu-sidebar">
                        <div class="nav flex-column nav-pills nav-pills-custom" role="tablist">
                            <a class="nav-link active" data-toggle="pill" href="#header-menu">
                                <i class="fas fa-compass"></i> Main Navigation
                            </a>
                            <a class="nav-link" data-toggle="pill" href="#header-top-menu">
                                <i class="fas fa-link"></i> Header Top Links
                            </a>
                            <a class="nav-link" data-toggle="pill" href="#footer-menu">
                                <i class="fas fa-list"></i> Footer Quick Links
                            </a>
                            <a class="nav-link" data-toggle="pill" href="#why-choose">
                                <i class="fas fa-star"></i> Why Choose Us
                            </a>
                        </div>
                        
                        <div class="px-3 mt-4">
                            <button type="submit" class="btn btn-save-menus">
                                <i class="fas fa-save"></i> Save All Menus
                            </button>
                            <a href="site-settings.php" class="btn btn-outline-secondary btn-block mt-2">
                                <i class="fas fa-cog"></i> Site Settings
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div class="col-md-9 p-0">
                    <div class="menu-content">
                        <div class="tab-content">
                    
                    <!-- Main Navigation Menu -->
                    <div class="tab-pane fade show active" id="header-menu">
                        <div class="menu-section">
                            <h6><i class="fas fa-compass"></i> Main Navigation Menu</h6>
                            <p class="text-muted small">These links appear in the main header navigation bar.</p>
                            
                            <div id="header-menu-container" class="sortable-container">
                                <?php foreach ($header_menu as $index => $item): ?>
                                <div class="menu-item" draggable="true">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="header_menu_title[]" 
                                                   value="<?php echo htmlspecialchars($item['title']); ?>" 
                                                   placeholder="Menu Title">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="header_menu_url[]" 
                                                   value="<?php echo htmlspecialchars($item['url']); ?>" 
                                                   placeholder="URL (e.g., shop.php)">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="header_menu_icon[]" 
                                                   value="<?php echo htmlspecialchars($item['icon'] ?? ''); ?>" 
                                                   placeholder="Icon (optional)">
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control form-control-sm" name="header_menu_target[]">
                                                <option value="_self" <?php echo ($item['target'] ?? '_self') == '_self' ? 'selected' : ''; ?>>Same Tab</option>
                                                <option value="_blank" <?php echo ($item['target'] ?? '_self') == '_blank' ? 'selected' : ''; ?>>New Tab</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="header_menu_enabled_<?php echo $index; ?>"
                                                       name="header_menu_enabled[<?php echo $index; ?>]"
                                                       <?php echo ($item['enabled'] ?? 1) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="header_menu_enabled_<?php echo $index; ?>"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="button" class="btn btn-add-menu mt-2" onclick="addHeaderMenuItem()">
                                <i class="fas fa-plus"></i> Add Menu Item
                            </button>
                            
                            <div class="preview-box">
                                <h6><i class="fas fa-eye"></i> PREVIEW</h6>
                                <div class="preview-menu" id="header-menu-preview">
                                    <?php foreach ($header_menu as $item): ?>
                                    <?php if ($item['enabled'] ?? 1): ?>
                                    <a href="#"><?php echo htmlspecialchars($item['title']); ?></a>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Header Top Links -->
                    <div class="tab-pane fade" id="header-top-menu">
                        <div class="menu-section">
                            <h6><i class="fas fa-link"></i> Header Top Links</h6>
                            <p class="text-muted small">These links appear in the header top bar (orange section).</p>
                            
                            <div id="header-top-container" class="sortable-container">
                                <?php foreach ($header_top_links as $index => $item): ?>
                                <div class="menu-item" draggable="true">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="header_top_title[]" 
                                                   value="<?php echo htmlspecialchars($item['title']); ?>" 
                                                   placeholder="Link Title">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="header_top_url[]" 
                                                   value="<?php echo htmlspecialchars($item['url']); ?>" 
                                                   placeholder="URL">
                                        </div>
                                        <div class="col-auto">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="header_top_enabled_<?php echo $index; ?>"
                                                       name="header_top_enabled[<?php echo $index; ?>]"
                                                       <?php echo ($item['enabled'] ?? 1) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="header_top_enabled_<?php echo $index; ?>"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="button" class="btn btn-add-menu mt-2" onclick="addHeaderTopLink()">
                                <i class="fas fa-plus"></i> Add Link
                            </button>
                        </div>
                    </div>
                    
                    <!-- Footer Quick Links -->
                    <div class="tab-pane fade" id="footer-menu">
                        <div class="menu-section">
                            <h6><i class="fas fa-list"></i> Footer Quick Links</h6>
                            <p class="text-muted small">These links appear in the footer "Quick Links" section.</p>
                            
                            <div id="footer-links-container" class="sortable-container">
                                <?php foreach ($footer_links as $index => $item): ?>
                                <div class="menu-item" draggable="true">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="footer_link_title[]" 
                                                   value="<?php echo htmlspecialchars($item['title']); ?>" 
                                                   placeholder="Link Title">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="footer_link_url[]" 
                                                   value="<?php echo htmlspecialchars($item['url']); ?>" 
                                                   placeholder="URL">
                                        </div>
                                        <div class="col-auto">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="footer_link_enabled_<?php echo $index; ?>"
                                                       name="footer_link_enabled[<?php echo $index; ?>]"
                                                       <?php echo ($item['enabled'] ?? 1) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="footer_link_enabled_<?php echo $index; ?>"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="button" class="btn btn-add-menu mt-2" onclick="addFooterLink()">
                                <i class="fas fa-plus"></i> Add Link
                            </button>
                        </div>
                    </div>
                    
                    <!-- Why Choose Us -->
                    <div class="tab-pane fade" id="why-choose">
                        <div class="menu-section">
                            <h6><i class="fas fa-star"></i> Why Choose Us Section</h6>
                            <p class="text-muted small">These items appear in the footer "Why Choose Us" section.</p>
                            
                            <div id="why-choose-container" class="sortable-container">
                                <?php foreach ($why_choose_us as $index => $item): ?>
                                <div class="menu-item" draggable="true">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="why_title[]" 
                                                   value="<?php echo htmlspecialchars($item['title']); ?>" 
                                                   placeholder="Title">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="why_subtitle[]" 
                                                   value="<?php echo htmlspecialchars($item['subtitle'] ?? ''); ?>" 
                                                   placeholder="Subtitle">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="why_url[]" 
                                                   value="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>" 
                                                   placeholder="URL">
                                        </div>
                                        <div class="col-auto">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="why_enabled_<?php echo $index; ?>"
                                                       name="why_enabled[<?php echo $index; ?>]"
                                                       <?php echo ($item['enabled'] ?? 1) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="why_enabled_<?php echo $index; ?>"></label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="button" class="btn btn-add-menu mt-2" onclick="addWhyItem()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>

<script>
// Counter for unique IDs
let menuCounter = 100;

// Add Header Menu Item
function addHeaderMenuItem() {
    menuCounter++;
    const container = document.getElementById('header-menu-container');
    const html = `
        <div class="menu-item" draggable="true">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="header_menu_title[]" placeholder="Menu Title">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="header_menu_url[]" placeholder="URL (e.g., shop.php)">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="header_menu_icon[]" placeholder="Icon (optional)">
                </div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="header_menu_target[]">
                        <option value="_self">Same Tab</option>
                        <option value="_blank">New Tab</option>
                    </select>
                </div>
                <div class="col-auto">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="header_menu_enabled_${menuCounter}" name="header_menu_enabled[${menuCounter}]" checked>
                        <label class="custom-control-label" for="header_menu_enabled_${menuCounter}"></label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    initDragDrop();
}

// Add Header Top Link
function addHeaderTopLink() {
    menuCounter++;
    const container = document.getElementById('header-top-container');
    const html = `
        <div class="menu-item" draggable="true">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="header_top_title[]" placeholder="Link Title">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="header_top_url[]" placeholder="URL">
                </div>
                <div class="col-auto">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="header_top_enabled_${menuCounter}" name="header_top_enabled[${menuCounter}]" checked>
                        <label class="custom-control-label" for="header_top_enabled_${menuCounter}"></label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    initDragDrop();
}

// Add Footer Link
function addFooterLink() {
    menuCounter++;
    const container = document.getElementById('footer-links-container');
    const html = `
        <div class="menu-item" draggable="true">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="footer_link_title[]" placeholder="Link Title">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="footer_link_url[]" placeholder="URL">
                </div>
                <div class="col-auto">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="footer_link_enabled_${menuCounter}" name="footer_link_enabled[${menuCounter}]" checked>
                        <label class="custom-control-label" for="footer_link_enabled_${menuCounter}"></label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    initDragDrop();
}

// Add Why Choose Us Item
function addWhyItem() {
    menuCounter++;
    const container = document.getElementById('why-choose-container');
    const html = `
        <div class="menu-item" draggable="true">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="why_title[]" placeholder="Title">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="why_subtitle[]" placeholder="Subtitle">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" name="why_url[]" placeholder="URL">
                </div>
                <div class="col-auto">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="why_enabled_${menuCounter}" name="why_enabled[${menuCounter}]" checked>
                        <label class="custom-control-label" for="why_enabled_${menuCounter}"></label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-remove-menu" onclick="removeMenuItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    initDragDrop();
}

// Remove Menu Item
function removeMenuItem(btn) {
    if (confirm('Are you sure you want to remove this item?')) {
        btn.closest('.menu-item').remove();
    }
}

// Drag and Drop functionality
let draggedItem = null;

function initDragDrop() {
    // Only select menu items within content-card (not sidebar)
    document.querySelectorAll('.content-card .menu-item').forEach(item => {
        // Remove existing listeners to prevent duplicates
        item.removeEventListener('dragstart', handleDragStart);
        item.removeEventListener('dragend', handleDragEnd);
        item.removeEventListener('dragover', handleDragOver);
        item.removeEventListener('drop', handleDrop);
        
        // Add new listeners
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleDrop);
    });
}

function handleDragStart(e) {
    draggedItem = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    // Only remove drag-over from content-card menu items
    document.querySelectorAll('.content-card .menu-item').forEach(item => {
        item.classList.remove('drag-over');
    });
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    
    const container = this.parentElement;
    if (container === draggedItem.parentElement) {
        const afterElement = getDragAfterElement(container, e.clientY);
        if (afterElement == null) {
            container.appendChild(draggedItem);
        } else {
            container.insertBefore(draggedItem, afterElement);
        }
    }
}

function handleDrop(e) {
    e.preventDefault();
}

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.menu-item:not(.dragging)')];
    
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Make sure sidebar menu items are not draggable
    document.querySelectorAll('.sidebar .menu-item').forEach(item => {
        item.setAttribute('draggable', 'false');
    });
    
    // Initialize drag and drop for content area only
    initDragDrop();
});
</script>

<?php include 'includes/footer.php'; ?>

