<?php
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAT Configuration - Cisco Nexus Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        .nat-status-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .nat-rule-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .rule-type-badge {
            font-size: 0.8em;
            padding: 4px 8px;
        }
        .pat-fields {
            display: none;
        }
        .translation-table {
            max-height: 400px;
            overflow-y: auto;
        }
        .stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-exchange-alt"></i> NAT Configuration</h2>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="showTranslations()">
                            <i class="fas fa-table"></i> Show Translations
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="showStatistics()">
                            <i class="fas fa-chart-bar"></i> Show Statistics
                        </button>
                    </div>
                </div>

                <!-- NAT Status Card -->
                <div class="nat-status-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5><i class="fas fa-toggle-on"></i> NAT Status</h5>
                            <p class="mb-0" id="nat-status">Checking...</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-light" onclick="toggleNAT()" id="nat-toggle-btn">
                                <i class="fas fa-power-off"></i> Enable NAT
                            </button>
                        </div>
                    </div>
                </div>

                <!-- NAT Rules Section -->
                <div class="card nat-rule-table">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list"></i> NAT Rules</h5>
                        <button type="button" class="btn btn-primary" onclick="showAddNATRule()">
                            <i class="fas fa-plus"></i> Add NAT Rule
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="nat-rules-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rule ID</th>
                                        <th>Type</th>
                                        <th>Inside Local</th>
                                        <th>Inside Global</th>
                                        <th>Outside IP</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="nat-rules-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-spinner fa-spin"></i> Loading NAT rules...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit NAT Rule Modal -->
    <div class="modal fade" id="natRuleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="natRuleModalTitle">Add NAT Rule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="natRuleForm">
                        <input type="hidden" id="nat-rule-id" value="">
                        <input type="hidden" id="nat-rule-action" value="add">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rule Type *</label>
                                    <select class="form-select" id="nat-rule-type" required onchange="togglePATFields()">
                                        <option value="">Select Type</option>
                                        <option value="static-nat">Static NAT</option>
                                        <option value="static-pat">Static PAT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Protocol</label>
                                    <select class="form-select" id="nat-protocol" disabled>
                                        <option value="tcp">TCP</option>
                                        <option value="udp">UDP</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inside Interface *</label>
                                    <select class="form-select" id="nat-inside-interface" required>
                                        <option value="">Select Interface</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Outside Interface *</label>
                                    <select class="form-select" id="nat-outside-interface" required>
                                        <option value="">Select Interface</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inside Local IP *</label>
                                    <input type="text" class="form-control" id="nat-inside-local-ip" 
                                           placeholder="192.168.1.10" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inside Global IP *</label>
                                    <input type="text" class="form-control" id="nat-inside-global-ip" 
                                           placeholder="203.0.113.10" required>
                                </div>
                            </div>
                        </div>

                        <!-- PAT Fields (initially hidden) -->
                        <div class="row pat-fields" id="pat-fields">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inside Local Port</label>
                                    <input type="number" class="form-control" id="nat-inside-local-port" 
                                           placeholder="80" min="1" max="65535">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inside Global Port</label>
                                    <input type="number" class="form-control" id="nat-inside-global-port" 
                                           placeholder="8080" min="1" max="65535">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Outside IP (Optional)</label>
                                    <input type="text" class="form-control" id="nat-outside-ip" 
                                           placeholder="203.0.113.20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" id="nat-description" 
                                           placeholder="Web server NAT rule">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveNATRule()">Save Rule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Translations Modal -->
    <div class="modal fade" id="translationsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">NAT Translations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive translation-table">
                        <table class="table table-striped" id="translations-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Protocol</th>
                                    <th>Inside Local</th>
                                    <th>Inside Global</th>
                                    <th>Outside Local</th>
                                    <th>Outside Global</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="translations-tbody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-spinner fa-spin"></i> Loading translations...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="refreshTranslations()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Modal -->
    <div class="modal fade" id="statisticsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">NAT Statistics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="nat-stats-container">
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h6><i class="fas fa-exchange-alt"></i> Total Translations</h6>
                                <h3 id="total-translations">-</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h6><i class="fas fa-clock"></i> Active Translations</h6>
                                <h3 id="active-translations">-</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h6><i class="fas fa-arrow-up"></i> Hits</h6>
                                <h3 id="nat-hits">-</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card">
                                <h6><i class="fas fa-arrow-down"></i> Misses</h6>
                                <h3 id="nat-misses">-</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Detailed Statistics</h6>
                        <div class="table-responsive">
                            <table class="table table-sm" id="detailed-stats-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Metric</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody id="detailed-stats-tbody">
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">
                                            <i class="fas fa-spinner fa-spin"></i> Loading statistics...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="refreshStatistics()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        let natRules = [];
        let interfaces = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadNATStatus();
            loadNATRules();
            loadInterfaces();
        });

        function loadNATStatus() {
            executeCommand('show feature nat', function(data) {
                console.log('NAT status response:', data);
                let isEnabled = false;
                
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body) {
                        isEnabled = output.body.includes('enabled') || output.body.includes('Enabled');
                    }
                }
                
                document.getElementById('nat-status').textContent = isEnabled ? 'NAT is Enabled' : 'NAT is Disabled';
                document.getElementById('nat-toggle-btn').innerHTML = isEnabled ? 
                    '<i class="fas fa-power-off"></i> Disable NAT' : 
                    '<i class="fas fa-power-off"></i> Enable NAT';
                document.getElementById('nat-toggle-btn').className = isEnabled ? 
                    'btn btn-warning' : 'btn btn-light';
            });
        }

        function loadNATRules() {
            executeCommand('show running-config | include ip nat', function(data) {
                natRules = parseNATRules(data);
                populateNATRulesTable();
            });
        }

        function loadInterfaces() {
            executeCommand('show interface brief', function(data) {
                interfaces = parseInterfaces(data);
                
                // If no interfaces found, try alternative command
                if (interfaces.length === 0) {
                    console.log('No interfaces found with "show interface brief", trying alternative...');
                    executeCommand('show interface', function(data2) {
                        interfaces = parseInterfaces(data2);
                        populateInterfaceSelects();
                    });
                } else {
                    populateInterfaceSelects();
                }
            });
        }

        function parseNATRules(data) {
            const rules = [];
            // Parse NAT configuration from running config
            // This is a simplified parser - you'll need to enhance it based on actual output
            if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                const output = data.ins_api.outputs.output.body || '';
                const lines = output.split('\n');
                
                let currentRule = null;
                lines.forEach(line => {
                    line = line.trim();
                    if (line.includes('ip nat inside source static')) {
                        if (currentRule) rules.push(currentRule);
                        currentRule = { type: 'static-nat', command: line };
                    } else if (line.includes('ip nat outside source static')) {
                        if (currentRule) rules.push(currentRule);
                        currentRule = { type: 'static-nat', command: line, direction: 'outside' };
                    }
                });
                if (currentRule) rules.push(currentRule);
            }
            return rules;
        }

        function parseInterfaces(data) {
            const interfaceList = [];
            console.log('Interface data response:', data);
            
            if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                const output = data.ins_api.outputs.output;
                
                // Try different response formats
                if (output.body && output.body.TABLE_interface) {
                    let rows = output.body.TABLE_interface.ROW_interface || [];
                    if (!Array.isArray(rows)) rows = [rows];
                    
                    rows.forEach(row => {
                        if (row.interface && (row.interface.startsWith('Ethernet') || row.interface.startsWith('port-channel'))) {
                            interfaceList.push(row.interface);
                        }
                    });
                } else if (output.body) {
                    // Fallback: parse from text output
                    const lines = output.body.split('\n');
                    lines.forEach(line => {
                        const match = line.match(/^(Ethernet\d+\/\d+|port-channel\d+)/);
                        if (match && !interfaceList.includes(match[1])) {
                            interfaceList.push(match[1]);
                        }
                    });
                }
            }
            
            console.log('Parsed interfaces:', interfaceList);
            return interfaceList;
        }

        function populateNATRulesTable() {
            const tbody = document.getElementById('nat-rules-tbody');
            
            if (natRules.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> No NAT rules configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = natRules.map((rule, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><span class="badge bg-primary rule-type-badge">${rule.type}</span></td>
                    <td>${rule.insideLocal || 'N/A'}</td>
                    <td>${rule.insideGlobal || 'N/A'}</td>
                    <td>${rule.outsideIP || 'N/A'}</td>
                    <td>${rule.description || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editNATRule(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNATRule(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function populateInterfaceSelects() {
            const selects = ['nat-inside-interface', 'nat-outside-interface'];
            
            console.log('Populating interface selects with:', interfaces);
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Interface</option>';
                    interfaces.forEach(intf => {
                        const option = document.createElement('option');
                        option.value = intf;
                        option.textContent = intf;
                        select.appendChild(option);
                    });
                    console.log(`Populated ${selectId} with ${interfaces.length} interfaces`);
                } else {
                    console.error(`Select element ${selectId} not found`);
                }
            });
        }

        function toggleNAT() {
            const isEnabled = document.getElementById('nat-status').textContent.includes('Enabled');
            const command = isEnabled ? 'no feature nat' : 'feature nat';
            
            // Disable button during operation
            const btn = document.getElementById('nat-toggle-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            executeCommand(command, function(data) {
                showAlert(`NAT ${isEnabled ? 'disabled' : 'enabled'} successfully`, 'success');
                
                // Wait a moment then refresh status
                setTimeout(() => {
                    loadNATStatus();
                    btn.disabled = false;
                }, 1000);
            }, 'cli_conf');
        }

        function showAddNATRule() {
            document.getElementById('nat-rule-action').value = 'add';
            document.getElementById('natRuleModalTitle').textContent = 'Add NAT Rule';
            document.getElementById('natRuleForm').reset();
            document.getElementById('pat-fields').style.display = 'none';
            document.getElementById('nat-protocol').disabled = true;
            
            const modal = new bootstrap.Modal(document.getElementById('natRuleModal'));
            modal.show();
        }

        function editNATRule(index) {
            const rule = natRules[index];
            document.getElementById('nat-rule-action').value = 'edit';
            document.getElementById('nat-rule-id').value = index;
            document.getElementById('natRuleModalTitle').textContent = 'Edit NAT Rule';
            
            // Populate form fields with rule data
            document.getElementById('nat-rule-type').value = rule.type;
            document.getElementById('nat-inside-interface').value = rule.insideInterface || '';
            document.getElementById('nat-outside-interface').value = rule.outsideInterface || '';
            document.getElementById('nat-inside-local-ip').value = rule.insideLocal || '';
            document.getElementById('nat-inside-global-ip').value = rule.insideGlobal || '';
            document.getElementById('nat-outside-ip').value = rule.outsideIP || '';
            document.getElementById('nat-description').value = rule.description || '';
            
            togglePATFields();
            
            const modal = new bootstrap.Modal(document.getElementById('natRuleModal'));
            modal.show();
        }

        function deleteNATRule(index) {
            if (confirm('Are you sure you want to delete this NAT rule?')) {
                const rule = natRules[index];
                const command = `no ${rule.command}`;
                
                executeCommand(command, function(data) {
                    showAlert('NAT rule deleted successfully', 'success');
                    loadNATRules();
                }, 'cli_conf');
            }
        }

        function togglePATFields() {
            const ruleType = document.getElementById('nat-rule-type').value;
            const patFields = document.getElementById('pat-fields');
            const protocolSelect = document.getElementById('nat-protocol');
            
            if (ruleType === 'static-pat') {
                patFields.style.display = 'block';
                protocolSelect.disabled = false;
            } else {
                patFields.style.display = 'none';
                protocolSelect.disabled = true;
            }
        }

        function saveNATRule() {
            if (!validateNATRuleForm()) {
                return;
            }

            const action = document.getElementById('nat-rule-action').value;
            const ruleType = document.getElementById('nat-rule-type').value;
            const insideInterface = document.getElementById('nat-inside-interface').value;
            const outsideInterface = document.getElementById('nat-outside-interface').value;
            const insideLocalIP = document.getElementById('nat-inside-local-ip').value;
            const insideGlobalIP = document.getElementById('nat-inside-global-ip').value;
            const outsideIP = document.getElementById('nat-outside-ip').value;
            const description = document.getElementById('nat-description').value;

            let commands = [];
            
            // Configure interfaces
            commands.push(`interface ${insideInterface}`);
            commands.push('ip nat inside');
            commands.push('exit');
            
            commands.push(`interface ${outsideInterface}`);
            commands.push('ip nat outside');
            commands.push('exit');

            // Configure NAT rule
            if (ruleType === 'static-nat') {
                commands.push(`ip nat inside source static ${insideLocalIP} ${insideGlobalIP}`);
            } else if (ruleType === 'static-pat') {
                const protocol = document.getElementById('nat-protocol').value;
                const insideLocalPort = document.getElementById('nat-inside-local-port').value;
                const insideGlobalPort = document.getElementById('nat-inside-global-port').value;
                commands.push(`ip nat inside source static ${protocol} ${insideLocalIP} ${insideLocalPort} ${insideGlobalIP} ${insideGlobalPort}`);
            }

            if (outsideIP) {
                commands.push(`ip nat outside source static ${insideGlobalIP} ${outsideIP}`);
            }

            executeCommand(commands.join(' ; '), function(data) {
                showAlert(`NAT rule ${action === 'add' ? 'added' : 'updated'} successfully`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('natRuleModal')).hide();
                loadNATRules();
            }, 'cli_conf');
        }

        function validateNATRuleForm() {
            const requiredFields = ['nat-rule-type', 'nat-inside-interface', 'nat-outside-interface', 
                                  'nat-inside-local-ip', 'nat-inside-global-ip'];
            
            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    showAlert(`Please fill in the ${field.previousElementSibling.textContent.replace('*', '').trim()} field`, 'warning');
                    field.focus();
                    return false;
                }
            }

            // Validate IP addresses
            const insideLocalIP = document.getElementById('nat-inside-local-ip').value;
            const insideGlobalIP = document.getElementById('nat-inside-global-ip').value;
            const outsideIP = document.getElementById('nat-outside-ip').value;

            if (!validateIP(insideLocalIP) || !validateIP(insideGlobalIP)) {
                showAlert('Please enter valid IP addresses', 'warning');
                return false;
            }

            if (outsideIP && !validateIP(outsideIP)) {
                showAlert('Please enter a valid outside IP address', 'warning');
                return false;
            }

            return true;
        }

        function showTranslations() {
            const modal = new bootstrap.Modal(document.getElementById('translationsModal'));
            modal.show();
            loadTranslations();
        }

        function loadTranslations() {
            executeCommand('show ip nat translations', function(data) {
                populateTranslationsTable(data);
            });
        }

        function populateTranslationsTable(data) {
            const tbody = document.getElementById('translations-tbody');
            
            // Parse translations from command output
            // This is a simplified implementation
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-info-circle"></i> No active translations found
                    </td>
                </tr>
            `;
        }

        function refreshTranslations() {
            loadTranslations();
        }

        function showStatistics() {
            const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
            modal.show();
            loadStatistics();
        }

        function loadStatistics() {
            executeCommand('show ip nat statistics', function(data) {
                populateStatistics(data);
            });
        }

        function populateStatistics(data) {
            // Parse statistics from command output
            // This is a simplified implementation
            document.getElementById('total-translations').textContent = '0';
            document.getElementById('active-translations').textContent = '0';
            document.getElementById('nat-hits').textContent = '0';
            document.getElementById('nat-misses').textContent = '0';
        }

        function refreshStatistics() {
            loadStatistics();
        }
    </script>
</body>
</html> 