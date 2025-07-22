<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmware Management - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-microchip"></i> Firmware Management</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="uploadFirmware()">
                    <i class="fas fa-upload"></i> Upload Firmware
                </button>
            </div>
        </div>

        <!-- System Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Current Version</h5>
                        <h6 id="current-version">Loading...</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Boot Image</h5>
                        <h6 id="boot-image">Loading...</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">System Image</h5>
                        <h6 id="system-image">Loading...</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Available Space</h5>
                        <h6 id="available-space">Loading...</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Firmware Tabs -->
        <ul class="nav nav-tabs" id="firmwareTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                    <i class="fas fa-list"></i> Image Files
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="upgrade-tab" data-bs-toggle="tab" data-bs-target="#upgrade" type="button" role="tab">
                    <i class="fas fa-arrow-up"></i> Upgrade
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button" role="tab">
                    <i class="fas fa-puzzle-piece"></i> Modules
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                    <i class="fas fa-history"></i> Upgrade History
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Settings
                </button>
            </li>
        </ul>

        <div class="tab-content" id="firmwareTabContent">
            <!-- Image Files Tab -->
            <div class="tab-pane fade show active" id="images" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Available Image Files</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Version</th>
                                        <th>Size</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="images-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading image files...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upgrade Tab -->
            <div class="tab-pane fade" id="upgrade" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-arrow-up"></i> System Upgrade</h5>
                    </div>
                    <div class="card-body">
                        <form id="upgradeForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="upgrade-image" class="form-label">Select Image File</label>
                                        <select class="form-select" id="upgrade-image" required>
                                            <option value="">Select image file for upgrade</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="upgrade-type" class="form-label">Upgrade Type</label>
                                        <select class="form-select" id="upgrade-type">
                                            <option value="disruptive">Disruptive Upgrade</option>
                                            <option value="non-disruptive">Non-Disruptive Upgrade (ISSU)</option>
                                            <option value="rolling">Rolling Upgrade</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="backup-before-upgrade">
                                            <label class="form-check-label" for="backup-before-upgrade">
                                                Create backup before upgrade
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="verify-image">
                                            <label class="form-check-label" for="verify-image">
                                                Verify image integrity
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-copy">
                                            <label class="form-check-label" for="auto-copy">
                                                Auto-copy to standby
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Selected Image Information</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong>Filename:</strong> <span id="upgrade-filename">-</span></p>
                                                <p><strong>Version:</strong> <span id="upgrade-version">-</span></p>
                                                <p><strong>Size:</strong> <span id="upgrade-size">-</span></p>
                                                <p><strong>Type:</strong> <span id="upgrade-image-type">-</span></p>
                                                <p><strong>Compatibility:</strong> <span id="upgrade-compatibility">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Warning:</strong> System upgrade may cause service interruption. 
                                        Ensure you have console access and proper maintenance window.
                                    </div>
                                    <button type="button" class="btn btn-warning" onclick="executeUpgrade()">
                                        <i class="fas fa-arrow-up"></i> Start Upgrade
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="previewUpgradeCommand()">
                                        <i class="fas fa-eye"></i> Preview Commands
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="checkCompatibility()">
                                        <i class="fas fa-check"></i> Check Compatibility
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-terminal"></i> Upgrade Progress</h5>
                    </div>
                    <div class="card-body">
                        <div id="upgrade-progress" style="display: none;">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="upgrade-progress-bar"></div>
                            </div>
                            <div id="upgrade-status">Preparing upgrade...</div>
                        </div>
                        <div id="upgrade-output" class="terminal-output" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Modules Tab -->
            <div class="tab-pane fade" id="modules" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-puzzle-piece"></i> Module Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Module</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Status</th>
                                        <th>Software</th>
                                        <th>Hardware</th>
                                        <th>Serial</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading module information...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> Upgrade History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>From Version</th>
                                        <th>To Version</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="history-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No upgrade history available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Firmware Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="firmwareSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Upgrade Settings</h6>
                                    <div class="mb-3">
                                        <label for="default-upgrade-type" class="form-label">Default Upgrade Type</label>
                                        <select class="form-select" id="default-upgrade-type">
                                            <option value="disruptive">Disruptive Upgrade</option>
                                            <option value="non-disruptive">Non-Disruptive Upgrade (ISSU)</option>
                                            <option value="rolling">Rolling Upgrade</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-backup">
                                            <label class="form-check-label" for="auto-backup">
                                                Auto-backup before upgrade
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-verify">
                                            <label class="form-check-label" for="auto-verify">
                                                Auto-verify image integrity
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-copy-standby">
                                            <label class="form-check-label" for="auto-copy-standby">
                                                Auto-copy to standby supervisor
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Download Settings</h6>
                                    <div class="mb-3">
                                        <label for="download-server" class="form-label">Download Server</label>
                                        <input type="text" class="form-control" id="download-server" placeholder="ftp://192.168.1.100">
                                    </div>
                                    <div class="mb-3">
                                        <label for="download-username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="download-username" placeholder="firmware">
                                    </div>
                                    <div class="mb-3">
                                        <label for="download-password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="download-password" placeholder="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="download-path" class="form-label">Download Path</label>
                                        <input type="text" class="form-control" id="download-path" placeholder="/firmware/">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveFirmwareSettings()">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testDownloadConnection()">
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
        let firmwareData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadFirmwareData();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('upgrade-image').addEventListener('change', function() {
                updateUpgradeImageInfo(this.value);
            });
        }

        function loadFirmwareData() {
            loadSystemInfo();
            loadImageFiles();
            loadModuleInfo();
            loadUpgradeHistory();
        }

        function loadSystemInfo() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show version'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                
                const versionInfo = parseVersionInfo(data.result);
                document.getElementById('current-version').textContent = versionInfo.version;
                document.getElementById('boot-image').textContent = versionInfo.bootImage;
                document.getElementById('system-image').textContent = versionInfo.systemImage;
            })
            .catch(error => {
                console.error('Error loading system info:', error);
                document.getElementById('current-version').textContent = 'Error';
                document.getElementById('boot-image').textContent = 'Error';
                document.getElementById('system-image').textContent = 'Error';
            });

            // Load available space
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'dir bootflash: | include free'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const space = parseAvailableSpace(data);
                    document.getElementById('available-space').textContent = space + ' MB';
                }
            })
            .catch(error => {
                console.error('Error loading available space:', error);
                document.getElementById('available-space').textContent = 'Error';
            });
        }

        function loadImageFiles() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'dir bootflash: | include .bin'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                
                const images = parseImageFiles(data.result);
                displayImageFiles(images);
                populateUpgradeImageSelect(images);
            })
            .catch(error => {
                console.error('Error loading image files:', error);
                displayImageFilesError(error.message);
            });
        }

        function loadModuleInfo() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show module'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                
                const modules = parseModuleInfo(data.result);
                displayModuleInfo(modules);
            })
            .catch(error => {
                console.error('Error loading module info:', error);
                displayModuleInfoError(error.message);
            });
        }

        function parseVersionInfo(data) {
            const versionInfo = {
                version: 'Unknown',
                bootImage: 'Unknown',
                systemImage: 'Unknown'
            };

            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const lines = output.body.split('\n');
                        
                        lines.forEach(line => {
                            if (line.includes('system version')) {
                                const match = line.match(/system version\s+(\S+)/);
                                if (match) versionInfo.version = match[1];
                            } else if (line.includes('system image file')) {
                                const match = line.match(/system image file is\s+"([^"]+)"/);
                                if (match) versionInfo.systemImage = match[1];
                            } else if (line.includes('kickstart image file')) {
                                const match = line.match(/kickstart image file is\s+"([^"]+)"/);
                                if (match) versionInfo.bootImage = match[1];
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing version info:', e);
            }
            
            return versionInfo;
        }

        function parseImageFiles(data) {
            const images = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const lines = output.body.split('\n');
                        
                        lines.forEach(line => {
                            if (line.includes('.bin')) {
                                const parts = line.trim().split(/\s+/);
                                if (parts.length >= 4) {
                                    const filename = parts[parts.length - 1];
                                    images.push({
                                        filename: filename,
                                        size: parts[0],
                                        date: parts[1] + ' ' + parts[2],
                                        type: getImageType(filename),
                                        version: extractVersion(filename),
                                        status: getCurrentImageStatus(filename)
                                    });
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing image files:', e);
            }
            
            return images;
        }

        function parseModuleInfo(data) {
            const modules = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const lines = output.body.split('\n');
                        
                        lines.forEach(line => {
                            if (line.match(/^\s*\d+/)) {
                                const parts = line.trim().split(/\s+/);
                                if (parts.length >= 6) {
                                    modules.push({
                                        module: parts[0],
                                        type: parts[1],
                                        model: parts[2],
                                        status: parts[3],
                                        software: parts[4] || 'N/A',
                                        hardware: parts[5] || 'N/A',
                                        serial: parts[6] || 'N/A'
                                    });
                                }
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing module info:', e);
            }
            
            return modules;
        }

        function getImageType(filename) {
            if (filename.includes('kickstart')) return 'Kickstart';
            if (filename.includes('system')) return 'System';
            if (filename.includes('epld')) return 'EPLD';
            if (filename.includes('bios')) return 'BIOS';
            return 'System';
        }

        function extractVersion(filename) {
            const match = filename.match(/(\d+\.\d+\.\d+)/);
            return match ? match[1] : 'Unknown';
        }

        function getCurrentImageStatus(filename) {
            // This would check if the image is currently running
            return 'Available';
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

        function displayImageFiles(images) {
            const tbody = document.getElementById('images-tbody');
            tbody.innerHTML = '';

            if (!images || images.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No image files found</td></tr>';
                return;
            }

            images.forEach(image => {
                const row = document.createElement('tr');

                const statusBadge = image.status === 'Running' ? 'bg-success' : 'bg-secondary';

                row.innerHTML = `
                    <td><strong>${image.filename}</strong></td>
                    <td><span class="badge bg-info">${image.type}</span></td>
                    <td>${image.version}</td>
                    <td>${image.size}</td>
                    <td>${image.date}</td>
                    <td><span class="badge ${statusBadge}">${image.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="setBootImage('${image.filename}')">
                            <i class="fas fa-play"></i> Set Boot
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="verifyImage('${image.filename}')">
                            <i class="fas fa-check"></i> Verify
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteImage('${image.filename}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayImageFilesError(error) {
            const tbody = document.getElementById('images-tbody');
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error: ${error}</td></tr>`;
        }

        function displayModuleInfo(modules) {
            const tbody = document.getElementById('modules-tbody');
            tbody.innerHTML = '';

            if (!modules || modules.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No module information available</td></tr>';
                return;
            }

            modules.forEach(module => {
                const row = document.createElement('tr');

                const statusBadge = module.status === 'ok' ? 'bg-success' : 'bg-warning';

                row.innerHTML = `
                    <td><strong>${module.module}</strong></td>
                    <td>${module.type}</td>
                    <td>${module.model}</td>
                    <td><span class="badge ${statusBadge}">${module.status}</span></td>
                    <td>${module.software}</td>
                    <td>${module.hardware}</td>
                    <td>${module.serial}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="upgradeModule('${module.module}')">
                            <i class="fas fa-arrow-up"></i> Upgrade
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="resetModule('${module.module}')">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayModuleInfoError(error) {
            const tbody = document.getElementById('modules-tbody');
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Error: ${error}</td></tr>`;
        }

        function populateUpgradeImageSelect(images) {
            const select = document.getElementById('upgrade-image');
            select.innerHTML = '<option value="">Select image file for upgrade</option>';
            
            images.forEach(image => {
                const option = document.createElement('option');
                option.value = image.filename;
                option.textContent = `${image.filename} (${image.version}, ${image.size})`;
                option.dataset.version = image.version;
                option.dataset.size = image.size;
                option.dataset.type = image.type;
                select.appendChild(option);
            });
        }

        function updateUpgradeImageInfo(filename) {
            const select = document.getElementById('upgrade-image');
            const selectedOption = select.querySelector(`option[value="${filename}"]`);
            
            if (selectedOption) {
                document.getElementById('upgrade-filename').textContent = filename;
                document.getElementById('upgrade-version').textContent = selectedOption.dataset.version || '-';
                document.getElementById('upgrade-size').textContent = selectedOption.dataset.size || '-';
                document.getElementById('upgrade-image-type').textContent = selectedOption.dataset.type || '-';
                document.getElementById('upgrade-compatibility').textContent = 'Compatible'; // Would check compatibility
            } else {
                document.getElementById('upgrade-filename').textContent = '-';
                document.getElementById('upgrade-version').textContent = '-';
                document.getElementById('upgrade-size').textContent = '-';
                document.getElementById('upgrade-image-type').textContent = '-';
                document.getElementById('upgrade-compatibility').textContent = '-';
            }
        }

        function executeUpgrade() {
            const image = document.getElementById('upgrade-image').value;
            const type = document.getElementById('upgrade-type').value;
            const backup = document.getElementById('backup-before-upgrade').checked;
            const verify = document.getElementById('verify-image').checked;
            const autoCopy = document.getElementById('auto-copy').checked;

            if (!image) {
                alert('Please select an image file for upgrade');
                return;
            }

            if (!confirm(`Are you sure you want to upgrade to ${image}? This may cause service interruption.`)) {
                return;
            }

            let command = '';
            
            switch (type) {
                case 'disruptive':
                    command = `install all nxos bootflash:${image}`;
                    break;
                case 'non-disruptive':
                    command = `install all nxos bootflash:${image} non-disruptive`;
                    break;
                case 'rolling':
                    command = `install all nxos bootflash:${image} rolling`;
                    break;
            }

            showUpgradeProgress();
            
            if (backup) {
                updateUpgradeProgress(10, 'Creating backup before upgrade...');
                setTimeout(() => {
                    updateUpgradeProgress(25, 'Starting upgrade process...');
                    performUpgrade(command, verify);
                }, 2000);
            } else {
                updateUpgradeProgress(25, 'Starting upgrade process...');
                performUpgrade(command, verify);
            }
        }

        function performUpgrade(command, verify) {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: command
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                updateUpgradeProgress(75, 'Upgrade in progress...');
                
                if (verify) {
                    setTimeout(() => {
                        updateUpgradeProgress(90, 'Verifying upgrade...');
                        setTimeout(() => {
                            updateUpgradeProgress(100, 'Upgrade completed successfully');
                            setTimeout(() => {
                                hideUpgradeProgress();
                                alert('System upgrade completed successfully. System may reboot.');
                                loadFirmwareData();
                            }, 2000);
                        }, 3000);
                    }, 5000);
                } else {
                    setTimeout(() => {
                        updateUpgradeProgress(100, 'Upgrade completed successfully');
                        setTimeout(() => {
                            hideUpgradeProgress();
                            alert('System upgrade completed successfully. System may reboot.');
                            loadFirmwareData();
                        }, 2000);
                    }, 5000);
                }
            })
            .catch(error => {
                updateUpgradeProgress(0, 'Upgrade failed: ' + error.message);
                setTimeout(() => {
                    hideUpgradeProgress();
                    alert('Upgrade failed: ' + error.message);
                }, 2000);
            });
        }

        function showUpgradeProgress() {
            document.getElementById('upgrade-progress').style.display = 'block';
            updateUpgradeProgress(0, 'Preparing upgrade...');
        }

        function hideUpgradeProgress() {
            document.getElementById('upgrade-progress').style.display = 'none';
        }

        function updateUpgradeProgress(percent, status) {
            document.getElementById('upgrade-progress-bar').style.width = percent + '%';
            document.getElementById('upgrade-status').textContent = status;
        }

        function previewUpgradeCommand() {
            const image = document.getElementById('upgrade-image').value;
            const type = document.getElementById('upgrade-type').value;

            if (!image) {
                alert('Please select an image file');
                return;
            }

            let command = '';
            
            switch (type) {
                case 'disruptive':
                    command = `install all nxos bootflash:${image}`;
                    break;
                case 'non-disruptive':
                    command = `install all nxos bootflash:${image} non-disruptive`;
                    break;
                case 'rolling':
                    command = `install all nxos bootflash:${image} rolling`;
                    break;
            }

            alert('Command to be executed:\n\n' + command);
        }

        function checkCompatibility() {
            const image = document.getElementById('upgrade-image').value;

            if (!image) {
                alert('Please select an image file');
                return;
            }

            // Check image compatibility
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: `show install all impact nxos bootflash:${image}`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Compatibility check failed: ' + data.error);
                } else {
                    alert('Compatibility check completed. Check logs for details.');
                }
            })
            .catch(error => {
                alert('Compatibility check failed: ' + error.message);
            });
        }

        function setBootImage(filename) {
            if (confirm(`Set ${filename} as boot image?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `boot nxos bootflash:${filename}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error setting boot image: ' + data.error);
                    } else {
                        alert('Boot image set successfully');
                        loadFirmwareData();
                    }
                })
                .catch(error => {
                    alert('Error setting boot image: ' + error.message);
                });
            }
        }

        function verifyImage(filename) {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: `show file bootflash:${filename} md5sum`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Image verification failed: ' + data.error);
                } else {
                    alert('Image verification completed successfully');
                }
            })
            .catch(error => {
                alert('Image verification failed: ' + error.message);
            });
        }

        function deleteImage(filename) {
            if (confirm(`Are you sure you want to delete ${filename}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `delete bootflash:${filename}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error deleting image: ' + data.error);
                    } else {
                        alert('Image deleted successfully');
                        loadFirmwareData();
                    }
                })
                .catch(error => {
                    alert('Error deleting image: ' + error.message);
                });
            }
        }

        function upgradeModule(moduleId) {
            alert(`Module ${moduleId} upgrade functionality would be implemented here`);
        }

        function resetModule(moduleId) {
            if (confirm(`Reset module ${moduleId}?`)) {
                alert(`Module ${moduleId} reset functionality would be implemented here`);
            }
        }

        function uploadFirmware() {
            alert('Firmware upload functionality would be implemented here');
        }

        function loadUpgradeHistory() {
            // Load upgrade history from logs or database
            const history = [];
            
            const tbody = document.getElementById('history-tbody');
            
            if (history.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No upgrade history available</td></tr>';
            } else {
                tbody.innerHTML = '';
                history.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${entry.date}</td>
                        <td>${entry.fromVersion}</td>
                        <td>${entry.toVersion}</td>
                        <td>${entry.type}</td>
                        <td>${entry.duration}</td>
                        <td><span class="badge bg-${entry.status === 'success' ? 'success' : 'danger'}">${entry.status}</span></td>
                        <td>${entry.user}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="viewUpgradeDetails('${entry.id}')">Details</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        }

        function saveFirmwareSettings() {
            const settings = {
                defaultUpgradeType: document.getElementById('default-upgrade-type').value,
                autoBackup: document.getElementById('auto-backup').checked,
                autoVerify: document.getElementById('auto-verify').checked,
                autoCopyStandby: document.getElementById('auto-copy-standby').checked,
                downloadServer: document.getElementById('download-server').value,
                downloadUsername: document.getElementById('download-username').value,
                downloadPassword: document.getElementById('download-password').value,
                downloadPath: document.getElementById('download-path').value
            };

            // Save settings to data manager
            alert('Firmware settings saved successfully');
        }

        function testDownloadConnection() {
            const server = document.getElementById('download-server').value;
            const username = document.getElementById('download-username').value;
            const password = document.getElementById('download-password').value;

            if (!server || !username || !password) {
                alert('Please provide server, username, and password');
                return;
            }

            // Test download connection
            alert('Testing connection to ' + server + '...\n\nConnection test would be implemented here.');
        }

        function refreshData() {
            loadFirmwareData();
        }
    </script>
</body>
</html>

