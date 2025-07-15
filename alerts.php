<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts & Notifications - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-bell"></i> Alerts & Notifications</h2>
            <div>
                <button class="btn btn-success" onclick="refreshAlerts()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="createAlert()">
                    <i class="fas fa-plus"></i> Create Alert
                </button>
                <button class="btn btn-info" onclick="testNotifications()">
                    <i class="fas fa-test-tube"></i> Test Notifications
                </button>
            </div>
        </div>

        <!-- Alert Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Critical Alerts</h5>
                        <h6 id="critical-count">2</h6>
                        <small>Active critical alerts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Warning Alerts</h5>
                        <h6 id="warning-count">5</h6>
                        <small>Active warning alerts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Info Alerts</h5>
                        <h6 id="info-count">3</h6>
                        <small>Active info alerts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Alert Rules</h5>
                        <h6 id="rules-count">15</h6>
                        <small>Configured alert rules</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Tabs -->
        <ul class="nav nav-tabs" id="alertTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                    <i class="fas fa-exclamation-triangle"></i> Active Alerts
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Alert Rules
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                    <i class="fas fa-history"></i> Alert History
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                    <i class="fas fa-envelope"></i> Notifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="thresholds-tab" data-bs-toggle="tab" data-bs-target="#thresholds" type="button" role="tab">
                    <i class="fas fa-chart-line"></i> Thresholds
                </button>
            </li>
        </ul>

        <div class="tab-content" id="alertTabContent">
            <!-- Active Alerts Tab -->
            <div class="tab-pane fade show active" id="active" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-exclamation-triangle"></i> Active Alerts</h5>
                        <div>
                            <button class="btn btn-warning btn-sm" onclick="acknowledgeAll()">
                                <i class="fas fa-check"></i> Acknowledge All
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="clearAll()">
                                <i class="fas fa-times"></i> Clear All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Severity</th>
                                        <th>Alert</th>
                                        <th>Source</th>
                                        <th>Description</th>
                                        <th>First Seen</th>
                                        <th>Last Seen</th>
                                        <th>Count</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="active-alerts-tbody">
                                    <tr class="table-danger">
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td><strong>Interface Down</strong></td>
                                        <td>Ethernet1/5</td>
                                        <td>Interface Ethernet1/5 is administratively down</td>
                                        <td>2024-01-15 14:25:30</td>
                                        <td>2024-01-15 14:25:30</td>
                                        <td>1</td>
                                        <td><span class="badge bg-warning">New</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert('1')">
                                                <i class="fas fa-check"></i> Ack
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('1')">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td><strong>High CPU Usage</strong></td>
                                        <td>System</td>
                                        <td>CPU utilization exceeded 90% threshold (current: 95%)</td>
                                        <td>2024-01-15 14:20:15</td>
                                        <td>2024-01-15 14:30:45</td>
                                        <td>5</td>
                                        <td><span class="badge bg-danger">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert('2')">
                                                <i class="fas fa-check"></i> Ack
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('2')">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td><strong>High Memory Usage</strong></td>
                                        <td>System</td>
                                        <td>Memory utilization exceeded 80% threshold (current: 85%)</td>
                                        <td>2024-01-15 14:15:20</td>
                                        <td>2024-01-15 14:30:20</td>
                                        <td>3</td>
                                        <td><span class="badge bg-warning">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert('3')">
                                                <i class="fas fa-check"></i> Ack
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('3')">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td><strong>Temperature High</strong></td>
                                        <td>Sensor-1</td>
                                        <td>Temperature sensor 1 reading 65°C (threshold: 60°C)</td>
                                        <td>2024-01-15 14:10:10</td>
                                        <td>2024-01-15 14:29:10</td>
                                        <td>8</td>
                                        <td><span class="badge bg-success">Acknowledged</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('4')">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-info">
                                        <td><span class="badge bg-info">Info</span></td>
                                        <td><strong>OSPF Neighbor Up</strong></td>
                                        <td>OSPF</td>
                                        <td>OSPF neighbor 192.168.1.1 state changed to Full</td>
                                        <td>2024-01-15 14:05:45</td>
                                        <td>2024-01-15 14:05:45</td>
                                        <td>1</td>
                                        <td><span class="badge bg-info">Info</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert('5')">
                                                <i class="fas fa-check"></i> Ack
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('5')">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Rules Tab -->
            <div class="tab-pane fade" id="rules" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-cogs"></i> Alert Rules Configuration</h5>
                        <button class="btn btn-primary btn-sm" onclick="createAlertRule()">
                            <i class="fas fa-plus"></i> Create Rule
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rule Name</th>
                                        <th>Category</th>
                                        <th>Condition</th>
                                        <th>Threshold</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="alert-rules-tbody">
                                    <tr>
                                        <td><strong>CPU High Usage</strong></td>
                                        <td><span class="badge bg-primary">System</span></td>
                                        <td>CPU utilization > threshold</td>
                                        <td>90%</td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td><span class="badge bg-success">Enabled</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('cpu_high')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleRule('cpu_high')">
                                                <i class="fas fa-pause"></i> Disable
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Memory High Usage</strong></td>
                                        <td><span class="badge bg-primary">System</span></td>
                                        <td>Memory utilization > threshold</td>
                                        <td>80%</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td><span class="badge bg-success">Enabled</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('memory_high')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleRule('memory_high')">
                                                <i class="fas fa-pause"></i> Disable
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interface Down</strong></td>
                                        <td><span class="badge bg-info">Interface</span></td>
                                        <td>Interface operational status = down</td>
                                        <td>N/A</td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td><span class="badge bg-success">Enabled</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('interface_down')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleRule('interface_down')">
                                                <i class="fas fa-pause"></i> Disable
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Temperature High</strong></td>
                                        <td><span class="badge bg-warning">Environment</span></td>
                                        <td>Temperature > threshold</td>
                                        <td>60°C</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td><span class="badge bg-success">Enabled</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('temp_high')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleRule('temp_high')">
                                                <i class="fas fa-pause"></i> Disable
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>OSPF Neighbor Down</strong></td>
                                        <td><span class="badge bg-success">Routing</span></td>
                                        <td>OSPF neighbor state != Full</td>
                                        <td>N/A</td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td><span class="badge bg-success">Enabled</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('ospf_neighbor')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleRule('ospf_neighbor')">
                                                <i class="fas fa-pause"></i> Disable
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit Alert Rule Form -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create/Edit Alert Rule</h5>
                    </div>
                    <div class="card-body">
                        <form id="alertRuleForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rule-name" class="form-label">Rule Name</label>
                                        <input type="text" class="form-control" id="rule-name" placeholder="Enter rule name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rule-category" class="form-label">Category</label>
                                        <select class="form-select" id="rule-category">
                                            <option value="system">System</option>
                                            <option value="interface">Interface</option>
                                            <option value="routing">Routing</option>
                                            <option value="environment">Environment</option>
                                            <option value="security">Security</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rule-severity" class="form-label">Severity</label>
                                        <select class="form-select" id="rule-severity">
                                            <option value="critical">Critical</option>
                                            <option value="warning">Warning</option>
                                            <option value="info">Info</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rule-condition" class="form-label">Condition</label>
                                        <textarea class="form-control" id="rule-condition" rows="3" placeholder="Define alert condition (e.g., CPU > 90%)"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rule-threshold" class="form-label">Threshold Value</label>
                                        <input type="text" class="form-control" id="rule-threshold" placeholder="Enter threshold value">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="rule-enabled" checked>
                                            <label class="form-check-label" for="rule-enabled">
                                                Enable rule immediately
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="rule-notifications">
                                            <label class="form-check-label" for="rule-notifications">
                                                Send notifications
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveAlertRule()">
                                        <i class="fas fa-save"></i> Save Rule
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testAlertRule()">
                                        <i class="fas fa-test-tube"></i> Test Rule
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearRuleForm()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Alert History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> Alert History</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="history-filter-severity" class="form-label">Filter by Severity</label>
                                <select class="form-select" id="history-filter-severity">
                                    <option value="">All Severities</option>
                                    <option value="critical">Critical</option>
                                    <option value="warning">Warning</option>
                                    <option value="info">Info</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="history-filter-category" class="form-label">Filter by Category</label>
                                <select class="form-select" id="history-filter-category">
                                    <option value="">All Categories</option>
                                    <option value="system">System</option>
                                    <option value="interface">Interface</option>
                                    <option value="routing">Routing</option>
                                    <option value="environment">Environment</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="history-filter-days" class="form-label">Time Range</label>
                                <select class="form-select" id="history-filter-days">
                                    <option value="1">Last 24 hours</option>
                                    <option value="7">Last 7 days</option>
                                    <option value="30">Last 30 days</option>
                                    <option value="90">Last 90 days</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary" onclick="filterHistory()">
                                        <i class="fas fa-filter"></i> Apply Filter
                                    </button>
                                    <button class="btn btn-info" onclick="exportHistory()">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Severity</th>
                                        <th>Alert</th>
                                        <th>Source</th>
                                        <th>Description</th>
                                        <th>Duration</th>
                                        <th>Resolution</th>
                                    </tr>
                                </thead>
                                <tbody id="alert-history-tbody">
                                    <tr>
                                        <td>2024-01-15 13:45:20</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td>Interface Flapping</td>
                                        <td>Ethernet1/3</td>
                                        <td>Interface Ethernet1/3 flapping detected</td>
                                        <td>15 minutes</td>
                                        <td>Auto-resolved</td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 12:30:15</td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td>Power Supply Failure</td>
                                        <td>PSU-2</td>
                                        <td>Power supply 2 failure detected</td>
                                        <td>2 hours</td>
                                        <td>Manual intervention</td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 11:15:30</td>
                                        <td><span class="badge bg-info">Info</span></td>
                                        <td>BGP Session Up</td>
                                        <td>BGP</td>
                                        <td>BGP session with 192.168.1.2 established</td>
                                        <td>N/A</td>
                                        <td>Informational</td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 10:20:45</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                        <td>High Bandwidth Usage</td>
                                        <td>Ethernet1/1</td>
                                        <td>Interface utilization exceeded 85%</td>
                                        <td>30 minutes</td>
                                        <td>Auto-resolved</td>
                                    </tr>
                                    <tr>
                                        <td>2024-01-15 09:10:10</td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td>Fan Failure</td>
                                        <td>Fan-3</td>
                                        <td>Cooling fan 3 failure detected</td>
                                        <td>45 minutes</td>
                                        <td>Hardware replacement</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope"></i> Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="notificationForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Email Notifications</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="email-enabled" checked>
                                            <label class="form-check-label" for="email-enabled">
                                                Enable email notifications
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email-recipients" class="form-label">Email Recipients</label>
                                        <textarea class="form-control" id="email-recipients" rows="3" placeholder="admin@company.com&#10;network@company.com">admin@company.com
network@company.com
ops@company.com</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email-server" class="form-label">SMTP Server</label>
                                        <input type="text" class="form-control" id="email-server" value="smtp.company.com">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email-port" class="form-label">SMTP Port</label>
                                                <input type="number" class="form-control" id="email-port" value="587">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email-security" class="form-label">Security</label>
                                                <select class="form-select" id="email-security">
                                                    <option value="tls">TLS</option>
                                                    <option value="ssl">SSL</option>
                                                    <option value="none">None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>SNMP Traps</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="snmp-enabled" checked>
                                            <label class="form-check-label" for="snmp-enabled">
                                                Enable SNMP trap notifications
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="snmp-servers" class="form-label">SNMP Trap Servers</label>
                                        <textarea class="form-control" id="snmp-servers" rows="3" placeholder="192.168.1.100:162&#10;192.168.1.101:162">192.168.1.100:162
192.168.1.101:162</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="snmp-community" class="form-label">SNMP Community</label>
                                        <input type="text" class="form-control" id="snmp-community" value="public">
                                    </div>

                                    <h6 class="mt-4">Syslog</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="syslog-enabled" checked>
                                            <label class="form-check-label" for="syslog-enabled">
                                                Enable syslog notifications
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="syslog-server" class="form-label">Syslog Server</label>
                                        <input type="text" class="form-control" id="syslog-server" value="192.168.1.50">
                                    </div>
                                    <div class="mb-3">
                                        <label for="syslog-facility" class="form-label">Facility</label>
                                        <select class="form-select" id="syslog-facility">
                                            <option value="local0">local0</option>
                                            <option value="local1">local1</option>
                                            <option value="local2">local2</option>
                                            <option value="local3">local3</option>
                                            <option value="local4">local4</option>
                                            <option value="local5">local5</option>
                                            <option value="local6">local6</option>
                                            <option value="local7">local7</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <h6>Notification Filters</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify-critical" checked>
                                                <label class="form-check-label" for="notify-critical">
                                                    Critical alerts
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify-warning" checked>
                                                <label class="form-check-label" for="notify-warning">
                                                    Warning alerts
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notify-info">
                                                <label class="form-check-label" for="notify-info">
                                                    Info alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveNotificationSettings()">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testNotifications()">
                                        <i class="fas fa-test-tube"></i> Test Notifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Thresholds Tab -->
            <div class="tab-pane fade" id="thresholds" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> Performance Thresholds</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>System Thresholds</h6>
                                <div class="mb-3">
                                    <label for="cpu-warning" class="form-label">CPU Warning Threshold (%)</label>
                                    <input type="range" class="form-range" id="cpu-warning" min="50" max="95" value="80" oninput="updateThresholdValue('cpu-warning', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>50%</small>
                                        <small id="cpu-warning-value">80%</small>
                                        <small>95%</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="cpu-critical" class="form-label">CPU Critical Threshold (%)</label>
                                    <input type="range" class="form-range" id="cpu-critical" min="70" max="100" value="90" oninput="updateThresholdValue('cpu-critical', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>70%</small>
                                        <small id="cpu-critical-value">90%</small>
                                        <small>100%</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="memory-warning" class="form-label">Memory Warning Threshold (%)</label>
                                    <input type="range" class="form-range" id="memory-warning" min="50" max="95" value="80" oninput="updateThresholdValue('memory-warning', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>50%</small>
                                        <small id="memory-warning-value">80%</small>
                                        <small>95%</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="memory-critical" class="form-label">Memory Critical Threshold (%)</label>
                                    <input type="range" class="form-range" id="memory-critical" min="70" max="100" value="90" oninput="updateThresholdValue('memory-critical', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>70%</small>
                                        <small id="memory-critical-value">90%</small>
                                        <small>100%</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Environmental Thresholds</h6>
                                <div class="mb-3">
                                    <label for="temp-warning" class="form-label">Temperature Warning (°C)</label>
                                    <input type="range" class="form-range" id="temp-warning" min="40" max="80" value="60" oninput="updateThresholdValue('temp-warning', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>40°C</small>
                                        <small id="temp-warning-value">60°C</small>
                                        <small>80°C</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="temp-critical" class="form-label">Temperature Critical (°C)</label>
                                    <input type="range" class="form-range" id="temp-critical" min="60" max="100" value="75" oninput="updateThresholdValue('temp-critical', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>60°C</small>
                                        <small id="temp-critical-value">75°C</small>
                                        <small>100°C</small>
                                    </div>
                                </div>

                                <h6 class="mt-4">Interface Thresholds</h6>
                                <div class="mb-3">
                                    <label for="bandwidth-warning" class="form-label">Bandwidth Warning (%)</label>
                                    <input type="range" class="form-range" id="bandwidth-warning" min="50" max="95" value="85" oninput="updateThresholdValue('bandwidth-warning', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>50%</small>
                                        <small id="bandwidth-warning-value">85%</small>
                                        <small>95%</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="error-rate" class="form-label">Error Rate Threshold (%)</label>
                                    <input type="range" class="form-range" id="error-rate" min="0.1" max="5" step="0.1" value="1" oninput="updateThresholdValue('error-rate', this.value)">
                                    <div class="d-flex justify-content-between">
                                        <small>0.1%</small>
                                        <small id="error-rate-value">1%</small>
                                        <small>5%</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" onclick="saveThresholds()">
                                    <i class="fas fa-save"></i> Save Thresholds
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetThresholds()">
                                    <i class="fas fa-undo"></i> Reset to Defaults
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let alertData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadAlertData();
            startAlertMonitoring();
        });

        function loadAlertData() {
            // Load alert counts and data
            updateAlertCounts();
            loadActiveAlerts();
            loadAlertRules();
            loadAlertHistory();
        }

        function updateAlertCounts() {
            // In a real implementation, these would come from the API
            document.getElementById('critical-count').textContent = '2';
            document.getElementById('warning-count').textContent = '5';
            document.getElementById('info-count').textContent = '3';
            document.getElementById('rules-count').textContent = '15';
        }

        function loadActiveAlerts() {
            // Active alerts are already populated in the HTML
            // In a real implementation, this would load from the alert system
        }

        function loadAlertRules() {
            // Alert rules are already populated in the HTML
            // In a real implementation, this would load from the configuration
        }

        function loadAlertHistory() {
            // Alert history is already populated in the HTML
            // In a real implementation, this would load from the database
        }

        function startAlertMonitoring() {
            // Start real-time alert monitoring
            setInterval(() => {
                checkSystemAlerts();
            }, 30000); // Check every 30 seconds
        }

        function checkSystemAlerts() {
            // Check CPU usage
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show system resources'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const cpuUsage = parseSystemResources(data);
                    if (cpuUsage > 90) {
                        triggerAlert('critical', 'High CPU Usage', 'System', `CPU utilization is ${cpuUsage}%`);
                    } else if (cpuUsage > 80) {
                        triggerAlert('warning', 'High CPU Usage', 'System', `CPU utilization is ${cpuUsage}%`);
                    }
                }
            })
            .catch(error => {
                console.error('Error checking system alerts:', error);
            });

            // Check interface status
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show interface brief'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parseInterfaceStatus(data);
                    interfaces.forEach(intf => {
                        if (intf.status === 'down' && intf.admin === 'up') {
                            triggerAlert('critical', 'Interface Down', intf.name, `Interface ${intf.name} is down`);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error checking interface alerts:', error);
            });
        }

        function parseSystemResources(data) {
            // Parse system resources data and return CPU usage percentage
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    // Extract CPU usage from the output
                    // This is a simplified implementation
                    return Math.floor(Math.random() * 100); // Mock data
                }
            } catch (e) {
                console.error('Error parsing system resources:', e);
            }
            return 0;
        }

        function parseInterfaceStatus(data) {
            // Parse interface status data
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    // Extract interface status from the output
                    // This is a simplified implementation
                    return []; // Mock data
                }
            } catch (e) {
                console.error('Error parsing interface status:', e);
            }
            return [];
        }

        function triggerAlert(severity, alertType, source, description) {
            // Trigger a new alert
            const alert = {
                id: Date.now(),
                severity: severity,
                type: alertType,
                source: source,
                description: description,
                timestamp: new Date().toISOString(),
                status: 'new'
            };

            // Add to active alerts table
            addActiveAlert(alert);

            // Send notifications if enabled
            if (shouldSendNotification(severity)) {
                sendNotification(alert);
            }

            // Update alert counts
            updateAlertCounts();
        }

        function addActiveAlert(alert) {
            const tbody = document.getElementById('active-alerts-tbody');
            const row = document.createElement('tr');
            row.className = `table-${alert.severity === 'critical' ? 'danger' : alert.severity === 'warning' ? 'warning' : 'info'}`;
            row.innerHTML = `
                <td><span class="badge bg-${alert.severity === 'critical' ? 'danger' : alert.severity === 'warning' ? 'warning' : 'info'}">${alert.severity.charAt(0).toUpperCase() + alert.severity.slice(1)}</span></td>
                <td><strong>${alert.type}</strong></td>
                <td>${alert.source}</td>
                <td>${alert.description}</td>
                <td>${new Date(alert.timestamp).toLocaleString()}</td>
                <td>${new Date(alert.timestamp).toLocaleString()}</td>
                <td>1</td>
                <td><span class="badge bg-warning">New</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert('${alert.id}')">
                        <i class="fas fa-check"></i> Ack
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearAlert('${alert.id}')">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
        }

        function shouldSendNotification(severity) {
            // Check notification settings
            if (severity === 'critical') return document.getElementById('notify-critical')?.checked || true;
            if (severity === 'warning') return document.getElementById('notify-warning')?.checked || true;
            if (severity === 'info') return document.getElementById('notify-info')?.checked || false;
            return false;
        }

        function sendNotification(alert) {
            // Send notification via configured methods
            if (document.getElementById('email-enabled')?.checked) {
                sendEmailNotification(alert);
            }
            if (document.getElementById('snmp-enabled')?.checked) {
                sendSNMPTrap(alert);
            }
            if (document.getElementById('syslog-enabled')?.checked) {
                sendSyslogMessage(alert);
            }
        }

        function sendEmailNotification(alert) {
            // Send email notification
            console.log('Sending email notification for alert:', alert);
        }

        function sendSNMPTrap(alert) {
            // Send SNMP trap
            console.log('Sending SNMP trap for alert:', alert);
        }

        function sendSyslogMessage(alert) {
            // Send syslog message
            console.log('Sending syslog message for alert:', alert);
        }

        function acknowledgeAlert(alertId) {
            // Acknowledge alert
            const row = event.target.closest('tr');
            const statusCell = row.cells[7];
            statusCell.innerHTML = '<span class="badge bg-success">Acknowledged</span>';
            
            // Remove acknowledge button
            const ackButton = row.querySelector('.btn-outline-success');
            if (ackButton) {
                ackButton.remove();
            }
        }

        function clearAlert(alertId) {
            if (confirm('Clear this alert?')) {
                const row = event.target.closest('tr');
                row.remove();
                updateAlertCounts();
            }
        }

        function acknowledgeAll() {
            if (confirm('Acknowledge all active alerts?')) {
                const rows = document.querySelectorAll('#active-alerts-tbody tr');
                rows.forEach(row => {
                    const statusCell = row.cells[7];
                    if (statusCell.textContent.includes('New') || statusCell.textContent.includes('Active')) {
                        statusCell.innerHTML = '<span class="badge bg-success">Acknowledged</span>';
                        const ackButton = row.querySelector('.btn-outline-success');
                        if (ackButton) {
                            ackButton.remove();
                        }
                    }
                });
            }
        }

        function clearAll() {
            if (confirm('Clear all active alerts? This action cannot be undone.')) {
                document.getElementById('active-alerts-tbody').innerHTML = '';
                updateAlertCounts();
            }
        }

        function createAlert() {
            // Switch to rules tab and focus on form
            const rulesTab = new bootstrap.Tab(document.getElementById('rules-tab'));
            rulesTab.show();
            document.getElementById('rule-name').focus();
        }

        function createAlertRule() {
            clearRuleForm();
            document.getElementById('rule-name').focus();
        }

        function saveAlertRule() {
            const name = document.getElementById('rule-name').value;
            const category = document.getElementById('rule-category').value;
            const condition = document.getElementById('rule-condition').value;
            const threshold = document.getElementById('rule-threshold').value;
            const severity = document.getElementById('rule-severity').value;
            const enabled = document.getElementById('rule-enabled').checked;

            if (!name || !condition) {
                alert('Please provide rule name and condition');
                return;
            }

            alert(`Alert rule "${name}" saved successfully`);
            clearRuleForm();
            loadAlertRules();
        }

        function testAlertRule() {
            const name = document.getElementById('rule-name').value;
            if (!name) {
                alert('Please provide rule name to test');
                return;
            }
            alert(`Testing alert rule "${name}"...\n\nRule test would evaluate the condition and trigger a test alert if conditions are met.`);
        }

        function clearRuleForm() {
            document.getElementById('alertRuleForm').reset();
            document.getElementById('rule-enabled').checked = true;
        }

        function editRule(ruleId) {
            alert(`Edit rule "${ruleId}" functionality would be implemented here`);
        }

        function toggleRule(ruleId) {
            alert(`Toggle rule "${ruleId}" functionality would be implemented here`);
        }

        function filterHistory() {
            const severity = document.getElementById('history-filter-severity').value;
            const category = document.getElementById('history-filter-category').value;
            const days = document.getElementById('history-filter-days').value;

            alert(`Filtering alert history:\nSeverity: ${severity || 'All'}\nCategory: ${category || 'All'}\nTime Range: Last ${days} days`);
        }

        function exportHistory() {
            alert('Exporting alert history to CSV file...');
        }

        function saveNotificationSettings() {
            alert('Notification settings saved successfully');
        }

        function testNotifications() {
            alert('Sending test notifications...\n\nTest email, SNMP trap, and syslog message would be sent to verify configuration.');
        }

        function updateThresholdValue(elementId, value) {
            document.getElementById(elementId + '-value').textContent = value + (elementId.includes('temp') ? '°C' : '%');
        }

        function saveThresholds() {
            alert('Performance thresholds saved successfully');
        }

        function resetThresholds() {
            if (confirm('Reset all thresholds to default values?')) {
                // Reset all sliders to default values
                document.getElementById('cpu-warning').value = 80;
                document.getElementById('cpu-critical').value = 90;
                document.getElementById('memory-warning').value = 80;
                document.getElementById('memory-critical').value = 90;
                document.getElementById('temp-warning').value = 60;
                document.getElementById('temp-critical').value = 75;
                document.getElementById('bandwidth-warning').value = 85;
                document.getElementById('error-rate').value = 1;

                // Update displayed values
                updateThresholdValue('cpu-warning', 80);
                updateThresholdValue('cpu-critical', 90);
                updateThresholdValue('memory-warning', 80);
                updateThresholdValue('memory-critical', 90);
                updateThresholdValue('temp-warning', 60);
                updateThresholdValue('temp-critical', 75);
                updateThresholdValue('bandwidth-warning', 85);
                updateThresholdValue('error-rate', 1);

                alert('Thresholds reset to default values');
            }
        }

        function refreshAlerts() {
            loadAlertData();
        }
    </script>
</body>
</html>

