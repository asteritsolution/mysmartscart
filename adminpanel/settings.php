<?php
require_once 'config.php';
checkAdminLogin();

$page_title = 'Settings';

$success = '';
$error = '';

// Get settings
$settings_query = "SELECT * FROM contact_settings LIMIT 1";
$settings_result = mysqli_query($conn, $settings_query);
$settings = mysqli_fetch_assoc($settings_result);

if (!$settings) {
    // Create default settings
    mysqli_query($conn, "INSERT INTO contact_settings (address, phone, email, business_hours_monday_friday, business_hours_saturday, business_hours_sunday, map_latitude, map_longitude) 
                        VALUES ('KRC Woollens, The Mall, Ranikhet, Uttarakhand, India', '+91 98765 43210', 'info@krcwoollens.com', '9am to 5pm', '9am to 2pm', 'Closed', 29.6458, 79.4200)");
    $settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM contact_settings LIMIT 1"));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $monday_friday_hours = mysqli_real_escape_string($conn, $_POST['monday_friday_hours'] ?? '');
    $saturday_hours = mysqli_real_escape_string($conn, $_POST['saturday_hours'] ?? '');
    $sunday_hours = mysqli_real_escape_string($conn, $_POST['sunday_hours'] ?? '');
    $map_latitude = floatval($_POST['map_latitude'] ?? 0);
    $map_longitude = floatval($_POST['map_longitude'] ?? 0);
    
    $query = "UPDATE contact_settings SET 
              address = '$address', 
              phone = '$phone', 
              email = '$email', 
              business_hours_monday_friday = '$monday_friday_hours', 
              business_hours_saturday = '$saturday_hours', 
              business_hours_sunday = '$sunday_hours', 
              map_latitude = $map_latitude, 
              map_longitude = $map_longitude 
              WHERE id = {$settings['id']}";
    
    if (mysqli_query($conn, $query)) {
        $success = 'Settings updated successfully!';
        $settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM contact_settings LIMIT 1"));
    } else {
        $error = 'Error updating settings: ' . mysqli_error($conn);
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <h5><i class="fas fa-cog"></i> Site Settings</h5>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" class="mt-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Contact Information</strong>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <strong>Business Hours</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Monday - Friday</label>
                            <input type="text" class="form-control" name="monday_friday_hours" value="<?php echo htmlspecialchars($settings['business_hours_monday_friday'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Saturday</label>
                            <input type="text" class="form-control" name="saturday_hours" value="<?php echo htmlspecialchars($settings['business_hours_saturday'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sunday</label>
                            <input type="text" class="form-control" name="sunday_hours" value="<?php echo htmlspecialchars($settings['business_hours_sunday'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <strong>Map Coordinates</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="number" step="0.00000001" class="form-control" name="map_latitude" value="<?php echo $settings['map_latitude'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="number" step="0.00000001" class="form-control" name="map_longitude" value="<?php echo $settings['map_longitude'] ?? ''; ?>">
                        </div>
                    </div>
                </div>
                <small class="form-text text-muted">Get coordinates from <a href="https://www.google.com/maps" target="_blank">Google Maps</a></small>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

