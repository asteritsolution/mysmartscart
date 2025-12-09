# Email Setup Instructions

## Hostinger SMTP Configuration

The forgot password system uses Hostinger SMTP to send OTP emails.

**SMTP Credentials:**
- **SMTP Host:** smtp.hostinger.com
- **SMTP Port:** 587 (TLS)
- **Username:** noreply@krcwoollens.com
- **Password:** Wp7?Mnq?3OU
- **From Email:** noreply@krcwoollens.com
- **From Name:** KRC Woollens

## Installation

### Option 1: Using PHPMailer (Recommended)

1. Install PHPMailer via Composer:
```bash
composer require phpmailer/phpmailer
```

2. The system will automatically use PHPMailer if it's available.

### Option 2: Using PHP mail() function

If PHPMailer is not available, the system will fallback to PHP's `mail()` function. However, this may not work with SMTP authentication on all servers.

**For XAMPP/Windows:**
- You may need to configure `php.ini` to use SMTP
- Or install PHPMailer for better reliability

## Testing

1. Go to: `http://localhost/krcwoollen/forgot-password.php`
2. Enter a registered email address
3. Check email inbox for OTP
4. Enter OTP to verify
5. Set new password

## Troubleshooting

If emails are not being sent:

1. **Check PHP error logs** for email sending errors
2. **Verify SMTP credentials** are correct
3. **Install PHPMailer** for better SMTP support:
   ```bash
   composer require phpmailer/phpmailer
   ```
4. **Check firewall** - Port 587 should be open
5. **Verify Hostinger email account** is active

## Database Table

The system automatically creates a `password_resets` table to store OTPs. The table structure:

- `id` - Primary key
- `email` - User email
- `otp` - 6-digit OTP code
- `expires_at` - OTP expiration time (10 minutes)
- `used` - Whether OTP has been used
- `created_at` - Creation timestamp

