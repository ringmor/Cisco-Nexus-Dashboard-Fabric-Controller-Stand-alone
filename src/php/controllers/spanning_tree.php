<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spanning Tree Protocol - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-project-diagram"></i> Spanning Tree Protocol</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureStp()">
                    <i class="fas fa-cog"></i> Configure STP
                </button>
            </div>
        </div>

        <!-- STP Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">STP Mode</h5>
                        <h3 id="stp-mode">Loading...</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Root Bridge</h5>
                        <h6 id="stp-root-bridge">Loading...</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">VLAN Instances</h5>
                        <h2 id="stp-instances">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Blocked Ports</h5>
                        <h2 id="stp-blocked-ports">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- STP Summary and Root Bridge Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> STP Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td><strong>STP Mode:</strong></td><td id="stp-mode-detail">Loading...</td></tr>
                            <tr><td><strong>Bridge Priority:</strong></td><td id="stp-bridge-priority">Loading...</td></tr>
                            <tr><td><strong>Bridge ID:</strong></td><td id="stp-bridge-id">Loading...</td></tr>
                            <tr><td><strong>Root Priority:</strong></td><td id="stp-root-priority">Loading...</td></tr>
                            <tr><td><strong>Root ID:</strong></td><td id="stp-root-id">Loading...</td></tr>
                            <tr><td><strong>Root Port:</strong></td><td id="stp-root-port">Loading...</td></tr>
                            <tr><td><strong>Root Cost:</strong></td><td id="stp-root-cost">Loading...</td></tr>
                            <tr><td><strong>Hello Time:</strong></td><td id="stp-hello-time">Loading...</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> STP Instances</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Instance</th>
                                        <th>VLANs</th>
                                        <th>Root Bridge</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="stp-instances-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading STP instances...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STP Interface Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> STP Interface Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>VLAN</th>
                                        <th>Role</th>
                                        <th>State</th>
                                        <th>Cost</th>
                                        <th>Priority</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="stp-interfaces-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading STP interfaces...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STP Configuration -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> STP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="stpConfigForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Global STP Configuration</h6>
                                    <div class="mb-3">
                                        <label for="stp-mode-select" class="form-label">STP Mode</label>
                                        <select class="form-select" id="stp-mode-select">
                                            <option value="pvst">PVST+</option>
                                            <option value="rapid-pvst">Rapid PVST+</option>
                                            <option value="mst">MST (Multiple Spanning Tree)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-bridge-priority" class="form-label">Bridge Priority</label>
                                        <select class="form-select" id="stp-bridge-priority">
                                            <option value="4096">4096</option>
                                            <option value="8192">8192</option>
                                            <option value="12288">12288</option>
                                            <option value="16384">16384</option>
                                            <option value="20480">20480</option>
                                            <option value="24576">24576</option>
                                            <option value="28672">28672</option>
                                            <option value="32768" selected>32768 (Default)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-vlan" class="form-label">VLAN (for PVST+)</label>
                                        <input type="text" class="form-control" id="stp-vlan" placeholder="1,10,20 or 1-100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Interface Configuration</h6>
                                    <div class="mb-3">
                                        <label for="stp-interface" class="form-label">Interface</label>
                                        <select class="form-select" id="stp-interface">
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-interface-cost" class="form-label">Interface Cost</label>
                                        <input type="number" class="form-control" id="stp-interface-cost" min="1" max="200000000" placeholder="Auto">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-interface-priority" class="form-label">Interface Priority</label>
                                        <select class="form-select" id="stp-interface-priority">
                                            <option value="0">0</option>
                                            <option value="16">16</option>
                                            <option value="32">32</option>
                                            <option value="48">48</option>
                                            <option value="64">64</option>
                                            <option value="80">80</option>
                                            <option value="96">96</option>
                                            <option value="112">112</option>
                                            <option value="128" selected>128 (Default)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Advanced Options</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="stp-portfast">
                                            <label class="form-check-label" for="stp-portfast">
                                                Enable PortFast
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Skip listening and learning states</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="stp-bpdu-guard">
                                            <label class="form-check-label" for="stp-bpdu-guard">
                                                Enable BPDU Guard
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Disable port on BPDU reception</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="stp-bpdu-filter">
                                            <label class="form-check-label" for="stp-bpdu-filter">
                                                Enable BPDU Filter
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Filter BPDUs on edge ports</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="stp-root-guard">
                                            <label class="form-check-label" for="stp-root-guard">
                                                Enable Root Guard
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Prevent root bridge changes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>MST Configuration</h6>
                                    <div class="mb-3">
                                        <label for="stp-mst-instance" class="form-label">MST Instance</label>
                                        <input type="number" class="form-control" id="stp-mst-instance" min="0" max="4094" placeholder="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-mst-vlans" class="form-label">VLANs for MST Instance</label>
                                        <input type="text" class="form-control" id="stp-mst-vlans" placeholder="1-100,200,300">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-mst-name" class="form-label">MST Region Name</label>
                                        <input type="text" class="form-control" id="stp-mst-name" placeholder="Region1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Timers</h6>
                                    <div class="mb-3">
                                        <label for="stp-hello-timer" class="form-label">Hello Time (seconds)</label>
                                        <input type="number" class="form-control" id="stp-hello-timer" min="1" max="10" placeholder="2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-forward-delay" class="form-label">Forward Delay (seconds)</label>
                                        <input type="number" class="form-control" id="stp-forward-delay" min="4" max="30" placeholder="15">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stp-max-age" class="form-label">Max Age (seconds)</label>
                                        <input type="number" class="form-control" id="stp-max-age" min="6" max="40" placeholder="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyStpConfig()">
                                        Apply STP Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="enableStp()">
                                        Enable STP
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="disableStp()">
                                        Disable STP
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="clearStpCounters()">
                                        Clear Counters
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="resetStpConfig()">
                                        Reset to Default
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
        let stpData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadStpData();
            loadInterfaceList();
        });

        function loadStpData() {
            loadStpSummary();
            loadStpInstances();
            loadStpInterfaces();
        }

        function loadStpStatus() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show spanning-tree summary'
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
                
                const stpInfo = parseStpStatus(data.result);
                displayStpStatus(stpInfo);
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            })
            .catch(error => {
                console.error('Error loading STP status:', error);
                document.getElementById('stp-status').innerHTML = 
                    '<div class="alert alert-danger">Error loading STP status</div>';
            });
        }

        function loadStpInstances() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show spanning-tree'
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
                
                const instances = parseStpInstances(data.result);
                displayStpInstances(instances);
            })
            .catch(error => {
                console.error('Error loading STP instances:', error);
                document.getElementById('stp-instances-tbody').innerHTML = 
                    '<tr><td colspan="4" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadStpInterfaces() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show spanning-tree interface brief'
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
                
                const interfaces = parseStpInterfaces(data.result);
                displayStpInterfaces(interfaces);
            })
            .catch(error => {
                console.error('Error loading STP interfaces:', error);
                document.getElementById('stp-interfaces-tbody').innerHTML = 
                    '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadStpVlans() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show spanning-tree vlan'
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
                
                const vlans = parseStpVlans(data.result);
                displayStpVlans(vlans);
            })
            .catch(error => {
                console.error('Error loading STP VLANs:', error);
                document.getElementById('stp-vlans').innerHTML = 
                    '<div class="alert alert-danger">Error loading STP VLANs</div>';
            });
        }

        function parseStpSummary(data) {
            const summary = {
                mode: 'Unknown',
                bridge_priority: 'Unknown',
                bridge_id: 'Unknown',
                root_priority: 'Unknown',
                root_id: 'Unknown',
                root_port: 'Unknown',
                root_cost: 'Unknown',
                hello_time: 'Unknown'
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const body = output.body;
                        
                        summary.mode = body.stp_mode || 'Unknown';
                        summary.bridge_priority = body.bridge_priority || 'Unknown';
                        summary.bridge_id = body.bridge_id || 'Unknown';
                        summary.root_priority = body.root_priority || 'Unknown';
                        summary.root_id = body.root_id || 'Unknown';
                        summary.root_port = body.root_port || 'Unknown';
                        summary.root_cost = body.root_cost || 'Unknown';
                        summary.hello_time = body.hello_time || 'Unknown';
                    }
                }
            } catch (e) {
                console.error('Error parsing STP summary:', e);
            }
            
            return summary;
        }

        function parseStpInstances(data) {
            const instances = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_cist) {
                        let rows = output.body.TABLE_cist.ROW_cist;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            instances.push({
                                instance: row.instance_id || '0',
                                vlans: row.vlan_list || '1-4094',
                                root_bridge: row.root_bridge || 'Unknown',
                                status: row.stp_state || 'unknown'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing STP instances:', e);
            }
            
            return instances;
        }

        function parseStpInterfaces(data) {
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
                            interfaces.push({
                                interface: row.interface || '',
                                vlan: row.vlan || '1',
                                role: row.port_role || 'unknown',
                                state: row.port_state || 'unknown',
                                cost: row.port_cost || '0',
                                priority: row.port_priority || '128',
                                type: row.port_type || 'P2P'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing STP interfaces:', e);
            }
            
            return interfaces;
        }

        function displayStpSummary(summary) {
            document.getElementById('stp-mode').textContent = summary.mode;
            document.getElementById('stp-root-bridge').textContent = summary.root_id.substring(0, 20) + '...';
            
            document.getElementById('stp-mode-detail').textContent = summary.mode;
            document.getElementById('stp-bridge-priority').textContent = summary.bridge_priority;
            document.getElementById('stp-bridge-id').textContent = summary.bridge_id;
            document.getElementById('stp-root-priority').textContent = summary.root_priority;
            document.getElementById('stp-root-id').textContent = summary.root_id;
            document.getElementById('stp-root-port').textContent = summary.root_port;
            document.getElementById('stp-root-cost').textContent = summary.root_cost;
            document.getElementById('stp-hello-time').textContent = summary.hello_time;
        }

        function displayStpInstances(instances) {
            const tbody = document.getElementById('stp-instances-tbody');
            tbody.innerHTML = '';

            if (!instances || instances.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No STP instances found</td></tr>';
                return;
            }

            document.getElementById('stp-instances').textContent = instances.length;

            instances.forEach(instance => {
                const row = document.createElement('tr');
                
                let statusBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (instance.status.toLowerCase() === 'forwarding') {
                    statusBadge = '<span class="badge bg-success">Forwarding</span>';
                } else if (instance.status.toLowerCase() === 'blocking') {
                    statusBadge = '<span class="badge bg-danger">Blocking</span>';
                } else if (instance.status.toLowerCase() === 'learning') {
                    statusBadge = '<span class="badge bg-warning">Learning</span>';
                }

                row.innerHTML = `
                    <td><strong>${instance.instance}</strong></td>
                    <td><small>${instance.vlans}</small></td>
                    <td><small>${instance.root_bridge}</small></td>
                    <td>${statusBadge}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayStpInterfaces(interfaces) {
            const tbody = document.getElementById('stp-interfaces-tbody');
            tbody.innerHTML = '';

            if (!interfaces || interfaces.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No STP interfaces found</td></tr>';
                return;
            }

            let blockedPorts = 0;

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                let roleBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (intf.role.toLowerCase() === 'root') {
                    roleBadge = '<span class="badge bg-success">Root</span>';
                } else if (intf.role.toLowerCase() === 'designated') {
                    roleBadge = '<span class="badge bg-primary">Designated</span>';
                } else if (intf.role.toLowerCase() === 'alternate') {
                    roleBadge = '<span class="badge bg-warning">Alternate</span>';
                } else if (intf.role.toLowerCase() === 'backup') {
                    roleBadge = '<span class="badge bg-info">Backup</span>';
                }

                let stateBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (intf.state.toLowerCase() === 'forwarding') {
                    stateBadge = '<span class="badge bg-success">Forwarding</span>';
                } else if (intf.state.toLowerCase() === 'blocking') {
                    stateBadge = '<span class="badge bg-danger">Blocking</span>';
                    blockedPorts++;
                } else if (intf.state.toLowerCase() === 'learning') {
                    stateBadge = '<span class="badge bg-warning">Learning</span>';
                } else if (intf.state.toLowerCase() === 'listening') {
                    stateBadge = '<span class="badge bg-info">Listening</span>';
                }

                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>${intf.vlan}</td>
                    <td>${roleBadge}</td>
                    <td>${stateBadge}</td>
                    <td>${intf.cost}</td>
                    <td>${intf.priority}</td>
                    <td><small>${intf.type}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="configureStpInterface('${intf.interface}')">
                            Configure
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            document.getElementById('stp-blocked-ports').textContent = blockedPorts;
        }

        function displayStpError(error) {
            const elements = ['stp-mode', 'stp-root-bridge'];
            elements.forEach(id => {
                document.getElementById(id).textContent = 'Error';
            });
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
                    populateInterfaceSelect(interfaces);
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
                            if (row.interface && !row.interface.startsWith('mgmt')) {
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

        function populateInterfaceSelect(interfaces) {
            const select = document.getElementById('stp-interface');
            
            interfaces.forEach(intf => {
                const option = document.createElement('option');
                option.value = intf;
                option.textContent = intf;
                select.appendChild(option);
            });
        }

        function applyStpConfig() {
            const mode = document.getElementById('stp-mode-select').value;
            const bridgePriority = document.getElementById('stp-bridge-priority').value;
            const vlan = document.getElementById('stp-vlan').value;
            const interfaceName = document.getElementById('stp-interface').value;
            const interfaceCost = document.getElementById('stp-interface-cost').value;
            const interfacePriority = document.getElementById('stp-interface-priority').value;
            const portfast = document.getElementById('stp-portfast').checked;
            const bpduGuard = document.getElementById('stp-bpdu-guard').checked;
            const bpduFilter = document.getElementById('stp-bpdu-filter').checked;
            const rootGuard = document.getElementById('stp-root-guard').checked;

            let commands = [];
            
            // Enable spanning tree feature
            commands.push('feature spanning-tree');
            
            // Set STP mode
            commands.push(`spanning-tree mode ${mode}`);
            
            // Set bridge priority
            if (vlan) {
                commands.push(`spanning-tree vlan ${vlan} priority ${bridgePriority}`);
            } else {
                commands.push(`spanning-tree priority ${bridgePriority}`);
            }
            
            // Configure interface if specified
            if (interfaceName) {
                commands.push(`interface ${interfaceName}`);
                
                if (interfaceCost) {
                    if (vlan) {
                        commands.push(`spanning-tree vlan ${vlan} cost ${interfaceCost}`);
                    } else {
                        commands.push(`spanning-tree cost ${interfaceCost}`);
                    }
                }
                
                if (interfacePriority) {
                    if (vlan) {
                        commands.push(`spanning-tree vlan ${vlan} port-priority ${interfacePriority}`);
                    } else {
                        commands.push(`spanning-tree port-priority ${interfacePriority}`);
                    }
                }
                
                if (portfast) {
                    commands.push('spanning-tree port type edge');
                }
                
                if (bpduGuard) {
                    commands.push('spanning-tree bpduguard enable');
                }
                
                if (bpduFilter) {
                    commands.push('spanning-tree bpdufilter enable');
                }
                
                if (rootGuard) {
                    commands.push('spanning-tree guard root');
                }
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
                    alert('Error applying STP configuration: ' + data.error);
                } else {
                    alert('STP configuration applied successfully');
                    loadStpData();
                }
            })
            .catch(error => {
                alert('Error applying STP configuration: ' + error.message);
            });
        }

        function enableStp() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=feature spanning-tree&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error enabling STP: ' + data.error);
                } else {
                    alert('STP enabled successfully');
                    loadStpData();
                }
            })
            .catch(error => {
                alert('Error enabling STP: ' + error.message);
            });
        }

        function disableStp() {
            if (confirm('Are you sure you want to disable Spanning Tree Protocol?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=no feature spanning-tree&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error disabling STP: ' + data.error);
                    } else {
                        alert('STP disabled successfully');
                        loadStpData();
                    }
                })
                .catch(error => {
                    alert('Error disabling STP: ' + error.message);
                });
            }
        }

        function clearStpCounters() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=clear spanning-tree counters'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error clearing STP counters: ' + data.error);
                } else {
                    alert('STP counters cleared successfully');
                    loadStpData();
                }
            })
            .catch(error => {
                alert('Error clearing STP counters: ' + error.message);
            });
        }

        function resetStpConfig() {
            if (confirm('Are you sure you want to reset STP configuration to default?')) {
                const commands = [
                    'spanning-tree mode rapid-pvst',
                    'spanning-tree priority 32768'
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
                        alert('Error resetting STP configuration: ' + data.error);
                    } else {
                        alert('STP configuration reset successfully');
                        loadStpData();
                    }
                })
                .catch(error => {
                    alert('Error resetting STP configuration: ' + error.message);
                });
            }
        }

        function configureStpInterface(interfaceName) {
            document.getElementById('stp-interface').value = interfaceName;
            document.querySelector('#stpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function configureStp() {
            document.querySelector('#stpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadStpData();
        }
    </script>
</body>
</html>

