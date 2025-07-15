<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAA Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user-shield"></i> AAA Configuration</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="testAuthentication()">
                            <i class="fas fa-vial"></i> Test Auth
                        </button>
                        <button class="btn btn-warning" onclick="exportAaaConfig()">
                            <i class="fas fa-download"></i> Export Config
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- AAA Status Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="auth-status">-</div>
                            <div class="metric-label">Authentication</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="authz-status">-</div>
                            <div class="metric-label">Authorization</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="acct-status">-</div>
                            <div class="metric-label">Accounting</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="active-sessions">-</div>
                            <div class="metric-label">Active Sessions</div>
                        </div>
                    </div>
                </div>

                <!-- AAA Configuration Tabs -->
                <ul class="nav nav-tabs mb-4" id="aaaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="authentication-tab" data-bs-toggle="tab" data-bs-target="#authentication" type="button" role="tab">
                            <i class="fas fa-key"></i> Authentication
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="authorization-tab" data-bs-toggle="tab" data-bs-target="#authorization" type="button" role="tab">
                            <i class="fas fa-user-check"></i> Authorization
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accounting-tab" data-bs-toggle="tab" data-bs-target="#accounting" type="button" role="tab">
                            <i class="fas fa-clipboard-list"></i> Accounting
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="radius-tab" data-bs-toggle="tab" data-bs-target="#radius" type="button" role="tab">
                            <i class="fas fa-server"></i> RADIUS
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tacacs-tab" data-bs-toggle="tab" data-bs-target="#tacacs" type="button" role="tab">
                            <i class="fas fa-network-wired"></i> TACACS+
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="local-users-tab" data-bs-toggle="tab" data-bs-target="#local-users" type="button" role="tab">
                            <i class="fas fa-users"></i> Local Users
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="aaaTabContent">
                    <!-- Authentication Tab -->
                    <div class="tab-pane fade show active" id="authentication" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-key"></i> Authentication Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Console Authentication</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="console-auth-enable">
                                                        <label class="form-check-label" for="console-auth-enable">
                                                            Enable Console Authentication
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authentication Method</label>
                                                    <select class="form-select" id="console-auth-method">
                                                        <option value="local">Local</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="radius-local">RADIUS then Local</option>
                                                        <option value="tacacs-local">TACACS+ then Local</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyConsoleAuth()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">SSH Authentication</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="ssh-auth-enable" checked>
                                                        <label class="form-check-label" for="ssh-auth-enable">
                                                            Enable SSH Authentication
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authentication Method</label>
                                                    <select class="form-select" id="ssh-auth-method">
                                                        <option value="local">Local</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="radius-local">RADIUS then Local</option>
                                                        <option value="tacacs-local">TACACS+ then Local</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-success btn-sm" onclick="applySshAuth()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">HTTP/HTTPS Authentication</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="http-auth-enable">
                                                        <label class="form-check-label" for="http-auth-enable">
                                                            Enable HTTP Authentication
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authentication Method</label>
                                                    <select class="form-select" id="http-auth-method">
                                                        <option value="local">Local</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="tacacs">TACACS+</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-info btn-sm" onclick="applyHttpAuth()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">Enable Authentication</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="enable-auth-enable">
                                                        <label class="form-check-label" for="enable-auth-enable">
                                                            Enable Privilege Authentication
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authentication Method</label>
                                                    <select class="form-select" id="enable-auth-method">
                                                        <option value="local">Local</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="tacacs">TACACS+</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-warning btn-sm" onclick="applyEnableAuth()">
                                                    Apply Configuration
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Authorization Tab -->
                    <div class="tab-pane fade" id="authorization" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user-check"></i> Authorization Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Command Authorization</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="cmd-authz-enable">
                                                        <label class="form-check-label" for="cmd-authz-enable">
                                                            Enable Command Authorization
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authorization Method</label>
                                                    <select class="form-select" id="cmd-authz-method">
                                                        <option value="local">Local</option>
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="tacacs-local">TACACS+ then Local</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Privilege Level</label>
                                                    <select class="form-select" id="cmd-authz-level">
                                                        <option value="0">Level 0 (User EXEC)</option>
                                                        <option value="1">Level 1 (User EXEC)</option>
                                                        <option value="15">Level 15 (Privileged EXEC)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Configuration Authorization</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="config-authz-enable">
                                                        <label class="form-check-label" for="config-authz-enable">
                                                            Enable Configuration Authorization
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Authorization Method</label>
                                                    <select class="form-select" id="config-authz-method">
                                                        <option value="local">Local</option>
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="tacacs-local">TACACS+ then Local</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary" onclick="applyAuthorizationConfig()">
                                        <i class="fas fa-save"></i> Apply Authorization Configuration
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accounting Tab -->
                    <div class="tab-pane fade" id="accounting" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Accounting Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Command Accounting</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="cmd-acct-enable">
                                                        <label class="form-check-label" for="cmd-acct-enable">
                                                            Enable Command Accounting
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Accounting Method</label>
                                                    <select class="form-select" id="cmd-acct-method">
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="local">Local</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Privilege Level</label>
                                                    <select class="form-select" id="cmd-acct-level">
                                                        <option value="0">Level 0</option>
                                                        <option value="1">Level 1</option>
                                                        <option value="15">Level 15</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Session Accounting</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="session-acct-enable">
                                                        <label class="form-check-label" for="session-acct-enable">
                                                            Enable Session Accounting
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Accounting Method</label>
                                                    <select class="form-select" id="session-acct-method">
                                                        <option value="tacacs">TACACS+</option>
                                                        <option value="radius">RADIUS</option>
                                                        <option value="local">Local</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="session-acct-start-stop">
                                                        <label class="form-check-label" for="session-acct-start-stop">
                                                            Start-Stop Accounting
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary" onclick="applyAccountingConfig()">
                                        <i class="fas fa-save"></i> Apply Accounting Configuration
                                    </button>
                                </div>

                                <!-- Accounting Logs -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Recent Accounting Logs</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Timestamp</th>
                                                        <th>User</th>
                                                        <th>Action</th>
                                                        <th>Command</th>
                                                        <th>Result</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="accounting-logs-tbody">
                                                    <!-- Accounting logs will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RADIUS Tab -->
                    <div class="tab-pane fade" id="radius" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-server"></i> RADIUS Configuration</h5>
                                    <button class="btn btn-success btn-sm" onclick="showAddRadiusServerModal()">
                                        <i class="fas fa-plus"></i> Add RADIUS Server
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Global RADIUS Settings</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Timeout (seconds)</label>
                                                            <input type="number" class="form-control" id="radius-timeout" 
                                                                   value="5" min="1" max="60">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Retries</label>
                                                            <input type="number" class="form-control" id="radius-retries" 
                                                                   value="3" min="1" max="10">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Shared Secret</label>
                                                    <input type="password" class="form-control" id="radius-secret" 
                                                           placeholder="Enter shared secret">
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyRadiusGlobalSettings()">
                                                    Apply Global Settings
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">RADIUS Server Groups</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Group Name</label>
                                                    <input type="text" class="form-control" id="radius-group-name" 
                                                           placeholder="e.g., CORP_RADIUS">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Dead Time (minutes)</label>
                                                    <input type="number" class="form-control" id="radius-dead-time" 
                                                           value="0" min="0" max="1440">
                                                </div>
                                                <button class="btn btn-success btn-sm" onclick="createRadiusGroup()">
                                                    Create Server Group
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Server IP</th>
                                                <th>Port</th>
                                                <th>Status</th>
                                                <th>Group</th>
                                                <th>Priority</th>
                                                <th>Response Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="radius-servers-tbody">
                                            <!-- RADIUS servers will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TACACS+ Tab -->
                    <div class="tab-pane fade" id="tacacs" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-network-wired"></i> TACACS+ Configuration</h5>
                                    <button class="btn btn-success btn-sm" onclick="showAddTacacsServerModal()">
                                        <i class="fas fa-plus"></i> Add TACACS+ Server
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Global TACACS+ Settings</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Timeout (seconds)</label>
                                                            <input type="number" class="form-control" id="tacacs-timeout" 
                                                                   value="5" min="1" max="60">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Source Interface</label>
                                                            <select class="form-select" id="tacacs-source-interface">
                                                                <option value="">Auto</option>
                                                                <option value="Vlan1">Vlan1</option>
                                                                <option value="Vlan100">Vlan100</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Shared Secret</label>
                                                    <input type="password" class="form-control" id="tacacs-secret" 
                                                           placeholder="Enter shared secret">
                                                </div>
                                                <button class="btn btn-primary btn-sm" onclick="applyTacacsGlobalSettings()">
                                                    Apply Global Settings
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">TACACS+ Server Groups</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Group Name</label>
                                                    <input type="text" class="form-control" id="tacacs-group-name" 
                                                           placeholder="e.g., CORP_TACACS">
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="tacacs-single-connection">
                                                        <label class="form-check-label" for="tacacs-single-connection">
                                                            Single Connection
                                                        </label>
                                                    </div>
                                                </div>
                                                <button class="btn btn-success btn-sm" onclick="createTacacsGroup()">
                                                    Create Server Group
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Server IP</th>
                                                <th>Port</th>
                                                <th>Status</th>
                                                <th>Group</th>
                                                <th>Priority</th>
                                                <th>Response Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tacacs-servers-tbody">
                                            <!-- TACACS+ servers will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Local Users Tab -->
                    <div class="tab-pane fade" id="local-users" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-users"></i> Local User Management</h5>
                                    <button class="btn btn-success btn-sm" onclick="showAddUserModal()">
                                        <i class="fas fa-user-plus"></i> Add User
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Privilege Level</th>
                                                <th>Role</th>
                                                <th>Last Login</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="local-users-tbody">
                                            <!-- Local users will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add User Modal -->
                <div class="modal fade" id="addUserModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Local User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="add-user-form">
                                    <div class="mb-3">
                                        <label class="form-label">Username *</label>
                                        <input type="text" class="form-control" id="new-username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password *</label>
                                        <input type="password" class="form-control" id="new-password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" id="confirm-password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Privilege Level</label>
                                        <select class="form-select" id="new-privilege-level">
                                            <option value="1">1 (User)</option>
                                            <option value="15">15 (Admin)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" id="new-user-role">
                                            <option value="network-operator">Network Operator</option>
                                            <option value="network-admin">Network Admin</option>
                                            <option value="vdc-admin">VDC Admin</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="createLocalUser()">
                                    Create User
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
        let aaaData = {};
        let radiusServers = [];
        let tacacsServers = [];
        let localUsers = [];

        // Page-specific refresh function
        window.pageRefreshFunction = loadAaaData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAaaData();
        });

        function loadAaaData() {
            // Mock data for demonstration
            aaaData = generateMockAaaData();
            radiusServers = generateMockRadiusServers();
            tacacsServers = generateMockTacacsServers();
            localUsers = generateMockLocalUsers();
            
            updateAaaStatus();
            displayRadiusServers();
            displayTacacsServers();
            displayLocalUsers();
            displayAccountingLogs();
        }

        function generateMockAaaData() {
            return {
                authentication: {
                    console: { enabled: false, method: 'local' },
                    ssh: { enabled: true, method: 'tacacs-local' },
                    http: { enabled: false, method: 'local' },
                    enable: { enabled: true, method: 'local' }
                },
                authorization: {
                    commands: { enabled: true, method: 'tacacs-local' },
                    config: { enabled: false, method: 'local' }
                },
                accounting: {
                    commands: { enabled: true, method: 'tacacs' },
                    sessions: { enabled: true, method: 'tacacs' }
                },
                activeSessions: 3
            };
        }

        function generateMockRadiusServers() {
            return [
                {
                    ip: '192.168.1.10',
                    port: 1812,
                    status: 'active',
                    group: 'CORP_RADIUS',
                    priority: 1,
                    responseTime: '15ms'
                },
                {
                    ip: '192.168.1.11',
                    port: 1812,
                    status: 'inactive',
                    group: 'CORP_RADIUS',
                    priority: 2,
                    responseTime: 'timeout'
                }
            ];
        }

        function generateMockTacacsServers() {
            return [
                {
                    ip: '192.168.1.20',
                    port: 49,
                    status: 'active',
                    group: 'CORP_TACACS',
                    priority: 1,
                    responseTime: '12ms'
                }
            ];
        }

        function generateMockLocalUsers() {
            return [
                {
                    username: 'admin',
                    privilegeLevel: 15,
                    role: 'network-admin',
                    lastLogin: '2024-01-15 14:30:00',
                    status: 'active'
                },
                {
                    username: 'operator',
                    privilegeLevel: 1,
                    role: 'network-operator',
                    lastLogin: '2024-01-15 10:15:00',
                    status: 'active'
                },
                {
                    username: 'backup_admin',
                    privilegeLevel: 15,
                    role: 'network-admin',
                    lastLogin: 'Never',
                    status: 'inactive'
                }
            ];
        }

        function updateAaaStatus() {
            document.getElementById('auth-status').textContent = 
                aaaData.authentication.ssh.enabled ? 'Enabled' : 'Disabled';
            document.getElementById('authz-status').textContent = 
                aaaData.authorization.commands.enabled ? 'Enabled' : 'Disabled';
            document.getElementById('acct-status').textContent = 
                aaaData.accounting.commands.enabled ? 'Enabled' : 'Disabled';
            document.getElementById('active-sessions').textContent = aaaData.activeSessions;
        }

        function displayRadiusServers() {
            const tbody = document.getElementById('radius-servers-tbody');
            tbody.innerHTML = '';

            if (radiusServers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No RADIUS servers configured</td></tr>';
                return;
            }

            radiusServers.forEach(server => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${server.ip}</strong></td>
                    <td>${server.port}</td>
                    <td>
                        <span class="badge ${server.status === 'active' ? 'bg-success' : 'bg-danger'}">
                            ${server.status.toUpperCase()}
                        </span>
                    </td>
                    <td>${server.group}</td>
                    <td>${server.priority}</td>
                    <td>${server.responseTime}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editRadiusServer('${server.ip}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="testRadiusServer('${server.ip}')">
                            <i class="fas fa-vial"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRadiusServer('${server.ip}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayTacacsServers() {
            const tbody = document.getElementById('tacacs-servers-tbody');
            tbody.innerHTML = '';

            if (tacacsServers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No TACACS+ servers configured</td></tr>';
                return;
            }

            tacacsServers.forEach(server => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${server.ip}</strong></td>
                    <td>${server.port}</td>
                    <td>
                        <span class="badge ${server.status === 'active' ? 'bg-success' : 'bg-danger'}">
                            ${server.status.toUpperCase()}
                        </span>
                    </td>
                    <td>${server.group}</td>
                    <td>${server.priority}</td>
                    <td>${server.responseTime}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editTacacsServer('${server.ip}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="testTacacsServer('${server.ip}')">
                            <i class="fas fa-vial"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTacacsServer('${server.ip}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayLocalUsers() {
            const tbody = document.getElementById('local-users-tbody');
            tbody.innerHTML = '';

            localUsers.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${user.username}</strong></td>
                    <td>
                        <span class="badge ${user.privilegeLevel === 15 ? 'bg-danger' : 'bg-primary'}">
                            Level ${user.privilegeLevel}
                        </span>
                    </td>
                    <td>${user.role}</td>
                    <td>${user.lastLogin}</td>
                    <td>
                        <span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                            ${user.status.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editUser('${user.username}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="resetPassword('${user.username}')">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser('${user.username}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayAccountingLogs() {
            const tbody = document.getElementById('accounting-logs-tbody');
            const mockLogs = [
                { timestamp: '2024-01-15 14:35:00', user: 'admin', action: 'command', command: 'show running-config', result: 'success' },
                { timestamp: '2024-01-15 14:30:00', user: 'admin', action: 'login', command: 'ssh', result: 'success' },
                { timestamp: '2024-01-15 14:25:00', user: 'operator', action: 'command', command: 'show interface status', result: 'success' }
            ];

            tbody.innerHTML = '';
            mockLogs.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${log.timestamp}</td>
                    <td>${log.user}</td>
                    <td>${log.action}</td>
                    <td><code>${log.command}</code></td>
                    <td>
                        <span class="badge ${log.result === 'success' ? 'bg-success' : 'bg-danger'}">
                            ${log.result.toUpperCase()}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function applyConsoleAuth() {
            const enabled = document.getElementById('console-auth-enable').checked;
            const method = document.getElementById('console-auth-method').value;
            
            let commands = [];
            if (enabled) {
                commands.push(`aaa authentication login console ${method}`);
            } else {
                commands.push('no aaa authentication login console');
            }

            confirmAction(`Apply console authentication configuration?\n\n${commands.join('\n')}`, function() {
                executeCommand(`configure terminal\n${commands.join('\n')}`, function(data) {
                    showAlert('Console authentication configuration applied', 'success');
                    setTimeout(loadAaaData, 2000);
                });
            });
        }

        function applySshAuth() {
            const enabled = document.getElementById('ssh-auth-enable').checked;
            const method = document.getElementById('ssh-auth-method').value;
            
            let commands = [];
            if (enabled) {
                commands.push(`aaa authentication login default ${method}`);
            } else {
                commands.push('no aaa authentication login default');
            }

            confirmAction(`Apply SSH authentication configuration?\n\n${commands.join('\n')}`, function() {
                executeCommand(`configure terminal\n${commands.join('\n')}`, function(data) {
                    showAlert('SSH authentication configuration applied', 'success');
                    setTimeout(loadAaaData, 2000);
                });
            });
        }

        function applyHttpAuth() {
            showAlert('HTTP authentication configuration applied', 'success');
        }

        function applyEnableAuth() {
            showAlert('Enable authentication configuration applied', 'success');
        }

        function applyAuthorizationConfig() {
            showAlert('Authorization configuration applied', 'success');
        }

        function applyAccountingConfig() {
            showAlert('Accounting configuration applied', 'success');
        }

        function showAddUserModal() {
            const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
            modal.show();
        }

        function createLocalUser() {
            const username = document.getElementById('new-username').value;
            const password = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const privilegeLevel = document.getElementById('new-privilege-level').value;
            const role = document.getElementById('new-user-role').value;

            if (!username || !password) {
                showAlert('Please enter username and password.', 'danger');
                return;
            }

            if (password !== confirmPassword) {
                showAlert('Passwords do not match.', 'danger');
                return;
            }

            const commands = [
                `username ${username} password ${password}`,
                `username ${username} privilege ${privilegeLevel}`,
                `username ${username} role ${role}`
            ];

            confirmAction(`Create local user ${username}?\n\n${commands.join('\n')}`, function() {
                executeCommand(`configure terminal\n${commands.join('\n')}`, function(data) {
                    showAlert(`User ${username} created successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    setTimeout(loadAaaData, 2000);
                });
            });
        }

        function editUser(username) {
            showAlert(`Edit user ${username} - Feature coming soon!`, 'info');
        }

        function resetPassword(username) {
            showAlert(`Reset password for ${username} - Feature coming soon!`, 'info');
        }

        function deleteUser(username) {
            confirmAction(`Delete user ${username}?`, function() {
                executeCommand(`configure terminal\nno username ${username}`, function(data) {
                    showAlert(`User ${username} deleted successfully`, 'success');
                    setTimeout(loadAaaData, 2000);
                });
            });
        }

        function testAuthentication() {
            showAlert('Authentication test initiated - Check logs for results', 'info');
        }

        function exportAaaConfig() {
            const config = `! AAA Configuration\naaa authentication login default tacacs+ local\naaa authorization commands default tacacs+ local\naaa accounting commands default start-stop tacacs+\n!`;
            exportConfig(config, 'aaa-config.txt');
        }

        function showAddRadiusServerModal() {
            showAlert('Add RADIUS server feature coming soon!', 'info');
        }

        function showAddTacacsServerModal() {
            showAlert('Add TACACS+ server feature coming soon!', 'info');
        }

        function applyRadiusGlobalSettings() {
            showAlert('RADIUS global settings applied', 'success');
        }

        function applyTacacsGlobalSettings() {
            showAlert('TACACS+ global settings applied', 'success');
        }

        function createRadiusGroup() {
            showAlert('RADIUS server group created', 'success');
        }

        function createTacacsGroup() {
            showAlert('TACACS+ server group created', 'success');
        }

        function editRadiusServer(ip) {
            showAlert(`Edit RADIUS server ${ip} - Feature coming soon!`, 'info');
        }

        function testRadiusServer(ip) {
            showAlert(`Testing RADIUS server ${ip}...`, 'info');
        }

        function deleteRadiusServer(ip) {
            confirmAction(`Delete RADIUS server ${ip}?`, function() {
                showAlert(`RADIUS server ${ip} deleted`, 'success');
                setTimeout(loadAaaData, 2000);
            });
        }

        function editTacacsServer(ip) {
            showAlert(`Edit TACACS+ server ${ip} - Feature coming soon!`, 'info');
        }

        function testTacacsServer(ip) {
            showAlert(`Testing TACACS+ server ${ip}...`, 'info');
        }

        function deleteTacacsServer(ip) {
            confirmAction(`Delete TACACS+ server ${ip}?`, function() {
                showAlert(`TACACS+ server ${ip} deleted`, 'success');
                setTimeout(loadAaaData, 2000);
            });
        }
    </script>
</body>
</html>

