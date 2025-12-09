<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'View Message';

$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    header("Location: contact-messages.php");
    exit;
}

// Get message
$message_query = "SELECT * FROM contact_messages WHERE id = $id LIMIT 1";
$message_result = mysqli_query($conn, $message_query);
$message = mysqli_fetch_assoc($message_result);

if (!$message) {
    header("Location: contact-messages.php");
    exit;
}

// Mark as read
mysqli_query($conn, "UPDATE contact_messages SET status = 'read' WHERE id = $id");

include 'includes/header.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-envelope-open"></i> Message Details</h5>
        <a href="contact-messages.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Messages
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>From: <?php echo htmlspecialchars($message['name']); ?></strong>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></p>
            <p><strong>Subject:</strong> <?php echo htmlspecialchars($message['subject'] ?? 'No Subject'); ?></p>
            <p><strong>Date:</strong> <?php echo formatDate($message['created_at']); ?></p>
            <hr>
            <p><strong>Message:</strong></p>
            <div class="alert alert-light">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

