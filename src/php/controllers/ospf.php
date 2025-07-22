<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSPF Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-route"></i> OSPF Configuration & Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureOspf()">
                    <i class="fas fa-cog"></i> Configure OSPF
                </button>
            </div>
        </div>

        <!-- OSPF Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">OSPF Processes</h5>
                        <h2 id="ospf-processes">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active Neighbors</h5>
                        <h2 id="ospf-neighbors">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">LSA Count</h5>
                        <h2 id="ospf-lsas">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Areas</h5>
                        <h2 id="ospf-areas">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- OSPF Process Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> OSPF Process Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Process ID</th>
                                        <th>Router ID</th>
                                        <th>State</th>
                                        <th>Areas</th>
                                        <th>Interfaces</th>
                                    </tr>
                                </thead>
                                <tbody id="ospf-process-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading OSPF processes...
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
                        <h5><i class="fas fa-users"></i> OSPF Neighbors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Neighbor ID</th>
                                        <th>State</th>
                                        <th>Interface</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody id="ospf-neighbors-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading OSPF neighbors...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OSPF Interfaces and Database -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> OSPF Interfaces</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Interface</th>
                                        <th>Area</th>
                                        <th>State</th>
                                        <th>Cost</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody id="ospf-interfaces-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading OSPF interfaces...
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
                        <h5><i class="fas fa-database"></i> OSPF Database Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>LSA Type</th>
                                        <th>Count</th>
                                        <th>Checksum</th>
                                    </tr>
                                </thead>
                                <tbody id="ospf-database-tbody">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading OSPF database...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OSPF Configuration -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> OSPF Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="ospfConfigForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ospf-process-id" class="form-label">Process ID</label>
                                        <input type="number" class="form-control" id="ospf-process-id" min="1" max="65535" placeholder="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-router-id" class="form-label">Router ID</label>
                                        <input type="text" class="form-control" id="ospf-router-id" placeholder="1.1.1.1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-area" class="form-label">Area</label>
                                        <input type="text" class="form-control" id="ospf-area" placeholder="0.0.0.0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ospf-network" class="form-label">Network</label>
                                        <input type="text" class="form-control" id="ospf-network" placeholder="192.168.1.0/24">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-interface" class="form-label">Interface (Optional)</label>
                                        <select class="form-select" id="ospf-interface">
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-cost" class="form-label">Interface Cost</label>
                                        <input type="number" class="form-control" id="ospf-cost" min="1" max="65535" placeholder="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ospf-priority" class="form-label">Interface Priority</label>
                                        <input type="number" class="form-control" id="ospf-priority" min="0" max="255" placeholder="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-hello-interval" class="form-label">Hello Interval</label>
                                        <input type="number" class="form-control" id="ospf-hello-interval" min="1" max="65535" placeholder="10">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ospf-dead-interval" class="form-label">Dead Interval</label>
                                        <input type="number" class="form-control" id="ospf-dead-interval" min="1" max="65535" placeholder="40">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ospf-passive-interface">
                                            <label class="form-check-label" for="ospf-passive-interface">
                                                Passive Interface
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ospf-area-stub">
                                            <label class="form-check-label" for="ospf-area-stub">
                                                Stub Area
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ospf-area-nssa">
                                            <label class="form-check-label" for="ospf-area-nssa">
                                                NSSA Area
                                            </label>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="applyOspfConfig()">
                                        Apply OSPF Configuration
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="removeOspfProcess()">
                                        Remove OSPF Process
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="clearOspfProcess()">
                                        Clear OSPF Process
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
        let ospfData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadOspfData();
            loadInterfaceList();
        });

        function loadOspfData() {
            loadOspfProcesses();
            loadOspfNeighbors();
            loadOspfInterfaces();
            loadOspfDatabase();
        }

        function loadOspfProcesses() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show ip ospf'
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
                
                const processes = parseOspfProcesses(data.result);
                displayOspfProcesses(processes);
                updateOspfSummary(processes);
            })
            .catch(error => {
                console.error('Error loading OSPF processes:', error);
                document.getElementById('ospf-process-tbody').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadOspfNeighbors() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show ip ospf neighbor'
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
                
                const neighbors = parseOspfNeighbors(data.result);
                displayOspfNeighbors(neighbors);
            })
            .catch(error => {
                console.error('Error loading OSPF neighbors:', error);
                document.getElementById('ospf-neighbors-tbody').innerHTML = 
                    '<tr><td colspan="4" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadOspfInterfaces() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show ip ospf interface'
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
                
                const interfaces = parseOspfInterfaces(data.result);
                displayOspfInterfaces(interfaces);
            })
            .catch(error => {
                console.error('Error loading OSPF interfaces:', error);
                document.getElementById('ospf-interfaces-tbody').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadOspfDatabase() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show ip ospf database'
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
                
                const database = parseOspfDatabase(data.result);
                displayOspfDatabase(database);
            })
            .catch(error => {
                console.error('Error loading OSPF database:', error);
                document.getElementById('ospf-database-tbody').innerHTML = 
                    '<tr><td colspan="3" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseOspfProcesses(data) {
            const processes = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_ctx) {
                        let rows = output.body.TABLE_ctx.ROW_ctx;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            processes.push({
                                process_id: row.instance_tag || '',
                                router_id: row.router_id || '',
                                state: row.admin_state || 'unknown',
                                areas: row.area_count || '0',
                                interfaces: row.intf_count || '0'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing OSPF processes:', e);
            }
            
            return processes;
        }

        function parseOspfNeighbors(data) {
            const neighbors = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_neighbor) {
                        let rows = output.body.TABLE_neighbor.ROW_neighbor;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            neighbors.push({
                                neighbor_id: row.rid || '',
                                state: row.state || 'unknown',
                                interface: row.intf_name || '',
                                address: row.addr || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing OSPF neighbors:', e);
            }
            
            return neighbors;
        }

        function parseOspfInterfaces(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_intf) {
                        let rows = output.body.TABLE_intf.ROW_intf;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            interfaces.push({
                                interface: row.intf_name || '',
                                area: row.area_id || '',
                                state: row.intf_state || 'unknown',
                                cost: row.intf_cost || '0',
                                priority: row.intf_priority || '0'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing OSPF interfaces:', e);
            }
            
            return interfaces;
        }

        function parseOspfDatabase(data) {
            const database = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_lsa) {
                        let rows = output.body.TABLE_lsa.ROW_lsa;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        const lsaTypes = {};
                        rows.forEach(row => {
                            const type = row.lsa_type || 'Unknown';
                            if (!lsaTypes[type]) {
                                lsaTypes[type] = { count: 0, checksum: 0 };
                            }
                            lsaTypes[type].count++;
                            lsaTypes[type].checksum += parseInt(row.lsa_chksum || 0, 16);
                        });
                        
                        Object.keys(lsaTypes).forEach(type => {
                            database.push({
                                lsa_type: type,
                                count: lsaTypes[type].count,
                                checksum: '0x' + lsaTypes[type].checksum.toString(16).toUpperCase()
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing OSPF database:', e);
            }
            
            return database;
        }

        function displayOspfProcesses(processes) {
            const tbody = document.getElementById('ospf-process-tbody');
            tbody.innerHTML = '';

            if (!processes || processes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No OSPF processes configured</td></tr>';
                return;
            }

            processes.forEach(process => {
                const row = document.createElement('tr');
                
                const stateBadge = process.state === 'up' ? 
                    '<span class="badge bg-success">UP</span>' : 
                    '<span class="badge bg-danger">DOWN</span>';

                row.innerHTML = `
                    <td><strong>${process.process_id}</strong></td>
                    <td>${process.router_id}</td>
                    <td>${stateBadge}</td>
                    <td>${process.areas}</td>
                    <td>${process.interfaces}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayOspfNeighbors(neighbors) {
            const tbody = document.getElementById('ospf-neighbors-tbody');
            tbody.innerHTML = '';

            if (!neighbors || neighbors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No OSPF neighbors found</td></tr>';
                return;
            }

            neighbors.forEach(neighbor => {
                const row = document.createElement('tr');
                
                let stateBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (neighbor.state === 'Full') {
                    stateBadge = '<span class="badge bg-success">Full</span>';
                } else if (neighbor.state === 'Init') {
                    stateBadge = '<span class="badge bg-warning">Init</span>';
                } else if (neighbor.state === 'Down') {
                    stateBadge = '<span class="badge bg-danger">Down</span>';
                }

                row.innerHTML = `
                    <td><strong>${neighbor.neighbor_id}</strong></td>
                    <td>${stateBadge}</td>
                    <td>${neighbor.interface}</td>
                    <td>${neighbor.address}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayOspfInterfaces(interfaces) {
            const tbody = document.getElementById('ospf-interfaces-tbody');
            tbody.innerHTML = '';

            if (!interfaces || interfaces.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No OSPF interfaces configured</td></tr>';
                return;
            }

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                let stateBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (intf.state === 'Up') {
                    stateBadge = '<span class="badge bg-success">Up</span>';
                } else if (intf.state === 'Down') {
                    stateBadge = '<span class="badge bg-danger">Down</span>';
                }

                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>${intf.area}</td>
                    <td>${stateBadge}</td>
                    <td>${intf.cost}</td>
                    <td>${intf.priority}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayOspfDatabase(database) {
            const tbody = document.getElementById('ospf-database-tbody');
            tbody.innerHTML = '';

            if (!database || database.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No OSPF database entries</td></tr>';
                return;
            }

            database.forEach(entry => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong>${entry.lsa_type}</strong></td>
                    <td><span class="badge bg-info">${entry.count}</span></td>
                    <td><small>${entry.checksum}</small></td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updateOspfSummary(processes) {
            document.getElementById('ospf-processes').textContent = processes.length;
            
            let totalAreas = 0;
            processes.forEach(process => {
                totalAreas += parseInt(process.areas || 0);
            });
            document.getElementById('ospf-areas').textContent = totalAreas;
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
            const select = document.getElementById('ospf-interface');
            
            interfaces.forEach(intf => {
                const option = document.createElement('option');
                option.value = intf;
                option.textContent = intf;
                select.appendChild(option);
            });
        }

        function applyOspfConfig() {
            const processId = document.getElementById('ospf-process-id').value;
            const routerId = document.getElementById('ospf-router-id').value;
            const area = document.getElementById('ospf-area').value;
            const network = document.getElementById('ospf-network').value;
            const interfaceName = document.getElementById('ospf-interface').value;
            const cost = document.getElementById('ospf-cost').value;
            const priority = document.getElementById('ospf-priority').value;
            const helloInterval = document.getElementById('ospf-hello-interval').value;
            const deadInterval = document.getElementById('ospf-dead-interval').value;
            const passiveInterface = document.getElementById('ospf-passive-interface').checked;
            const stubArea = document.getElementById('ospf-area-stub').checked;
            const nssaArea = document.getElementById('ospf-area-nssa').checked;

            if (!processId || !area) {
                alert('Please provide at least Process ID and Area');
                return;
            }

            let commands = [];
            
            // Enable OSPF feature
            commands.push('feature ospf');
            
            // Configure OSPF process
            commands.push(`router ospf ${processId}`);
            
            if (routerId) {
                commands.push(`router-id ${routerId}`);
            }
            
            if (network) {
                commands.push(`network ${network} area ${area}`);
            }
            
            // Configure area properties
            if (stubArea) {
                commands.push(`area ${area} stub`);
            }
            
            if (nssaArea) {
                commands.push(`area ${area} nssa`);
            }
            
            // Configure interface if specified
            if (interfaceName) {
                commands.push(`interface ${interfaceName}`);
                commands.push(`ip router ospf ${processId} area ${area}`);
                
                if (cost) {
                    commands.push(`ip ospf cost ${cost}`);
                }
                
                if (priority) {
                    commands.push(`ip ospf priority ${priority}`);
                }
                
                if (helloInterval) {
                    commands.push(`ip ospf hello-interval ${helloInterval}`);
                }
                
                if (deadInterval) {
                    commands.push(`ip ospf dead-interval ${deadInterval}`);
                }
                
                if (passiveInterface) {
                    commands.push(`ip ospf passive-interface`);
                }
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: configCmd
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error applying OSPF configuration: ' + data.error);
                } else {
                    alert('OSPF configuration applied successfully');
                    loadOspfData();
                }
            })
            .catch(error => {
                alert('Error applying OSPF configuration: ' + error.message);
            });
        }

        function removeOspfProcess() {
            const processId = document.getElementById('ospf-process-id').value;
            
            if (!processId) {
                alert('Please specify a Process ID to remove');
                return;
            }
            
            if (confirm('Are you sure you want to remove OSPF process ' + processId + '?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'no router ospf ' + processId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing OSPF process: ' + data.error);
                    } else {
                        alert('OSPF process removed successfully');
                        loadOspfData();
                    }
                })
                .catch(error => {
                    alert('Error removing OSPF process: ' + error.message);
                });
            }
        }

        function clearOspfProcess() {
            const processId = document.getElementById('ospf-process-id').value;
            
            if (!processId) {
                alert('Please specify a Process ID to clear');
                return;
            }
            
            if (confirm('Are you sure you want to clear OSPF process ' + processId + '?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'clear ip ospf process ' + processId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing OSPF process: ' + data.error);
                    } else {
                        alert('OSPF process cleared successfully');
                        loadOspfData();
                    }
                })
                .catch(error => {
                    alert('Error clearing OSPF process: ' + error.message);
                });
            }
        }

        function configureOspf() {
            document.querySelector('#ospfConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadOspfData();
        }
    </script>
</body>
</html>

