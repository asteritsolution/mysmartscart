<?php
session_start();

// Include main config
require_once '../config.php';

// Admin session check function
function checkAdminLogin() {
    if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
        header("Location: index.php");
        exit;
    }
}

// Check if user is admin (for now, we'll use email check)
function isAdmin($email) {
    // Admin email
    $admin_emails = ['admin@krcwoollens.com'];
    return in_array($email, $admin_emails);
}

// Get admin user
function getAdminUser() {
    global $conn;
    if (isset($_SESSION['admin_id'])) {
        $admin_id = (int) $_SESSION['admin_id'];
        $query = "SELECT * FROM users WHERE id = $admin_id AND status = 1 LIMIT 1";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Format date
function formatDate($date) {
    return date('d M Y, h:i A', strtotime($date));
}

// Format currency
function formatCurrency($amount) {
    return '₹' . number_format($amount, 2);
}

// Get status badge
function getStatusBadge($status) {
    $badges = [
        'active' => '<span class="badge badge-success">Active</span>',
        'inactive' => '<span class="badge badge-secondary">Inactive</span>',
        'pending' => '<span class="badge badge-warning">Pending</span>',
        'processing' => '<span class="badge badge-info">Processing</span>',
        'shipped' => '<span class="badge badge-primary">Shipped</span>',
        'delivered' => '<span class="badge badge-success">Delivered</span>',
        'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
    ];
    return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
}
?>

