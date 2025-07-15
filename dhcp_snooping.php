<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHCP Snooping - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shield-alt"></i> DHCP Snooping Configuration</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureDhcpSnooping()">
                    <i class="fas fa-cog"></i> Configure DHCP Snooping
                </button>
            </div>
        </div>

        <!-- DHCP Snooping Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Global Status</h5>
                        <h2 id="global-status">Disabled</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Trusted Interfaces</h5>
                        <h2 id="trusted-interfaces">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Binding Entries</h5>
                        <h2 id="binding-entries">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Violations</h5>
                        <h2 id="violations">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- DHCP Snooping Tabs -->
        <ul class="nav nav-tabs" id="dhcpSnoopingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">
                    <i class="fas fa-info-circle"></i> Status
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="interfaces-tab" data-bs-toggle="tab" data-bs-target="#interfaces" type="button" role="tab">
                    <i class="fas fa-network-wired"></i> Interfaces
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="binding-tab" data-bs-toggle="tab" data-bs-target="#binding" type="button" role="tab">
                    <i class="fas fa-link"></i> Binding Table
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="statistics-tab" data-bs-toggle="tab" data-bs-target="#statistics" type="button" role="tab">
                    <i class="fas fa-chart-bar"></i> Statistics
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Configuration
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dhcpSnoopingTabContent">
            <!-- Status Tab -->
            <div class="tab-pane fade show active" id="status" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> DHCP Snooping Global Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>DHCP Snooping:</strong></td>
                                        <td id="dhcp-snooping-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Information Option:</strong></td>
                                        <td id="dhcp-info-option">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Information Option Allow-untrusted:</strong></td>
                                        <td id="dhcp-allow-untrusted">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Rate Limit:</strong></td>
                                        <td id="dhcp-rate-limit">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>DHCP Snooping Verify MAC Address:</strong></td>
                                        <td id="dhcp-verify-mac">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Database Agent:</strong></td>
                                        <td id="dhcp-database-agent">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Database Timeout:</strong></td>
                                        <td id="dhcp-database-timeout">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Snooping Database Write-delay:</strong></td>
                                        <td id="dhcp-write-delay">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-layer-group"></i> VLAN Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>VLAN</th>
                                        <th>DHCP Snooping</th>
                                        <th>Trusted Interfaces</th>
                                        <th>Untrusted Interfaces</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="vlan-config-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading VLAN configuration...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interfaces Tab -->
            <div class="tab-pane fade" id="interfaces" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> Interface Trust Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Trust State</th>
                                        <th>Rate Limit (pps)</th>
                                        <th>Burst Interval</th>
                                        <th>VLAN</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="interfaces-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading interface configuration...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Configure Interface Trust</h5>
                    </div>
                    <div class="card-body">
                        <form id="interfaceTrustForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="trust-interface" class="form-label">Interface</label>
                                    <select class="form-select" id="trust-interface" required>
                                        <option value="">Select Interface</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="trust-state" class="form-label">Trust State</label>
                                    <select class="form-select" id="trust-state">
                                        <option value="trusted">Trusted</option>
                                        <option value="untrusted">Untrusted</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="rate-limit" class="form-label">Rate Limit (pps)</label>
                                    <input type="number" class="form-control" id="rate-limit" min="1" max="2048" placeholder="100">
                                </div>
                                <div class="col-md-3">
                                    <label for="burst-interval" class="form-label">Burst Interval (s)</label>
                                    <input type="number" class="form-control" id="burst-interval" min="1" max="32" placeholder="1">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="configureInterfaceTrust()">
                                        Apply Configuration
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="removeInterfaceTrust()">
                                        Remove Trust
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Binding Table Tab -->
            <div class="tab-pane fade" id="binding" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-link"></i> DHCP Snooping Binding Table</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>MAC Address</th>
                                        <th>IP Address</th>
                                        <th>Lease (sec)</th>
                                        <th>Type</th>
                                        <th>VLAN</th>
                                        <th>Interface</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="binding-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading binding table...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Add Static Binding</h5>
                    </div>
                    <div class="card-body">
                        <form id="staticBindingForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="binding-mac" class="form-label">MAC Address</label>
                                    <input type="text" class="form-control" id="binding-mac" placeholder="00:11:22:33:44:55" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="binding-ip" class="form-label">IP Address</label>
                                    <input type="text" class="form-control" id="binding-ip" placeholder="192.168.1.100" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="binding-vlan" class="form-label">VLAN</label>
                                    <input type="number" class="form-control" id="binding-vlan" min="1" max="4094" placeholder="100" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="binding-interface" class="form-label">Interface</label>
                                    <select class="form-select" id="binding-interface" required>
                                        <option value="">Select Interface</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="binding-lease" class="form-label">Lease (sec)</label>
                                    <input type="number" class="form-control" id="binding-lease" min="60" max="4294967295" placeholder="86400">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="addStaticBinding()">
                                        Add Static Binding
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="clearBindingTable()">
                                        Clear Binding Table
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div class="tab-pane fade" id="statistics" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> DHCP Snooping Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Packet Statistics</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Total Packets Forwarded:</strong></td>
                                        <td id="packets-forwarded">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Packets Dropped:</strong></td>
                                        <td id="packets-dropped">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Discover Packets:</strong></td>
                                        <td id="discover-packets">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Offer Packets:</strong></td>
                                        <td id="offer-packets">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Request Packets:</strong></td>
                                        <td id="request-packets">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP ACK Packets:</strong></td>
                                        <td id="ack-packets">0</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Security Statistics</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>MAC Verification Failures:</strong></td>
                                        <td id="mac-failures">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DHCP Server Violations:</strong></td>
                                        <td id="server-violations">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Option 82 Failures:</strong></td>
                                        <td id="option82-failures">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rate Limit Violations:</strong></td>
                                        <td id="rate-limit-violations">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Binding Violations:</strong></td>
                                        <td id="binding-violations">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Violations:</strong></td>
                                        <td id="total-violations">0</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-warning" onclick="clearStatistics()">
                                    <i class="fas fa-eraser"></i> Clear Statistics
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
                        <h5><i class="fas fa-cogs"></i> DHCP Snooping Global Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="dhcpSnoopingConfigForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Basic Settings</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-dhcp-snooping">
                                            <label class="form-check-label" for="enable-dhcp-snooping">
                                                Enable DHCP Snooping
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="verify-mac-address">
                                            <label class="form-check-label" for="verify-mac-address">
                                                Verify MAC Address
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="information-option">
                                            <label class="form-check-label" for="information-option">
                                                Information Option (Option 82)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow-untrusted">
                                            <label class="form-check-label" for="allow-untrusted">
                                                Allow Untrusted
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Rate Limiting</h6>
                                    <div class="mb-3">
                                        <label for="global-rate-limit" class="form-label">Global Rate Limit (pps)</label>
                                        <input type="number" class="form-control" id="global-rate-limit" min="1" max="2048" placeholder="100">
                                    </div>
                                    <div class="mb-3">
                                        <label for="global-burst-interval" class="form-label">Global Burst Interval (s)</label>
                                        <input type="number" class="form-control" id="global-burst-interval" min="1" max="32" placeholder="1">
                                    </div>
                                    <h6>Database Settings</h6>
                                    <div class="mb-3">
                                        <label for="database-timeout" class="form-label">Database Timeout (s)</label>
                                        <input type="number" class="form-control" id="database-timeout" min="0" max="86400" placeholder="300">
                                    </div>
                                    <div class="mb-3">
                                        <label for="write-delay" class="form-label">Write Delay (s)</label>
                                        <input type="number" class="form-control" id="write-delay" min="15" max="86400" placeholder="300">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>VLAN Configuration</h6>
                                    <div class="mb-3">
                                        <label for="snooping-vlans" class="form-label">Enable DHCP Snooping on VLANs</label>
                                        <input type="text" class="form-control" id="snooping-vlans" placeholder="1,10,20,100-200">
                                        <div class="form-text">Enter VLAN IDs separated by commas or ranges (e.g., 1,10,20,100-200)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyDhcpSnoopingConfig()">
                                        Apply Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="enableDhcpSnooping()">
                                        Enable DHCP Snooping
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="disableDhcpSnooping()">
                                        Disable DHCP Snooping
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="resetDhcpSnooping()">
                                        Reset Configuration
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
        let dhcpSnoopingData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadDhcpSnoopingData();
            loadInterfaceList();
        });

        function loadDhcpSnoopingData() {
            loadDhcpSnoopingStatus();
            loadInterfaceConfig();
            loadBindingTable();
            loadStatistics();
        }

        function loadDhcpSnoopingStatus() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip dhcp snooping'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const status = parseDhcpSnoopingStatus(data);
                displayDhcpSnoopingStatus(status);
            })
            .catch(error => {
                console.error('Error loading DHCP snooping status:', error);
                displayDhcpSnoopingStatusError(error.message);
            });

            // Load VLAN configuration
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip dhcp snooping vlan'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const vlanConfig = parseVlanConfig(data);
                    displayVlanConfig(vlanConfig);
                }
            })
            .catch(error => console.error('Error loading VLAN configuration:', error));
        }

        function loadInterfaceConfig() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip dhcp snooping interface'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const interfaces = parseInterfaceConfig(data);
                displayInterfaceConfig(interfaces);
                document.getElementById('trusted-interfaces').textContent = 
                    interfaces.filter(intf => intf.trust_state === 'trusted').length;
            })
            .catch(error => {
                console.error('Error loading interface configuration:', error);
                document.getElementById('interfaces-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadBindingTable() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip dhcp snooping binding'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const bindings = parseBindingTable(data);
                displayBindingTable(bindings);
                document.getElementById('binding-entries').textContent = bindings.length;
            })
            .catch(error => {
                console.error('Error loading binding table:', error);
                document.getElementById('binding-tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadStatistics() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip dhcp snooping statistics'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const statistics = parseStatistics(data);
                displayStatistics(statistics);
                document.getElementById('violations').textContent = statistics.total_violations || 0;
            })
            .catch(error => {
                console.error('Error loading statistics:', error);
                // Set default values for statistics
                document.getElementById('violations').textContent = '0';
            });
        }

        function parseDhcpSnoopingStatus(data) {
            const status = {
                global_status: 'Disabled',
                info_option: 'Disabled',
                allow_untrusted: 'Disabled',
                rate_limit: 'None',
                verify_mac: 'Disabled',
                database_agent: 'None',
                database_timeout: '300',
                write_delay: '300'
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        status.global_status = output.body.dhcp_snooping_enabled || 'Disabled';
                        status.info_option = output.body.dhcp_snooping_info_option || 'Disabled';
                        status.allow_untrusted = output.body.dhcp_snooping_allow_untrusted || 'Disabled';
                        status.rate_limit = output.body.dhcp_snooping_rate_limit || 'None';
                        status.verify_mac = output.body.dhcp_snooping_verify_mac || 'Disabled';
                        status.database_agent = output.body.dhcp_snooping_database_agent || 'None';
                        status.database_timeout = output.body.dhcp_snooping_database_timeout || '300';
                        status.write_delay = output.body.dhcp_snooping_write_delay || '300';
                    }
                }
            } catch (e) {
                console.error('Error parsing DHCP snooping status:', e);
            }
            
            return status;
        }

        function parseVlanConfig(data) {
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
                                dhcp_snooping: row.dhcp_snooping_enabled || 'Disabled',
                                trusted_interfaces: row.trusted_interfaces || '',
                                untrusted_interfaces: row.untrusted_interfaces || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing VLAN configuration:', e);
            }
            
            return vlans;
        }

        function parseInterfaceConfig(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_interface) {
                        let rows = output.body.TABLE_interface.ROW_interface;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            interfaces.push({
                                interface: row.interface_name || '',
                                trust_state: row.trust_state || 'untrusted',
                                rate_limit: row.rate_limit || 'None',
                                burst_interval: row.burst_interval || 'None',
                                vlan: row.vlan || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing interface configuration:', e);
            }
            
            return interfaces;
        }

        function parseBindingTable(data) {
            const bindings = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_binding) {
                        let rows = output.body.TABLE_binding.ROW_binding;
                        if (!Array.isArray(rows)) rows = [rows];
                        
                        rows.forEach(row => {
                            bindings.push({
                                mac_address: row.mac_address || '',
                                ip_address: row.ip_address || '',
                                lease_time: row.lease_time || '',
                                type: row.binding_type || 'dynamic',
                                vlan: row.vlan || '',
                                interface: row.interface || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing binding table:', e);
            }
            
            return bindings;
        }

        function parseStatistics(data) {
            const statistics = {
                packets_forwarded: 0,
                packets_dropped: 0,
                discover_packets: 0,
                offer_packets: 0,
                request_packets: 0,
                ack_packets: 0,
                mac_failures: 0,
                server_violations: 0,
                option82_failures: 0,
                rate_limit_violations: 0,
                binding_violations: 0,
                total_violations: 0
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        statistics.packets_forwarded = output.body.packets_forwarded || 0;
                        statistics.packets_dropped = output.body.packets_dropped || 0;
                        statistics.discover_packets = output.body.discover_packets || 0;
                        statistics.offer_packets = output.body.offer_packets || 0;
                        statistics.request_packets = output.body.request_packets || 0;
                        statistics.ack_packets = output.body.ack_packets || 0;
                        statistics.mac_failures = output.body.mac_failures || 0;
                        statistics.server_violations = output.body.server_violations || 0;
                        statistics.option82_failures = output.body.option82_failures || 0;
                        statistics.rate_limit_violations = output.body.rate_limit_violations || 0;
                        statistics.binding_violations = output.body.binding_violations || 0;
                        statistics.total_violations = output.body.total_violations || 0;
                    }
                }
            } catch (e) {
                console.error('Error parsing statistics:', e);
            }
            
            return statistics;
        }

        function displayDhcpSnoopingStatus(status) {
            document.getElementById('global-status').textContent = status.global_status;
            
            document.getElementById('dhcp-snooping-status').innerHTML = getStatusBadge(status.global_status);
            document.getElementById('dhcp-info-option').innerHTML = getStatusBadge(status.info_option);
            document.getElementById('dhcp-allow-untrusted').innerHTML = getStatusBadge(status.allow_untrusted);
            document.getElementById('dhcp-rate-limit').textContent = status.rate_limit;
            document.getElementById('dhcp-verify-mac').innerHTML = getStatusBadge(status.verify_mac);
            document.getElementById('dhcp-database-agent').textContent = status.database_agent;
            document.getElementById('dhcp-database-timeout').textContent = status.database_timeout + ' seconds';
            document.getElementById('dhcp-write-delay').textContent = status.write_delay + ' seconds';
        }

        function displayDhcpSnoopingStatusError(error) {
            document.getElementById('global-status').textContent = 'Error';
            document.getElementById('dhcp-snooping-status').innerHTML = '<span class="badge bg-danger">Error: ' + error + '</span>';
        }

        function displayVlanConfig(vlans) {
            const tbody = document.getElementById('vlan-config-tbody');
            tbody.innerHTML = '';

            if (!vlans || vlans.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No VLAN configuration found</td></tr>';
                return;
            }

            vlans.forEach(vlan => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td><strong>${vlan.vlan}</strong></td>
                    <td>${getStatusBadge(vlan.dhcp_snooping)}</td>
                    <td><small>${vlan.trusted_interfaces}</small></td>
                    <td><small>${vlan.untrusted_interfaces}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="configureVlan('${vlan.vlan}')">
                            Configure
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayInterfaceConfig(interfaces) {
            const tbody = document.getElementById('interfaces-tbody');
            tbody.innerHTML = '';

            if (!interfaces || interfaces.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No interface configuration found</td></tr>';
                return;
            }

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                let trustBadge = intf.trust_state === 'trusted' ? 
                    '<span class="badge bg-success">Trusted</span>' : 
                    '<span class="badge bg-warning">Untrusted</span>';

                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>${trustBadge}</td>
                    <td>${intf.rate_limit}</td>
                    <td>${intf.burst_interval}</td>
                    <td>${intf.vlan}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editInterfaceTrust('${intf.interface}')">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeInterfaceTrustConfig('${intf.interface}')">
                            Remove
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayBindingTable(bindings) {
            const tbody = document.getElementById('binding-tbody');
            tbody.innerHTML = '';

            if (!bindings || bindings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No binding entries found</td></tr>';
                return;
            }

            bindings.forEach(binding => {
                const row = document.createElement('tr');
                
                let typeBadge = binding.type === 'static' ? 
                    '<span class="badge bg-info">Static</span>' : 
                    '<span class="badge bg-secondary">Dynamic</span>';

                row.innerHTML = `
                    <td><strong>${binding.mac_address}</strong></td>
                    <td>${binding.ip_address}</td>
                    <td>${binding.lease_time}</td>
                    <td>${typeBadge}</td>
                    <td>${binding.vlan}</td>
                    <td>${binding.interface}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeBinding('${binding.mac_address}', '${binding.ip_address}')">
                            Remove
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayStatistics(statistics) {
            document.getElementById('packets-forwarded').textContent = statistics.packets_forwarded;
            document.getElementById('packets-dropped').textContent = statistics.packets_dropped;
            document.getElementById('discover-packets').textContent = statistics.discover_packets;
            document.getElementById('offer-packets').textContent = statistics.offer_packets;
            document.getElementById('request-packets').textContent = statistics.request_packets;
            document.getElementById('ack-packets').textContent = statistics.ack_packets;
            document.getElementById('mac-failures').textContent = statistics.mac_failures;
            document.getElementById('server-violations').textContent = statistics.server_violations;
            document.getElementById('option82-failures').textContent = statistics.option82_failures;
            document.getElementById('rate-limit-violations').textContent = statistics.rate_limit_violations;
            document.getElementById('binding-violations').textContent = statistics.binding_violations;
            document.getElementById('total-violations').textContent = statistics.total_violations;
        }

        function getStatusBadge(status) {
            const statusLower = status.toLowerCase();
            
            if (statusLower.includes('enabled') || statusLower.includes('trusted')) {
                return `<span class="badge bg-success">${status}</span>`;
            } else if (statusLower.includes('disabled') || statusLower.includes('untrusted')) {
                return `<span class="badge bg-warning">${status}</span>`;
            } else {
                return `<span class="badge bg-secondary">${status}</span>`;
            }
        }

        function loadInterfaceList() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show interface brief'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parseInterfaceList(data);
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
                            if (row.interface && (row.interface.startsWith('Ethernet') || row.interface.startsWith('Vlan'))) {
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
            const selects = ['trust-interface', 'binding-interface'];
            
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

        function configureInterfaceTrust() {
            const interfaceName = document.getElementById('trust-interface').value;
            const trustState = document.getElementById('trust-state').value;
            const rateLimit = document.getElementById('rate-limit').value;
            const burstInterval = document.getElementById('burst-interval').value;

            if (!interfaceName) {
                alert('Please select an interface');
                return;
            }

            let commands = [`interface ${interfaceName}`];
            
            if (trustState === 'trusted') {
                commands.push('ip dhcp snooping trust');
            } else {
                commands.push('no ip dhcp snooping trust');
            }
            
            if (rateLimit) {
                let rateLimitCmd = `ip dhcp snooping limit rate ${rateLimit}`;
                if (burstInterval) {
                    rateLimitCmd += ` burst interval ${burstInterval}`;
                }
                commands.push(rateLimitCmd);
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error configuring interface trust: ' + data.error);
                } else {
                    alert('Interface trust configured successfully');
                    loadInterfaceConfig();
                }
            })
            .catch(error => {
                alert('Error configuring interface trust: ' + error.message);
            });
        }

        function removeInterfaceTrust() {
            const interfaceName = document.getElementById('trust-interface').value;

            if (!interfaceName) {
                alert('Please select an interface');
                return;
            }

            if (confirm(`Remove DHCP snooping configuration from ${interfaceName}?`)) {
                const configCmd = `interface ${interfaceName} ; no ip dhcp snooping trust ; no ip dhcp snooping limit rate`;
                
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing interface trust: ' + data.error);
                    } else {
                        alert('Interface trust removed successfully');
                        loadInterfaceConfig();
                    }
                })
                .catch(error => {
                    alert('Error removing interface trust: ' + error.message);
                });
            }
        }

        function addStaticBinding() {
            const macAddress = document.getElementById('binding-mac').value;
            const ipAddress = document.getElementById('binding-ip').value;
            const vlan = document.getElementById('binding-vlan').value;
            const interfaceName = document.getElementById('binding-interface').value;
            const lease = document.getElementById('binding-lease').value;

            if (!macAddress || !ipAddress || !vlan || !interfaceName) {
                alert('Please provide MAC address, IP address, VLAN, and interface');
                return;
            }

            let command = `ip dhcp snooping binding ${macAddress} vlan ${vlan} ${ipAddress} interface ${interfaceName}`;
            
            if (lease) {
                command += ` expiry ${lease}`;
            }

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error adding static binding: ' + data.error);
                } else {
                    alert('Static binding added successfully');
                    loadBindingTable();
                }
            })
            .catch(error => {
                alert('Error adding static binding: ' + error.message);
            });
        }

        function clearBindingTable() {
            if (confirm('Clear all DHCP snooping binding entries?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=clear ip dhcp snooping binding *'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing binding table: ' + data.error);
                    } else {
                        alert('Binding table cleared successfully');
                        loadBindingTable();
                    }
                })
                .catch(error => {
                    alert('Error clearing binding table: ' + error.message);
                });
            }
        }

        function applyDhcpSnoopingConfig() {
            const enableSnooping = document.getElementById('enable-dhcp-snooping').checked;
            const verifyMac = document.getElementById('verify-mac-address').checked;
            const infoOption = document.getElementById('information-option').checked;
            const allowUntrusted = document.getElementById('allow-untrusted').checked;
            const globalRateLimit = document.getElementById('global-rate-limit').value;
            const globalBurstInterval = document.getElementById('global-burst-interval').value;
            const databaseTimeout = document.getElementById('database-timeout').value;
            const writeDelay = document.getElementById('write-delay').value;
            const snoopingVlans = document.getElementById('snooping-vlans').value;

            let commands = [];
            
            if (enableSnooping) {
                commands.push('ip dhcp snooping');
            }
            
            if (verifyMac) {
                commands.push('ip dhcp snooping verify mac-address');
            }
            
            if (infoOption) {
                commands.push('ip dhcp snooping information option');
                
                if (allowUntrusted) {
                    commands.push('ip dhcp snooping information option allow-untrusted');
                }
            }
            
            if (globalRateLimit) {
                let rateLimitCmd = `ip dhcp snooping limit rate ${globalRateLimit}`;
                if (globalBurstInterval) {
                    rateLimitCmd += ` burst interval ${globalBurstInterval}`;
                }
                commands.push(rateLimitCmd);
            }
            
            if (databaseTimeout) {
                commands.push(`ip dhcp snooping database timeout ${databaseTimeout}`);
            }
            
            if (writeDelay) {
                commands.push(`ip dhcp snooping database write-delay ${writeDelay}`);
            }
            
            if (snoopingVlans) {
                commands.push(`ip dhcp snooping vlan ${snoopingVlans}`);
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error applying DHCP snooping configuration: ' + data.error);
                } else {
                    alert('DHCP snooping configuration applied successfully');
                    loadDhcpSnoopingData();
                }
            })
            .catch(error => {
                alert('Error applying DHCP snooping configuration: ' + error.message);
            });
        }

        function enableDhcpSnooping() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=ip dhcp snooping&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error enabling DHCP snooping: ' + data.error);
                } else {
                    alert('DHCP snooping enabled successfully');
                    loadDhcpSnoopingStatus();
                }
            })
            .catch(error => {
                alert('Error enabling DHCP snooping: ' + error.message);
            });
        }

        function disableDhcpSnooping() {
            if (confirm('Are you sure you want to disable DHCP snooping?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=no ip dhcp snooping&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error disabling DHCP snooping: ' + data.error);
                    } else {
                        alert('DHCP snooping disabled successfully');
                        loadDhcpSnoopingStatus();
                    }
                })
                .catch(error => {
                    alert('Error disabling DHCP snooping: ' + error.message);
                });
            }
        }

        function resetDhcpSnooping() {
            if (confirm('Are you sure you want to reset DHCP snooping configuration?')) {
                const commands = [
                    'no ip dhcp snooping',
                    'no ip dhcp snooping verify mac-address',
                    'no ip dhcp snooping information option',
                    'no ip dhcp snooping limit rate',
                    'no ip dhcp snooping database timeout',
                    'no ip dhcp snooping database write-delay'
                ];

                const configCmd = commands.join(' ; ');
                
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error resetting DHCP snooping: ' + data.error);
                    } else {
                        alert('DHCP snooping configuration reset successfully');
                        loadDhcpSnoopingData();
                    }
                })
                .catch(error => {
                    alert('Error resetting DHCP snooping: ' + error.message);
                });
            }
        }

        function clearStatistics() {
            if (confirm('Clear DHCP snooping statistics?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=clear ip dhcp snooping statistics'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing statistics: ' + data.error);
                    } else {
                        alert('Statistics cleared successfully');
                        loadStatistics();
                    }
                })
                .catch(error => {
                    alert('Error clearing statistics: ' + error.message);
                });
            }
        }

        function removeBinding(macAddress, ipAddress) {
            if (confirm(`Remove binding for ${macAddress} (${ipAddress})?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=clear ip dhcp snooping binding ${macAddress}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing binding: ' + data.error);
                    } else {
                        alert('Binding removed successfully');
                        loadBindingTable();
                    }
                })
                .catch(error => {
                    alert('Error removing binding: ' + error.message);
                });
            }
        }

        function editInterfaceTrust(interfaceName) {
            document.getElementById('trust-interface').value = interfaceName;
            
            // Switch to interfaces tab
            const interfacesTab = new bootstrap.Tab(document.getElementById('interfaces-tab'));
            interfacesTab.show();
            
            document.querySelector('#interfaceTrustForm').scrollIntoView({ behavior: 'smooth' });
        }

        function removeInterfaceTrustConfig(interfaceName) {
            if (confirm(`Remove DHCP snooping configuration from ${interfaceName}?`)) {
                const configCmd = `interface ${interfaceName} ; no ip dhcp snooping trust ; no ip dhcp snooping limit rate`;
                
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing interface configuration: ' + data.error);
                    } else {
                        alert('Interface configuration removed successfully');
                        loadInterfaceConfig();
                    }
                })
                .catch(error => {
                    alert('Error removing interface configuration: ' + error.message);
                });
            }
        }

        function configureVlan(vlanId) {
            document.getElementById('snooping-vlans').value = vlanId;
            
            // Switch to configuration tab
            const configTab = new bootstrap.Tab(document.getElementById('config-tab'));
            configTab.show();
            
            document.querySelector('#dhcpSnoopingConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function configureDhcpSnooping() {
            // Switch to configuration tab
            const configTab = new bootstrap.Tab(document.getElementById('config-tab'));
            configTab.show();
            
            document.querySelector('#dhcpSnoopingConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadDhcpSnoopingData();
        }
    </script>
</body>
</html>

