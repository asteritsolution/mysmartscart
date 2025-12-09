<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Site Settings';

$success = '';
$error = '';

// Include site settings helper
require_once '../includes/site-settings.php';

// Check if site_settings table exists, if not create it
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'site_settings'");
if (mysqli_num_rows($table_check) == 0) {
    // Read and execute the SQL file
    $sql_file = '../database/site_settings.sql';
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        // Split by semicolons and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                mysqli_query($conn, $statement);
            }
        }
        $success = 'Site settings table created successfully!';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = '../assets/images/';
    
    // Handle logo upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $logo_name = 'logo_' . time() . '_' . basename($_FILES['site_logo']['name']);
        $logo_path = $upload_dir . $logo_name;
        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $logo_path)) {
            updateSetting('site_logo', 'assets/images/' . $logo_name);
        }
    }
    
    // Handle footer logo upload
    if (isset($_FILES['footer_logo']) && $_FILES['footer_logo']['error'] == 0) {
        $footer_logo_name = 'footer_logo_' . time() . '_' . basename($_FILES['footer_logo']['name']);
        $footer_logo_path = $upload_dir . $footer_logo_name;
        if (move_uploaded_file($_FILES['footer_logo']['tmp_name'], $footer_logo_path)) {
            updateSetting('footer_logo', 'assets/images/' . $footer_logo_name);
        }
    }
    
    // Handle favicon upload
    if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] == 0) {
        $favicon_dir = '../assets/images/icons/';
        if (!is_dir($favicon_dir)) {
            mkdir($favicon_dir, 0777, true);
        }
        $favicon_name = 'favicon_' . time() . '_' . basename($_FILES['site_favicon']['name']);
        $favicon_path = $favicon_dir . $favicon_name;
        if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $favicon_path)) {
            updateSetting('site_favicon', 'assets/images/icons/' . $favicon_name);
        }
    }
    
    // Update text settings
    $text_settings = [
        'site_name', 'site_tagline', 'site_description', 'site_keywords',
        'header_top_text', 'header_top_small_text', 'header_phone',
        'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube', 'social_whatsapp',
        'footer_about_text', 'footer_copyright', 'footer_newsletter_text',
        'color_primary', 'color_secondary', 'color_header_top', 'color_top_notice',
        'google_analytics_id', 'facebook_pixel_id'
    ];
    
    foreach ($text_settings as $setting) {
        if (isset($_POST[$setting])) {
            updateSetting($setting, $_POST[$setting]);
        }
    }
    
    // Update boolean settings
    $boolean_settings = ['header_show_currency', 'header_show_language', 'show_payment_icons'];
    foreach ($boolean_settings as $setting) {
        updateSetting($setting, isset($_POST[$setting]) ? '1' : '0');
    }
    
    // Update footer quick links (JSON)
    if (isset($_POST['quick_link_title']) && is_array($_POST['quick_link_title'])) {
        $links = [];
        for ($i = 0; $i < count($_POST['quick_link_title']); $i++) {
            if (!empty($_POST['quick_link_title'][$i]) && !empty($_POST['quick_link_url'][$i])) {
                $links[] = [
                    'title' => $_POST['quick_link_title'][$i],
                    'url' => $_POST['quick_link_url'][$i]
                ];
            }
        }
        updateSetting('footer_quick_links', json_encode($links));
    }
    
    // Update why choose us (JSON)
    if (isset($_POST['why_title']) && is_array($_POST['why_title'])) {
        $items = [];
        for ($i = 0; $i < count($_POST['why_title']); $i++) {
            if (!empty($_POST['why_title'][$i])) {
                $items[] = [
                    'title' => $_POST['why_title'][$i],
                    'subtitle' => $_POST['why_subtitle'][$i] ?? '',
                    'url' => $_POST['why_url'][$i] ?? '#'
                ];
            }
        }
        updateSetting('footer_why_choose_us', json_encode($items));
    }
    
    // Clear cache
    clearSettingsCache();
    
    $success = 'Settings updated successfully!';
    
    // Get active tab from POST or default to general
    $active_tab = $_POST['active_tab'] ?? 'general';
    
    // Redirect to maintain active tab
    header('Location: site-settings.php?tab=' . urlencode($active_tab) . '&success=1');
    exit;
}

// Get current settings
$settings = getSiteSettings();
$quick_links = getFooterQuickLinks();
$why_choose_us = getWhyChooseUs();

// Get active tab from GET, POST, or default
$active_tab = $_GET['tab'] ?? ($_POST['active_tab'] ?? 'general');

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = 'Settings updated successfully!';
}

include 'includes/header.php';
?>

<style>
/* Professional Design for Site Settings */
.site-settings-wrapper {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
}

.settings-sidebar {
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    border-right: 1px solid #e9ecef;
    padding: 25px 0;
    min-height: 600px;
}

.settings-sidebar .nav-pills {
    padding: 0 15px;
}

.settings-sidebar .nav-link {
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

.settings-sidebar .nav-link:hover {
    background: #f0f0f0;
    color: #1a237e;
    border-left-color: #f68b28;
    transform: translateX(3px);
}

.settings-sidebar .nav-link.active {
    background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
    color: #fff;
    border-left-color: #f68b28;
    box-shadow: 0 4px 12px rgba(26, 35, 126, 0.3);
}

.settings-sidebar .nav-link.active i {
    color: #f68b28;
}

.settings-sidebar .nav-link i {
    margin-right: 12px;
    width: 22px;
    text-align: center;
    font-size: 16px;
    color: #6c757d;
    transition: all 0.3s;
}

.settings-sidebar .nav-link.active i {
    color: #f68b28;
}

.settings-content {
    background: #fff;
    padding: 30px;
    min-height: 600px;
}

.tab-pane {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.setting-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.setting-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border-color: #dee2e6;
}

.setting-card h6 {
    color: #1a237e;
    border-bottom: 3px solid #f68b28;
    padding-bottom: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
}

.setting-card h6 i {
    margin-right: 10px;
    color: #f68b28;
    font-size: 18px;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 10px 15px;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #1a237e;
    box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.15);
}

.preview-image {
    max-width: 200px;
    max-height: 100px;
    border: 2px solid #e9ecef;
    padding: 8px;
    border-radius: 8px;
    background: #f8f9fa;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.color-preview {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    border: 2px solid #dee2e6;
    display: inline-block;
    vertical-align: middle;
    margin-left: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.link-item {
    background: #f8f9fa;
    padding: 15px;
    margin-bottom: 12px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}

.link-item:hover {
    background: #fff;
    border-color: #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.btn-add-link {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-add-link:hover {
    background: linear-gradient(135deg, #218838, #1aa179);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    color: #fff;
}

.btn-remove-link {
    background: #dc3545;
    border: none;
    color: #fff;
    width: 35px;
    height: 35px;
    border-radius: 6px;
    padding: 0;
    transition: all 0.3s;
}

.btn-remove-link:hover {
    background: #c82333;
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    color: #fff;
}

.btn-save-settings {
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

.btn-save-settings:hover {
    background: linear-gradient(135deg, #283593, #5c6bc0);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(26, 35, 126, 0.4);
    color: #fff;
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

/* Section divider */
.section-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e9ecef, transparent);
    margin: 30px 0;
}
</style>

<div class="content-card p-0">
    <div class="site-settings-wrapper">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white">
            <div>
                <h5 class="mb-0"><i class="fas fa-cogs text-primary"></i> <strong>Site Settings</strong></h5>
                <small class="text-muted">Manage your website configuration and branding</small>
            </div>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
            <i class="fas fa-check-circle"></i> <strong>Success!</strong> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-danger m-4"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" id="settingsForm">
            <!-- Hidden input to track active tab -->
            <input type="hidden" name="active_tab" id="active_tab" value="<?php echo htmlspecialchars($active_tab); ?>">
            
            <div class="row m-0">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 p-0">
                    <div class="settings-sidebar">
                        <div class="nav flex-column nav-pills" role="tablist" id="settingsTabs">
                            <a class="nav-link <?php echo $active_tab == 'general' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#general" data-tab="general">
                                <i class="fas fa-info-circle"></i> General
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'branding' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#branding" data-tab="branding">
                                <i class="fas fa-palette"></i> Logo & Favicon
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'header' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#header" data-tab="header">
                                <i class="fas fa-arrow-up"></i> Header Settings
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'footer' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#footer" data-tab="footer">
                                <i class="fas fa-arrow-down"></i> Footer Settings
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'social' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#social" data-tab="social">
                                <i class="fas fa-share-alt"></i> Social Media
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'colors' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#colors" data-tab="colors">
                                <i class="fas fa-fill-drip"></i> Theme Colors
                            </a>
                            <a class="nav-link <?php echo $active_tab == 'analytics' ? 'active' : ''; ?>" 
                               data-toggle="pill" href="#analytics" data-tab="analytics">
                                <i class="fas fa-chart-line"></i> Analytics
                            </a>
                        </div>
                        
                        <div class="px-3 mt-4">
                            <button type="submit" class="btn btn-save-settings">
                                <i class="fas fa-save"></i> Save All Settings
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div class="col-md-9 p-0">
                    <div class="settings-content">
                        <div class="tab-content">
                    
                    <!-- General Settings -->
                    <div class="tab-pane fade <?php echo $active_tab == 'general' ? 'show active' : ''; ?>" id="general">
                        <div class="setting-card">
                            <h6><i class="fas fa-info-circle"></i> General Information</h6>
                            
                            <div class="form-group">
                                <label><strong>Site Name</strong></label>
                                <input type="text" class="form-control" name="site_name" 
                                       value="<?php echo htmlspecialchars($settings['site_name'] ?? 'MySmartSCart'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Site Tagline</strong></label>
                                <input type="text" class="form-control" name="site_tagline" 
                                       value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>">
                                <small class="text-muted">Shown in header and SEO</small>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Site Description</strong></label>
                                <textarea class="form-control" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                                <small class="text-muted">Used for SEO meta description</small>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>SEO Keywords</strong></label>
                                <textarea class="form-control" name="site_keywords" rows="2"><?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?></textarea>
                                <small class="text-muted">Comma-separated keywords</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Logo & Favicon -->
                    <div class="tab-pane fade <?php echo $active_tab == 'branding' ? 'show active' : ''; ?>" id="branding">
                        <div class="setting-card">
                            <h6><i class="fas fa-image"></i> Logo Settings</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Site Logo</strong></label>
                                        <?php if (!empty($settings['site_logo'])): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" class="preview-image" alt="Current Logo">
                                        </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control-file" name="site_logo" accept="image/*">
                                        <small class="text-muted">Recommended: PNG, 250x80px</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Footer Logo</strong></label>
                                        <?php if (!empty($settings['footer_logo'])): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo htmlspecialchars($settings['footer_logo']); ?>" class="preview-image" alt="Footer Logo">
                                        </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control-file" name="footer_logo" accept="image/*">
                                        <small class="text-muted">Used in footer (can be different)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <h6><i class="fas fa-bookmark"></i> Favicon</h6>
                            
                            <div class="form-group">
                                <label><strong>Site Favicon</strong></label>
                                <?php if (!empty($settings['site_favicon'])): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($settings['site_favicon']); ?>" style="max-width:64px; max-height:64px;" alt="Favicon">
                                </div>
                                <?php endif; ?>
                                <input type="file" class="form-control-file" name="site_favicon" accept=".ico,.png,.svg">
                                <small class="text-muted">Recommended: ICO or PNG, 32x32px or 64x64px</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Header Settings -->
                    <div class="tab-pane fade <?php echo $active_tab == 'header' ? 'show active' : ''; ?>" id="header">
                        <div class="setting-card">
                            <h6><i class="fas fa-bullhorn"></i> Top Bar Announcement</h6>
                            
                            <div class="form-group">
                                <label><strong>Announcement Text</strong></label>
                                <input type="text" class="form-control" name="header_top_text" 
                                       value="<?php echo htmlspecialchars($settings['header_top_text'] ?? ''); ?>">
                                <small class="text-muted">Supports HTML: use &lt;b&gt;text&lt;/b&gt; for bold</small>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Small Text (Right Side)</strong></label>
                                <input type="text" class="form-control" name="header_top_small_text" 
                                       value="<?php echo htmlspecialchars($settings['header_top_small_text'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <h6><i class="fas fa-phone"></i> Contact Info</h6>
                            
                            <div class="form-group">
                                <label><strong>Phone Number (Header)</strong></label>
                                <input type="text" class="form-control" name="header_phone" 
                                       value="<?php echo htmlspecialchars($settings['header_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <h6><i class="fas fa-toggle-on"></i> Display Options</h6>
                            
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="header_show_currency" id="header_show_currency"
                                       <?php echo ($settings['header_show_currency'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="header_show_currency">Show Currency Selector</label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="header_show_language" id="header_show_language"
                                       <?php echo ($settings['header_show_language'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="header_show_language">Show Language Selector</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Settings -->
                    <div class="tab-pane fade <?php echo $active_tab == 'footer' ? 'show active' : ''; ?>" id="footer">
                        <div class="setting-card">
                            <h6><i class="fas fa-align-left"></i> Footer Content</h6>
                            
                            <div class="form-group">
                                <label><strong>About Text</strong></label>
                                <textarea class="form-control" name="footer_about_text" rows="4"><?php echo htmlspecialchars($settings['footer_about_text'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Newsletter Text</strong></label>
                                <textarea class="form-control" name="footer_newsletter_text" rows="2"><?php echo htmlspecialchars($settings['footer_newsletter_text'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Copyright Text</strong></label>
                                <input type="text" class="form-control" name="footer_copyright" 
                                       value="<?php echo htmlspecialchars($settings['footer_copyright'] ?? ''); ?>">
                                <small class="text-muted">Use {year} for current year</small>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="show_payment_icons" id="show_payment_icons"
                                       <?php echo ($settings['show_payment_icons'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="show_payment_icons">Show Payment Icons</label>
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <h6><i class="fas fa-link"></i> Quick Links</h6>
                            <div id="quick-links-container">
                                <?php foreach ($quick_links as $index => $link): ?>
                                <div class="link-item row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control form-control-sm" name="quick_link_title[]" 
                                               value="<?php echo htmlspecialchars($link['title']); ?>" placeholder="Title">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control form-control-sm" name="quick_link_url[]" 
                                               value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="URL">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-remove-link" onclick="this.closest('.link-item').remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-add-link" onclick="addQuickLink()">
                                <i class="fas fa-plus"></i> Add Link
                            </button>
                        </div>
                        
                        <div class="setting-card">
                            <h6><i class="fas fa-star"></i> Why Choose Us Section</h6>
                            <div id="why-choose-container">
                                <?php foreach ($why_choose_us as $index => $item): ?>
                                <div class="link-item row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="why_title[]" 
                                               value="<?php echo htmlspecialchars($item['title']); ?>" placeholder="Title">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="why_subtitle[]" 
                                               value="<?php echo htmlspecialchars($item['subtitle'] ?? ''); ?>" placeholder="Subtitle">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm" name="why_url[]" 
                                               value="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>" placeholder="URL">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-remove-link" onclick="this.closest('.link-item').remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-add-link" onclick="addWhyItem()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="tab-pane fade <?php echo $active_tab == 'social' ? 'show active' : ''; ?>" id="social">
                        <div class="setting-card">
                            <h6><i class="fas fa-share-alt"></i> Social Media Links</h6>
                            
                            <div class="form-group">
                                <label><i class="fab fa-facebook text-primary"></i> <strong>Facebook URL</strong></label>
                                <input type="url" class="form-control" name="social_facebook" 
                                       value="<?php echo htmlspecialchars($settings['social_facebook'] ?? ''); ?>" placeholder="https://facebook.com/yourpage">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fab fa-twitter text-info"></i> <strong>Twitter URL</strong></label>
                                <input type="url" class="form-control" name="social_twitter" 
                                       value="<?php echo htmlspecialchars($settings['social_twitter'] ?? ''); ?>" placeholder="https://twitter.com/yourpage">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fab fa-instagram text-danger"></i> <strong>Instagram URL</strong></label>
                                <input type="url" class="form-control" name="social_instagram" 
                                       value="<?php echo htmlspecialchars($settings['social_instagram'] ?? ''); ?>" placeholder="https://instagram.com/yourpage">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fab fa-youtube text-danger"></i> <strong>YouTube URL</strong></label>
                                <input type="url" class="form-control" name="social_youtube" 
                                       value="<?php echo htmlspecialchars($settings['social_youtube'] ?? ''); ?>" placeholder="https://youtube.com/yourchannel">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fab fa-whatsapp text-success"></i> <strong>WhatsApp Number</strong></label>
                                <input type="text" class="form-control" name="social_whatsapp" 
                                       value="<?php echo htmlspecialchars($settings['social_whatsapp'] ?? ''); ?>" placeholder="+91XXXXXXXXXX">
                                <small class="text-muted">Include country code without spaces</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Theme Colors -->
                    <div class="tab-pane fade <?php echo $active_tab == 'colors' ? 'show active' : ''; ?>" id="colors">
                        <div class="setting-card">
                            <h6><i class="fas fa-fill-drip"></i> Theme Colors</h6>
                            <p class="text-muted">Customize your website's color scheme</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Primary Color (Orange)</strong></label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" class="form-control" name="color_primary" 
                                                   value="<?php echo htmlspecialchars($settings['color_primary'] ?? '#f68b28'); ?>" style="width:100px; height:40px;">
                                            <input type="text" class="form-control ml-2" id="color_primary_text" 
                                                   value="<?php echo htmlspecialchars($settings['color_primary'] ?? '#f68b28'); ?>" style="width:100px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Secondary Color (Royal Blue)</strong></label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" class="form-control" name="color_secondary" 
                                                   value="<?php echo htmlspecialchars($settings['color_secondary'] ?? '#1a237e'); ?>" style="width:100px; height:40px;">
                                            <input type="text" class="form-control ml-2" id="color_secondary_text" 
                                                   value="<?php echo htmlspecialchars($settings['color_secondary'] ?? '#1a237e'); ?>" style="width:100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Header Top Bar Color</strong></label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" class="form-control" name="color_header_top" 
                                                   value="<?php echo htmlspecialchars($settings['color_header_top'] ?? '#f68b28'); ?>" style="width:100px; height:40px;">
                                            <input type="text" class="form-control ml-2" 
                                                   value="<?php echo htmlspecialchars($settings['color_header_top'] ?? '#f68b28'); ?>" style="width:100px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Top Notice Bar Color</strong></label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" class="form-control" name="color_top_notice" 
                                                   value="<?php echo htmlspecialchars($settings['color_top_notice'] ?? '#1a237e'); ?>" style="width:100px; height:40px;">
                                            <input type="text" class="form-control ml-2" 
                                                   value="<?php echo htmlspecialchars($settings['color_top_notice'] ?? '#1a237e'); ?>" style="width:100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i> <strong>Note:</strong> Color changes will be applied after saving. For advanced color customization, edit <code>assets/css/optimizations.css</code>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Analytics -->
                    <div class="tab-pane fade <?php echo $active_tab == 'analytics' ? 'show active' : ''; ?>" id="analytics">
                        <div class="setting-card">
                            <h6><i class="fas fa-chart-line"></i> Analytics & Tracking</h6>
                            
                            <div class="form-group">
                                <label><strong>Google Analytics ID</strong></label>
                                <input type="text" class="form-control" name="google_analytics_id" 
                                       value="<?php echo htmlspecialchars($settings['google_analytics_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX or UA-XXXXXXXX-X">
                                <small class="text-muted">Enter your Google Analytics tracking ID</small>
                            </div>
                            
                            <div class="form-group">
                                <label><strong>Facebook Pixel ID</strong></label>
                                <input type="text" class="form-control" name="facebook_pixel_id" 
                                       value="<?php echo htmlspecialchars($settings['facebook_pixel_id'] ?? ''); ?>" placeholder="XXXXXXXXXXXXXXX">
                                <small class="text-muted">Enter your Facebook Pixel ID for conversion tracking</small>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function addQuickLink() {
    const container = document.getElementById('quick-links-container');
    const html = `
        <div class="link-item row">
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" name="quick_link_title[]" placeholder="Title">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" name="quick_link_url[]" placeholder="URL">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-remove-link" onclick="this.closest('.link-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addWhyItem() {
    const container = document.getElementById('why-choose-container');
    const html = `
        <div class="link-item row">
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" name="why_title[]" placeholder="Title">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" name="why_subtitle[]" placeholder="Subtitle">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" name="why_url[]" placeholder="URL">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-remove-link" onclick="this.closest('.link-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Sync color inputs
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        const textInput = this.nextElementSibling;
        if (textInput && textInput.tagName === 'INPUT') {
            textInput.value = this.value;
        }
    });
});

// Track active tab and maintain it after form submission
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = '<?php echo htmlspecialchars($active_tab); ?>';
    
    // Set active tab on page load
    if (activeTab) {
        const tabLink = document.querySelector(`a[data-tab="${activeTab}"]`);
        const tabPane = document.getElementById(activeTab);
        
        if (tabLink && tabPane) {
            // Remove active from all tabs
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active to selected tab
            tabLink.classList.add('active');
            tabPane.classList.add('show', 'active');
        }
    }
    
    // Track tab changes
    document.querySelectorAll('a[data-toggle="pill"]').forEach(link => {
        link.addEventListener('shown.bs.tab', function(e) {
            const tabName = this.getAttribute('data-tab');
            document.getElementById('active_tab').value = tabName;
            
            // Update URL hash without reloading
            if (history.pushState) {
                history.pushState(null, null, '?tab=' + tabName);
            }
        });
    });
    
    // Handle form submission - maintain active tab
    const form = document.getElementById('settingsForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const currentTab = document.querySelector('.nav-link.active').getAttribute('data-tab');
            document.getElementById('active_tab').value = currentTab;
        });
    }
    
    // Check URL hash on load
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const tabLink = document.querySelector(`a[data-tab="${tabParam}"]`);
        if (tabLink) {
            tabLink.click();
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>

