<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Control Lists - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-shield-alt"></i> Access Control Lists (ACLs)</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showCreateAclModal()">
                            <i class="fas fa-plus"></i> Create ACL
                        </button>
                        <button class="btn btn-info" onclick="showAclTemplatesModal()">
                            <i class="fas fa-layer-group"></i> Templates
                        </button>
                        <button class="btn btn-warning" onclick="exportAcls()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- ACL Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-acls">-</div>
                            <div class="metric-label">Total ACLs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="applied-acls">-</div>
                            <div class="metric-label">Applied ACLs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="unused-acls">-</div>
                            <div class="metric-label">Unused ACLs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="total-rules">-</div>
                            <div class="metric-label">Total Rules</div>
                        </div>
                    </div>
                </div>

                <!-- ACL Types Tabs -->
                <ul class="nav nav-tabs mb-4" id="aclTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ipv4-tab" data-bs-toggle="tab" data-bs-target="#ipv4-acls" type="button" role="tab">
                            <i class="fas fa-network-wired"></i> IPv4 ACLs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ipv6-tab" data-bs-toggle="tab" data-bs-target="#ipv6-acls" type="button" role="tab">
                            <i class="fas fa-globe"></i> IPv6 ACLs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mac-tab" data-bs-toggle="tab" data-bs-target="#mac-acls" type="button" role="tab">
                            <i class="fas fa-ethernet"></i> MAC ACLs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="object-groups-tab" data-bs-toggle="tab" data-bs-target="#object-groups" type="button" role="tab">
                            <i class="fas fa-layer-group"></i> Object Groups
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="aclTabContent">
                    <!-- IPv4 ACLs Tab -->
                    <div class="tab-pane fade show active" id="ipv4-acls" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-list"></i> IPv4 Access Control Lists</h5>
                                    <div class="d-flex gap-2">
                                        <select class="form-select form-select-sm" id="ipv4-acl-filter" onchange="filterIpv4Acls()">
                                            <option value="">All ACLs</option>
                                            <option value="standard">Standard ACLs</option>
                                            <option value="extended">Extended ACLs</option>
                                            <option value="named">Named ACLs</option>
                                        </select>
                                        <input type="text" class="form-control form-control-sm" id="ipv4-search" 
                                               placeholder="Search ACLs..." onkeyup="filterIpv4Acls()">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleIpv4RawJson()" data-bs-toggle="tooltip" title="Show raw JSON">
                                            <i class="fas fa-code"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="ipv4-acls-table">
                                        <thead>
                                            <tr>
                                                <th>ACL Name/Number</th>
                                                <th>Type</th>
                                                <th>Rules</th>
                                                <th>Applied To</th>
                                                <th>Direction</th>
                                                <th>Hit Count</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ipv4-acls-tbody">
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <div class="loading-spinner"></div> Loading IPv4 ACLs...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Raw JSON output for IPv4 -->
                                <div class="mt-3">
                                    <pre id="ipv4-json-raw" class="command-output"></pre>
                                </div>
                                <!-- Parsed ACL details -->
                                <div id="ipv4-acl-details" class="mt-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- IPv6 ACLs Tab -->
                    <div class="tab-pane fade" id="ipv6-acls" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-list"></i> IPv6 Access Control Lists</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ACL Name</th>
                                                <th>Rules</th>
                                                <th>Applied To</th>
                                                <th>Direction</th>
                                                <th>Hit Count</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ipv6-acls-tbody">
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="loading-spinner"></div> Loading IPv6 ACLs...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MAC ACLs Tab -->
                    <div class="tab-pane fade" id="mac-acls" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-list"></i> MAC Access Control Lists</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ACL Name</th>
                                                <th>Rules</th>
                                                <th>Applied To</th>
                                                <th>Direction</th>
                                                <th>Hit Count</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mac-acls-tbody">
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="loading-spinner"></div> Loading MAC ACLs...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Object Groups Tab -->
                    <div class="tab-pane fade" id="object-groups" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-layer-group"></i> Object Groups</h5>
                                    <button class="btn btn-sm btn-success" onclick="showCreateObjectGroupModal()">
                                        <i class="fas fa-plus"></i> Create Object Group
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-network-wired"></i> Network Object Groups</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Members</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="network-groups-tbody">
                                                    <!-- Network groups will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-plug"></i> Service Object Groups</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Members</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="service-groups-tbody">
                                                    <!-- Service groups will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit ACL Modal -->
                <div class="modal fade" id="aclModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="aclModalTitle">Create IPv4 ACL</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="acl-form">
                                    <input type="hidden" id="acl-action" value="create">
                                    <input type="hidden" id="original-acl-name">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">ACL Type *</label>
                                            <select class="form-select" id="acl-type" onchange="toggleAclTypeOptions()">
                                                <option value="ipv4-standard">IPv4 Standard</option>
                                                <option value="ipv4-extended">IPv4 Extended</option>
                                                <option value="ipv6">IPv6</option>
                                                <option value="mac">MAC</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">ACL Name/Number *</label>
                                            <input type="text" class="form-control" id="acl-name" 
                                                   placeholder="ACL_WEB_SERVERS or 100" required>
                                            <div class="form-text">Name or number (1-99 standard, 100-199 extended)</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" id="acl-description" 
                                                   placeholder="ACL description">
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0"><i class="fas fa-list-ol"></i> ACL Rules</h6>
                                                <button type="button" class="btn btn-sm btn-success" onclick="addAclRule()">
                                                    <i class="fas fa-plus"></i> Add Rule
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="acl-rules-container">
                                                <!-- ACL rules will be added here dynamically -->
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline-primary" onclick="previewAcl()">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button type="button" class="btn btn-nexus" onclick="saveAcl()">
                                    <span id="acl-save-btn-text">Create ACL</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACL Templates Modal -->
                <div class="modal fade" id="aclTemplatesModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">ACL Templates</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-server"></i> Web Server ACL</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Allow HTTP/HTTPS traffic to web servers</p>
                                                <button class="btn btn-outline-primary btn-sm" onclick="applyAclTemplate('web-server')">
                                                    Apply Template
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0"><i class="fas fa-database"></i> Database ACL</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Restrict database access to specific hosts</p>
                                                <button class="btn btn-outline-success btn-sm" onclick="applyAclTemplate('database')">
                                                    Apply Template
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><i class="fas fa-shield-alt"></i> DMZ ACL</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">DMZ security policies</p>
                                                <button class="btn btn-outline-warning btn-sm" onclick="applyAclTemplate('dmz')">
                                                    Apply Template
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-danger text-white">
                                                <h6 class="mb-0"><i class="fas fa-ban"></i> Deny All ACL</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Block all traffic (emergency use)</p>
                                                <button class="btn btn-outline-danger btn-sm" onclick="applyAclTemplate('deny-all')">
                                                    Apply Template
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

                <!-- Apply ACL Modal -->
                <div class="modal fade" id="applyAclModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Apply ACL to Interface</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="apply-acl-form">
                                    <input type="hidden" id="apply-acl-name">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Interface *</label>
                                        <select class="form-select" id="apply-interface" required>
                                            <option value="">-- Select Interface --</option>
                                            <!-- Interfaces will be populated here -->
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Direction *</label>
                                        <select class="form-select" id="apply-direction" required>
                                            <option value="in">Inbound (in)</option>
                                            <option value="out">Outbound (out)</option>
                                        </select>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="apply-log">
                                        <label class="form-check-label" for="apply-log">
                                            Enable logging for this ACL
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="executeApplyAcl()">
                                    Apply ACL
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
        let aclsData = [];
        let filteredAcls = [];
        let interfacesData = [];
        let ruleCounter = 0;
        let ipv6AclsData = [];
        let macAclsData = [];
        let aclSummaryData = {};

        // Page-specific refresh function
        window.pageRefreshFunction = loadAcls;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAcls();
            loadInterfaces();
        });

        function loadAcls() {
            // Fetch and display IPv4 ACLs
            const ipv4Body = document.getElementById('ipv4-acls-tbody');
            ipv4Body.innerHTML = '<tr><td colspan="8" class="text-center"><div class="loading-spinner"></div> Loading IPv4 ACLs...</td></tr>';
            executeCommand('show ip access-lists', function(data) {
                // Parse NX-API CLI or JSON-RPC response for IPv4 ACLs
                let bodyObj;
                if (data.ins_api) {
                    const out = data.ins_api.outputs.output;
                    bodyObj = Array.isArray(out) ? out[0].body : out.body;
                } else if (data.result) {
                    bodyObj = data.result.body;
                } else {
                    bodyObj = {};
                }
                const rows = bodyObj.TABLE_ip_ipv6_mac?.ROW_ip_ipv6_mac || [];
                const arr = Array.isArray(rows) ? rows : [rows];
                aclsData = arr.map(item => {
                    const seq = item.TABLE_seqno?.ROW_seqno;
                    const entries = seq ? (Array.isArray(seq) ? seq : [seq]) : [];
                    return {
                        name: item.acl_name,
                        type: 'ipv4-extended',
                        rules: entries.length,
                        appliedTo: [],
                        direction: null,
                        hitCount: 0,
                        status: 'active',
                        entries
                    };
                });
                filteredAcls = [...aclsData];
                displayIpv4Acls();
                updateAclSummary();
                // Populate raw JSON for testing
                document.getElementById('ipv4-json-raw').textContent = JSON.stringify(data, null, 2);
            });

            // Fetch and display IPv6 ACLs
            const ipv6Body = document.getElementById('ipv6-acls-tbody');
            ipv6Body.innerHTML = '<tr><td colspan="6" class="text-center"><div class="loading-spinner"></div> Loading IPv6 ACLs...</td></tr>';
            executeCommand('show ipv6 access-lists', function(data) {
                // Support both ins_api and JSON-RPC wrappers
                const body = data.ins_api?.outputs?.output?.body || data.result?.body;
                const rows = body.TABLE_ip_ipv6_mac?.ROW_ip_ipv6_mac || [];
                const arr = Array.isArray(rows) ? rows : [rows];
                ipv6AclsData = arr.map(item => {
                    const seq = item.TABLE_seqno?.ROW_seqno;
                    const entries = seq ? (Array.isArray(seq) ? seq : [seq]) : [];
                    return {
                        name: item.acl_name,
                        rules: entries.length,
                        appliedTo: [],
                        direction: null,
                        hitCount: 0,
                        status: 'active',
                        entries
                    };
                });
                displayIpv6Acls();
            });

            // Fetch and display MAC ACLs
            const macBody = document.getElementById('mac-acls-tbody');
            macBody.innerHTML = '<tr><td colspan="6" class="text-center"><div class="loading-spinner"></div> Loading MAC ACLs...</td></tr>';
            executeCommand('show mac access-lists', function(data) {
                // Support both ins_api and JSON-RPC wrappers
                const body = data.ins_api?.outputs?.output?.body || data.result?.body;
                const rows = body.TABLE_ip_ipv6_mac?.ROW_ip_ipv6_mac || [];
                const arr = Array.isArray(rows) ? rows : [rows];
                macAclsData = arr.map(item => {
                    const seq = item.TABLE_seqno?.ROW_seqno;
                    const entries = seq ? (Array.isArray(seq) ? seq : [seq]) : [];
                    return {
                        name: item.acl_name,
                        rules: entries.length,
                        appliedTo: [],
                        direction: null,
                        hitCount: 0,
                        status: 'active',
                        entries
                    };
                });
                displayMacAcls();
            });

            // Refresh object groups
            displayObjectGroups();
        }

        function loadInterfaces() {
            executeCommand('show interface brief', function(data) {
                interfacesData = generateMockInterfaceData();
                populateInterfaceSelects();
            });
        }

        function generateMockAclData() {
            return [
                {
                    name: 'WEB_SERVERS',
                    type: 'ipv4-extended',
                    rules: 5,
                    appliedTo: ['Vlan10'],
                    direction: 'in',
                    hitCount: 1250,
                    status: 'active',
                    description: 'Web server access control'
                },
                {
                    name: '100',
                    type: 'ipv4-extended',
                    rules: 3,
                    appliedTo: ['Ethernet1/1'],
                    direction: 'out',
                    hitCount: 890,
                    status: 'active',
                    description: 'DMZ outbound traffic'
                },
                {
                    name: 'DATABASE_ACCESS',
                    type: 'ipv4-extended',
                    rules: 4,
                    appliedTo: [],
                    direction: null,
                    hitCount: 0,
                    status: 'unused',
                    description: 'Database server protection'
                },
                {
                    name: 'MANAGEMENT_V6',
                    type: 'ipv6',
                    rules: 2,
                    appliedTo: ['Vlan100'],
                    direction: 'in',
                    hitCount: 45,
                    status: 'active',
                    description: 'IPv6 management access'
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
                { interface: 'Ethernet1/2' }
            ];
        }
       
        // Mock data for MAC-based ACLs
        function generateMockMacAclData() {
            return [
                {
                    name: 'MAC_ACL1',
                    type: 'mac',
                    rules: 2,
                    appliedTo: ['Vlan10'],
                    direction: 'in',
                    hitCount: 350,
                    status: 'active',
                    description: 'Allow frames from specific MAC'
                },
                {
                    name: 'MAC_BLOCK',
                    type: 'mac',
                    rules: 1,
                    appliedTo: [],
                    direction: null,
                    hitCount: 0,
                    status: 'unused',
                    description: 'Block all MAC addresses'
                }
            ];
        }

        function displayIpv4Acls() {
            const tbody = document.getElementById('ipv4-acls-tbody');
            tbody.innerHTML = '';

            const ipv4Acls = filteredAcls.filter(acl => acl.type.startsWith('ipv4'));

            ipv4Acls.forEach(acl => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong><a href="#" onclick="viewAclDetails('${acl.name}'); return false;">${acl.name}</a></strong></td>
                    <td>
                        <span class="badge ${getAclTypeBadge(acl.type)}">${getAclTypeLabel(acl.type)}</span>
                    </td>
                    <td>${acl.rules} rules</td>
                    <td>${acl.appliedTo.length > 0 ? acl.appliedTo.join(', ') : '--'}</td>
                    <td>${acl.direction ? acl.direction.toUpperCase() : '--'}</td>
                    <td>${formatNumber(acl.hitCount)}</td>
                    <td>
                        <span class="badge ${getStatusBadge(acl.status)}">${acl.status.toUpperCase()}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editAcl('${acl.name}')"
                                data-bs-toggle="tooltip" title="Edit ACL">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" 
                                onclick="showApplyAclModal('${acl.name}')"
                                data-bs-toggle="tooltip" title="Apply to Interface">
                            <i class="fas fa-link"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="viewAclDetails('${acl.name}')"
                                data-bs-toggle="tooltip" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="deleteAcl('${acl.name}')"
                                data-bs-toggle="tooltip" title="Delete ACL">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            initializeTooltips();
        }

        function displayIpv6Acls() {
            const tbody = document.getElementById('ipv6-acls-tbody');
            tbody.innerHTML = '';

            const ipv6Acls = aclsData.filter(acl => acl.type === 'ipv6');

            if (ipv6Acls.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No IPv6 ACLs configured</td></tr>';
                return;
            }

            ipv6Acls.forEach(acl => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong>${acl.name}</strong></td>
                    <td>${acl.rules} rules</td>
                    <td>${acl.appliedTo.length > 0 ? acl.appliedTo.join(', ') : '--'}</td>
                    <td>${acl.direction ? acl.direction.toUpperCase() : '--'}</td>
                    <td>${formatNumber(acl.hitCount)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editAcl('${acl.name}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="showApplyAclModal('${acl.name}')">
                            <i class="fas fa-link"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAcl('${acl.name}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayMacAcls() {
            const tbody = document.getElementById('mac-acls-tbody');
            tbody.innerHTML = '';
            if (!macAclsData || macAclsData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No MAC ACLs configured</td></tr>';
            } else {
                macAclsData.forEach(acl => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><strong>${acl.name}</strong></td>
                        <td>${acl.rules} rules</td>
                        <td>${acl.appliedTo.length > 0 ? acl.appliedTo.join(', ') : '--'}</td>
                        <td>${acl.direction ? acl.direction.toUpperCase() : '--'}</td>
                        <td>${formatNumber(acl.hitCount)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="editAcl('${acl.name}')"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-success" onclick="showApplyAclModal('${acl.name}')"><i class="fas fa-link"></i></button>
                            <button class="btn btn-sm btn-outline-info" onclick="viewAclDetails('${acl.name}')"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAcl('${acl.name}')"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
            initializeTooltips();
        }

        function displayObjectGroups() {
            const networkTbody = document.getElementById('network-groups-tbody');
            const serviceTbody = document.getElementById('service-groups-tbody');
            
            // Mock object groups
            networkTbody.innerHTML = `
                <tr>
                    <td>WEB_SERVERS</td>
                    <td>3 networks</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>DB_SERVERS</td>
                    <td>2 networks</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;

            serviceTbody.innerHTML = `
                <tr>
                    <td>WEB_SERVICES</td>
                    <td>HTTP, HTTPS</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>DB_SERVICES</td>
                    <td>MySQL, PostgreSQL</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        }

        function updateAclSummary() {
            const total = aclsData.length;
            const applied = aclsData.filter(acl => acl.appliedTo.length > 0).length;
            const unused = total - applied;
            const totalRules = aclsData.reduce((sum, acl) => sum + acl.rules, 0);

            document.getElementById('total-acls').textContent = total;
            document.getElementById('applied-acls').textContent = applied;
            document.getElementById('unused-acls').textContent = unused;
            document.getElementById('total-rules').textContent = totalRules;
        }

        function getAclTypeBadge(type) {
            const badges = {
                'ipv4-standard': 'bg-primary',
                'ipv4-extended': 'bg-success',
                'ipv6': 'bg-info',
                'mac': 'bg-warning'
            };
            return badges[type] || 'bg-secondary';
        }

        function getAclTypeLabel(type) {
            const labels = {
                'ipv4-standard': 'Standard',
                'ipv4-extended': 'Extended',
                'ipv6': 'IPv6',
                'mac': 'MAC'
            };
            return labels[type] || type;
        }

        function getStatusBadge(status) {
            return status === 'active' ? 'bg-success' : 'bg-warning';
        }

        function populateInterfaceSelects() {
            const select = document.getElementById('apply-interface');
            if (select) {
                interfacesData.forEach(intf => {
                    const option = document.createElement('option');
                    option.value = intf.interface;
                    option.textContent = intf.interface;
                    select.appendChild(option);
                });
            }
        }

        function filterIpv4Acls() {
            const typeFilter = document.getElementById('ipv4-acl-filter').value;
            const searchTerm = document.getElementById('ipv4-search').value.toLowerCase();

            filteredAcls = aclsData.filter(acl => {
                const matchesType = !typeFilter || acl.type === typeFilter;
                const matchesSearch = acl.name.toLowerCase().includes(searchTerm) ||
                                    (acl.description && acl.description.toLowerCase().includes(searchTerm));
                
                return matchesType && matchesSearch;
            });

            displayIpv4Acls();
        }

        function showCreateAclModal() {
            document.getElementById('acl-action').value = 'create';
            document.getElementById('aclModalTitle').textContent = 'Create ACL';
            document.getElementById('acl-save-btn-text').textContent = 'Create ACL';
            
            document.getElementById('acl-form').reset();
            document.getElementById('acl-rules-container').innerHTML = '';
            ruleCounter = 0;
            
            addAclRule(); // Add first rule
            
            const modal = new bootstrap.Modal(document.getElementById('aclModal'));
            modal.show();
        }

        function addAclRule() {
            ruleCounter++;
            const container = document.getElementById('acl-rules-container');
            
            const ruleDiv = document.createElement('div');
            ruleDiv.className = 'border rounded p-3 mb-3';
            ruleDiv.id = `rule-${ruleCounter}`;
            
            ruleDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Rule ${ruleCounter}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAclRule(${ruleCounter})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Sequence</label>
                        <input type="number" class="form-control" value="${ruleCounter * 10}" min="1" max="65535">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select class="form-select">
                            <option value="permit">Permit</option>
                            <option value="deny">Deny</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Protocol</label>
                        <select class="form-select">
                            <option value="ip">IP (any)</option>
                            <option value="tcp">TCP</option>
                            <option value="udp">UDP</option>
                            <option value="icmp">ICMP</option>
                            <option value="ospf">OSPF</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Source</label>
                        <input type="text" class="form-control" placeholder="any or 192.168.1.0/24">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Destination</label>
                        <input type="text" class="form-control" placeholder="any or 10.0.0.0/8">
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label class="form-label">Source Port</label>
                        <input type="text" class="form-control" placeholder="any, 80, or range 1024-65535">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dest Port</label>
                        <input type="text" class="form-control" placeholder="any, 443, or range 80-8080">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Log matches</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Established</label>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(ruleDiv);
        }

        function removeAclRule(ruleId) {
            const ruleDiv = document.getElementById(`rule-${ruleId}`);
            if (ruleDiv) {
                ruleDiv.remove();
            }
        }

        function toggleAclTypeOptions() {
            // Handle different ACL type options
            const aclType = document.getElementById('acl-type').value;
            // Add logic to show/hide relevant fields based on ACL type
        }

        function saveAcl() {
            const aclName = document.getElementById('acl-name').value;
            const aclType = document.getElementById('acl-type').value;
            const description = document.getElementById('acl-description').value;

            if (!aclName) {
                showAlert('Please enter an ACL name or number.', 'danger');
                return;
            }

            // Generate ACL configuration commands
            let commands = [];
            // Use NX-OS classic ACL syntax (auto type detection)
            if (aclType.startsWith('ipv4')) {
                commands.push(`ip access-list ${aclName}`);
            } else if (aclType === 'ipv6') {
                commands.push(`ipv6 access-list ${aclName}`);
            } else if (aclType === 'mac') {
                commands.push(`mac access-list ${aclName}`);
            }
            // Add a remark for the description
            if (description) {
                commands.push(`remark ${description}`);
            }
            // Iterate each rule block and build commands
            const ruleDivs = document.querySelectorAll('#acl-rules-container > div');
            ruleDivs.forEach(div => {
                const action = div.querySelector('select:nth-of-type(1)').value;
                const protocol = div.querySelector('select:nth-of-type(2)').value;
                const anyInputs = div.querySelectorAll('input[placeholder^="any"]');
                const src = anyInputs[0]?.value || 'any';
                const dst = anyInputs[1]?.value || 'any';
                const portInputs = div.querySelectorAll('input[placeholder^="any,"]');
                const srcPort = portInputs[0]?.value || 'any';
                const dstPort = portInputs[1]?.value || 'any';
                const checks = div.querySelectorAll('.form-check-input');
                let ruleCmd = `${action} ${protocol} ${src}`;
                if (srcPort !== 'any') ruleCmd += ` ${srcPort}`;
                ruleCmd += ` ${dst}`;
                if (dstPort !== 'any') ruleCmd += ` ${dstPort}`;
                if (checks[0]?.checked) ruleCmd += ' log';
                if (checks[1]?.checked) ruleCmd += ' established';
                commands.push(ruleCmd);
            });
            // Exit ACL configuration context
            commands.push('exit');

            confirmAction(`Create ACL with the following configuration?\n\n${commands.join('\n')}`, function() {
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert('ACL created successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('aclModal')).hide();
                    setTimeout(loadAcls, 2000);
                }, 'cli_conf');
            });
        }

        function editAcl(aclName) {
            showAlert(`Edit ACL ${aclName} - Feature coming soon!`, 'info');
        }

        function deleteAcl(aclName) {
            confirmAction(`Delete ACL ${aclName}?`, function() {
                // Determine ACL type for proper removal command
                let delCmd;
                if (aclsData.find(a => a.name === aclName)) {
                    delCmd = `no ip access-list ${aclName}`;
                } else if (ipv6AclsData.find(a => a.name === aclName)) {
                    delCmd = `no ipv6 access-list ${aclName}`;
                } else if (macAclsData.find(a => a.name === aclName)) {
                    delCmd = `no mac access-list ${aclName}`;
                } else {
                    delCmd = `no ip access-list ${aclName}`;
                }
                executeCommand(delCmd, function(data) {
                    showAlert('ACL deleted successfully', 'success');
                    setTimeout(loadAcls, 2000);
                }, 'cli_conf');
            });
        }

        function showApplyAclModal(aclName) {
            document.getElementById('apply-acl-name').value = aclName;
            const modal = new bootstrap.Modal(document.getElementById('applyAclModal'));
            modal.show();
        }

        function executeApplyAcl() {
            const aclName = document.getElementById('apply-acl-name').value;
            const interface = document.getElementById('apply-interface').value;
            const direction = document.getElementById('apply-direction').value;

            if (!interface) {
                showAlert('Please select an interface.', 'danger');
                return;
            }

            const command = `interface ${interface}\nip access-group ${aclName} ${direction}`;

            confirmAction(`Apply ACL ${aclName} to ${interface} (${direction})?`, function() {
                executeCommand(`configure terminal\n${command}`, function(data) {
                    showAlert('ACL applied successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('applyAclModal')).hide();
                    setTimeout(loadAcls, 2000);
                });
            });
        }

        function viewAclDetails(aclName) {
            // Hide raw JSON panel
            const raw = document.getElementById('ipv4-json-raw');
            raw.style.display = 'none';
            // Find the parsed ACL data
            const acl = aclsData.find(a => a.name === aclName);
            const detailsDiv = document.getElementById('ipv4-acl-details');
            if (!acl) {
                detailsDiv.innerHTML = `<p class="text-danger">ACL ${aclName} not found.</p>`;
                return;
            }
            const entries = acl.entries || [];
            if (entries.length === 0) {
                detailsDiv.innerHTML = `<p>No entries configured for ACL <strong>${aclName}</strong>.</p>`;
                return;
            }
            let html = `<h5>Entries for ACL <strong>${aclName}</strong>:</h5>`;
            html += `<table class="table table-striped table-bordered"><thead><tr>` +
                    `<th>Seq</th><th>Action</th><th>Protocol</th><th>Source</th><th>Destination</th><th>Operator</th><th>Port</th>` +
                `</tr></thead><tbody>`;
            entries.forEach(e => {
                const op = e.dest_port_op || e.src_port_op || '';
                const portVal = e.dest_port1_str || e.dest_port1_num || e.src_port1_str || e.src_port1_num || '';
                const proto = e.proto_str || e.proto;
                html += `<tr>` +
                    `<td>${e.seqno}</td>` +
                    `<td>${e.permitdeny}</td>` +
                    `<td>${proto}</td>` +
                    `<td>${e.src_any}${e.src_ip_prefix ? ' '+e.src_ip_prefix : ''}</td>` +
                    `<td>${e.dest_any}${e.dest_ip_prefix ? ' '+e.dest_ip_prefix : ''}</td>` +
                    `<td>${op}</td>` +
                    `<td>${portVal}</td>` +
                `</tr>`;
            });
            html += `</tbody></table>`;
            detailsDiv.innerHTML = html;
        }

        function previewAcl() {
            showAlert('ACL preview feature coming soon!', 'info');
        }

        function showAclTemplatesModal() {
            const modal = new bootstrap.Modal(document.getElementById('aclTemplatesModal'));
            modal.show();
        }

        function applyAclTemplate(templateType) {
            bootstrap.Modal.getInstance(document.getElementById('aclTemplatesModal')).hide();
            
            setTimeout(() => {
                showCreateAclModal();
                
                // Apply template-specific settings
                switch (templateType) {
                    case 'web-server':
                        document.getElementById('acl-name').value = 'WEB_SERVERS';
                        document.getElementById('acl-description').value = 'Web server access control';
                        break;
                    case 'database':
                        document.getElementById('acl-name').value = 'DATABASE_ACCESS';
                        document.getElementById('acl-description').value = 'Database server protection';
                        break;
                    case 'dmz':
                        document.getElementById('acl-name').value = 'DMZ_POLICY';
                        document.getElementById('acl-description').value = 'DMZ security policies';
                        break;
                    case 'deny-all':
                        document.getElementById('acl-name').value = 'EMERGENCY_BLOCK';
                        document.getElementById('acl-description').value = 'Emergency block all traffic';
                        break;
                }
            }, 500);
        }

        function exportAcls() {
            const aclConfig = aclsData.map(acl => {
                return `! ACL: ${acl.name} (${acl.description || 'No description'})\nip access-list extended ${acl.name}\n permit ip any any\n!`;
            }).join('\n');

            exportConfig(aclConfig, 'acls-config.txt');
        }

        function showCreateObjectGroupModal() {
            showAlert('Create Object Group feature coming soon!', 'info');
        }

        // Add toggle function for IPv4 raw JSON
        function toggleIpv4RawJson() {
            const pre = document.getElementById('ipv4-json-raw');
            // Toggle visibility
            if (pre.style.display === 'block') {
                pre.style.display = 'none';
            } else {
                pre.style.display = 'block';
                // Fetch raw response text for debug
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'cmd=' + encodeURIComponent('show ip access-lists')
                })
                .then(response => response.text())
                .then(text => {
                    pre.textContent = text;
                })
                .catch(err => {
                    pre.textContent = 'Error fetching raw data: ' + err.message;
                });
            }
        }
    </script>
</body>
</html>

