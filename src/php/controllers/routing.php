<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Table - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-route"></i> Route Table</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showAddRouteModal()">
                            <i class="fas fa-plus"></i> Add Route
                        </button>
                        <button class="btn btn-info" onclick="showRoutingProtocolsModal()">
                            <i class="fas fa-cogs"></i> Protocols
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Routing Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-routes">-</div>
                            <div class="metric-label">Total Routes</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="connected-routes">-</div>
                            <div class="metric-label">Connected</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="static-routes">-</div>
                            <div class="metric-label">Static</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="dynamic-routes">-</div>
                            <div class="metric-label">Dynamic</div>
                        </div>
                    </div>
                </div>

                <!-- Route Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Filter by Protocol</label>
                                <select class="form-select" id="protocol-filter" onchange="filterRoutes()">
                                    <option value="">All Protocols</option>
                                    <option value="C">Connected</option>
                                    <option value="S">Static</option>
                                    <option value="O">OSPF</option>
                                    <option value="B">BGP</option>
                                    <option value="R">RIP</option>
                                    <option value="E">EIGRP</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter by Network</label>
                                <input type="text" class="form-control" id="network-filter" 
                                       placeholder="192.168.1.0/24" onkeyup="filterRoutes()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter by Next Hop</label>
                                <input type="text" class="form-control" id="nexthop-filter" 
                                       placeholder="192.168.1.1" onkeyup="filterRoutes()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter by Interface</label>
                                <select class="form-select" id="interface-filter" onchange="filterRoutes()">
                                    <option value="">All Interfaces</option>
                                    <!-- Interfaces will be populated here -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Route Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> IP Routing Table</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="routes-table">
                                <thead>
                                    <tr>
                                        <th>Protocol</th>
                                        <th>Network</th>
                                        <th>Next Hop</th>
                                        <th>Interface</th>
                                        <th>Metric</th>
                                        <th>Admin Distance</th>
                                        <th>Age</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="routes-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="loading-spinner"></div> Loading routes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Route Modal -->
                <div class="modal fade" id="routeModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="routeModalTitle">Add Static Route</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="route-form">
                                    <input type="hidden" id="route-action" value="add">
                                    <input type="hidden" id="original-route-network">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Destination Network *</label>
                                        <input type="text" class="form-control" id="route-network" 
                                               placeholder="192.168.1.0/24" required>
                                        <div class="form-text">Network in CIDR notation</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Route Type</label>
                                        <select class="form-select" id="route-type" onchange="toggleRouteOptions()">
                                            <option value="nexthop">Next Hop IP</option>
                                            <option value="interface">Exit Interface</option>
                                            <option value="null">Null Route (Discard)</option>
                                        </select>
                                    </div>

                                    <div id="nexthop-config">
                                        <div class="mb-3">
                                            <label class="form-label">Next Hop IP Address *</label>
                                            <input type="text" class="form-control" id="route-nexthop" 
                                                   placeholder="192.168.1.1">
                                        </div>
                                    </div>

                                    <div id="interface-config" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Exit Interface *</label>
                                            <select class="form-select" id="route-interface">
                                                <option value="">-- Select Interface --</option>
                                                <!-- Interfaces will be populated here -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Administrative Distance</label>
                                                <input type="number" class="form-control" id="route-admin-distance" 
                                                       min="1" max="255" value="1">
                                                <div class="form-text">1-255 (default: 1)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Metric</label>
                                                <input type="number" class="form-control" id="route-metric" 
                                                       min="0" placeholder="Optional">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" id="route-description" 
                                               placeholder="Route description">
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="route-permanent">
                                        <label class="form-check-label" for="route-permanent">
                                            Permanent route (survives interface down)
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="saveRoute()">
                                    <span id="route-save-btn-text">Add Route</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Routing Protocols Modal -->
                <div class="modal fade" id="protocolsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Routing Protocols Configuration</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-network-wired"></i> OSPF</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="ospf-enable">
                                                        <label class="form-check-label" for="ospf-enable">
                                                            Enable OSPF
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Process ID</label>
                                                    <input type="number" class="form-control" id="ospf-process-id" 
                                                           min="1" max="65535" value="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Router ID</label>
                                                    <input type="text" class="form-control" id="ospf-router-id" 
                                                           placeholder="1.1.1.1">
                                                </div>
                                                <button class="btn btn-outline-primary btn-sm" onclick="configureOspf()">
                                                    Configure OSPF
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0"><i class="fas fa-globe"></i> BGP</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="bgp-enable">
                                                        <label class="form-check-label" for="bgp-enable">
                                                            Enable BGP
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">AS Number</label>
                                                    <input type="number" class="form-control" id="bgp-as-number" 
                                                           min="1" max="4294967295" placeholder="65001">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Router ID</label>
                                                    <input type="text" class="form-control" id="bgp-router-id" 
                                                           placeholder="1.1.1.1">
                                                </div>
                                                <button class="btn btn-outline-success btn-sm" onclick="configureBgp()">
                                                    Configure BGP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><i class="fas fa-exchange-alt"></i> EIGRP</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="eigrp-enable">
                                                        <label class="form-check-label" for="eigrp-enable">
                                                            Enable EIGRP
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">AS Number</label>
                                                    <input type="number" class="form-control" id="eigrp-as-number" 
                                                           min="1" max="65535" value="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Router ID</label>
                                                    <input type="text" class="form-control" id="eigrp-router-id" 
                                                           placeholder="1.1.1.1">
                                                </div>
                                                <button class="btn btn-outline-warning btn-sm" onclick="configureEigrp()">
                                                    Configure EIGRP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><i class="fas fa-route"></i> RIP</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="rip-enable">
                                                        <label class="form-check-label" for="rip-enable">
                                                            Enable RIP
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Version</label>
                                                    <select class="form-select" id="rip-version">
                                                        <option value="2">RIP Version 2</option>
                                                        <option value="1">RIP Version 1</option>
                                                    </select>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="rip-auto-summary">
                                                    <label class="form-check-label" for="rip-auto-summary">
                                                        Auto Summary
                                                    </label>
                                                </div>
                                                <button class="btn btn-outline-info btn-sm" onclick="configureRip()">
                                                    Configure RIP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
        let routesData = [];
        let interfacesData = [];
        let filteredRoutes = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            setupRouteTypeToggle();
        });

        function loadData() {
            loadRoutes();
            loadInterfaces();
        }

        function loadRoutes() {
            executeCommand('show ip route', function(data) {
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_vrf) {
                        const vrfData = output.body.TABLE_vrf.ROW_vrf;
                        if (vrfData && vrfData.TABLE_addrf && vrfData.TABLE_addrf.ROW_addrf) {
                            const addrfData = vrfData.TABLE_addrf.ROW_addrf;
                            if (addrfData.TABLE_prefix && addrfData.TABLE_prefix.ROW_prefix) {
                                routesData = addrfData.TABLE_prefix.ROW_prefix;
                                if (!Array.isArray(routesData)) {
                                    routesData = [routesData];
                                }
                            }
                        }
                    }
                } else {
                    // Mock data for demonstration
                    routesData = generateMockRouteData();
                }
                
                filteredRoutes = [...routesData];
                displayRoutes();
                updateSummaryCards();
            });
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
                populateInterfaceSelects();
            });
        }

        function generateMockRouteData() {
            return [
                {
                    ipprefix: '0.0.0.0/0',
                    ucast_nhops: '1',
                    attach: 'false',
                    TABLE_path: {
                        ROW_path: {
                            ipnexthop: '192.168.1.1',
                            ifname: 'Vlan100',
                            uptime: '1d2h',
                            pref: '1',
                            metric: '0',
                            clientname: 'static'
                        }
                    }
                },
                {
                    ipprefix: '192.168.1.0/24',
                    ucast_nhops: '1',
                    attach: 'true',
                    TABLE_path: {
                        ROW_path: {
                            ipnexthop: '0.0.0.0',
                            ifname: 'Vlan1',
                            uptime: '2d5h',
                            pref: '0',
                            metric: '0',
                            clientname: 'direct'
                        }
                    }
                },
                {
                    ipprefix: '192.168.10.0/24',
                    ucast_nhops: '1',
                    attach: 'true',
                    TABLE_path: {
                        ROW_path: {
                            ipnexthop: '0.0.0.0',
                            ifname: 'Vlan10',
                            uptime: '1d8h',
                            pref: '0',
                            metric: '0',
                            clientname: 'direct'
                        }
                    }
                },
                {
                    ipprefix: '192.168.20.0/24',
                    ucast_nhops: '1',
                    attach: 'true',
                    TABLE_path: {
                        ROW_path: {
                            ipnexthop: '0.0.0.0',
                            ifname: 'Vlan20',
                            uptime: '1d8h',
                            pref: '0',
                            metric: '0',
                            clientname: 'direct'
                        }
                    }
                },
                {
                    ipprefix: '10.0.0.0/8',
                    ucast_nhops: '1',
                    attach: 'false',
                    TABLE_path: {
                        ROW_path: {
                            ipnexthop: '192.168.1.254',
                            ifname: 'Vlan1',
                            uptime: '5h32m',
                            pref: '110',
                            metric: '20',
                            clientname: 'ospf-1'
                        }
                    }
                }
            ];
        }

        function generateMockInterfaceData() {
            return [
                { interface: 'Vlan1', state: 'up' },
                { interface: 'Vlan10', state: 'up' },
                { interface: 'Vlan20', state: 'up' },
                { interface: 'Vlan100', state: 'up' },
                { interface: 'Ethernet1/1', state: 'up' },
                { interface: 'Ethernet1/2', state: 'up' }
            ];
        }

        function displayRoutes() {
            const tbody = document.getElementById('routes-tbody');
            tbody.innerHTML = '';

            filteredRoutes.forEach(route => {
                const row = document.createElement('tr');
                
                const pathData = route.TABLE_path ? route.TABLE_path.ROW_path : null;
                const protocol = getProtocolCode(pathData ? pathData.clientname : 'unknown');
                const nextHop = pathData ? (pathData.ipnexthop === '0.0.0.0' ? 'directly connected' : pathData.ipnexthop) : '--';
                const interface = pathData ? pathData.ifname : '--';
                const metric = pathData ? pathData.metric : '--';
                const adminDistance = pathData ? pathData.pref : '--';
                const age = pathData ? pathData.uptime : '--';
                
                row.innerHTML = `
                    <td><span class="badge ${getProtocolBadgeClass(protocol)}">${protocol}</span></td>
                    <td><strong>${route.ipprefix}</strong></td>
                    <td>${nextHop}</td>
                    <td>${formatInterfaceName(interface)}</td>
                    <td>${metric}</td>
                    <td>${adminDistance}</td>
                    <td>${age}</td>
                    <td>
                        ${protocol === 'S' ? `
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editRoute('${route.ipprefix}', '${nextHop}', '${interface}')"
                                data-bs-toggle="tooltip" title="Edit Route">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="deleteRoute('${route.ipprefix}', '${nextHop}')"
                                data-bs-toggle="tooltip" title="Delete Route">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : `
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="showRouteDetails('${route.ipprefix}')"
                                data-bs-toggle="tooltip" title="View Details">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        `}
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            initializeTooltips();
        }

        function updateSummaryCards() {
            const total = routesData.length;
            const connected = routesData.filter(r => {
                const pathData = r.TABLE_path ? r.TABLE_path.ROW_path : null;
                return pathData && pathData.clientname === 'direct';
            }).length;
            const static = routesData.filter(r => {
                const pathData = r.TABLE_path ? r.TABLE_path.ROW_path : null;
                return pathData && pathData.clientname === 'static';
            }).length;
            const dynamic = total - connected - static;

            document.getElementById('total-routes').textContent = total;
            document.getElementById('connected-routes').textContent = connected;
            document.getElementById('static-routes').textContent = static;
            document.getElementById('dynamic-routes').textContent = dynamic;
        }

        function getProtocolCode(clientname) {
            const protocolMap = {
                'direct': 'C',
                'static': 'S',
                'ospf-1': 'O',
                'ospf-2': 'O',
                'bgp-65001': 'B',
                'eigrp-1': 'D',
                'rip-1': 'R'
            };
            return protocolMap[clientname] || 'U';
        }

        function getProtocolBadgeClass(protocol) {
            const classMap = {
                'C': 'bg-success',
                'S': 'bg-primary',
                'O': 'bg-warning',
                'B': 'bg-info',
                'D': 'bg-secondary',
                'R': 'bg-dark'
            };
            return classMap[protocol] || 'bg-light text-dark';
        }

        function populateInterfaceSelects() {
            const selects = ['interface-filter', 'route-interface'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    // Clear existing options except the first one
                    while (select.children.length > 1) {
                        select.removeChild(select.lastChild);
                    }
                    
                    interfacesData.forEach(intf => {
                        const option = document.createElement('option');
                        option.value = intf.interface;
                        option.textContent = intf.interface;
                        select.appendChild(option);
                    });
                }
            });
        }

        function filterRoutes() {
            const protocolFilter = document.getElementById('protocol-filter').value;
            const networkFilter = document.getElementById('network-filter').value.toLowerCase();
            const nexthopFilter = document.getElementById('nexthop-filter').value.toLowerCase();
            const interfaceFilter = document.getElementById('interface-filter').value;

            filteredRoutes = routesData.filter(route => {
                const pathData = route.TABLE_path ? route.TABLE_path.ROW_path : null;
                const protocol = getProtocolCode(pathData ? pathData.clientname : 'unknown');
                const nextHop = pathData ? pathData.ipnexthop : '';
                const interface = pathData ? pathData.ifname : '';

                return (!protocolFilter || protocol === protocolFilter) &&
                       (!networkFilter || route.ipprefix.toLowerCase().includes(networkFilter)) &&
                       (!nexthopFilter || nextHop.toLowerCase().includes(nexthopFilter)) &&
                       (!interfaceFilter || interface === interfaceFilter);
            });

            displayRoutes();
        }

        function setupRouteTypeToggle() {
            document.getElementById('route-type').addEventListener('change', toggleRouteOptions);
        }

        function toggleRouteOptions() {
            const routeType = document.getElementById('route-type').value;
            const nexthopConfig = document.getElementById('nexthop-config');
            const interfaceConfig = document.getElementById('interface-config');

            nexthopConfig.style.display = 'none';
            interfaceConfig.style.display = 'none';

            if (routeType === 'nexthop') {
                nexthopConfig.style.display = 'block';
            } else if (routeType === 'interface') {
                interfaceConfig.style.display = 'block';
            }
        }

        function showAddRouteModal() {
            document.getElementById('route-action').value = 'add';
            document.getElementById('routeModalTitle').textContent = 'Add Static Route';
            document.getElementById('route-save-btn-text').textContent = 'Add Route';
            
            // Reset form
            document.getElementById('route-form').reset();
            document.getElementById('route-admin-distance').value = '1';
            toggleRouteOptions();
            
            const modal = new bootstrap.Modal(document.getElementById('routeModal'));
            modal.show();
        }

        function editRoute(network, nextHop, interface) {
            document.getElementById('route-action').value = 'edit';
            document.getElementById('routeModalTitle').textContent = 'Edit Static Route';
            document.getElementById('route-save-btn-text').textContent = 'Update Route';
            
            // Populate form
            document.getElementById('route-network').value = network;
            document.getElementById('original-route-network').value = network;
            
            if (nextHop !== 'directly connected') {
                document.getElementById('route-type').value = 'nexthop';
                document.getElementById('route-nexthop').value = nextHop;
            } else {
                document.getElementById('route-type').value = 'interface';
                document.getElementById('route-interface').value = interface;
            }
            
            toggleRouteOptions();
            
            const modal = new bootstrap.Modal(document.getElementById('routeModal'));
            modal.show();
        }

        function saveRoute() {
            const action = document.getElementById('route-action').value;
            const network = document.getElementById('route-network').value;
            const routeType = document.getElementById('route-type').value;
            const adminDistance = document.getElementById('route-admin-distance').value;
            const metric = document.getElementById('route-metric').value;
            const description = document.getElementById('route-description').value;
            const permanent = document.getElementById('route-permanent').checked;

            if (!network) {
                showAlert('Please enter a destination network.', 'danger');
                return;
            }

            let commands = [];
            let routeCommand = `ip route ${network}`;

            if (routeType === 'nexthop') {
                const nextHop = document.getElementById('route-nexthop').value;
                if (!nextHop || !validateIP(nextHop)) {
                    showAlert('Please enter a valid next hop IP address.', 'danger');
                    return;
                }
                routeCommand += ` ${nextHop}`;
            } else if (routeType === 'interface') {
                const interface = document.getElementById('route-interface').value;
                if (!interface) {
                    showAlert('Please select an exit interface.', 'danger');
                    return;
                }
                routeCommand += ` ${interface}`;
            } else if (routeType === 'null') {
                routeCommand += ' null0';
            }

            if (adminDistance && adminDistance !== '1') {
                routeCommand += ` ${adminDistance}`;
            }

            if (metric) {
                routeCommand += ` ${metric}`;
            }

            if (permanent) {
                routeCommand += ' permanent';
            }

            if (description) {
                routeCommand += ` name ${description}`;
            }

            commands.push(routeCommand);

            const configCommands = commands.join('\n');
            
            confirmAction(`${action === 'add' ? 'Add' : 'Update'} static route?\n\n${configCommands}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert(`Static route ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('routeModal')).hide();
                    setTimeout(loadData, 2000);
                }, 'cli_conf');
            });
        }

        function deleteRoute(network, nextHop) {
            confirmAction(`Are you sure you want to delete the static route to ${network}?`, function() {
                let command = `no ip route ${network}`;
                if (nextHop !== 'directly connected') {
                    command += ` ${nextHop}`;
                }

                executeCommand(`configure terminal\n${command}`, function(data) {
                    showAlert('Static route deleted successfully', 'success');
                    setTimeout(loadData, 2000);
                });
            });
        }

        function showRouteDetails(network) {
            executeCommand(`show ip route ${network}`, function(data) {
                showAlert(`Detailed route information for ${network} - Feature coming soon!`, 'info');
            });
        }

        function showRoutingProtocolsModal() {
            const modal = new bootstrap.Modal(document.getElementById('protocolsModal'));
            modal.show();
        }

        function configureOspf() {
            const enable = document.getElementById('ospf-enable').checked;
            const processId = document.getElementById('ospf-process-id').value;
            const routerId = document.getElementById('ospf-router-id').value;

            if (!enable) {
                showAlert('Please enable OSPF first.', 'warning');
                return;
            }

            if (!processId) {
                showAlert('Please enter OSPF process ID.', 'danger');
                return;
            }

            let commands = [`router ospf ${processId}`];
            
            if (routerId && validateIP(routerId)) {
                commands.push(`router-id ${routerId}`);
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`Configure OSPF with the following settings?\n\n${configCommands}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('OSPF configuration applied successfully', 'success');
                }, 'cli_conf');
            });
        }

        function configureBgp() {
            const enable = document.getElementById('bgp-enable').checked;
            const asNumber = document.getElementById('bgp-as-number').value;
            const routerId = document.getElementById('bgp-router-id').value;

            if (!enable) {
                showAlert('Please enable BGP first.', 'warning');
                return;
            }

            if (!asNumber) {
                showAlert('Please enter BGP AS number.', 'danger');
                return;
            }

            let commands = [`router bgp ${asNumber}`];
            
            if (routerId && validateIP(routerId)) {
                commands.push(`router-id ${routerId}`);
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`Configure BGP with the following settings?\n\n${configCommands}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('BGP configuration applied successfully', 'success');
                }, 'cli_conf');
            });
        }

        function configureEigrp() {
            const enable = document.getElementById('eigrp-enable').checked;
            const asNumber = document.getElementById('eigrp-as-number').value;
            const routerId = document.getElementById('eigrp-router-id').value;

            if (!enable) {
                showAlert('Please enable EIGRP first.', 'warning');
                return;
            }

            if (!asNumber) {
                showAlert('Please enter EIGRP AS number.', 'danger');
                return;
            }

            let commands = [`router eigrp ${asNumber}`];
            
            if (routerId && validateIP(routerId)) {
                commands.push(`router-id ${routerId}`);
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`Configure EIGRP with the following settings?\n\n${configCommands}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('EIGRP configuration applied successfully', 'success');
                }, 'cli_conf');
            });
        }

        function configureRip() {
            const enable = document.getElementById('rip-enable').checked;
            const version = document.getElementById('rip-version').value;
            const autoSummary = document.getElementById('rip-auto-summary').checked;

            if (!enable) {
                showAlert('Please enable RIP first.', 'warning');
                return;
            }

            let commands = ['router rip'];
            commands.push(`version ${version}`);
            
            if (!autoSummary) {
                commands.push('no auto-summary');
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`Configure RIP with the following settings?\n\n${configCommands}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('RIP configuration applied successfully', 'success');
                }, 'cli_conf');
            });
        }
    </script>
</body>
</html>

