<?php
// Prevent any output before headers
if (!ob_get_level()) {
    ob_start();
}

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "config.php";

// Debug mode (set to false in production)
$debug = false; // Set to true for debugging

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please enter both email and password.';
        session_write_close();
        while (ob_get_level()) {
            ob_end_clean();
        }
        header("Location: /mysmartscart/login", true, 302);
        exit;
    }
    
    // Sanitize email
    $email = mysqli_real_escape_string($conn, $email);
    
    // Check if user exists
    $query = "SELECT * FROM users WHERE email = '$email' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    // Check for database errors
    if (!$result) {
        $_SESSION['login_error'] = 'Database error. Please try again later.';
        if ($debug) {
            $_SESSION['login_error'] .= ' Error: ' . mysqli_error($conn);
        }
        session_write_close();
        while (ob_get_level()) {
            ob_end_clean();
        }
        header("Location: /mysmartscart/login", true, 302);
        exit;
    }
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Debug: Log password verification attempt
        if ($debug) {
            error_log("Login attempt - Email: $email, Password length: " . strlen($password));
            error_log("Stored hash: " . substr($user['password'], 0, 20) . "...");
        }
        
        // Verify password
        $password_verified = password_verify($password, $user['password']);
        
        if ($debug) {
            error_log("Password verification result: " . ($password_verified ? 'SUCCESS' : 'FAILED'));
        }
        
        if ($password_verified) {
            // Login successful - Set session variables
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
            $_SESSION['user_email'] = $user['email'];
            
            // Clear any login errors
            unset($_SESSION['login_error']);
            
            // Get redirect URL BEFORE closing session
            $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/mysmartscart/';
            unset($_SESSION['redirect_after_login']);
            
            // Ensure redirect starts with / for absolute path
            if (substr($redirect, 0, 1) !== '/') {
                $redirect = '/mysmartscart/' . $redirect;
            }
            
            // Store session data before closing (for debug logging)
            $session_user_id = $_SESSION['user_id'];
            $session_user_name = $_SESSION['user_name'];
            $session_user_email = $_SESSION['user_email'];
            
            // Write session data immediately (this saves the session)
            session_write_close();
            
            // Clear ALL output buffers completely
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Use absolute URL for redirect
            if ($debug) {
                error_log("Login successful - User ID: " . $session_user_id . ", Email: " . $session_user_email . ", Redirecting to: " . $redirect);
            }
            
            // Redirect with 302 status - MUST be absolute URL
            header("Location: " . $redirect, true, 302);
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password. Password verification failed.';
            if ($debug) {
                $_SESSION['login_error'] .= ' (Debug: Hash mismatch)';
            }
        }
    } else {
        $_SESSION['login_error'] = 'Invalid email or password. User not found.';
        if ($debug) {
            $_SESSION['login_error'] .= ' (Debug: No user with email: ' . htmlspecialchars($email) . ')';
        }
    }
    
    session_write_close();
    while (ob_get_level()) {
        ob_end_clean();
    }
    header("Location: /mysmartscart/login", true, 302);
    exit;
} else {
    // Not a POST request or missing login parameter
    session_write_close();
    while (ob_get_level()) {
        ob_end_clean();
    }
    header("Location: /mysmartscart/login", true, 302);
    exit;
}
?>
