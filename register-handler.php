<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $check_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = 'Email already registered. Please login instead.';
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header("Location: login.php");
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $insert_query = "INSERT INTO users (first_name, last_name, email, phone, password, status) 
                     VALUES ('$first_name', '$last_name', '$email', '$phone', '$hashed_password', 1)";
    
    if (mysqli_query($conn, $insert_query)) {
        $user_id = mysqli_insert_id($conn);
        
        // Auto login after registration
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $first_name . ' ' . $last_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['register_success'] = 'Registration successful! Welcome to KRC Woollens.';
        
        // Redirect to intended page or index
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'index.php';
        unset($_SESSION['redirect_after_login']);
        header("Location: " . $redirect);
        exit;
    } else {
        $_SESSION['register_errors'] = ['Registration failed. Please try again.'];
    }
    
    header("Location: login.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>

