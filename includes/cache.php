<?php
/**
 * MySmartSCart - Simple Cache System
 * Speeds up website by caching database queries
 */

class Cache {
    private $conn;
    private $cache_dir;
    private $use_file_cache = true;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->cache_dir = __DIR__ . '/../cache/';
        
        // Create cache directory if not exists
        if (!is_dir($this->cache_dir)) {
            @mkdir($this->cache_dir, 0755, true);
        }
    }
    
    /**
     * Get cached data
     */
    public function get($key) {
        // Try file cache first (faster)
        if ($this->use_file_cache) {
            $file = $this->cache_dir . md5($key) . '.cache';
            if (file_exists($file)) {
                $data = file_get_contents($file);
                $cache = unserialize($data);
                if ($cache && isset($cache['expires']) && $cache['expires'] > time()) {
                    return $cache['data'];
                }
                // Expired, delete file
                @unlink($file);
            }
        }
        
        // Try database cache
        $key_escaped = mysqli_real_escape_string($this->conn, $key);
        $query = "SELECT cache_value FROM cache WHERE cache_key = '$key_escaped' AND expires_at > NOW() LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return json_decode($row['cache_value'], true);
        }
        
        return null;
    }
    
    /**
     * Set cache data
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int $ttl Time to live in seconds (default 1 hour)
     */
    public function set($key, $data, $ttl = 3600) {
        // File cache
        if ($this->use_file_cache) {
            $file = $this->cache_dir . md5($key) . '.cache';
            $cache = [
                'data' => $data,
                'expires' => time() + $ttl
            ];
            @file_put_contents($file, serialize($cache));
        }
        
        // Database cache
        $key_escaped = mysqli_real_escape_string($this->conn, $key);
        $value_escaped = mysqli_real_escape_string($this->conn, json_encode($data));
        $expires = date('Y-m-d H:i:s', time() + $ttl);
        
        $query = "INSERT INTO cache (cache_key, cache_value, expires_at) 
                  VALUES ('$key_escaped', '$value_escaped', '$expires')
                  ON DUPLICATE KEY UPDATE cache_value = '$value_escaped', expires_at = '$expires'";
        @mysqli_query($this->conn, $query);
        
        return true;
    }
    
    /**
     * Delete specific cache
     */
    public function delete($key) {
        // Delete file cache
        $file = $this->cache_dir . md5($key) . '.cache';
        if (file_exists($file)) {
            @unlink($file);
        }
        
        // Delete from database
        $key_escaped = mysqli_real_escape_string($this->conn, $key);
        mysqli_query($this->conn, "DELETE FROM cache WHERE cache_key = '$key_escaped'");
    }
    
    /**
     * Clear all cache
     */
    public function clear() {
        // Clear file cache
        $files = glob($this->cache_dir . '*.cache');
        foreach ($files as $file) {
            @unlink($file);
        }
        
        // Clear database cache
        mysqli_query($this->conn, "DELETE FROM cache");
    }
    
    /**
     * Clear expired cache
     */
    public function clearExpired() {
        // Clear expired file cache
        $files = glob($this->cache_dir . '*.cache');
        foreach ($files as $file) {
            $data = @file_get_contents($file);
            if ($data) {
                $cache = @unserialize($data);
                if (!$cache || !isset($cache['expires']) || $cache['expires'] < time()) {
                    @unlink($file);
                }
            }
        }
        
        // Clear expired database cache
        mysqli_query($this->conn, "DELETE FROM cache WHERE expires_at < NOW()");
    }
}

/**
 * Quick cache functions for easy use
 */
function cache_get($key) {
    global $conn, $_cache;
    if (!isset($_cache)) {
        $_cache = new Cache($conn);
    }
    return $_cache->get($key);
}

function cache_set($key, $data, $ttl = 3600) {
    global $conn, $_cache;
    if (!isset($_cache)) {
        $_cache = new Cache($conn);
    }
    return $_cache->set($key, $data, $ttl);
}

function cache_delete($key) {
    global $conn, $_cache;
    if (!isset($_cache)) {
        $_cache = new Cache($conn);
    }
    return $_cache->delete($key);
}
?>

