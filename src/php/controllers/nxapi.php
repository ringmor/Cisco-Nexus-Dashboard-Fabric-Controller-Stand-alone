<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
header("Content-Type: application/json");

// Read settings from dashboard_settings.json
$settings_file = 'data/dashboard_settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true);
    $nexus_ip = $settings['switchIP'];
    $user = $settings['username'];
    $pass = $settings['password'];
    $use_https = $settings['useHTTPS'];
    $port = $settings['switchPort'];
} else {
    echo json_encode(["error" => "Settings file not found"]);
    exit;
}

// Get input data (handle both JSON and form data)
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Override settings with POST body if provided
if (isset($input['switchIP']) && $input['switchIP']) $nexus_ip = $input['switchIP'];
if (isset($input['switchPort']) && $input['switchPort']) $port = $input['switchPort'];
if (isset($input['username']) && $input['username']) $user = $input['username'];
if (isset($input['password']) && $input['password']) $pass = $input['password'];
if (isset($input['useHTTPS'])) $use_https = $input['useHTTPS'] ? true : false;

$action = isset($input['action']) ? $input['action'] : 'execute_command';

// Debug logging
file_put_contents('nxapi_debug.log', "ACTION: $action\nINPUT: " . json_encode($input) . "\n", FILE_APPEND);

if ($action === 'test_connection') {
    // Test connection to switch
    $ch = curl_init();
    $protocol = $use_https ? 'https' : 'http';
    curl_setopt($ch, CURLOPT_URL, "$protocol://$nexus_ip:$port/ins");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["ins_api"=>["version"=>"1.0","type"=>"cli_show","chunk"=>"0","sid"=>"1","input"=>"show version","output_format"=>"json"]]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($res && $http_code == 200) {
        $response = json_decode($res, true);
        $hostname = '';
        if (isset($response['ins_api']['outputs']['output']['body']['host_name'])) {
            $hostname = $response['ins_api']['outputs']['output']['body']['host_name'];
        }
        echo json_encode(["success" => true, "hostname" => $hostname]);
    } else {
        echo json_encode(["success" => false, "message" => $error ?: "Connection failed"]);
    }
    
} elseif ($action === 'execute_command') {
    // Execute a specific command
    $cmd = isset($input['command']) ? $input['command'] : 'show version';
    $type = isset($input['type']) ? $input['type'] : 'cli_show';
    $body = json_encode(["ins_api"=>["version"=>"1.0","type"=>$type,"chunk"=>"0","sid"=>"1","input"=>$cmd,"output_format"=>"json"]]);
    
    $ch = curl_init();
    $protocol = $use_https ? 'https' : 'http';
    curl_setopt($ch, CURLOPT_URL, "$protocol://$nexus_ip:$port/ins");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $res = curl_exec($ch);
    
    // Log response
    file_put_contents('nxapi_debug.log', "RESPONSE: $res\n\n", FILE_APPEND);
    
    if (!$res) {
        echo json_encode(["success" => false, "error" => curl_error($ch)]);
    } else {
        echo json_encode(["success" => true, "result" => json_decode($res, true)]);
    }
    curl_close($ch);
    
} else {
    // Default command execution (backward compatibility)
    $cmd = isset($input['cmd']) ? $input['cmd'] : 'show interface brief';
    $type = isset($input['type']) ? $input['type'] : 'cli_show';
    $body = json_encode(["ins_api"=>["version"=>"1.0","type"=>$type,"chunk"=>"0","sid"=>"1","input"=>$cmd,"output_format"=>"json"]]);
    
    $ch = curl_init();
    $protocol = $use_https ? 'https' : 'http';
    curl_setopt($ch, CURLOPT_URL, "$protocol://$nexus_ip:$port/ins");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $res = curl_exec($ch);
    
    // Log response
    file_put_contents('nxapi_debug.log', "RESPONSE: $res\n\n", FILE_APPEND);
    
    if (!$res) {
        echo json_encode(["error" => curl_error($ch)]);
    } else {
        echo $res;
    }
    curl_close($ch);
}

exit;
}

