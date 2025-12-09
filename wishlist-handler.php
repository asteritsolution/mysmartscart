<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['wishlist_error'] = 'Please login to add products to wishlist.';
    $_SESSION['redirect_after_login'] = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'wishlist.php';
    header("Location: login.php");
    exit;
}

// Initialize wishlist if not exists
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add' && isset($_GET['id'])) {
        $product_id = (int) $_GET['id'];
        
        // Check if product exists and is active
        $check_query = "SELECT id FROM products WHERE id = $product_id AND status = 1 LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            // Add to wishlist
            $_SESSION['wishlist'][$product_id] = true;
            $_SESSION['wishlist_message'] = 'Product added to wishlist successfully!';
        } else {
            $_SESSION['wishlist_error'] = 'Product not found.';
        }
        
        // Redirect back
        $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'wishlist.php';
        header("Location: " . $redirect);
        exit;
    }
    
    if ($_GET['action'] == 'remove' && isset($_GET['id'])) {
        $product_id = (int) $_GET['id'];
        if (isset($_SESSION['wishlist'][$product_id])) {
            unset($_SESSION['wishlist'][$product_id]);
            $_SESSION['wishlist_message'] = 'Product removed from wishlist.';
        }
        header("Location: wishlist.php");
        exit;
    }
}

header("Location: wishlist.php");
exit;
?>

