<?php
$admin = getAdminUser();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - MySmartSCart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        :root {
            --primary-color: #1a237e;
            --primary-orange: #f68b28;
            --royal-blue: #1a237e;
            --royal-blue-medium: #283593;
            --royal-blue-light: #3949ab;
            --sidebar-width: 260px;
        }
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 15px rgba(26, 35, 126, 0.3);
        }
        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 2px solid rgba(246, 139, 40, 0.3);
        }
        .sidebar-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }
        .sidebar-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            opacity: 0.9;
            color: rgba(255,255,255,0.8);
        }
        .sidebar-menu {
            padding: 10px 0;
        }
        .sidebar-menu .menu-item {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu .menu-item:hover {
            background: rgba(246, 139, 40, 0.15);
            color: white;
            border-left-color: var(--primary-orange);
            transform: translateX(3px);
        }
        .sidebar-menu .menu-item.active {
            background: rgba(246, 139, 40, 0.25);
            color: white;
            border-left-color: var(--primary-orange);
            font-weight: 600;
        }
        .sidebar-menu .menu-item i {
            width: 20px;
            margin-right: 10px;
            color: rgba(255,255,255,0.7);
        }
        .sidebar-menu .menu-item.active i,
        .sidebar-menu .menu-item:hover i {
            color: var(--primary-orange);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid var(--primary-orange);
        }
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-top: 3px solid var(--royal-blue);
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.2s;
            border-top: 3px solid transparent;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .stat-card.primary:hover {
            border-top-color: var(--royal-blue);
        }
        .stat-card.success:hover {
            border-top-color: #28a745;
        }
        .stat-card.warning:hover {
            border-top-color: var(--primary-orange);
        }
        .stat-card.info:hover {
            border-top-color: #17a2b8;
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        .stat-card.primary .icon { 
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%); 
        }
        .stat-card.success .icon { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
        }
        .stat-card.warning .icon { 
            background: linear-gradient(135deg, #f68b28 0%, #ff9800 100%); 
        }
        .stat-card.info .icon { 
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); 
        }
        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }
        .stat-card p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #283593 0%, #3949ab 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 35, 126, 0.4);
            color: #fff;
        }
        .btn-outline-primary {
            border-color: var(--royal-blue);
            color: var(--royal-blue);
        }
        .btn-outline-primary:hover {
            background: var(--royal-blue);
            border-color: var(--royal-blue);
            color: #fff;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
            color: white;
        }
        .table thead th {
            border: none;
            font-weight: 600;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        .badge-primary {
            background: var(--royal-blue);
            color: #fff;
        }
        .badge-success {
            background: #28a745;
        }
        .badge-warning {
            background: var(--primary-orange);
            color: #fff;
        }
        .badge-danger {
            background: #dc3545;
        }
        .alert-success {
            border-left: 4px solid #28a745;
        }
        .alert-danger {
            border-left: 4px solid #dc3545;
        }
        .alert-info {
            border-left: 4px solid var(--royal-blue);
        }
        .alert-warning {
            border-left: 4px solid var(--primary-orange);
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-shield-alt"></i> Admin Panel</h4>
            <p>MySmartSCart</p>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="products.php" class="menu-item <?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="categories.php" class="menu-item <?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="orders.php" class="menu-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="banners.php" class="menu-item <?php echo $current_page == 'banners.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i> Banners
            </a>
            <a href="users.php" class="menu-item <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="contact-messages.php" class="menu-item <?php echo $current_page == 'contact-messages.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Contact Messages
            </a>
            <a href="site-settings.php" class="menu-item <?php echo $current_page == 'site-settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-palette"></i> Site Settings
            </a>
            <a href="menu-manager.php" class="menu-item <?php echo $current_page == 'menu-manager.php' ? 'active' : ''; ?>">
                <i class="fas fa-bars"></i> Menu Manager
            </a>
            <a href="sitemap-generator.php" class="menu-item <?php echo $current_page == 'sitemap-generator.php' ? 'active' : ''; ?>">
                <i class="fas fa-sitemap"></i> Sitemap Generator
            </a>
            <a href="settings.php" class="menu-item <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Contact Settings
            </a>
            <a href="logout.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="main-content">
        <div class="top-bar">
            <div>
                <button class="btn btn-sm btn-primary d-md-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="d-inline-block mb-0 ml-2"><?php echo $page_title ?? 'Dashboard'; ?></h5>
            </div>
            <div>
                <span class="text-muted">Welcome, <strong><?php echo htmlspecialchars($admin['first_name'] ?? 'Admin'); ?></strong></span>
                <?php
                // Auto-detect base URL for website link
                require_once __DIR__ . '/../../includes/site-settings.php';
                $site_base_url = getBaseUrl();
                ?>
                <a href="<?php echo rtrim($site_base_url, '/'); ?>" class="btn btn-sm btn-outline-primary ml-3" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
            </div>
        </div>

