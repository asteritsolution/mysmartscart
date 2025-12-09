<?php
// Email Configuration for Hostinger SMTP
// This file contains email sending functions

// Hostinger SMTP Settings
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@krcwoollens.com');
define('SMTP_PASSWORD', 'Wp7?Mnq?3OU');
define('SMTP_FROM_EMAIL', 'noreply@krcwoollens.com');
define('SMTP_FROM_NAME', 'KRC Woollens');

// Check if PHPMailer is available from mailing folder
$phpmailer_available = false;
$mailing_autoload = __DIR__ . '/../mailing/vendor/autoload.php';
if (file_exists($mailing_autoload)) {
    try {
        require_once $mailing_autoload;
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $phpmailer_available = true;
        }
    } catch (Exception $e) {
        // PHPMailer not available
        error_log("PHPMailer autoload error: " . $e->getMessage());
    }
}

// SMTP Email Function
function sendEmailSMTP($to, $subject, $message, $from_email = SMTP_FROM_EMAIL, $from_name = SMTP_FROM_NAME) {
    global $phpmailer_available;
    
    if ($phpmailer_available) {
        // Use PHPMailer if available
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0; // Set to 2 for debugging
            
            // Recipients
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($to);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);
            
            $mail->send();
            return true;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            // Fallback to simple mail function
            return sendEmailSimple($to, $subject, $message, $from_email, $from_name);
        } catch (\Exception $e) {
            error_log("Email Error: " . $e->getMessage());
            // Fallback to simple mail function
            return sendEmailSimple($to, $subject, $message, $from_email, $from_name);
        }
    } else {
        // Use simple mail function as fallback
        return sendEmailSimple($to, $subject, $message, $from_email, $from_name);
    }
}

// Simple email function using PHP mail() with SMTP configuration
function sendEmailSimple($to, $subject, $message, $from_email, $from_name) {
    // Try to use ini_set for SMTP (if server allows)
    @ini_set('SMTP', SMTP_HOST);
    @ini_set('smtp_port', SMTP_PORT);
    @ini_set('sendmail_from', $from_email);
    
    $headers = "From: $from_name <$from_email>\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Try sending email
    $result = @mail($to, $subject, $message, $headers);
    
    // If mail() fails, log error but don't reveal to user
    if (!$result) {
        error_log("Email sending failed for: $to");
    }
    
    return $result;
}

// Function to send OTP email
function sendOTPEmail($email, $otp) {
    $subject = "Password Reset OTP - KRC Woollens";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 5px; }
            .otp-box { background: white; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; }
            .otp-code { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>KRC Woollens Ranikhet</h2>
            </div>
            <div class='content'>
                <h3>Password Reset Request</h3>
                <p>Hello,</p>
                <p>You have requested to reset your password. Please use the following OTP (One-Time Password) to proceed:</p>
                
                <div class='otp-box'>
                    <div class='otp-code'>$otp</div>
                </div>
                
                <p><strong>This OTP will expire in 10 minutes.</strong></p>
                <p>If you did not request this password reset, please ignore this email.</p>
                
                <p>Best regards,<br>KRC Woollens Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated email. Please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailSMTP($email, $subject, $message);
}
?>

