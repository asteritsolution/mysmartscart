<?php
// Database Configuration - Check if constants are already defined
// Set ENVIRONMENT to 'production' on live server, 'development' for local
if (!defined('ENVIRONMENT')) {
    // Auto-detect: if localhost then development, otherwise production
    $is_localhost = ($_SERVER['HTTP_HOST'] ?? 'localhost') === 'localhost' || 
                    strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false ||
                    ($_SERVER['SERVER_ADDR'] ?? '') === '127.0.0.1';
    define('ENVIRONMENT', $is_localhost ? 'development' : 'production');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (ENVIRONMENT === 'production') {
    // Production (Live Server) Credentials
    if (!defined('DB_USER')) {
        define('DB_USER', 'u282526926_krcwoollens');
    }
    if (!defined('DB_PASS')) {
        define('DB_PASS', '^xCKh3JmH3');
    }
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'u282526926_krcwoollens');
    }
} else {
    // Development (Local) Credentials
    if (!defined('DB_USER')) {
        define('DB_USER', 'root');
    }
    if (!defined('DB_PASS')) {
        define('DB_PASS', '');
    }
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'krcwoollen');
    }
}

// Create database connection only if not already created
if (!isset($conn) || !$conn) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Set charset to utf8
    mysqli_set_charset($conn, "utf8");
}

?>

