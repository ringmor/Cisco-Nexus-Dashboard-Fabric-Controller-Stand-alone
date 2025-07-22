<?php
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTP Configuration - Cisco Nexus Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <style>
        .ntp-status-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .config-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .config-title {
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .status-badge {
            font-size: 0.8em;
            padding: 4px 8px;
        }
        .sync-status {
            font-size: 1.2em;
            font-weight: bold;
        }
        .ntp-stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-clock"></i> NTP Configuration</h2>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="showNTPStatus()">
                            <i class="fas fa-info-circle"></i> Show Status
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="showNTPPeers()">
                            <i class="fas fa-users"></i> Show Peers
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="showNTPStats()">
                            <i class="fas fa-chart-bar"></i> Show Statistics
                        </button>
                    </div>
                </div>

                <!-- NTP Status Card -->
                <div class="ntp-status-card">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5><i class="fas fa-toggle-on"></i> NTP Status</h5>
                            <p class="mb-0" id="ntp-status">Checking...</p>
                        </div>
                        <div class="col-md-4">
                            <h5><i class="fas fa-sync-alt"></i> Synchronization</h5>
                            <p class="mb-0 sync-status" id="ntp-sync-status">Unknown</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-light" onclick="toggleNTP()" id="ntp-toggle-btn">
                                <i class="fas fa-power-off"></i> Enable NTP
                            </button>
                        </div>
                    </div>
                </div>

                <!-- NTP Servers Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-server"></i> NTP Servers</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddNTPServer()">
                            <i class="fas fa-plus"></i> Add Server
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="ntp-servers-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Type</th>
                                    <th>Key ID</th>
                                    <th>Prefer</th>
                                    <th>VRF</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ntp-servers-tbody">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading NTP servers...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- NTP Peers Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-users"></i> NTP Peers</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddNTPPeer()">
                            <i class="fas fa-plus"></i> Add Peer
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="ntp-peers-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Key ID</th>
                                    <th>Prefer</th>
                                    <th>VRF</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ntp-peers-tbody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading NTP peers...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- NTP Authentication Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-key"></i> NTP Authentication</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddNTPKey()">
                            <i class="fas fa-plus"></i> Add Key
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-hover" id="ntp-keys-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Key ID</th>
                                            <th>Type</th>
                                            <th>Trusted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ntp-keys-tbody">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin"></i> Loading NTP keys...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Authentication Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="ntp-authenticate">
                                        <label class="form-check-label" for="ntp-authenticate">
                                            Enable NTP Authentication
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="saveAuthSettings()">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NTP Source Configuration -->
                <div class="config-section">
                    <h5 class="config-title"><i class="fas fa-network-wired"></i> NTP Source Configuration</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Source Interface</label>
                                <select class="form-select" id="ntp-source-interface">
                                    <option value="">Select Interface</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Source IP Address</label>
                                <input type="text" class="form-control" id="ntp-source-ip" placeholder="192.168.1.1">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveSourceConfig()">
                        <i class="fas fa-save"></i> Save Source Configuration
                    </button>
                </div>

                <!-- NTP Access Control -->
                <div class="config-section">
                    <h5 class="config-title"><i class="fas fa-shield-alt"></i> NTP Access Control</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Access Group Type</label>
                                <select class="form-select" id="ntp-access-type">
                                    <option value="">Select Type</option>
                                    <option value="peer">Peer</option>
                                    <option value="serve">Serve</option>
                                    <option value="serve-only">Serve Only</option>
                                    <option value="query-only">Query Only</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ACL Name</label>
                                <input type="text" class="form-control" id="ntp-acl-name" placeholder="NTP_ACL">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveAccessControl()">
                        <i class="fas fa-save"></i> Save Access Control
                    </button>
                </div>

                <!-- NTP Logging -->
                <div class="config-section">
                    <h5 class="config-title"><i class="fas fa-file-alt"></i> NTP Logging</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="ntp-logging">
                                <label class="form-check-label" for="ntp-logging">
                                    Enable NTP Logging
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" onclick="saveLoggingConfig()">
                                <i class="fas fa-save"></i> Save Logging Configuration
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit NTP Server Modal -->
    <div class="modal fade" id="ntpServerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ntpServerModalTitle">Add NTP Server</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ntpServerForm">
                        <input type="hidden" id="ntp-server-action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">IP Address *</label>
                            <input type="text" class="form-control" id="ntp-server-ip" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Key ID (Optional)</label>
                            <input type="number" class="form-control" id="ntp-server-key" min="1" max="4294967295">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ntp-server-prefer">
                                <label class="form-check-label" for="ntp-server-prefer">
                                    Prefer this server
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">VRF (Optional)</label>
                            <input type="text" class="form-control" id="ntp-server-vrf" placeholder="default">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNTPServer()">Save Server</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit NTP Peer Modal -->
    <div class="modal fade" id="ntpPeerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ntpPeerModalTitle">Add NTP Peer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ntpPeerForm">
                        <input type="hidden" id="ntp-peer-action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">IP Address *</label>
                            <input type="text" class="form-control" id="ntp-peer-ip" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Key ID (Optional)</label>
                            <input type="number" class="form-control" id="ntp-peer-key" min="1" max="4294967295">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ntp-peer-prefer">
                                <label class="form-check-label" for="ntp-peer-prefer">
                                    Prefer this peer
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">VRF (Optional)</label>
                            <input type="text" class="form-control" id="ntp-peer-vrf" placeholder="default">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNTPPeer()">Save Peer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit NTP Key Modal -->
    <div class="modal fade" id="ntpKeyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ntpKeyModalTitle">Add NTP Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ntpKeyForm">
                        <input type="hidden" id="ntp-key-action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Key ID *</label>
                            <input type="number" class="form-control" id="ntp-key-id" required min="1" max="4294967295">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Key String *</label>
                            <input type="password" class="form-control" id="ntp-key-string" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ntp-key-trusted">
                                <label class="form-check-label" for="ntp-key-trusted">
                                    Trusted Key
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNTPKey()">Save Key</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/common.js"></script>
    <script>
        let ntpServers = [];
        let ntpPeers = [];
        let ntpKeys = [];
        let interfaces = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadNTPStatus();
            loadNTPServers();
            loadNTPPeers();
            loadNTPKeys();
            loadInterfaces();
            loadAuthSettings();
            loadSourceConfig();
            loadAccessControl();
            loadLoggingConfig();
        });

        function loadNTPStatus() {
            executeCommand('show feature', function(data) {
                let isEnabled = false;
                let syncStatus = 'Unknown';

                // Try to get the output object
                let output = null;
                if (data && data.result && data.result.ins_api && data.result.ins_api.outputs && data.result.ins_api.outputs.output) {
                    output = data.result.ins_api.outputs.output;
                } else if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    output = data.ins_api.outputs.output;
                }

                // Check for clierror (unstructured output)
                if (output && output.clierror) {
                    const lines = output.clierror.split('\n');
                    lines.forEach(line => {
                        if (line.toLowerCase().includes('ntp')) {
                            if (line.toLowerCase().includes('enabled')) isEnabled = true;
                        }
                    });
                } else if (output && output.body && output.body.TABLE_feature) {
                    // Fallback to structured output if available
                    let features = output.body.TABLE_feature.ROW_feature;
                    if (!Array.isArray(features)) features = [features];
                    features.forEach(feature => {
                        if (feature.feature_name && feature.feature_name.toLowerCase() === 'ntp') {
                            isEnabled = (feature.state && feature.state.toLowerCase() === 'enabled');
                        }
                    });
                }

                document.getElementById('ntp-status').textContent = isEnabled ? 'NTP is Enabled' : 'NTP is Disabled';
                document.getElementById('ntp-sync-status').textContent = syncStatus; // Optionally update with a separate call
                document.getElementById('ntp-toggle-btn').innerHTML = isEnabled ? 
                    '<i class="fas fa-power-off"></i> Disable NTP' : 
                    '<i class="fas fa-power-off"></i> Enable NTP';
                document.getElementById('ntp-toggle-btn').className = isEnabled ? 
                    'btn btn-warning' : 'btn btn-light';
            });
        }

        function loadNTPServers() {
            executeCommand('show ntp peers', function(data) {
                ntpServers = parseNTPServers(data);
                populateNTPServersTable();
            });
        }

        function loadNTPPeers() {
            executeCommand('show ntp peer-status', function(data) {
                ntpPeers = parseNTPPeers(data);
                populateNTPPeersTable();
            });
        }

        function loadNTPKeys() {
            executeCommand('show running-config | include ntp authentication-key', function(data) {
                ntpKeys = parseNTPKeys(data);
                populateNTPKeysTable();
            });
        }

        function loadInterfaces() {
            executeCommand('show interface brief', function(data) {
                interfaces = parseInterfaces(data);
                populateInterfaceSelect();
            });
        }

        function parseNTPServers(data) {
            const servers = [];
            // Parse NTP servers from command output
            // This is a simplified parser
            return servers;
        }

        function parseNTPPeers(data) {
            const peers = [];
            // Parse NTP peers from 'clierror' output
            let output = null;
            if (data && data.result && data.result.ins_api && data.result.ins_api.outputs && data.result.ins_api.outputs.output) {
                output = data.result.ins_api.outputs.output;
            } else if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                output = data.ins_api.outputs.output;
            }
            if (output && output.clierror) {
                const lines = output.clierror.split('\n');
                // Find the header line (starts with 'remote')
                let headerIdx = lines.findIndex(line => line.trim().startsWith('remote'));
                if (headerIdx !== -1) {
                    for (let i = headerIdx + 2; i < lines.length; i++) { // skip header and separator
                        const line = lines[i].trim();
                        if (!line) continue;
                        // Example line: '=1.1.1.1                0.0.0.0                16   16       0   0.00000 default'
                        // Remove any leading mode character (=,*,+,-)
                        let peerLine = line.replace(/^[=*+\-]/, '').trim();
                        // Split by whitespace
                        const parts = peerLine.split(/\s+/);
                        if (parts.length >= 7) {
                            peers.push({
                                ip: parts[0],
                                local: parts[1],
                                st: parts[2],
                                poll: parts[3],
                                reach: parts[4],
                                delay: parts[5],
                                vrf: parts[6]
                            });
                        }
                    }
                }
            }
            return peers;
        }

        function parseNTPKeys(data) {
            const keys = [];
            // Parse NTP keys from command output
            // This is a simplified parser
            return keys;
        }

        function parseInterfaces(data) {
            const interfaceList = [];
            if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                const output = data.ins_api.outputs.output;
                if (output.body && output.body.TABLE_interface) {
                    let rows = output.body.TABLE_interface.ROW_interface || [];
                    if (!Array.isArray(rows)) rows = [rows];
                    
                    rows.forEach(row => {
                        if (row.interface && (row.interface.startsWith('Ethernet') || row.interface.startsWith('port-channel'))) {
                            interfaceList.push(row.interface);
                        }
                    });
                }
            }
            return interfaceList;
        }

        function populateNTPServersTable() {
            const tbody = document.getElementById('ntp-servers-tbody');
            
            if (ntpServers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No NTP servers configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = ntpServers.map((server, index) => `
                <tr>
                    <td>${server.ip}</td>
                    <td><span class="badge bg-info">Server</span></td>
                    <td>${server.keyId || '-'}</td>
                    <td><span class="badge ${server.prefer ? 'bg-success' : 'bg-secondary'}">${server.prefer ? 'Yes' : 'No'}</span></td>
                    <td>${server.vrf || 'default'}</td>
                    <td><span class="badge bg-success status-badge">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editNTPServer(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNTPServer(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateNTPPeersTable() {
            const tbody = document.getElementById('ntp-peers-tbody');
            
            if (ntpPeers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No NTP peers configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = ntpPeers.map((peer, index) => `
                <tr>
                    <td>${peer.ip}</td>
                    <td>${peer.keyId || '-'}</td>
                    <td><span class="badge ${peer.prefer ? 'bg-success' : 'bg-secondary'}">${peer.prefer ? 'Yes' : 'No'}</span></td>
                    <td>${peer.vrf || 'default'}</td>
                    <td><span class="badge bg-success status-badge">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editNTPPeer(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNTPPeer(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateNTPKeysTable() {
            const tbody = document.getElementById('ntp-keys-tbody');
            
            if (ntpKeys.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No NTP keys configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = ntpKeys.map((key, index) => `
                <tr>
                    <td>${key.id}</td>
                    <td><span class="badge bg-info">MD5</span></td>
                    <td><span class="badge ${key.trusted ? 'bg-success' : 'bg-secondary'}">${key.trusted ? 'Yes' : 'No'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editNTPKey(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNTPKey(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateInterfaceSelect() {
            const select = document.getElementById('ntp-source-interface');
            if (select) {
                select.innerHTML = '<option value="">Select Interface</option>';
                interfaces.forEach(intf => {
                    const option = document.createElement('option');
                    option.value = intf;
                    option.textContent = intf;
                    select.appendChild(option);
                });
            }
        }

        function toggleNTP() {
            const isEnabled = document.getElementById('ntp-status').textContent.includes('Enabled');
            const command = isEnabled ? 'no feature ntp' : 'feature ntp';
            
            const btn = document.getElementById('ntp-toggle-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            executeCommand(command, function(data) {
                showAlert(`NTP ${isEnabled ? 'disabled' : 'enabled'} successfully`, 'success');
                setTimeout(() => {
                    loadNTPStatus();
                    btn.disabled = false;
                }, 1000);
            }, 'cli_conf');
        }

        function showAddNTPServer() {
            document.getElementById('ntp-server-action').value = 'add';
            document.getElementById('ntpServerModalTitle').textContent = 'Add NTP Server';
            document.getElementById('ntpServerForm').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('ntpServerModal'));
            modal.show();
        }

        function editNTPServer(index) {
            const server = ntpServers[index];
            document.getElementById('ntp-server-action').value = 'edit';
            document.getElementById('ntpServerModalTitle').textContent = 'Edit NTP Server';
            
            document.getElementById('ntp-server-ip').value = server.ip || '';
            document.getElementById('ntp-server-key').value = server.keyId || '';
            document.getElementById('ntp-server-prefer').checked = server.prefer || false;
            document.getElementById('ntp-server-vrf').value = server.vrf || '';
            
            const modal = new bootstrap.Modal(document.getElementById('ntpServerModal'));
            modal.show();
        }

        function deleteNTPServer(index) {
            if (confirm('Are you sure you want to delete this NTP server?')) {
                const server = ntpServers[index];
                const command = `no ntp server ${server.ip}`;
                
                executeCommand(command, function(data) {
                    showAlert('NTP server deleted successfully', 'success');
                    loadNTPServers();
                }, 'cli_conf');
            }
        }

        function saveNTPServer() {
            if (!validateNTPServerForm()) {
                return;
            }

            const action = document.getElementById('ntp-server-action').value;
            const ip = document.getElementById('ntp-server-ip').value;
            const keyId = document.getElementById('ntp-server-key').value;
            const prefer = document.getElementById('ntp-server-prefer').checked;
            const vrf = document.getElementById('ntp-server-vrf').value;

            let command = `ntp server ${ip}`;
            if (keyId) {
                command += ` key ${keyId}`;
            }
            if (prefer) {
                command += ' prefer';
            }
            if (vrf && vrf !== 'default') {
                command += ` use-vrf ${vrf}`;
            }

            executeCommand(command, function(data) {
                showAlert(`NTP server ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('ntpServerModal')).hide();
                loadNTPServers();
            }, 'cli_conf');
        }

        function validateNTPServerForm() {
            const ip = document.getElementById('ntp-server-ip').value;
            if (!ip.trim()) {
                showAlert('Please enter an IP address', 'warning');
                return false;
            }
            if (!validateIP(ip)) {
                showAlert('Please enter a valid IP address', 'warning');
                return false;
            }
            return true;
        }

        function showAddNTPPeer() {
            document.getElementById('ntp-peer-action').value = 'add';
            document.getElementById('ntpPeerModalTitle').textContent = 'Add NTP Peer';
            document.getElementById('ntpPeerForm').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('ntpPeerModal'));
            modal.show();
        }

        function editNTPPeer(index) {
            const peer = ntpPeers[index];
            document.getElementById('ntp-peer-action').value = 'edit';
            document.getElementById('ntpPeerModalTitle').textContent = 'Edit NTP Peer';
            
            document.getElementById('ntp-peer-ip').value = peer.ip || '';
            document.getElementById('ntp-peer-key').value = peer.keyId || '';
            document.getElementById('ntp-peer-prefer').checked = peer.prefer || false;
            document.getElementById('ntp-peer-vrf').value = peer.vrf || '';
            
            const modal = new bootstrap.Modal(document.getElementById('ntpPeerModal'));
            modal.show();
        }

        function deleteNTPPeer(index) {
            if (confirm('Are you sure you want to delete this NTP peer?')) {
                const peer = ntpPeers[index];
                let command = `no ntp peer ${peer.ip}`;
                if (peer.vrf && peer.vrf !== 'default') {
                    command += ` use-vrf ${peer.vrf}`;
                }
                executeCommand(command, function(data) {
                    console.log('NTP peer delete response:', data);
                    showAlert('NTP peer deleted successfully', 'success');
                    loadNTPPeers();
                }, 'cli_conf');
            }
        }

        function saveNTPPeer() {
            if (!validateNTPPeerForm()) {
                return;
            }

            const action = document.getElementById('ntp-peer-action').value;
            const ip = document.getElementById('ntp-peer-ip').value;
            const keyId = document.getElementById('ntp-peer-key').value;
            const prefer = document.getElementById('ntp-peer-prefer').checked;
            const vrf = document.getElementById('ntp-peer-vrf').value;

            let command = `ntp peer ${ip}`;
            if (keyId) {
                command += ` key ${keyId}`;
            }
            if (prefer) {
                command += ' prefer';
            }
            if (vrf && vrf !== 'default') {
                command += ` use-vrf ${vrf}`;
            }

            executeCommand(command, function(data) {
                console.log('NTP peer command response:', data);
                showAlert(`NTP peer ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('ntpPeerModal')).hide();
                loadNTPPeers();
            }, 'cli_conf');
        }

        function validateNTPPeerForm() {
            const ip = document.getElementById('ntp-peer-ip').value;
            if (!ip.trim()) {
                showAlert('Please enter an IP address', 'warning');
                return false;
            }
            if (!validateIP(ip)) {
                showAlert('Please enter a valid IP address', 'warning');
                return false;
            }
            return true;
        }

        function showAddNTPKey() {
            document.getElementById('ntp-key-action').value = 'add';
            document.getElementById('ntpKeyModalTitle').textContent = 'Add NTP Key';
            document.getElementById('ntpKeyForm').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('ntpKeyModal'));
            modal.show();
        }

        function editNTPKey(index) {
            const key = ntpKeys[index];
            document.getElementById('ntp-key-action').value = 'edit';
            document.getElementById('ntpKeyModalTitle').textContent = 'Edit NTP Key';
            
            document.getElementById('ntp-key-id').value = key.id || '';
            document.getElementById('ntp-key-string').value = '';
            document.getElementById('ntp-key-trusted').checked = key.trusted || false;
            
            const modal = new bootstrap.Modal(document.getElementById('ntpKeyModal'));
            modal.show();
        }

        function deleteNTPKey(index) {
            if (confirm('Are you sure you want to delete this NTP key?')) {
                const key = ntpKeys[index];
                const command = `no ntp authentication-key ${key.id}`;
                
                executeCommand(command, function(data) {
                    showAlert('NTP key deleted successfully', 'success');
                    loadNTPKeys();
                }, 'cli_conf');
            }
        }

        function saveNTPKey() {
            if (!validateNTPKeyForm()) {
                return;
            }

            const action = document.getElementById('ntp-key-action').value;
            const keyId = document.getElementById('ntp-key-id').value;
            const keyString = document.getElementById('ntp-key-string').value;
            const trusted = document.getElementById('ntp-key-trusted').checked;

            let commands = [`ntp authentication-key ${keyId} md5 ${keyString}`];
            if (trusted) {
                commands.push(`ntp trusted-key ${keyId}`);
            }

            executeCommand(commands.join(' ; '), function(data) {
                showAlert(`NTP key ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('ntpKeyModal')).hide();
                loadNTPKeys();
            }, 'cli_conf');
        }

        function validateNTPKeyForm() {
            const keyId = document.getElementById('ntp-key-id').value;
            const keyString = document.getElementById('ntp-key-string').value;
            
            if (!keyId || !keyString) {
                showAlert('Please fill in all required fields', 'warning');
                return false;
            }
            return true;
        }

        function loadAuthSettings() {
            executeCommand('show running-config | include ntp authenticate', function(data) {
                const isEnabled = data && data.ins_api && data.ins_api.outputs && 
                                data.ins_api.outputs.output && 
                                data.ins_api.outputs.output.body && 
                                data.ins_api.outputs.output.body.includes('ntp authenticate');
                document.getElementById('ntp-authenticate').checked = isEnabled;
            });
        }

        function saveAuthSettings() {
            const isEnabled = document.getElementById('ntp-authenticate').checked;
            const command = isEnabled ? 'ntp authenticate' : 'no ntp authenticate';
            
            executeCommand(command, function(data) {
                showAlert('Authentication settings saved successfully', 'success');
            }, 'cli_conf');
        }

        function loadSourceConfig() {
            executeCommand('show running-config | include ntp source', function(data) {
                // Parse source configuration
            });
        }

        function saveSourceConfig() {
            const interface = document.getElementById('ntp-source-interface').value;
            const ip = document.getElementById('ntp-source-ip').value;

            let commands = [];
            if (interface) {
                commands.push(`ntp source-interface ${interface}`);
            }
            if (ip) {
                commands.push(`ntp source ${ip}`);
            }

            if (commands.length > 0) {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('Source configuration saved successfully', 'success');
                }, 'cli_conf');
            }
        }

        function loadAccessControl() {
            executeCommand('show running-config | include ntp access-group', function(data) {
                // Parse access control configuration
            });
        }

        function saveAccessControl() {
            const type = document.getElementById('ntp-access-type').value;
            const acl = document.getElementById('ntp-acl-name').value;

            if (type && acl) {
                const command = `ntp access-group ${type} ${acl}`;
                executeCommand(command, function(data) {
                    showAlert('Access control saved successfully', 'success');
                }, 'cli_conf');
            }
        }

        function loadLoggingConfig() {
            executeCommand('show running-config | include ntp logging', function(data) {
                const isEnabled = data && data.ins_api && data.ins_api.outputs && 
                                data.ins_api.outputs.output && 
                                data.ins_api.outputs.output.body && 
                                data.ins_api.outputs.output.body.includes('ntp logging');
                document.getElementById('ntp-logging').checked = isEnabled;
            });
        }

        function saveLoggingConfig() {
            const isEnabled = document.getElementById('ntp-logging').checked;
            const command = isEnabled ? 'ntp logging' : 'no ntp logging';
            
            executeCommand(command, function(data) {
                showAlert('Logging configuration saved successfully', 'success');
            }, 'cli_conf');
        }

        function showNTPStatus() {
            loadNTPStatus();
            showAlert('NTP status refreshed', 'info');
        }

        function showNTPPeers() {
            showAlert('NTP peers view will be implemented later', 'info');
        }

        function showNTPStats() {
            showAlert('NTP statistics view will be implemented later', 'info');
        }
    </script>
</body>
</html> 