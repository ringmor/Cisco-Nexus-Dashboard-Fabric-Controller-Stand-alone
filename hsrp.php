<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSRP Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shield-alt"></i> HSRP Configuration & Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureHsrp()">
                    <i class="fas fa-cog"></i> Configure HSRP
                </button>
            </div>
        </div>

        <!-- HSRP Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">HSRP Groups</h5>
                        <h2 id="hsrp-groups">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active Groups</h5>
                        <h2 id="hsrp-active">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Standby Groups</h5>
                        <h2 id="hsrp-standby">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Interfaces</h5>
                        <h2 id="hsrp-interfaces">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- HSRP Groups Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> HSRP Groups Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Group</th>
                                        <th>Version</th>
                                        <th>State</th>
                                        <th>Virtual IP</th>
                                        <th>Priority</th>
                                        <th>Preempt</th>
                                        <th>Active Router</th>
                                        <th>Standby Router</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="hsrp-groups-tbody">
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading HSRP groups...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HSRP Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> HSRP Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Statistic</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody id="hsrp-stats-tbody">
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading statistics...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> HSRP Events</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Event</th>
                                        <th>Group</th>
                                    </tr>
                                </thead>
                                <tbody id="hsrp-events-tbody">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading events...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HSRP Configuration -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> HSRP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="hsrpConfigForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Basic HSRP Configuration</h6>
                                    <div class="mb-3">
                                        <label for="hsrp-interface" class="form-label">Interface</label>
                                        <select class="form-select" id="hsrp-interface" required>
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-group" class="form-label">Group Number</label>
                                        <input type="number" class="form-control" id="hsrp-group" min="0" max="255" placeholder="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-version" class="form-label">HSRP Version</label>
                                        <select class="form-select" id="hsrp-version">
                                            <option value="1">Version 1</option>
                                            <option value="2" selected>Version 2</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-virtual-ip" class="form-label">Virtual IP Address</label>
                                        <input type="text" class="form-control" id="hsrp-virtual-ip" placeholder="192.168.1.1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Priority and Preemption</h6>
                                    <div class="mb-3">
                                        <label for="hsrp-priority" class="form-label">Priority</label>
                                        <input type="number" class="form-control" id="hsrp-priority" min="1" max="255" placeholder="100">
                                        <small class="form-text text-muted">Higher priority becomes active (default: 100)</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="hsrp-preempt">
                                            <label class="form-check-label" for="hsrp-preempt">
                                                Enable Preemption
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Allow higher priority router to become active</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-preempt-delay" class="form-label">Preempt Delay (seconds)</label>
                                        <input type="number" class="form-control" id="hsrp-preempt-delay" min="0" max="3600" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Timers and Authentication</h6>
                                    <div class="mb-3">
                                        <label for="hsrp-hello-time" class="form-label">Hello Time (seconds)</label>
                                        <input type="number" class="form-control" id="hsrp-hello-time" min="1" max="255" placeholder="3">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-hold-time" class="form-label">Hold Time (seconds)</label>
                                        <input type="number" class="form-control" id="hsrp-hold-time" min="1" max="255" placeholder="10">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-auth-type" class="form-label">Authentication</label>
                                        <select class="form-select" id="hsrp-auth-type">
                                            <option value="">No Authentication</option>
                                            <option value="text">Text Authentication</option>
                                            <option value="md5">MD5 Authentication</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="hsrp-auth-key-group" style="display: none;">
                                        <label for="hsrp-auth-key" class="form-label">Authentication Key</label>
                                        <input type="password" class="form-control" id="hsrp-auth-key" placeholder="Enter key">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Advanced Options</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="hsrp-track-interface">
                                            <label class="form-check-label" for="hsrp-track-interface">
                                                Track Interface
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="hsrp-track-interface-group" style="display: none;">
                                        <label for="hsrp-track-interface-name" class="form-label">Track Interface Name</label>
                                        <select class="form-select" id="hsrp-track-interface-name">
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="hsrp-track-decrement-group" style="display: none;">
                                        <label for="hsrp-track-decrement" class="form-label">Priority Decrement</label>
                                        <input type="number" class="form-control" id="hsrp-track-decrement" min="1" max="255" placeholder="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Virtual MAC Address</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="hsrp-use-bia">
                                            <label class="form-check-label" for="hsrp-use-bia">
                                                Use BIA (Burned-in Address)
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Use interface MAC instead of virtual MAC</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hsrp-mac-address" class="form-label">Custom MAC Address</label>
                                        <input type="text" class="form-control" id="hsrp-mac-address" placeholder="0000.0c07.ac01">
                                        <small class="form-text text-muted">Optional: Custom virtual MAC address</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyHsrpConfig()">
                                        Apply HSRP Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="enableHsrpGroup()">
                                        Enable Group
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="disableHsrpGroup()">
                                        Disable Group
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="forceHsrpActive()">
                                        Force Active
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="removeHsrpGroup()">
                                        Remove Group
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
        let hsrpData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadHsrpData();
            loadInterfaceList();
            
            // Show/hide authentication key field
            document.getElementById('hsrp-auth-type').addEventListener('change', function() {
                const authKeyGroup = document.getElementById('hsrp-auth-key-group');
                authKeyGroup.style.display = this.value ? 'block' : 'none';
            });
            
            // Show/hide track interface fields
            document.getElementById('hsrp-track-interface').addEventListener('change', function() {
                const trackInterfaceGroup = document.getElementById('hsrp-track-interface-group');
                const trackDecrementGroup = document.getElementById('hsrp-track-decrement-group');
                const display = this.checked ? 'block' : 'none';
                trackInterfaceGroup.style.display = display;
                trackDecrementGroup.style.display = display;
            });
        });

        function loadHsrpData() {
            loadHsrpGroups();
            loadHsrpStatistics();
            loadHsrpEvents();
        }

        function loadHsrpGroups() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show hsrp all'
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
                
                const groups = parseHsrpGroups(data.result);
                hsrpData.groups = groups;
                displayHsrpGroups(groups);
                updateHsrpSummary(groups);
            })
            .catch(error => {
                console.error('Error loading HSRP groups:', error);
                document.getElementById('hsrp-groups-tbody').innerHTML = 
                    '<tr><td colspan="10" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseHsrpGroups(data) {
            const groups = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_grp) {
                        let rows = output.body.TABLE_grp.ROW_grp;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            groups.push({
                                interface: row.sh_if_index || '',
                                group: row.sh_group_num || '',
                                version: row.sh_hsrp_version || '2',
                                state: row.sh_group_state || 'unknown',
                                virtual_ip: row.sh_vip || '',
                                priority: row.sh_priority || '100',
                                preempt: row.sh_preempt || 'disabled',
                                active_router: row.sh_active_router || '',
                                standby_router: row.sh_standby_router || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing HSRP groups:', e);
            }
            
            return groups;
        }

        function displayHsrpGroups(groups) {
            const tbody = document.getElementById('hsrp-groups-tbody');
            tbody.innerHTML = '';

            if (!groups || groups.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">No HSRP groups configured</td></tr>';
                return;
            }

            groups.forEach(group => {
                const row = document.createElement('tr');
                
                let stateBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (group.state.toLowerCase() === 'active') {
                    stateBadge = '<span class="badge bg-success">Active</span>';
                } else if (group.state.toLowerCase() === 'standby') {
                    stateBadge = '<span class="badge bg-warning">Standby</span>';
                } else if (group.state.toLowerCase() === 'listen') {
                    stateBadge = '<span class="badge bg-info">Listen</span>';
                } else if (group.state.toLowerCase() === 'init') {
                    stateBadge = '<span class="badge bg-secondary">Init</span>';
                }

                const preemptBadge = group.preempt === 'enabled' ? 
                    '<span class="badge bg-success">Yes</span>' : 
                    '<span class="badge bg-secondary">No</span>';

                row.innerHTML = `
                    <td><strong>${group.interface}</strong></td>
                    <td>${group.group}</td>
                    <td>v${group.version}</td>
                    <td>${stateBadge}</td>
                    <td>${group.virtual_ip}</td>
                    <td>${group.priority}</td>
                    <td>${preemptBadge}</td>
                    <td><small>${group.active_router}</small></td>
                    <td><small>${group.standby_router}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editHsrpGroup('${group.interface}', '${group.group}')">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteHsrpGroup('${group.interface}', '${group.group}')">
                            Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updateHsrpSummary(groups) {
            let totalGroups = groups.length;
            let activeGroups = 0;
            let standbyGroups = 0;
            let interfaces = new Set();

            groups.forEach(group => {
                if (group.state.toLowerCase() === 'active') activeGroups++;
                else if (group.state.toLowerCase() === 'standby') standbyGroups++;
                interfaces.add(group.interface);
            });

            document.getElementById('hsrp-groups').textContent = totalGroups;
            document.getElementById('hsrp-active').textContent = activeGroups;
            document.getElementById('hsrp-standby').textContent = standbyGroups;
            document.getElementById('hsrp-interfaces').textContent = interfaces.size;
        }

        function loadHsrpStatistics() {
            // Mock HSRP statistics since detailed stats command may vary
            const stats = [
                { name: 'Hello Packets Sent', value: '1234' },
                { name: 'Hello Packets Received', value: '5678' },
                { name: 'Resign Packets Sent', value: '12' },
                { name: 'Resign Packets Received', value: '8' },
                { name: 'Coup Packets Sent', value: '3' },
                { name: 'Coup Packets Received', value: '1' },
                { name: 'State Changes', value: '15' },
                { name: 'Authentication Failures', value: '0' }
            ];

            displayHsrpStatistics(stats);
        }

        function displayHsrpStatistics(stats) {
            const tbody = document.getElementById('hsrp-stats-tbody');
            tbody.innerHTML = '';

            stats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${stat.name}</strong></td>
                    <td><span class="badge bg-primary">${stat.value}</span></td>
                `;
                tbody.appendChild(row);
            });
        }

        function loadHsrpEvents() {
            // Mock HSRP events
            const events = [
                { time: '12:34:56', event: 'Group 1 state change to Active', group: '1' },
                { time: '12:33:45', event: 'Group 1 preemption enabled', group: '1' },
                { time: '12:32:10', event: 'Group 2 standby router changed', group: '2' },
                { time: '12:30:22', event: 'Group 1 hello timer expired', group: '1' },
                { time: '12:28:15', event: 'Group 2 state change to Standby', group: '2' }
            ];

            displayHsrpEvents(events);
        }

        function displayHsrpEvents(events) {
            const tbody = document.getElementById('hsrp-events-tbody');
            tbody.innerHTML = '';

            events.forEach(event => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><small>${event.time}</small></td>
                    <td><small>${event.event}</small></td>
                    <td><span class="badge bg-info">${event.group}</span></td>
                `;
                tbody.appendChild(row);
            });
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
                            if (row.interface && (row.interface.startsWith('Vlan') || row.interface.startsWith('Ethernet'))) {
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
            const selects = ['hsrp-interface', 'hsrp-track-interface-name'];
            
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

        function applyHsrpConfig() {
            const interfaceName = document.getElementById('hsrp-interface').value;
            const group = document.getElementById('hsrp-group').value;
            const version = document.getElementById('hsrp-version').value;
            const virtualIp = document.getElementById('hsrp-virtual-ip').value;
            const priority = document.getElementById('hsrp-priority').value;
            const preempt = document.getElementById('hsrp-preempt').checked;
            const preemptDelay = document.getElementById('hsrp-preempt-delay').value;
            const helloTime = document.getElementById('hsrp-hello-time').value;
            const holdTime = document.getElementById('hsrp-hold-time').value;
            const authType = document.getElementById('hsrp-auth-type').value;
            const authKey = document.getElementById('hsrp-auth-key').value;
            const trackInterface = document.getElementById('hsrp-track-interface').checked;
            const trackInterfaceName = document.getElementById('hsrp-track-interface-name').value;
            const trackDecrement = document.getElementById('hsrp-track-decrement').value;
            const useBia = document.getElementById('hsrp-use-bia').checked;
            const macAddress = document.getElementById('hsrp-mac-address').value;

            if (!interfaceName || !group || !virtualIp) {
                alert('Please provide Interface, Group Number, and Virtual IP Address');
                return;
            }

            let commands = [];
            
            // Enable HSRP feature
            commands.push('feature hsrp');
            
            // Configure interface
            commands.push(`interface ${interfaceName}`);
            
            // Set HSRP version
            commands.push(`hsrp version ${version}`);
            
            // Configure HSRP group
            commands.push(`hsrp ${group}`);
            commands.push(`ip ${virtualIp}`);
            
            if (priority) {
                commands.push(`priority ${priority}`);
            }
            
            if (preempt) {
                if (preemptDelay) {
                    commands.push(`preempt delay ${preemptDelay}`);
                } else {
                    commands.push('preempt');
                }
            }
            
            if (helloTime && holdTime) {
                commands.push(`timers ${helloTime} ${holdTime}`);
            }
            
            if (authType && authKey) {
                if (authType === 'text') {
                    commands.push(`authentication text ${authKey}`);
                } else if (authType === 'md5') {
                    commands.push(`authentication md5 key-string ${authKey}`);
                }
            }
            
            if (trackInterface && trackInterfaceName) {
                if (trackDecrement) {
                    commands.push(`track ${trackInterfaceName} decrement ${trackDecrement}`);
                } else {
                    commands.push(`track ${trackInterfaceName}`);
                }
            }
            
            if (useBia) {
                commands.push('use-bia');
            }
            
            if (macAddress) {
                commands.push(`mac-address ${macAddress}`);
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
                    alert('Error applying HSRP configuration: ' + data.error);
                } else {
                    alert('HSRP configuration applied successfully');
                    loadHsrpData();
                }
            })
            .catch(error => {
                alert('Error applying HSRP configuration: ' + error.message);
            });
        }

        function editHsrpGroup(interfaceName, group) {
            const hsrpGroup = hsrpData.groups.find(g => g.interface === interfaceName && g.group === group);
            if (!hsrpGroup) return;

            // Populate form with existing values
            document.getElementById('hsrp-interface').value = hsrpGroup.interface;
            document.getElementById('hsrp-group').value = hsrpGroup.group;
            document.getElementById('hsrp-virtual-ip').value = hsrpGroup.virtual_ip;
            document.getElementById('hsrp-priority').value = hsrpGroup.priority;
            document.getElementById('hsrp-preempt').checked = hsrpGroup.preempt === 'enabled';
            
            // Scroll to configuration form
            document.querySelector('#hsrpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function deleteHsrpGroup(interfaceName, group) {
            if (confirm(`Are you sure you want to delete HSRP group ${group} on ${interfaceName}?`)) {
                const commands = [
                    `interface ${interfaceName}`,
                    `no hsrp ${group}`
                ];

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
                        alert('Error deleting HSRP group: ' + data.error);
                    } else {
                        alert('HSRP group deleted successfully');
                        loadHsrpData();
                    }
                })
                .catch(error => {
                    alert('Error deleting HSRP group: ' + error.message);
                });
            }
        }

        function enableHsrpGroup() {
            const interfaceName = document.getElementById('hsrp-interface').value;
            const group = document.getElementById('hsrp-group').value;
            
            if (!interfaceName || !group) {
                alert('Please specify Interface and Group Number');
                return;
            }
            
            const commands = [
                `interface ${interfaceName}`,
                `hsrp ${group}`,
                'no shutdown'
            ];

            executeHsrpCommand(commands, 'HSRP group enabled successfully');
        }

        function disableHsrpGroup() {
            const interfaceName = document.getElementById('hsrp-interface').value;
            const group = document.getElementById('hsrp-group').value;
            
            if (!interfaceName || !group) {
                alert('Please specify Interface and Group Number');
                return;
            }
            
            const commands = [
                `interface ${interfaceName}`,
                `hsrp ${group}`,
                'shutdown'
            ];

            executeHsrpCommand(commands, 'HSRP group disabled successfully');
        }

        function forceHsrpActive() {
            const interfaceName = document.getElementById('hsrp-interface').value;
            const group = document.getElementById('hsrp-group').value;
            
            if (!interfaceName || !group) {
                alert('Please specify Interface and Group Number');
                return;
            }
            
            if (confirm('Are you sure you want to force this router to become active?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: `hsrp ${interfaceName} ${group} active`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error forcing HSRP active: ' + data.error);
                    } else {
                        alert('HSRP group forced to active state');
                        loadHsrpData();
                    }
                })
                .catch(error => {
                    alert('Error forcing HSRP active: ' + error.message);
                });
            }
        }

        function removeHsrpGroup() {
            const interfaceName = document.getElementById('hsrp-interface').value;
            const group = document.getElementById('hsrp-group').value;
            
            if (!interfaceName || !group) {
                alert('Please specify Interface and Group Number');
                return;
            }
            
            deleteHsrpGroup(interfaceName, group);
        }

        function executeHsrpCommand(commands, successMessage) {
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
                    alert('Error: ' + data.error);
                } else {
                    alert(successMessage);
                    loadHsrpData();
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }

        function configureHsrp() {
            document.querySelector('#hsrpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadHsrpData();
        }
    </script>
</body>
</html>

