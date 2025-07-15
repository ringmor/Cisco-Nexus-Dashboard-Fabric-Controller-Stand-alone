<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Nexus Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-cog text-primary"></i> Dashboard Settings</h2>
                    <button class="btn btn-success" onclick="saveSettings()">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>

                <!-- Settings Tabs -->
                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="switch-tab" data-bs-toggle="tab" data-bs-target="#switch" type="button" role="tab">
                            <i class="fas fa-network-wired"></i> Switch Connection
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#api" type="button" role="tab">
                            <i class="fas fa-plug"></i> API Settings
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" role="tab">
                            <i class="fas fa-download"></i> Backup/Restore
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="settingsTabContent">
                    <!-- Switch Connection Tab -->
                    <div class="tab-pane fade show active" id="switch" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="fas fa-network-wired"></i> Nexus Switch Connection Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="switchIP" class="form-label">Switch Management IP Address</label>
                                            <input type="text" class="form-control" id="switchIP" placeholder="10.10.100.80" value="10.10.100.80">
                                            <div class="form-text">IP address of the Nexus switch management interface</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="switchPort" class="form-label">Management Port</label>
                                            <input type="number" class="form-control" id="switchPort" placeholder="443" value="443">
                                            <div class="form-text">HTTPS port for NX-API (default: 443)</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" placeholder="admin" value="admin">
                                            <div class="form-text">Username for switch authentication</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="admin" value="admin">
                                            <div class="form-text">Password for switch authentication</div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="useHTTPS" checked>
                                                <label class="form-check-label" for="useHTTPS">
                                                    Use HTTPS (Recommended)
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="verifySSL">
                                                <label class="form-check-label" for="verifySSL">
                                                    Verify SSL Certificate
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h6><i class="fas fa-info-circle"></i> Connection Status</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="connectionStatus">
                                                    <div class="text-muted">
                                                        <i class="fas fa-circle text-secondary"></i> Not tested
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-sm mt-2" onclick="testConnection()">
                                                    <i class="fas fa-plug"></i> Test Connection
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card bg-light mt-3">
                                            <div class="card-header">
                                                <h6><i class="fas fa-lightbulb"></i> Quick Setup</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="small">Common Nexus switch configurations:</p>
                                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2" onclick="setDefaults('nexus9k')">Nexus 9000</button>
                                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2" onclick="setDefaults('nexus7k')">Nexus 7000</button>
                                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2" onclick="setDefaults('nexus5k')">Nexus 5000</button>
                                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2" onclick="setDefaults('nexus3k')">Nexus 3000</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- API Settings Tab -->
                    <div class="tab-pane fade" id="api" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="fas fa-plug"></i> NX-API Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="apiTimeout" class="form-label">API Timeout (seconds)</label>
                                            <input type="number" class="form-control" id="apiTimeout" value="30" min="5" max="300">
                                            <div class="form-text">Timeout for API requests (5-300 seconds)</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="maxRetries" class="form-label">Max Retries</label>
                                            <input type="number" class="form-control" id="maxRetries" value="3" min="0" max="10">
                                            <div class="form-text">Number of retry attempts for failed requests</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="refreshInterval" class="form-label">Auto-refresh Interval (seconds)</label>
                                            <input type="number" class="form-control" id="refreshInterval" value="10" min="5" max="300">
                                            <div class="form-text">Default auto-refresh interval for monitoring pages</div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableLogging" checked>
                                                <label class="form-check-label" for="enableLogging">
                                                    Enable API Logging
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableDebug">
                                                <label class="form-check-label" for="enableDebug">
                                                    Enable Debug Mode
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h6><i class="fas fa-terminal"></i> API Commands</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label for="testCommand" class="form-label">Test Command</label>
                                                    <input type="text" class="form-control" id="testCommand" value="show version" placeholder="show version">
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="testAPICommand()">
                                                    <i class="fas fa-play"></i> Execute Test
                                                </button>
                                                <div id="commandResult" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Settings Tab -->
                    <div class="tab-pane fade" id="dashboard" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="fas fa-tachometer-alt"></i> Dashboard Preferences</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="dashboardTheme" class="form-label">Theme</label>
                                            <select class="form-select" id="dashboardTheme">
                                                <option value="light">Light</option>
                                                <option value="dark">Dark</option>
                                                <option value="auto">Auto (System)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="defaultPage" class="form-label">Default Landing Page</label>
                                            <select class="form-select" id="defaultPage">
                                                <option value="interfaces.php">Interface Status</option>
                                                <option value="monitoring.php">System Monitoring</option>
                                                <option value="system.php">System Information</option>
                                                <option value="vlans.php">VLAN Management</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="itemsPerPage" class="form-label">Items Per Page</label>
                                            <select class="form-select" id="itemsPerPage">
                                                <option value="10">10</option>
                                                <option value="25" selected>25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="showTooltips" checked>
                                                <label class="form-check-label" for="showTooltips">
                                                    Show Tooltips
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="enableAnimations" checked>
                                                <label class="form-check-label" for="enableAnimations">
                                                    Enable Animations
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h6><i class="fas fa-bell"></i> Notifications</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="enableNotifications" checked>
                                                    <label class="form-check-label" for="enableNotifications">
                                                        Enable Browser Notifications
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="soundAlerts">
                                                    <label class="form-check-label" for="soundAlerts">
                                                        Sound Alerts
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="emailAlerts">
                                                    <label class="form-check-label" for="emailAlerts">
                                                        Email Alerts
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup/Restore Tab -->
                    <div class="tab-pane fade" id="backup" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="fas fa-download"></i> Settings Backup & Restore</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-download"></i> Export Settings</h6>
                                        <p class="text-muted">Download your current dashboard settings as a backup file.</p>
                                        <button class="btn btn-primary" onclick="exportSettings()">
                                            <i class="fas fa-download"></i> Export Settings
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-upload"></i> Import Settings</h6>
                                        <p class="text-muted">Restore settings from a previously exported backup file.</p>
                                        <input type="file" class="form-control mb-2" id="settingsFile" accept=".json">
                                        <button class="btn btn-success" onclick="importSettings()">
                                            <i class="fas fa-upload"></i> Import Settings
                                        </button>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <h6><i class="fas fa-undo"></i> Reset to Defaults</h6>
                                        <p class="text-muted">Reset all settings to their default values.</p>
                                        <button class="btn btn-warning" onclick="resetToDefaults()">
                                            <i class="fas fa-undo"></i> Reset All Settings
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="common.js"></script>
    <script>
        // Load settings on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
        });

        function loadSettings() {
            // Load settings from localStorage or server
            const settings = JSON.parse(localStorage.getItem('nexusSettings') || '{}');
            
            // Switch Connection Settings
            document.getElementById('switchIP').value = settings.switchIP || '10.10.100.80';
            document.getElementById('switchPort').value = settings.switchPort || '443';
            document.getElementById('username').value = settings.username || 'admin';
            document.getElementById('password').value = settings.password || 'admin';
            document.getElementById('useHTTPS').checked = settings.useHTTPS !== false;
            document.getElementById('verifySSL').checked = settings.verifySSL || false;
            
            // API Settings
            document.getElementById('apiTimeout').value = settings.apiTimeout || '30';
            document.getElementById('maxRetries').value = settings.maxRetries || '3';
            document.getElementById('refreshInterval').value = settings.refreshInterval || '10';
            document.getElementById('enableLogging').checked = settings.enableLogging !== false;
            document.getElementById('enableDebug').checked = settings.enableDebug || false;
            
            // Dashboard Settings
            document.getElementById('dashboardTheme').value = settings.dashboardTheme || 'light';
            document.getElementById('defaultPage').value = settings.defaultPage || 'interfaces.php';
            document.getElementById('itemsPerPage').value = settings.itemsPerPage || '25';
            document.getElementById('showTooltips').checked = settings.showTooltips !== false;
            document.getElementById('enableAnimations').checked = settings.enableAnimations !== false;
            document.getElementById('enableNotifications').checked = settings.enableNotifications !== false;
            document.getElementById('soundAlerts').checked = settings.soundAlerts || false;
            document.getElementById('emailAlerts').checked = settings.emailAlerts || false;
        }

        function saveSettings() {
            const settings = {
                // Switch Connection
                switchIP: document.getElementById('switchIP').value,
                switchPort: document.getElementById('switchPort').value,
                username: document.getElementById('username').value,
                password: document.getElementById('password').value,
                useHTTPS: document.getElementById('useHTTPS').checked,
                verifySSL: document.getElementById('verifySSL').checked,
                
                // API Settings
                apiTimeout: document.getElementById('apiTimeout').value,
                maxRetries: document.getElementById('maxRetries').value,
                refreshInterval: document.getElementById('refreshInterval').value,
                enableLogging: document.getElementById('enableLogging').checked,
                enableDebug: document.getElementById('enableDebug').checked,
                
                // Dashboard Settings
                dashboardTheme: document.getElementById('dashboardTheme').value,
                defaultPage: document.getElementById('defaultPage').value,
                itemsPerPage: document.getElementById('itemsPerPage').value,
                showTooltips: document.getElementById('showTooltips').checked,
                enableAnimations: document.getElementById('enableAnimations').checked,
                enableNotifications: document.getElementById('enableNotifications').checked,
                soundAlerts: document.getElementById('soundAlerts').checked,
                emailAlerts: document.getElementById('emailAlerts').checked,
                
                lastUpdated: new Date().toISOString()
            };

            // Save to localStorage
            localStorage.setItem('nexusSettings', JSON.stringify(settings));
            
            // Save to server
            fetch('data_manager.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'save_settings',
                    data: settings
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Settings saved successfully!', 'success');
                } else {
                    showAlert('Error saving settings: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error saving settings', 'danger');
            });
        }

        function testConnection() {
            const switchIP = document.getElementById('switchIP').value;
            const switchPort = document.getElementById('switchPort').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const useHTTPS = document.getElementById('useHTTPS').checked;

            document.getElementById('connectionStatus').innerHTML = 
                '<div class="text-info"><i class="fas fa-spinner fa-spin"></i> Testing connection...</div>';

            fetch('nxapi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'test_connection',
                    switchIP: switchIP,
                    switchPort: switchPort,
                    username: username,
                    password: password,
                    useHTTPS: useHTTPS
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('connectionStatus').innerHTML = 
                        '<div class="text-success"><i class="fas fa-check-circle"></i> Connection successful</div>' +
                        '<small class="text-muted">Switch: ' + (data.hostname || 'Unknown') + '</small>';
                } else {
                    document.getElementById('connectionStatus').innerHTML = 
                        '<div class="text-danger"><i class="fas fa-times-circle"></i> Connection failed</div>' +
                        '<small class="text-muted">' + (data.message || 'Unknown error') + '</small>';
                }
            })
            .catch(error => {
                document.getElementById('connectionStatus').innerHTML = 
                    '<div class="text-danger"><i class="fas fa-times-circle"></i> Connection failed</div>' +
                    '<small class="text-muted">Network error</small>';
            });
        }

        function testAPICommand() {
            const command = document.getElementById('testCommand').value;
            if (!command) {
                showAlert('Please enter a command to test', 'warning');
                return;
            }

            document.getElementById('commandResult').innerHTML = 
                '<div class="text-info"><i class="fas fa-spinner fa-spin"></i> Executing command...</div>';

            fetch('nxapi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: command
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('commandResult').innerHTML = 
                        '<div class="text-success"><i class="fas fa-check"></i> Command executed successfully</div>' +
                        '<pre class="bg-light p-2 mt-2 small">' + JSON.stringify(data.result, null, 2) + '</pre>';
                } else {
                    document.getElementById('commandResult').innerHTML = 
                        '<div class="text-danger"><i class="fas fa-times"></i> Command failed</div>' +
                        '<small class="text-muted">' + (data.message || 'Unknown error') + '</small>';
                }
            })
            .catch(error => {
                document.getElementById('commandResult').innerHTML = 
                    '<div class="text-danger"><i class="fas fa-times"></i> Command failed</div>' +
                    '<small class="text-muted">Network error</small>';
            });
        }

        function setDefaults(switchType) {
            const defaults = {
                nexus9k: { port: 443, https: true },
                nexus7k: { port: 443, https: true },
                nexus5k: { port: 443, https: true },
                nexus3k: { port: 443, https: true }
            };

            const config = defaults[switchType];
            if (config) {
                document.getElementById('switchPort').value = config.port;
                document.getElementById('useHTTPS').checked = config.https;
                showAlert('Default settings applied for ' + switchType.toUpperCase(), 'info');
            }
        }

        function exportSettings() {
            const settings = JSON.parse(localStorage.getItem('nexusSettings') || '{}');
            const dataStr = JSON.stringify(settings, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = 'nexus-dashboard-settings-' + new Date().toISOString().split('T')[0] + '.json';
            link.click();
        }

        function importSettings() {
            const fileInput = document.getElementById('settingsFile');
            const file = fileInput.files[0];
            
            if (!file) {
                showAlert('Please select a settings file to import', 'warning');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const settings = JSON.parse(e.target.result);
                    localStorage.setItem('nexusSettings', JSON.stringify(settings));
                    loadSettings();
                    showAlert('Settings imported successfully!', 'success');
                } catch (error) {
                    showAlert('Invalid settings file format', 'danger');
                }
            };
            reader.readAsText(file);
        }

        function resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to defaults? This cannot be undone.')) {
                localStorage.removeItem('nexusSettings');
                loadSettings();
                showAlert('Settings reset to defaults', 'info');
            }
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>

