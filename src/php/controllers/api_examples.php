<?php
/**
 * NX-API Examples for Port Status Reading
 * This file demonstrates real API calls to Nexus switches
 */

// Example 1: Basic Interface Status
function getInterfaceStatus($nexus_ip, $username, $password) {
    $command = "show interface brief";
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => $command,
            "output_format" => "json"
        ]
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$nexus_ip/ins");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_error($ch)) {
        curl_close($ch);
        return ["error" => curl_error($ch)];
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return ["error" => "HTTP Error: $httpCode"];
    }
    
    return json_decode($response, true);
}

// Example 2: Detailed Interface Information
function getDetailedInterfaceInfo($nexus_ip, $username, $password, $interface) {
    $command = "show interface $interface";
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => $command,
            "output_format" => "json"
        ]
    ]);
    
    return executeNXAPICommand($nexus_ip, $username, $password, $body);
}

// Example 3: Interface Counters
function getInterfaceCounters($nexus_ip, $username, $password, $interface = null) {
    $command = $interface ? "show interface $interface counters" : "show interface counters";
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => $command,
            "output_format" => "json"
        ]
    ]);
    
    return executeNXAPICommand($nexus_ip, $username, $password, $body);
}

// Example 4: Interface Status with Reasons
function getInterfaceStatusWithReasons($nexus_ip, $username, $password) {
    $command = "show interface status";
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => $command,
            "output_format" => "json"
        ]
    ]);
    
    return executeNXAPICommand($nexus_ip, $username, $password, $body);
}

// Example 5: Interface Descriptions
function getInterfaceDescriptions($nexus_ip, $username, $password) {
    $command = "show interface description";
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => $command,
            "output_format" => "json"
        ]
    ]);
    
    return executeNXAPICommand($nexus_ip, $username, $password, $body);
}

// Example 6: Multiple Commands in One Request
function getMultipleInterfaceInfo($nexus_ip, $username, $password) {
    $commands = [
        "show interface brief",
        "show interface status",
        "show interface description"
    ];
    
    $body = json_encode([
        "ins_api" => [
            "version" => "1.0",
            "type" => "cli_show",
            "chunk" => "0",
            "sid" => "1",
            "input" => implode(" ; ", $commands),
            "output_format" => "json"
        ]
    ]);
    
    return executeNXAPICommand($nexus_ip, $username, $password, $body);
}

// Helper function for executing NX-API commands
function executeNXAPICommand($nexus_ip, $username, $password, $body) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$nexus_ip/ins");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_error($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => $error];
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return ["error" => "HTTP Error: $httpCode"];
    }
    
    return json_decode($response, true);
}

// Example 7: Parse Interface Status Response
function parseInterfaceStatus($apiResponse) {
    $interfaces = [];
    
    if (isset($apiResponse['ins_api']['outputs']['output']['body']['TABLE_interface']['ROW_interface'])) {
        $rows = $apiResponse['ins_api']['outputs']['output']['body']['TABLE_interface']['ROW_interface'];
        
        // Handle single interface response
        if (!is_array($rows) || !isset($rows[0])) {
            $rows = [$rows];
        }
        
        foreach ($rows as $row) {
            $interfaces[] = [
                'interface' => $row['interface'] ?? '',
                'state' => $row['state'] ?? 'unknown',
                'admin_state' => $row['admin_state'] ?? 'unknown',
                'description' => $row['desc'] ?? '',
                'vlan' => $row['vlan'] ?? '',
                'speed' => $row['speed'] ?? '',
                'duplex' => $row['duplex'] ?? '',
                'type' => $row['type'] ?? '',
                'mtu' => $row['mtu'] ?? '',
                'reason' => $row['reason'] ?? ''
            ];
        }
    }
    
    return $interfaces;
}

// Example 8: Real-time Status Monitoring
function monitorInterfaceStatus($nexus_ip, $username, $password, $interval = 10) {
    while (true) {
        $response = getInterfaceStatus($nexus_ip, $username, $password);
        
        if (isset($response['error'])) {
            echo "Error: " . $response['error'] . "\n";
            sleep($interval);
            continue;
        }
        
        $interfaces = parseInterfaceStatus($response);
        
        echo "=== Interface Status Update - " . date('Y-m-d H:i:s') . " ===\n";
        foreach ($interfaces as $intf) {
            $status = ($intf['admin_state'] === 'down') ? 'ADMIN-DOWN' : 
                     (($intf['state'] === 'up') ? 'UP' : 'DOWN');
            
            echo sprintf("%-15s %-10s %-30s\n", 
                $intf['interface'], 
                $status, 
                $intf['description']
            );
        }
        echo "\n";
        
        sleep($interval);
    }
}

// Example 9: Configuration Change Detection
function detectConfigChanges($nexus_ip, $username, $password) {
    static $lastConfig = null;
    
    $response = getInterfaceDescriptions($nexus_ip, $username, $password);
    
    if (isset($response['error'])) {
        return ["error" => $response['error']];
    }
    
    $currentConfig = parseInterfaceStatus($response);
    
    if ($lastConfig === null) {
        $lastConfig = $currentConfig;
        return ["message" => "Initial configuration captured"];
    }
    
    $changes = [];
    foreach ($currentConfig as $current) {
        foreach ($lastConfig as $last) {
            if ($current['interface'] === $last['interface']) {
                if ($current['description'] !== $last['description'] ||
                    $current['state'] !== $last['state'] ||
                    $current['admin_state'] !== $last['admin_state']) {
                    
                    $changes[] = [
                        'interface' => $current['interface'],
                        'old' => $last,
                        'new' => $current
                    ];
                }
                break;
            }
        }
    }
    
    $lastConfig = $currentConfig;
    return $changes;
}

// Example 10: Health Check
function performHealthCheck($nexus_ip, $username, $password) {
    $results = [
        'timestamp' => date('Y-m-d H:i:s'),
        'switch_ip' => $nexus_ip,
        'api_status' => 'unknown',
        'total_interfaces' => 0,
        'interfaces_up' => 0,
        'interfaces_down' => 0,
        'interfaces_admin_down' => 0,
        'errors' => []
    ];
    
    // Test API connectivity
    $response = getInterfaceStatus($nexus_ip, $username, $password);
    
    if (isset($response['error'])) {
        $results['api_status'] = 'failed';
        $results['errors'][] = $response['error'];
        return $results;
    }
    
    $results['api_status'] = 'connected';
    
    // Parse interface data
    $interfaces = parseInterfaceStatus($response);
    $results['total_interfaces'] = count($interfaces);
    
    foreach ($interfaces as $intf) {
        if ($intf['admin_state'] === 'down') {
            $results['interfaces_admin_down']++;
        } elseif ($intf['state'] === 'up') {
            $results['interfaces_up']++;
        } else {
            $results['interfaces_down']++;
        }
    }
    
    return $results;
}

// Usage Examples:

/*
// Basic usage
$switch_ip = "192.168.1.100";
$username = "admin";
$password = "admin";

// Get interface status
$status = getInterfaceStatus($switch_ip, $username, $password);
print_r($status);

// Get detailed info for specific interface
$details = getDetailedInterfaceInfo($switch_ip, $username, $password, "Ethernet1/1");
print_r($details);

// Perform health check
$health = performHealthCheck($switch_ip, $username, $password);
print_r($health);

// Monitor interfaces (runs continuously)
// monitorInterfaceStatus($switch_ip, $username, $password, 30);
*/

?>

