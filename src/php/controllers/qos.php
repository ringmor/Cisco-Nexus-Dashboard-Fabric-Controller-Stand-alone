<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QoS Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tachometer-alt"></i> Quality of Service (QoS)</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="enableQosGlobally()">
                            <i class="fas fa-power-off"></i> Enable QoS
                        </button>
                        <button class="btn btn-warning" onclick="exportQosConfig()">
                            <i class="fas fa-download"></i> Export Config
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- QoS Status Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="qos-status">-</div>
                            <div class="metric-label">QoS Status</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="active-policies">-</div>
                            <div class="metric-label">Active Policies</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="classified-traffic">-</div>
                            <div class="metric-label">Classified Traffic</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="dropped-packets">-</div>
                            <div class="metric-label">Dropped Packets</div>
                        </div>
                    </div>
                </div>

                <!-- QoS Configuration Tabs -->
                <ul class="nav nav-tabs mb-4" id="qosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="class-maps-tab" data-bs-toggle="tab" data-bs-target="#class-maps" type="button" role="tab">
                            <i class="fas fa-tags"></i> Class Maps
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="policy-maps-tab" data-bs-toggle="tab" data-bs-target="#policy-maps" type="button" role="tab">
                            <i class="fas fa-clipboard-list"></i> Policy Maps
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="service-policies-tab" data-bs-toggle="tab" data-bs-target="#service-policies" type="button" role="tab">
                            <i class="fas fa-cogs"></i> Service Policies
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="queuing-tab" data-bs-toggle="tab" data-bs-target="#queuing" type="button" role="tab">
                            <i class="fas fa-layer-group"></i> Queuing
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="marking-tab" data-bs-toggle="tab" data-bs-target="#marking" type="button" role="tab">
                            <i class="fas fa-marker"></i> Marking
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="statistics-tab" data-bs-toggle="tab" data-bs-target="#statistics" type="button" role="tab">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="qosTabContent">
                    <!-- Class Maps Tab -->
                    <div class="tab-pane fade show active" id="class-maps" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-tags"></i> Class Maps</h5>
                                    <button class="btn btn-success btn-sm" onclick="showCreateClassMapModal()">
                                        <i class="fas fa-plus"></i> Create Class Map
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Class Map Name</th>
                                                <th>Match Type</th>
                                                <th>Match Criteria</th>
                                                <th>Packets Matched</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="class-maps-tbody">
                                            <!-- Class maps will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy Maps Tab -->
                    <div class="tab-pane fade" id="policy-maps" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Policy Maps</h5>
                                    <button class="btn btn-success btn-sm" onclick="showCreatePolicyMapModal()">
                                        <i class="fas fa-plus"></i> Create Policy Map
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Policy Map Name</th>
                                                <th>Classes</th>
                                                <th>Actions</th>
                                                <th>Applied To</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="policy-maps-tbody">
                                            <!-- Policy maps will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Policies Tab -->
                    <div class="tab-pane fade" id="service-policies" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Service Policies</h5>
                                    <button class="btn btn-success btn-sm" onclick="showApplyServicePolicyModal()">
                                        <i class="fas fa-plus"></i> Apply Service Policy
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Interface</th>
                                                <th>Direction</th>
                                                <th>Policy Map</th>
                                                <th>Status</th>
                                                <th>Packets</th>
                                                <th>Bytes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="service-policies-tbody">
                                            <!-- Service policies will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Queuing Tab -->
                    <div class="tab-pane fade" id="queuing" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-layer-group"></i> Queuing Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Queue Scheduling</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Scheduling Algorithm</label>
                                                    <select class="form-select" id="scheduling-algorithm">
                                                        <option value="wrr">Weighted Round Robin (WRR)</option>
                                                        <option value="strict">Strict Priority</option>
                                                        <option value="dwrr">Deficit Weighted Round Robin (DWRR)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Number of Queues</label>
                                                    <select class="form-select" id="num-queues">
                                                        <option value="4">4 Queues</option>
                                                        <option value="8">8 Queues</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyQueuingConfig()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Buffer Management</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Buffer Size (KB)</label>
                                                    <input type="number" class="form-control" id="buffer-size" value="1024" min="256" max="8192">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Drop Policy</label>
                                                    <select class="form-select" id="drop-policy">
                                                        <option value="tail-drop">Tail Drop</option>
                                                        <option value="wred">Weighted Random Early Detection</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyBufferConfig()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Queue Statistics -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Queue Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Queue</th>
                                                        <th>Weight</th>
                                                        <th>Packets</th>
                                                        <th>Bytes</th>
                                                        <th>Drops</th>
                                                        <th>Depth</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="queue-stats-tbody">
                                                    <!-- Queue statistics will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Marking Tab -->
                    <div class="tab-pane fade" id="marking" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-marker"></i> Traffic Marking</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">DSCP Marking</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Traffic Class</label>
                                                    <select class="form-select" id="dscp-traffic-class">
                                                        <option value="">Select Class Map</option>
                                                        <option value="VOICE">VOICE</option>
                                                        <option value="VIDEO">VIDEO</option>
                                                        <option value="DATA">DATA</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">DSCP Value</label>
                                                    <select class="form-select" id="dscp-value">
                                                        <option value="ef">EF (46) - Voice</option>
                                                        <option value="af41">AF41 (34) - Video</option>
                                                        <option value="af31">AF31 (26) - High Data</option>
                                                        <option value="af21">AF21 (18) - Medium Data</option>
                                                        <option value="af11">AF11 (10) - Low Data</option>
                                                        <option value="be">BE (0) - Best Effort</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyDscpMarking()">
                                                    Apply DSCP Marking
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">CoS Marking</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Traffic Class</label>
                                                    <select class="form-select" id="cos-traffic-class">
                                                        <option value="">Select Class Map</option>
                                                        <option value="VOICE">VOICE</option>
                                                        <option value="VIDEO">VIDEO</option>
                                                        <option value="DATA">DATA</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">CoS Value</label>
                                                    <select class="form-select" id="cos-value">
                                                        <option value="7">7 - Network Control</option>
                                                        <option value="6">6 - Internetwork Control</option>
                                                        <option value="5">5 - Voice</option>
                                                        <option value="4">4 - Video</option>
                                                        <option value="3">3 - Call Signaling</option>
                                                        <option value="2">2 - High Data</option>
                                                        <option value="1">1 - Medium Data</option>
                                                        <option value="0">0 - Best Effort</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyCosMarking()">
                                                    Apply CoS Marking
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Marking Statistics -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Marking Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Class Map</th>
                                                        <th>DSCP</th>
                                                        <th>CoS</th>
                                                        <th>Packets Marked</th>
                                                        <th>Bytes Marked</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="marking-stats-tbody">
                                                    <!-- Marking statistics will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Tab -->
                    <div class="tab-pane fade" id="statistics" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> QoS Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Interface</label>
                                            <select class="form-select" id="stats-interface" onchange="loadQosStats()">
                                                <option value="">All Interfaces</option>
                                                <option value="Ethernet1/1">Ethernet1/1</option>
                                                <option value="Ethernet1/2">Ethernet1/2</option>
                                                <option value="Ethernet1/3">Ethernet1/3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Time Range</label>
                                            <select class="form-select" id="stats-timerange" onchange="loadQosStats()">
                                                <option value="1h">Last Hour</option>
                                                <option value="24h">Last 24 Hours</option>
                                                <option value="7d">Last 7 Days</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Interface</th>
                                                <th>Policy Map</th>
                                                <th>Class Map</th>
                                                <th>Packets</th>
                                                <th>Bytes</th>
                                                <th>Rate (bps)</th>
                                                <th>Drops</th>
                                                <th>Drop Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody id="qos-stats-tbody">
                                            <!-- QoS statistics will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Class Map Modal -->
                <div class="modal fade" id="createClassMapModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Class Map</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="class-map-form">
                                    <div class="mb-3">
                                        <label class="form-label">Class Map Name *</label>
                                        <input type="text" class="form-control" id="class-map-name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Match Type</label>
                                        <select class="form-select" id="match-type" onchange="updateMatchOptions()">
                                            <option value="any">Match Any</option>
                                            <option value="all">Match All</option>
                                        </select>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Match Criteria</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="match-dscp">
                                                        <label class="form-check-label" for="match-dscp">
                                                            Match DSCP
                                                        </label>
                                                    </div>
                                                    <select class="form-select mt-2" id="dscp-values" style="display: none;">
                                                        <option value="ef">EF (46)</option>
                                                        <option value="af41">AF41 (34)</option>
                                                        <option value="af31">AF31 (26)</option>
                                                        <option value="af21">AF21 (18)</option>
                                                        <option value="af11">AF11 (10)</option>
                                                        <option value="be">BE (0)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="match-cos">
                                                        <label class="form-check-label" for="match-cos">
                                                            Match CoS
                                                        </label>
                                                    </div>
                                                    <input type="number" class="form-control mt-2" id="cos-values" 
                                                           min="0" max="7" style="display: none;" placeholder="0-7">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="match-acl">
                                                        <label class="form-check-label" for="match-acl">
                                                            Match Access List
                                                        </label>
                                                    </div>
                                                    <input type="text" class="form-control mt-2" id="acl-name" 
                                                           style="display: none;" placeholder="ACL Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="match-protocol">
                                                        <label class="form-check-label" for="match-protocol">
                                                            Match Protocol
                                                        </label>
                                                    </div>
                                                    <select class="form-select mt-2" id="protocol-type" style="display: none;">
                                                        <option value="tcp">TCP</option>
                                                        <option value="udp">UDP</option>
                                                        <option value="icmp">ICMP</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="createClassMap()">
                                    Create Class Map
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Policy Map Modal -->
                <div class="modal fade" id="createPolicyMapModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Policy Map</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="policy-map-form">
                                    <div class="mb-3">
                                        <label class="form-label">Policy Map Name *</label>
                                        <input type="text" class="form-control" id="policy-map-name" required>
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Policy Classes</h6>
                                                <button type="button" class="btn btn-sm btn-success" onclick="addPolicyClass()">
                                                    <i class="fas fa-plus"></i> Add Class
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body" id="policy-classes-container">
                                            <!-- Policy classes will be added here -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="createPolicyMap()">
                                    Create Policy Map
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
        let qosData = {};
        let classMaps = [];
        let policyMaps = [];
        let servicePolicies = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadQosData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadQosData();
            setupQosEventHandlers();
        });

        function loadQosData() {
            // Mock data for demonstration
            qosData = generateMockQosData();
            classMaps = generateMockClassMaps();
            policyMaps = generateMockPolicyMaps();
            servicePolicies = generateMockServicePolicies();
            
            updateQosStatus();
            displayClassMaps();
            displayPolicyMaps();
            displayServicePolicies();
            displayQueueStats();
            displayMarkingStats();
            displayQosStats();
        }

        function generateMockQosData() {
            return {
                enabled: true,
                activePolicies: 3,
                classifiedTraffic: '85%',
                droppedPackets: 1250
            };
        }

        function generateMockClassMaps() {
            return [
                {
                    name: 'VOICE',
                    matchType: 'any',
                    criteria: 'DSCP EF',
                    packetsMatched: 125000
                },
                {
                    name: 'VIDEO',
                    matchType: 'any',
                    criteria: 'DSCP AF41',
                    packetsMatched: 85000
                },
                {
                    name: 'DATA',
                    matchType: 'any',
                    criteria: 'DSCP AF21',
                    packetsMatched: 250000
                }
            ];
        }

        function generateMockPolicyMaps() {
            return [
                {
                    name: 'ENTERPRISE_POLICY',
                    classes: ['VOICE', 'VIDEO', 'DATA'],
                    actions: 'Priority, Shape, Mark',
                    appliedTo: 2,
                    status: 'active'
                },
                {
                    name: 'WAN_POLICY',
                    classes: ['VOICE', 'DATA'],
                    actions: 'Priority, Police',
                    appliedTo: 1,
                    status: 'active'
                }
            ];
        }

        function generateMockServicePolicies() {
            return [
                {
                    interface: 'Ethernet1/1',
                    direction: 'output',
                    policyMap: 'ENTERPRISE_POLICY',
                    status: 'active',
                    packets: 1250000,
                    bytes: '1.2 GB'
                },
                {
                    interface: 'Ethernet1/2',
                    direction: 'input',
                    policyMap: 'WAN_POLICY',
                    status: 'active',
                    packets: 850000,
                    bytes: '850 MB'
                }
            ];
        }

        function updateQosStatus() {
            document.getElementById('qos-status').textContent = qosData.enabled ? 'Enabled' : 'Disabled';
            document.getElementById('active-policies').textContent = qosData.activePolicies;
            document.getElementById('classified-traffic').textContent = qosData.classifiedTraffic;
            document.getElementById('dropped-packets').textContent = qosData.droppedPackets.toLocaleString();
        }

        function displayClassMaps() {
            const tbody = document.getElementById('class-maps-tbody');
            tbody.innerHTML = '';

            classMaps.forEach(classMap => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${classMap.name}</strong></td>
                    <td>
                        <span class="badge ${classMap.matchType === 'any' ? 'bg-info' : 'bg-warning'}">
                            ${classMap.matchType.toUpperCase()}
                        </span>
                    </td>
                    <td>${classMap.criteria}</td>
                    <td>${classMap.packetsMatched.toLocaleString()}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editClassMap('${classMap.name}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteClassMap('${classMap.name}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayPolicyMaps() {
            const tbody = document.getElementById('policy-maps-tbody');
            tbody.innerHTML = '';

            policyMaps.forEach(policyMap => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${policyMap.name}</strong></td>
                    <td>
                        ${policyMap.classes.map(cls => `<span class="badge bg-secondary me-1">${cls}</span>`).join('')}
                    </td>
                    <td>${policyMap.actions}</td>
                    <td>${policyMap.appliedTo} interfaces</td>
                    <td>
                        <span class="badge ${policyMap.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                            ${policyMap.status.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editPolicyMap('${policyMap.name}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePolicyMap('${policyMap.name}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayServicePolicies() {
            const tbody = document.getElementById('service-policies-tbody');
            tbody.innerHTML = '';

            servicePolicies.forEach(policy => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${policy.interface}</strong></td>
                    <td>
                        <span class="badge ${policy.direction === 'input' ? 'bg-primary' : 'bg-success'}">
                            ${policy.direction.toUpperCase()}
                        </span>
                    </td>
                    <td>${policy.policyMap}</td>
                    <td>
                        <span class="badge ${policy.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                            ${policy.status.toUpperCase()}
                        </span>
                    </td>
                    <td>${policy.packets.toLocaleString()}</td>
                    <td>${policy.bytes}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="removeServicePolicy('${policy.interface}', '${policy.direction}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayQueueStats() {
            const tbody = document.getElementById('queue-stats-tbody');
            const mockQueueStats = [
                { queue: 0, weight: 1, packets: 125000, bytes: '125 MB', drops: 0, depth: 0 },
                { queue: 1, weight: 2, packets: 85000, bytes: '85 MB', drops: 15, depth: 2 },
                { queue: 2, weight: 3, packets: 250000, bytes: '250 MB', drops: 125, depth: 5 },
                { queue: 3, weight: 4, packets: 180000, bytes: '180 MB', drops: 0, depth: 1 }
            ];

            tbody.innerHTML = '';
            mockQueueStats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>Queue ${stat.queue}</strong></td>
                    <td>${stat.weight}</td>
                    <td>${stat.packets.toLocaleString()}</td>
                    <td>${stat.bytes}</td>
                    <td>${stat.drops}</td>
                    <td>${stat.depth}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayMarkingStats() {
            const tbody = document.getElementById('marking-stats-tbody');
            const mockMarkingStats = [
                { classMap: 'VOICE', dscp: 'EF', cos: '5', packetsMarked: 125000, bytesMarked: '125 MB' },
                { classMap: 'VIDEO', dscp: 'AF41', cos: '4', packetsMarked: 85000, bytesMarked: '85 MB' },
                { classMap: 'DATA', dscp: 'AF21', cos: '2', packetsMarked: 250000, bytesMarked: '250 MB' }
            ];

            tbody.innerHTML = '';
            mockMarkingStats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${stat.classMap}</strong></td>
                    <td><span class="badge bg-info">${stat.dscp}</span></td>
                    <td><span class="badge bg-warning">${stat.cos}</span></td>
                    <td>${stat.packetsMarked.toLocaleString()}</td>
                    <td>${stat.bytesMarked}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayQosStats() {
            const tbody = document.getElementById('qos-stats-tbody');
            const mockStats = [
                {
                    interface: 'Ethernet1/1',
                    policyMap: 'ENTERPRISE_POLICY',
                    classMap: 'VOICE',
                    packets: 125000,
                    bytes: '125 MB',
                    rate: '10 Mbps',
                    drops: 0,
                    dropRate: '0%'
                },
                {
                    interface: 'Ethernet1/1',
                    policyMap: 'ENTERPRISE_POLICY',
                    classMap: 'VIDEO',
                    packets: 85000,
                    bytes: '85 MB',
                    rate: '8 Mbps',
                    drops: 15,
                    dropRate: '0.02%'
                }
            ];

            tbody.innerHTML = '';
            mockStats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${stat.interface}</td>
                    <td>${stat.policyMap}</td>
                    <td>${stat.classMap}</td>
                    <td>${stat.packets.toLocaleString()}</td>
                    <td>${stat.bytes}</td>
                    <td>${stat.rate}</td>
                    <td>${stat.drops}</td>
                    <td>${stat.dropRate}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function setupQosEventHandlers() {
            // Setup match criteria toggles
            document.getElementById('match-dscp').addEventListener('change', function() {
                document.getElementById('dscp-values').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('match-cos').addEventListener('change', function() {
                document.getElementById('cos-values').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('match-acl').addEventListener('change', function() {
                document.getElementById('acl-name').style.display = this.checked ? 'block' : 'none';
            });

            document.getElementById('match-protocol').addEventListener('change', function() {
                document.getElementById('protocol-type').style.display = this.checked ? 'block' : 'none';
            });
        }

        function enableQosGlobally() {
            confirmAction('Enable QoS globally? This will activate all configured policies.', function() {
                executeCommand('configure terminal\nqos', function(data) {
                    showAlert('QoS enabled globally', 'success');
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function showCreateClassMapModal() {
            const modal = new bootstrap.Modal(document.getElementById('createClassMapModal'));
            modal.show();
        }

        function createClassMap() {
            const name = document.getElementById('class-map-name').value;
            const matchType = document.getElementById('match-type').value;

            if (!name) {
                showAlert('Please enter a class map name.', 'danger');
                return;
            }

            let commands = [`class-map type qos match-${matchType} ${name}`];
            
            // Add match criteria
            if (document.getElementById('match-dscp').checked) {
                const dscpValue = document.getElementById('dscp-values').value;
                commands.push(`match dscp ${dscpValue}`);
            }

            if (document.getElementById('match-cos').checked) {
                const cosValue = document.getElementById('cos-values').value;
                commands.push(`match cos ${cosValue}`);
            }

            if (document.getElementById('match-acl').checked) {
                const aclName = document.getElementById('acl-name').value;
                if (aclName) {
                    commands.push(`match access-group name ${aclName}`);
                }
            }

            if (document.getElementById('match-protocol').checked) {
                const protocol = document.getElementById('protocol-type').value;
                commands.push(`match protocol ${protocol}`);
            }

            confirmAction(`Create class map ${name}?\n\n${commands.join('\n')}`, function() {
                executeCommand(`configure terminal\n${commands.join('\n')}`, function(data) {
                    showAlert(`Class map ${name} created successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('createClassMapModal')).hide();
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function showCreatePolicyMapModal() {
            const modal = new bootstrap.Modal(document.getElementById('createPolicyMapModal'));
            modal.show();
        }

        function addPolicyClass() {
            const container = document.getElementById('policy-classes-container');
            const classDiv = document.createElement('div');
            classDiv.className = 'card mb-3';
            classDiv.innerHTML = `
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Policy Class</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.card').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Class Map</label>
                            <select class="form-select policy-class-map">
                                <option value="">Select Class Map</option>
                                <option value="VOICE">VOICE</option>
                                <option value="VIDEO">VIDEO</option>
                                <option value="DATA">DATA</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Action</label>
                            <select class="form-select policy-action">
                                <option value="priority">Priority</option>
                                <option value="bandwidth">Bandwidth</option>
                                <option value="shape">Shape</option>
                                <option value="police">Police</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Value</label>
                            <input type="text" class="form-control policy-value" placeholder="e.g., 100 mbps">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit</label>
                            <select class="form-select policy-unit">
                                <option value="mbps">Mbps</option>
                                <option value="kbps">Kbps</option>
                                <option value="percent">Percent</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(classDiv);
        }

        function createPolicyMap() {
            const name = document.getElementById('policy-map-name').value;
            
            if (!name) {
                showAlert('Please enter a policy map name.', 'danger');
                return;
            }

            let commands = [`policy-map type qos ${name}`];
            
            // Add policy classes
            const classCards = document.querySelectorAll('#policy-classes-container .card');
            classCards.forEach(card => {
                const classMap = card.querySelector('.policy-class-map').value;
                const action = card.querySelector('.policy-action').value;
                const value = card.querySelector('.policy-value').value;
                const unit = card.querySelector('.policy-unit').value;

                if (classMap && action) {
                    commands.push(`class ${classMap}`);
                    if (value) {
                        commands.push(`${action} ${value} ${unit}`);
                    } else {
                        commands.push(action);
                    }
                }
            });

            confirmAction(`Create policy map ${name}?\n\n${commands.join('\n')}`, function() {
                executeCommand(`configure terminal\n${commands.join('\n')}`, function(data) {
                    showAlert(`Policy map ${name} created successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('createPolicyMapModal')).hide();
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function showApplyServicePolicyModal() {
            showAlert('Apply service policy feature coming soon!', 'info');
        }

        function applyQueuingConfig() {
            showAlert('Queuing configuration applied', 'success');
        }

        function applyBufferConfig() {
            showAlert('Buffer configuration applied', 'success');
        }

        function applyDscpMarking() {
            showAlert('DSCP marking configuration applied', 'success');
        }

        function applyCosMarking() {
            showAlert('CoS marking configuration applied', 'success');
        }

        function loadQosStats() {
            displayQosStats();
        }

        function editClassMap(name) {
            showAlert(`Edit class map ${name} - Feature coming soon!`, 'info');
        }

        function deleteClassMap(name) {
            confirmAction(`Delete class map ${name}?`, function() {
                executeCommand(`configure terminal\nno class-map ${name}`, function(data) {
                    showAlert(`Class map ${name} deleted`, 'success');
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function editPolicyMap(name) {
            showAlert(`Edit policy map ${name} - Feature coming soon!`, 'info');
        }

        function deletePolicyMap(name) {
            confirmAction(`Delete policy map ${name}?`, function() {
                executeCommand(`configure terminal\nno policy-map ${name}`, function(data) {
                    showAlert(`Policy map ${name} deleted`, 'success');
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function removeServicePolicy(interfaceName, direction) {
            confirmAction(`Remove service policy from ${interfaceName} ${direction}?`, function() {
                executeCommand(`configure terminal\ninterface ${interfaceName}\nno service-policy ${direction}`, function(data) {
                    showAlert(`Service policy removed from ${interfaceName}`, 'success');
                    setTimeout(loadQosData, 2000);
                });
            });
        }

        function exportQosConfig() {
            const config = `! QoS Configuration\nqos\n!\nclass-map type qos match-any VOICE\n  match dscp ef\n!\npolicy-map type qos ENTERPRISE_POLICY\n  class VOICE\n    priority\n!`;
            exportConfig(config, 'qos-config.txt');
        }

        function updateMatchOptions() {
            // Update match options based on match type
        }
    </script>
</body>
</html>

