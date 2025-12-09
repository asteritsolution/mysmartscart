<?php
session_start();
include "config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['contact-name']) ? mysqli_real_escape_string($conn, trim($_POST['contact-name'])) : '';
    $email = isset($_POST['contact-email']) ? mysqli_real_escape_string($conn, trim($_POST['contact-email'])) : '';
    $subject = isset($_POST['contact-subject']) ? mysqli_real_escape_string($conn, trim($_POST['contact-subject'])) : '';
    $message = isset($_POST['contact-message']) ? mysqli_real_escape_string($conn, trim($_POST['contact-message'])) : '';
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (empty($errors)) {
        // Insert into database
        $sql = "INSERT INTO contact_messages (name, email, subject, message, status) 
                VALUES ('$name', '$email', '$subject', '$message', 'unread')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['contact_success'] = "Thank you for contacting us! We will get back to you soon.";
            header("Location: contact.php?success=1");
            exit();
        } else {
            $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again.";
            header("Location: contact.php?error=1");
            exit();
        }
    } else {
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['contact_form_data'] = $_POST;
        header("Location: contact.php?error=1");
        exit();
    }
} else {
    header("Location: contact.php");
    exit();
}
?>

