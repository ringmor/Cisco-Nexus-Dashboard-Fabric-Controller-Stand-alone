<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static Routes - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-map-signs"></i> Static Routes Management</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showAddRouteModal()">
                            <i class="fas fa-plus"></i> Add Static Route
                        </button>
                        <button class="btn btn-info" onclick="showBulkImportModal()">
                            <i class="fas fa-upload"></i> Bulk Import
                        </button>
                        <button class="btn btn-warning" onclick="exportRoutes()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Static Routes Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-static-routes">-</div>
                            <div class="metric-label">Total Static Routes</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="active-static-routes">-</div>
                            <div class="metric-label">Active Routes</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="backup-routes">-</div>
                            <div class="metric-label">Backup Routes</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="default-routes">-</div>
                            <div class="metric-label">Default Routes</div>
                        </div>
                    </div>
                </div>

                <!-- Route Configuration Templates -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-templates"></i> Quick Route Templates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary w-100 mb-2" onclick="applyTemplate('default-gateway')">
                                    <i class="fas fa-globe"></i><br>Default Gateway
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success w-100 mb-2" onclick="applyTemplate('host-route')">
                                    <i class="fas fa-desktop"></i><br>Host Route
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-warning w-100 mb-2" onclick="applyTemplate('null-route')">
                                    <i class="fas fa-ban"></i><br>Null Route
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-info w-100 mb-2" onclick="applyTemplate('backup-route')">
                                    <i class="fas fa-shield-alt"></i><br>Backup Route
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Static Routes Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Static Routes Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="route-search" 
                                       placeholder="Search routes..." onkeyup="filterRoutes()">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="route-type-filter" onchange="filterRoutes()">
                                    <option value="">All Route Types</option>
                                    <option value="nexthop">Next Hop Routes</option>
                                    <option value="interface">Interface Routes</option>
                                    <option value="null">Null Routes</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="admin-distance-filter" onchange="filterRoutes()">
                                    <option value="">All Admin Distances</option>
                                    <option value="1">AD 1 (Primary)</option>
                                    <option value="5-50">AD 5-50 (Backup)</option>
                                    <option value="200+">AD 200+ (Floating)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="static-routes-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Destination Network</th>
                                        <th>Next Hop / Interface</th>
                                        <th>Admin Distance</th>
                                        <th>Metric</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="static-routes-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="loading-spinner"></div> Loading static routes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <button class="btn btn-outline-danger" onclick="deleteSelectedRoutes()" disabled id="delete-selected-btn">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                                <button class="btn btn-outline-warning" onclick="disableSelectedRoutes()" disabled id="disable-selected-btn">
                                    <i class="fas fa-pause"></i> Disable Selected
                                </button>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Showing <span id="routes-count">0</span> routes
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Static Route Modal -->
                <div class="modal fade" id="staticRouteModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticRouteModalTitle">Add Static Route</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="static-route-form">
                                    <input type="hidden" id="route-action" value="add">
                                    <input type="hidden" id="original-route-id">
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label">Destination Network *</label>
                                                <input type="text" class="form-control" id="destination-network" 
                                                       placeholder="192.168.1.0/24 or 0.0.0.0/0" required>
                                                <div class="form-text">Network in CIDR notation</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Route Type</label>
                                                <select class="form-select" id="static-route-type" onchange="toggleStaticRouteOptions()">
                                                    <option value="nexthop">Next Hop IP</option>
                                                    <option value="interface">Exit Interface</option>
                                                    <option value="null">Null Route</option>
                                                    <option value="recursive">Recursive Route</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="nexthop-static-config">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">Next Hop IP Address *</label>
                                                    <input type="text" class="form-control" id="static-nexthop" 
                                                           placeholder="192.168.1.1">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Exit Interface</label>
                                                    <select class="form-select" id="static-exit-interface">
                                                        <option value="">Auto-detect</option>
                                                        <!-- Interfaces will be populated here -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="interface-static-config" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Exit Interface *</label>
                                            <select class="form-select" id="static-interface-only">
                                                <option value="">-- Select Interface --</option>
                                                <!-- Interfaces will be populated here -->
                                            </select>
                                        </div>
                                    </div>

                                    <div id="recursive-static-config" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Recursive Next Hop *</label>
                                                    <input type="text" class="form-control" id="recursive-nexthop" 
                                                           placeholder="10.0.0.1">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Resolve Via</label>
                                                    <input type="text" class="form-control" id="resolve-via" 
                                                           placeholder="192.168.1.0/24">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Administrative Distance</label>
                                                <input type="number" class="form-control" id="static-admin-distance" 
                                                       min="1" max="255" value="1">
                                                <div class="form-text">1-255 (1=primary, 200+=floating)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Metric</label>
                                                <input type="number" class="form-control" id="static-metric" 
                                                       min="0" placeholder="Optional">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Tag</label>
                                                <input type="number" class="form-control" id="static-tag" 
                                                       min="0" placeholder="Optional">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" id="static-description" 
                                               placeholder="Route description">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="static-permanent">
                                                <label class="form-check-label" for="static-permanent">
                                                    Permanent (survives interface down)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="static-track">
                                                <label class="form-check-label" for="static-track">
                                                    Enable object tracking
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="static-bfd">
                                                <label class="form-check-label" for="static-bfd">
                                                    Enable BFD monitoring
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="static-vrf">
                                                <label class="form-check-label" for="static-vrf">
                                                    VRF-specific route
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="track-config" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Track Object ID</label>
                                            <input type="number" class="form-control" id="track-object-id" 
                                                   min="1" max="500" placeholder="1-500">
                                        </div>
                                    </div>

                                    <div id="vrf-config" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">VRF Name</label>
                                            <select class="form-select" id="vrf-name">
                                                <option value="">Default VRF</option>
                                                <option value="management">Management</option>
                                                <option value="customer-a">Customer-A</option>
                                                <option value="customer-b">Customer-B</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline-primary" onclick="previewStaticRoute()">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button type="button" class="btn btn-nexus" onclick="saveStaticRoute()">
                                    <span id="static-route-save-btn-text">Add Route</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Import Modal -->
                <div class="modal fade" id="bulkImportModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bulk Import Static Routes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Import Format</label>
                                    <select class="form-select" id="import-format">
                                        <option value="cisco">Cisco IOS Format</option>
                                        <option value="csv">CSV Format</option>
                                        <option value="json">JSON Format</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Routes Configuration</label>
                                    <textarea class="form-control command-input" id="bulk-routes-input" rows="10" 
                                              placeholder="ip route 0.0.0.0 0.0.0.0 192.168.1.1&#10;ip route 10.0.0.0 255.0.0.0 192.168.1.254"></textarea>
                                    <div class="form-text">Enter routes in the selected format</div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="validate-before-import" checked>
                                    <label class="form-check-label" for="validate-before-import">
                                        Validate routes before importing
                                    </label>
                                </div>

                                <div id="import-preview" style="display: none;">
                                    <h6>Import Preview:</h6>
                                    <div class="command-output" id="import-preview-content"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline-primary" onclick="validateBulkImport()">
                                    <i class="fas fa-check"></i> Validate
                                </button>
                                <button type="button" class="btn btn-nexus" onclick="executeBulkImport()">
                                    <i class="fas fa-upload"></i> Import Routes
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
        let staticRoutesData = [];
        let filteredStaticRoutes = [];
        let interfacesData = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadStaticRoutes;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStaticRoutes();
            loadInterfaces();
            setupStaticRouteToggles();
        });

        function loadStaticRoutes() {
            executeCommand('show ip route static', function(data) {
                // Mock data for demonstration
                staticRoutesData = generateMockStaticRoutes();
                filteredStaticRoutes = [...staticRoutesData];
                displayStaticRoutes();
                updateStaticRouteSummary();
            });
        }

        function loadInterfaces() {
            executeCommand('show interface brief', function(data) {
                interfacesData = generateMockInterfaceData();
                populateInterfaceSelects();
            });
        }

        function generateMockStaticRoutes() {
            return [
                {
                    id: 1,
                    network: '0.0.0.0/0',
                    nexthop: '192.168.1.1',
                    interface: 'Vlan1',
                    adminDistance: 1,
                    metric: 0,
                    status: 'active',
                    description: 'Default Gateway',
                    type: 'nexthop',
                    permanent: false,
                    tag: null
                },
                {
                    id: 2,
                    network: '10.0.0.0/8',
                    nexthop: '192.168.1.254',
                    interface: 'Vlan1',
                    adminDistance: 1,
                    metric: 0,
                    status: 'active',
                    description: 'Corporate Network',
                    type: 'nexthop',
                    permanent: false,
                    tag: 100
                },
                {
                    id: 3,
                    network: '172.16.0.0/12',
                    nexthop: null,
                    interface: 'Null0',
                    adminDistance: 1,
                    metric: 0,
                    status: 'active',
                    description: 'Blackhole Route',
                    type: 'null',
                    permanent: true,
                    tag: null
                },
                {
                    id: 4,
                    network: '192.168.100.0/24',
                    nexthop: '192.168.1.100',
                    interface: 'Vlan1',
                    adminDistance: 200,
                    metric: 0,
                    status: 'backup',
                    description: 'Backup Route to DMZ',
                    type: 'nexthop',
                    permanent: false,
                    tag: 200
                }
            ];
        }

        function generateMockInterfaceData() {
            return [
                { interface: 'Vlan1' },
                { interface: 'Vlan10' },
                { interface: 'Vlan20' },
                { interface: 'Vlan100' },
                { interface: 'Ethernet1/1' },
                { interface: 'Ethernet1/2' },
                { interface: 'Null0' }
            ];
        }

        function displayStaticRoutes() {
            const tbody = document.getElementById('static-routes-tbody');
            tbody.innerHTML = '';

            filteredStaticRoutes.forEach(route => {
                const row = document.createElement('tr');
                
                const nextHopDisplay = route.type === 'null' ? 'Null0' : 
                                     route.type === 'interface' ? route.interface :
                                     route.nexthop;
                
                row.innerHTML = `
                    <td>
                        <input type="checkbox" class="route-checkbox" value="${route.id}">
                    </td>
                    <td><strong>${route.network}</strong></td>
                    <td>${nextHopDisplay}</td>
                    <td>
                        <span class="badge ${getAdminDistanceBadge(route.adminDistance)}">${route.adminDistance}</span>
                    </td>
                    <td>${route.metric}</td>
                    <td>
                        <span class="badge ${getStatusBadge(route.status)}">${route.status.toUpperCase()}</span>
                    </td>
                    <td>${route.description || '--'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editStaticRoute(${route.id})"
                                data-bs-toggle="tooltip" title="Edit Route">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="duplicateStaticRoute(${route.id})"
                                data-bs-toggle="tooltip" title="Duplicate Route">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="deleteStaticRoute(${route.id})"
                                data-bs-toggle="tooltip" title="Delete Route">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            document.getElementById('routes-count').textContent = filteredStaticRoutes.length;
            initializeTooltips();
            updateBulkActionButtons();
        }

        function updateStaticRouteSummary() {
            const total = staticRoutesData.length;
            const active = staticRoutesData.filter(r => r.status === 'active').length;
            const backup = staticRoutesData.filter(r => r.adminDistance >= 200).length;
            const defaultRoutes = staticRoutesData.filter(r => r.network === '0.0.0.0/0').length;

            document.getElementById('total-static-routes').textContent = total;
            document.getElementById('active-static-routes').textContent = active;
            document.getElementById('backup-routes').textContent = backup;
            document.getElementById('default-routes').textContent = defaultRoutes;
        }

        function getAdminDistanceBadge(ad) {
            if (ad === 1) return 'bg-success';
            if (ad <= 50) return 'bg-warning';
            if (ad >= 200) return 'bg-secondary';
            return 'bg-primary';
        }

        function getStatusBadge(status) {
            return status === 'active' ? 'bg-success' : 'bg-warning';
        }

        function populateInterfaceSelects() {
            const selects = ['static-exit-interface', 'static-interface-only'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    interfacesData.forEach(intf => {
                        const option = document.createElement('option');
                        option.value = intf.interface;
                        option.textContent = intf.interface;
                        select.appendChild(option);
                    });
                }
            });
        }

        function setupStaticRouteToggles() {
            document.getElementById('static-route-type').addEventListener('change', toggleStaticRouteOptions);
            document.getElementById('static-track').addEventListener('change', function() {
                document.getElementById('track-config').style.display = this.checked ? 'block' : 'none';
            });
            document.getElementById('static-vrf').addEventListener('change', function() {
                document.getElementById('vrf-config').style.display = this.checked ? 'block' : 'none';
            });
        }

        function toggleStaticRouteOptions() {
            const routeType = document.getElementById('static-route-type').value;
            
            document.getElementById('nexthop-static-config').style.display = 'none';
            document.getElementById('interface-static-config').style.display = 'none';
            document.getElementById('recursive-static-config').style.display = 'none';

            if (routeType === 'nexthop') {
                document.getElementById('nexthop-static-config').style.display = 'block';
            } else if (routeType === 'interface') {
                document.getElementById('interface-static-config').style.display = 'block';
            } else if (routeType === 'recursive') {
                document.getElementById('recursive-static-config').style.display = 'block';
            }
        }

        function filterRoutes() {
            const searchTerm = document.getElementById('route-search').value.toLowerCase();
            const typeFilter = document.getElementById('route-type-filter').value;
            const adFilter = document.getElementById('admin-distance-filter').value;

            filteredStaticRoutes = staticRoutesData.filter(route => {
                const matchesSearch = route.network.toLowerCase().includes(searchTerm) ||
                                    (route.description && route.description.toLowerCase().includes(searchTerm));
                
                const matchesType = !typeFilter || route.type === typeFilter;
                
                let matchesAD = true;
                if (adFilter === '1') matchesAD = route.adminDistance === 1;
                else if (adFilter === '5-50') matchesAD = route.adminDistance >= 5 && route.adminDistance <= 50;
                else if (adFilter === '200+') matchesAD = route.adminDistance >= 200;

                return matchesSearch && matchesType && matchesAD;
            });

            displayStaticRoutes();
        }

        function clearFilters() {
            document.getElementById('route-search').value = '';
            document.getElementById('route-type-filter').value = '';
            document.getElementById('admin-distance-filter').value = '';
            filteredStaticRoutes = [...staticRoutesData];
            displayStaticRoutes();
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.route-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkActionButtons();
        }

        function updateBulkActionButtons() {
            const checkedBoxes = document.querySelectorAll('.route-checkbox:checked');
            const deleteBtn = document.getElementById('delete-selected-btn');
            const disableBtn = document.getElementById('disable-selected-btn');
            
            const hasSelection = checkedBoxes.length > 0;
            deleteBtn.disabled = !hasSelection;
            disableBtn.disabled = !hasSelection;
        }

        // Add event listeners to checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('route-checkbox')) {
                updateBulkActionButtons();
            }
        });

        function applyTemplate(templateType) {
            showAddRouteModal();
            
            switch (templateType) {
                case 'default-gateway':
                    document.getElementById('destination-network').value = '0.0.0.0/0';
                    document.getElementById('static-route-type').value = 'nexthop';
                    document.getElementById('static-nexthop').value = '192.168.1.1';
                    document.getElementById('static-description').value = 'Default Gateway';
                    break;
                    
                case 'host-route':
                    document.getElementById('destination-network').value = '192.168.1.100/32';
                    document.getElementById('static-route-type').value = 'nexthop';
                    document.getElementById('static-description').value = 'Host Route';
                    break;
                    
                case 'null-route':
                    document.getElementById('destination-network').value = '192.168.99.0/24';
                    document.getElementById('static-route-type').value = 'null';
                    document.getElementById('static-description').value = 'Blackhole Route';
                    break;
                    
                case 'backup-route':
                    document.getElementById('destination-network').value = '10.0.0.0/8';
                    document.getElementById('static-route-type').value = 'nexthop';
                    document.getElementById('static-admin-distance').value = '200';
                    document.getElementById('static-description').value = 'Backup Route';
                    break;
            }
            
            toggleStaticRouteOptions();
        }

        function showAddRouteModal() {
            document.getElementById('route-action').value = 'add';
            document.getElementById('staticRouteModalTitle').textContent = 'Add Static Route';
            document.getElementById('static-route-save-btn-text').textContent = 'Add Route';
            
            document.getElementById('static-route-form').reset();
            document.getElementById('static-admin-distance').value = '1';
            toggleStaticRouteOptions();
            
            const modal = new bootstrap.Modal(document.getElementById('staticRouteModal'));
            modal.show();
        }

        function editStaticRoute(routeId) {
            const route = staticRoutesData.find(r => r.id === routeId);
            if (!route) return;
            
            document.getElementById('route-action').value = 'edit';
            document.getElementById('staticRouteModalTitle').textContent = 'Edit Static Route';
            document.getElementById('static-route-save-btn-text').textContent = 'Update Route';
            document.getElementById('original-route-id').value = routeId;
            
            // Populate form
            document.getElementById('destination-network').value = route.network;
            document.getElementById('static-route-type').value = route.type;
            document.getElementById('static-admin-distance').value = route.adminDistance;
            document.getElementById('static-metric').value = route.metric || '';
            document.getElementById('static-description').value = route.description || '';
            document.getElementById('static-permanent').checked = route.permanent;
            
            if (route.type === 'nexthop') {
                document.getElementById('static-nexthop').value = route.nexthop;
            } else if (route.type === 'interface') {
                document.getElementById('static-interface-only').value = route.interface;
            }
            
            toggleStaticRouteOptions();
            
            const modal = new bootstrap.Modal(document.getElementById('staticRouteModal'));
            modal.show();
        }

        function duplicateStaticRoute(routeId) {
            const route = staticRoutesData.find(r => r.id === routeId);
            if (!route) return;
            
            editStaticRoute(routeId);
            document.getElementById('route-action').value = 'add';
            document.getElementById('staticRouteModalTitle').textContent = 'Duplicate Static Route';
            document.getElementById('static-route-save-btn-text').textContent = 'Add Route';
            document.getElementById('original-route-id').value = '';
        }

        function saveStaticRoute() {
            const action = document.getElementById('route-action').value;
            const network = document.getElementById('destination-network').value;
            const routeType = document.getElementById('static-route-type').value;
            const adminDistance = document.getElementById('static-admin-distance').value;
            const description = document.getElementById('static-description').value;
            const vrf = document.getElementById('static-vrf') ? document.getElementById('static-vrf').value : '';

            if (!network) {
                showAlert('Please enter a destination network.', 'danger');
                return;
            }

            let routeCommand = 'ip route';
            if (vrf && vrf.trim() !== '') {
                routeCommand += ` vrf ${vrf.trim()}`;
            }
            routeCommand += ` ${network}`;

            if (routeType === 'nexthop') {
                const nextHop = document.getElementById('static-nexthop').value;
                if (!nextHop || !validateIP(nextHop)) {
                    showAlert('Please enter a valid next hop IP address.', 'danger');
                    return;
                }
                routeCommand += ` ${nextHop}`;
            } else if (routeType === 'interface') {
                const iface = document.getElementById('static-interface-only').value;
                if (!iface) {
                    showAlert('Please select an exit interface.', 'danger');
                    return;
                }
                routeCommand += ` ${iface}`;
            } else if (routeType === 'null') {
                routeCommand += ' null0';
            }

            if (adminDistance && adminDistance !== '1') {
                routeCommand += ` ${adminDistance}`;
            }

            const metric = document.getElementById('static-metric').value;
            if (metric) {
                routeCommand += ` ${metric}`;
            }

            if (document.getElementById('static-permanent').checked) {
                routeCommand += ' permanent';
            }

            if (description) {
                routeCommand += ` name ${description}`;
            }

            confirmAction(`${action === 'add' ? 'Add' : 'Update'} static route?\n\n${routeCommand}`, function() {
                executeCommand(routeCommand, function(data) {
                    showAlert(`Static route ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('staticRouteModal')).hide();
                    setTimeout(loadStaticRoutes, 2000);
                }, 'cli_conf');
            });
        }

        function deleteStaticRoute(routeId) {
            const route = staticRoutesData.find(r => r.id === routeId);
            if (!route) return;
            // Reconstruct the full static route command for deletion
            let routeCommand = 'no ip route';
            if (route.vrf && route.vrf.trim() !== '') {
                routeCommand += ` vrf ${route.vrf.trim()}`;
            }
            routeCommand += ` ${route.network}`;
            if (route.type === 'nexthop' && route.nexthop) {
                routeCommand += ` ${route.nexthop}`;
            } else if (route.type === 'interface' && route.interface) {
                routeCommand += ` ${route.interface}`;
            } else if (route.type === 'null') {
                routeCommand += ' null0';
            }
            if (route.adminDistance && route.adminDistance !== '1') {
                routeCommand += ` ${route.adminDistance}`;
            }
            if (route.metric) {
                routeCommand += ` ${route.metric}`;
            }
            if (route.permanent) {
                routeCommand += ' permanent';
            }
            if (route.description) {
                routeCommand += ` name ${route.description}`;
            }
            confirmAction(`Are you sure you want to delete the static route to ${route.network}?`, function() {
                executeCommand(routeCommand, function(data) {
                    showAlert('Static route deleted successfully', 'success');
                    setTimeout(loadStaticRoutes, 2000);
                }, 'cli_conf');
            });
        }

        function deleteSelectedRoutes() {
            const checkedBoxes = document.querySelectorAll('.route-checkbox:checked');
            const routeIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));
            
            if (routeIds.length === 0) return;
            
            confirmAction(`Delete ${routeIds.length} selected route(s)?`, function() {
                let commands = ['configure terminal'];
                
                routeIds.forEach(routeId => {
                    const route = staticRoutesData.find(r => r.id === routeId);
                    if (route) {
                        let command = `no ip route ${route.network}`;
                        if (route.nexthop) {
                            command += ` ${route.nexthop}`;
                        }
                        commands.push(command);
                    }
                });

                executeCommand(commands.join('\n'), function(data) {
                    showAlert(`${routeIds.length} static routes deleted successfully`, 'success');
                    setTimeout(loadStaticRoutes, 2000);
                });
            });
        }

        function previewStaticRoute() {
            // Generate preview of the route command
            showAlert('Route preview feature coming soon!', 'info');
        }

        function exportRoutes() {
            const routeConfig = staticRoutesData.map(route => {
                let command = `ip route ${route.network}`;
                if (route.nexthop) command += ` ${route.nexthop}`;
                if (route.adminDistance !== 1) command += ` ${route.adminDistance}`;
                if (route.description) command += ` name ${route.description}`;
                return command;
            }).join('\n');

            exportConfig(routeConfig, 'static-routes.txt');
        }

        function showBulkImportModal() {
            const modal = new bootstrap.Modal(document.getElementById('bulkImportModal'));
            modal.show();
        }

        function validateBulkImport() {
            const input = document.getElementById('bulk-routes-input').value;
            const format = document.getElementById('import-format').value;
            
            if (!input.trim()) {
                showAlert('Please enter routes to import.', 'warning');
                return;
            }

            // Simple validation for Cisco format
            const lines = input.split('\n').filter(line => line.trim());
            const validRoutes = [];
            const errors = [];

            lines.forEach((line, index) => {
                if (line.trim().startsWith('ip route')) {
                    validRoutes.push(line.trim());
                } else if (line.trim()) {
                    errors.push(`Line ${index + 1}: Invalid format`);
                }
            });

            const previewDiv = document.getElementById('import-preview');
            const previewContent = document.getElementById('import-preview-content');
            
            previewContent.innerHTML = `
                <strong>Valid Routes: ${validRoutes.length}</strong><br>
                <strong>Errors: ${errors.length}</strong><br><br>
                ${validRoutes.join('<br>')}
                ${errors.length > 0 ? '<br><br><span class="text-danger">' + errors.join('<br>') + '</span>' : ''}
            `;
            
            previewDiv.style.display = 'block';
        }

        function executeBulkImport() {
            const input = document.getElementById('bulk-routes-input').value;
            
            if (!input.trim()) {
                showAlert('Please enter routes to import.', 'warning');
                return;
            }

            confirmAction('Import the validated routes?', function() {
                executeCommand(`configure terminal\n${input}`, function(data) {
                    showAlert('Routes imported successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('bulkImportModal')).hide();
                    setTimeout(loadStaticRoutes, 2000);
                });
            });
        }
    </script>
</body>
</html>

