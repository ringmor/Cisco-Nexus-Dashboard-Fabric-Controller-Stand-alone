<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VPC Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-link"></i> VPC (Virtual Port Channel) Configuration</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureVpc()">
                    <i class="fas fa-cog"></i> Configure VPC
                </button>
            </div>
        </div>

        <!-- VPC Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">VPC Domain</h5>
                        <h2 id="vpc-domain">-</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">VPC Status</h5>
                        <h2 id="vpc-status">Down</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">VPC Peers</h5>
                        <h2 id="vpc-peers">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">VPC VLANs</h5>
                        <h2 id="vpc-vlans">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- VPC Tabs -->
        <ul class="nav nav-tabs" id="vpcTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="domain-tab" data-bs-toggle="tab" data-bs-target="#domain" type="button" role="tab">
                    <i class="fas fa-network-wired"></i> VPC Domain
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="peers-tab" data-bs-toggle="tab" data-bs-target="#peers" type="button" role="tab">
                    <i class="fas fa-handshake"></i> VPC Peers
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vlans-tab" data-bs-toggle="tab" data-bs-target="#vlans" type="button" role="tab">
                    <i class="fas fa-layer-group"></i> VPC VLANs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="consistency-tab" data-bs-toggle="tab" data-bs-target="#consistency" type="button" role="tab">
                    <i class="fas fa-check-circle"></i> Consistency Check
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Configuration
                </button>
            </li>
        </ul>

        <div class="tab-content" id="vpcTabContent">
            <!-- VPC Domain Tab -->
            <div class="tab-pane fade show active" id="domain" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> VPC Domain Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Domain ID:</strong></td>
                                        <td id="domain-id">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Peer Status:</strong></td>
                                        <td id="peer-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Peer Keepalive Status:</strong></td>
                                        <td id="keepalive-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Configuration Status:</strong></td>
                                        <td id="config-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Per-VLAN Consistency Status:</strong></td>
                                        <td id="vlan-consistency-status">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Local Switch Role:</strong></td>
                                        <td id="local-role">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Number of VPCs:</strong></td>
                                        <td id="num-vpcs">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Peer-link Status:</strong></td>
                                        <td id="peer-link-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dual-Active Detection Status:</strong></td>
                                        <td id="dual-active-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>System MAC:</strong></td>
                                        <td id="system-mac">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-link"></i> VPC Peer-link Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Status</th>
                                        <th>VLAN</th>
                                        <th>Consistency Status</th>
                                        <th>Reason</th>
                                        <th>Active VLANs</th>
                                    </tr>
                                </thead>
                                <tbody id="peer-link-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading peer-link information...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VPC Peers Tab -->
            <div class="tab-pane fade" id="peers" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-handshake"></i> VPC Peer Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>VPC ID</th>
                                        <th>Port</th>
                                        <th>Status</th>
                                        <th>Consistency Status</th>
                                        <th>Reason</th>
                                        <th>Active VLANs</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="vpc-peers-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading VPC peers...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create VPC</h5>
                    </div>
                    <div class="card-body">
                        <form id="vpcCreateForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="vpc-id" class="form-label">VPC ID</label>
                                    <input type="number" class="form-control" id="vpc-id" min="1" max="4096" placeholder="1-4096" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="vpc-interface" class="form-label">Interface</label>
                                    <select class="form-select" id="vpc-interface" required>
                                        <option value="">Select Interface</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="vpc-mode" class="form-label">Mode</label>
                                    <select class="form-select" id="vpc-mode">
                                        <option value="active">Active</option>
                                        <option value="passive">Passive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="vpc-priority" class="form-label">Priority</label>
                                    <input type="number" class="form-control" id="vpc-priority" min="1" max="65535" placeholder="32768">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="createVpc()">
                                        Create VPC
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="deleteVpc()">
                                        Delete VPC
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- VPC VLANs Tab -->
            <div class="tab-pane fade" id="vlans" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-layer-group"></i> VPC VLAN Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>VLAN</th>
                                        <th>Status</th>
                                        <th>Consistency Status</th>
                                        <th>Local Suspended VLANs</th>
                                        <th>Remote Suspended VLANs</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="vpc-vlans-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading VPC VLANs...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consistency Check Tab -->
            <div class="tab-pane fade" id="consistency" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-check-circle"></i> VPC Consistency Check</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Local Value</th>
                                        <th>Peer Value</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="consistency-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading consistency check...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-tools"></i> Consistency Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary" onclick="runConsistencyCheck()">
                                    <i class="fas fa-play"></i> Run Consistency Check
                                </button>
                                <button class="btn btn-warning" onclick="suspendInconsistentVlans()">
                                    <i class="fas fa-pause"></i> Suspend Inconsistent VLANs
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success" onclick="resumeVlans()">
                                    <i class="fas fa-play"></i> Resume VLANs
                                </button>
                                <button class="btn btn-info" onclick="clearConsistencyCheck()">
                                    <i class="fas fa-eraser"></i> Clear Check Results
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Tab -->
            <div class="tab-pane fade" id="config" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> VPC Domain Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="vpcDomainConfigForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Basic VPC Settings</h6>
                                    <div class="mb-3">
                                        <label for="domain-id-config" class="form-label">Domain ID</label>
                                        <input type="number" class="form-control" id="domain-id-config" min="1" max="1000" placeholder="1-1000" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="system-priority" class="form-label">System Priority</label>
                                        <input type="number" class="form-control" id="system-priority" min="1" max="65535" placeholder="32768">
                                    </div>
                                    <div class="mb-3">
                                        <label for="system-mac-config" class="form-label">System MAC</label>
                                        <input type="text" class="form-control" id="system-mac-config" placeholder="Auto-generated">
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-switch" class="form-label">Peer Switch</label>
                                        <select class="form-select" id="peer-switch">
                                            <option value="">Disabled</option>
                                            <option value="enabled">Enabled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Peer-link Configuration</h6>
                                    <div class="mb-3">
                                        <label for="peer-link-interface" class="form-label">Peer-link Interface</label>
                                        <select class="form-select" id="peer-link-interface">
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-dest" class="form-label">Peer Keepalive Destination</label>
                                        <input type="text" class="form-control" id="peer-keepalive-dest" placeholder="192.168.1.2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-src" class="form-label">Peer Keepalive Source</label>
                                        <input type="text" class="form-control" id="peer-keepalive-src" placeholder="192.168.1.1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-vrf" class="form-label">Peer Keepalive VRF</label>
                                        <select class="form-select" id="peer-keepalive-vrf">
                                            <option value="management">management</option>
                                            <option value="default">default</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Advanced Settings</h6>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-interval" class="form-label">Keepalive Interval (ms)</label>
                                        <input type="number" class="form-control" id="peer-keepalive-interval" min="400" max="10000" placeholder="1000">
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-timeout" class="form-label">Keepalive Timeout (s)</label>
                                        <input type="number" class="form-control" id="peer-keepalive-timeout" min="3" max="20" placeholder="5">
                                    </div>
                                    <div class="mb-3">
                                        <label for="peer-keepalive-hold-timeout" class="form-label">Hold Timeout (s)</label>
                                        <input type="number" class="form-control" id="peer-keepalive-hold-timeout" min="3" max="10" placeholder="3">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dual-Active Detection</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="dual-active-exclude-interface-vlan">
                                            <label class="form-check-label" for="dual-active-exclude-interface-vlan">
                                                Exclude Interface-VLAN
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto-recovery">
                                            <label class="form-check-label" for="auto-recovery">
                                                Auto Recovery
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="auto-recovery-interval" class="form-label">Auto Recovery Interval (s)</label>
                                        <input type="number" class="form-control" id="auto-recovery-interval" min="240" max="3600" placeholder="240">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyVpcDomainConfig()">
                                        Apply Domain Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="enableVpcFeature()">
                                        Enable VPC Feature
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="disableVpcFeature()">
                                        Disable VPC Feature
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="reloadVpcDomain()">
                                        Reload VPC Domain
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
        let vpcData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadVpcData();
            loadInterfaceList();
        });

        function loadVpcData() {
            loadVpcDomain();
            loadVpcPeers();
            loadVpcVlans();
            loadConsistencyCheck();
        }

        function loadVpcDomain() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show vpc'
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
                
                const domain = parseVpcDomain(data.result);
                displayVpcDomain(domain);
            })
            .catch(error => {
                console.error('Error loading VPC domain:', error);
                displayVpcDomainError(error.message);
            });

            // Load peer-link information
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show vpc peer-keepalive'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const peerLink = parseVpcPeerLink(data.result);
                    displayVpcPeerLink(peerLink);
                }
            })
            .catch(error => console.error('Error loading VPC peer-link:', error));
        }

        function loadVpcPeers() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show vpc brief'
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
                
                const peers = parseVpcPeers(data.result);
                displayVpcPeers(peers);
                document.getElementById('vpc-peers').textContent = peers.length;
            })
            .catch(error => {
                console.error('Error loading VPC peers:', error);
                document.getElementById('vpc-peers-tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadVpcVlans() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show vpc consistency-parameters vlans'
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
                
                const vlans = parseVpcVlans(data.result);
                displayVpcVlans(vlans);
                document.getElementById('vpc-vlans').textContent = vlans.length;
            })
            .catch(error => {
                console.error('Error loading VPC VLANs:', error);
                document.getElementById('vpc-vlans-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadConsistencyCheck() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show vpc consistency-parameters global'
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
                
                const consistency = parseConsistencyCheck(data.result);
                displayConsistencyCheck(consistency);
            })
            .catch(error => {
                console.error('Error loading consistency check:', error);
                document.getElementById('consistency-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseVpcDomain(data) {
            const domain = {
                domain_id: '-',
                peer_status: 'Down',
                keepalive_status: 'Down',
                config_status: 'Not configured',
                vlan_consistency_status: 'Not applicable',
                local_role: 'None',
                num_vpcs: '0',
                peer_link_status: 'Down',
                dual_active_status: 'Not configured',
                system_mac: '-'
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.vpc_domain_id) {
                        domain.domain_id = output.body.vpc_domain_id;
                        domain.peer_status = output.body.vpc_peer_status || 'Down';
                        domain.keepalive_status = output.body.vpc_peer_keepalive_status || 'Down';
                        domain.config_status = output.body.vpc_config_status || 'Not configured';
                        domain.vlan_consistency_status = output.body.vpc_vlan_consistency_status || 'Not applicable';
                        domain.local_role = output.body.vpc_local_role || 'None';
                        domain.num_vpcs = output.body.vpc_num_vpcs || '0';
                        domain.peer_link_status = output.body.vpc_peer_link_status || 'Down';
                        domain.dual_active_status = output.body.vpc_dual_active_status || 'Not configured';
                        domain.system_mac = output.body.vpc_system_mac || '-';
                    }
                }
            } catch (e) {
                console.error('Error parsing VPC domain:', e);
            }
            
            return domain;
        }

        function parseVpcPeerLink(data) {
            const peerLink = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_peerlink) {
                        let rows = output.body.TABLE_peerlink.ROW_peerlink;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            peerLink.push({
                                interface: row.peerlink_ifindex || '',
                                status: row.peerlink_port_state || 'down',
                                vlan: row.peerlink_vlans || '',
                                consistency_status: row.peerlink_consistency_status || 'not-applicable',
                                reason: row.peerlink_consistency_reason || '',
                                active_vlans: row.peerlink_active_vlans || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing VPC peer-link:', e);
            }
            
            return peerLink;
        }

        function parseVpcPeers(data) {
            const peers = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vpc) {
                        let rows = output.body.TABLE_vpc.ROW_vpc;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            peers.push({
                                vpc_id: row.vpc_id || '',
                                port: row.vpc_ifindex || '',
                                status: row.vpc_port_state || 'down',
                                consistency_status: row.vpc_consistency_status || 'not-applicable',
                                reason: row.vpc_consistency_reason || '',
                                active_vlans: row.vpc_active_vlans || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing VPC peers:', e);
            }
            
            return peers;
        }

        function parseVpcVlans(data) {
            const vlans = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vlan) {
                        let rows = output.body.TABLE_vlan.ROW_vlan;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            vlans.push({
                                vlan: row.vlan_id || '',
                                status: row.vlan_status || 'down',
                                consistency_status: row.vlan_consistency_status || 'not-applicable',
                                local_suspended: row.vlan_local_suspended || '',
                                remote_suspended: row.vlan_remote_suspended || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing VPC VLANs:', e);
            }
            
            return vlans;
        }

        function parseConsistencyCheck(data) {
            const consistency = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_consistency) {
                        let rows = output.body.TABLE_consistency.ROW_consistency;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            consistency.push({
                                parameter: row.consistency_parameter || '',
                                local_value: row.consistency_local_value || '',
                                peer_value: row.consistency_peer_value || '',
                                status: row.consistency_status || 'not-applicable',
                                reason: row.consistency_reason || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing consistency check:', e);
            }
            
            return consistency;
        }

        function displayVpcDomain(domain) {
            document.getElementById('vpc-domain').textContent = domain.domain_id;
            document.getElementById('vpc-status').textContent = domain.peer_status;
            
            document.getElementById('domain-id').textContent = domain.domain_id;
            document.getElementById('peer-status').innerHTML = getStatusBadge(domain.peer_status);
            document.getElementById('keepalive-status').innerHTML = getStatusBadge(domain.keepalive_status);
            document.getElementById('config-status').innerHTML = getStatusBadge(domain.config_status);
            document.getElementById('vlan-consistency-status').innerHTML = getStatusBadge(domain.vlan_consistency_status);
            document.getElementById('local-role').innerHTML = getRoleBadge(domain.local_role);
            document.getElementById('num-vpcs').textContent = domain.num_vpcs;
            document.getElementById('peer-link-status').innerHTML = getStatusBadge(domain.peer_link_status);
            document.getElementById('dual-active-status').innerHTML = getStatusBadge(domain.dual_active_status);
            document.getElementById('system-mac').textContent = domain.system_mac;
        }

        function displayVpcDomainError(error) {
            document.getElementById('vpc-domain').textContent = 'Error';
            document.getElementById('vpc-status').textContent = 'Error';
            
            document.getElementById('domain-id').textContent = 'Error: ' + error;
            document.getElementById('peer-status').innerHTML = '<span class="badge bg-danger">Error</span>';
        }

        function displayVpcPeerLink(peerLink) {
            const tbody = document.getElementById('peer-link-tbody');
            tbody.innerHTML = '';

            if (!peerLink || peerLink.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No peer-link configured</td></tr>';
                return;
            }

            peerLink.forEach(link => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${link.interface}</strong></td>
                    <td>${getStatusBadge(link.status)}</td>
                    <td>${link.vlan}</td>
                    <td>${getStatusBadge(link.consistency_status)}</td>
                    <td><small>${link.reason}</small></td>
                    <td><small>${link.active_vlans}</small></td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayVpcPeers(peers) {
            const tbody = document.getElementById('vpc-peers-tbody');
            tbody.innerHTML = '';

            if (!peers || peers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No VPC peers configured</td></tr>';
                return;
            }

            peers.forEach(peer => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${peer.vpc_id}</strong></td>
                    <td>${peer.port}</td>
                    <td>${getStatusBadge(peer.status)}</td>
                    <td>${getStatusBadge(peer.consistency_status)}</td>
                    <td><small>${peer.reason}</small></td>
                    <td><small>${peer.active_vlans}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="suspendVpc('${peer.vpc_id}')">
                            Suspend
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteVpcPeer('${peer.vpc_id}')">
                            Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayVpcVlans(vlans) {
            const tbody = document.getElementById('vpc-vlans-tbody');
            tbody.innerHTML = '';

            if (!vlans || vlans.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No VPC VLANs found</td></tr>';
                return;
            }

            vlans.forEach(vlan => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${vlan.vlan}</strong></td>
                    <td>${getStatusBadge(vlan.status)}</td>
                    <td>${getStatusBadge(vlan.consistency_status)}</td>
                    <td><small>${vlan.local_suspended}</small></td>
                    <td><small>${vlan.remote_suspended}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="suspendVlan('${vlan.vlan}')">
                            Suspend
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="resumeVlan('${vlan.vlan}')">
                            Resume
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayConsistencyCheck(consistency) {
            const tbody = document.getElementById('consistency-tbody');
            tbody.innerHTML = '';

            if (!consistency || consistency.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No consistency check data available</td></tr>';
                return;
            }

            consistency.forEach(check => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${check.parameter}</strong></td>
                    <td>${check.local_value}</td>
                    <td>${check.peer_value}</td>
                    <td>${getStatusBadge(check.status)}</td>
                    <td><small>${check.reason}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="fixConsistency('${check.parameter}')">
                            Fix
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function getStatusBadge(status) {
            const statusLower = status.toLowerCase();
            
            if (statusLower.includes('up') || statusLower.includes('success') || statusLower.includes('ok')) {
                return `<span class="badge bg-success">${status}</span>`;
            } else if (statusLower.includes('down') || statusLower.includes('error') || statusLower.includes('failed')) {
                return `<span class="badge bg-danger">${status}</span>`;
            } else if (statusLower.includes('suspend') || statusLower.includes('warning')) {
                return `<span class="badge bg-warning">${status}</span>`;
            } else {
                return `<span class="badge bg-secondary">${status}</span>`;
            }
        }

        function getRoleBadge(role) {
            const roleLower = role.toLowerCase();
            
            if (roleLower.includes('primary')) {
                return `<span class="badge bg-primary">${role}</span>`;
            } else if (roleLower.includes('secondary')) {
                return `<span class="badge bg-info">${role}</span>`;
            } else {
                return `<span class="badge bg-secondary">${role}</span>`;
            }
        }

        function loadInterfaceList() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show interface brief'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parseInterfaceList(data.result);
                    populateInterfaceSelects(interfaces);
                }
            })
            .catch(error => console.error('Error loading interface list:', error));
        }

        function parseInterfaceList(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_interface) {
                        let rows = output.body.TABLE_interface.ROW_interface;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            if (row.interface && (row.interface.startsWith('Ethernet') || row.interface.startsWith('port-channel'))) {
                                interfaces.push(row.interface);
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing interface list:', e);
            }
            
            return interfaces;
        }

        function populateInterfaceSelects(interfaces) {
            const selects = ['vpc-interface', 'peer-link-interface'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                
                interfaces.forEach(intf => {
                    const option = document.createElement('option');
                    option.value = intf;
                    option.textContent = intf;
                    select.appendChild(option);
                });
            });
        }

        function createVpc() {
            const vpcId = document.getElementById('vpc-id').value;
            const interfaceName = document.getElementById('vpc-interface').value;
            const mode = document.getElementById('vpc-mode').value;
            const priority = document.getElementById('vpc-priority').value;

            if (!vpcId || !interfaceName) {
                alert('Please provide VPC ID and Interface');
                return;
            }

            let commands = [
                `interface ${interfaceName}`,
                `vpc ${vpcId}`
            ];

            if (mode && mode !== 'active') {
                commands.push(`lacp mode ${mode}`);
            }

            if (priority) {
                commands.push(`lacp port-priority ${priority}`);
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: configCmd,
                    type: 'cli_conf'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error creating VPC: ' + data.error);
                } else {
                    alert('VPC created successfully');
                    loadVpcPeers();
                }
            })
            .catch(error => {
                alert('Error creating VPC: ' + error.message);
            });
        }

        function deleteVpc() {
            const vpcId = document.getElementById('vpc-id').value;
            const interfaceName = document.getElementById('vpc-interface').value;

            if (!vpcId || !interfaceName) {
                alert('Please provide VPC ID and Interface');
                return;
            }

            if (confirm(`Delete VPC ${vpcId} on ${interfaceName}?`)) {
                const configCmd = `interface ${interfaceName} ; no vpc ${vpcId}`;
                
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: configCmd,
                        type: 'cli_conf'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error deleting VPC: ' + data.error);
                    } else {
                        alert('VPC deleted successfully');
                        loadVpcPeers();
                    }
                })
                .catch(error => {
                    alert('Error deleting VPC: ' + error.message);
                });
            }
        }

        function applyVpcDomainConfig() {
            const domainId = document.getElementById('domain-id-config').value;
            const systemPriority = document.getElementById('system-priority').value;
            const systemMac = document.getElementById('system-mac-config').value;
            const peerSwitch = document.getElementById('peer-switch').value;
            const peerLinkInterface = document.getElementById('peer-link-interface').value;
            const keepaliveDest = document.getElementById('peer-keepalive-dest').value;
            const keepaliveSrc = document.getElementById('peer-keepalive-src').value;
            const keepaliveVrf = document.getElementById('peer-keepalive-vrf').value;
            const keepaliveInterval = document.getElementById('peer-keepalive-interval').value;
            const keepaliveTimeout = document.getElementById('peer-keepalive-timeout').value;
            const holdTimeout = document.getElementById('peer-keepalive-hold-timeout').value;
            const autoRecovery = document.getElementById('auto-recovery').checked;
            const autoRecoveryInterval = document.getElementById('auto-recovery-interval').value;

            if (!domainId) {
                alert('Please provide Domain ID');
                return;
            }

            let commands = [`vpc domain ${domainId}`];
            
            if (systemPriority) {
                commands.push(`system-priority ${systemPriority}`);
            }
            
            if (systemMac) {
                commands.push(`system-mac ${systemMac}`);
            }
            
            if (peerSwitch) {
                commands.push('peer-switch');
            }
            
            if (keepaliveDest) {
                let keepaliveCmd = `peer-keepalive destination ${keepaliveDest}`;
                
                if (keepaliveSrc) {
                    keepaliveCmd += ` source ${keepaliveSrc}`;
                }
                
                if (keepaliveVrf) {
                    keepaliveCmd += ` vrf ${keepaliveVrf}`;
                }
                
                commands.push(keepaliveCmd);
            }
            
            if (keepaliveInterval) {
                commands.push(`peer-keepalive interval ${keepaliveInterval}`);
            }
            
            if (keepaliveTimeout) {
                commands.push(`peer-keepalive timeout ${keepaliveTimeout}`);
            }
            
            if (holdTimeout) {
                commands.push(`peer-keepalive hold-timeout ${holdTimeout}`);
            }
            
            if (autoRecovery) {
                let recoveryCmd = 'auto-recovery';
                if (autoRecoveryInterval) {
                    recoveryCmd += ` reload-delay ${autoRecoveryInterval}`;
                }
                commands.push(recoveryCmd);
            }

            // Configure peer-link if specified
            if (peerLinkInterface) {
                commands.push(`interface ${peerLinkInterface}`);
                commands.push('vpc peer-link');
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: configCmd,
                    type: 'cli_conf'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error applying VPC domain configuration: ' + data.error);
                } else {
                    alert('VPC domain configuration applied successfully');
                    loadVpcData();
                }
            })
            .catch(error => {
                alert('Error applying VPC domain configuration: ' + error.message);
            });
        }

        function enableVpcFeature() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'feature vpc'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error enabling VPC feature: ' + data.error);
                } else {
                    alert('VPC feature enabled successfully');
                    loadVpcData();
                }
            })
            .catch(error => {
                alert('Error enabling VPC feature: ' + error.message);
            });
        }

        function disableVpcFeature() {
            if (confirm('Are you sure you want to disable VPC feature? This will remove all VPC configurations.')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'no feature vpc'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error disabling VPC feature: ' + data.error);
                    } else {
                        alert('VPC feature disabled successfully');
                        loadVpcData();
                    }
                })
                .catch(error => {
                    alert('Error disabling VPC feature: ' + error.message);
                });
            }
        }

        function reloadVpcDomain() {
            if (confirm('Are you sure you want to reload the VPC domain? This may cause temporary disruption.')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'vpc domain reload'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error reloading VPC domain: ' + data.error);
                    } else {
                        alert('VPC domain reload initiated');
                        setTimeout(() => loadVpcData(), 5000); // Reload data after 5 seconds
                    }
                })
                .catch(error => {
                    alert('Error reloading VPC domain: ' + error.message);
                });
            }
        }

        function runConsistencyCheck() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'vpc consistency-check'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error running consistency check: ' + data.error);
                } else {
                    alert('Consistency check completed');
                    loadConsistencyCheck();
                }
            })
            .catch(error => {
                alert('Error running consistency check: ' + error.message);
            });
        }

        function suspendInconsistentVlans() {
            if (confirm('Suspend all inconsistent VLANs?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'vpc suspend inconsistent-vlans'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error suspending inconsistent VLANs: ' + data.error);
                    } else {
                        alert('Inconsistent VLANs suspended');
                        loadVpcVlans();
                    }
                })
                .catch(error => {
                    alert('Error suspending inconsistent VLANs: ' + error.message);
                });
            }
        }

        function resumeVlans() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'no vpc suspend inconsistent-vlans'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error resuming VLANs: ' + data.error);
                } else {
                    alert('VLANs resumed');
                    loadVpcVlans();
                }
            })
            .catch(error => {
                alert('Error resuming VLANs: ' + error.message);
            });
        }

        function clearConsistencyCheck() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'clear vpc consistency-check'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error clearing consistency check: ' + data.error);
                } else {
                    alert('Consistency check results cleared');
                    loadConsistencyCheck();
                }
            })
            .catch(error => {
                alert('Error clearing consistency check: ' + error.message);
            });
        }

        function suspendVpc(vpcId) {
            if (confirm(`Suspend VPC ${vpcId}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `vpc ${vpcId} suspend`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error suspending VPC: ' + data.error);
                    } else {
                        alert('VPC suspended successfully');
                        loadVpcPeers();
                    }
                })
                .catch(error => {
                    alert('Error suspending VPC: ' + error.message);
                });
            }
        }

        function deleteVpcPeer(vpcId) {
            if (confirm(`Delete VPC ${vpcId}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `no vpc ${vpcId}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error deleting VPC: ' + data.error);
                    } else {
                        alert('VPC deleted successfully');
                        loadVpcPeers();
                    }
                })
                .catch(error => {
                    alert('Error deleting VPC: ' + error.message);
                });
            }
        }

        function suspendVlan(vlanId) {
            if (confirm(`Suspend VLAN ${vlanId} in VPC?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `vpc suspend vlan ${vlanId}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error suspending VLAN: ' + data.error);
                    } else {
                        alert('VLAN suspended in VPC');
                        loadVpcVlans();
                    }
                })
                .catch(error => {
                    alert('Error suspending VLAN: ' + error.message);
                });
            }
        }

        function resumeVlan(vlanId) {
            if (confirm(`Resume VLAN ${vlanId} in VPC?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `no vpc suspend vlan ${vlanId}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error resuming VLAN: ' + data.error);
                    } else {
                        alert('VLAN resumed in VPC');
                        loadVpcVlans();
                    }
                })
                .catch(error => {
                    alert('Error resuming VLAN: ' + error.message);
                });
            }
        }

        function fixConsistency(parameter) {
            alert(`Consistency fix for ${parameter} would require manual configuration based on the specific parameter and values shown.`);
        }

        function configureVpc() {
            // Switch to configuration tab
            const configTab = new bootstrap.Tab(document.getElementById('config-tab'));
            configTab.show();
            
            document.querySelector('#vpcDomainConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadVpcData();
        }
    </script>
</body>
</html>

