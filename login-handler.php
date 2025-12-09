<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please enter both email and password.';
        header("Location: login.php");
        exit;
    }
    
    // Check if user exists
    $query = "SELECT * FROM users WHERE email = '$email' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to intended page or cart
            $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'index.php';
            unset($_SESSION['redirect_after_login']);
            header("Location: " . $redirect);
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
        }
    } else {
        $_SESSION['login_error'] = 'Invalid email or password.';
    }
    
    header("Location: login.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>

