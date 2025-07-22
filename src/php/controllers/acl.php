<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Control Lists - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="../../assets/css/styles.css">
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
                        <button class="btn btn-outline-warning" onclick="debugAclData()" data-bs-toggle="tooltip" title="Debug ACL Data">
                            <i class="fas fa-bug"></i> Debug
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
                        <button class="nav-link disabled" id="ipv6-tab" data-bs-toggle="tab" data-bs-target="#ipv6-acls" type="button" role="tab" disabled>
                            <i class="fas fa-globe"></i> IPv6 ACLs <small class="text-muted">(Not Supported)</small>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" id="mac-tab" data-bs-toggle="tab" data-bs-target="#mac-acls" type="button" role="tab" disabled>
                            <i class="fas fa-ethernet"></i> MAC ACLs <small class="text-muted">(Not Supported)</small>
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
                                            <option value="system">System ACLs</option>
                                            <option value="user">User ACLs</option>
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

    <script src="../../assets/js/common.js"></script>
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
            console.log('Loading ACLs...');
            // Fetch and display IPv4 ACLs
            const ipv4Body = document.getElementById('ipv4-acls-tbody');
            ipv4Body.innerHTML = '<tr><td colspan="8" class="text-center"><div class="loading-spinner"></div> Loading IPv4 ACLs...</td></tr>';
            
            executeCommand('show access-lists expanded', function(data) {
                console.log('ACL response:', data);
                console.log('Response type:', typeof data);
                console.log('Response structure:', JSON.stringify(data, null, 2));
                
                // Handle the result wrapper if present
                let actualData = data;
                if (data && data.result) {
                    actualData = data.result;
                    console.log('Using data.result structure');
                }
                
                let bodyObj;
                if (actualData.ins_api) {
                    const out = actualData.ins_api.outputs.output;
                    bodyObj = Array.isArray(out) ? out[0].body : out.body;
                } else if (actualData.result) {
                    bodyObj = actualData.result.body;
                } else {
                    bodyObj = {};
                }
                
                console.log('Body object:', bodyObj);
                
                let arr = [];
                if (bodyObj && bodyObj.TABLE_ip_ipv6_mac && bodyObj.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac) {
                    const rows = bodyObj.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac;
                    arr = Array.isArray(rows) ? rows : [rows];
                    console.log('Raw ACL rows:', arr);
                }
                
                // Filter to only show IPv4 ACLs (op_ip_ipv6_mac === "ip")
                const ipv4Acls = arr.filter(item => item.op_ip_ipv6_mac === "ip");
                console.log('Filtered IPv4 ACLs:', ipv4Acls);
                
                // If no ACLs found with expanded command, try the regular command as fallback
                if (ipv4Acls.length === 0) {
                    console.log('No ACLs found with expanded command, trying fallback...');
                    executeCommand('show ip access-lists', function(fallbackData) {
                        console.log('Fallback ACL response:', fallbackData);
                        processAclData(fallbackData, 'fallback');
                    }, 'cli_show');
                    return;
                }
                
                processAclData(data, 'expanded');
            }, 'cli_show');

            // Fetch and display IPv6 ACLs
            const ipv6Body = document.getElementById('ipv6-acls-tbody');
            ipv6Body.innerHTML = '<tr><td colspan="6" class="text-center"><div class="loading-spinner"></div> Loading IPv6 ACLs...</td></tr>';
            executeCommand('show ipv6 access-lists', function(data) {
                const body = data.ins_api?.outputs?.output?.body || data.result?.body;
                let arr = [];
                if (body && body.TABLE_ip_ipv6_mac && body.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac) {
                    const rows = body.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac;
                    arr = Array.isArray(rows) ? rows : [rows];
                }
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
                const body = data.ins_api?.outputs?.output?.body || data.result?.body;
                let arr = [];
                if (body && body.TABLE_ip_ipv6_mac && body.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac) {
                    const rows = body.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac;
                    arr = Array.isArray(rows) ? rows : [rows];
                }
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

        // Process ACL data from either expanded or fallback command
        function processAclData(data, source) {
            console.log(`Processing ACL data from ${source} command:`, data);
            
            // Handle the result wrapper if present
            let actualData = data;
            if (data && data.result) {
                actualData = data.result;
                console.log('Using data.result structure');
            }
            
            let bodyObj;
            if (actualData.ins_api) {
                const out = actualData.ins_api.outputs.output;
                bodyObj = Array.isArray(out) ? out[0].body : out.body;
            } else if (actualData.result) {
                bodyObj = actualData.result.body;
            } else {
                bodyObj = {};
            }
            
            console.log('Body object:', bodyObj);
            
            let arr = [];
            if (bodyObj && bodyObj.TABLE_ip_ipv6_mac && bodyObj.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac) {
                const rows = bodyObj.TABLE_ip_ipv6_mac.ROW_ip_ipv6_mac;
                arr = Array.isArray(rows) ? rows : [rows];
                console.log('Raw ACL rows:', arr);
            }
            
            // Filter to only show IPv4 ACLs (op_ip_ipv6_mac === "ip")
            const ipv4Acls = arr.filter(item => item.op_ip_ipv6_mac === "ip");
            console.log('Filtered IPv4 ACLs:', ipv4Acls);
            
            aclsData = ipv4Acls.map(item => {
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
            
            console.log('Processed ACLs data:', aclsData);
            filteredAcls = [...aclsData];
            displayIpv4Acls();
            updateAclSummary();
            // Hide the raw JSON section
            document.getElementById('ipv4-json-raw').style.display = 'none';
            // Show parsed summary area
            showParsedAclSummary(ipv4Acls);
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

            if (ipv4Acls.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No IPv4 ACLs found</td></tr>';
                return;
            }

            ipv4Acls.forEach((acl, idx) => {
                // Check if all entries are remarks
                let allRemarks = false;
                if (acl.entries && acl.entries.length > 0) {
                    allRemarks = acl.entries.every(e => e.remark && !e.permitdeny);
                }
                const rulesCount = acl.entries ? acl.entries.filter(e => e.permitdeny).length : 0;
                let rulesDisplay = rulesCount;
                if (allRemarks) {
                    rulesDisplay = '0 <span class="badge bg-secondary ms-1">remark only</span>';
                } else if (acl.entries && acl.entries.length > 0 && rulesCount === 0) {
                    rulesDisplay = '0';
                }
                
                // Determine ACL type based on name (system ACLs vs user ACLs)
                const isSystemAcl = acl.name.startsWith('copp-system-');
                const aclType = isSystemAcl ? 'System' : 'User';
                const typeBadge = isSystemAcl ? 'bg-warning' : 'bg-success';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong><a href="#" onclick="toggleAclRulesRow(${idx}); return false;">${acl.name}</a></strong></td>
                    <td>
                        <span class="badge ${typeBadge}">${aclType}</span>
                    </td>
                    <td>${rulesDisplay} rules</td>
                    <td>${acl.appliedTo.length > 0 ? acl.appliedTo.join(', ') : '--'}</td>
                    <td>${acl.direction ? acl.direction.toUpperCase() : '--'}</td>
                    <td>${formatNumber(acl.hitCount)}</td>
                    <td>
                        <span class="badge ${getStatusBadge(acl.status)}">${acl.status.toUpperCase()}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editAcl('${acl.name}')" data-bs-toggle="tooltip" title="Edit ACL" ${isSystemAcl ? 'disabled' : ''}><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-success" onclick="showApplyAclModal('${acl.name}')" data-bs-toggle="tooltip" title="Apply to Interface"><i class="fas fa-link"></i></button>
                        <button class="btn btn-sm btn-outline-info" onclick="toggleAclRulesRow(${idx});" data-bs-toggle="tooltip" title="View Rules"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAcl('${acl.name}')" data-bs-toggle="tooltip" title="Delete ACL" ${isSystemAcl ? 'disabled' : ''}><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(row);

                // Expandable row for rules
                const rulesRow = document.createElement('tr');
                rulesRow.className = 'acl-rules-row';
                rulesRow.style.display = 'none';
                rulesRow.innerHTML = `<td colspan="8" id="acl-rules-table-${idx}"></td>`;
                tbody.appendChild(rulesRow);
            });

            initializeTooltips();
        }

        // Toggle rules table for an ACL
        function toggleAclRulesRow(idx) {
            const rulesRow = document.querySelectorAll('.acl-rules-row')[idx];
            if (!rulesRow) return;
            if (rulesRow.style.display === 'none') {
                // Render rules table
                const acl = filteredAcls[idx];
                const isSystemAcl = acl.name.startsWith('copp-system-');
                let html = `<div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Rules for ACL: ${acl.name}</strong>
                    ${!isSystemAcl ? `<button class="btn btn-sm btn-success" onclick="showAddRuleModal('${acl.name}')"><i class="fas fa-plus"></i> Add Rule</button>` : ''}
                </div>`;
                html += '<div class="table-responsive"><table class="table table-bordered table-sm mb-0"><thead><tr>' +
                    '<th>Seq</th><th>Action</th><th>Protocol</th><th>Source</th><th>Src Port</th>' +
                    '<th>Destination</th><th>Dst Port</th><th>ICMP</th><th>Actions</th></tr></thead><tbody>';
                let rules = acl.entries || [];
                let hasRealRule = false;
                rules.forEach(rule => {
                    if (rule.remark) {
                        html += `<tr><td>${rule.seqno || ''}</td><td colspan="7"><em>Remark: ${rule.remark}</em></td><td></td></tr>`;
                    } else {
                        hasRealRule = true;
                        
                        // Build source display
                        let sourceDisplay = rule.src_any || '';
                        if (rule.src_ip_prefix) {
                            sourceDisplay = rule.src_ip_prefix;
                        }
                        
                        // Build destination display
                        let destDisplay = rule.dest_any || '';
                        if (rule.dest_ip_prefix) {
                            destDisplay = rule.dest_ip_prefix;
                        }
                        
                        // Build source port display
                        let srcPortDisplay = '';
                        if (rule.src_port_op && (rule.src_port1_str || rule.src_port1_num)) {
                            srcPortDisplay = `${rule.src_port_op} ${rule.src_port1_str || rule.src_port1_num}`;
                        }
                        
                        // Build destination port display
                        let dstPortDisplay = '';
                        if (rule.dest_port_op && (rule.dest_port1_str || rule.dest_port1_num)) {
                            dstPortDisplay = `${rule.dest_port_op} ${rule.dest_port1_str || rule.dest_port1_num}`;
                        }
                        
                        html += '<tr>' +
                            `<td>${rule.seqno || ''}</td>` +
                            `<td><span class="badge ${rule.permitdeny === 'permit' ? 'bg-success' : 'bg-danger'}">${rule.permitdeny || ''}</span></td>` +
                            `<td>${rule.proto_str || rule.proto || ''}</td>` +
                            `<td>${sourceDisplay}</td>` +
                            `<td>${srcPortDisplay}</td>` +
                            `<td>${destDisplay}</td>` +
                            `<td>${dstPortDisplay}</td>` +
                            `<td>${rule.icmp_str || ''}</td>` +
                            `<td>`;
                        
                        if (!isSystemAcl) {
                            html += `<button class="btn btn-sm btn-outline-primary me-1" onclick="showEditRuleModal('${acl.name}', ${JSON.stringify(rule).replace(/"/g, '&quot;')})"><i class="fas fa-edit"></i></button>` +
                                   `<button class="btn btn-sm btn-outline-danger" onclick="deleteAclRule('${acl.name}', ${rule.seqno})"><i class="fas fa-trash"></i></button>`;
                        } else {
                            html += `<span class="text-muted">System ACL</span>`;
                        }
                        
                        html += `</td></tr>`;
                    }
                });
                if (!hasRealRule && rules.length > 0) {
                    html += `<tr><td colspan="9" class="text-muted">No rules, only remarks defined for this ACL.</td></tr>`;
                }
                if (rules.length === 0) {
                    html += `<tr><td colspan="9" class="text-muted">No rules or remarks defined for this ACL.</td></tr>`;
                }
                html += '</tbody></table></div>';
                document.getElementById(`acl-rules-table-${idx}`).innerHTML = html;
                rulesRow.style.display = '';
            } else {
                rulesRow.style.display = 'none';
            }
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
                let matchesType = true;
                if (typeFilter) {
                    if (typeFilter === 'system') {
                        matchesType = acl.name.startsWith('copp-system-');
                    } else if (typeFilter === 'user') {
                        matchesType = !acl.name.startsWith('copp-system-');
                    } else {
                        matchesType = acl.type === typeFilter;
                    }
                }
                
                const matchesSearch = acl.name.toLowerCase().includes(searchTerm) ||
                                    (acl.description && acl.description.toLowerCase().includes(searchTerm));
                
                return matchesType && matchesSearch;
            });

            displayIpv4Acls();
        }

        function showCreateAclModal() {
            console.log('showCreateAclModal called');
            
            // Check if modal element exists
            const modalElement = document.getElementById('aclModal');
            if (!modalElement) {
                console.error('ACL modal element not found!');
                showAlert('Error: Modal element not found', 'danger');
                return;
            }
            
            document.getElementById('acl-action').value = 'create';
            document.getElementById('aclModalTitle').textContent = 'Create ACL';
            document.getElementById('acl-save-btn-text').textContent = 'Create ACL';
            
            document.getElementById('acl-form').reset();
            document.getElementById('acl-rules-container').innerHTML = '';
            ruleCounter = 0;
            
            addAclRule(); // Add first rule
            
            try {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal should be visible now');
            } catch (error) {
                console.error('Error showing modal:', error);
                showAlert('Error showing modal: ' + error.message, 'danger');
            }
        }

        function addAclRule() {
            console.log('addAclRule called, counter:', ruleCounter);
            ruleCounter++;
            const container = document.getElementById('acl-rules-container');
            
            if (!container) {
                console.error('acl-rules-container not found!');
                return;
            }
            
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
                        <input type="number" class="form-control acl-sequence" value="${ruleCounter * 10}" min="1" max="65535">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select class="form-select acl-action">
                            <option value="permit">Permit</option>
                            <option value="deny">Deny</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Protocol</label>
                        <select class="form-select acl-protocol">
                            <option value="ip">IP (any)</option>
                            <option value="tcp">TCP</option>
                            <option value="udp">UDP</option>
                            <option value="icmp">ICMP</option>
                            <option value="ospf">OSPF</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Source</label>
                        <input type="text" class="form-control acl-source" placeholder="any or 192.168.1.0/24">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Destination</label>
                        <input type="text" class="form-control acl-destination" placeholder="any or 10.0.0.0/8">
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label class="form-label">Source Port</label>
                        <input type="text" class="form-control acl-src-port" placeholder="any, 80, or range 1024-65535">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dest Port</label>
                        <input type="text" class="form-control acl-dst-port" placeholder="any, 443, or range 80-8080">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input acl-log" type="checkbox">
                            <label class="form-check-label">Log matches</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input acl-established" type="checkbox">
                            <label class="form-check-label">Established</label>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(ruleDiv);
            console.log('Rule added successfully, total rules:', ruleCounter);
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

        function isSingleIPv4(ip) {
            // Simple regex for IPv4 address
            return /^\d{1,3}(?:\.\d{1,3}){3}$/.test(ip);
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
            
            // Create the ACL based on type
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
            
            // Get all rule containers and build commands
            const ruleDivs = document.querySelectorAll('#acl-rules-container > div');
            if (ruleDivs.length === 0) {
                // Add a default permit rule if no rules are defined
                commands.push(`permit ip any any`);
            } else {
                ruleDivs.forEach(div => {
                    const sequence = div.querySelector('.acl-sequence').value;
                    const action = div.querySelector('.acl-action').value;
                    const protocol = div.querySelector('.acl-protocol').value;
                    let source = div.querySelector('.acl-source').value || 'any';
                    let destination = div.querySelector('.acl-destination').value || 'any';
                    const srcPort = div.querySelector('.acl-src-port').value || '';
                    const dstPort = div.querySelector('.acl-dst-port').value || '';
                    const logCheckbox = div.querySelector('.acl-log');
                    const establishedCheckbox = div.querySelector('.acl-established');
                    
                    // Prepend 'host' if single IPv4 address
                    if (source !== 'any' && isSingleIPv4(source)) {
                        source = `host ${source}`;
                    }
                    if (destination !== 'any' && isSingleIPv4(destination)) {
                        destination = `host ${destination}`;
                    }
                    
                    // Build the rule command with correct NX-OS syntax
                    let ruleCmd = '';
                    if (sequence) {
                        ruleCmd += `${sequence} `;
                    }
                    ruleCmd += `${action} ${protocol}`;
                    
                    // Handle source
                    ruleCmd += ` ${source}`;
                    if (srcPort && srcPort !== 'any' && srcPort !== '') {
                        ruleCmd += ` eq ${srcPort}`;
                    }
                    // Handle destination
                    ruleCmd += ` ${destination}`;
                    if (dstPort && dstPort !== 'any' && dstPort !== '') {
                        ruleCmd += ` eq ${dstPort}`;
                    }
                    // Add optional parameters
                    if (logCheckbox?.checked) {
                        ruleCmd += ' log';
                    }
                    if (establishedCheckbox?.checked) {
                        ruleCmd += ' established';
                    }
                    
                    commands.push(ruleCmd);
                });
            }
            
            // Exit ACL configuration context
            commands.push('exit');

            const commandString = commands.join(' ; ');
            console.log('Creating ACL with commands:', commandString);

            confirmAction(`Create ACL with the following configuration?\n\n${commands.join('\n')}`, function() {
                console.log('Executing ACL creation command:', commandString);
                executeCommand(`configure terminal ; ${commandString}`, function(data) {
                    console.log('ACL creation response:', data);
                    
                    // Check for CLI errors in the response
                    let hasErrors = false;
                    let errorMessages = [];
                    
                    if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                        const outputs = Array.isArray(data.ins_api.outputs.output) ? 
                            data.ins_api.outputs.output : [data.ins_api.outputs.output];
                        
                        outputs.forEach(output => {
                            if (output.code === '400' || output.clierror) {
                                hasErrors = true;
                                if (output.clierror) {
                                    errorMessages.push(output.clierror);
                                }
                            }
                        });
                    }
                    
                    if (hasErrors) {
                        const errorText = errorMessages.join(', ');
                        console.error('ACL creation failed:', errorText);
                        showAlert(`ACL creation failed: ${errorText}`, 'danger');
                        return;
                    }
                    
                    console.log('ACL created successfully, refreshing data...');
                    showAlert('ACL created successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('aclModal')).hide();
                    
                    // Force refresh after a longer delay to ensure the switch has processed the command
                    setTimeout(() => {
                        console.log('Refreshing ACL data...');
                        loadAcls();
                    }, 3000);
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
                executeCommand(`configure terminal ; ${delCmd}`, function(data) {
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
            const interfaceName = document.getElementById('apply-interface').value;
            const direction = document.getElementById('apply-direction').value;

            if (!interfaceName) {
                showAlert('Please select an interface.', 'danger');
                return;
            }

            const command = `interface ${interfaceName} ; ip access-group ${aclName} ${direction}`;

            confirmAction(`Apply ACL ${aclName} to ${interfaceName} (${direction})?`, function() {
                executeCommand(`configure terminal ; ${command}`, function(data) {
                    showAlert('ACL applied successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('applyAclModal')).hide();
                    setTimeout(loadAcls, 2000);
                }, 'cli_conf');
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
            const aclConfig = [
                ...aclsData.map(acl => {
                    return `! ACL: ${acl.name} (${acl.description || 'No description'})\n` +
                        `ip access-list ${acl.name}\n permit ip any any\n!`;
                }),
                ...ipv6AclsData.map(acl => {
                    return `! ACL: ${acl.name} (IPv6)\n` +
                        `ipv6 access-list ${acl.name}\n permit ipv6 any any\n!`;
                }),
                ...macAclsData.map(acl => {
                    return `! ACL: ${acl.name} (MAC)\n` +
                        `mac access-list ${acl.name}\n permit any any\n!`;
                })
            ].join('\n');

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
                    body: 'cmd=' + encodeURIComponent('show access-lists expanded')
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

        // Add a new function to show parsed ACL summary
        function showParsedAclSummary(aclsArr) {
            let html = '<div class="card mt-4"><div class="card-header bg-info text-white"><strong>Parsed IPv4 ACLs (from show access-lists expanded)</strong></div><div class="card-body">';
            if (!aclsArr || aclsArr.length === 0) {
                html += '<p class="text-muted">No ACLs found.</p>';
            } else {
                aclsArr.forEach(acl => {
                    html += `<div class="mb-3"><strong>${acl.acl_name}</strong> <span class="badge bg-secondary">${acl.op_ip_ipv6_mac || 'ip'}</span><ul class="list-group">`;
                    let rules = acl.TABLE_seqno?.ROW_seqno;
                    if (rules) {
                        if (!Array.isArray(rules)) rules = [rules];
                        rules.forEach(rule => {
                            if (rule.remark) {
                                html += `<li class="list-group-item"><em>Remark: ${rule.remark}</em></li>`;
                            } else {
                                let ruleText = `Seq ${rule.seqno || ''}: <strong>${rule.permitdeny || ''}</strong> ${rule.proto_str || rule.proto || ''}`;
                                
                                // Handle source
                                if (rule.src_any) {
                                    ruleText += ` ${rule.src_any}`;
                                }
                                if (rule.src_ip_prefix) {
                                    ruleText += ` ${rule.src_ip_prefix}`;
                                }
                                if (rule.src_port_op && (rule.src_port1_str || rule.src_port1_num)) {
                                    ruleText += ` ${rule.src_port_op} ${rule.src_port1_str || rule.src_port1_num}`;
                                }
                                
                                // Handle destination
                                if (rule.dest_any) {
                                    ruleText += ` ${rule.dest_any}`;
                                }
                                if (rule.dest_ip_prefix) {
                                    ruleText += ` ${rule.dest_ip_prefix}`;
                                }
                                if (rule.dest_port_op && (rule.dest_port1_str || rule.dest_port1_num)) {
                                    ruleText += ` ${rule.dest_port_op} ${rule.dest_port1_str || rule.dest_port1_num}`;
                                }
                                
                                // Handle ICMP
                                if (rule.icmp_str) {
                                    ruleText += ` icmp: ${rule.icmp_str}`;
                                }
                                
                                html += `<li class="list-group-item">${ruleText}</li>`;
                            }
                        });
                    } else {
                        html += '<li class="list-group-item text-muted">No rules or remarks.</li>';
                    }
                    html += '</ul></div>';
                });
            }
            html += '</div></div>';
            // Insert or update the summary area below the IPv4 ACLs table
            let summaryDiv = document.getElementById('parsed-acl-summary');
            if (!summaryDiv) {
                summaryDiv = document.createElement('div');
                summaryDiv.id = 'parsed-acl-summary';
                const ipv4Tab = document.getElementById('ipv4-acls');
                ipv4Tab.parentNode.insertBefore(summaryDiv, ipv4Tab.nextSibling);
            }
            summaryDiv.innerHTML = html;
        }

        // Graphical ACL display with add/edit/delete
        // This function is no longer needed as rules are rendered directly in the table
        // function displayAclDetailsGraphical(acls) {
        //     const container = document.getElementById('ipv4-acl-details');
        //     if (!acls || acls.length === 0) {
        //         container.innerHTML = '<p>No ACLs found.</p>';
        //         return;
        //     }
        //     let html = '';
        //     acls.forEach(acl => {
        //         html += `<div class="card mb-3"><div class="card-header d-flex justify-content-between align-items-center">` +
        //             `<span><strong>ACL:</strong> ${acl.acl_name}</span>` +
        //             `<button class="btn btn-sm btn-success" onclick="showAddRuleModal('${acl.acl_name}')"><i class="fas fa-plus"></i> Add Rule</button>` +
        //             `</div><div class="card-body p-0">`;
        //         html += '<div class="table-responsive"><table class="table table-bordered table-sm mb-0"><thead><tr>' +
        //             '<th>Seq</th><th>Action</th><th>Protocol</th><th>Source</th><th>Src Port</th>' +
        //             '<th>Destination</th><th>Dst Port</th><th>ICMP</th><th>Actions</th></tr></thead><tbody>';
        //         let rules = acl.TABLE_seqno?.ROW_seqno;
        //         if (rules) {
        //             if (!Array.isArray(rules)) rules = [rules];
        //             rules.forEach(rule => {
        //                 html += '<tr>' +
        //                     `<td>${rule.seqno || ''}</td>` +
        //                     `<td>${rule.permitdeny || ''}</td>` +
        //                     `<td>${rule.proto_str || rule.proto || ''}</td>` +
        //                     `<td>${rule.src_any || ''}</td>` +
        //                     `<td>${rule.src_port1_str || rule.src_port1_num || ''}</td>` +
        //                     `<td>${rule.dest_any || ''}</td>` +
        //                     `<td>${rule.dest_port1_str || rule.dest_port1_num || ''}</td>` +
        //                     `<td>${rule.icmp_str || ''}</td>` +
        //                     `<td>` +
        //                         `<button class="btn btn-sm btn-outline-primary me-1" onclick="showEditRuleModal('${acl.acl_name}', ${JSON.stringify(rule).replace(/"/g, '&quot;')})"><i class="fas fa-edit"></i></button>` +
        //                         `<button class="btn btn-sm btn-outline-danger" onclick="deleteAclRule('${acl.acl_name}', ${rule.seqno})"><i class="fas fa-trash"></i></button>` +
        //                     `</td>` +
        //                 '</tr>';
        //             });
        //         }
        //         html += '</tbody></table></div></div></div>';
        //     });
        //     container.innerHTML = html;
        // }

        // Show Add Rule Modal
        function showAddRuleModal(aclName) {
            showRuleModal('add', aclName, {});
        }
        // Show Edit Rule Modal
        function showEditRuleModal(aclName, rule) {
            showRuleModal('edit', aclName, rule);
        }
        // Show Rule Modal (Add/Edit)
        function showRuleModal(mode, aclName, rule) {
            let modalHtml = `<div class='modal fade' id='ruleModal' tabindex='-1'><div class='modal-dialog'><div class='modal-content'>` +
                `<div class='modal-header'><h5 class='modal-title'>${mode === 'add' ? 'Add' : 'Edit'} Rule for ${aclName}</h5>` +
                `<button type='button' class='btn-close' data-bs-dismiss='modal'></button></div>` +
                `<div class='modal-body'><form id='rule-form'>` +
                `<input type='hidden' id='rule-acl-name' value='${aclName}'>` +
                `<input type='hidden' id='rule-mode' value='${mode}'>` +
                `<input type='hidden' id='rule-seqno' value='${rule.seqno || ''}'>` +
                `<div class='mb-2'><label>Sequence</label><input type='number' class='form-control' id='rule-seq' value='${rule.seqno || ''}' required></div>` +
                `<div class='mb-2'><label>Action</label><select class='form-select' id='rule-action'><option value='permit'${rule.permitdeny==='permit'?' selected':''}>Permit</option><option value='deny'${rule.permitdeny==='deny'?' selected':''}>Deny</option></select></div>` +
                `<div class='mb-2'><label>Protocol</label><input type='text' class='form-control' id='rule-proto' value='${rule.proto_str || rule.proto || ''}'></div>` +
                `<div class='mb-2'><label>Source</label><input type='text' class='form-control' id='rule-src' value='${rule.src_any || ''}'></div>` +
                `<div class='mb-2'><label>Source Port</label><input type='text' class='form-control' id='rule-src-port' value='${rule.src_port1_str || rule.src_port1_num || ''}'></div>` +
                `<div class='mb-2'><label>Destination</label><input type='text' class='form-control' id='rule-dst' value='${rule.dest_any || ''}'></div>` +
                `<div class='mb-2'><label>Dest Port</label><input type='text' class='form-control' id='rule-dst-port' value='${rule.dest_port1_str || rule.dest_port1_num || ''}'></div>` +
                `<div class='mb-2'><label>ICMP Type</label><input type='text' class='form-control' id='rule-icmp' value='${rule.icmp_str || ''}'></div>` +
                `</form></div>` +
                `<div class='modal-footer'><button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>` +
                `<button type='button' class='btn btn-nexus' onclick='saveRule()'>${mode === 'add' ? 'Add' : 'Save'} Rule</button></div>` +
                `</div></div></div>`;
            // Remove any existing modal
            const oldModal = document.getElementById('ruleModal');
            if (oldModal) oldModal.remove();
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            new bootstrap.Modal(document.getElementById('ruleModal')).show();
        }

        // Save Rule (Add/Edit)
        function saveRule() {
            const aclName = document.getElementById('rule-acl-name').value;
            const mode = document.getElementById('rule-mode').value;
            const seqno = document.getElementById('rule-seq').value;
            const action = document.getElementById('rule-action').value;
            const proto = document.getElementById('rule-proto').value;
            let src = document.getElementById('rule-src').value;
            const srcPort = document.getElementById('rule-src-port').value;
            let dst = document.getElementById('rule-dst').value;
            const dstPort = document.getElementById('rule-dst-port').value;
            const icmp = document.getElementById('rule-icmp').value;
            
            // Prepend 'host' if single IPv4 address
            if (src !== 'any' && isSingleIPv4(src)) {
                src = `host ${src}`;
            }
            if (dst !== 'any' && isSingleIPv4(dst)) {
                dst = `host ${dst}`;
            }
            
            // Build the rule command with correct NX-OS syntax
            let ruleCmd = '';
            if (seqno) {
                ruleCmd += `${seqno} `;
            }
            ruleCmd += `${action} ${proto}`;
            // Handle source
            ruleCmd += ` ${src}`;
            if (srcPort && srcPort !== 'any' && srcPort !== '') {
                ruleCmd += ` eq ${srcPort}`;
            }
            // Handle destination
            ruleCmd += ` ${dst}`;
            if (dstPort && dstPort !== 'any' && dstPort !== '') {
                ruleCmd += ` eq ${dstPort}`;
            }
            // Add ICMP type if specified
            if (icmp && icmp !== '') {
                ruleCmd += ` ${icmp}`;
            }
            
            let cmd = `ip access-list ${aclName} ; ${ruleCmd}`;
            if (mode === 'edit') {
                // Remove old rule first (by seqno)
                cmd = `ip access-list ${aclName} ; no ${seqno} ; ${ruleCmd}`;
            }
            
            console.log('Saving rule with command:', cmd);
            
            executeCommand(`configure terminal ; ${cmd} ; exit`, function(data) {
                // Check for CLI errors in the response
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const outputs = Array.isArray(data.ins_api.outputs.output) ? 
                        data.ins_api.outputs.output : [data.ins_api.outputs.output];
                    
                    const hasErrors = outputs.some(output => 
                        output.code === '400' || output.clierror
                    );
                    
                    if (hasErrors) {
                        const errors = outputs.filter(output => output.clierror)
                            .map(output => output.clierror).join(', ');
                        showAlert(`Rule save failed: ${errors}`, 'danger');
                        return;
                    }
                }
                
                showAlert('Rule saved successfully', 'success');
                bootstrap.Modal.getInstance(document.getElementById('ruleModal')).hide();
                setTimeout(loadAcls, 2000);
            }, 'cli_conf');
        }

        // Delete Rule
        function deleteAclRule(aclName, seqno) {
            confirmAction(`Delete rule ${seqno} from ACL ${aclName}?`, function() {
                const cmd = `configure terminal ; ip access-list ${aclName} ; no ${seqno} ; exit`;
                executeCommand(cmd, function(data) {
                    showAlert('Rule deleted successfully', 'success');
                    setTimeout(loadAcls, 2000);
                }, 'cli_conf');
            });
        }

        // Debug function to test ACL data loading
        function debugAclData() {
            console.log('=== ACL Debug Information ===');
            console.log('Current aclsData:', aclsData);
            console.log('Current filteredAcls:', filteredAcls);
            console.log('Current aclSummaryData:', aclSummaryData);
            
            // Test the command directly
            console.log('Testing show access-lists expanded command...');
            executeCommand('show access-lists expanded', function(data) {
                console.log('Raw ACL command response:', data);
                
                // Also test show ip access-lists for comparison
                executeCommand('show ip access-lists', function(data2) {
                    console.log('Raw show ip access-lists response:', data2);
                    
                    // Show both in the UI for comparison
                    const debugDiv = document.createElement('div');
                    debugDiv.className = 'alert alert-info mt-3';
                    debugDiv.innerHTML = `
                        <h6>Debug Information</h6>
                        <p><strong>show access-lists expanded:</strong></p>
                        <pre style="max-height: 200px; overflow-y: auto;">${JSON.stringify(data, null, 2)}</pre>
                        <p><strong>show ip access-lists:</strong></p>
                        <pre style="max-height: 200px; overflow-y: auto;">${JSON.stringify(data2, null, 2)}</pre>
                    `;
                    
                    // Insert at the top of the page
                    const container = document.querySelector('.container-fluid');
                    container.insertBefore(debugDiv, container.firstChild);
                }, 'cli_show');
            }, 'cli_show');
        }

        // Utility function to format numbers with commas
        function formatNumber(num) {
            if (!num || num === '0') return '0';
            return parseInt(num).toLocaleString();
        }
    </script>
</body>
</html>

