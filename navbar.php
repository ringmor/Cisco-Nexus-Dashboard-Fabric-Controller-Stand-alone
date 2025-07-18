<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="styles.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
<div class="container-fluid">
<a class="navbar-brand" href="index.php"><i class="fas fa-network-wired"></i> Nexus Dashboard</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav me-auto">
<!-- Interface Management -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-ethernet"></i> Interfaces
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="interfaces.php">Interface Status</a></li>
<li><a class="dropdown-item" href="interface_config.php">Interface Config</a></li>
<li><a class="dropdown-item" href="interface_counters.php">Counters</a></li>
<li><a class="dropdown-item" href="port_channels.php">Port Channels</a></li>
</ul>
</li>

<!-- VLAN Management -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-sitemap"></i> VLANs
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="vlans.php">VLAN Status</a></li>

<li><a class="dropdown-item" href="svi.php">SVI Management</a></li>
<li><a class="dropdown-item" href="vtp.php">VTP Config</a></li>
</ul>
</li>

<!-- Routing -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-route"></i> Routing
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="routing.php">Route Table</a></li>
<li><a class="dropdown-item" href="static_routes.php">Static Routes</a></li>
<li><a class="dropdown-item" href="ospf.php">OSPF</a></li>
<li><a class="dropdown-item" href="bgp.php">BGP</a></li>
<li><a class="dropdown-item" href="hsrp.php">HSRP/VRRP</a></li>
<li><a class="dropdown-item" href="nat.php">NAT Configuration</a></li>
</ul>
</li>

<!-- Security -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-shield-alt"></i> Security
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="acl.php">Access Lists</a></li>
<li><a class="dropdown-item" href="port_security.php">Port Security</a></li>
<li><a class="dropdown-item" href="aaa.php">AAA Config</a></li>
<li><a class="dropdown-item" href="dhcp_snooping.php">DHCP Snooping</a></li>
<li><a class="dropdown-item" href="snmp.php">SNMP Configuration</a></li>
<li><a class="dropdown-item" href="ntp.php">NTP Configuration</a></li>
</ul>
</li>

<!-- QoS & Advanced -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-cogs"></i> Advanced
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="qos.php">QoS Policies</a></li>
<li><a class="dropdown-item" href="spanning_tree.php">Spanning Tree</a></li>
<li><a class="dropdown-item" href="multicast.php">Multicast</a></li>
<li><a class="dropdown-item" href="vpc.php">VPC</a></li>
</ul>
</li>

<!-- Monitoring -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-chart-line"></i> Monitoring
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="system.php">System Info</a></li>
<li><a class="dropdown-item" href="cpu_memory.php">CPU/Memory</a></li>
<li><a class="dropdown-item" href="environment.php">Environment</a></li>
<li><a class="dropdown-item" href="logs.php">Logs</a></li>
<li><a class="dropdown-item" href="alerts.php">Alerts</a></li>
</ul>
</li>

<!-- Tools -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
<i class="fas fa-tools"></i> Tools
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="config_management.php"><i class="fas fa-cogs me-2"></i>Configuration Management</a></li>
<li><a class="dropdown-item" href="#">Config CLI</a></li>
<li><a class="dropdown-item" href="backup.php">Backup/Restore</a></li>
<li><a class="dropdown-item" href="troubleshooting.php">Troubleshooting</a></li>
<li><a class="dropdown-item" href="firmware.php">Firmware</a></li>
<li><a class="dropdown-item" href="scheduled_tasks.php">Scheduled Tasks</a></li>
</ul>
</li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item d-flex align-items-center">
                <a class="nav-link btn btn-outline-light btn-sm me-3" href="settings.php" style="border-radius: 20px; padding: 8px 16px; font-weight: 500;">
                    <i class="fas fa-cog me-1"></i> Settings
                </a>
            </li>
            <li class="nav-item d-flex align-items-center">
                <span class="navbar-text" id="connection-status">
                    <i class="fas fa-circle text-secondary"></i> <span id="status-text">Checking...</span>
                </span>
            </li>
        </ul>
</div>
</div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Connection status management
function updateConnectionStatus(status, message) {
    const statusElement = document.getElementById('connection-status');
    const statusText = document.getElementById('status-text');
    const icon = statusElement.querySelector('i');
    
    // Remove all status classes
    icon.classList.remove('text-success', 'text-danger', 'text-warning', 'text-secondary');
    
    switch(status) {
        case 'connected':
            icon.classList.add('text-success');
            statusText.textContent = 'Connected';
            break;
        case 'disconnected':
            icon.classList.add('text-danger');
            statusText.textContent = 'Disconnected';
            break;
        case 'connecting':
            icon.classList.add('text-warning');
            statusText.textContent = 'Connecting...';
            break;
        case 'error':
            icon.classList.add('text-danger');
            statusText.textContent = 'Error';
            break;
        default:
            icon.classList.add('text-secondary');
            statusText.textContent = message || 'Unknown';
    }
}

function testConnectionStatus() {
    // Get settings from localStorage
    const settings = JSON.parse(localStorage.getItem('nexusSettings') || '{}');
    
    if (!settings.switchIP) {
        updateConnectionStatus('error', 'No IP configured');
        return;
    }
    
    updateConnectionStatus('connecting');
    
    // Test connection using the same endpoint as settings page
    fetch('nxapi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'test_connection',
            switchIP: settings.switchIP,
            switchPort: settings.switchPort,
            username: settings.username,
            password: settings.password,
            useHTTPS: settings.useHTTPS
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateConnectionStatus('connected');
        } else {
            updateConnectionStatus('disconnected');
        }
    })
    .catch(error => {
        console.error('Connection test failed:', error);
        updateConnectionStatus('error', 'Network Error');
    });
}

// Test connection on page load
document.addEventListener('DOMContentLoaded', function() {
    testConnectionStatus();
    
    // Test connection every 30 seconds
    setInterval(testConnectionStatus, 30000);
});

// Test connection when settings are saved
window.addEventListener('settingsSaved', function() {
    setTimeout(testConnectionStatus, 1000); // Test after 1 second
});
</script>

