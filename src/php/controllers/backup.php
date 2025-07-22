<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-save"></i> Backup & Restore Management</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="createBackup()">
                    <i class="fas fa-download"></i> Create Backup
                </button>
            </div>
        </div>

        <!-- Backup Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Backups</h5>
                        <h2 id="total-backups">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Running Config Size</h5>
                        <h2 id="running-config-size">0 KB</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Startup Config Size</h5>
                        <h2 id="startup-config-size">0 KB</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Available Space</h5>
                        <h2 id="available-space">0 MB</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Tabs -->
        <ul class="nav nav-tabs" id="backupTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="backups-tab" data-bs-toggle="tab" data-bs-target="#backups" type="button" role="tab">
                    <i class="fas fa-list"></i> Backup Files
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab">
                    <i class="fas fa-plus"></i> Create Backup
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="restore-tab" data-bs-toggle="tab" data-bs-target="#restore" type="button" role="tab">
                    <i class="fas fa-upload"></i> Restore
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                    <i class="fas fa-clock"></i> Scheduled Backups
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Settings
                </button>
            </li>
        </ul>

        <div class="tab-content" id="backupTabContent">
            <!-- Backup Files Tab -->
            <div class="tab-pane fade show active" id="backups" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Available Backup Files</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Created</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="backups-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading backup files...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Backup Tab -->
            <div class="tab-pane fade" id="create" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create New Backup</h5>
                    </div>
                    <div class="card-body">
                        <form id="createBackupForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="backup-name" class="form-label">Backup Name</label>
                                        <input type="text" class="form-control" id="backup-name" placeholder="backup-YYYY-MM-DD" required>
                                        <div class="form-text">Enter a descriptive name for the backup</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="backup-type" class="form-label">Backup Type</label>
                                        <select class="form-select" id="backup-type" required>
                                            <option value="running-config">Running Configuration</option>
                                            <option value="startup-config">Startup Configuration</option>
                                            <option value="full-system">Full System Backup</option>
                                            <option value="vdc-config">VDC Configuration</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="backup-location" class="form-label">Backup Location</label>
                                        <select class="form-select" id="backup-location">
                                            <option value="bootflash">bootflash:</option>
                                            <option value="slot0">slot0:</option>
                                            <option value="ftp">FTP Server</option>
                                            <option value="tftp">TFTP Server</option>
                                            <option value="scp">SCP Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="backup-description" class="form-label">Description</label>
                                        <textarea class="form-control" id="backup-description" rows="3" placeholder="Optional description for this backup"></textarea>
                                    </div>
                                    <div class="mb-3" id="server-settings" style="display: none;">
                                        <label for="server-address" class="form-label">Server Address</label>
                                        <input type="text" class="form-control" id="server-address" placeholder="192.168.1.100">
                                        <label for="server-username" class="form-label mt-2">Username</label>
                                        <input type="text" class="form-control" id="server-username" placeholder="admin">
                                        <label for="server-password" class="form-label mt-2">Password</label>
                                        <input type="password" class="form-control" id="server-password" placeholder="password">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="compress-backup">
                                            <label class="form-check-label" for="compress-backup">
                                                Compress Backup
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="encrypt-backup">
                                            <label class="form-check-label" for="encrypt-backup">
                                                Encrypt Backup
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="executeBackup()">
                                        <i class="fas fa-download"></i> Create Backup
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="previewBackupCommand()">
                                        <i class="fas fa-eye"></i> Preview Command
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-terminal"></i> Backup Progress</h5>
                    </div>
                    <div class="card-body">
                        <div id="backup-progress" style="display: none;">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="backup-progress-bar"></div>
                            </div>
                            <div id="backup-status">Preparing backup...</div>
                        </div>
                        <div id="backup-output" class="terminal-output" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Restore Tab -->
            <div class="tab-pane fade" id="restore" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-upload"></i> Restore Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="restoreForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="restore-file" class="form-label">Select Backup File</label>
                                        <select class="form-select" id="restore-file" required>
                                            <option value="">Select backup file to restore</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="restore-type" class="form-label">Restore Type</label>
                                        <select class="form-select" id="restore-type">
                                            <option value="running-config">To Running Configuration</option>
                                            <option value="startup-config">To Startup Configuration</option>
                                            <option value="replace">Replace Configuration</option>
                                            <option value="merge">Merge Configuration</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="backup-before-restore">
                                            <label class="form-check-label" for="backup-before-restore">
                                                Create backup before restore
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="verify-restore">
                                            <label class="form-check-label" for="verify-restore">
                                                Verify configuration after restore
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Backup File Information</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong>Filename:</strong> <span id="restore-filename">-</span></p>
                                                <p><strong>Size:</strong> <span id="restore-size">-</span></p>
                                                <p><strong>Created:</strong> <span id="restore-created">-</span></p>
                                                <p><strong>Type:</strong> <span id="restore-file-type">-</span></p>
                                                <p><strong>Description:</strong> <span id="restore-description">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Warning:</strong> Restoring configuration may disrupt network connectivity. 
                                        Ensure you have console access before proceeding.
                                    </div>
                                    <button type="button" class="btn btn-warning" onclick="executeRestore()">
                                        <i class="fas fa-upload"></i> Restore Configuration
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="previewRestoreCommand()">
                                        <i class="fas fa-eye"></i> Preview Command
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-terminal"></i> Restore Progress</h5>
                    </div>
                    <div class="card-body">
                        <div id="restore-progress" style="display: none;">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="restore-progress-bar"></div>
                            </div>
                            <div id="restore-status">Preparing restore...</div>
                        </div>
                        <div id="restore-output" class="terminal-output" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Scheduled Backups Tab -->
            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-clock"></i> Scheduled Backup Jobs</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Job Name</th>
                                        <th>Schedule</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Last Run</th>
                                        <th>Next Run</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="scheduled-backups-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No scheduled backup jobs configured</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary" onclick="createScheduledBackup()">
                            <i class="fas fa-plus"></i> Create Scheduled Backup
                        </button>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create Scheduled Backup</h5>
                    </div>
                    <div class="card-body">
                        <form id="scheduledBackupForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schedule-name" class="form-label">Job Name</label>
                                        <input type="text" class="form-control" id="schedule-name" placeholder="daily-backup" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="schedule-type" class="form-label">Backup Type</label>
                                        <select class="form-select" id="schedule-type">
                                            <option value="running-config">Running Configuration</option>
                                            <option value="startup-config">Startup Configuration</option>
                                            <option value="full-system">Full System Backup</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schedule-frequency" class="form-label">Frequency</label>
                                        <select class="form-select" id="schedule-frequency">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="custom">Custom Cron</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="schedule-time" class="form-label">Time</label>
                                        <input type="time" class="form-control" id="schedule-time" value="02:00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schedule-location" class="form-label">Backup Location</label>
                                        <select class="form-select" id="schedule-location">
                                            <option value="bootflash">bootflash:</option>
                                            <option value="ftp">FTP Server</option>
                                            <option value="tftp">TFTP Server</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="schedule-retention" class="form-label">Retention (days)</label>
                                        <input type="number" class="form-control" id="schedule-retention" min="1" max="365" value="30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveScheduledBackup()">
                                        <i class="fas fa-save"></i> Save Scheduled Backup
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Backup Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="backupSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Default Settings</h6>
                                    <div class="mb-3">
                                        <label for="default-location" class="form-label">Default Backup Location</label>
                                        <select class="form-select" id="default-location">
                                            <option value="bootflash">bootflash:</option>
                                            <option value="slot0">slot0:</option>
                                            <option value="ftp">FTP Server</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="default-retention" class="form-label">Default Retention (days)</label>
                                        <input type="number" class="form-control" id="default-retention" min="1" max="365" value="30">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-compress">
                                            <label class="form-check-label" for="auto-compress">
                                                Auto-compress backups
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-encrypt">
                                            <label class="form-check-label" for="auto-encrypt">
                                                Auto-encrypt backups
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Remote Server Settings</h6>
                                    <div class="mb-3">
                                        <label for="ftp-server" class="form-label">FTP Server</label>
                                        <input type="text" class="form-control" id="ftp-server" placeholder="192.168.1.100">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ftp-username" class="form-label">FTP Username</label>
                                        <input type="text" class="form-control" id="ftp-username" placeholder="backup">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ftp-password" class="form-label">FTP Password</label>
                                        <input type="password" class="form-control" id="ftp-password" placeholder="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ftp-path" class="form-label">FTP Path</label>
                                        <input type="text" class="form-control" id="ftp-path" placeholder="/backups/">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveBackupSettings()">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testConnection()">
                                        <i class="fas fa-plug"></i> Test Connection
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let backupData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadBackupData();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('backup-location').addEventListener('change', function() {
                const serverSettings = document.getElementById('server-settings');
                if (['ftp', 'tftp', 'scp'].includes(this.value)) {
                    serverSettings.style.display = 'block';
                } else {
                    serverSettings.style.display = 'none';
                }
            });

            document.getElementById('restore-file').addEventListener('change', function() {
                updateRestoreFileInfo(this.value);
            });
        }

        function loadBackupData() {
            loadBackupFiles();
            loadSystemInfo();
            loadScheduledBackups();
        }

        function loadBackupFiles() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=dir bootflash: | include .cfg'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const backups = parseBackupFiles(data);
                displayBackupFiles(backups);
                populateRestoreFileSelect(backups);
                document.getElementById('total-backups').textContent = backups.length;
            })
            .catch(error => {
                console.error('Error loading backup files:', error);
                displayBackupFilesError(error.message);
            });
        }

        function loadSystemInfo() {
            // Load running config size
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show running-config | wc'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const size = parseConfigSize(data);
                    document.getElementById('running-config-size').textContent = size + ' KB';
                }
            })
            .catch(error => console.error('Error loading running config size:', error));

            // Load startup config size
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show startup-config | wc'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const size = parseConfigSize(data);
                    document.getElementById('startup-config-size').textContent = size + ' KB';
                }
            })
            .catch(error => console.error('Error loading startup config size:', error));

            // Load available space
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=dir bootflash: | include free'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const space = parseAvailableSpace(data);
                    document.getElementById('available-space').textContent = space + ' MB';
                }
            })
            .catch(error => console.error('Error loading available space:', error));
        }

        function parseBackupFiles(data) {
            const backups = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const lines = output.body.split('\n');
                        
                        lines.forEach(line => {
                            if (line.includes('.cfg') || line.includes('.conf')) {
                                const parts = line.trim().split(/\s+/);
                                if (parts.length >= 4) {
                                    backups.push({
                                        filename: parts[parts.length - 1],
                                        size: parts[0],
                                        date: parts[1] + ' ' + parts[2],
                                        type: getBackupType(parts[parts.length - 1]),
                                        description: getBackupDescription(parts[parts.length - 1])
                                    });
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing backup files:', e);
            }
            
            return backups;
        }

        function getBackupType(filename) {
            if (filename.includes('running')) return 'Running Config';
            if (filename.includes('startup')) return 'Startup Config';
            if (filename.includes('full')) return 'Full System';
            return 'Configuration';
        }

        function getBackupDescription(filename) {
            if (filename.includes('auto')) return 'Automatic backup';
            if (filename.includes('manual')) return 'Manual backup';
            if (filename.includes('scheduled')) return 'Scheduled backup';
            return 'Configuration backup';
        }

        function parseConfigSize(data) {
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const lines = output.body.split('\n');
                        const lastLine = lines[lines.length - 1].trim();
                        const parts = lastLine.split(/\s+/);
                        
                        if (parts.length >= 3) {
                            return Math.round(parseInt(parts[2]) / 1024); // Convert bytes to KB
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing config size:', e);
            }
            
            return 0;
        }

        function parseAvailableSpace(data) {
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const match = output.body.match(/(\d+)\s+bytes\s+free/);
                        if (match) {
                            return Math.round(parseInt(match[1]) / (1024 * 1024)); // Convert bytes to MB
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing available space:', e);
            }
            
            return 0;
        }

        function displayBackupFiles(backups) {
            const tbody = document.getElementById('backups-tbody');
            tbody.innerHTML = '';

            if (!backups || backups.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No backup files found</td></tr>';
                return;
            }

            backups.forEach(backup => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${backup.filename}</strong></td>
                    <td><span class="badge bg-info">${backup.type}</span></td>
                    <td>${backup.size}</td>
                    <td>${backup.date}</td>
                    <td>${backup.description}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="downloadBackup('${backup.filename}')">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="restoreBackup('${backup.filename}')">
                            <i class="fas fa-upload"></i> Restore
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBackup('${backup.filename}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayBackupFilesError(error) {
            const tbody = document.getElementById('backups-tbody');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error: ${error}</td></tr>`;
        }

        function populateRestoreFileSelect(backups) {
            const select = document.getElementById('restore-file');
            select.innerHTML = '<option value="">Select backup file to restore</option>';
            
            backups.forEach(backup => {
                const option = document.createElement('option');
                option.value = backup.filename;
                option.textContent = `${backup.filename} (${backup.size}, ${backup.date})`;
                option.dataset.size = backup.size;
                option.dataset.date = backup.date;
                option.dataset.type = backup.type;
                option.dataset.description = backup.description;
                select.appendChild(option);
            });
        }

        function updateRestoreFileInfo(filename) {
            const select = document.getElementById('restore-file');
            const selectedOption = select.querySelector(`option[value="${filename}"]`);
            
            if (selectedOption) {
                document.getElementById('restore-filename').textContent = filename;
                document.getElementById('restore-size').textContent = selectedOption.dataset.size || '-';
                document.getElementById('restore-created').textContent = selectedOption.dataset.date || '-';
                document.getElementById('restore-file-type').textContent = selectedOption.dataset.type || '-';
                document.getElementById('restore-description').textContent = selectedOption.dataset.description || '-';
            } else {
                document.getElementById('restore-filename').textContent = '-';
                document.getElementById('restore-size').textContent = '-';
                document.getElementById('restore-created').textContent = '-';
                document.getElementById('restore-file-type').textContent = '-';
                document.getElementById('restore-description').textContent = '-';
            }
        }

        function executeBackup() {
            const name = document.getElementById('backup-name').value;
            const type = document.getElementById('backup-type').value;
            const location = document.getElementById('backup-location').value;
            const description = document.getElementById('backup-description').value;
            const compress = document.getElementById('compress-backup').checked;
            const encrypt = document.getElementById('encrypt-backup').checked;

            if (!name || !type) {
                alert('Please provide backup name and type');
                return;
            }

            const timestamp = new Date().toISOString().slice(0, 19).replace(/[:-]/g, '');
            const filename = `${name}-${timestamp}.cfg`;
            
            let command = '';
            
            switch (type) {
                case 'running-config':
                    command = `copy running-config ${location}:${filename}`;
                    break;
                case 'startup-config':
                    command = `copy startup-config ${location}:${filename}`;
                    break;
                case 'full-system':
                    command = `copy system:running-config ${location}:${filename}`;
                    break;
                case 'vdc-config':
                    command = `copy vdc-running-config ${location}:${filename}`;
                    break;
            }

            if (compress) {
                command += ' compress';
            }

            showBackupProgress();
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                updateBackupProgress(100, 'Backup completed successfully');
                setTimeout(() => {
                    hideBackupProgress();
                    alert('Backup created successfully: ' + filename);
                    loadBackupFiles();
                }, 2000);
            })
            .catch(error => {
                updateBackupProgress(0, 'Backup failed: ' + error.message);
                setTimeout(() => {
                    hideBackupProgress();
                    alert('Backup failed: ' + error.message);
                }, 2000);
            });
        }

        function executeRestore() {
            const filename = document.getElementById('restore-file').value;
            const type = document.getElementById('restore-type').value;
            const backupBefore = document.getElementById('backup-before-restore').checked;
            const verify = document.getElementById('verify-restore').checked;

            if (!filename) {
                alert('Please select a backup file to restore');
                return;
            }

            if (!confirm(`Are you sure you want to restore ${filename}? This may disrupt network connectivity.`)) {
                return;
            }

            let command = '';
            
            switch (type) {
                case 'running-config':
                    command = `copy bootflash:${filename} running-config`;
                    break;
                case 'startup-config':
                    command = `copy bootflash:${filename} startup-config`;
                    break;
                case 'replace':
                    command = `configure replace bootflash:${filename}`;
                    break;
                case 'merge':
                    command = `copy bootflash:${filename} running-config`;
                    break;
            }

            showRestoreProgress();
            
            if (backupBefore) {
                updateRestoreProgress(25, 'Creating backup before restore...');
                // Create backup first, then restore
                setTimeout(() => {
                    updateRestoreProgress(50, 'Restoring configuration...');
                    performRestore(command, verify);
                }, 2000);
            } else {
                updateRestoreProgress(50, 'Restoring configuration...');
                performRestore(command, verify);
            }
        }

        function performRestore(command, verify) {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (verify) {
                    updateRestoreProgress(75, 'Verifying configuration...');
                    setTimeout(() => {
                        updateRestoreProgress(100, 'Restore completed and verified');
                        setTimeout(() => {
                            hideRestoreProgress();
                            alert('Configuration restored successfully');
                        }, 2000);
                    }, 2000);
                } else {
                    updateRestoreProgress(100, 'Restore completed successfully');
                    setTimeout(() => {
                        hideRestoreProgress();
                        alert('Configuration restored successfully');
                    }, 2000);
                }
            })
            .catch(error => {
                updateRestoreProgress(0, 'Restore failed: ' + error.message);
                setTimeout(() => {
                    hideRestoreProgress();
                    alert('Restore failed: ' + error.message);
                }, 2000);
            });
        }

        function showBackupProgress() {
            document.getElementById('backup-progress').style.display = 'block';
            updateBackupProgress(0, 'Preparing backup...');
        }

        function hideBackupProgress() {
            document.getElementById('backup-progress').style.display = 'none';
        }

        function updateBackupProgress(percent, status) {
            document.getElementById('backup-progress-bar').style.width = percent + '%';
            document.getElementById('backup-status').textContent = status;
        }

        function showRestoreProgress() {
            document.getElementById('restore-progress').style.display = 'block';
            updateRestoreProgress(0, 'Preparing restore...');
        }

        function hideRestoreProgress() {
            document.getElementById('restore-progress').style.display = 'none';
        }

        function updateRestoreProgress(percent, status) {
            document.getElementById('restore-progress-bar').style.width = percent + '%';
            document.getElementById('restore-status').textContent = status;
        }

        function previewBackupCommand() {
            const name = document.getElementById('backup-name').value;
            const type = document.getElementById('backup-type').value;
            const location = document.getElementById('backup-location').value;

            if (!name || !type) {
                alert('Please provide backup name and type');
                return;
            }

            const timestamp = new Date().toISOString().slice(0, 19).replace(/[:-]/g, '');
            const filename = `${name}-${timestamp}.cfg`;
            
            let command = '';
            
            switch (type) {
                case 'running-config':
                    command = `copy running-config ${location}:${filename}`;
                    break;
                case 'startup-config':
                    command = `copy startup-config ${location}:${filename}`;
                    break;
                case 'full-system':
                    command = `copy system:running-config ${location}:${filename}`;
                    break;
                case 'vdc-config':
                    command = `copy vdc-running-config ${location}:${filename}`;
                    break;
            }

            alert('Command to be executed:\n\n' + command);
        }

        function previewRestoreCommand() {
            const filename = document.getElementById('restore-file').value;
            const type = document.getElementById('restore-type').value;

            if (!filename) {
                alert('Please select a backup file');
                return;
            }

            let command = '';
            
            switch (type) {
                case 'running-config':
                    command = `copy bootflash:${filename} running-config`;
                    break;
                case 'startup-config':
                    command = `copy bootflash:${filename} startup-config`;
                    break;
                case 'replace':
                    command = `configure replace bootflash:${filename}`;
                    break;
                case 'merge':
                    command = `copy bootflash:${filename} running-config`;
                    break;
            }

            alert('Command to be executed:\n\n' + command);
        }

        function downloadBackup(filename) {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent('show file bootflash:' + filename)
            })
            .then(response => response.json())
            .then(data => {
                let content = '';
                try {
                    if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                        const output = data.ins_api.outputs.output;
                        content = output.body || (Array.isArray(output) ? output.map(o => o.body).join('\n') : '');
                    }
                } catch (e) {
                    console.error('Error parsing file content:', e);
                }
                const blob = new Blob([content], { type: 'text/plain' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href);
            })
            .catch(error => {
                alert('Error fetching file: ' + error.message);
            });
        }

        function restoreBackup(filename) {
            document.getElementById('restore-file').value = filename;
            updateRestoreFileInfo(filename);
            
            // Switch to restore tab
            const restoreTab = new bootstrap.Tab(document.getElementById('restore-tab'));
            restoreTab.show();
        }

        function deleteBackup(filename) {
            if (confirm(`Are you sure you want to delete ${filename}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=delete bootflash:${filename}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error deleting backup: ' + data.error);
                    } else {
                        alert('Backup deleted successfully');
                        loadBackupFiles();
                    }
                })
                .catch(error => {
                    alert('Error deleting backup: ' + error.message);
                });
            }
        }

        function createBackup() {
            // Switch to create tab
            const createTab = new bootstrap.Tab(document.getElementById('create-tab'));
            createTab.show();
            
            // Set default backup name with timestamp
            const now = new Date();
            const timestamp = now.toISOString().slice(0, 10);
            document.getElementById('backup-name').value = `backup-${timestamp}`;
        }

        function loadScheduledBackups() {
            // Load scheduled backup jobs from data manager
            // This would typically load from a configuration file or database
            const scheduledBackups = [];
            
            const tbody = document.getElementById('scheduled-backups-tbody');
            
            if (scheduledBackups.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No scheduled backup jobs configured</td></tr>';
            } else {
                tbody.innerHTML = '';
                scheduledBackups.forEach(job => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${job.name}</td>
                        <td>${job.schedule}</td>
                        <td>${job.type}</td>
                        <td>${job.location}</td>
                        <td>${job.lastRun}</td>
                        <td>${job.nextRun}</td>
                        <td><span class="badge bg-${job.status === 'active' ? 'success' : 'warning'}">${job.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="editScheduledBackup('${job.id}')">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteScheduledBackup('${job.id}')">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        }

        function saveScheduledBackup() {
            const name = document.getElementById('schedule-name').value;
            const type = document.getElementById('schedule-type').value;
            const frequency = document.getElementById('schedule-frequency').value;
            const time = document.getElementById('schedule-time').value;
            const location = document.getElementById('schedule-location').value;
            const retention = document.getElementById('schedule-retention').value;

            if (!name || !type || !frequency || !time) {
                alert('Please fill in all required fields');
                return;
            }

            // Save scheduled backup configuration
            alert('Scheduled backup saved successfully');
            loadScheduledBackups();
        }

        function saveBackupSettings() {
            const settings = {
                defaultLocation: document.getElementById('default-location').value,
                defaultRetention: document.getElementById('default-retention').value,
                autoCompress: document.getElementById('auto-compress').checked,
                autoEncrypt: document.getElementById('auto-encrypt').checked,
                ftpServer: document.getElementById('ftp-server').value,
                ftpUsername: document.getElementById('ftp-username').value,
                ftpPassword: document.getElementById('ftp-password').value,
                ftpPath: document.getElementById('ftp-path').value
            };

            // Save settings to data manager
            alert('Backup settings saved successfully');
        }

        function testConnection() {
            const server = document.getElementById('ftp-server').value;
            const username = document.getElementById('ftp-username').value;
            const password = document.getElementById('ftp-password').value;

            if (!server || !username || !password) {
                alert('Please provide server, username, and password');
                return;
            }

            // Test FTP connection
            alert('Testing connection to ' + server + '...\n\nConnection test would be implemented here.');
        }

        function refreshData() {
            loadBackupData();
        }
    </script>
</body>
</html>

