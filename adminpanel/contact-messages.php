<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Contact Messages';

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id = $id");
    header("Location: contact-messages.php?deleted=1");
    exit;
}

// Handle status update
if (isset($_GET['mark_read']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "UPDATE contact_messages SET status = 'read' WHERE id = $id");
    header("Location: contact-messages.php?updated=1");
    exit;
}

// Get all messages
$messages_query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$messages_result = mysqli_query($conn, $messages_query);
$messages = [];
while ($row = mysqli_fetch_assoc($messages_result)) {
    $messages[] = $row;
}

include 'includes/header.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Message deleted successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Message marked as read!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="content-card">
    <h5><i class="fas fa-envelope"></i> Contact Messages</h5>
    
    <div class="table-responsive mt-4">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="7" class="text-center">No messages found</td>
                </tr>
                <?php else: ?>
                <?php foreach ($messages as $message): ?>
                <tr class="<?php echo $message['status'] == 'unread' ? 'table-warning' : ''; ?>">
                    <td><?php echo $message['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($message['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['subject'] ?? 'No Subject'); ?></td>
                    <td><?php echo formatDate($message['created_at']); ?></td>
                    <td>
                        <?php if ($message['status'] == 'unread'): ?>
                        <span class="badge badge-warning">Unread</span>
                        <?php else: ?>
                        <span class="badge badge-success">Read</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="message-view.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <?php if ($message['status'] == 'unread'): ?>
                        <a href="?mark_read=1&id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-check"></i> Mark Read
                        </a>
                        <?php endif; ?>
                        <a href="?delete=1&id=<?php echo $message['id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this message?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

