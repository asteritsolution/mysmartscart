<?php
/**
 * MySmartSCart - Image Optimization Tool
 * Run this to optimize all product images
 */

require_once 'config.php';
checkAdminLogin();

$page_title = 'Image Optimizer';

// Include image optimizer
require_once '../includes/image-optimizer.php';

$message = '';
$error = '';
$results = [];

// Handle optimization request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['optimize'])) {
    $optimizer = new ImageOptimizer();
    
    $folder = '../assets/images/products/';
    if (is_dir($folder)) {
        $images = glob($folder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $optimized_count = 0;
        
        foreach ($images as $image) {
            // Skip already optimized images
            if (strpos($image, '_optimized') !== false || 
                strpos($image, '_thumb') !== false ||
                strpos($image, '.webp') !== false) {
                continue;
            }
            
            $result = $optimizer->optimize($image);
            if ($result) {
                $results[] = [
                    'original' => $image,
                    'optimized' => $result
                ];
                $optimized_count++;
            }
        }
        
        $message = "Optimized $optimized_count images successfully!";
        
        // Update product thumbnails in database
        $update_query = "UPDATE products SET thumbnail = CONCAT(
            SUBSTRING_INDEX(image, '.', 1),
            '_thumb.jpg'
        ) WHERE image IS NOT NULL AND thumbnail IS NULL";
        mysqli_query($conn, $update_query);
        
    } else {
        $error = "Products folder not found!";
    }
}

// Get image statistics
$total_images = 0;
$optimized_images = 0;
$total_size = 0;
$optimized_size = 0;

$folder = '../assets/images/products/';
if (is_dir($folder)) {
    $all_images = glob($folder . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    foreach ($all_images as $img) {
        $size = filesize($img);
        if (strpos($img, '_optimized') !== false || strpos($img, '_thumb') !== false) {
            $optimized_images++;
            $optimized_size += $size;
        } else if (strpos($img, '.webp') === false) {
            $total_images++;
            $total_size += $size;
        }
    }
}

include 'includes/header.php';
?>

<div class="content-card">
    <h5><i class="fas fa-images"></i> Image Optimization Tool</h5>
    <p class="text-muted">Optimize product images to improve website speed. Creates compressed versions and WebP format.</p>
    
    <?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="icon"><i class="fas fa-image"></i></div>
                <h3><?php echo $total_images; ?></h3>
                <p>Original Images</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="icon"><i class="fas fa-compress"></i></div>
                <h3><?php echo $optimized_images; ?></h3>
                <p>Optimized Images</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="icon"><i class="fas fa-database"></i></div>
                <h3><?php echo round($total_size / 1024 / 1024, 2); ?> MB</h3>
                <p>Original Size</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                <h3><?php echo round($optimized_size / 1024 / 1024, 2); ?> MB</h3>
                <p>Optimized Size</p>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h6>What this tool does:</h6>
        <ul>
            <li>Creates compressed JPEG versions (80% quality)</li>
            <li>Creates WebP versions (modern browsers, 30% smaller)</li>
            <li>Creates thumbnails (300px width) for product listings</li>
            <li>Resizes large images to max 800px width</li>
        </ul>
        
        <form method="POST" class="mt-4">
            <button type="submit" name="optimize" class="btn btn-primary btn-lg" 
                    onclick="return confirm('This will optimize all product images. Continue?')">
                <i class="fas fa-magic"></i> Optimize All Images
            </button>
        </form>
    </div>
    
    <?php if (!empty($results)): ?>
    <div class="mt-4">
        <h6>Optimization Results:</h6>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Original</th>
                        <th>Optimized</th>
                        <th>Thumbnail</th>
                        <th>WebP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo basename($result['original']); ?></td>
                        <td><i class="fas fa-check text-success"></i> Created</td>
                        <td><i class="fas fa-check text-success"></i> Created</td>
                        <td><?php echo isset($result['optimized']['webp']) ? '<i class="fas fa-check text-success"></i> Created' : '<i class="fas fa-times text-muted"></i>'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="content-card mt-4">
    <h5><i class="fas fa-info-circle"></i> Image Guidelines for Best Performance</h5>
    <div class="row">
        <div class="col-md-6">
            <h6>Recommended Image Sizes:</h6>
            <ul>
                <li><strong>Product Main Image:</strong> 800x800px</li>
                <li><strong>Product Thumbnail:</strong> 300x300px</li>
                <li><strong>Banner Images:</strong> 1920x600px</li>
                <li><strong>Category Images:</strong> 400x300px</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h6>Best Practices:</h6>
            <ul>
                <li>Use JPEG for photos (smaller file size)</li>
                <li>Use PNG only for images with transparency</li>
                <li>Compress images before uploading</li>
                <li>Use descriptive file names (SEO benefit)</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

