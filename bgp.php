<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGP Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-globe"></i> BGP Configuration & Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureBgp()">
                    <i class="fas fa-cog"></i> Configure BGP
                </button>
            </div>
        </div>

        <!-- BGP Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">BGP AS Number</h5>
                        <h2 id="bgp-as-number">-</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Established Peers</h5>
                        <h2 id="bgp-established">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Routes</h5>
                        <h2 id="bgp-routes">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">VRF Instances</h5>
                        <h2 id="bgp-vrfs">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- BGP Summary and Neighbors -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> BGP Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td><strong>BGP Router ID:</strong></td><td id="bgp-router-id">Loading...</td></tr>
                            <tr><td><strong>Local AS:</strong></td><td id="bgp-local-as">Loading...</td></tr>
                            <tr><td><strong>BGP State:</strong></td><td id="bgp-state">Loading...</td></tr>
                            <tr><td><strong>Table Version:</strong></td><td id="bgp-table-version">Loading...</td></tr>
                            <tr><td><strong>Network Entries:</strong></td><td id="bgp-network-entries">Loading...</td></tr>
                            <tr><td><strong>Path Entries:</strong></td><td id="bgp-path-entries">Loading...</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> BGP Neighbors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Neighbor</th>
                                        <th>AS</th>
                                        <th>State</th>
                                        <th>Uptime</th>
                                    </tr>
                                </thead>
                                <tbody id="bgp-neighbors-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading BGP neighbors...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BGP Routes and Attributes -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-route"></i> BGP Route Table</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Network</th>
                                        <th>Next Hop</th>
                                        <th>Metric</th>
                                        <th>Local Pref</th>
                                        <th>Weight</th>
                                        <th>Path</th>
                                        <th>Origin</th>
                                    </tr>
                                </thead>
                                <tbody id="bgp-routes-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading BGP routes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BGP Configuration -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> BGP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="bgpConfigForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Basic BGP Configuration</h6>
                                    <div class="mb-3">
                                        <label for="bgp-as" class="form-label">AS Number</label>
                                        <input type="number" class="form-control" id="bgp-as" min="1" max="4294967295" placeholder="65001">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-router-id-input" class="form-label">Router ID</label>
                                        <input type="text" class="form-control" id="bgp-router-id-input" placeholder="1.1.1.1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-vrf" class="form-label">VRF (Optional)</label>
                                        <input type="text" class="form-control" id="bgp-vrf" placeholder="default">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Neighbor Configuration</h6>
                                    <div class="mb-3">
                                        <label for="bgp-neighbor-ip" class="form-label">Neighbor IP</label>
                                        <input type="text" class="form-control" id="bgp-neighbor-ip" placeholder="192.168.1.1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-neighbor-as" class="form-label">Neighbor AS</label>
                                        <input type="number" class="form-control" id="bgp-neighbor-as" min="1" max="4294967295" placeholder="65002">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-neighbor-description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="bgp-neighbor-description" placeholder="ISP Connection">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Network Advertisement</h6>
                                    <div class="mb-3">
                                        <label for="bgp-network" class="form-label">Network to Advertise</label>
                                        <input type="text" class="form-control" id="bgp-network" placeholder="192.168.0.0/16">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-network-mask" class="form-label">Network Mask</label>
                                        <input type="text" class="form-control" id="bgp-network-mask" placeholder="255.255.0.0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bgp-redistribute" class="form-label">Redistribute</label>
                                        <select class="form-select" id="bgp-redistribute">
                                            <option value="">None</option>
                                            <option value="connected">Connected</option>
                                            <option value="static">Static</option>
                                            <option value="ospf">OSPF</option>
                                            <option value="eigrp">EIGRP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Advanced Options</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bgp-synchronization">
                                            <label class="form-check-label" for="bgp-synchronization">
                                                Synchronization
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bgp-auto-summary">
                                            <label class="form-check-label" for="bgp-auto-summary">
                                                Auto Summary
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bgp-log-neighbor-changes">
                                            <label class="form-check-label" for="bgp-log-neighbor-changes">
                                                Log Neighbor Changes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Timers</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="bgp-keepalive" class="form-label">Keepalive</label>
                                                <input type="number" class="form-control" id="bgp-keepalive" min="1" max="65535" placeholder="60">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="bgp-holdtime" class="form-label">Hold Time</label>
                                                <input type="number" class="form-control" id="bgp-holdtime" min="3" max="65535" placeholder="180">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyBgpConfig()">
                                        Apply BGP Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="addBgpNeighbor()">
                                        Add Neighbor
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="advertiseBgpNetwork()">
                                        Advertise Network
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="clearBgpSession()">
                                        Clear BGP Session
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="removeBgpProcess()">
                                        Remove BGP Process
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
        let bgpData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadBgpData();
        });

        function loadBgpData() {
            loadBgpSummary();
            loadBgpNeighbors();
            loadBgpRoutes();
        }

        function loadBgpSummary() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip bgp summary'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const summary = parseBgpSummary(data);
                displayBgpSummary(summary);
                updateBgpOverview(summary);
            })
            .catch(error => {
                console.error('Error loading BGP summary:', error);
                displayBgpError(error.message);
            });
        }

        function loadBgpNeighbors() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip bgp neighbors'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const neighbors = parseBgpNeighbors(data);
                displayBgpNeighbors(neighbors);
            })
            .catch(error => {
                console.error('Error loading BGP neighbors:', error);
                document.getElementById('bgp-neighbors-tbody').innerHTML = 
                    '<tr><td colspan="4" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadBgpRoutes() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip bgp'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const routes = parseBgpRoutes(data);
                displayBgpRoutes(routes);
            })
            .catch(error => {
                console.error('Error loading BGP routes:', error);
                document.getElementById('bgp-routes-tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseBgpSummary(data) {
            const summary = {
                router_id: 'Unknown',
                local_as: 'Unknown',
                state: 'Unknown',
                table_version: '0',
                network_entries: '0',
                path_entries: '0',
                established_peers: 0,
                total_routes: 0
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const body = output.body;
                        
                        summary.router_id = body.router_id || 'Unknown';
                        summary.local_as = body.local_as || 'Unknown';
                        summary.state = body.bgp_state || 'Unknown';
                        summary.table_version = body.table_version || '0';
                        summary.network_entries = body.network_entries || '0';
                        summary.path_entries = body.path_entries || '0';
                        
                        // Count established peers
                        if (body.TABLE_neighbor && body.TABLE_neighbor.ROW_neighbor) {
                            let neighbors = body.TABLE_neighbor.ROW_neighbor;
                            if (!Array.isArray(neighbors)) {
                                neighbors = [neighbors];
                            }
                            
                            summary.established_peers = neighbors.filter(n => 
                                n.state && n.state.toLowerCase() === 'established'
                            ).length;
                        }
                        
                        summary.total_routes = parseInt(summary.network_entries) || 0;
                    }
                }
            } catch (e) {
                console.error('Error parsing BGP summary:', e);
            }
            
            return summary;
        }

        function parseBgpNeighbors(data) {
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
                                neighbor_ip: row.neighbor_ip || '',
                                remote_as: row.remote_as || '',
                                state: row.state || 'unknown',
                                uptime: row.uptime || '0'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing BGP neighbors:', e);
            }
            
            return neighbors;
        }

        function parseBgpRoutes(data) {
            const routes = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_path) {
                        let rows = output.body.TABLE_path.ROW_path;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            routes.push({
                                network: row.network || '',
                                next_hop: row.next_hop || '',
                                metric: row.metric || '0',
                                local_pref: row.local_pref || '100',
                                weight: row.weight || '0',
                                path: row.as_path || '',
                                origin: row.origin || 'i'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing BGP routes:', e);
            }
            
            return routes;
        }

        function displayBgpSummary(summary) {
            document.getElementById('bgp-router-id').textContent = summary.router_id;
            document.getElementById('bgp-local-as').textContent = summary.local_as;
            document.getElementById('bgp-state').textContent = summary.state;
            document.getElementById('bgp-table-version').textContent = summary.table_version;
            document.getElementById('bgp-network-entries').textContent = summary.network_entries;
            document.getElementById('bgp-path-entries').textContent = summary.path_entries;
        }

        function updateBgpOverview(summary) {
            document.getElementById('bgp-as-number').textContent = summary.local_as;
            document.getElementById('bgp-established').textContent = summary.established_peers;
            document.getElementById('bgp-routes').textContent = summary.total_routes;
            document.getElementById('bgp-vrfs').textContent = '1'; // Default VRF
        }

        function displayBgpNeighbors(neighbors) {
            const tbody = document.getElementById('bgp-neighbors-tbody');
            tbody.innerHTML = '';

            if (!neighbors || neighbors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No BGP neighbors configured</td></tr>';
                return;
            }

            neighbors.forEach(neighbor => {
                const row = document.createElement('tr');
                
                let stateBadge = '<span class="badge bg-secondary">Unknown</span>';
                if (neighbor.state.toLowerCase() === 'established') {
                    stateBadge = '<span class="badge bg-success">Established</span>';
                } else if (neighbor.state.toLowerCase() === 'idle') {
                    stateBadge = '<span class="badge bg-warning">Idle</span>';
                } else if (neighbor.state.toLowerCase() === 'active') {
                    stateBadge = '<span class="badge bg-info">Active</span>';
                } else {
                    stateBadge = '<span class="badge bg-danger">' + neighbor.state + '</span>';
                }

                row.innerHTML = `
                    <td><strong>${neighbor.neighbor_ip}</strong></td>
                    <td>${neighbor.remote_as}</td>
                    <td>${stateBadge}</td>
                    <td>${neighbor.uptime}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayBgpRoutes(routes) {
            const tbody = document.getElementById('bgp-routes-tbody');
            tbody.innerHTML = '';

            if (!routes || routes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No BGP routes found</td></tr>';
                return;
            }

            routes.slice(0, 20).forEach(route => { // Show first 20 routes
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong>${route.network}</strong></td>
                    <td>${route.next_hop}</td>
                    <td>${route.metric}</td>
                    <td>${route.local_pref}</td>
                    <td>${route.weight}</td>
                    <td><small>${route.path}</small></td>
                    <td>${route.origin}</td>
                `;
                
                tbody.appendChild(row);
            });

            if (routes.length > 20) {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="7" class="text-center text-muted">... and ${routes.length - 20} more routes</td>`;
                tbody.appendChild(row);
            }
        }

        function displayBgpError(error) {
            const elements = ['bgp-router-id', 'bgp-local-as', 'bgp-state'];
            elements.forEach(id => {
                document.getElementById(id).textContent = 'Error';
            });
        }

        function applyBgpConfig() {
            const asNumber = document.getElementById('bgp-as').value;
            const routerId = document.getElementById('bgp-router-id-input').value;
            const vrf = document.getElementById('bgp-vrf').value;
            const logNeighborChanges = document.getElementById('bgp-log-neighbor-changes').checked;
            const keepalive = document.getElementById('bgp-keepalive').value;
            const holdtime = document.getElementById('bgp-holdtime').value;

            if (!asNumber) {
                alert('Please provide AS Number');
                return;
            }

            let commands = [];
            
            // Enable BGP feature
            commands.push('feature bgp');
            
            // Configure BGP process
            commands.push(`router bgp ${asNumber}`);
            
            if (routerId) {
                commands.push(`router-id ${routerId}`);
            }
            
            if (logNeighborChanges) {
                commands.push('log-neighbor-changes');
            }
            
            if (keepalive && holdtime) {
                commands.push(`timers bgp ${keepalive} ${holdtime}`);
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
                    alert('Error applying BGP configuration: ' + data.error);
                } else {
                    alert('BGP configuration applied successfully');
                    loadBgpData();
                }
            })
            .catch(error => {
                alert('Error applying BGP configuration: ' + error.message);
            });
        }

        function addBgpNeighbor() {
            const asNumber = document.getElementById('bgp-as').value;
            const neighborIp = document.getElementById('bgp-neighbor-ip').value;
            const neighborAs = document.getElementById('bgp-neighbor-as').value;
            const description = document.getElementById('bgp-neighbor-description').value;

            if (!asNumber || !neighborIp || !neighborAs) {
                alert('Please provide AS Number, Neighbor IP, and Neighbor AS');
                return;
            }

            let commands = [];
            
            commands.push(`router bgp ${asNumber}`);
            commands.push(`neighbor ${neighborIp} remote-as ${neighborAs}`);
            
            if (description) {
                commands.push(`neighbor ${neighborIp} description ${description}`);
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
                    alert('Error adding BGP neighbor: ' + data.error);
                } else {
                    alert('BGP neighbor added successfully');
                    loadBgpData();
                }
            })
            .catch(error => {
                alert('Error adding BGP neighbor: ' + error.message);
            });
        }

        function advertiseBgpNetwork() {
            const asNumber = document.getElementById('bgp-as').value;
            const network = document.getElementById('bgp-network').value;
            const mask = document.getElementById('bgp-network-mask').value;
            const redistribute = document.getElementById('bgp-redistribute').value;

            if (!asNumber) {
                alert('Please provide AS Number');
                return;
            }

            let commands = [];
            
            commands.push(`router bgp ${asNumber}`);
            
            if (network) {
                if (mask) {
                    commands.push(`network ${network} mask ${mask}`);
                } else {
                    commands.push(`network ${network}`);
                }
            }
            
            if (redistribute) {
                commands.push(`redistribute ${redistribute}`);
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
                    alert('Error advertising BGP network: ' + data.error);
                } else {
                    alert('BGP network advertised successfully');
                    loadBgpData();
                }
            })
            .catch(error => {
                alert('Error advertising BGP network: ' + error.message);
            });
        }

        function clearBgpSession() {
            const neighborIp = document.getElementById('bgp-neighbor-ip').value;
            
            if (!neighborIp) {
                alert('Please specify a neighbor IP to clear');
                return;
            }
            
            if (confirm('Are you sure you want to clear BGP session with ' + neighborIp + '?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=clear ip bgp ' + neighborIp
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing BGP session: ' + data.error);
                    } else {
                        alert('BGP session cleared successfully');
                        loadBgpData();
                    }
                })
                .catch(error => {
                    alert('Error clearing BGP session: ' + error.message);
                });
            }
        }

        function removeBgpProcess() {
            const asNumber = document.getElementById('bgp-as').value;
            
            if (!asNumber) {
                alert('Please specify an AS Number to remove');
                return;
            }
            
            if (confirm('Are you sure you want to remove BGP process ' + asNumber + '?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=no router bgp ' + asNumber + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing BGP process: ' + data.error);
                    } else {
                        alert('BGP process removed successfully');
                        loadBgpData();
                    }
                })
                .catch(error => {
                    alert('Error removing BGP process: ' + error.message);
                });
            }
        }

        function configureBgp() {
            document.querySelector('#bgpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadBgpData();
        }
    </script>
</body>
</html>

