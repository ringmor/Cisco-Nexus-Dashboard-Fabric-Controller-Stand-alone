<?php
/**
 * Data Manager for Nexus Dashboard
 * Handles persistent storage of configuration data
 */

class DataManager {
    private $dataDir;
    
    public function __construct() {
        $this->dataDir = __DIR__ . '/data/';
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }
    
    /**
     * Save interface configuration
     */
    public function saveInterfaceConfig($interface, $config) {
        $interfaces = $this->loadData('interfaces.json', []);
        $interfaces[$interface] = array_merge(
            isset($interfaces[$interface]) ? $interfaces[$interface] : [],
            $config
        );
        return $this->saveData('interfaces.json', $interfaces);
    }
    
    /**
     * Load interface configuration
     */
    public function loadInterfaceConfig($interface = null) {
        $interfaces = $this->loadData('interfaces.json', []);
        return $interface ? (isset($interfaces[$interface]) ? $interfaces[$interface] : []) : $interfaces;
    }
    
    /**
     * Save VLAN configuration
     */
    public function saveVlanConfig($vlanId, $config) {
        $vlans = $this->loadData('vlans.json', []);
        $vlans[$vlanId] = array_merge(
            isset($vlans[$vlanId]) ? $vlans[$vlanId] : [],
            $config
        );
        return $this->saveData('vlans.json', $vlans);
    }
    
    /**
     * Load VLAN configuration
     */
    public function loadVlanConfig($vlanId = null) {
        $vlans = $this->loadData('vlans.json', []);
        return $vlanId ? (isset($vlans[$vlanId]) ? $vlans[$vlanId] : []) : $vlans;
    }
    
    /**
     * Save user configuration
     */
    public function saveUser($username, $userData) {
        $users = $this->loadData('users.json', []);
        $users[$username] = array_merge(
            isset($users[$username]) ? $users[$username] : [],
            $userData,
            ['created_at' => date('Y-m-d H:i:s')]
        );
        return $this->saveData('users.json', $users);
    }
    
    /**
     * Load user configuration
     */
    public function loadUsers($username = null) {
        $users = $this->loadData('users.json', []);
        return $username ? (isset($users[$username]) ? $users[$username] : null) : $users;
    }
    
    /**
     * Delete user
     */
    public function deleteUser($username) {
        $users = $this->loadData('users.json', []);
        if (isset($users[$username])) {
            unset($users[$username]);
            return $this->saveData('users.json', $users);
        }
        return false;
    }
    
    /**
     * Save static routes
     */
    public function saveStaticRoute($routeId, $routeData) {
        $routes = $this->loadData('static_routes.json', []);
        $routes[$routeId] = array_merge(
            isset($routes[$routeId]) ? $routes[$routeId] : [],
            $routeData,
            ['created_at' => date('Y-m-d H:i:s')]
        );
        return $this->saveData('static_routes.json', $routes);
    }
    
    /**
     * Load static routes
     */
    public function loadStaticRoutes($routeId = null) {
        $routes = $this->loadData('static_routes.json', []);
        return $routeId ? (isset($routes[$routeId]) ? $routes[$routeId] : null) : $routes;
    }
    
    /**
     * Delete static route
     */
    public function deleteStaticRoute($routeId) {
        $routes = $this->loadData('static_routes.json', []);
        if (isset($routes[$routeId])) {
            unset($routes[$routeId]);
            return $this->saveData('static_routes.json', $routes);
        }
        return false;
    }
    
    /**
     * Save ACL configuration
     */
    public function saveAcl($aclName, $aclData) {
        $acls = $this->loadData('acls.json', []);
        $acls[$aclName] = array_merge(
            isset($acls[$aclName]) ? $acls[$aclName] : [],
            $aclData,
            ['modified_at' => date('Y-m-d H:i:s')]
        );
        return $this->saveData('acls.json', $acls);
    }
    
    /**
     * Load ACL configuration
     */
    public function loadAcls($aclName = null) {
        $acls = $this->loadData('acls.json', []);
        return $aclName ? (isset($acls[$aclName]) ? $acls[$aclName] : null) : $acls;
    }
    
    /**
     * Save QoS configuration
     */
    public function saveQosConfig($type, $name, $config) {
        $qos = $this->loadData('qos.json', ['class_maps' => [], 'policy_maps' => [], 'service_policies' => []]);
        $qos[$type][$name] = array_merge(
            isset($qos[$type][$name]) ? $qos[$type][$name] : [],
            $config,
            ['modified_at' => date('Y-m-d H:i:s')]
        );
        return $this->saveData('qos.json', $qos);
    }
    
    /**
     * Load QoS configuration
     */
    public function loadQosConfig($type = null, $name = null) {
        $qos = $this->loadData('qos.json', ['class_maps' => [], 'policy_maps' => [], 'service_policies' => []]);
        if ($type && $name) {
            return isset($qos[$type][$name]) ? $qos[$type][$name] : null;
        } elseif ($type) {
            return isset($qos[$type]) ? $qos[$type] : [];
        }
        return $qos;
    }
    
    /**
     * Save system logs
     */
    public function saveLog($level, $message, $module = 'system') {
        $logs = $this->loadData('logs.json', []);
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'module' => $module,
            'message' => $message,
            'id' => uniqid()
        ];
        array_unshift($logs, $logEntry);
        
        // Keep only last 1000 log entries
        if (count($logs) > 1000) {
            $logs = array_slice($logs, 0, 1000);
        }
        
        return $this->saveData('logs.json', $logs);
    }
    
    /**
     * Load system logs
     */
    public function loadLogs($limit = 100, $level = null, $module = null) {
        $logs = $this->loadData('logs.json', []);
        
        // Filter by level and module if specified
        if ($level || $module) {
            $logs = array_filter($logs, function($log) use ($level, $module) {
                $levelMatch = !$level || $log['level'] === $level;
                $moduleMatch = !$module || $log['module'] === $module;
                return $levelMatch && $moduleMatch;
            });
        }
        
        return array_slice($logs, 0, $limit);
    }
    
    /**
     * Save monitoring data
     */
    public function saveMonitoringData($type, $data) {
        $monitoring = $this->loadData('monitoring.json', []);
        if (!isset($monitoring[$type])) {
            $monitoring[$type] = [];
        }
        
        $dataPoint = array_merge($data, ['timestamp' => time()]);
        array_unshift($monitoring[$type], $dataPoint);
        
        // Keep only last 1000 data points per type
        if (count($monitoring[$type]) > 1000) {
            $monitoring[$type] = array_slice($monitoring[$type], 0, 1000);
        }
        
        return $this->saveData('monitoring.json', $monitoring);
    }
    
    /**
     * Load monitoring data
     */
    public function loadMonitoringData($type = null, $limit = 100) {
        $monitoring = $this->loadData('monitoring.json', []);
        if ($type) {
            return isset($monitoring[$type]) ? array_slice($monitoring[$type], 0, $limit) : [];
        }
        return $monitoring;
    }
    
    /**
     * Save backup configuration
     */
    public function saveBackup($backupName, $config) {
        $backups = $this->loadData('backups.json', []);
        $backups[$backupName] = [
            'config' => $config,
            'created_at' => date('Y-m-d H:i:s'),
            'size' => strlen(json_encode($config))
        ];
        return $this->saveData('backups.json', $backups);
    }
    
    /**
     * Load backup configurations
     */
    public function loadBackups($backupName = null) {
        $backups = $this->loadData('backups.json', []);
        return $backupName ? (isset($backups[$backupName]) ? $backups[$backupName] : null) : $backups;
    }
    
    /**
     * Generic data loader
     */
    private function loadData($filename, $default = []) {
        $filepath = $this->dataDir . $filename;
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            $data = json_decode($content, true);
            return $data !== null ? $data : $default;
        }
        return $default;
    }
    
    /**
     * Generic data saver
     */
    private function saveData($filename, $data) {
        $filepath = $this->dataDir . $filename;
        $content = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($filepath, $content) !== false;
    }
    
    /**
     * Get data directory path
     */
    public function getDataDir() {
        return $this->dataDir;
    }
    
    /**
     * Clear all data (for testing)
     */
    public function clearAllData() {
        $files = glob($this->dataDir . '*.json');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }
}

// Initialize data manager
$dataManager = new DataManager();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    
    switch ($action) {
        case 'save_interface':
            $result = $dataManager->saveInterfaceConfig($input['interface'], $input['config']);
            if ($result) {
                $dataManager->saveLog('info', "Interface {$input['interface']} configuration saved", 'interface');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'load_interface':
            $config = $dataManager->loadInterfaceConfig($input['interface'] ?? null);
            echo json_encode(['success' => true, 'data' => $config]);
            break;
            
        case 'save_user':
            $result = $dataManager->saveUser($input['username'], $input['userData']);
            if ($result) {
                $dataManager->saveLog('info', "User {$input['username']} created/updated", 'aaa');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'load_users':
            $users = $dataManager->loadUsers();
            echo json_encode(['success' => true, 'data' => $users]);
            break;
            
        case 'delete_user':
            $result = $dataManager->deleteUser($input['username']);
            if ($result) {
                $dataManager->saveLog('info', "User {$input['username']} deleted", 'aaa');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'save_vlan':
            $result = $dataManager->saveVlanConfig($input['vlanId'], $input['config']);
            if ($result) {
                $dataManager->saveLog('info', "VLAN {$input['vlanId']} configuration saved", 'vlan');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'save_static_route':
            $result = $dataManager->saveStaticRoute($input['routeId'], $input['routeData']);
            if ($result) {
                $dataManager->saveLog('info', "Static route {$input['routeId']} saved", 'routing');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'save_qos':
            $result = $dataManager->saveQosConfig($input['type'], $input['name'], $input['config']);
            if ($result) {
                $dataManager->saveLog('info', "QoS {$input['type']} {$input['name']} saved", 'qos');
            }
            echo json_encode(['success' => $result]);
            break;
            
        case 'load_logs':
            $logs = $dataManager->loadLogs($input['limit'] ?? 100, $input['level'] ?? null, $input['module'] ?? null);
            echo json_encode(['success' => true, 'data' => $logs]);
            break;
            
        case 'save_monitoring':
            $result = $dataManager->saveMonitoringData($input['type'], $input['data']);
            echo json_encode(['success' => $result]);
            break;
            
        case 'load_monitoring':
            $data = $dataManager->loadMonitoringData($input['type'] ?? null, $input['limit'] ?? 100);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
            break;
    }
    exit;
}
?>

