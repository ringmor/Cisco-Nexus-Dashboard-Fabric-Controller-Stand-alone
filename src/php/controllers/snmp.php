<?php
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNMP Configuration - Cisco Nexus Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <style>
        .snmp-status-card {
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
        .security-badge {
            font-size: 0.8em;
            padding: 4px 8px;
        }
        .version-badge {
            font-size: 0.7em;
            padding: 3px 6px;
        }
        .auth-type-badge {
            font-size: 0.7em;
            padding: 3px 6px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-broadcast-tower"></i> SNMP Configuration</h2>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="showSNMPStatus()">
                            <i class="fas fa-info-circle"></i> Show Status
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="testSNMP()">
                            <i class="fas fa-play"></i> Test SNMP
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="debugSNMPUsers()">
                            <i class="fas fa-bug"></i> Debug Users
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="debugSNMPCommunities()">
                            <i class="fas fa-bug"></i> Debug Communities
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="debugSNMPHosts()">
                            <i class="fas fa-bug"></i> Debug Hosts
                        </button>
                    </div>
                </div>

                <!-- SNMP Status Card -->
                <div class="snmp-status-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5><i class="fas fa-toggle-on"></i> SNMP Status</h5>
                            <p class="mb-0" id="snmp-status">Checking...</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-light" onclick="toggleSNMP()" id="snmp-toggle-btn">
                                <i class="fas fa-power-off"></i> Enable SNMP
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SNMP Users Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-users"></i> SNMP Users</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddSNMPUser()">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="snmp-users-table">
                                                            <thead class="table-dark">
                                    <tr>
                                        <th>Username</th>
                                        <th>Group</th>
                                        <th>Auth Type</th>
                                        <th>Privacy</th>
                                        <th>Enforce Priv</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            <tbody id="snmp-users-tbody">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading SNMP users...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SNMP Communities Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-key"></i> SNMP Communities</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddSNMPCommunity()">
                            <i class="fas fa-plus"></i> Add Community
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="snmp-communities-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Community</th>
                                    <th>Group</th>
                                    <th>ACL</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="snmp-communities-tbody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading SNMP communities...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SNMP Hosts Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-server"></i> SNMP Notification Receivers</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddSNMPHost()">
                            <i class="fas fa-plus"></i> Add Receiver
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="snmp-hosts-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Type</th>
                                    <th>Version</th>
                                    <th>Community/User</th>
                                    <th>Port</th>
                                    <th>VRF</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="snmp-hosts-tbody">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading SNMP hosts...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SNMP Traps Section -->
                <div class="config-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="config-title mb-0"><i class="fas fa-bell"></i> SNMP Traps</h5>
                        <button type="button" class="btn btn-primary" onclick="showTrapConfiguration()">
                            <i class="fas fa-cog"></i> Configure Traps
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Enabled Traps</h6>
                                </div>
                                <div class="card-body">
                                    <div id="enabled-traps">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="trap-link" checked>
                                            <label class="form-check-label" for="trap-link">Link Up/Down</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="trap-snmp" checked>
                                            <label class="form-check-label" for="trap-snmp">SNMP Authentication</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="trap-interface">
                                            <label class="form-check-label" for="trap-interface">Interface</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="trap-system">
                                            <label class="form-check-label" for="trap-system">System</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">System Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">System Contact</label>
                                        <input type="text" class="form-control" id="system-contact" placeholder="admin@company.com">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">System Location</label>
                                        <input type="text" class="form-control" id="system-location" placeholder="Data Center - Rack 1">
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="saveSystemInfo()">
                                        <i class="fas fa-save"></i> Save System Info
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit SNMP User Modal -->
    <div class="modal fade" id="snmpUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="snmpUserModalTitle">Add SNMP User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="snmpUserForm">
                        <input type="hidden" id="snmp-user-action" value="add">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username *</label>
                                    <input type="text" class="form-control" id="snmp-username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Group *</label>
                                    <select class="form-select" id="snmp-group" required>
                                        <option value="">Select Group</option>
                                        <option value="network-admin">network-admin</option>
                                        <option value="network-operator">network-operator</option>
                                        <option value="vdc-admin">vdc-admin</option>
                                        <option value="vdc-operator">vdc-operator</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">SNMP Version *</label>
                                    <select class="form-select" id="snmp-version" required onchange="toggleAuthFields()">
                                        <option value="">Select Version</option>
                                        <option value="1">SNMPv1</option>
                                        <option value="2c">SNMPv2c</option>
                                        <option value="3">SNMPv3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Authentication Type</label>
                                    <select class="form-select" id="snmp-auth-type" disabled>
                                        <option value="">Select Auth Type</option>
                                        <option value="md5">MD5</option>
                                        <option value="sha">SHA</option>
                                        <option value="sha-256">SHA-256</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Authentication Password</label>
                                    <input type="password" class="form-control" id="snmp-auth-password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Privacy Type</label>
                                    <select class="form-select" id="snmp-priv-type" disabled>
                                        <option value="">Select Privacy Type</option>
                                        <option value="des">DES</option>
                                        <option value="aes-128">AES-128</option>
                                        <option value="aes-192">AES-192</option>
                                        <option value="aes-256">AES-256</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Privacy Password</label>
                                    <input type="password" class="form-control" id="snmp-priv-password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="snmp-enforce-priv">
                                        <label class="form-check-label" for="snmp-enforce-priv">
                                            Enforce Privacy
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSNMPUser()">Save User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit SNMP Community Modal -->
    <div class="modal fade" id="snmpCommunityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="snmpCommunityModalTitle">Add SNMP Community</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="snmpCommunityForm">
                        <input type="hidden" id="snmp-community-action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Community String *</label>
                            <input type="text" class="form-control" id="snmp-community-string" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Group *</label>
                            <select class="form-select" id="snmp-community-group" required>
                                <option value="">Select Group</option>
                                <option value="ro">Read Only</option>
                                <option value="rw">Read Write</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ACL (Optional)</label>
                            <input type="text" class="form-control" id="snmp-community-acl" placeholder="ACL name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSNMPCommunity()">Save Community</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit SNMP Host Modal -->
    <div class="modal fade" id="snmpHostModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="snmpHostModalTitle">Add SNMP Notification Receiver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="snmpHostForm">
                        <input type="hidden" id="snmp-host-action" value="add">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IP Address *</label>
                                    <input type="text" class="form-control" id="snmp-host-ip" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Type *</label>
                                    <select class="form-select" id="snmp-host-type" required>
                                        <option value="">Select Type</option>
                                        <option value="traps">Traps</option>
                                        <option value="informs">Informs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">SNMP Version *</label>
                                    <select class="form-select" id="snmp-host-version" required>
                                        <option value="">Select Version</option>
                                        <option value="1">SNMPv1</option>
                                        <option value="2c">SNMPv2c</option>
                                        <option value="3">SNMPv3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Community/User *</label>
                                    <input type="text" class="form-control" id="snmp-host-community" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Port</label>
                                    <input type="number" class="form-control" id="snmp-host-port" value="162" min="1" max="65535">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">VRF</label>
                                    <input type="text" class="form-control" id="snmp-host-vrf" placeholder="default">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSNMPHost()">Save Receiver</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/common.js"></script>
    <script>
        let snmpUsers = [];
        let snmpCommunities = [];
        let snmpHosts = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('SNMP page initializing...');
            loadSNMPStatus();
            loadSNMPUsers();
            loadSNMPCommunities();
            loadSNMPHosts();
            loadSystemInfo();
        });

        function loadSNMPStatus() {
            executeCommand('show feature snmp', function(data) {
                console.log('SNMP status response:', data);
                let isEnabled = false;
                
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        isEnabled = output.body.includes('enabled') || output.body.includes('Enabled');
                    }
                }
                
                document.getElementById('snmp-status').textContent = isEnabled ? 'SNMP is Enabled' : 'SNMP is Disabled';
                document.getElementById('snmp-toggle-btn').innerHTML = isEnabled ? 
                    '<i class="fas fa-power-off"></i> Disable SNMP' : 
                    '<i class="fas fa-power-off"></i> Enable SNMP';
                document.getElementById('snmp-toggle-btn').className = isEnabled ? 
                    'btn btn-warning' : 'btn btn-light';
            }, 'cli_show');
        }

        function loadSNMPUsers() {
            executeCommand('show snmp user', function(data) {
                console.log('SNMP users response:', data);
                console.log('Response type:', typeof data);
                console.log('Response structure:', JSON.stringify(data, null, 2));
                
                // Handle the result wrapper if present
                let actualData = data;
                if (data && data.result) {
                    actualData = data.result;
                    console.log('Using data.result structure');
                }
                
                snmpUsers = parseSNMPUsers(data);
                console.log('Parsed users array:', snmpUsers);
                populateSNMPUsersTable();
            }, 'cli_show');
        }

        function loadSNMPCommunities() {
            executeCommand('show snmp community', function(data) {
                console.log('SNMP communities response:', data);
                snmpCommunities = parseSNMPCommunities(data);
                populateSNMPCommunitiesTable();
            }, 'cli_show');
        }

        function loadSNMPHosts() {
            executeCommand('show snmp host', function(data) {
                console.log('SNMP hosts response:', data);
                snmpHosts = parseSNMPHosts(data);
                populateSNMPHostsTable();
            }, 'cli_show');
        }

        function loadSystemInfo() {
            executeCommand('show running-config | include snmp-server contact', function(data) {
                console.log('System contact response:', data);
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        const match = output.body.match(/snmp-server contact (.+)/);
                        if (match) {
                            document.getElementById('system-contact').value = match[1];
                        }
                    }
                }
            }, 'cli_show');
            
            executeCommand('show running-config | include snmp-server location', function(data) {
                console.log('System location response:', data);
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        const match = output.body.match(/snmp-server location (.+)/);
                        if (match) {
                            document.getElementById('system-location').value = match[1];
                        }
                    }
                }
            }, 'cli_show');
        }

        function parseSNMPUsers(data) {
            const users = [];
            console.log('Starting parseSNMPUsers with data:', data);
            
            // Handle different response structures
            let actualData = data;
            if (data && data.result) {
                actualData = data.result;
                console.log('Using data.result structure');
            }
            
            if (actualData && actualData.ins_api && actualData.ins_api.outputs && actualData.ins_api.outputs.output) {
                console.log('Found ins_api structure');
                const output = actualData.ins_api.outputs.output;
                console.log('Output body:', output.body);
                
                if (output.body && output.body.TABLE_snmp_user && output.body.TABLE_snmp_user.ROW_snmp_user) {
                    console.log('Found TABLE_snmp_user structure');
                    let userRows = output.body.TABLE_snmp_user.ROW_snmp_user;
                    console.log('User rows:', userRows);
                    
                    // Ensure it's always an array
                    if (!Array.isArray(userRows)) {
                        userRows = [userRows];
                    }
                    console.log('User rows after array conversion:', userRows);
                    
                    userRows.forEach((user, index) => {
                        console.log(`Processing user ${index}:`, user);
                        
                        // Parse privacy and enforce from priv_protocol
                        let privacy = 'no';
                        let enforcePriv = false;
                        
                        if (user.priv_protocol && user.priv_protocol !== 'no') {
                            const privMatch = user.priv_protocol.match(/(\w+)\((\w+)\)/);
                            if (privMatch) {
                                privacy = privMatch[1];
                                enforcePriv = privMatch[2] === 'yes';
                            } else {
                                privacy = user.priv_protocol;
                            }
                        }
                        
                        // Get group name from nested structure
                        let group = 'unknown';
                        if (user.TABLE_snmp_group_names && user.TABLE_snmp_group_names.ROW_snmp_group_names) {
                            const groupRow = user.TABLE_snmp_group_names.ROW_snmp_group_names;
                            if (Array.isArray(groupRow)) {
                                group = groupRow[0].group_names || 'unknown';
                            } else {
                                group = groupRow.group_names || 'unknown';
                            }
                        }
                        
                        const userObj = {
                            username: user.user || '',
                            authType: user.auth_protocol === 'no' ? null : user.auth_protocol,
                            privacy: privacy === 'no' ? null : privacy,
                            enforcePriv: enforcePriv,
                            group: group
                        };
                        
                        console.log(`Created user object:`, userObj);
                        users.push(userObj);
                    });
                } else {
                    console.log('No TABLE_snmp_user structure found in body');
                    console.log('Available keys in body:', Object.keys(output.body || {}));
                }
            } else {
                console.log('No ins_api structure found');
                console.log('Data keys:', Object.keys(actualData || {}));
            }
            
            console.log('Final parsed SNMP users:', users);
            return users;
        }

        function parseSNMPCommunities(data) {
            const communities = [];
            console.log('Starting parseSNMPCommunities with data:', data);
            
            // Handle different response structures
            let actualData = data;
            if (data && data.result) {
                actualData = data.result;
                console.log('Using data.result structure for communities');
            }
            
            if (actualData && actualData.ins_api && actualData.ins_api.outputs && actualData.ins_api.outputs.output) {
                console.log('Found ins_api structure for communities');
                const output = actualData.ins_api.outputs.output;
                console.log('Communities output body:', output.body);
                
                if (output.body && output.body.TABLE_snmp_community && output.body.TABLE_snmp_community.ROW_snmp_community) {
                    console.log('Found TABLE_snmp_community structure');
                    let communityRows = output.body.TABLE_snmp_community.ROW_snmp_community;
                    console.log('Community rows:', communityRows);
                    
                    // Ensure it's always an array
                    if (!Array.isArray(communityRows)) {
                        communityRows = [communityRows];
                    }
                    console.log('Community rows after array conversion:', communityRows);
                    
                    communityRows.forEach((community, index) => {
                        console.log(`Processing community ${index}:`, community);
                        
                        const communityObj = {
                            string: community.community || community.community_string || '',
                            group: community.group || community.group_name || '',
                            acl: community.acl || community.acl_name || null
                        };
                        
                        console.log(`Created community object:`, communityObj);
                        communities.push(communityObj);
                    });
                } else if (output.body) {
                    console.log('No TABLE_snmp_community structure found, trying text parsing');
                    // Fallback to parsing from running-config if JSON structure not available
                    const lines = output.body.split('\n');
                    lines.forEach(line => {
                        line = line.trim();
                        if (line.startsWith('snmp-server community')) {
                            const parts = line.split(' ');
                            if (parts.length >= 4) {
                                const communityString = parts[2];
                                const group = parts[3];
                                const acl = parts.length > 4 && parts[4] === 'use-acl' ? parts[5] : null;
                                
                                communities.push({
                                    string: communityString,
                                    group: group,
                                    acl: acl
                                });
                            }
                        }
                    });
                } else {
                    console.log('No body found in output');
                }
            } else {
                console.log('No ins_api structure found for communities');
                console.log('Data keys:', Object.keys(actualData || {}));
            }
            
            console.log('Final parsed SNMP communities:', communities);
            return communities;
        }

        function parseSNMPHosts(data) {
            const hosts = [];
            console.log('Starting parseSNMPHosts with data:', data);
            
            // Handle different response structures
            let actualData = data;
            if (data && data.result) {
                actualData = data.result;
                console.log('Using data.result structure for hosts');
            }
            
            if (actualData && actualData.ins_api && actualData.ins_api.outputs && actualData.ins_api.outputs.output) {
                console.log('Found ins_api structure for hosts');
                const output = actualData.ins_api.outputs.output;
                console.log('Hosts output body:', output.body);
                
                if (output.body && output.body.TABLE_snmp_host && output.body.TABLE_snmp_host.ROW_snmp_host) {
                    console.log('Found TABLE_snmp_host structure');
                    let hostRows = output.body.TABLE_snmp_host.ROW_snmp_host;
                    console.log('Host rows:', hostRows);
                    
                    // Ensure it's always an array
                    if (!Array.isArray(hostRows)) {
                        hostRows = [hostRows];
                    }
                    console.log('Host rows after array conversion:', hostRows);
                    
                    hostRows.forEach((host, index) => {
                        console.log(`Processing host ${index}:`, host);
                        
                        const hostObj = {
                            ip: host.host_ip || host.ip_address || '',
                            type: host.host_type || host.type || '',
                            version: host.snmp_version || host.version || '',
                            community: host.community || host.community_string || '',
                            port: host.port || '162',
                            vrf: host.vrf || host.use_vrf || 'default'
                        };
                        
                        console.log(`Created host object:`, hostObj);
                        hosts.push(hostObj);
                    });
                } else if (output.body) {
                    console.log('No TABLE_snmp_host structure found, trying text parsing');
                    // Fallback to text parsing if JSON structure not available
                    const lines = output.body.split('\n');
                    lines.forEach(line => {
                        line = line.trim();
                        if (line.startsWith('snmp-server host')) {
                            // Parse snmp-server host command line
                            const parts = line.split(' ');
                            if (parts.length >= 4) {
                                const ip = parts[2];
                                const type = parts[3];
                                // Extract other fields as needed
                                hosts.push({
                                    ip: ip,
                                    type: type,
                                    version: '2c', // default
                                    community: 'public', // default
                                    port: '162',
                                    vrf: 'default'
                                });
                            }
                        }
                    });
                } else {
                    console.log('No body found in output for hosts');
                }
            } else {
                console.log('No ins_api structure found for hosts');
                console.log('Data keys:', Object.keys(actualData || {}));
            }
            
            console.log('Final parsed SNMP hosts:', hosts);
            return hosts;
        }

        function populateSNMPUsersTable() {
            const tbody = document.getElementById('snmp-users-tbody');
            
            if (snmpUsers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No SNMP users configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = snmpUsers.map((user, index) => `
                <tr>
                    <td>${user.username}</td>
                    <td>${user.group}</td>
                    <td><span class="badge bg-secondary auth-type-badge">${user.authType || 'None'}</span></td>
                    <td><span class="badge bg-secondary auth-type-badge">${user.privacy || 'None'}</span></td>
                    <td><span class="badge ${user.enforcePriv ? 'bg-success' : 'bg-secondary'}">${user.enforcePriv ? 'Yes' : 'No'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editSNMPUser(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSNMPUser(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateSNMPCommunitiesTable() {
            const tbody = document.getElementById('snmp-communities-tbody');
            
            if (snmpCommunities.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No SNMP communities configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = snmpCommunities.map((community, index) => `
                <tr>
                    <td>${community.string}</td>
                    <td><span class="badge ${community.group === 'ro' ? 'bg-info' : 'bg-warning'}">${community.group}</span></td>
                    <td>${community.acl || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editSNMPCommunity(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSNMPCommunity(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateSNMPHostsTable() {
            const tbody = document.getElementById('snmp-hosts-tbody');
            
            if (snmpHosts.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No SNMP hosts configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = snmpHosts.map((host, index) => `
                <tr>
                    <td>${host.ip}</td>
                    <td><span class="badge bg-info">${host.type}</span></td>
                    <td><span class="badge bg-secondary version-badge">v${host.version}</span></td>
                    <td>${host.community}</td>
                    <td>${host.port || '162'}</td>
                    <td>${host.vrf || 'default'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editSNMPHost(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSNMPHost(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function toggleSNMP() {
            const isEnabled = document.getElementById('snmp-status').textContent.includes('Enabled');
            const command = isEnabled ? 'no feature snmp' : 'feature snmp';
            
            const btn = document.getElementById('snmp-toggle-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            executeCommand(command, function(data) {
                showAlert(`SNMP ${isEnabled ? 'disabled' : 'enabled'} successfully`, 'success');
                setTimeout(() => {
                    loadSNMPStatus();
                    btn.disabled = false;
                }, 1000);
            }, 'cli_conf');
        }

        function showAddSNMPUser() {
            document.getElementById('snmp-user-action').value = 'add';
            document.getElementById('snmpUserModalTitle').textContent = 'Add SNMP User';
            document.getElementById('snmpUserForm').reset();
            document.getElementById('snmp-auth-type').disabled = true;
            document.getElementById('snmp-priv-type').disabled = true;
            
            const modal = new bootstrap.Modal(document.getElementById('snmpUserModal'));
            modal.show();
        }

        function editSNMPUser(index) {
            const user = snmpUsers[index];
            document.getElementById('snmp-user-action').value = 'edit';
            document.getElementById('snmpUserModalTitle').textContent = 'Edit SNMP User';
            
            // Populate form fields
            document.getElementById('snmp-username').value = user.username || '';
            document.getElementById('snmp-group').value = user.group || '';
            document.getElementById('snmp-version').value = user.version || '';
            document.getElementById('snmp-auth-type').value = user.authType || '';
            document.getElementById('snmp-auth-password').value = '';
            document.getElementById('snmp-priv-type').value = user.privacy || '';
            document.getElementById('snmp-priv-password').value = '';
            document.getElementById('snmp-enforce-priv').checked = user.enforcePriv || false;
            
            toggleAuthFields();
            
            const modal = new bootstrap.Modal(document.getElementById('snmpUserModal'));
            modal.show();
        }

        function deleteSNMPUser(index) {
            if (confirm('Are you sure you want to delete this SNMP user?')) {
                const user = snmpUsers[index];
                const command = `no snmp-server user ${user.username}`;
                
                executeCommand(command, function(data) {
                    showAlert('SNMP user deleted successfully', 'success');
                    loadSNMPUsers();
                }, 'cli_conf');
            }
        }

        function toggleAuthFields() {
            const version = document.getElementById('snmp-version').value;
            const authType = document.getElementById('snmp-auth-type');
            const privType = document.getElementById('snmp-priv-type');
            
            if (version === '3') {
                authType.disabled = false;
                privType.disabled = false;
            } else {
                authType.disabled = true;
                privType.disabled = true;
            }
        }

        function saveSNMPUser() {
            if (!validateSNMPUserForm()) {
                return;
            }

            const action = document.getElementById('snmp-user-action').value;
            const username = document.getElementById('snmp-username').value;
            const group = document.getElementById('snmp-group').value;
            const version = document.getElementById('snmp-version').value;
            const authType = document.getElementById('snmp-auth-type').value;
            const authPassword = document.getElementById('snmp-auth-password').value;
            const privType = document.getElementById('snmp-priv-type').value;
            const privPassword = document.getElementById('snmp-priv-password').value;
            const enforcePriv = document.getElementById('snmp-enforce-priv').checked;

            let command = `snmp-server user ${username} ${group}`;
            
            if (version === '3') {
                if (authType) {
                    command += ` auth ${authType} ${authPassword}`;
                }
                if (privType) {
                    command += ` priv ${privType} ${privPassword}`;
                }
                if (enforcePriv) {
                    command += ' enforcePriv';
                }
            }

            executeCommand(command, function(data) {
                showAlert(`SNMP user ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('snmpUserModal')).hide();
                loadSNMPUsers();
            }, 'cli_conf');
        }

        function validateSNMPUserForm() {
            const requiredFields = ['snmp-username', 'snmp-group', 'snmp-version'];
            
            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    showAlert(`Please fill in the ${field.previousElementSibling.textContent.replace('*', '').trim()} field`, 'warning');
                    field.focus();
                    return false;
                }
            }

            return true;
        }

        function showAddSNMPCommunity() {
            document.getElementById('snmp-community-action').value = 'add';
            document.getElementById('snmpCommunityModalTitle').textContent = 'Add SNMP Community';
            document.getElementById('snmpCommunityForm').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('snmpCommunityModal'));
            modal.show();
        }

        function editSNMPCommunity(index) {
            const community = snmpCommunities[index];
            document.getElementById('snmp-community-action').value = 'edit';
            document.getElementById('snmpCommunityModalTitle').textContent = 'Edit SNMP Community';
            
            document.getElementById('snmp-community-string').value = community.string || '';
            document.getElementById('snmp-community-group').value = community.group || '';
            document.getElementById('snmp-community-acl').value = community.acl || '';
            
            const modal = new bootstrap.Modal(document.getElementById('snmpCommunityModal'));
            modal.show();
        }

        function deleteSNMPCommunity(index) {
            if (confirm('Are you sure you want to delete this SNMP community?')) {
                const community = snmpCommunities[index];
                const command = `no snmp-server community ${community.string}`;
                
                executeCommand(command, function(data) {
                    showAlert('SNMP community deleted successfully', 'success');
                    loadSNMPCommunities();
                }, 'cli_conf');
            }
        }

        function saveSNMPCommunity() {
            if (!validateSNMPCommunityForm()) {
                return;
            }

            const action = document.getElementById('snmp-community-action').value;
            const community = document.getElementById('snmp-community-string').value;
            const group = document.getElementById('snmp-community-group').value;
            const acl = document.getElementById('snmp-community-acl').value;

            let command = `snmp-server community ${community} group ${group}`;
            if (acl) {
                command += ` use-acl ${acl}`;
            }

            executeCommand(command, function(data) {
                showAlert(`SNMP community ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('snmpCommunityModal')).hide();
                loadSNMPCommunities();
            }, 'cli_conf');
        }

        function validateSNMPCommunityForm() {
            const requiredFields = ['snmp-community-string', 'snmp-community-group'];
            
            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    showAlert(`Please fill in the ${field.previousElementSibling.textContent.replace('*', '').trim()} field`, 'warning');
                    field.focus();
                    return false;
                }
            }

            return true;
        }

        function showAddSNMPHost() {
            document.getElementById('snmp-host-action').value = 'add';
            document.getElementById('snmpHostModalTitle').textContent = 'Add SNMP Notification Receiver';
            document.getElementById('snmpHostForm').reset();
            
            const modal = new bootstrap.Modal(document.getElementById('snmpHostModal'));
            modal.show();
        }

        function editSNMPHost(index) {
            const host = snmpHosts[index];
            document.getElementById('snmp-host-action').value = 'edit';
            document.getElementById('snmpHostModalTitle').textContent = 'Edit SNMP Notification Receiver';
            
            document.getElementById('snmp-host-ip').value = host.ip || '';
            document.getElementById('snmp-host-type').value = host.type || '';
            document.getElementById('snmp-host-version').value = host.version || '';
            document.getElementById('snmp-host-community').value = host.community || '';
            document.getElementById('snmp-host-port').value = host.port || '162';
            document.getElementById('snmp-host-vrf').value = host.vrf || '';
            
            const modal = new bootstrap.Modal(document.getElementById('snmpHostModal'));
            modal.show();
        }

        function deleteSNMPHost(index) {
            if (confirm('Are you sure you want to delete this SNMP host?')) {
                const host = snmpHosts[index];
                const command = `no snmp-server host ${host.ip}`;
                
                executeCommand(command, function(data) {
                    showAlert('SNMP host deleted successfully', 'success');
                    loadSNMPHosts();
                }, 'cli_conf');
            }
        }

        function saveSNMPHost() {
            if (!validateSNMPHostForm()) {
                return;
            }

            const action = document.getElementById('snmp-host-action').value;
            const ip = document.getElementById('snmp-host-ip').value;
            const type = document.getElementById('snmp-host-type').value;
            const version = document.getElementById('snmp-host-version').value;
            const community = document.getElementById('snmp-host-community').value;
            const port = document.getElementById('snmp-host-port').value;
            const vrf = document.getElementById('snmp-host-vrf').value;

            let command = `snmp-server host ${ip} ${type} version ${version} ${community}`;
            if (port && port !== '162') {
                command += ` port ${port}`;
            }
            if (vrf && vrf !== 'default') {
                command += ` use-vrf ${vrf}`;
            }

            executeCommand(command, function(data) {
                showAlert(`SNMP host ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('snmpHostModal')).hide();
                loadSNMPHosts();
            }, 'cli_conf');
        }

        function validateSNMPHostForm() {
            const requiredFields = ['snmp-host-ip', 'snmp-host-type', 'snmp-host-version', 'snmp-host-community'];
            
            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    showAlert(`Please fill in the ${field.previousElementSibling.textContent.replace('*', '').trim()} field`, 'warning');
                    field.focus();
                    return false;
                }
            }

            const ip = document.getElementById('snmp-host-ip').value;
            if (!validateIP(ip)) {
                showAlert('Please enter a valid IP address', 'warning');
                return false;
            }

            return true;
        }

        function saveSystemInfo() {
            const contact = document.getElementById('system-contact').value;
            const location = document.getElementById('system-location').value;

            let commands = [];
            if (contact) {
                commands.push(`snmp-server contact ${contact}`);
            }
            if (location) {
                commands.push(`snmp-server location ${location}`);
            }

            if (commands.length > 0) {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('System information saved successfully', 'success');
                }, 'cli_conf');
            }
        }

        function showSNMPStatus() {
            loadSNMPStatus();
            showAlert('SNMP status refreshed', 'info');
        }

        function testSNMP() {
            console.log('Testing SNMP API connection...');
            executeCommand('show version', function(data) {
                console.log('Test command response:', data);
                if (data && data.ins_api) {
                    showAlert('SNMP API connection successful!', 'success');
                    
                    // Test creating a simple SNMP user
                    executeCommand('snmp-server user testuser network-operator', function(data2) {
                        console.log('Create user response:', data2);
                        if (data2 && data2.ins_api) {
                            showAlert('SNMP user creation test successful!', 'success');
                            // Reload users to show the new one
                            setTimeout(() => {
                                loadSNMPUsers();
                            }, 1000);
                        } else {
                            showAlert('SNMP user creation test failed!', 'warning');
                        }
                    }, 'cli_conf');
                } else {
                    showAlert('SNMP API connection failed!', 'danger');
                }
            }, 'cli_show');
        }

        function debugSNMPUsers() {
            console.log('Debugging SNMP users...');
            executeCommand('show snmp user', function(data) {
                console.log('Raw SNMP users response:', JSON.stringify(data, null, 2));
                console.log('Data type:', typeof data);
                console.log('Data keys:', Object.keys(data || {}));
                
                if (data && data.result) {
                    console.log('Data.result:', JSON.stringify(data.result, null, 2));
                }
                
                const users = parseSNMPUsers(data);
                console.log('Parsed users:', users);
                showAlert(`Found ${users.length} SNMP users`, 'info');
            }, 'cli_show');
        }

        function debugSNMPCommunities() {
            console.log('Debugging SNMP communities...');
            executeCommand('show snmp community', function(data) {
                console.log('Raw SNMP communities response:', JSON.stringify(data, null, 2));
                console.log('Data type:', typeof data);
                console.log('Data keys:', Object.keys(data || {}));
                
                if (data && data.result) {
                    console.log('Data.result:', JSON.stringify(data.result, null, 2));
                }
                
                const communities = parseSNMPCommunities(data);
                console.log('Parsed communities:', communities);
                showAlert(`Found ${communities.length} SNMP communities`, 'info');
            }, 'cli_show');
        }

        function debugSNMPHosts() {
            console.log('Debugging SNMP hosts...');
            executeCommand('show snmp host', function(data) {
                console.log('Raw SNMP hosts response:', JSON.stringify(data, null, 2));
                console.log('Data type:', typeof data);
                console.log('Data keys:', Object.keys(data || {}));
                
                if (data && data.result) {
                    console.log('Data.result:', JSON.stringify(data.result, null, 2));
                }
                
                const hosts = parseSNMPHosts(data);
                console.log('Parsed hosts:', hosts);
                showAlert(`Found ${hosts.length} SNMP hosts`, 'info');
            }, 'cli_show');
        }

        function showTrapConfiguration() {
            showAlert('Trap configuration will be implemented later', 'info');
        }
    </script>
</body>
</html> 