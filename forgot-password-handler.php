<?php
session_start();
include "config.php";

// Create password_resets table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `otp` VARCHAR(6) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT(1) DEFAULT 0 COMMENT '1=Used, 0=Not Used',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `otp` (`otp`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
mysqli_query($conn, $create_table);

// Include email config
require_once 'includes/email-config.php';

$step = isset($_GET['step']) ? $_GET['step'] : (isset($_POST['step']) ? $_POST['step'] : 'email');
$error = '';
$success = '';

// Step 1: Send OTP to Email
if ($step == 'email' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if user exists
        $user_query = "SELECT id, first_name, email FROM users WHERE email = '$email' AND status = 1 LIMIT 1";
        $user_result = mysqli_query($conn, $user_query);
        
        if (mysqli_num_rows($user_result) > 0) {
            // Generate 6-digit OTP
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Set expiration (10 minutes from now)
            $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            // Invalidate previous OTPs for this email
            mysqli_query($conn, "UPDATE password_resets SET used = 1 WHERE email = '$email' AND used = 0");
            
            // Insert new OTP
            $insert_query = "INSERT INTO password_resets (email, otp, expires_at) VALUES ('$email', '$otp', '$expires_at')";
            
            if (mysqli_query($conn, $insert_query)) {
                // Send OTP email
                if (sendOTPEmail($email, $otp)) {
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['reset_otp_sent'] = true;
                    header("Location: forgot-password.php?step=otp");
                    exit;
                } else {
                    $error = 'Failed to send email. Please try again later.';
                }
            } else {
                $error = 'Error generating OTP. Please try again.';
            }
        } else {
            // Don't reveal if email exists or not (security best practice)
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_otp_sent'] = true;
            header("Location: forgot-password.php?step=otp");
            exit;
        }
    }
}

// Step 2: Verify OTP
if ($step == 'otp' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';
    $otp = mysqli_real_escape_string($conn, trim($_POST['otp'] ?? ''));
    
    if (empty($email)) {
        header("Location: forgot-password.php");
        exit;
    }
    
    if (empty($otp) || strlen($otp) != 6) {
        $error = 'Please enter a valid 6-digit OTP.';
    } else {
        // Verify OTP
        $otp_query = "SELECT * FROM password_resets 
                      WHERE email = '$email' 
                      AND otp = '$otp' 
                      AND used = 0 
                      AND expires_at > NOW() 
                      ORDER BY created_at DESC 
                      LIMIT 1";
        $otp_result = mysqli_query($conn, $otp_query);
        
        if (mysqli_num_rows($otp_result) > 0) {
            $otp_data = mysqli_fetch_assoc($otp_result);
            
            // Mark OTP as used
            mysqli_query($conn, "UPDATE password_resets SET used = 1 WHERE id = {$otp_data['id']}");
            
            // Set session for password reset
            $_SESSION['reset_verified'] = true;
            $_SESSION['reset_otp_id'] = $otp_data['id'];
            header("Location: forgot-password.php?step=password");
            exit;
        } else {
            $error = 'Invalid or expired OTP. Please try again.';
        }
    }
}

// Step 3: Reset Password
if ($step == 'password' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($email) || !isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
        header("Location: forgot-password.php");
        exit;
    }
    
    if (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email' AND status = 1";
        
        if (mysqli_query($conn, $update_query)) {
            // Clear reset session
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_otp_sent']);
            unset($_SESSION['reset_verified']);
            unset($_SESSION['reset_otp_id']);
            
            $_SESSION['password_reset_success'] = true;
            header("Location: forgot-password.php?step=success");
            exit;
        } else {
            $error = 'Error updating password. Please try again.';
        }
    }
}

// Redirect based on step
if ($step == 'email') {
    header("Location: forgot-password.php");
} elseif ($step == 'otp') {
    if (!isset($_SESSION['reset_otp_sent'])) {
        header("Location: forgot-password.php");
        exit;
    }
    header("Location: forgot-password.php?step=otp" . ($error ? '&error=' . urlencode($error) : ''));
} elseif ($step == 'password') {
    if (!isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
        header("Location: forgot-password.php");
        exit;
    }
    header("Location: forgot-password.php?step=password" . ($error ? '&error=' . urlencode($error) : ''));
} else {
    header("Location: forgot-password.php");
}
exit;
?>

