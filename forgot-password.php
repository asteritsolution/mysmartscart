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

// Get current step
$step = isset($_GET['step']) ? $_GET['step'] : 'email';
$error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
$success = isset($_SESSION['password_reset_success']) ? true : false;

// Include email config
require_once 'includes/email-config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($step == 'email') {
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
                        header("Location: forgot-password.php?step=otp&success=OTP sent to your email");
                        exit;
                    } else {
                        $error = 'Failed to send email. Please try again later.';
                    }
                } else {
                    $error = 'Error generating OTP. Please try again.';
                }
            } else {
                // Don't reveal if email exists or not (security best practice)
                // Still show success to prevent email enumeration
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_otp_sent'] = true;
                header("Location: forgot-password.php?step=otp&success=OTP sent to your email");
                exit;
            }
        }
    } elseif ($step == 'otp') {
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
    } elseif ($step == 'password') {
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
}

// Get success message from URL
$success_msg = isset($_GET['success']) ? urldecode($_GET['success']) : '';

// Clear success session after displaying
if ($success) {
    unset($_SESSION['password_reset_success']);
}

include "common/header.php";
?>

<main class="main">
    <div class="page-header">
        <div class="container d-flex flex-column align-items-center">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="login.php">Login</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
                    </ol>
                </div>
            </nav>
            <h1>Forgot Password</h1>
        </div>
    </div>

    <div class="container reset-password-container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="feature-box border-top-primary">
                    <div class="feature-box-content">
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success_msg): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Step 1: Email Input -->
                        <?php if ($step == 'email'): ?>
                        <form method="POST" action="forgot-password.php" class="mb-0">
                            <input type="hidden" name="step" value="email">
                            <h3 class="mb-3"><i class="fas fa-envelope"></i> Enter Your Email</h3>
                            <p>
                                Lost your password? Please enter your email address. 
                                You will receive an OTP (One-Time Password) to reset your password.
                            </p>
                            <div class="form-group mb-3">
                                <label for="reset-email" class="font-weight-normal">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="reset-email" name="email" 
                                       placeholder="your@email.com" required autofocus />
                            </div>
                            <div class="form-footer mb-0">
                                <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
                                <button type="submit" class="btn btn-md btn-primary form-footer-right font-weight-normal text-transform-none mr-0">
                                    <i class="fas fa-paper-plane"></i> Send OTP
                                </button>
                            </div>
                        </form>
                        
                        <!-- Step 2: OTP Verification -->
                        <?php elseif ($step == 'otp' && isset($_SESSION['reset_otp_sent'])): ?>
                        <form method="POST" action="forgot-password.php?step=otp" class="mb-0">
                            <input type="hidden" name="step" value="otp">
                            <h3 class="mb-3"><i class="fas fa-key"></i> Enter OTP</h3>
                            <p>
                                We've sent a 6-digit OTP to <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? ''); ?></strong>
                                <br>Please check your email and enter the OTP below.
                            </p>
                            <div class="form-group mb-3">
                                <label for="otp" class="font-weight-normal">OTP (6 digits) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-center" id="otp" name="otp" 
                                       placeholder="000000" maxlength="6" pattern="[0-9]{6}" 
                                       style="font-size: 24px; letter-spacing: 10px;" required autofocus />
                                <small class="form-text text-muted">OTP expires in 10 minutes</small>
                            </div>
                            <div class="form-footer mb-0">
                                <a href="forgot-password.php"><i class="fas fa-arrow-left"></i> Change Email</a>
                                <button type="submit" class="btn btn-md btn-primary form-footer-right font-weight-normal text-transform-none mr-0">
                                    <i class="fas fa-check"></i> Verify OTP
                                </button>
                            </div>
                        </form>
                        
                        <!-- Step 3: New Password -->
                        <?php elseif ($step == 'password' && isset($_SESSION['reset_verified']) && $_SESSION['reset_verified']): ?>
                        <form method="POST" action="forgot-password.php?step=password" class="mb-0" id="passwordForm">
                            <input type="hidden" name="step" value="password">
                            <h3 class="mb-3"><i class="fas fa-lock"></i> Set New Password</h3>
                            <p>Please enter your new password below.</p>
                            <div class="form-group mb-3">
                                <label for="password" class="font-weight-normal">New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter new password" minlength="6" required />
                                <small class="form-text text-muted">Password must be at least 6 characters long</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password" class="font-weight-normal">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm new password" minlength="6" required />
                                <div id="passwordMatch" class="mt-2"></div>
                            </div>
                            <div class="form-footer mb-0">
                                <a href="forgot-password.php?step=otp"><i class="fas fa-arrow-left"></i> Back</a>
                                <button type="submit" class="btn btn-md btn-primary form-footer-right font-weight-normal text-transform-none mr-0">
                                    <i class="fas fa-save"></i> Reset Password
                                </button>
                            </div>
                        </form>
                        
                        <!-- Step 4: Success -->
                        <?php elseif ($step == 'success' || $success): ?>
                        <div class="text-center mb-0">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                            </div>
                            <h3 class="mb-3">Password Reset Successful!</h3>
                            <p class="mb-4">
                                Your password has been successfully reset. You can now login with your new password.
                            </p>
                            <div class="form-footer mb-0">
                                <a href="login.php" class="btn btn-md btn-primary font-weight-normal text-transform-none">
                                    <i class="fas fa-sign-in-alt"></i> Go to Login
                                </a>
                            </div>
                        </div>
                        
                        <?php else: ?>
                        <!-- Invalid step, redirect to email step -->
                        <?php 
                        header("Location: forgot-password.php"); 
                        exit; 
                        ?>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "common/footer.php"; ?>

<script>
// OTP input formatting
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // Password match validation
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordMatch = document.getElementById('passwordMatch');
        
        function checkPasswordMatch() {
            if (confirmPassword.value === '') {
                passwordMatch.innerHTML = '';
                return;
            }
            
            if (password.value === confirmPassword.value) {
                passwordMatch.innerHTML = '<small class="text-success"><i class="fas fa-check-circle"></i> Passwords match</small>';
                confirmPassword.setCustomValidity('');
            } else {
                passwordMatch.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle"></i> Passwords do not match</small>';
                confirmPassword.setCustomValidity('Passwords do not match');
            }
        }
        
        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }
});
</script>
