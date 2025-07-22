<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Tasks - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock"></i> Scheduled Tasks</h2>
            <div>
                <button class="btn btn-success" onclick="refreshTasks()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="createTask()">
                    <i class="fas fa-plus"></i> Create Task
                </button>
                <button class="btn btn-info" onclick="viewTaskHistory()">
                    <i class="fas fa-history"></i> Task History
                </button>
            </div>
        </div>

        <!-- Task Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active Tasks</h5>
                        <h6 id="active-tasks-count">8</h6>
                        <small>Currently scheduled</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Running Tasks</h5>
                        <h6 id="running-tasks-count">2</h6>
                        <small>Currently executing</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Failed Tasks</h5>
                        <h6 id="failed-tasks-count">1</h6>
                        <small>Last 24 hours</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Completed</h5>
                        <h6 id="completed-tasks-count">45</h6>
                        <small>Last 24 hours</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Tabs -->
        <ul class="nav nav-tabs" id="taskTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tasks-tab" data-bs-toggle="tab" data-bs-target="#active-tasks" type="button" role="tab">
                    <i class="fas fa-tasks"></i> Active Tasks
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="create-task-tab" data-bs-toggle="tab" data-bs-target="#create-task" type="button" role="tab">
                    <i class="fas fa-plus"></i> Create Task
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="task-templates-tab" data-bs-toggle="tab" data-bs-target="#task-templates" type="button" role="tab">
                    <i class="fas fa-file-alt"></i> Templates
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="task-history-tab" data-bs-toggle="tab" data-bs-target="#task-history" type="button" role="tab">
                    <i class="fas fa-history"></i> History
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="task-settings-tab" data-bs-toggle="tab" data-bs-target="#task-settings" type="button" role="tab">
                    <i class="fas fa-cogs"></i> Settings
                </button>
            </li>
        </ul>

        <div class="tab-content" id="taskTabContent">
            <!-- Active Tasks Tab -->
            <div class="tab-pane fade show active" id="active-tasks" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-tasks"></i> Active Scheduled Tasks</h5>
                        <div>
                            <button class="btn btn-warning btn-sm" onclick="pauseAllTasks()">
                                <i class="fas fa-pause"></i> Pause All
                            </button>
                            <button class="btn btn-success btn-sm" onclick="resumeAllTasks()">
                                <i class="fas fa-play"></i> Resume All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Task Name</th>
                                        <th>Type</th>
                                        <th>Schedule</th>
                                        <th>Next Run</th>
                                        <th>Last Run</th>
                                        <th>Status</th>
                                        <th>Success Rate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="active-tasks-tbody">
                                    <tr>
                                        <td><strong>Daily Config Backup</strong></td>
                                        <td><span class="badge bg-primary">Backup</span></td>
                                        <td>Daily at 02:00</td>
                                        <td>2024-01-16 02:00:00</td>
                                        <td>2024-01-15 02:00:00</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>98% (29/30)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('backup_daily')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="pauseTask('backup_daily')">
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="runTask('backup_daily')">
                                                <i class="fas fa-play"></i> Run Now
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Interface Status Check</strong></td>
                                        <td><span class="badge bg-info">Monitoring</span></td>
                                        <td>Every 5 minutes</td>
                                        <td>2024-01-15 14:35:00</td>
                                        <td>2024-01-15 14:30:00</td>
                                        <td><span class="badge bg-success">Running</span></td>
                                        <td>100% (288/288)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('interface_check')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="pauseTask('interface_check')">
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskLog('interface_check')">
                                                <i class="fas fa-file-alt"></i> Log
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Weekly Firmware Check</strong></td>
                                        <td><span class="badge bg-warning">Maintenance</span></td>
                                        <td>Weekly on Sunday 01:00</td>
                                        <td>2024-01-21 01:00:00</td>
                                        <td>2024-01-14 01:00:00</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>95% (19/20)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('firmware_check')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="pauseTask('firmware_check')">
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="runTask('firmware_check')">
                                                <i class="fas fa-play"></i> Run Now
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>OSPF Neighbor Monitor</strong></td>
                                        <td><span class="badge bg-success">Routing</span></td>
                                        <td>Every 2 minutes</td>
                                        <td>2024-01-15 14:32:00</td>
                                        <td>2024-01-15 14:30:00</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>99% (719/720)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('ospf_monitor')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="pauseTask('ospf_monitor')">
                                                <i class="fas fa-pause"></i> Pause
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskLog('ospf_monitor')">
                                                <i class="fas fa-file-alt"></i> Log
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>System Health Report</strong></td>
                                        <td><span class="badge bg-info">Report</span></td>
                                        <td>Daily at 08:00</td>
                                        <td>2024-01-16 08:00:00</td>
                                        <td>2024-01-15 08:00:00</td>
                                        <td><span class="badge bg-warning">Paused</span></td>
                                        <td>92% (27/30)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('health_report')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="resumeTask('health_report')">
                                                <i class="fas fa-play"></i> Resume
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="runTask('health_report')">
                                                <i class="fas fa-play"></i> Run Now
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Log Cleanup</strong></td>
                                        <td><span class="badge bg-secondary">Maintenance</span></td>
                                        <td>Weekly on Saturday 23:00</td>
                                        <td>2024-01-20 23:00:00</td>
                                        <td>2024-01-13 23:00:00</td>
                                        <td><span class="badge bg-danger">Failed</span></td>
                                        <td>85% (17/20)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask('log_cleanup')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="runTask('log_cleanup')">
                                                <i class="fas fa-play"></i> Run Now
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="viewTaskError('log_cleanup')">
                                                <i class="fas fa-exclamation-triangle"></i> Error
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Task Tab -->
            <div class="tab-pane fade" id="create-task" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Create New Scheduled Task</h5>
                    </div>
                    <div class="card-body">
                        <form id="createTaskForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="task-name" class="form-label">Task Name</label>
                                        <input type="text" class="form-control" id="task-name" placeholder="Enter task name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="task-type" class="form-label">Task Type</label>
                                        <select class="form-select" id="task-type" onchange="updateTaskOptions()">
                                            <option value="">Select task type</option>
                                            <option value="backup">Configuration Backup</option>
                                            <option value="monitoring">System Monitoring</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="routing">Routing Check</option>
                                            <option value="security">Security Audit</option>
                                            <option value="report">Report Generation</option>
                                            <option value="custom">Custom Command</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="task-description" class="form-label">Description</label>
                                        <textarea class="form-control" id="task-description" rows="3" placeholder="Describe what this task does"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="task-priority" class="form-label">Priority</label>
                                        <select class="form-select" id="task-priority">
                                            <option value="low">Low</option>
                                            <option value="normal" selected>Normal</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="schedule-type" class="form-label">Schedule Type</label>
                                        <select class="form-select" id="schedule-type" onchange="updateScheduleOptions()">
                                            <option value="interval">Interval (Every X minutes/hours)</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="cron">Custom (Cron Expression)</option>
                                        </select>
                                    </div>
                                    <div id="schedule-options">
                                        <div class="mb-3">
                                            <label for="interval-value" class="form-label">Interval</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="interval-value" value="5" min="1">
                                                <select class="form-select" id="interval-unit">
                                                    <option value="minutes">Minutes</option>
                                                    <option value="hours">Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="task-timeout" class="form-label">Timeout (minutes)</label>
                                        <input type="number" class="form-control" id="task-timeout" value="30" min="1" max="1440">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task-enabled" checked>
                                            <label class="form-check-label" for="task-enabled">
                                                Enable task immediately
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task-notifications">
                                            <label class="form-check-label" for="task-notifications">
                                                Send notifications on failure
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task-retry">
                                            <label class="form-check-label" for="task-retry">
                                                Retry on failure
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="task-specific-options" class="row">
                                <!-- Task-specific options will be populated here -->
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveTask()">
                                        <i class="fas fa-save"></i> Create Task
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testTask()">
                                        <i class="fas fa-test-tube"></i> Test Task
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearTaskForm()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Task Templates Tab -->
            <div class="tab-pane fade" id="task-templates" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-file-alt"></i> Task Templates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-save"></i> Daily Backup</h6>
                                        <p class="card-text">Automated daily configuration backup to remote server</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('daily_backup')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-heartbeat"></i> Health Monitor</h6>
                                        <p class="card-text">Continuous system health monitoring with alerts</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('health_monitor')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-route"></i> Routing Check</h6>
                                        <p class="card-text">Monitor OSPF/BGP neighbors and routing table</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('routing_check')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-shield-alt"></i> Security Audit</h6>
                                        <p class="card-text">Weekly security configuration audit and compliance check</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('security_audit')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-chart-bar"></i> Performance Report</h6>
                                        <p class="card-text">Generate daily performance and utilization reports</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('performance_report')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-broom"></i> Log Cleanup</h6>
                                        <p class="card-text">Automated log file cleanup and archival</p>
                                        <button class="btn btn-primary btn-sm" onclick="useTemplate('log_cleanup')">
                                            <i class="fas fa-plus"></i> Use Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task History Tab -->
            <div class="tab-pane fade" id="task-history" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> Task Execution History</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="history-filter-task" class="form-label">Filter by Task</label>
                                <select class="form-select" id="history-filter-task">
                                    <option value="">All Tasks</option>
                                    <option value="backup_daily">Daily Config Backup</option>
                                    <option value="interface_check">Interface Status Check</option>
                                    <option value="firmware_check">Weekly Firmware Check</option>
                                    <option value="ospf_monitor">OSPF Neighbor Monitor</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="history-filter-status" class="form-label">Filter by Status</label>
                                <select class="form-select" id="history-filter-status">
                                    <option value="">All Statuses</option>
                                    <option value="success">Success</option>
                                    <option value="failed">Failed</option>
                                    <option value="timeout">Timeout</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="history-filter-days" class="form-label">Time Range</label>
                                <select class="form-select" id="history-filter-days">
                                    <option value="1">Last 24 hours</option>
                                    <option value="7">Last 7 days</option>
                                    <option value="30">Last 30 days</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary" onclick="filterTaskHistory()">
                                        <i class="fas fa-filter"></i> Apply Filter
                                    </button>
                                    <button class="btn btn-info" onclick="exportTaskHistory()">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Task Name</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Output</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="task-history-tbody">
                                    <tr>
                                        <td>Daily Config Backup</td>
                                        <td>2024-01-15 02:00:00</td>
                                        <td>2024-01-15 02:02:15</td>
                                        <td>2m 15s</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                        <td>Backup completed successfully</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskOutput('backup_20240115')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Interface Status Check</td>
                                        <td>2024-01-15 14:30:00</td>
                                        <td>2024-01-15 14:30:05</td>
                                        <td>5s</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                        <td>All interfaces checked</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskOutput('interface_20240115_1430')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Log Cleanup</td>
                                        <td>2024-01-13 23:00:00</td>
                                        <td>2024-01-13 23:15:30</td>
                                        <td>15m 30s</td>
                                        <td><span class="badge bg-danger">Failed</span></td>
                                        <td>Permission denied on log directory</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" onclick="viewTaskOutput('cleanup_20240113')">
                                                <i class="fas fa-exclamation-triangle"></i> Error
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>OSPF Neighbor Monitor</td>
                                        <td>2024-01-15 14:28:00</td>
                                        <td>2024-01-15 14:28:03</td>
                                        <td>3s</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                        <td>All neighbors up</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskOutput('ospf_20240115_1428')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Weekly Firmware Check</td>
                                        <td>2024-01-14 01:00:00</td>
                                        <td>2024-01-14 01:05:45</td>
                                        <td>5m 45s</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                        <td>Firmware up to date</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTaskOutput('firmware_20240114')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Settings Tab -->
            <div class="tab-pane fade" id="task-settings" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Task Scheduler Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="taskSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>General Settings</h6>
                                    <div class="mb-3">
                                        <label for="max-concurrent-tasks" class="form-label">Maximum Concurrent Tasks</label>
                                        <input type="number" class="form-control" id="max-concurrent-tasks" value="5" min="1" max="20">
                                        <small class="form-text text-muted">Maximum number of tasks that can run simultaneously</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="default-timeout" class="form-label">Default Task Timeout (minutes)</label>
                                        <input type="number" class="form-control" id="default-timeout" value="30" min="1" max="1440">
                                    </div>
                                    <div class="mb-3">
                                        <label for="retry-attempts" class="form-label">Default Retry Attempts</label>
                                        <input type="number" class="form-control" id="retry-attempts" value="3" min="0" max="10">
                                    </div>
                                    <div class="mb-3">
                                        <label for="retry-delay" class="form-label">Retry Delay (minutes)</label>
                                        <input type="number" class="form-control" id="retry-delay" value="5" min="1" max="60">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Logging & Notifications</h6>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-task-logging" checked>
                                            <label class="form-check-label" for="enable-task-logging">
                                                Enable task execution logging
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-failure-notifications" checked>
                                            <label class="form-check-label" for="enable-failure-notifications">
                                                Send notifications on task failures
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable-success-notifications">
                                            <label class="form-check-label" for="enable-success-notifications">
                                                Send notifications on task success
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="log-retention-days" class="form-label">Log Retention (days)</label>
                                        <input type="number" class="form-control" id="log-retention-days" value="30" min="1" max="365">
                                    </div>

                                    <h6 class="mt-4">Scheduler Status</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="scheduler-enabled" checked>
                                            <label class="form-check-label" for="scheduler-enabled">
                                                <strong>Task Scheduler Enabled</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Disable to pause all scheduled tasks</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="saveTaskSettings()">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="restartScheduler()">
                                        <i class="fas fa-sync-alt"></i> Restart Scheduler
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="clearTaskQueue()">
                                        <i class="fas fa-trash"></i> Clear Queue
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
        let taskData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadTaskData();
            startTaskMonitoring();
        });

        function loadTaskData() {
            updateTaskCounts();
            loadActiveTasks();
            loadTaskHistory();
        }

        function updateTaskCounts() {
            document.getElementById('active-tasks-count').textContent = '8';
            document.getElementById('running-tasks-count').textContent = '2';
            document.getElementById('failed-tasks-count').textContent = '1';
            document.getElementById('completed-tasks-count').textContent = '45';
        }

        function loadActiveTasks() {
            // Active tasks are already populated in the HTML
        }

        function loadTaskHistory() {
            // Task history is already populated in the HTML
        }

        function startTaskMonitoring() {
            // Start real-time task monitoring
            setInterval(() => {
                updateTaskStatus();
            }, 10000); // Check every 10 seconds
        }

        function updateTaskStatus() {
            // Update task status in real-time
            console.log('Updating task status...');
        }

        function createTask() {
            const createTab = new bootstrap.Tab(document.getElementById('create-task-tab'));
            createTab.show();
            document.getElementById('task-name').focus();
        }

        function updateTaskOptions() {
            const taskType = document.getElementById('task-type').value;
            const optionsDiv = document.getElementById('task-specific-options');
            
            let optionsHTML = '';
            
            switch(taskType) {
                case 'backup':
                    optionsHTML = `
                        <div class="col-12">
                            <h6>Backup Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="backup-type" class="form-label">Backup Type</label>
                                        <select class="form-select" id="backup-type">
                                            <option value="running">Running Configuration</option>
                                            <option value="startup">Startup Configuration</option>
                                            <option value="full">Full System Backup</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="backup-location" class="form-label">Backup Location</label>
                                        <select class="form-select" id="backup-location">
                                            <option value="local">Local Storage</option>
                                            <option value="ftp">FTP Server</option>
                                            <option value="tftp">TFTP Server</option>
                                            <option value="scp">SCP Server</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'monitoring':
                    optionsHTML = `
                        <div class="col-12">
                            <h6>Monitoring Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="monitor-type" class="form-label">Monitor Type</label>
                                        <select class="form-select" id="monitor-type">
                                            <option value="interfaces">Interface Status</option>
                                            <option value="cpu">CPU Usage</option>
                                            <option value="memory">Memory Usage</option>
                                            <option value="temperature">Temperature</option>
                                            <option value="all">All System Resources</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="alert-threshold" class="form-label">Alert Threshold</label>
                                        <input type="number" class="form-control" id="alert-threshold" placeholder="e.g., 90 for 90%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'custom':
                    optionsHTML = `
                        <div class="col-12">
                            <h6>Custom Command Options</h6>
                            <div class="mb-3">
                                <label for="custom-command" class="form-label">Command to Execute</label>
                                <textarea class="form-control" id="custom-command" rows="3" placeholder="Enter the command or script to execute"></textarea>
                            </div>
                        </div>
                    `;
                    break;
            }
            
            optionsDiv.innerHTML = optionsHTML;
        }

        function updateScheduleOptions() {
            const scheduleType = document.getElementById('schedule-type').value;
            const optionsDiv = document.getElementById('schedule-options');
            
            let optionsHTML = '';
            
            switch(scheduleType) {
                case 'interval':
                    optionsHTML = `
                        <div class="mb-3">
                            <label for="interval-value" class="form-label">Interval</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="interval-value" value="5" min="1">
                                <select class="form-select" id="interval-unit">
                                    <option value="minutes">Minutes</option>
                                    <option value="hours">Hours</option>
                                </select>
                            </div>
                        </div>
                    `;
                    break;
                case 'daily':
                    optionsHTML = `
                        <div class="mb-3">
                            <label for="daily-time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="daily-time" value="02:00">
                        </div>
                    `;
                    break;
                case 'weekly':
                    optionsHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weekly-day" class="form-label">Day of Week</label>
                                    <select class="form-select" id="weekly-day">
                                        <option value="0">Sunday</option>
                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                        <option value="6">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weekly-time" class="form-label">Time</label>
                                    <input type="time" class="form-control" id="weekly-time" value="02:00">
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'monthly':
                    optionsHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly-day" class="form-label">Day of Month</label>
                                    <input type="number" class="form-control" id="monthly-day" value="1" min="1" max="31">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly-time" class="form-label">Time</label>
                                    <input type="time" class="form-control" id="monthly-time" value="02:00">
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'cron':
                    optionsHTML = `
                        <div class="mb-3">
                            <label for="cron-expression" class="form-label">Cron Expression</label>
                            <input type="text" class="form-control" id="cron-expression" placeholder="0 2 * * *">
                            <small class="form-text text-muted">Format: minute hour day month day-of-week</small>
                        </div>
                    `;
                    break;
            }
            
            optionsDiv.innerHTML = optionsHTML;
        }

        function saveTask() {
            const taskName = document.getElementById('task-name').value;
            const taskType = document.getElementById('task-type').value;
            
            if (!taskName || !taskType) {
                alert('Please provide task name and type');
                return;
            }
            
            alert(`Task "${taskName}" created successfully`);
            clearTaskForm();
            loadActiveTasks();
        }

        function testTask() {
            const taskName = document.getElementById('task-name').value;
            if (!taskName) {
                alert('Please provide task name to test');
                return;
            }
            alert(`Testing task "${taskName}"...\n\nTask would be executed once to verify configuration.`);
        }

        function clearTaskForm() {
            document.getElementById('createTaskForm').reset();
            document.getElementById('task-enabled').checked = true;
            document.getElementById('task-specific-options').innerHTML = '';
            updateScheduleOptions();
        }

        function editTask(taskId) {
            alert(`Edit task "${taskId}" functionality would be implemented here`);
        }

        function pauseTask(taskId) {
            alert(`Task "${taskId}" paused`);
        }

        function resumeTask(taskId) {
            alert(`Task "${taskId}" resumed`);
        }

        function runTask(taskId) {
            alert(`Running task "${taskId}" immediately...`);
        }

        function viewTaskLog(taskId) {
            alert(`Viewing log for task "${taskId}"`);
        }

        function viewTaskError(taskId) {
            alert(`Viewing error details for task "${taskId}"`);
        }

        function pauseAllTasks() {
            if (confirm('Pause all active tasks?')) {
                alert('All tasks paused');
            }
        }

        function resumeAllTasks() {
            if (confirm('Resume all paused tasks?')) {
                alert('All tasks resumed');
            }
        }

        function useTemplate(templateId) {
            const createTab = new bootstrap.Tab(document.getElementById('create-task-tab'));
            createTab.show();
            
            // Populate form with template data
            switch(templateId) {
                case 'daily_backup':
                    document.getElementById('task-name').value = 'Daily Configuration Backup';
                    document.getElementById('task-type').value = 'backup';
                    document.getElementById('schedule-type').value = 'daily';
                    break;
                case 'health_monitor':
                    document.getElementById('task-name').value = 'System Health Monitor';
                    document.getElementById('task-type').value = 'monitoring';
                    document.getElementById('schedule-type').value = 'interval';
                    break;
                // Add more templates as needed
            }
            
            updateTaskOptions();
            updateScheduleOptions();
        }

        function filterTaskHistory() {
            const task = document.getElementById('history-filter-task').value;
            const status = document.getElementById('history-filter-status').value;
            const days = document.getElementById('history-filter-days').value;
            
            alert(`Filtering task history:\nTask: ${task || 'All'}\nStatus: ${status || 'All'}\nTime Range: Last ${days} days`);
        }

        function exportTaskHistory() {
            alert('Exporting task history to CSV file...');
        }

        function viewTaskOutput(taskId) {
            alert(`Viewing output for task execution "${taskId}"`);
        }

        function saveTaskSettings() {
            alert('Task scheduler settings saved successfully');
        }

        function restartScheduler() {
            if (confirm('Restart the task scheduler? This will interrupt running tasks.')) {
                alert('Task scheduler restarted');
            }
        }

        function clearTaskQueue() {
            if (confirm('Clear all pending tasks from the queue?')) {
                alert('Task queue cleared');
            }
        }

        function refreshTasks() {
            loadTaskData();
        }

        function viewTaskHistory() {
            const historyTab = new bootstrap.Tab(document.getElementById('task-history-tab'));
            historyTab.show();
        }
    </script>
</body>
</html>

