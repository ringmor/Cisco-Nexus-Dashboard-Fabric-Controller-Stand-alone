<?php
/**
 * Common Functions File
 * 
 * This file contains utility functions used throughout the application
 */

/**
 * Log a message to the application log
 */
function log_message($level, $message, $context = []) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message";
    
    if (!empty($context)) {
        $log_entry .= ' ' . json_encode($context);
    }
    
    $log_entry .= PHP_EOL;
    
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Sanitize user input
 */
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Redirect to another page
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Get current page URL
 */
function get_current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return "$protocol://$host$uri";
}

/**
 * Format bytes to human readable format
 */
function format_bytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Format timestamp to readable date
 */
function format_timestamp($timestamp) {
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * Check if user is authenticated
 */
function is_authenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require authentication
 */
function require_auth() {
    if (!is_authenticated()) {
        redirect('login.php');
    }
}

/**
 * Get asset URL
 */
function asset_url($path) {
    return APP_URL . '/src/assets/' . ltrim($path, '/');
}

/**
 * Include a view file
 */
function include_view($view, $data = []) {
    extract($data);
    $view_file = PHP_PATH . '/views/' . $view . '.php';
    
    if (file_exists($view_file)) {
        include $view_file;
    } else {
        log_message('ERROR', "View file not found: $view_file");
        echo "Error: View not found";
    }
}

/**
 * Make API request to Nexus switch
 */
function nexus_api_request($switch_ip, $command, $username = null, $password = null) {
    // Implementation for Nexus API requests
    // This would contain the logic for making API calls to Cisco Nexus switches
    
    log_message('INFO', "API request to $switch_ip: $command");
    
    // Placeholder implementation
    return [
        'success' => true,
        'data' => [],
        'message' => 'API request completed'
    ];
}

/**
 * Validate IP address
 */
function validate_ip($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP);
}

/**
 * Validate MAC address
 */
function validate_mac($mac) {
    return filter_var($mac, FILTER_VALIDATE_MAC);
}

/**
 * Get switch status
 */
function get_switch_status($switch_ip) {
    // Implementation for getting switch status
    return [
        'online' => true,
        'uptime' => '10 days, 5 hours',
        'cpu_usage' => 25,
        'memory_usage' => 60
    ];
}

/**
 * Send notification
 */
function send_notification($type, $message, $recipients = []) {
    log_message('INFO', "Notification sent: $type - $message");
    // Implementation for sending notifications (email, SMS, etc.)
}

/**
 * Backup configuration
 */
function backup_config($switch_ip, $config_data) {
    $backup_file = DATA_PATH . '/backups/' . $switch_ip . '_' . date('Y-m-d_H-i-s') . '.cfg';
    
    if (file_put_contents($backup_file, $config_data)) {
        log_message('INFO', "Configuration backed up: $backup_file");
        return true;
    }
    
    log_message('ERROR', "Failed to backup configuration: $backup_file");
    return false;
}
?> 