<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Port Security - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-lock"></i> Port Security</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="enableBulkPortSecurity()">
                            <i class="fas fa-shield-alt"></i> Bulk Enable
                        </button>
                        <button class="btn btn-warning" onclick="clearViolations()">
                            <i class="fas fa-eraser"></i> Clear Violations
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Port Security Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-secure-ports">-</div>
                            <div class="metric-label">Secure Ports</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="secure-up-ports">-</div>
                            <div class="metric-label">Secure Up</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-danger" id="violation-ports">-</div>
                            <div class="metric-label">Violations</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="shutdown-ports">-</div>
                            <div class="metric-label">Shutdown</div>
                        </div>
                    </div>
                </div>

                <!-- Port Security Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Global Port Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="global-port-security" onchange="toggleGlobalPortSecurity()">
                                    <label class="form-check-label" for="global-port-security">
                                        <strong>Enable Global Port Security</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Default Max MAC Addresses</label>
                                <input type="number" class="form-control" id="default-max-mac" value="1" min="1" max="8192">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Default Violation Action</label>
                                <select class="form-select" id="default-violation-action">
                                    <option value="shutdown">Shutdown</option>
                                    <option value="restrict">Restrict</option>
                                    <option value="protect">Protect</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Aging Time (minutes)</label>
                                <input type="number" class="form-control" id="aging-time" value="0" min="0" max="1440">
                                <div class="form-text">0 = disabled</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button class="btn btn-primary" onclick="applyGlobalSettings()">
                                    <i class="fas fa-save"></i> Apply Global Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interface Port Security Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-table"></i> Interface Port Security Status</h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="status-filter" onchange="filterInterfaces()">
                                    <option value="">All Interfaces</option>
                                    <option value="enabled">Security Enabled</option>
                                    <option value="disabled">Security Disabled</option>
                                    <option value="violation">Has Violations</option>
                                    <option value="shutdown">Shutdown</option>
                                </select>
                                <input type="text" class="form-control form-control-sm" id="interface-search" 
                                       placeholder="Search interfaces..." onkeyup="filterInterfaces()">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="port-security-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all-interfaces" onchange="toggleSelectAllInterfaces()">
                                        </th>
                                        <th>Interface</th>
                                        <th>Port Security</th>
                                        <th>Max MAC</th>
                                        <th>Current MAC</th>
                                        <th>Violation Action</th>
                                        <th>Violations</th>
                                        <th>Last Violation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="port-security-tbody">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="loading-spinner"></div> Loading port security status...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Configure Port Security Modal -->
                <div class="modal fade" id="portSecurityModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="portSecurityModalTitle">Configure Port Security</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="port-security-form">
                                    <input type="hidden" id="config-interface">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Interface</label>
                                            <input type="text" class="form-control" id="display-interface" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="enable-port-security">
                                                <label class="form-check-label" for="enable-port-security">
                                                    <strong>Enable Port Security</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="port-security-options" style="display: none;">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Maximum MAC Addresses</label>
                                                <input type="number" class="form-control" id="max-mac-addresses" 
                                                       value="1" min="1" max="8192">
                                                <div class="form-text">1-8192 addresses</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Violation Action</label>
                                                <select class="form-select" id="violation-action">
                                                    <option value="shutdown">Shutdown (default)</option>
                                                    <option value="restrict">Restrict</option>
                                                    <option value="protect">Protect</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Aging Type</label>
                                                <select class="form-select" id="aging-type">
                                                    <option value="absolute">Absolute</option>
                                                    <option value="inactivity">Inactivity</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Aging Time (minutes)</label>
                                                <input type="number" class="form-control" id="interface-aging-time" 
                                                       value="0" min="0" max="1440">
                                                <div class="form-text">0 = disabled</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="sticky-mac">
                                                    <label class="form-check-label" for="sticky-mac">
                                                        Sticky MAC Learning
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" id="send-trap">
                                                    <label class="form-check-label" for="send-trap">
                                                        Send SNMP Trap
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fas fa-list"></i> Static MAC Addresses</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" id="static-mac-address" 
                                                               placeholder="00:11:22:33:44:55">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control" id="static-mac-vlan" 
                                                               placeholder="VLAN ID" min="1" max="4094">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-success w-100" onclick="addStaticMac()">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="static-mac-list">
                                                    <!-- Static MAC addresses will be listed here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline-primary" onclick="previewPortSecurityConfig()">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button type="button" class="btn btn-nexus" onclick="savePortSecurityConfig()">
                                    <i class="fas fa-save"></i> Apply Configuration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Port Security Modal -->
                <div class="modal fade" id="bulkPortSecurityModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Enable Port Security</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="bulk-port-security-form">
                                    <div class="mb-3">
                                        <label class="form-label">Interface Range</label>
                                        <input type="text" class="form-control" id="bulk-interface-range" 
                                               placeholder="e.g., Ethernet1/1-10 or Ethernet1/1,Ethernet1/5">
                                        <div class="form-text">Specify interface range or comma-separated list</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Max MAC Addresses</label>
                                                <input type="number" class="form-control" id="bulk-max-mac" 
                                                       value="1" min="1" max="8192">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Violation Action</label>
                                                <select class="form-select" id="bulk-violation-action">
                                                    <option value="shutdown">Shutdown</option>
                                                    <option value="restrict">Restrict</option>
                                                    <option value="protect">Protect</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="bulk-sticky-mac">
                                                <label class="form-check-label" for="bulk-sticky-mac">
                                                    Enable Sticky MAC Learning
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="bulk-send-trap">
                                                <label class="form-check-label" for="bulk-send-trap">
                                                    Send SNMP Traps
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="applyBulkPortSecurity()">
                                    <i class="fas fa-shield-alt"></i> Enable Port Security
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAC Address Table Modal -->
                <div class="modal fade" id="macTableModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="macTableModalTitle">MAC Address Table</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>MAC Address</th>
                                                <th>VLAN</th>
                                                <th>Type</th>
                                                <th>Age</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mac-table-tbody">
                                            <!-- MAC addresses will be populated here -->
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
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        let portSecurityData = [];
        let filteredInterfaces = [];
        let staticMacList = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadPortSecurityData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPortSecurityData();
            setupPortSecurityToggles();
        });

        function loadPortSecurityData() {
            executeCommand('show port-security', function(data) {
                // Mock data for demonstration
                portSecurityData = generateMockPortSecurityData();
                filteredInterfaces = [...portSecurityData];
                displayPortSecurityTable();
                updatePortSecuritySummary();
            });
        }

        function generateMockPortSecurityData() {
            return [
                {
                    interface: 'Ethernet1/1',
                    portSecurity: true,
                    maxMac: 1,
                    currentMac: 1,
                    violationAction: 'shutdown',
                    violations: 0,
                    lastViolation: null,
                    status: 'secure-up',
                    stickyMac: true,
                    agingTime: 0
                },
                {
                    interface: 'Ethernet1/2',
                    portSecurity: true,
                    maxMac: 2,
                    currentMac: 2,
                    violationAction: 'restrict',
                    violations: 1,
                    lastViolation: '2024-01-15 10:30:00',
                    status: 'secure-up',
                    stickyMac: false,
                    agingTime: 60
                },
                {
                    interface: 'Ethernet1/3',
                    portSecurity: true,
                    maxMac: 1,
                    currentMac: 0,
                    violationAction: 'shutdown',
                    violations: 2,
                    lastViolation: '2024-01-15 09:15:00',
                    status: 'secure-shutdown',
                    stickyMac: true,
                    agingTime: 0
                },
                {
                    interface: 'Ethernet1/4',
                    portSecurity: false,
                    maxMac: 0,
                    currentMac: 0,
                    violationAction: null,
                    violations: 0,
                    lastViolation: null,
                    status: 'disabled',
                    stickyMac: false,
                    agingTime: 0
                },
                {
                    interface: 'Ethernet1/5',
                    portSecurity: true,
                    maxMac: 5,
                    currentMac: 3,
                    violationAction: 'protect',
                    violations: 0,
                    lastViolation: null,
                    status: 'secure-up',
                    stickyMac: false,
                    agingTime: 120
                }
            ];
        }

        function displayPortSecurityTable() {
            const tbody = document.getElementById('port-security-tbody');
            tbody.innerHTML = '';

            filteredInterfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>
                        <input type="checkbox" class="interface-checkbox" value="${intf.interface}">
                    </td>
                    <td><strong>${intf.interface}</strong></td>
                    <td>
                        <span class="badge ${intf.portSecurity ? 'bg-success' : 'bg-secondary'}">
                            ${intf.portSecurity ? 'Enabled' : 'Disabled'}
                        </span>
                    </td>
                    <td>${intf.maxMac || '--'}</td>
                    <td>${intf.currentMac || '--'}</td>
                    <td>
                        ${intf.violationAction ? `<span class="badge ${getViolationActionBadge(intf.violationAction)}">${intf.violationAction.toUpperCase()}</span>` : '--'}
                    </td>
                    <td>
                        ${intf.violations > 0 ? `<span class="badge bg-danger">${intf.violations}</span>` : '0'}
                    </td>
                    <td>${intf.lastViolation || '--'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="configurePortSecurity('${intf.interface}')"
                                data-bs-toggle="tooltip" title="Configure">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="viewMacTable('${intf.interface}')"
                                data-bs-toggle="tooltip" title="MAC Table">
                            <i class="fas fa-list"></i>
                        </button>
                        ${intf.violations > 0 ? `
                        <button class="btn btn-sm btn-outline-warning" 
                                onclick="clearInterfaceViolations('${intf.interface}')"
                                data-bs-toggle="tooltip" title="Clear Violations">
                            <i class="fas fa-eraser"></i>
                        </button>
                        ` : ''}
                        ${intf.status === 'secure-shutdown' ? `
                        <button class="btn btn-sm btn-outline-success" 
                                onclick="enableInterface('${intf.interface}')"
                                data-bs-toggle="tooltip" title="Enable Interface">
                            <i class="fas fa-play"></i>
                        </button>
                        ` : ''}
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            initializeTooltips();
        }

        function updatePortSecuritySummary() {
            const total = portSecurityData.filter(intf => intf.portSecurity).length;
            const secureUp = portSecurityData.filter(intf => intf.status === 'secure-up').length;
            const violations = portSecurityData.filter(intf => intf.violations > 0).length;
            const shutdown = portSecurityData.filter(intf => intf.status === 'secure-shutdown').length;

            document.getElementById('total-secure-ports').textContent = total;
            document.getElementById('secure-up-ports').textContent = secureUp;
            document.getElementById('violation-ports').textContent = violations;
            document.getElementById('shutdown-ports').textContent = shutdown;
        }

        function getViolationActionBadge(action) {
            const badges = {
                'shutdown': 'bg-danger',
                'restrict': 'bg-warning',
                'protect': 'bg-info'
            };
            return badges[action] || 'bg-secondary';
        }

        function filterInterfaces() {
            const statusFilter = document.getElementById('status-filter').value;
            const searchTerm = document.getElementById('interface-search').value.toLowerCase();

            filteredInterfaces = portSecurityData.filter(intf => {
                const matchesStatus = !statusFilter || 
                    (statusFilter === 'enabled' && intf.portSecurity) ||
                    (statusFilter === 'disabled' && !intf.portSecurity) ||
                    (statusFilter === 'violation' && intf.violations > 0) ||
                    (statusFilter === 'shutdown' && intf.status === 'secure-shutdown');
                
                const matchesSearch = intf.interface.toLowerCase().includes(searchTerm);
                
                return matchesStatus && matchesSearch;
            });

            displayPortSecurityTable();
        }

        function setupPortSecurityToggles() {
            document.getElementById('enable-port-security').addEventListener('change', function() {
                const options = document.getElementById('port-security-options');
                options.style.display = this.checked ? 'block' : 'none';
            });
        }

        function toggleSelectAllInterfaces() {
            const selectAll = document.getElementById('select-all-interfaces');
            const checkboxes = document.querySelectorAll('.interface-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function configurePortSecurity(interfaceName) {
            const intf = portSecurityData.find(i => i.interface === interfaceName);
            if (!intf) return;

            document.getElementById('config-interface').value = interfaceName;
            document.getElementById('display-interface').value = interfaceName;
            document.getElementById('enable-port-security').checked = intf.portSecurity;
            
            if (intf.portSecurity) {
                document.getElementById('max-mac-addresses').value = intf.maxMac;
                document.getElementById('violation-action').value = intf.violationAction;
                document.getElementById('interface-aging-time').value = intf.agingTime;
                document.getElementById('sticky-mac').checked = intf.stickyMac;
                document.getElementById('port-security-options').style.display = 'block';
            } else {
                document.getElementById('port-security-options').style.display = 'none';
            }

            staticMacList = [];
            updateStaticMacList();

            const modal = new bootstrap.Modal(document.getElementById('portSecurityModal'));
            modal.show();
        }

        function addStaticMac() {
            const macAddress = document.getElementById('static-mac-address').value;
            const vlanId = document.getElementById('static-mac-vlan').value;

            if (!macAddress || !vlanId) {
                showAlert('Please enter both MAC address and VLAN ID.', 'warning');
                return;
            }

            if (!validateMAC(macAddress)) {
                showAlert('Please enter a valid MAC address.', 'danger');
                return;
            }

            staticMacList.push({ mac: macAddress, vlan: vlanId });
            updateStaticMacList();

            document.getElementById('static-mac-address').value = '';
            document.getElementById('static-mac-vlan').value = '';
        }

        function updateStaticMacList() {
            const container = document.getElementById('static-mac-list');
            container.innerHTML = '';

            staticMacList.forEach((entry, index) => {
                const div = document.createElement('div');
                div.className = 'alert alert-info d-flex justify-content-between align-items-center py-2';
                div.innerHTML = `
                    <span><strong>${entry.mac}</strong> on VLAN ${entry.vlan}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStaticMac(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function removeStaticMac(index) {
            staticMacList.splice(index, 1);
            updateStaticMacList();
        }

        function savePortSecurityConfig() {
            const interfaceName = document.getElementById('config-interface').value;
            const enableSecurity = document.getElementById('enable-port-security').checked;

            let commands = [`interface ${interfaceName}`];

            if (enableSecurity) {
                commands.push('switchport port-security');
                
                const maxMac = document.getElementById('max-mac-addresses').value;
                if (maxMac && maxMac !== '1') {
                    commands.push(`switchport port-security maximum ${maxMac}`);
                }

                const violationAction = document.getElementById('violation-action').value;
                if (violationAction !== 'shutdown') {
                    commands.push(`switchport port-security violation ${violationAction}`);
                }

                const agingTime = document.getElementById('interface-aging-time').value;
                if (agingTime && agingTime !== '0') {
                    const agingType = document.getElementById('aging-type').value;
                    commands.push(`switchport port-security aging time ${agingTime}`);
                    commands.push(`switchport port-security aging type ${agingType}`);
                }

                if (document.getElementById('sticky-mac').checked) {
                    commands.push('switchport port-security mac-address sticky');
                }

                // Add static MAC addresses
                staticMacList.forEach(entry => {
                    commands.push(`switchport port-security mac-address ${entry.mac} vlan ${entry.vlan}`);
                });
            } else {
                commands.push('no switchport port-security');
            }

            confirmAction(`Apply port security configuration to ${interfaceName}?\n\n${commands.join('\n')}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('Port security configuration applied successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('portSecurityModal')).hide();
                    setTimeout(loadPortSecurityData, 2000);
                }, 'cli_conf');
            });
        }

        function enableBulkPortSecurity() {
            const modal = new bootstrap.Modal(document.getElementById('bulkPortSecurityModal'));
            modal.show();
        }

        function applyBulkPortSecurity() {
            const interfaceRange = document.getElementById('bulk-interface-range').value;
            const maxMac = document.getElementById('bulk-max-mac').value;
            const violationAction = document.getElementById('bulk-violation-action').value;
            const stickyMac = document.getElementById('bulk-sticky-mac').checked;

            if (!interfaceRange) {
                showAlert('Please specify interface range.', 'danger');
                return;
            }

            let commands = [`interface range ${interfaceRange}`];
            commands.push('switchport port-security');
            
            if (maxMac !== '1') {
                commands.push(`switchport port-security maximum ${maxMac}`);
            }
            
            if (violationAction !== 'shutdown') {
                commands.push(`switchport port-security violation ${violationAction}`);
            }
            
            if (stickyMac) {
                commands.push('switchport port-security mac-address sticky');
            }

            confirmAction(`Apply bulk port security configuration?\n\n${commands.join('\n')}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('Bulk port security configuration applied successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('bulkPortSecurityModal')).hide();
                    setTimeout(loadPortSecurityData, 2000);
                }, 'cli_conf');
            });
        }

        function clearViolations() {
            confirmAction('Clear all port security violations?', function() {
                executeCommand('clear port-security all', function(data) {
                    showAlert('Port security violations cleared successfully', 'success');
                    setTimeout(loadPortSecurityData, 2000);
                });
            });
        }

        function clearInterfaceViolations(interfaceName) {
            confirmAction(`Clear port security violations on ${interfaceName}?`, function() {
                executeCommand(`clear port-security interface ${interfaceName}`, function(data) {
                    showAlert(`Violations cleared on ${interfaceName}`, 'success');
                    setTimeout(loadPortSecurityData, 2000);
                });
            });
        }

        function enableInterface(interfaceName) {
            confirmAction(`Enable interface ${interfaceName}?`, function() {
                executeCommand([`interface ${interfaceName}`, 'no shutdown'].join(' ; '), function(data) {
                    showAlert(`Interface ${interfaceName} enabled`, 'success');
                    setTimeout(loadPortSecurityData, 2000);
                }, 'cli_conf');
            });
        }

        function viewMacTable(interfaceName) {
            document.getElementById('macTableModalTitle').textContent = `MAC Address Table - ${interfaceName}`;
            
            // Mock MAC table data
            const macTableData = [
                { mac: '00:11:22:33:44:55', vlan: 1, type: 'Dynamic', age: '5 min' },
                { mac: '00:aa:bb:cc:dd:ee', vlan: 1, type: 'Static', age: '--' }
            ];

            const tbody = document.getElementById('mac-table-tbody');
            tbody.innerHTML = '';

            macTableData.forEach(entry => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><code>${entry.mac}</code></td>
                    <td>${entry.vlan}</td>
                    <td><span class="badge ${entry.type === 'Static' ? 'bg-primary' : 'bg-success'}">${entry.type}</span></td>
                    <td>${entry.age}</td>
                    <td>
                        ${entry.type === 'Dynamic' ? `
                        <button class="btn btn-sm btn-outline-danger" onclick="clearMacAddress('${entry.mac}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : '--'}
                    </td>
                `;
                tbody.appendChild(row);
            });

            const modal = new bootstrap.Modal(document.getElementById('macTableModal'));
            modal.show();
        }

        function clearMacAddress(macAddress) {
            confirmAction(`Clear MAC address ${macAddress}?`, function() {
                executeCommand(`clear mac address-table dynamic address ${macAddress}`, function(data) {
                    showAlert(`MAC address ${macAddress} cleared`, 'success');
                });
            });
        }

        function toggleGlobalPortSecurity() {
            const enabled = document.getElementById('global-port-security').checked;
            showAlert(`Global port security ${enabled ? 'enabled' : 'disabled'}`, 'info');
        }

        function applyGlobalSettings() {
            const defaultMaxMac = document.getElementById('default-max-mac').value;
            const defaultAction = document.getElementById('default-violation-action').value;
            const agingTime = document.getElementById('aging-time').value;

            let commands = [];
            
            if (defaultMaxMac !== '1') {
                commands.push(`switchport port-security maximum ${defaultMaxMac}`);
            }
            
            if (defaultAction !== 'shutdown') {
                commands.push(`switchport port-security violation ${defaultAction}`);
            }
            
            if (agingTime !== '0') {
                commands.push(`switchport port-security aging time ${agingTime}`);
            }

            if (commands.length > 0) {
                confirmAction(`Apply global port security settings?\n\n${commands.join('\n')}`, function() {
                    executeCommand(commands.join(' ; '), function(data) {
                        showAlert('Global port security settings applied', 'success');
                    });
                });
            } else {
                showAlert('No changes to apply', 'info');
            }
        }

        function previewPortSecurityConfig() {
            showAlert('Configuration preview feature coming soon!', 'info');
        }

        function validateMAC(mac) {
            const macRegex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
            return macRegex.test(mac);
        }
    </script>
</body>
</html>

