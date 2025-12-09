<?php
/**
 * MySmartSCart - Image Optimization Utility
 * Compresses images and creates thumbnails for faster loading
 */

class ImageOptimizer {
    private $max_width = 800;      // Max width for product images
    private $thumb_width = 300;    // Thumbnail width
    private $quality = 80;         // JPEG quality (1-100)
    private $webp_quality = 80;    // WebP quality
    
    /**
     * Optimize an image and create thumbnail
     * @param string $source_path Original image path
     * @param string $dest_folder Destination folder
     * @return array Array with optimized and thumbnail paths
     */
    public function optimize($source_path, $dest_folder = null) {
        if (!file_exists($source_path)) {
            return false;
        }
        
        $info = getimagesize($source_path);
        if (!$info) {
            return false;
        }
        
        $mime = $info['mime'];
        $width = $info[0];
        $height = $info[1];
        
        // Load image based on type
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source_path);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source_path);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source_path);
                break;
            default:
                return false;
        }
        
        if (!$image) {
            return false;
        }
        
        // Determine destination folder
        if (!$dest_folder) {
            $dest_folder = dirname($source_path);
        }
        
        $filename = pathinfo($source_path, PATHINFO_FILENAME);
        $results = [];
        
        // Create optimized main image
        if ($width > $this->max_width) {
            $new_height = intval($height * ($this->max_width / $width));
            $optimized = $this->resize($image, $width, $height, $this->max_width, $new_height);
        } else {
            $optimized = $image;
        }
        
        // Save optimized image
        $optimized_path = $dest_folder . '/' . $filename . '_optimized.jpg';
        imagejpeg($optimized, $optimized_path, $this->quality);
        $results['optimized'] = $optimized_path;
        
        // Create WebP version
        $webp_path = $dest_folder . '/' . $filename . '.webp';
        if (function_exists('imagewebp')) {
            imagewebp($optimized, $webp_path, $this->webp_quality);
            $results['webp'] = $webp_path;
        }
        
        // Create thumbnail
        $thumb_height = intval($height * ($this->thumb_width / $width));
        $thumbnail = $this->resize($image, $width, $height, $this->thumb_width, $thumb_height);
        $thumb_path = $dest_folder . '/' . $filename . '_thumb.jpg';
        imagejpeg($thumbnail, $thumb_path, $this->quality);
        $results['thumbnail'] = $thumb_path;
        
        // Create WebP thumbnail
        if (function_exists('imagewebp')) {
            $thumb_webp_path = $dest_folder . '/' . $filename . '_thumb.webp';
            imagewebp($thumbnail, $thumb_webp_path, $this->webp_quality);
            $results['thumbnail_webp'] = $thumb_webp_path;
        }
        
        // Free memory
        imagedestroy($image);
        if ($optimized !== $image) {
            imagedestroy($optimized);
        }
        imagedestroy($thumbnail);
        
        return $results;
    }
    
    /**
     * Resize image
     */
    private function resize($image, $old_width, $old_height, $new_width, $new_height) {
        $resized = imagecreatetruecolor($new_width, $new_height);
        
        // Preserve transparency for PNG
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
        imagefilledrectangle($resized, 0, 0, $new_width, $new_height, $transparent);
        
        // Resize
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
        
        return $resized;
    }
    
    /**
     * Batch optimize all images in a folder
     */
    public function batchOptimize($folder, $output_folder = null) {
        if (!is_dir($folder)) {
            return false;
        }
        
        $results = [];
        $images = glob($folder . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        foreach ($images as $image) {
            $result = $this->optimize($image, $output_folder);
            if ($result) {
                $results[] = $result;
            }
        }
        
        return $results;
    }
    
    /**
     * Get optimized image URL (returns WebP if browser supports)
     */
    public static function getOptimizedUrl($image_path, $use_thumbnail = false) {
        if (empty($image_path)) {
            return 'assets/images/products/placeholder.webp';
        }
        
        $pathinfo = pathinfo($image_path);
        $dir = $pathinfo['dirname'];
        $filename = $pathinfo['filename'];
        
        // Check for WebP support
        $supports_webp = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
        
        if ($use_thumbnail) {
            if ($supports_webp && file_exists($dir . '/' . $filename . '_thumb.webp')) {
                return $dir . '/' . $filename . '_thumb.webp';
            }
            if (file_exists($dir . '/' . $filename . '_thumb.jpg')) {
                return $dir . '/' . $filename . '_thumb.jpg';
            }
        } else {
            if ($supports_webp && file_exists($dir . '/' . $filename . '.webp')) {
                return $dir . '/' . $filename . '.webp';
            }
            if (file_exists($dir . '/' . $filename . '_optimized.jpg')) {
                return $dir . '/' . $filename . '_optimized.jpg';
            }
        }
        
        // Return original if no optimized version exists
        return $image_path;
    }
}

/**
 * Quick function to get lazy loading image HTML
 */
function lazy_image($src, $alt = '', $class = '', $width = '', $height = '') {
    $placeholder = 'assets/images/lazy.png';
    $attrs = '';
    if ($class) $attrs .= ' class="' . htmlspecialchars($class) . ' lazy"';
    else $attrs .= ' class="lazy"';
    if ($width) $attrs .= ' width="' . intval($width) . '"';
    if ($height) $attrs .= ' height="' . intval($height) . '"';
    
    return '<img src="' . $placeholder . '" data-src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '"' . $attrs . ' loading="lazy">';
}
?>

