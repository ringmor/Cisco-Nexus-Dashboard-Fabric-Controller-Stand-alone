// Nexus Dashboard Common JavaScript Functions

// Global variables
let refreshInterval;
let connectionStatus = true;

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    checkConnectionStatus();
    setupAutoRefresh();
});

// Initialize Bootstrap tooltips
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Check connection status to Nexus device
function checkConnectionStatus() {
    fetch('nxapi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cmd=show version'
    })
    .then(response => response.json())
    .then(data => {
        updateConnectionStatus(true);
    })
    .catch(error => {
        updateConnectionStatus(false);
        console.error('Connection error:', error);
    });
}

// Update connection status indicator
function updateConnectionStatus(isConnected) {
    const statusElement = document.getElementById('connection-status');
    if (statusElement) {
        if (isConnected) {
            statusElement.innerHTML = '<i class="fas fa-circle text-success"></i> Connected';
            connectionStatus = true;
        } else {
            statusElement.innerHTML = '<i class="fas fa-circle text-danger"></i> Disconnected';
            connectionStatus = false;
        }
    }
}

// Setup auto-refresh functionality
function setupAutoRefresh() {
    const refreshSelect = document.getElementById('refresh-interval');
    if (refreshSelect) {
        refreshSelect.addEventListener('change', function() {
            const interval = parseInt(this.value);
            setRefreshInterval(interval);
        });
    }
}

// Set refresh interval
function setRefreshInterval(seconds) {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    
    if (seconds > 0) {
        refreshInterval = setInterval(function() {
            if (typeof refreshData === 'function') {
                refreshData();
            }
        }, seconds * 1000);
    }
}

// Execute NX-API command
function executeCommand(command, callback, type = null) {
    showLoading(true);
    let body = 'cmd=' + encodeURIComponent(command);
    if (type) {
        body += '&type=' + encodeURIComponent(type);
    }
    fetch('nxapi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        if (callback) callback(data);
    })
    .catch(error => {
        showLoading(false);
        showAlert('Error executing command: ' + error.message, 'danger');
        console.error('Command error:', error);
    });
}

// Show/hide loading indicator
function showLoading(show) {
    const loadingElements = document.querySelectorAll('.loading-spinner');
    loadingElements.forEach(element => {
        element.style.display = show ? 'inline-block' : 'none';
    });
}

// Show alert message
function showAlert(message, type = 'info', duration = 5000) {
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-custom`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    if (duration > 0) {
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, duration);
    }
}

// Create alert container if it doesn't exist
function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    container.style.maxWidth = '400px';
    document.body.appendChild(container);
    return container;
}

// Format bytes to human readable
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Format interface status
function formatInterfaceStatus(status) {
    const statusLower = status.toLowerCase();
    if (statusLower.includes('up')) {
        return '<span class="status-up">UP</span>';
    } else if (statusLower.includes('down') && statusLower.includes('admin')) {
        return '<span class="status-admin-down">ADMIN DOWN</span>';
    } else {
        return '<span class="status-down">DOWN</span>';
    }
}

// Format VLAN status
function formatVlanStatus(status) {
    const statusLower = status.toLowerCase();
    if (statusLower === 'active') {
        return '<span class="vlan-tag vlan-active">ACTIVE</span>';
    } else {
        return '<span class="vlan-tag vlan-inactive">INACTIVE</span>';
    }
}

// Format interface name with icon
function formatInterfaceName(interfaceName) {
    let icon = 'fas fa-ethernet';
    let className = 'port-ethernet';
    
    if (interfaceName.toLowerCase().includes('mgmt')) {
        icon = 'fas fa-cog';
        className = 'port-mgmt';
    } else if (interfaceName.toLowerCase().includes('loopback')) {
        icon = 'fas fa-circle';
        className = 'port-loopback';
    }
    
    return `<span class="interface-port ${className}">
                <i class="${icon}"></i> ${interfaceName}
            </span>`;
}

// Validate IP address
function validateIP(ip) {
    const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return ipRegex.test(ip);
}

// Validate VLAN ID
function validateVlanId(vlanId) {
    const id = parseInt(vlanId);
    return id >= 1 && id <= 4094;
}
// Export configuration to file
function exportConfig(config, filename) {
    const blob = new Blob([config], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}

// Data persistence functions
function saveData(action, data) {
    return fetch('data_manager.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            ...data
        })
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Error saving data:', error);
        return { success: false, error: error.message };
    });
}

function loadData(action, data = {}) {
    return fetch('data_manager.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            ...data
        })
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Error loading data:', error);
        return { success: false, error: error.message };
    });
}

// Save interface configuration
function saveInterfaceConfig(interfaceName, config) {
    return saveData('save_interface', {
        interface: interfaceName,
        config: config
    });
}

// Load interface configuration
function loadInterfaceConfig(interfaceName = null) {
    return loadData('load_interface', interfaceName ? { interface: interfaceName } : {});
}

// Save user data
function saveUserData(username, userData) {
    return saveData('save_user', {
        username: username,
        userData: userData
    });
}

// Load users
function loadUsers() {
    return loadData('load_users');
}

// Delete user
function deleteUserData(username) {
    return saveData('delete_user', { username: username });
}

// Save VLAN configuration
function saveVlanConfig(vlanId, config) {
    return saveData('save_vlan', {
        vlanId: vlanId,
        config: config
    });
}

// Save static route
function saveStaticRouteData(routeId, routeData) {
    return saveData('save_static_route', {
        routeId: routeId,
        routeData: routeData
    });
}

// Save QoS configuration
function saveQosConfig(type, name, config) {
    return saveData('save_qos', {
        type: type,
        name: name,
        config: config
    });
}

// Load system logs
function loadSystemLogs(limit = 100, level = null, module = null) {
    return loadData('load_logs', {
        limit: limit,
        level: level,
        module: module
    });
}

// Save monitoring data
function saveMonitoringData(type, data) {
    return saveData('save_monitoring', {
        type: type,
        data: data
    });
}

// Load monitoring data
function loadMonitoringData(type = null, limit = 100) {
    return loadData('load_monitoring', {
        type: type,
        limit: limit
    });
}

// Confirm action
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Format uptime
function formatUptime(seconds) {
    const days = Math.floor(seconds / 86400);
    const hours = Math.floor((seconds % 86400) / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    
    let result = '';
    if (days > 0) result += days + 'd ';
    if (hours > 0) result += hours + 'h ';
    if (minutes > 0) result += minutes + 'm';
    
    return result || '0m';
}

// Create progress bar
function createProgressBar(percentage, label = '') {
    let colorClass = 'bg-success';
    if (percentage > 70) colorClass = 'bg-warning';
    if (percentage > 90) colorClass = 'bg-danger';
    
    return `
        <div class="progress progress-custom mb-2">
            <div class="progress-bar progress-bar-custom ${colorClass}" 
                 style="width: ${percentage}%" 
                 role="progressbar" 
                 aria-valuenow="${percentage}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                ${percentage}%
            </div>
        </div>
        ${label ? `<small class="text-muted">${label}</small>` : ''}
    `;
}

// Initialize data tables
function initializeDataTable(tableId, options = {}) {
    const defaultOptions = {
        pageLength: 25,
        responsive: true,
        order: [[0, 'asc']],
        language: {
            search: "Filter:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    };
    
    const finalOptions = Object.assign(defaultOptions, options);
    
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        return $('#' + tableId).DataTable(finalOptions);
    }
}

// Refresh page data
function refreshData() {
    if (typeof window.pageRefreshFunction === 'function') {
        window.pageRefreshFunction();
    } else {
        location.reload();
    }
}

