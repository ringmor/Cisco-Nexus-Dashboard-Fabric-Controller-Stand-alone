<?php
/**
 * Configuration File
 * 
 * This file contains all configuration settings for the Cisco Nexus Dashboard
 */

// Database configuration (if needed)
define('DB_HOST', 'localhost');
define('DB_NAME', 'nexus_dashboard');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'Cisco Nexus Dashboard Fabric Controller');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/Cisco-Nexus-Dashboard-Fabric-Controller-Stand-alone');

// File paths
define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
define('SRC_PATH', ROOT_PATH . '/src');
define('PHP_PATH', SRC_PATH . '/php');
define('ASSETS_PATH', SRC_PATH . '/assets');
define('DATA_PATH', ROOT_PATH . '/data');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Logging configuration
define('LOG_LEVEL', 'DEBUG'); // DEBUG, INFO, WARNING, ERROR
define('LOG_FILE', LOGS_PATH . '/application.log');

// Security configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');

// API configuration
define('NEXUS_API_TIMEOUT', 30);
define('NEXUS_API_RETRIES', 3);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', LOG_FILE);

// Timezone
date_default_timezone_set('UTC');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Load environment-specific configuration
$env_file = CONFIG_PATH . '/environment.php';
if (file_exists($env_file)) {
    include $env_file;
}
?> 