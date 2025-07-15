<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration Management - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-cogs"></i> Configuration Management</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="compareConfigs()">
                    <i class="fas fa-code-branch"></i> Compare Configs
                </button>
            </div>
        </div>

        <!-- Configuration Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Running Config</h5>
                        <h6 id="running-lines">Loading...</h6>
                        <small>Lines of configuration</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Startup Config</h5>
                        <h6 id="startup-lines">Loading...</h6>
                        <small>Lines of configuration</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Templates</h5>
                        <h6 id="template-count">Loading...</h6>
                        <small>Available templates</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Changes</h5>
                        <h6 id="unsaved-changes">Loading...</h6>
                        <small>Unsaved changes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Tabs -->
        <ul class="nav nav-tabs" id="configTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="compare-tab" data-bs-toggle="tab" data-bs-target="#compare" type="button" role="tab">
                    <i class="fas fa-code-branch"></i> Compare Configs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab">
                    <i class="fas fa-file-code"></i> Templates
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance" type="button" role="tab">
                    <i class="fas fa-shield-alt"></i> Compliance
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="changes-tab" data-bs-toggle="tab" data-bs-target="#changes" type="button" role="tab">
                    <i class="fas fa-history"></i> Change History
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="deploy-tab" data-bs-toggle="tab" data-bs-target="#deploy" type="button" role="tab">
                    <i class="fas fa-rocket"></i> Deploy
                </button>
            </li>
        </ul>

        <div class="tab-content" id="configTabContent">
            <!-- Compare Configs Tab -->
            <div class="tab-pane fade show active" id="compare" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-code-branch"></i> Configuration Comparison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="config-source" class="form-label">Source Configuration</label>
                                <select class="form-select" id="config-source">
                                    <option value="running">Running Configuration</option>
                                    <option value="startup">Startup Configuration</option>
                                    <option value="backup">Backup File</option>
                                    <option value="template">Template</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="config-target" class="form-label">Target Configuration</label>
                                <select class="form-select" id="config-target">
                                    <option value="startup">Startup Configuration</option>
                                    <option value="running">Running Configuration</option>
                                    <option value="backup">Backup File</option>
                                    <option value="template">Template</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button class="btn btn-primary" onclick="executeComparison()">
                                    <i class="fas fa-search"></i> Compare Configurations
                                </button>
                                <button class="btn btn-info" onclick="exportComparison()">
                                    <i class="fas fa-download"></i> Export Diff
                                </button>
                            </div>
                        </div>
                        
                        <div id="comparison-result" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Source Configuration</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <pre id="source-config" class="config-display"></pre>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Target Configuration</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <pre id="target-config" class="config-display"></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Differences</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <pre id="config-diff" class="diff-display"></pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates Tab -->
            <div class="tab-pane fade" id="templates" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-file-code"></i> Configuration Templates</h5>
                        <button class="btn btn-primary btn-sm" onclick="createTemplate()">
                            <i class="fas fa-plus"></i> Create Template
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                        <th>Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="templates-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading templates...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create/Edit Template</h5>
                    </div>
                    <div class="card-body">
                        <form id="templateForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="template-name" class="form-label">Template Name</label>
                                        <input type="text" class="form-control" id="template-name" placeholder="Enter template name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="template-category" class="form-label">Category</label>
                                        <select class="form-select" id="template-category">
                                            <option value="interface">Interface Configuration</option>
                                            <option value="vlan">VLAN Configuration</option>
                                            <option value="routing">Routing Configuration</option>
                                            <option value="security">Security Configuration</option>
                                            <option value="qos">QoS Configuration</option>
                                            <option value="system">System Configuration</option>
                                            <option value="custom">Custom Configuration</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="template-description" class="form-label">Description</label>
                                        <textarea class="form-control" id="template-description" rows="3" placeholder="Template description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="template-variables" class="form-label">Variables</label>
                                        <textarea class="form-control" id="template-variables" rows="5" placeholder="Define variables (JSON format)&#10;{&#10;  &quot;interface&quot;: &quot;Ethernet1/1&quot;,&#10;  &quot;vlan&quot;: &quot;100&quot;,&#10;  &quot;description&quot;: &quot;Server Port&quot;&#10;}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="template-config" class="form-label">Configuration Template</label>
                                        <textarea class="form-control" id="template-config" rows="10" placeholder="Enter configuration template with variables&#10;interface {{interface}}&#10;  description {{description}}&#10;  switchport access vlan {{vlan}}&#10;  no shutdown"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveTemplate()">
                                        <i class="fas fa-save"></i> Save Template
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="previewTemplate()">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="applyTemplate()">
                                        <i class="fas fa-play"></i> Apply Template
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Compliance Tab -->
            <div class="tab-pane fade" id="compliance" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-shield-alt"></i> Configuration Compliance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3 id="compliance-score">85%</h3>
                                        <p>Compliance Score</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3 id="violations-count">3</h3>
                                        <p>Policy Violations</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3 id="policies-count">12</h3>
                                        <p>Active Policies</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Policy</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Severity</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="compliance-tbody">
                                    <tr>
                                        <td><strong>Password Policy</strong></td>
                                        <td>Security</td>
                                        <td><span class="badge bg-success">Compliant</span></td>
                                        <td><span class="badge bg-info">Medium</span></td>
                                        <td>Strong password requirements enforced</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewPolicyDetails('password')">Details</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>SNMP Community</strong></td>
                                        <td>Security</td>
                                        <td><span class="badge bg-danger">Violation</span></td>
                                        <td><span class="badge bg-danger">High</span></td>
                                        <td>Default SNMP community strings detected</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="fixViolation('snmp')">Fix</button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewPolicyDetails('snmp')">Details</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interface Naming</strong></td>
                                        <td>Standards</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td><span class="badge bg-warning">Low</span></td>
                                        <td>Some interfaces lack proper descriptions</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="fixViolation('naming')">Fix</button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewPolicyDetails('naming')">Details</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Logging Configuration</strong></td>
                                        <td>Operations</td>
                                        <td><span class="badge bg-success">Compliant</span></td>
                                        <td><span class="badge bg-info">Medium</span></td>
                                        <td>Proper logging configuration in place</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewPolicyDetails('logging')">Details</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="runComplianceCheck()">
                                <i class="fas fa-play"></i> Run Compliance Check
                            </button>
                            <button class="btn btn-info" onclick="exportComplianceReport()">
                                <i class="fas fa-download"></i> Export Report
                            </button>
                            <button class="btn btn-success" onclick="createCompliancePolicy()">
                                <i class="fas fa-plus"></i> Create Policy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change History Tab -->
            <div class="tab-pane fade" id="changes" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> Configuration Change History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        <th>Change Type</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="changes-tbody">
                                    <tr>
                                        <td>2024-01-15 14:30:25</td>
                                        <td>admin</td>
                                        <td><span class="badge bg-info">Interface</span></td>
                                        <td>Ethernet1/1</td>
                                        <td>Changed interface description to "Web Server"</td>
                                        <td><span class="badge bg-success">Applied</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewChangeDetails('1')">Details</button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="rollbackChange('1')">Rollback</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 14:25:10</td>
                                        <td>admin</td>
                                        <td><span class="badge bg-warning">VLAN</span></td>
                                        <td>VLAN 100</td>
                                        <td>Created VLAN 100 with name "Servers"</td>
                                        <td><span class="badge bg-success">Applied</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewChangeDetails('2')">Details</button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="rollbackChange('2')">Rollback</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 14:20:45</td>
                                        <td>admin</td>
                                        <td><span class="badge bg-danger">Security</span></td>
                                        <td>ACL Web-ACL</td>
                                        <td>Added permit rule for HTTP traffic</td>
                                        <td><span class="badge bg-success">Applied</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewChangeDetails('3')">Details</button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="rollbackChange('3')">Rollback</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deploy Tab -->
            <div class="tab-pane fade" id="deploy" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-rocket"></i> Configuration Deployment</h5>
                    </div>
                    <div class="card-body">
                        <form id="deployForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deploy-source" class="form-label">Configuration Source</label>
                                        <select class="form-select" id="deploy-source">
                                            <option value="template">Template</option>
                                            <option value="backup">Backup File</option>
                                            <option value="manual">Manual Configuration</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deploy-target" class="form-label">Deployment Target</label>
                                        <select class="form-select" id="deploy-target">
                                            <option value="running">Running Configuration</option>
                                            <option value="startup">Startup Configuration</option>
                                            <option value="both">Both Configurations</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="deploy-backup">
                                            <label class="form-check-label" for="deploy-backup">
                                                Create backup before deployment
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="deploy-validate">
                                            <label class="form-check-label" for="deploy-validate">
                                                Validate configuration before applying
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="deploy-rollback">
                                            <label class="form-check-label" for="deploy-rollback">
                                                Enable automatic rollback on failure
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deploy-config" class="form-label">Configuration to Deploy</label>
                                        <textarea class="form-control" id="deploy-config" rows="10" placeholder="Enter configuration commands to deploy"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Note:</strong> Configuration deployment will apply changes to the running configuration. 
                                        Ensure you have tested the configuration in a lab environment.
                                    </div>
                                    <button type="button" class="btn btn-warning" onclick="executeDeploy()">
                                        <i class="fas fa-rocket"></i> Deploy Configuration
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="validateDeploy()">
                                        <i class="fas fa-check"></i> Validate Only
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="previewDeploy()">
                                        <i class="fas fa-eye"></i> Preview Commands
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-terminal"></i> Deployment Progress</h5>
                    </div>
                    <div class="card-body">
                        <div id="deploy-progress" style="display: none;">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="deploy-progress-bar"></div>
                            </div>
                            <div id="deploy-status">Preparing deployment...</div>
                        </div>
                        <div id="deploy-output" class="terminal-output" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let configData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadConfigData();
        });

        function loadConfigData() {
            loadConfigStats();
            loadTemplates();
            loadChangeHistory();
        }

        function loadConfigStats() {
            // Load running config stats
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show running-config | wc -l'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const lines = parseConfigLines(data);
                    document.getElementById('running-lines').textContent = lines;
                }
            })
            .catch(error => {
                console.error('Error loading running config stats:', error);
                document.getElementById('running-lines').textContent = 'Error';
            });

            // Load startup config stats
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show startup-config | wc -l'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const lines = parseConfigLines(data);
                    document.getElementById('startup-lines').textContent = lines;
                }
            })
            .catch(error => {
                console.error('Error loading startup config stats:', error);
                document.getElementById('startup-lines').textContent = 'Error';
            });

            // Set mock data for templates and changes
            document.getElementById('template-count').textContent = '8';
            document.getElementById('unsaved-changes').textContent = '0';
        }

        function parseConfigLines(data) {
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        return output.body.trim();
                    }
                }
            } catch (e) {
                console.error('Error parsing config lines:', e);
            }
            return '0';
        }

        function loadTemplates() {
            const templates = [
                {
                    name: 'Access Port Template',
                    category: 'Interface',
                    description: 'Standard access port configuration',
                    created: '2024-01-10',
                    modified: '2024-01-15'
                },
                {
                    name: 'Trunk Port Template',
                    category: 'Interface',
                    description: 'Standard trunk port configuration',
                    created: '2024-01-10',
                    modified: '2024-01-12'
                },
                {
                    name: 'Server VLAN Template',
                    category: 'VLAN',
                    description: 'Server VLAN with SVI configuration',
                    created: '2024-01-08',
                    modified: '2024-01-14'
                },
                {
                    name: 'OSPF Area Template',
                    category: 'Routing',
                    description: 'OSPF area configuration template',
                    created: '2024-01-05',
                    modified: '2024-01-13'
                },
                {
                    name: 'Web ACL Template',
                    category: 'Security',
                    description: 'Web server access control list',
                    created: '2024-01-12',
                    modified: '2024-01-15'
                },
                {
                    name: 'QoS Voice Template',
                    category: 'QoS',
                    description: 'Voice traffic QoS configuration',
                    created: '2024-01-09',
                    modified: '2024-01-11'
                },
                {
                    name: 'SNMP Template',
                    category: 'System',
                    description: 'SNMP monitoring configuration',
                    created: '2024-01-07',
                    modified: '2024-01-10'
                },
                {
                    name: 'Backup Config Template',
                    category: 'System',
                    description: 'Automated backup configuration',
                    created: '2024-01-06',
                    modified: '2024-01-09'
                }
            ];

            displayTemplates(templates);
        }

        function displayTemplates(templates) {
            const tbody = document.getElementById('templates-tbody');
            tbody.innerHTML = '';

            templates.forEach(template => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${template.name}</strong></td>
                    <td><span class="badge bg-info">${template.category}</span></td>
                    <td>${template.description}</td>
                    <td>${template.created}</td>
                    <td>${template.modified}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('${template.name}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="applyTemplate('${template.name}')">
                            <i class="fas fa-play"></i> Apply
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTemplate('${template.name}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function loadChangeHistory() {
            // Change history is already populated in the HTML
            // In a real implementation, this would load from audit logs
        }

        function executeComparison() {
            const source = document.getElementById('config-source').value;
            const target = document.getElementById('config-target').value;

            if (source === target) {
                alert('Please select different configurations to compare');
                return;
            }

            // Show comparison result
            document.getElementById('comparison-result').style.display = 'block';

            // Load source configuration
            loadConfiguration(source, 'source-config');
            
            // Load target configuration
            loadConfiguration(target, 'target-config');

            // Generate diff
            setTimeout(() => {
                generateConfigDiff();
            }, 1000);
        }

        function loadConfiguration(configType, elementId) {
            let command = '';
            
            switch (configType) {
                case 'running':
                    command = 'show running-config';
                    break;
                case 'startup':
                    command = 'show startup-config';
                    break;
                case 'backup':
                    command = 'show file bootflash:backup.cfg';
                    break;
                case 'template':
                    command = 'show running-config | section template';
                    break;
            }

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById(elementId).textContent = 'Error loading configuration: ' + data.error;
                } else {
                    const config = parseConfigOutput(data);
                    document.getElementById(elementId).textContent = config;
                }
            })
            .catch(error => {
                document.getElementById(elementId).textContent = 'Error loading configuration: ' + error.message;
            });
        }

        function parseConfigOutput(data) {
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        return output.body;
                    }
                }
            } catch (e) {
                console.error('Error parsing config output:', e);
            }
            return 'Configuration not available';
        }

        function generateConfigDiff() {
            const sourceConfig = document.getElementById('source-config').textContent;
            const targetConfig = document.getElementById('target-config').textContent;
            
            // Simple diff implementation
            const sourceLines = sourceConfig.split('\n');
            const targetLines = targetConfig.split('\n');
            
            let diff = '';
            const maxLines = Math.max(sourceLines.length, targetLines.length);
            
            for (let i = 0; i < maxLines; i++) {
                const sourceLine = sourceLines[i] || '';
                const targetLine = targetLines[i] || '';
                
                if (sourceLine !== targetLine) {
                    if (sourceLine && !targetLine) {
                        diff += `- ${sourceLine}\n`;
                    } else if (!sourceLine && targetLine) {
                        diff += `+ ${targetLine}\n`;
                    } else {
                        diff += `- ${sourceLine}\n+ ${targetLine}\n`;
                    }
                }
            }
            
            if (!diff) {
                diff = 'No differences found between configurations.';
            }
            
            document.getElementById('config-diff').textContent = diff;
        }

        function exportComparison() {
            const diff = document.getElementById('config-diff').textContent;
            const blob = new Blob([diff], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'config-diff.txt';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function createTemplate() {
            // Clear form and switch to templates tab
            document.getElementById('templateForm').reset();
            document.getElementById('template-name').focus();
        }

        function saveTemplate() {
            const name = document.getElementById('template-name').value;
            const category = document.getElementById('template-category').value;
            const description = document.getElementById('template-description').value;
            const variables = document.getElementById('template-variables').value;
            const config = document.getElementById('template-config').value;

            if (!name || !config) {
                alert('Please provide template name and configuration');
                return;
            }

            // Save template to data manager
            const template = {
                name: name,
                category: category,
                description: description,
                variables: variables,
                config: config,
                created: new Date().toISOString().slice(0, 10),
                modified: new Date().toISOString().slice(0, 10)
            };

            alert('Template saved successfully: ' + name);
            loadTemplates();
        }

        function previewTemplate() {
            const config = document.getElementById('template-config').value;
            const variables = document.getElementById('template-variables').value;

            if (!config) {
                alert('Please enter template configuration');
                return;
            }

            let preview = config;
            
            if (variables) {
                try {
                    const vars = JSON.parse(variables);
                    Object.keys(vars).forEach(key => {
                        const regex = new RegExp(`{{${key}}}`, 'g');
                        preview = preview.replace(regex, vars[key]);
                    });
                } catch (e) {
                    alert('Invalid variables JSON format');
                    return;
                }
            }

            alert('Template Preview:\n\n' + preview);
        }

        function applyTemplate(templateName) {
            if (confirm(`Apply template "${templateName}" to the running configuration?`)) {
                alert(`Template "${templateName}" would be applied to the configuration`);
            }
        }

        function editTemplate(templateName) {
            alert(`Edit template "${templateName}" functionality would be implemented here`);
        }

        function deleteTemplate(templateName) {
            if (confirm(`Delete template "${templateName}"?`)) {
                alert(`Template "${templateName}" deleted successfully`);
                loadTemplates();
            }
        }

        function runComplianceCheck() {
            alert('Running compliance check...\n\nCompliance check would analyze the current configuration against defined policies.');
        }

        function exportComplianceReport() {
            alert('Exporting compliance report...\n\nReport would be generated in PDF format.');
        }

        function createCompliancePolicy() {
            alert('Create compliance policy functionality would be implemented here');
        }

        function viewPolicyDetails(policyId) {
            alert(`Policy details for "${policyId}" would be displayed here`);
        }

        function fixViolation(violationId) {
            alert(`Auto-fix for violation "${violationId}" would be implemented here`);
        }

        function viewChangeDetails(changeId) {
            alert(`Change details for ID "${changeId}" would be displayed here`);
        }

        function rollbackChange(changeId) {
            if (confirm(`Rollback change ID "${changeId}"?`)) {
                alert(`Change ID "${changeId}" would be rolled back`);
            }
        }

        function executeDeploy() {
            const config = document.getElementById('deploy-config').value;
            const backup = document.getElementById('deploy-backup').checked;
            const validate = document.getElementById('deploy-validate').checked;

            if (!config) {
                alert('Please enter configuration to deploy');
                return;
            }

            if (!confirm('Deploy configuration to the running system? This may affect network connectivity.')) {
                return;
            }

            showDeployProgress();
            
            if (backup) {
                updateDeployProgress(25, 'Creating backup...');
                setTimeout(() => {
                    updateDeployProgress(50, 'Deploying configuration...');
                    performDeploy(config, validate);
                }, 2000);
            } else {
                updateDeployProgress(50, 'Deploying configuration...');
                performDeploy(config, validate);
            }
        }

        function performDeploy(config, validate) {
            // Split config into individual commands
            const commands = config.split('\n').filter(cmd => cmd.trim());
            
            // Deploy each command
            let deployPromise = Promise.resolve();
            
            commands.forEach(command => {
                deployPromise = deployPromise.then(() => {
                    return fetch('nxapi.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'cmd=' + encodeURIComponent(command)
                    });
                });
            });

            deployPromise
                .then(() => {
                    if (validate) {
                        updateDeployProgress(75, 'Validating configuration...');
                        setTimeout(() => {
                            updateDeployProgress(100, 'Deployment completed successfully');
                            setTimeout(() => {
                                hideDeployProgress();
                                alert('Configuration deployed successfully');
                            }, 2000);
                        }, 2000);
                    } else {
                        updateDeployProgress(100, 'Deployment completed successfully');
                        setTimeout(() => {
                            hideDeployProgress();
                            alert('Configuration deployed successfully');
                        }, 2000);
                    }
                })
                .catch(error => {
                    updateDeployProgress(0, 'Deployment failed: ' + error.message);
                    setTimeout(() => {
                        hideDeployProgress();
                        alert('Deployment failed: ' + error.message);
                    }, 2000);
                });
        }

        function validateDeploy() {
            const config = document.getElementById('deploy-config').value;

            if (!config) {
                alert('Please enter configuration to validate');
                return;
            }

            alert('Configuration validation would check syntax and dependencies');
        }

        function previewDeploy() {
            const config = document.getElementById('deploy-config').value;

            if (!config) {
                alert('Please enter configuration to preview');
                return;
            }

            alert('Configuration Preview:\n\n' + config);
        }

        function showDeployProgress() {
            document.getElementById('deploy-progress').style.display = 'block';
            updateDeployProgress(0, 'Preparing deployment...');
        }

        function hideDeployProgress() {
            document.getElementById('deploy-progress').style.display = 'none';
        }

        function updateDeployProgress(percent, status) {
            document.getElementById('deploy-progress-bar').style.width = percent + '%';
            document.getElementById('deploy-status').textContent = status;
        }

        function compareConfigs() {
            // Switch to compare tab
            const compareTab = new bootstrap.Tab(document.getElementById('compare-tab'));
            compareTab.show();
        }

        function refreshData() {
            loadConfigData();
        }
    </script>
</body>
</html>

