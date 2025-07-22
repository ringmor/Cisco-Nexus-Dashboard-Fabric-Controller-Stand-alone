<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Management - Nexus Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include 'navbar.php'; ?>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-cogs"></i> Configuration Management</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-success" onclick="showCreateCheckpointModal()">
                    <i class="fas fa-save"></i> Create Checkpoint
                </button>
                <button class="btn btn-primary" onclick="backupConfig()">
                    <i class="fas fa-download"></i> Backup Config
                </button>
                <button class="btn btn-nexus btn-secondary" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Running Config</h5>
                    <p class="card-text" id="runningConfigStatus">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Startup Config</h5>
                    <p class="card-text" id="startupConfigStatus">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Checkpoints</h5>
                    <p class="card-text" id="checkpointCount">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Last Backup</h5>
                    <p class="card-text" id="lastBackupTime">Loading...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-tools"></i> Configuration Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Backup & Restore</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="viewRunningConfig()">
                                    <i class="fas fa-eye"></i> View Running Config
                                </button>
                                <button class="btn btn-outline-success" onclick="viewStartupConfig()">
                                    <i class="fas fa-eye"></i> View Startup Config
                                </button>
                                <button class="btn btn-outline-warning" onclick="saveRunningToStartup()">
                                    <i class="fas fa-save"></i> Save Running to Startup
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Configuration Comparison</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-info" onclick="compareConfigs()">
                                    <i class="fas fa-exchange-alt"></i> Compare Running vs Startup
                                </button>
                                <button class="btn btn-outline-secondary" onclick="showCheckpointModal()">
                                    <i class="fas fa-history"></i> Manage Checkpoints
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-file-code"></i> Configuration Content</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="downloadConfig()">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <pre id="configContent" class="bg-light p-3" style="max-height: 600px; overflow-y: auto; font-size: 12px;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Checkpoint Modal -->
<div class="modal fade" id="checkpointModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Configuration Checkpoint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="checkpointName" class="form-label">Checkpoint Name</label>
                    <input type="text" class="form-control" id="checkpointName" placeholder="Enter checkpoint name">
                </div>
                <div class="mb-3">
                    <label for="checkpointDescription" class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="checkpointDescription" rows="3" placeholder="Enter description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createCheckpointConfirm()">Create Checkpoint</button>
            </div>
        </div>
    </div>
</div>

<!-- Checkpoint Management Modal -->
<div class="modal fade" id="checkpointManagementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkpoint Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="checkpointTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="checkpointTableBody">
                        <!-- Checkpoints will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/common.js"></script>
<script>
let currentConfigType = 'running';
let currentConfigData = '';

// Initial load
window.addEventListener('DOMContentLoaded', function() {
    loadConfigStatus();
    loadCheckpoints();
});

function loadConfigStatus() {
    executeCommand('show running-config | head 20', function(data) {
        document.getElementById('runningConfigStatus').textContent = data && data.success ? 'Available' : 'Error';
    });
    executeCommand('show startup-config | head 20', function(data) {
        document.getElementById('startupConfigStatus').textContent = data && data.success ? 'Available' : 'Error';
    });
    const lastBackup = localStorage.getItem('lastConfigBackup');
    document.getElementById('lastBackupTime').textContent = lastBackup ? new Date(lastBackup).toLocaleString() : 'Never';
}

function loadCheckpoints() {
    executeCommand('show checkpoint summary', function(data) {
        let count = 0;
        if (data && data.success && data.output) {
            count = parseCheckpoints(data.output).length;
        }
        document.getElementById('checkpointCount').textContent = count + ' available';
    });
}

function parseCheckpoints(output) {
    const lines = output.split('\n');
    const checkpoints = [];
    for (let line of lines) {
        if (line.trim() && !line.toLowerCase().includes('checkpoint')) {
            const parts = line.split(/\s+/);
            if (parts.length >= 1) {
                checkpoints.push({
                    name: parts[0],
                    description: parts.slice(1).join(' ')
                });
            }
        }
    }
    return checkpoints;
}

function viewRunningConfig() {
    currentConfigType = 'running';
    showLoading(true);
    executeCommand('show running-config', function(data) {
        showLoading(false);
        if (data && data.success) {
            currentConfigData = data.output;
            document.getElementById('configContent').textContent = data.output;
            showAlert('Running configuration loaded successfully', 'success');
        } else {
            showAlert('Failed to load running configuration', 'danger');
        }
    });
}

function viewStartupConfig() {
    currentConfigType = 'startup';
    showLoading(true);
    executeCommand('show startup-config', function(data) {
        showLoading(false);
        if (data && data.success) {
            currentConfigData = data.output;
            document.getElementById('configContent').textContent = data.output;
            showAlert('Startup configuration loaded successfully', 'success');
        } else {
            showAlert('Failed to load startup configuration', 'danger');
        }
    });
}

function saveRunningToStartup() {
    if (confirm('Are you sure you want to save the running configuration to startup configuration? This will overwrite the current startup config.')) {
        showLoading(true);
        executeCommand('copy running-config startup-config', function(data) {
            showLoading(false);
            if (data && data.success) {
                showAlert('Configuration saved to startup successfully', 'success');
                loadConfigStatus();
            } else {
                showAlert('Failed to save configuration to startup', 'danger');
            }
        }, 'cli_conf');
    }
}

function compareConfigs() {
    showLoading(true);
    Promise.all([
        new Promise(resolve => executeCommand('show running-config', resolve)),
        new Promise(resolve => executeCommand('show startup-config', resolve))
    ]).then(([runningData, startupData]) => {
        showLoading(false);
        if (runningData && runningData.success && startupData && startupData.success) {
            const diff = generateDiff(runningData.output, startupData.output);
            currentConfigData = diff;
            document.getElementById('configContent').textContent = diff;
            showAlert('Configuration comparison completed', 'success');
        } else {
            showAlert('Failed to load configurations for comparison', 'danger');
        }
    });
}

function generateDiff(running, startup) {
    const runningLines = running.split('\n');
    const startupLines = startup.split('\n');
    let diff = '=== CONFIGURATION DIFF (Running vs Startup) ===\n\n';
    const maxLines = Math.max(runningLines.length, startupLines.length);
    for (let i = 0; i < maxLines; i++) {
        const runningLine = runningLines[i] || '';
        const startupLine = startupLines[i] || '';
        if (runningLine !== startupLine) {
            diff += `Line ${i + 1}:\n- ${startupLine}\n+ ${runningLine}\n\n`;
        }
    }
    return diff;
}

function showCreateCheckpointModal() {
    document.getElementById('checkpointName').value = '';
    document.getElementById('checkpointDescription').value = '';
    new bootstrap.Modal(document.getElementById('checkpointModal')).show();
}

function createCheckpointConfirm() {
    const name = document.getElementById('checkpointName').value.trim();
    const description = document.getElementById('checkpointDescription').value.trim();
    if (!name) {
        showAlert('Please enter a checkpoint name', 'warning');
        return;
    }
    showLoading(true);
    let command = `checkpoint ${name}`;
    if (description) command += ` description ${description}`;
    executeCommand(command, function(data) {
        showLoading(false);
        if (data && data.success) {
            showAlert(`Checkpoint "${name}" created successfully`, 'success');
            loadCheckpoints();
            bootstrap.Modal.getInstance(document.getElementById('checkpointModal')).hide();
        } else {
            showAlert('Failed to create checkpoint', 'danger');
        }
    }, 'cli_conf');
}

function showCheckpointModal() {
    loadCheckpointTable();
    new bootstrap.Modal(document.getElementById('checkpointManagementModal')).show();
}

function loadCheckpointTable() {
    executeCommand('show checkpoint summary', function(data) {
        const tbody = document.getElementById('checkpointTableBody');
        tbody.innerHTML = '';
        if (data && data.success && data.output) {
            const checkpoints = parseCheckpoints(data.output);
            checkpoints.forEach(checkpoint => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${checkpoint.name}</td>
                    <td>${checkpoint.description}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewCheckpoint('${checkpoint.name}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="rollbackToCheckpoint('${checkpoint.name}')">
                            <i class="fas fa-undo"></i> Rollback
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCheckpoint('${checkpoint.name}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
    });
}

function viewCheckpoint(name) {
    showLoading(true);
    executeCommand(`show checkpoint ${name}`, function(data) {
        showLoading(false);
        if (data && data.success) {
            currentConfigData = data.output;
            document.getElementById('configContent').textContent = data.output;
            showAlert(`Checkpoint "${name}" loaded successfully`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('checkpointManagementModal')).hide();
        } else {
            showAlert('Failed to load checkpoint', 'danger');
        }
    });
}

function rollbackToCheckpoint(name) {
    if (confirm(`Are you sure you want to rollback to checkpoint "${name}"? This will overwrite the current running configuration.`)) {
        showLoading(true);
        executeCommand(`rollback running-config checkpoint ${name}`, function(data) {
            showLoading(false);
            if (data && data.success) {
                showAlert(`Successfully rolled back to checkpoint "${name}"`, 'success');
                loadConfigStatus();
            } else {
                showAlert('Failed to rollback to checkpoint', 'danger');
            }
        }, 'cli_conf');
    }
}

function deleteCheckpoint(name) {
    if (confirm(`Are you sure you want to delete checkpoint "${name}"?`)) {
        showLoading(true);
        executeCommand(`no checkpoint ${name}`, function(data) {
            showLoading(false);
            if (data && data.success) {
                showAlert(`Checkpoint "${name}" deleted successfully`, 'success');
                loadCheckpoints();
                loadCheckpointTable();
            } else {
                showAlert('Failed to delete checkpoint', 'danger');
            }
        }, 'cli_conf');
    }
}

function backupConfig() {
    if (!currentConfigData) {
        showAlert('No configuration data to backup. Please view a config first.', 'warning');
        return;
    }
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    const filename = `nexus-config-backup-${timestamp}.txt`;
    const blob = new Blob([currentConfigData], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    localStorage.setItem('lastConfigBackup', new Date().toISOString());
    loadConfigStatus();
    showAlert('Configuration backup downloaded successfully', 'success');
}

function copyToClipboard() {
    if (currentConfigData) {
        navigator.clipboard.writeText(currentConfigData).then(() => {
            showAlert('Configuration copied to clipboard', 'success');
        }).catch(() => {
            showAlert('Failed to copy to clipboard', 'danger');
        });
    } else {
        showAlert('No configuration data to copy', 'warning');
    }
}

function downloadConfig() {
    backupConfig();
}

function refreshData() {
    loadConfigStatus();
    loadCheckpoints();
    showAlert('Configuration data refreshed', 'info');
}
</script>

</body>
</html> 