<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multicast Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-broadcast-tower"></i> Multicast Configuration & Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureMulticast()">
                    <i class="fas fa-cog"></i> Configure Multicast
                </button>
            </div>
        </div>

        <!-- Multicast Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">IGMP Groups</h5>
                        <h2 id="igmp-groups">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">PIM Neighbors</h5>
                        <h2 id="pim-neighbors">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Multicast Routes</h5>
                        <h2 id="mcast-routes">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">RP Mappings</h5>
                        <h2 id="rp-mappings">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Multicast Tabs -->
        <ul class="nav nav-tabs" id="multicastTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="igmp-tab" data-bs-toggle="tab" data-bs-target="#igmp" type="button" role="tab">
                    <i class="fas fa-users"></i> IGMP
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pim-tab" data-bs-toggle="tab" data-bs-target="#pim" type="button" role="tab">
                    <i class="fas fa-route"></i> PIM
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="mroute-tab" data-bs-toggle="tab" data-bs-target="#mroute" type="button" role="tab">
                    <i class="fas fa-table"></i> Multicast Routes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rp-tab" data-bs-toggle="tab" data-bs-target="#rp" type="button" role="tab">
                    <i class="fas fa-server"></i> Rendezvous Point
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Configuration
                </button>
            </li>
        </ul>

        <div class="tab-content" id="multicastTabContent">
            <!-- IGMP Tab -->
            <div class="tab-pane fade show active" id="igmp" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> IGMP Groups</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Group Address</th>
                                        <th>Source</th>
                                        <th>Version</th>
                                        <th>Uptime</th>
                                        <th>Expires</th>
                                        <th>Last Reporter</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="igmp-groups-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading IGMP groups...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> IGMP Interface Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Status</th>
                                        <th>Version</th>
                                        <th>Query Interval</th>
                                        <th>Max Response</th>
                                        <th>Groups</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="igmp-interfaces-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading IGMP interfaces...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PIM Tab -->
            <div class="tab-pane fade" id="pim" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-route"></i> PIM Neighbors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Neighbor</th>
                                        <th>Version</th>
                                        <th>Mode</th>
                                        <th>Uptime</th>
                                        <th>Expires</th>
                                        <th>DR Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pim-neighbors-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading PIM neighbors...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-network-wired"></i> PIM Interface Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Interface</th>
                                        <th>Status</th>
                                        <th>Mode</th>
                                        <th>DR</th>
                                        <th>DR Priority</th>
                                        <th>Hello Interval</th>
                                        <th>Neighbors</th>
                                    </tr>
                                </thead>
                                <tbody id="pim-interfaces-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading PIM interfaces...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multicast Routes Tab -->
            <div class="tab-pane fade" id="mroute" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-table"></i> Multicast Routing Table</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Source</th>
                                        <th>Group</th>
                                        <th>Input Interface</th>
                                        <th>Output Interfaces</th>
                                        <th>Uptime</th>
                                        <th>Expires</th>
                                        <th>Flags</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="mroute-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading multicast routes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rendezvous Point Tab -->
            <div class="tab-pane fade" id="rp" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-server"></i> Rendezvous Point Mappings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Group Range</th>
                                        <th>RP Address</th>
                                        <th>Mode</th>
                                        <th>Info Source</th>
                                        <th>Uptime</th>
                                        <th>Expires</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="rp-mappings-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading RP mappings...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Configure Static RP</h5>
                    </div>
                    <div class="card-body">
                        <form id="rpConfigForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="rp-address" class="form-label">RP Address</label>
                                    <input type="text" class="form-control" id="rp-address" placeholder="192.168.1.1" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="rp-group-list" class="form-label">Group List/ACL</label>
                                    <input type="text" class="form-control" id="rp-group-list" placeholder="224.0.0.0/4 or ACL name">
                                </div>
                                <div class="col-md-4">
                                    <label for="rp-bidir" class="form-label">Mode</label>
                                    <select class="form-select" id="rp-bidir">
                                        <option value="">PIM-SM</option>
                                        <option value="bidir">Bidirectional</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="addStaticRp()">
                                        Add Static RP
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="removeStaticRp()">
                                        Remove Static RP
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Configuration Tab -->
            <div class="tab-pane fade" id="config" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Multicast Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="multicastConfigForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Global Multicast Settings</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-multicast">
                                            <label class="form-check-label" for="enable-multicast">
                                                Enable IP Multicast Routing
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-pim">
                                            <label class="form-check-label" for="enable-pim">
                                                Enable PIM
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-igmp">
                                            <label class="form-check-label" for="enable-igmp">
                                                Enable IGMP
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Interface Configuration</h6>
                                    <div class="mb-3">
                                        <label for="mcast-interface" class="form-label">Interface</label>
                                        <select class="form-select" id="mcast-interface">
                                            <option value="">Select Interface</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pim-mode" class="form-label">PIM Mode</label>
                                        <select class="form-select" id="pim-mode">
                                            <option value="sparse-mode">Sparse Mode</option>
                                            <option value="dense-mode">Dense Mode</option>
                                            <option value="sparse-dense-mode">Sparse-Dense Mode</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="igmp-version" class="form-label">IGMP Version</label>
                                        <select class="form-select" id="igmp-version">
                                            <option value="2">Version 2</option>
                                            <option value="3">Version 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>IGMP Settings</h6>
                                    <div class="mb-3">
                                        <label for="igmp-query-interval" class="form-label">Query Interval (seconds)</label>
                                        <input type="number" class="form-control" id="igmp-query-interval" min="1" max="18000" placeholder="125">
                                    </div>
                                    <div class="mb-3">
                                        <label for="igmp-query-max-response" class="form-label">Query Max Response (seconds)</label>
                                        <input type="number" class="form-control" id="igmp-query-max-response" min="1" max="25" placeholder="10">
                                    </div>
                                    <div class="mb-3">
                                        <label for="igmp-robustness" class="form-label">Robustness Variable</label>
                                        <input type="number" class="form-control" id="igmp-robustness" min="1" max="7" placeholder="2">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>PIM Settings</h6>
                                    <div class="mb-3">
                                        <label for="pim-hello-interval" class="form-label">Hello Interval (seconds)</label>
                                        <input type="number" class="form-control" id="pim-hello-interval" min="1" max="18000" placeholder="30">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pim-dr-priority" class="form-label">DR Priority</label>
                                        <input type="number" class="form-control" id="pim-dr-priority" min="0" max="4294967295" placeholder="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pim-join-prune-interval" class="form-label">Join/Prune Interval (seconds)</label>
                                        <input type="number" class="form-control" id="pim-join-prune-interval" min="1" max="18000" placeholder="60">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="applyMulticastConfig()">
                                        Apply Configuration
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="enableMulticastFeatures()">
                                        Enable Features
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="disableMulticastFeatures()">
                                        Disable Features
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="clearMulticastCounters()">
                                        Clear Counters
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
        let multicastData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadMulticastData();
            loadInterfaceList();
        });

        function loadMulticastData() {
            loadIgmpGroups();
            loadPimNeighbors();
            loadMulticastRoutes();
            loadRpMappings();
        }

        function loadIgmpGroups() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip igmp groups'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const groups = parseIgmpGroups(data);
                displayIgmpGroups(groups);
                document.getElementById('igmp-groups').textContent = groups.length;
            })
            .catch(error => {
                console.error('Error loading IGMP groups:', error);
                document.getElementById('igmp-groups-tbody').innerHTML = 
                    '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });

            // Load IGMP interfaces
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip igmp interface'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parseIgmpInterfaces(data);
                    displayIgmpInterfaces(interfaces);
                }
            })
            .catch(error => console.error('Error loading IGMP interfaces:', error));
        }

        function loadPimNeighbors() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip pim neighbor'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const neighbors = parsePimNeighbors(data);
                displayPimNeighbors(neighbors);
                document.getElementById('pim-neighbors').textContent = neighbors.length;
            })
            .catch(error => {
                console.error('Error loading PIM neighbors:', error);
                document.getElementById('pim-neighbors-tbody').innerHTML = 
                    '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });

            // Load PIM interfaces
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip pim interface'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parsePimInterfaces(data);
                    displayPimInterfaces(interfaces);
                }
            })
            .catch(error => console.error('Error loading PIM interfaces:', error));
        }

        function loadMulticastRoutes() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip mroute'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const routes = parseMulticastRoutes(data);
                displayMulticastRoutes(routes);
                document.getElementById('mcast-routes').textContent = routes.length;
            })
            .catch(error => {
                console.error('Error loading multicast routes:', error);
                document.getElementById('mroute-tbody').innerHTML = 
                    '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadRpMappings() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show ip pim rp'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const mappings = parseRpMappings(data);
                displayRpMappings(mappings);
                document.getElementById('rp-mappings').textContent = mappings.length;
            })
            .catch(error => {
                console.error('Error loading RP mappings:', error);
                document.getElementById('rp-mappings-tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseIgmpGroups(data) {
            const groups = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_group && vrf.TABLE_group.ROW_group) {
                                let groupRows = vrf.TABLE_group.ROW_group;
                                if (!Array.isArray(groupRows)) groupRows = [groupRows];
                                
                                groupRows.forEach(group => {
                                    groups.push({
                                        interface: group.intf_name || '',
                                        group_address: group.group_addr || '',
                                        source: group.source_addr || '*',
                                        version: group.version || '2',
                                        uptime: group.uptime || '00:00:00',
                                        expires: group.expires || 'never',
                                        last_reporter: group.last_reporter || ''
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing IGMP groups:', e);
            }
            
            return groups;
        }

        function parseIgmpInterfaces(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_intf && vrf.TABLE_intf.ROW_intf) {
                                let intfRows = vrf.TABLE_intf.ROW_intf;
                                if (!Array.isArray(intfRows)) intfRows = [intfRows];
                                
                                intfRows.forEach(intf => {
                                    interfaces.push({
                                        interface: intf.intf_name || '',
                                        status: intf.intf_status || 'down',
                                        version: intf.igmp_version || '2',
                                        query_interval: intf.query_interval || '125',
                                        max_response: intf.max_response || '10',
                                        groups: intf.group_count || '0'
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing IGMP interfaces:', e);
            }
            
            return interfaces;
        }

        function parsePimNeighbors(data) {
            const neighbors = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_neighbor && vrf.TABLE_neighbor.ROW_neighbor) {
                                let neighborRows = vrf.TABLE_neighbor.ROW_neighbor;
                                if (!Array.isArray(neighborRows)) neighborRows = [neighborRows];
                                
                                neighborRows.forEach(neighbor => {
                                    neighbors.push({
                                        interface: neighbor.intf_name || '',
                                        neighbor: neighbor.neighbor_addr || '',
                                        version: neighbor.pim_version || '2',
                                        mode: neighbor.pim_mode || 'sparse',
                                        uptime: neighbor.uptime || '00:00:00',
                                        expires: neighbor.expires || 'never',
                                        dr_priority: neighbor.dr_priority || '1'
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing PIM neighbors:', e);
            }
            
            return neighbors;
        }

        function parsePimInterfaces(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_intf && vrf.TABLE_intf.ROW_intf) {
                                let intfRows = vrf.TABLE_intf.ROW_intf;
                                if (!Array.isArray(intfRows)) intfRows = [intfRows];
                                
                                intfRows.forEach(intf => {
                                    interfaces.push({
                                        interface: intf.intf_name || '',
                                        status: intf.intf_status || 'down',
                                        mode: intf.pim_mode || 'sparse',
                                        dr: intf.is_dr || 'no',
                                        dr_priority: intf.dr_priority || '1',
                                        hello_interval: intf.hello_interval || '30',
                                        neighbors: intf.neighbor_count || '0'
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing PIM interfaces:', e);
            }
            
            return interfaces;
        }

        function parseMulticastRoutes(data) {
            const routes = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_route && vrf.TABLE_route.ROW_route) {
                                let routeRows = vrf.TABLE_route.ROW_route;
                                if (!Array.isArray(routeRows)) routeRows = [routeRows];
                                
                                routeRows.forEach(route => {
                                    routes.push({
                                        source: route.source_addr || '*',
                                        group: route.group_addr || '',
                                        input_interface: route.iif || '',
                                        output_interfaces: route.oil || '',
                                        uptime: route.uptime || '00:00:00',
                                        expires: route.expires || 'never',
                                        flags: route.flags || ''
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing multicast routes:', e);
            }
            
            return routes;
        }

        function parseRpMappings(data) {
            const mappings = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vrf) {
                        let vrfs = output.body.TABLE_vrf.ROW_vrf;
                        if (!Array.isArray(vrfs)) vrfs = [vrfs];
                        
                        vrfs.forEach(vrf => {
                            if (vrf.TABLE_rp && vrf.TABLE_rp.ROW_rp) {
                                let rpRows = vrf.TABLE_rp.ROW_rp;
                                if (!Array.isArray(rpRows)) rpRows = [rpRows];
                                
                                rpRows.forEach(rp => {
                                    mappings.push({
                                        group_range: rp.group_range || '',
                                        rp_address: rp.rp_addr || '',
                                        mode: rp.mode || 'SM',
                                        info_source: rp.info_source || 'static',
                                        uptime: rp.uptime || '00:00:00',
                                        expires: rp.expires || 'never'
                                    });
                                });
                            }
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing RP mappings:', e);
            }
            
            return mappings;
        }

        function displayIgmpGroups(groups) {
            const tbody = document.getElementById('igmp-groups-tbody');
            tbody.innerHTML = '';

            if (!groups || groups.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No IGMP groups found</td></tr>';
                return;
            }

            groups.forEach(group => {
                const row = document.createElement('tr');
                
                let versionBadge = `<span class="badge bg-info">v${group.version}</span>`;

                row.innerHTML = `
                    <td><strong>${group.interface}</strong></td>
                    <td>${group.group_address}</td>
                    <td>${group.source}</td>
                    <td>${versionBadge}</td>
                    <td><small>${group.uptime}</small></td>
                    <td><small>${group.expires}</small></td>
                    <td><small>${group.last_reporter}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="leaveIgmpGroup('${group.interface}', '${group.group_address}')">
                            Leave
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayIgmpInterfaces(interfaces) {
            const tbody = document.getElementById('igmp-interfaces-tbody');
            tbody.innerHTML = '';

            if (!interfaces || interfaces.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No IGMP interfaces found</td></tr>';
                return;
            }

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                let statusBadge = intf.status === 'up' ? 
                    '<span class="badge bg-success">Up</span>' : 
                    '<span class="badge bg-danger">Down</span>';

                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>${statusBadge}</td>
                    <td><span class="badge bg-info">v${intf.version}</span></td>
                    <td>${intf.query_interval}s</td>
                    <td>${intf.max_response}s</td>
                    <td><span class="badge bg-primary">${intf.groups}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="configureIgmpInterface('${intf.interface}')">
                            Configure
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayPimNeighbors(neighbors) {
            const tbody = document.getElementById('pim-neighbors-tbody');
            tbody.innerHTML = '';

            if (!neighbors || neighbors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No PIM neighbors found</td></tr>';
                return;
            }

            neighbors.forEach(neighbor => {
                const row = document.createElement('tr');
                
                let modeBadge = `<span class="badge bg-info">${neighbor.mode}</span>`;

                row.innerHTML = `
                    <td><strong>${neighbor.interface}</strong></td>
                    <td>${neighbor.neighbor}</td>
                    <td><span class="badge bg-info">v${neighbor.version}</span></td>
                    <td>${modeBadge}</td>
                    <td><small>${neighbor.uptime}</small></td>
                    <td><small>${neighbor.expires}</small></td>
                    <td>${neighbor.dr_priority}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="clearPimNeighbor('${neighbor.interface}', '${neighbor.neighbor}')">
                            Clear
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayPimInterfaces(interfaces) {
            const tbody = document.getElementById('pim-interfaces-tbody');
            tbody.innerHTML = '';

            if (!interfaces || interfaces.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No PIM interfaces found</td></tr>';
                return;
            }

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                
                let statusBadge = intf.status === 'up' ? 
                    '<span class="badge bg-success">Up</span>' : 
                    '<span class="badge bg-danger">Down</span>';

                let drBadge = intf.dr === 'yes' ? 
                    '<span class="badge bg-success">Yes</span>' : 
                    '<span class="badge bg-secondary">No</span>';

                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>${statusBadge}</td>
                    <td><span class="badge bg-info">${intf.mode}</span></td>
                    <td>${drBadge}</td>
                    <td>${intf.dr_priority}</td>
                    <td>${intf.hello_interval}s</td>
                    <td><span class="badge bg-primary">${intf.neighbors}</span></td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayMulticastRoutes(routes) {
            const tbody = document.getElementById('mroute-tbody');
            tbody.innerHTML = '';

            if (!routes || routes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No multicast routes found</td></tr>';
                return;
            }

            routes.forEach(route => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${route.source}</td>
                    <td><strong>${route.group}</strong></td>
                    <td>${route.input_interface}</td>
                    <td><small>${route.output_interfaces}</small></td>
                    <td><small>${route.uptime}</small></td>
                    <td><small>${route.expires}</small></td>
                    <td><small>${route.flags}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="clearMroute('${route.source}', '${route.group}')">
                            Clear
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayRpMappings(mappings) {
            const tbody = document.getElementById('rp-mappings-tbody');
            tbody.innerHTML = '';

            if (!mappings || mappings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No RP mappings found</td></tr>';
                return;
            }

            mappings.forEach(mapping => {
                const row = document.createElement('tr');
                
                let modeBadge = `<span class="badge bg-info">${mapping.mode}</span>`;
                let sourceBadge = `<span class="badge bg-secondary">${mapping.info_source}</span>`;

                row.innerHTML = `
                    <td>${mapping.group_range}</td>
                    <td><strong>${mapping.rp_address}</strong></td>
                    <td>${modeBadge}</td>
                    <td>${sourceBadge}</td>
                    <td><small>${mapping.uptime}</small></td>
                    <td><small>${mapping.expires}</small></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeRpMapping('${mapping.rp_address}', '${mapping.group_range}')">
                            Remove
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
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

        function populateInterfaceSelect(interfaces) {
            const select = document.getElementById('mcast-interface');
            
            interfaces.forEach(intf => {
                const option = document.createElement('option');
                option.value = intf;
                option.textContent = intf;
                select.appendChild(option);
            });
        }

        function applyMulticastConfig() {
            const enableMulticast = document.getElementById('enable-multicast').checked;
            const enablePim = document.getElementById('enable-pim').checked;
            const enableIgmp = document.getElementById('enable-igmp').checked;
            const interfaceName = document.getElementById('mcast-interface').value;
            const pimMode = document.getElementById('pim-mode').value;
            const igmpVersion = document.getElementById('igmp-version').value;
            const queryInterval = document.getElementById('igmp-query-interval').value;
            const maxResponse = document.getElementById('igmp-query-max-response').value;
            const robustness = document.getElementById('igmp-robustness').value;
            const helloInterval = document.getElementById('pim-hello-interval').value;
            const drPriority = document.getElementById('pim-dr-priority').value;
            const joinPruneInterval = document.getElementById('pim-join-prune-interval').value;

            let commands = [];
            
            // Enable features
            if (enableMulticast) {
                commands.push('feature multicast');
            }
            if (enablePim) {
                commands.push('feature pim');
            }
            if (enableIgmp) {
                commands.push('feature igmp');
            }
            
            // Configure interface
            if (interfaceName) {
                commands.push(`interface ${interfaceName}`);
                
                if (enablePim) {
                    commands.push(`ip pim ${pimMode}`);
                    
                    if (helloInterval) {
                        commands.push(`ip pim hello-interval ${helloInterval}`);
                    }
                    if (drPriority) {
                        commands.push(`ip pim dr-priority ${drPriority}`);
                    }
                    if (joinPruneInterval) {
                        commands.push(`ip pim join-prune-interval ${joinPruneInterval}`);
                    }
                }
                
                if (enableIgmp) {
                    if (igmpVersion) {
                        commands.push(`ip igmp version ${igmpVersion}`);
                    }
                    if (queryInterval) {
                        commands.push(`ip igmp query-interval ${queryInterval}`);
                    }
                    if (maxResponse) {
                        commands.push(`ip igmp query-max-response-time ${maxResponse}`);
                    }
                    if (robustness) {
                        commands.push(`ip igmp robustness-variable ${robustness}`);
                    }
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
                    alert('Error applying multicast configuration: ' + data.error);
                } else {
                    alert('Multicast configuration applied successfully');
                    loadMulticastData();
                }
            })
            .catch(error => {
                alert('Error applying multicast configuration: ' + error.message);
            });
        }

        function addStaticRp() {
            const rpAddress = document.getElementById('rp-address').value;
            const groupList = document.getElementById('rp-group-list').value;
            const bidir = document.getElementById('rp-bidir').value;

            if (!rpAddress) {
                alert('Please provide RP Address');
                return;
            }

            let command = `ip pim rp-address ${rpAddress}`;
            
            if (groupList) {
                command += ` group-list ${groupList}`;
            }
            
            if (bidir) {
                command += ` bidir`;
            }

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error adding static RP: ' + data.error);
                } else {
                    alert('Static RP added successfully');
                    loadRpMappings();
                }
            })
            .catch(error => {
                alert('Error adding static RP: ' + error.message);
            });
        }

        function removeStaticRp() {
            const rpAddress = document.getElementById('rp-address').value;
            const groupList = document.getElementById('rp-group-list').value;

            if (!rpAddress) {
                alert('Please provide RP Address');
                return;
            }

            let command = `no ip pim rp-address ${rpAddress}`;
            
            if (groupList) {
                command += ` group-list ${groupList}`;
            }

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error removing static RP: ' + data.error);
                } else {
                    alert('Static RP removed successfully');
                    loadRpMappings();
                }
            })
            .catch(error => {
                alert('Error removing static RP: ' + error.message);
            });
        }

        function enableMulticastFeatures() {
            const commands = [
                'feature multicast',
                'feature pim',
                'feature igmp'
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
                    alert('Error enabling multicast features: ' + data.error);
                } else {
                    alert('Multicast features enabled successfully');
                    loadMulticastData();
                }
            })
            .catch(error => {
                alert('Error enabling multicast features: ' + error.message);
            });
        }

        function disableMulticastFeatures() {
            if (confirm('Are you sure you want to disable multicast features?')) {
                const commands = [
                    'no feature igmp',
                    'no feature pim',
                    'no feature multicast'
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
                        alert('Error disabling multicast features: ' + data.error);
                    } else {
                        alert('Multicast features disabled successfully');
                        loadMulticastData();
                    }
                })
                .catch(error => {
                    alert('Error disabling multicast features: ' + error.message);
                });
            }
        }

        function clearMulticastCounters() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=clear ip mroute *'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error clearing multicast counters: ' + data.error);
                } else {
                    alert('Multicast counters cleared successfully');
                    loadMulticastData();
                }
            })
            .catch(error => {
                alert('Error clearing multicast counters: ' + error.message);
            });
        }

        function leaveIgmpGroup(interfaceName, groupAddress) {
            if (confirm(`Leave IGMP group ${groupAddress} on ${interfaceName}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=clear ip igmp group ${groupAddress} interface ${interfaceName}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error leaving IGMP group: ' + data.error);
                    } else {
                        alert('IGMP group left successfully');
                        loadIgmpGroups();
                    }
                })
                .catch(error => {
                    alert('Error leaving IGMP group: ' + error.message);
                });
            }
        }

        function clearPimNeighbor(interfaceName, neighborAddress) {
            if (confirm(`Clear PIM neighbor ${neighborAddress} on ${interfaceName}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=clear ip pim neighbor ${neighborAddress}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing PIM neighbor: ' + data.error);
                    } else {
                        alert('PIM neighbor cleared successfully');
                        loadPimNeighbors();
                    }
                })
                .catch(error => {
                    alert('Error clearing PIM neighbor: ' + error.message);
                });
            }
        }

        function clearMroute(source, group) {
            if (confirm(`Clear multicast route ${source},${group}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=clear ip mroute ${source} ${group}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing multicast route: ' + data.error);
                    } else {
                        alert('Multicast route cleared successfully');
                        loadMulticastRoutes();
                    }
                })
                .catch(error => {
                    alert('Error clearing multicast route: ' + error.message);
                });
            }
        }

        function removeRpMapping(rpAddress, groupRange) {
            if (confirm(`Remove RP mapping ${rpAddress} for ${groupRange}?`)) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cmd=no ip pim rp-address ${rpAddress} group-list ${groupRange}&type=config`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error removing RP mapping: ' + data.error);
                    } else {
                        alert('RP mapping removed successfully');
                        loadRpMappings();
                    }
                })
                .catch(error => {
                    alert('Error removing RP mapping: ' + error.message);
                });
            }
        }

        function configureIgmpInterface(interfaceName) {
            document.getElementById('mcast-interface').value = interfaceName;
            document.getElementById('enable-igmp').checked = true;
            
            // Switch to configuration tab
            const configTab = new bootstrap.Tab(document.getElementById('config-tab'));
            configTab.show();
            
            document.querySelector('#multicastConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function configureMulticast() {
            // Switch to configuration tab
            const configTab = new bootstrap.Tab(document.getElementById('config-tab'));
            configTab.show();
            
            document.querySelector('#multicastConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadMulticastData();
        }
    </script>
</body>
</html>

