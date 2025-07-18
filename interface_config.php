<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-cog"></i> Interface Configuration</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showBulkConfigModal()">
                            <i class="fas fa-layer-group"></i> Bulk Config
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Configuration Templates -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-desktop"></i> Access Port Template</h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Configure interfaces as access ports for end devices</p>
                                <button class="btn btn-outline-primary btn-sm" onclick="applyTemplate('access')">
                                    Apply Template
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-network-wired"></i> Trunk Port Template</h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Configure interfaces as trunk ports for switches</p>
                                <button class="btn btn-outline-success btn-sm" onclick="applyTemplate('trunk')">
                                    Apply Template
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-route"></i> Routed Port Template</h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Configure interfaces as Layer 3 routed ports</p>
                                <button class="btn btn-outline-info btn-sm" onclick="applyTemplate('routed')">
                                    Apply Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interface Configuration Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Interface Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="interface-config-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="config-section">
                                        <h6 class="config-title">Basic Configuration</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Interface Selection</label>
                                            <select class="form-select" id="interface-select" multiple size="8">
                                                <!-- Interfaces will be populated here -->
                                            </select>
                                            <div class="form-text">Hold Ctrl/Cmd to select multiple interfaces</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" id="interface-description" 
                                                           placeholder="Interface description">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Administrative State</label>
                                                    <select class="form-select" id="admin-state">
                                                        <option value="">-- No Change --</option>
                                                        <option value="no shutdown">Enable (no shutdown)</option>
                                                        <option value="shutdown">Disable (shutdown)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Speed</label>
                                                    <select class="form-select" id="interface-speed">
                                                        <option value="">-- No Change --</option>
                                                        <option value="auto">Auto</option>
                                                        <option value="10">10 Mbps</option>
                                                        <option value="100">100 Mbps</option>
                                                        <option value="1000">1 Gbps</option>
                                                        <option value="10000">10 Gbps</option>
                                                        <option value="25000">25 Gbps</option>
                                                        <option value="40000">40 Gbps</option>
                                                        <option value="100000">100 Gbps</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Duplex</label>
                                                    <select class="form-select" id="interface-duplex">
                                                        <option value="">-- No Change --</option>
                                                        <option value="auto">Auto</option>
                                                        <option value="full">Full</option>
                                                        <option value="half">Half</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="config-section">
                                        <h6 class="config-title">Layer 2/3 Configuration</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Interface Mode</label>
                                            <select class="form-select" id="interface-mode">
                                                <option value="">-- No Change --</option>
                                                <option value="switchport-access">Layer 2 Access</option>
                                                <option value="switchport-trunk">Layer 2 Trunk</option>
                                                <option value="routed">Layer 3 Routed</option>
                                            </select>
                                        </div>

                                        <!-- Layer 2 Access Configuration -->
                                        <div id="access-config" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Access VLAN</label>
                                                <select class="form-select" id="access-vlan">
                                                    <option value="">-- Select VLAN --</option>
                                                    <!-- VLANs will be populated here -->
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Layer 2 Trunk Configuration -->
                                        <div id="trunk-config" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Native VLAN</label>
                                                <select class="form-select" id="native-vlan">
                                                    <option value="1">VLAN 1 (default)</option>
                                                    <!-- VLANs will be populated here -->
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Allowed VLANs</label>
                                                <input type="text" class="form-control" id="allowed-vlans" 
                                                       placeholder="1,10,20-30,100 or 'all'">
                                                <div class="form-text">Comma-separated list, ranges, or 'all'</div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="trunk-encapsulation">
                                                <label class="form-check-label" for="trunk-encapsulation">
                                                    Force 802.1Q encapsulation
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Layer 3 Routed Configuration -->
                                        <div id="routed-config" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label class="form-label">IP Address</label>
                                                        <input type="text" class="form-control" id="ip-address" 
                                                               placeholder="192.168.1.1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Prefix Length</label>
                                                        <select class="form-select" id="prefix-length">
                                                            <option value="24">/24</option>
                                                            <option value="25">/25</option>
                                                            <option value="26">/26</option>
                                                            <option value="27">/27</option>
                                                            <option value="28">/28</option>
                                                            <option value="29">/29</option>
                                                            <option value="30">/30</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Secondary IP Addresses</label>
                                                <textarea class="form-control" id="secondary-ips" rows="3" 
                                                          placeholder="192.168.2.1/24&#10;192.168.3.1/24"></textarea>
                                                <div class="form-text">One IP/prefix per line</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Configuration -->
                            <div class="config-section">
                                <h6 class="config-title">Advanced Configuration</h6>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">MTU Size</label>
                                            <input type="number" class="form-control" id="mtu-size" 
                                                   min="1500" max="9216" placeholder="1500">
                                            <div class="form-text">1500-9216 bytes</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Load Interval</label>
                                            <select class="form-select" id="load-interval">
                                                <option value="">-- Default --</option>
                                                <option value="30">30 seconds</option>
                                                <option value="60">1 minute</option>
                                                <option value="300">5 minutes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Bandwidth (Kbps)</label>
                                            <input type="number" class="form-control" id="bandwidth" 
                                                   placeholder="Auto-detected">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="cdp-enable">
                                            <label class="form-check-label" for="cdp-enable">
                                                Enable CDP (Cisco Discovery Protocol)
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="lldp-enable">
                                            <label class="form-check-label" for="lldp-enable">
                                                Enable LLDP (Link Layer Discovery Protocol)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="storm-control">
                                            <label class="form-check-label" for="storm-control">
                                                Enable Storm Control
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="flow-control">
                                            <label class="form-check-label" for="flow-control">
                                                Enable Flow Control
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-nexus" onclick="applyConfiguration()">
                                    <i class="fas fa-check"></i> Apply Configuration
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="previewConfiguration()">
                                    <i class="fas fa-eye"></i> Preview Commands
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="saveAsTemplate()">
                                    <i class="fas fa-save"></i> Save Template
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="resetForm()">
                                    <i class="fas fa-undo"></i> Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Configuration Preview Modal -->
                <div class="modal fade" id="previewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Configuration Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="command-output" id="preview-commands">
                                    <!-- Commands will be displayed here -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-nexus" onclick="applyFromPreview()">
                                    Apply Configuration
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="copyCommands()">
                                    Copy Commands
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Configuration Modal -->
                <div class="modal fade" id="bulkConfigModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Interface Configuration</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Interface Range</label>
                                    <input type="text" class="form-control" id="bulk-interface-range" 
                                           placeholder="e.g., Ethernet1/1-10, Ethernet1/15-20">
                                    <div class="form-text">Specify interface ranges for bulk configuration</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Configuration Template</label>
                                    <select class="form-select" id="bulk-template">
                                        <option value="">-- Select Template --</option>
                                        <option value="access-user">Access Port - User VLAN</option>
                                        <option value="access-server">Access Port - Server VLAN</option>
                                        <option value="trunk-switch">Trunk Port - Switch Connection</option>
                                        <option value="shutdown">Shutdown Unused Ports</option>
                                    </select>
                                </div>

                                <div id="bulk-template-config">
                                    <!-- Template-specific configuration will appear here -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="applyBulkConfiguration()">
                                    Apply Bulk Configuration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        let interfacesData = [];
        let vlansData = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupInterfaceModeChange();
        });

        function loadData() {
            loadInterfaces();
            loadVlans();
        }

        function loadInterfaces() {
            executeCommand('show interface brief', function(data) {
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_interface) {
                        interfacesData = output.body.TABLE_interface.ROW_interface || [];
                        if (!Array.isArray(interfacesData)) {
                            interfacesData = [interfacesData];
                        }
                    }
                } else {
                    // Mock data for demonstration
                    interfacesData = generateMockInterfaceData();
                }
                populateInterfaceSelect();
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
                populateVlanSelects();
            });
        }

        function generateMockInterfaceData() {
            const interfaces = [];
            for (let i = 1; i <= 48; i++) {
                interfaces.push({
                    interface: `Ethernet1/${i}`,
                    state: i <= 10 ? 'up' : 'down',
                    admin_state: i <= 40 ? 'up' : 'down'
                });
            }
            interfaces.push({ interface: 'mgmt0', state: 'up', admin_state: 'up' });
            return interfaces;
        }

        function generateMockVlanData() {
            return [
                { vlanshowbr_vlanid: '1', vlanshowbr_vlanname: 'default' },
                { vlanshowbr_vlanid: '10', vlanshowbr_vlanname: 'USERS' },
                { vlanshowbr_vlanid: '20', vlanshowbr_vlanname: 'SERVERS' },
                { vlanshowbr_vlanid: '30', vlanshowbr_vlanname: 'GUEST' },
                { vlanshowbr_vlanid: '100', vlanshowbr_vlanname: 'MANAGEMENT' }
            ];
        }

        function populateInterfaceSelect() {
            const select = document.getElementById('interface-select');
            select.innerHTML = '';
            
            interfacesData.forEach(intf => {
                if (!intf.interface.includes('mgmt')) {
                    const option = document.createElement('option');
                    option.value = intf.interface;
                    option.textContent = `${intf.interface} (${intf.state})`;
                    select.appendChild(option);
                }
            });
        }

        function populateVlanSelects() {
            const selects = ['access-vlan', 'native-vlan'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    // Keep existing options and add VLANs
                    vlansData.forEach(vlan => {
                        const vlanId = vlan.vlanshowbr_vlanid || vlan.vlan_id;
                        const vlanName = vlan.vlanshowbr_vlanname || vlan.vlan_name;
                        
                        const option = document.createElement('option');
                        option.value = vlanId;
                        option.textContent = `VLAN ${vlanId} (${vlanName})`;
                        select.appendChild(option);
                    });
                }
            });
        }

        function setupInterfaceModeChange() {
            document.getElementById('interface-mode').addEventListener('change', function() {
                const mode = this.value;
                
                // Hide all mode-specific configs
                document.getElementById('access-config').style.display = 'none';
                document.getElementById('trunk-config').style.display = 'none';
                document.getElementById('routed-config').style.display = 'none';
                
                // Show relevant config based on mode
                if (mode === 'switchport-access') {
                    document.getElementById('access-config').style.display = 'block';
                } else if (mode === 'switchport-trunk') {
                    document.getElementById('trunk-config').style.display = 'block';
                } else if (mode === 'routed') {
                    document.getElementById('routed-config').style.display = 'block';
                }
            });
        }

        function applyTemplate(templateType) {
            // Don't reset the form - just set template-specific values
            // Hide all mode-specific configs first
            document.getElementById('access-config').style.display = 'none';
            document.getElementById('trunk-config').style.display = 'none';
            document.getElementById('routed-config').style.display = 'none';
            
            switch (templateType) {
                case 'access':
                    document.getElementById('interface-mode').value = 'switchport-access';
                    document.getElementById('access-config').style.display = 'block';
                    document.getElementById('admin-state').value = 'no shutdown';
                    document.getElementById('interface-speed').value = 'auto';
                    document.getElementById('interface-duplex').value = 'auto';
                    break;
                    
                case 'trunk':
                    document.getElementById('interface-mode').value = 'switchport-trunk';
                    document.getElementById('trunk-config').style.display = 'block';
                    document.getElementById('admin-state').value = 'no shutdown';
                    document.getElementById('interface-speed').value = 'auto';
                    document.getElementById('interface-duplex').value = 'auto';
                    document.getElementById('allowed-vlans').value = 'all';
                    break;
                    
                case 'routed':
                    document.getElementById('interface-mode').value = 'routed';
                    document.getElementById('routed-config').style.display = 'block';
                    document.getElementById('admin-state').value = 'no shutdown';
                    document.getElementById('interface-speed').value = 'auto';
                    document.getElementById('interface-duplex').value = 'auto';
                    break;
            }
            
            showAlert(`${templateType.charAt(0).toUpperCase() + templateType.slice(1)} template applied. Select interfaces and configure as needed.`, 'info');
        }

        function previewConfiguration() {
            const commands = generateConfigurationCommands();
            if (commands.length === 0) {
                showAlert('No configuration changes to preview.', 'warning');
                return;
            }
            
            // Format commands with proper line breaks and styling
            const formattedCommands = commands.map((cmd, index) => {
                const lineNumber = (index + 1).toString().padStart(3, ' ');
                return `${lineNumber}: ${cmd}`;
            }).join('\n');
            
            const previewElement = document.getElementById('preview-commands');
            previewElement.innerHTML = `<pre style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.4; max-height: 400px; overflow-y: auto; white-space: pre-wrap;">${formattedCommands}</pre>`;
            
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }

        function validateConfiguration() {
            const selectedInterfaces = Array.from(document.getElementById('interface-select').selectedOptions)
                .map(option => option.value);
            
            if (selectedInterfaces.length === 0) {
                showAlert('Please select at least one interface.', 'warning');
                return false;
            }

            const mode = document.getElementById('interface-mode').value;
            if (mode === 'switchport-access') {
                const accessVlan = document.getElementById('access-vlan').value;
                if (!accessVlan) {
                    showAlert('Please select an Access VLAN for access port configuration.', 'warning');
                    return false;
                }
            } else if (mode === 'switchport-trunk') {
                const allowedVlans = document.getElementById('allowed-vlans').value;
                if (!allowedVlans) {
                    showAlert('Please specify allowed VLANs for trunk port configuration.', 'warning');
                    return false;
                }
            } else if (mode === 'routed') {
                const ipAddress = document.getElementById('ip-address').value;
                if (!ipAddress) {
                    showAlert('Please specify an IP address for routed port configuration.', 'warning');
                    return false;
                }
                if (!validateIP(ipAddress)) {
                    showAlert('Please enter a valid IP address.', 'warning');
                    return false;
                }
            }

            return true;
        }

        function generateConfigurationCommands() {
            if (!validateConfiguration()) {
                return [];
            }

            const selectedInterfaces = Array.from(document.getElementById('interface-select').selectedOptions)
                .map(option => option.value);

            // Debug: Log form values (only in development)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Selected interfaces:', selectedInterfaces);
                console.log('Description:', document.getElementById('interface-description').value);
                console.log('Mode:', document.getElementById('interface-mode').value);
                console.log('Admin state:', document.getElementById('admin-state').value);
            }

            let commands = ['configure terminal'];
            
            selectedInterfaces.forEach(interfaceName => {
                commands.push(`interface ${interfaceName}`);
                
                // Basic configuration
                const description = document.getElementById('interface-description').value;
                if (description) {
                    commands.push(`description ${description}`);
                }
                
                const speed = document.getElementById('interface-speed').value;
                if (speed) {
                    commands.push(`speed ${speed}`);
                }
                
                const duplex = document.getElementById('interface-duplex').value;
                if (duplex) {
                    commands.push(`duplex ${duplex}`);
                }
                
                // Interface mode configuration
                const mode = document.getElementById('interface-mode').value;
                if (mode === 'switchport-access') {
                    commands.push('switchport');
                    commands.push('switchport mode access');
                    
                    const accessVlan = document.getElementById('access-vlan').value;
                    if (accessVlan) {
                        commands.push(`switchport access vlan ${accessVlan}`);
                    }
                } else if (mode === 'switchport-trunk') {
                    commands.push('switchport');
                    commands.push('switchport mode trunk');
                    
                    const nativeVlan = document.getElementById('native-vlan').value;
                    if (nativeVlan && nativeVlan !== '1') {
                        commands.push(`switchport trunk native vlan ${nativeVlan}`);
                    }
                    
                    const allowedVlans = document.getElementById('allowed-vlans').value;
                    if (allowedVlans) {
                        commands.push(`switchport trunk allowed vlan ${allowedVlans}`);
                    }
                    
                    if (document.getElementById('trunk-encapsulation').checked) {
                        commands.push('switchport trunk encapsulation dot1q');
                    }
                } else if (mode === 'routed') {
                    commands.push('no switchport');
                    
                    const ipAddress = document.getElementById('ip-address').value;
                    const prefixLength = document.getElementById('prefix-length').value;
                    if (ipAddress && validateIP(ipAddress)) {
                        commands.push(`ip address ${ipAddress}/${prefixLength}`);
                    }
                    
                    const secondaryIps = document.getElementById('secondary-ips').value;
                    if (secondaryIps) {
                        secondaryIps.split('\n').forEach(line => {
                            line = line.trim();
                            if (line && line.includes('/')) {
                                commands.push(`ip address ${line} secondary`);
                            }
                        });
                    }
                }
                
                // Advanced configuration
                const mtu = document.getElementById('mtu-size').value;
                if (mtu) {
                    commands.push(`mtu ${mtu}`);
                }
                
                const loadInterval = document.getElementById('load-interval').value;
                if (loadInterval) {
                    commands.push(`load-interval ${loadInterval}`);
                }
                
                const bandwidth = document.getElementById('bandwidth').value;
                if (bandwidth) {
                    commands.push(`bandwidth ${bandwidth}`);
                }
                
                // Protocol configuration
                if (document.getElementById('cdp-enable').checked) {
                    commands.push('cdp enable');
                } else {
                    commands.push('no cdp enable');
                }
                
                if (document.getElementById('lldp-enable').checked) {
                    commands.push('lldp enable');
                } else {
                    commands.push('no lldp enable');
                }
                
                if (document.getElementById('storm-control').checked) {
                    commands.push('storm-control broadcast level 10.00');
                    commands.push('storm-control multicast level 10.00');
                }
                
                if (document.getElementById('flow-control').checked) {
                    commands.push('flowcontrol receive on');
                    commands.push('flowcontrol send on');
                } else {
                    commands.push('no flowcontrol receive');
                    commands.push('no flowcontrol send');
                }
                
                // Administrative state (last)
                const adminState = document.getElementById('admin-state').value;
                if (adminState) {
                    commands.push(adminState);
                }
                
                commands.push('exit');
            });
            
            commands.push('end');
            commands.push('copy running-config startup-config');
            
            // Debug: Log generated commands (only in development)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Generated commands:', commands);
            }
            
            return commands;
        }

        function applyConfiguration() {
            const commands = generateConfigurationCommands();
            if (commands.length === 0) return;
            
            const selectedInterfaces = Array.from(document.getElementById('interface-select').selectedOptions)
                .map(option => option.value);
            
            confirmAction(`Apply configuration to ${selectedInterfaces.length} interface(s)?\n\n${selectedInterfaces.join(', ')}`, function() {
                // Send commands individually for cli_conf
                executeCommandsSequentially(commands, function(data) {
                    if (data.success) {
                        showAlert(`Configuration applied successfully! ${data.successCount}/${data.totalCommands} commands executed.`, 'success');
                    } else {
                        showAlert(`Configuration completed with errors. ${data.successCount}/${data.totalCommands} commands succeeded, ${data.errorCount} failed.`, 'warning');
                    }
                    setTimeout(loadData, 2000);
                });
            });
        }

        function executeCommandsSequentially(commands, callback) {
            let currentIndex = 0;
            let successCount = 0;
            let errorCount = 0;
            
            // Show progress message
            showAlert(`Executing ${commands.length} configuration commands...`, 'info');
            
            function executeNext() {
                if (currentIndex >= commands.length) {
                    if (callback) {
                        const result = { 
                            success: errorCount === 0, 
                            successCount: successCount, 
                            errorCount: errorCount,
                            totalCommands: commands.length
                        };
                        callback(result);
                    }
                    return;
                }
                
                const command = commands[currentIndex];
                const progress = Math.round(((currentIndex + 1) / commands.length) * 100);
                
                // Update progress message
                showAlert(`Executing command ${currentIndex + 1}/${commands.length}: ${command}`, 'info', 2000);
                
                executeCommand(command, function(data) {
                    if (data.success) {
                        successCount++;
                        currentIndex++;
                        // Small delay between commands
                        setTimeout(executeNext, 100);
                    } else {
                        errorCount++;
                        const errorMsg = data.error || data.message || 'Unknown error';
                        showAlert(`Error executing command: ${command}\nError: ${errorMsg}`, 'danger');
                        // Continue with next command even if one fails
                        currentIndex++;
                        setTimeout(executeNext, 100);
                    }
                }, 'cli_conf');
            }
            
            executeNext();
        }

        function applyFromPreview() {
            bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
            applyConfiguration();
        }

        function copyCommands() {
            const commands = generateConfigurationCommands();
            const commandText = commands.join('\n');
            copyToClipboard(commandText);
            showAlert('Commands copied to clipboard!', 'success', 2000);
        }

        function resetForm() {
            document.getElementById('interface-config-form').reset();
            document.getElementById('access-config').style.display = 'none';
            document.getElementById('trunk-config').style.display = 'none';
            document.getElementById('routed-config').style.display = 'none';
        }

        function showBulkConfigModal() {
            const modal = new bootstrap.Modal(document.getElementById('bulkConfigModal'));
            modal.show();
        }

        function applyBulkConfiguration() {
            const interfaceRange = document.getElementById('bulk-interface-range').value;
            const template = document.getElementById('bulk-template').value;
            
            if (!interfaceRange || !template) {
                showAlert('Please specify interface range and template.', 'warning');
                return;
            }
            
            // This would parse the interface range and apply the template
            showAlert('Bulk configuration feature coming soon!', 'info');
        }

        // Test function for debugging
        function testCommandExecution() {
            console.log('Testing command execution...');
            executeCommand('show version', function(data) {
                console.log('Test command result:', data);
                if (data.success) {
                    showAlert('Test command executed successfully!', 'success');
                } else {
                    showAlert('Test command failed!', 'danger');
                }
            }, 'cli_show');
        }

        // Test function for configuration commands
        function testConfigCommand() {
            console.log('Testing configuration command...');
            executeCommand('configure terminal', function(data) {
                console.log('Config command result:', data);
                if (data.success) {
                    showAlert('Config command executed successfully!', 'success');
                } else {
                    showAlert('Config command failed!', 'danger');
                }
            }, 'cli_conf');
        }

        // Test function to check form values
        function testFormValues() {
            console.log('=== FORM VALUES TEST ===');
            console.log('Interface Description:', document.getElementById('interface-description').value);
            console.log('Interface Mode:', document.getElementById('interface-mode').value);
            console.log('Admin State:', document.getElementById('admin-state').value);
            console.log('Speed:', document.getElementById('interface-speed').value);
            console.log('Duplex:', document.getElementById('interface-duplex').value);
            console.log('Access VLAN:', document.getElementById('access-vlan').value);
            console.log('Native VLAN:', document.getElementById('native-vlan').value);
            console.log('Allowed VLANs:', document.getElementById('allowed-vlans').value);
            console.log('IP Address:', document.getElementById('ip-address').value);
            console.log('CDP Enable:', document.getElementById('cdp-enable').checked);
            console.log('LLDP Enable:', document.getElementById('lldp-enable').checked);
            console.log('Storm Control:', document.getElementById('storm-control').checked);
            console.log('Flow Control:', document.getElementById('flow-control').checked);
            console.log('=== END FORM VALUES TEST ===');
        }

        // Test function to simulate a complete configuration
        function testCompleteConfig() {
            console.log('=== TESTING COMPLETE CONFIGURATION ===');
            
            // Select an interface
            const interfaceSelect = document.getElementById('interface-select');
            if (interfaceSelect.options.length > 0) {
                interfaceSelect.options[0].selected = true;
            }
            
            // Set some form values
            document.getElementById('interface-description').value = 'Test Interface';
            document.getElementById('interface-mode').value = 'switchport-access';
            document.getElementById('access-vlan').value = '10';
            document.getElementById('admin-state').value = 'no shutdown';
            document.getElementById('cdp-enable').checked = true;
            document.getElementById('lldp-enable').checked = false;
            
            // Show the access config
            document.getElementById('access-config').style.display = 'block';
            
            console.log('Form values set. Now test preview or apply configuration.');
            showAlert('Test configuration set. Try preview or apply now.', 'info');
        }

        // Function to save current configuration as template
        function saveAsTemplate() {
            const templateName = prompt('Enter a name for this template:');
            if (!templateName) return;
            
            const config = {
                description: document.getElementById('interface-description').value,
                mode: document.getElementById('interface-mode').value,
                adminState: document.getElementById('admin-state').value,
                speed: document.getElementById('interface-speed').value,
                duplex: document.getElementById('interface-duplex').value,
                accessVlan: document.getElementById('access-vlan').value,
                nativeVlan: document.getElementById('native-vlan').value,
                allowedVlans: document.getElementById('allowed-vlans').value,
                ipAddress: document.getElementById('ip-address').value,
                prefixLength: document.getElementById('prefix-length').value,
                secondaryIps: document.getElementById('secondary-ips').value,
                mtu: document.getElementById('mtu-size').value,
                loadInterval: document.getElementById('load-interval').value,
                bandwidth: document.getElementById('bandwidth').value,
                cdpEnable: document.getElementById('cdp-enable').checked,
                lldpEnable: document.getElementById('lldp-enable').checked,
                stormControl: document.getElementById('storm-control').checked,
                flowControl: document.getElementById('flow-control').checked,
                trunkEncapsulation: document.getElementById('trunk-encapsulation').checked
            };
            
            // Save to localStorage for now (could be enhanced to save to server)
            const templates = JSON.parse(localStorage.getItem('interfaceTemplates') || '{}');
            templates[templateName] = config;
            localStorage.setItem('interfaceTemplates', JSON.stringify(templates));
            
            showAlert(`Template "${templateName}" saved successfully!`, 'success');
        }

        // Function to load a saved template
        function loadTemplate(templateName) {
            const templates = JSON.parse(localStorage.getItem('interfaceTemplates') || '{}');
            const config = templates[templateName];
            
            if (!config) {
                showAlert(`Template "${templateName}" not found.`, 'warning');
                return;
            }
            
            // Apply the template
            document.getElementById('interface-description').value = config.description || '';
            document.getElementById('interface-mode').value = config.mode || '';
            document.getElementById('admin-state').value = config.adminState || '';
            document.getElementById('interface-speed').value = config.speed || '';
            document.getElementById('interface-duplex').value = config.duplex || '';
            document.getElementById('access-vlan').value = config.accessVlan || '';
            document.getElementById('native-vlan').value = config.nativeVlan || '';
            document.getElementById('allowed-vlans').value = config.allowedVlans || '';
            document.getElementById('ip-address').value = config.ipAddress || '';
            document.getElementById('prefix-length').value = config.prefixLength || '24';
            document.getElementById('secondary-ips').value = config.secondaryIps || '';
            document.getElementById('mtu-size').value = config.mtu || '';
            document.getElementById('load-interval').value = config.loadInterval || '';
            document.getElementById('bandwidth').value = config.bandwidth || '';
            document.getElementById('cdp-enable').checked = config.cdpEnable || false;
            document.getElementById('lldp-enable').checked = config.lldpEnable || false;
            document.getElementById('storm-control').checked = config.stormControl || false;
            document.getElementById('flow-control').checked = config.flowControl || false;
            document.getElementById('trunk-encapsulation').checked = config.trunkEncapsulation || false;
            
            // Show appropriate config section
            const mode = config.mode;
            document.getElementById('access-config').style.display = 'none';
            document.getElementById('trunk-config').style.display = 'none';
            document.getElementById('routed-config').style.display = 'none';
            
            if (mode === 'switchport-access') {
                document.getElementById('access-config').style.display = 'block';
            } else if (mode === 'switchport-trunk') {
                document.getElementById('trunk-config').style.display = 'block';
            } else if (mode === 'routed') {
                document.getElementById('routed-config').style.display = 'block';
            }
            
            showAlert(`Template "${templateName}" loaded successfully!`, 'success');
        }
    </script>
</body>
</html>

