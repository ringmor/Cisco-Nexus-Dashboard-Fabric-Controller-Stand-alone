<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVI Management - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-layer-group"></i> SVI Management</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showCreateSviModal()">
                            <i class="fas fa-plus"></i> Create SVI
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- SVI Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-svis">-</div>
                            <div class="metric-label">Total SVIs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="up-svis">-</div>
                            <div class="metric-label">Up SVIs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-danger" id="down-svis">-</div>
                            <div class="metric-label">Down SVIs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="configured-svis">-</div>
                            <div class="metric-label">With IP Config</div>
                        </div>
                    </div>
                </div>

                <!-- SVI Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Switch Virtual Interfaces</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="svi-table">
                                <thead>
                                    <tr>
                                        <th>Interface</th>
                                        <th>VLAN</th>
                                        <th>Status</th>
                                        <th>Protocol</th>
                                        <th>IP Address</th>
                                        <th>Secondary IPs</th>
                                        <th>Description</th>
                                        <th>MTU</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="svi-tbody">
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="loading-spinner"></div> Loading SVIs...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit SVI Modal -->
                <div class="modal fade" id="sviModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sviModalTitle">Create SVI</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="svi-form">
                                    <input type="hidden" id="svi-action" value="create">
                                    <input type="hidden" id="original-vlan-id">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">VLAN ID *</label>
                                                <select class="form-select" id="svi-vlan-id" required>
                                                    <option value="">-- Select VLAN --</option>
                                                    <!-- VLANs will be populated here -->
                                                </select>
                                                <div class="form-text">Select VLAN for SVI creation</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Administrative State</label>
                                                <select class="form-select" id="svi-admin-state">
                                                    <option value="no shutdown">Up (no shutdown)</option>
                                                    <option value="shutdown">Down (shutdown)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" id="svi-description" 
                                               placeholder="SVI description">
                                    </div>

                                    <div class="config-section">
                                        <h6 class="config-title">IP Configuration</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">Primary IP Address</label>
                                                    <input type="text" class="form-control" id="svi-ip-address" 
                                                           placeholder="192.168.1.1">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Subnet Mask</label>
                                                    <select class="form-select" id="svi-subnet-mask">
                                                        <option value="24">/24 (255.255.255.0)</option>
                                                        <option value="25">/25 (255.255.255.128)</option>
                                                        <option value="26">/26 (255.255.255.192)</option>
                                                        <option value="27">/27 (255.255.255.224)</option>
                                                        <option value="28">/28 (255.255.255.240)</option>
                                                        <option value="29">/29 (255.255.255.248)</option>
                                                        <option value="30">/30 (255.255.255.252)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Secondary IP Addresses</label>
                                            <textarea class="form-control" id="svi-secondary-ips" rows="3" 
                                                      placeholder="192.168.2.1/24&#10;192.168.3.1/24"></textarea>
                                            <div class="form-text">One IP address per line in CIDR notation</div>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="svi-dhcp-relay">
                                            <label class="form-check-label" for="svi-dhcp-relay">
                                                Enable DHCP Relay
                                            </label>
                                        </div>

                                        <div id="dhcp-relay-config" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">DHCP Server IP</label>
                                                <input type="text" class="form-control" id="dhcp-server-ip" 
                                                       placeholder="192.168.100.10">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="config-section">
                                        <h6 class="config-title">Advanced Configuration</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">MTU Size</label>
                                                    <input type="number" class="form-control" id="svi-mtu" 
                                                           min="1500" max="9216" value="1500">
                                                    <div class="form-text">1500-9216 bytes</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Load Interval</label>
                                                    <select class="form-select" id="svi-load-interval">
                                                        <option value="30">30 seconds</option>
                                                        <option value="60">1 minute</option>
                                                        <option value="300" selected>5 minutes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="svi-ip-redirects" checked>
                                                    <label class="form-check-label" for="svi-ip-redirects">
                                                        Enable IP Redirects
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="svi-ip-proxy-arp">
                                                    <label class="form-check-label" for="svi-ip-proxy-arp">
                                                        Enable Proxy ARP
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="svi-ip-unreachables" checked>
                                                    <label class="form-check-label" for="svi-ip-unreachables">
                                                        Send ICMP Unreachables
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="svi-ip-directed-broadcast">
                                                    <label class="form-check-label" for="svi-ip-directed-broadcast">
                                                        Enable Directed Broadcast
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="config-section">
                                        <h6 class="config-title">HSRP Configuration</h6>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="enable-hsrp">
                                            <label class="form-check-label" for="enable-hsrp">
                                                Enable HSRP (Hot Standby Router Protocol)
                                            </label>
                                        </div>

                                        <div id="hsrp-config" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Group ID</label>
                                                        <input type="number" class="form-control" id="hsrp-group" 
                                                               min="0" max="255" value="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Priority</label>
                                                        <input type="number" class="form-control" id="hsrp-priority" 
                                                               min="1" max="255" value="100">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Virtual IP</label>
                                                        <input type="text" class="form-control" id="hsrp-vip" 
                                                               placeholder="192.168.1.254">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="hsrp-preempt">
                                                <label class="form-check-label" for="hsrp-preempt">
                                                    Enable Preemption
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="saveSvi()">
                                    <span id="svi-save-btn-text">Create SVI</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SVI Details Modal -->
                <div class="modal fade" id="sviDetailsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">SVI Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="svi-details-content">
                                    <!-- SVI details will be populated here -->
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

    <script src="../../assets/js/common.js"></script>
    <script>
        let sviData = [];
        let vlansData = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupDhcpRelayToggle();
            setupHsrpToggle();
        });

        function loadData() {
            loadSvis();
            loadVlans();
        }

        function loadSvis() {
            executeCommand('show interface vlan brief', function(data) {
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_interface) {
                        sviData = output.body.TABLE_interface.ROW_interface || [];
                        if (!Array.isArray(sviData)) {
                            sviData = [sviData];
                        }
                    }
                } else {
                    // Mock data for demonstration
                    sviData = generateMockSviData();
                }
                displaySvis();
                updateSummaryCards();
            });
        }

        function loadVlans() {
            executeCommand('show vlan brief', function(data) {
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_vlanbriefxbrief) {
                        vlansData = output.body.TABLE_vlanbriefxbrief.ROW_vlanbriefxbrief || [];
                        if (!Array.isArray(vlansData)) {
                            vlansData = [vlansData];
                        }
                    }
                } else {
                    // Mock data for demonstration
                    vlansData = generateMockVlanData();
                }
                populateVlanSelect();
            });
        }

        function generateMockSviData() {
            return [
                {
                    interface: 'Vlan10',
                    state: 'up',
                    admin_state: 'up',
                    desc: 'User Network Gateway',
                    ip_addr: '192.168.10.1/24',
                    mtu: '1500'
                },
                {
                    interface: 'Vlan20',
                    state: 'up',
                    admin_state: 'up',
                    desc: 'Server Network Gateway',
                    ip_addr: '192.168.20.1/24',
                    mtu: '1500'
                },
                {
                    interface: 'Vlan100',
                    state: 'down',
                    admin_state: 'down',
                    desc: 'Management Network',
                    ip_addr: '192.168.100.1/24',
                    mtu: '1500'
                }
            ];
        }

        function generateMockVlanData() {
            return [
                { vlanshowbr_vlanid: '1', vlanshowbr_vlanname: 'default' },
                { vlanshowbr_vlanid: '10', vlanshowbr_vlanname: 'USERS' },
                { vlanshowbr_vlanid: '20', vlanshowbr_vlanname: 'SERVERS' },
                { vlanshowbr_vlanid: '30', vlanshowbr_vlanname: 'GUEST' },
                { vlanshowbr_vlanid: '100', vlanshowbr_vlanname: 'MANAGEMENT' },
                { vlanshowbr_vlanid: '200', vlanshowbr_vlanname: 'DMZ' }
            ];
        }

        function displaySvis() {
            const tbody = document.getElementById('svi-tbody');
            tbody.innerHTML = '';

            sviData.forEach(svi => {
                const row = document.createElement('tr');
                
                const vlanId = svi.interface.replace('Vlan', '');
                const secondaryIps = getSecondaryIps(svi.interface);
                
                row.innerHTML = `
                    <td>${formatInterfaceName(svi.interface)}</td>
                    <td><span class="vlan-tag vlan-active">${vlanId}</span></td>
                    <td>${formatInterfaceStatus(svi.state)}</td>
                    <td>${formatInterfaceStatus(svi.admin_state)}</td>
                    <td><strong>${svi.ip_addr || '--'}</strong></td>
                    <td>
                        ${secondaryIps.length > 0 ? 
                            `<span class="badge bg-info">${secondaryIps.length} secondary</span>` : 
                            '--'
                        }
                    </td>
                    <td>${svi.desc || '--'}</td>
                    <td>${svi.mtu || '1500'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editSvi('${vlanId}')"
                                data-bs-toggle="tooltip" title="Edit SVI">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="showSviDetails('${svi.interface}')"
                                data-bs-toggle="tooltip" title="View Details">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="deleteSvi('${vlanId}')"
                                data-bs-toggle="tooltip" title="Delete SVI">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            initializeTooltips();
        }

        function updateSummaryCards() {
            const total = sviData.length;
            const up = sviData.filter(s => s.state === 'up').length;
            const down = sviData.filter(s => s.state === 'down').length;
            const configured = sviData.filter(s => s.ip_addr && s.ip_addr !== '--').length;

            document.getElementById('total-svis').textContent = total;
            document.getElementById('up-svis').textContent = up;
            document.getElementById('down-svis').textContent = down;
            document.getElementById('configured-svis').textContent = configured;
        }

        function getSecondaryIps(interfaceName) {
            // Mock secondary IPs - in real implementation, this would get actual secondary IPs
            const secondaryIps = {
                'Vlan10': ['192.168.11.1/24', '192.168.12.1/24'],
                'Vlan20': ['192.168.21.1/24']
            };
            return secondaryIps[interfaceName] || [];
        }

        function populateVlanSelect() {
            const select = document.getElementById('svi-vlan-id');
            select.innerHTML = '<option value="">-- Select VLAN --</option>';
            
            vlansData.forEach(vlan => {
                const vlanId = vlan.vlanshowbr_vlanid || vlan.vlan_id;
                const vlanName = vlan.vlanshowbr_vlanname || vlan.vlan_name;
                
                // Check if SVI already exists for this VLAN
                const sviExists = sviData.some(svi => svi.interface === `Vlan${vlanId}`);
                
                const option = document.createElement('option');
                option.value = vlanId;
                option.textContent = `VLAN ${vlanId} (${vlanName})${sviExists ? ' - SVI exists' : ''}`;
                option.disabled = sviExists;
                select.appendChild(option);
            });
        }

        function setupDhcpRelayToggle() {
            document.getElementById('svi-dhcp-relay').addEventListener('change', function() {
                const dhcpConfig = document.getElementById('dhcp-relay-config');
                dhcpConfig.style.display = this.checked ? 'block' : 'none';
            });
        }

        function setupHsrpToggle() {
            document.getElementById('enable-hsrp').addEventListener('change', function() {
                const hsrpConfig = document.getElementById('hsrp-config');
                hsrpConfig.style.display = this.checked ? 'block' : 'none';
            });
        }

        function showCreateSviModal() {
            document.getElementById('svi-action').value = 'create';
            document.getElementById('sviModalTitle').textContent = 'Create SVI';
            document.getElementById('svi-save-btn-text').textContent = 'Create SVI';
            
            // Reset form
            document.getElementById('svi-form').reset();
            document.getElementById('svi-vlan-id').disabled = false;
            document.getElementById('dhcp-relay-config').style.display = 'none';
            document.getElementById('hsrp-config').style.display = 'none';
            
            const modal = new bootstrap.Modal(document.getElementById('sviModal'));
            modal.show();
        }

        function editSvi(vlanId) {
            const svi = sviData.find(s => s.interface === `Vlan${vlanId}`);
            if (!svi) return;
            
            document.getElementById('svi-action').value = 'edit';
            document.getElementById('sviModalTitle').textContent = `Edit SVI VLAN ${vlanId}`;
            document.getElementById('svi-save-btn-text').textContent = 'Update SVI';
            
            // Populate form
            document.getElementById('svi-vlan-id').value = vlanId;
            document.getElementById('svi-vlan-id').disabled = true;
            document.getElementById('original-vlan-id').value = vlanId;
            document.getElementById('svi-description').value = svi.desc || '';
            document.getElementById('svi-admin-state').value = svi.admin_state === 'up' ? 'no shutdown' : 'shutdown';
            document.getElementById('svi-mtu').value = svi.mtu || '1500';
            
            if (svi.ip_addr && svi.ip_addr !== '--') {
                const [ip, prefix] = svi.ip_addr.split('/');
                document.getElementById('svi-ip-address').value = ip;
                document.getElementById('svi-subnet-mask').value = prefix || '24';
            }
            
            // Load secondary IPs
            const secondaryIps = getSecondaryIps(svi.interface);
            document.getElementById('svi-secondary-ips').value = secondaryIps.join('\n');
            
            const modal = new bootstrap.Modal(document.getElementById('sviModal'));
            modal.show();
        }

        function saveSvi() {
            const action = document.getElementById('svi-action').value;
            const vlanId = document.getElementById('svi-vlan-id').value;
            const description = document.getElementById('svi-description').value;
            const adminState = document.getElementById('svi-admin-state').value;
            const ipAddress = document.getElementById('svi-ip-address').value;
            const subnetMask = document.getElementById('svi-subnet-mask').value;
            const secondaryIps = document.getElementById('svi-secondary-ips').value;
            const mtu = document.getElementById('svi-mtu').value;

            if (!vlanId) {
                showAlert('Please select a VLAN.', 'danger');
                return;
            }

            if (ipAddress && !validateIP(ipAddress)) {
                showAlert('Invalid IP address format.', 'danger');
                return;
            }

            let commands = [`interface vlan${vlanId}`];

            if (description) {
                commands.push(`description ${description}`);
            }

            if (ipAddress) {
                commands.push(`ip address ${ipAddress}/${subnetMask}`);
            }

            // Secondary IP addresses
            if (secondaryIps) {
                secondaryIps.split('\n').forEach(line => {
                    line = line.trim();
                    if (line && line.includes('/')) {
                        commands.push(`ip address ${line} secondary`);
                    }
                });
            }

            if (mtu && mtu !== '1500') {
                commands.push(`mtu ${mtu}`);
            }

            const loadInterval = document.getElementById('svi-load-interval').value;
            if (loadInterval) {
                commands.push(`load-interval ${loadInterval}`);
            }

            // IP options
            if (!document.getElementById('svi-ip-redirects').checked) {
                commands.push('no ip redirects');
            }

            if (document.getElementById('svi-ip-proxy-arp').checked) {
                commands.push('ip proxy-arp');
            }

            if (!document.getElementById('svi-ip-unreachables').checked) {
                commands.push('no ip unreachables');
            }

            if (document.getElementById('svi-ip-directed-broadcast').checked) {
                commands.push('ip directed-broadcast');
            }

            // DHCP Relay
            if (document.getElementById('svi-dhcp-relay').checked) {
                const dhcpServer = document.getElementById('dhcp-server-ip').value;
                if (dhcpServer && validateIP(dhcpServer)) {
                    commands.push(`ip dhcp relay address ${dhcpServer}`);
                }
            }

            // HSRP Configuration
            if (document.getElementById('enable-hsrp').checked) {
                const hsrpGroup = document.getElementById('hsrp-group').value;
                const hsrpPriority = document.getElementById('hsrp-priority').value;
                const hsrpVip = document.getElementById('hsrp-vip').value;
                
                if (hsrpVip && validateIP(hsrpVip)) {
                    commands.push(`hsrp ${hsrpGroup}`);
                    commands.push(`priority ${hsrpPriority}`);
                    commands.push(`ip ${hsrpVip}`);
                    
                    if (document.getElementById('hsrp-preempt').checked) {
                        commands.push('preempt');
                    }
                    
                    commands.push('exit');
                }
            }

            commands.push(adminState);

            const configCommands = commands.join('\n');
            
            confirmAction(`${action === 'create' ? 'Create' : 'Update'} SVI for VLAN ${vlanId}?\n\n${configCommands}`, function() {
                executeCommand(configCommands.split('\n').join(' ; '), function(data) {
                    showAlert(`SVI for VLAN ${vlanId} ${action === 'create' ? 'created' : 'updated'} successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('sviModal')).hide();
                    setTimeout(loadData, 2000);
                }, 'cli_conf');
            });
        }

        function deleteSvi(vlanId) {
            confirmAction(`Are you sure you want to delete SVI for VLAN ${vlanId}?\n\nThis will remove all IP configuration for this VLAN.`, function() {
                const commands = [`no interface vlan${vlanId}`];
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert(`SVI for VLAN ${vlanId} deleted successfully`, 'success');
                    setTimeout(loadData, 2000);
                }, 'cli_conf');
            });
        }

        function showSviDetails(interfaceName) {
            executeCommand(`show interface ${interfaceName}`, function(data) {
                // In a real implementation, this would parse and display detailed interface information
                const detailsContent = document.getElementById('svi-details-content');
                detailsContent.innerHTML = `
                    <div class="config-section">
                        <h6 class="config-title">Interface ${interfaceName} Details</h6>
                        <p>Detailed interface statistics and configuration would be displayed here.</p>
                        <p>This would include:</p>
                        <ul>
                            <li>Interface statistics (packets, bytes, errors)</li>
                            <li>IP configuration details</li>
                            <li>HSRP status and configuration</li>
                            <li>DHCP relay configuration</li>
                            <li>Access control lists applied</li>
                            <li>Quality of Service policies</li>
                        </ul>
                    </div>
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('sviDetailsModal'));
                modal.show();
            });
        }
    </script>
</body>
</html>

