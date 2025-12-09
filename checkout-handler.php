<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: login.php");
    exit;
}

// Redirect to cart if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Get user ID
$user_id = (int) $_SESSION['user_id'];

// Validate form data
$errors = [];

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$address_line1 = isset($_POST['address_line1']) ? trim($_POST['address_line1']) : '';
$address_line2 = isset($_POST['address_line2']) ? trim($_POST['address_line2']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$state = isset($_POST['state']) ? trim($_POST['state']) : '';
$postcode = isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : 'India';
$order_notes = isset($_POST['order_notes']) ? trim($_POST['order_notes']) : '';
$shipping_method = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : 'free';

// Validation
if (empty($first_name)) $errors[] = 'First name is required.';
if (empty($last_name)) $errors[] = 'Last name is required.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if (empty($phone)) $errors[] = 'Phone number is required.';
if (empty($address_line1)) $errors[] = 'Address is required.';
if (empty($city)) $errors[] = 'City is required.';
if (empty($state)) $errors[] = 'State is required.';
if (empty($postcode)) $errors[] = 'Postcode is required.';

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    header("Location: checkout.php");
    exit;
}

// Get cart items and calculate totals
$cart_items = [];
$subtotal = 0;
$product_ids = array_keys($_SESSION['cart']);
$ids_string = implode(',', array_map('intval', $product_ids));

$cart_query = "SELECT * FROM products WHERE id IN ($ids_string) AND status = 1";
$cart_result = mysqli_query($conn, $cart_query);

while ($product = mysqli_fetch_assoc($cart_result)) {
    $product_id = $product['id'];
    $quantity = $_SESSION['cart'][$product_id]['quantity'] ?? 1;
    
    $price = !empty($product['sale_price']) && $product['sale_price'] < $product['price']
        ? $product['sale_price']
        : $product['price'];
    
    $item_subtotal = $price * $quantity;
    $subtotal += $item_subtotal;
    
    $cart_items[] = [
        'id' => $product_id,
        'name' => $product['name'],
        'sku' => $product['sku'] ?? '',
        'quantity' => $quantity,
        'price' => $price,
        'subtotal' => $item_subtotal
    ];
}

// Calculate shipping
$shipping_cost = ($shipping_method == 'free') ? 0.00 : 0.00; // Free shipping for now
$total = $subtotal + $shipping_cost;

// Generate unique order number
$order_number = 'KRC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert order
    $first_name_escaped = mysqli_real_escape_string($conn, $first_name);
    $last_name_escaped = mysqli_real_escape_string($conn, $last_name);
    $email_escaped = mysqli_real_escape_string($conn, $email);
    $phone_escaped = mysqli_real_escape_string($conn, $phone);
    $company_escaped = mysqli_real_escape_string($conn, $company);
    $address_line1_escaped = mysqli_real_escape_string($conn, $address_line1);
    $address_line2_escaped = mysqli_real_escape_string($conn, $address_line2);
    $city_escaped = mysqli_real_escape_string($conn, $city);
    $state_escaped = mysqli_real_escape_string($conn, $state);
    $postcode_escaped = mysqli_real_escape_string($conn, $postcode);
    $country_escaped = mysqli_real_escape_string($conn, $country);
    $order_notes_escaped = mysqli_real_escape_string($conn, $order_notes);
    
    $order_query = "INSERT INTO orders (
        order_number, user_id, first_name, last_name, email, phone, company,
        address_line1, address_line2, city, state, postcode, country,
        subtotal, shipping_cost, total, payment_method, payment_status, order_status, order_notes
    ) VALUES (
        '$order_number', $user_id, '$first_name_escaped', '$last_name_escaped', '$email_escaped',
        '$phone_escaped', '$company_escaped', '$address_line1_escaped', '$address_line2_escaped',
        '$city_escaped', '$state_escaped', '$postcode_escaped', '$country_escaped',
        $subtotal, $shipping_cost, $total, 'cod', 'pending', 'pending', '$order_notes_escaped'
    )";
    
    if (!mysqli_query($conn, $order_query)) {
        throw new Exception("Error creating order: " . mysqli_error($conn));
    }
    
    $order_id = mysqli_insert_id($conn);
    
    // Insert order items
    foreach ($cart_items as $item) {
        $product_name_escaped = mysqli_real_escape_string($conn, $item['name']);
        $product_sku_escaped = mysqli_real_escape_string($conn, $item['sku']);
        
        $item_query = "INSERT INTO order_items (
            order_id, product_id, product_name, product_sku, quantity, price, subtotal
        ) VALUES (
            $order_id, {$item['id']}, '$product_name_escaped', '$product_sku_escaped',
            {$item['quantity']}, {$item['price']}, {$item['subtotal']}
        )";
        
        if (!mysqli_query($conn, $item_query)) {
            throw new Exception("Error creating order item: " . mysqli_error($conn));
        }
        
        // Update product stock (optional - reduce stock)
        // $update_stock = "UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['id']}";
        // mysqli_query($conn, $update_stock);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Clear cart
    $_SESSION['cart'] = [];
    
    // Store order number in session for success page
    $_SESSION['order_number'] = $order_number;
    $_SESSION['order_id'] = $order_id;
    
    // Redirect to success page
    header("Location: order-success.php");
    exit;
    
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    
    $_SESSION['checkout_error'] = 'Order placement failed. Please try again. Error: ' . $e->getMessage();
    header("Location: checkout.php");
    exit;
}
?>

