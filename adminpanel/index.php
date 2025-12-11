<?php
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        // Check if user is admin
        if (isAdmin($email)) {
            $query = "SELECT * FROM users WHERE email = '$email' AND status = 1 LIMIT 1";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                if (password_verify($password, $user['password'])) {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['admin_email'] = $user['email'];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = 'Invalid email or password!';
                }
            } else {
                $error = 'Invalid email or password!';
            }
        } else {
            $error = 'Access denied! Admin access only.';
        }
    } else {
        $error = 'Please fill all fields!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MySmartSCart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            color: white;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        .form-control.with-icon {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h3><i class="fas fa-shield-alt"></i> Admin Panel</h3>
            <p class="mb-0">MySmartSCart</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control with-icon" name="email" placeholder="admin@mysmartscart.in" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control with-icon" name="password" placeholder="Enter password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="text-center mt-3">
                <?php
                // Auto-detect base URL for website link
                require_once __DIR__ . '/../includes/site-settings.php';
                $site_base_url = getBaseUrl();
                ?>
                <a href="<?php echo rtrim($site_base_url, '/'); ?>" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Website</a>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

