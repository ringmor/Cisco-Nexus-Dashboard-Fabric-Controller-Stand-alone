<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-alt"></i> System Logs & Audit Trail</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="startLogTail()" id="start-tail">
                            <i class="fas fa-play"></i> Start Live Tail
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="stopLogTail()" id="stop-tail" disabled>
                            <i class="fas fa-pause"></i> Stop Tail
                        </button>
                        <button class="btn btn-nexus btn-sm" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportLogs()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Log Filters and Controls -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> Log Filters & Search</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Log Level</label>
                                <select class="form-select" id="log-level-filter" onchange="applyFilters()">
                                    <option value="">All Levels</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="alert">Alert</option>
                                    <option value="critical">Critical</option>
                                    <option value="error">Error</option>
                                    <option value="warning">Warning</option>
                                    <option value="notice">Notice</option>
                                    <option value="info">Info</option>
                                    <option value="debug">Debug</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Module/Facility</label>
                                <select class="form-select" id="module-filter" onchange="applyFilters()">
                                    <option value="">All Modules</option>
                                    <option value="system">System</option>
                                    <option value="interface">Interface</option>
                                    <option value="vlan">VLAN</option>
                                    <option value="routing">Routing</option>
                                    <option value="security">Security</option>
                                    <option value="qos">QoS</option>
                                    <option value="stp">Spanning Tree</option>
                                    <option value="bgp">BGP</option>
                                    <option value="ospf">OSPF</option>
                                    <option value="aaa">AAA</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Time Range</label>
                                <select class="form-select" id="time-filter" onchange="applyFilters()">
                                    <option value="1h">Last Hour</option>
                                    <option value="6h">Last 6 Hours</option>
                                    <option value="24h" selected>Last 24 Hours</option>
                                    <option value="7d">Last 7 Days</option>
                                    <option value="30d">Last 30 Days</option>
                                    <option value="all">All Time</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-input" placeholder="Search logs..." onkeyup="applyFilters()">
                                    <button class="btn btn-outline-secondary" onclick="clearSearch()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="auto-scroll" checked>
                                    <label class="form-check-label" for="auto-scroll">Auto-scroll to bottom</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="show-timestamps" checked>
                                    <label class="form-check-label" for="show-timestamps">Show timestamps</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="word-wrap" checked>
                                    <label class="form-check-label" for="word-wrap">Word wrap</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="text-muted">Showing <span id="visible-logs">0</span> of <span id="total-logs">0</span> log entries</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Log Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-danger" id="error-count">0</div>
                                <small>Errors</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-warning" id="warning-count">0</div>
                                <small>Warnings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-info" id="info-count">0</div>
                                <small>Info</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-success" id="debug-count">0</div>
                                <small>Debug</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-primary" id="total-count">0</div>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-secondary" id="rate-count">0</div>
                                <small>Per Min</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Log Display -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Log Entries</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="toggleLogView()">
                                    <i class="fas fa-expand-arrows-alt"></i> Toggle View
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="clearLogs()">
                                    <i class="fas fa-trash"></i> Clear Display
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="log-container" class="log-container" style="height: 500px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 13px;">
                            <div class="p-3 text-muted">Loading system logs...</div>
                        </div>
                    </div>
                </div>

                <!-- Log Analysis -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Top Error Sources</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Module</th>
                                                <th>Error Count</th>
                                                <th>Last Error</th>
                                            </tr>
                                        </thead>
                                        <tbody id="error-sources-tbody">
                                            <!-- Error sources will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Level</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody id="recent-activity-tbody">
                                            <!-- Recent activity will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/common.js"></script>
    <script>
        let allLogs = [];
        let filteredLogs = [];
        let logTailInterval;
        let isLogTailing = false;

        // Page-specific refresh function
        window.pageRefreshFunction = loadLogs;

        // Load logs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadLogs();
            generateMockLogs();
        });

        function loadLogs() {
            loadSystemLogs(1000).then(response => {
                if (response.success) {
                    allLogs = response.data || [];
                    applyFilters();
                    updateLogStatistics();
                    updateErrorSources();
                    updateRecentActivity();
                } else {
                    // If no saved logs, generate mock data
                    generateMockLogs();
                }
            });
        }

        function generateMockLogs() {
            const mockLogs = [];
            const levels = ['info', 'warning', 'error', 'debug', 'critical'];
            const modules = ['system', 'interface', 'vlan', 'routing', 'security', 'qos', 'stp', 'bgp', 'ospf', 'aaa'];
            const messages = [
                'Interface Ethernet1/1 changed state to up',
                'VLAN 10 created successfully',
                'BGP neighbor 192.168.1.1 established',
                'OSPF neighbor 10.0.0.2 down',
                'User admin logged in from 192.168.1.100',
                'Configuration saved to startup-config',
                'Temperature sensor reading: 42°C',
                'Port security violation on Ethernet1/5',
                'STP topology change detected',
                'QoS policy applied to interface Ethernet1/2',
                'Static route 192.168.2.0/24 added',
                'AAA authentication failed for user guest',
                'DHCP snooping enabled on VLAN 20',
                'Link flap detected on Ethernet1/3',
                'Memory utilization: 65%',
                'CPU utilization: 45%',
                'Fan speed adjusted to 3200 RPM',
                'Power supply 1 status: OK',
                'SNMP trap sent to 192.168.1.200',
                'NTP synchronization successful'
            ];

            // Generate logs for the last 24 hours
            const now = new Date();
            for (let i = 0; i < 200; i++) {
                const timestamp = new Date(now.getTime() - Math.random() * 24 * 60 * 60 * 1000);
                const level = levels[Math.floor(Math.random() * levels.length)];
                const module = modules[Math.floor(Math.random() * modules.length)];
                const message = messages[Math.floor(Math.random() * messages.length)];
                
                mockLogs.push({
                    timestamp: timestamp.toISOString(),
                    level: level,
                    module: module,
                    message: message,
                    id: `log_${i}`
                });
            }

            // Sort by timestamp (newest first)
            mockLogs.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
            
            allLogs = mockLogs;
            applyFilters();
            updateLogStatistics();
            updateErrorSources();
            updateRecentActivity();
        }

        function applyFilters() {
            const levelFilter = document.getElementById('log-level-filter').value;
            const moduleFilter = document.getElementById('module-filter').value;
            const timeFilter = document.getElementById('time-filter').value;
            const searchTerm = document.getElementById('search-input').value.toLowerCase();

            filteredLogs = allLogs.filter(log => {
                // Level filter
                if (levelFilter && log.level !== levelFilter) return false;
                
                // Module filter
                if (moduleFilter && log.module !== moduleFilter) return false;
                
                // Time filter
                if (timeFilter !== 'all') {
                    const logTime = new Date(log.timestamp);
                    const now = new Date();
                    const timeLimit = getTimeLimit(timeFilter);
                    if (logTime < timeLimit) return false;
                }
                
                // Search filter
                if (searchTerm && !log.message.toLowerCase().includes(searchTerm)) return false;
                
                return true;
            });

            displayLogs();
            updateLogCounts();
        }

        function getTimeLimit(timeFilter) {
            const now = new Date();
            switch (timeFilter) {
                case '1h': return new Date(now.getTime() - 60 * 60 * 1000);
                case '6h': return new Date(now.getTime() - 6 * 60 * 60 * 1000);
                case '24h': return new Date(now.getTime() - 24 * 60 * 60 * 1000);
                case '7d': return new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                case '30d': return new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                default: return new Date(0);
            }
        }

        function displayLogs() {
            const container = document.getElementById('log-container');
            const showTimestamps = document.getElementById('show-timestamps').checked;
            const wordWrap = document.getElementById('word-wrap').checked;
            
            container.innerHTML = '';
            
            if (filteredLogs.length === 0) {
                container.innerHTML = '<div class="p-3 text-muted">No log entries match the current filters.</div>';
                return;
            }

            filteredLogs.forEach(log => {
                const logEntry = document.createElement('div');
                logEntry.className = `log-entry log-${log.level} p-2 border-bottom`;
                if (!wordWrap) logEntry.style.whiteSpace = 'nowrap';
                
                const timestamp = showTimestamps ? 
                    `<span class="log-timestamp text-muted">${formatTimestamp(log.timestamp)}</span> ` : '';
                
                const levelBadge = `<span class="badge log-level-${log.level}">${log.level.toUpperCase()}</span>`;
                const moduleBadge = `<span class="badge bg-secondary">${log.module}</span>`;
                
                logEntry.innerHTML = `
                    ${timestamp}${levelBadge} ${moduleBadge} 
                    <span class="log-message">${log.message}</span>
                `;
                
                container.appendChild(logEntry);
            });

            // Auto-scroll to bottom if enabled
            if (document.getElementById('auto-scroll').checked) {
                container.scrollTop = container.scrollHeight;
            }
        }

        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString();
        }

        function updateLogCounts() {
            document.getElementById('visible-logs').textContent = filteredLogs.length;
            document.getElementById('total-logs').textContent = allLogs.length;
        }

        function updateLogStatistics() {
            const stats = {
                error: 0,
                warning: 0,
                info: 0,
                debug: 0,
                total: allLogs.length
            };

            allLogs.forEach(log => {
                if (stats.hasOwnProperty(log.level)) {
                    stats[log.level]++;
                }
            });

            document.getElementById('error-count').textContent = stats.error;
            document.getElementById('warning-count').textContent = stats.warning;
            document.getElementById('info-count').textContent = stats.info;
            document.getElementById('debug-count').textContent = stats.debug;
            document.getElementById('total-count').textContent = stats.total;

            // Calculate rate (logs per minute in last hour)
            const oneHourAgo = new Date(Date.now() - 60 * 60 * 1000);
            const recentLogs = allLogs.filter(log => new Date(log.timestamp) > oneHourAgo);
            const rate = Math.round(recentLogs.length / 60);
            document.getElementById('rate-count').textContent = rate;
        }

        function updateErrorSources() {
            const errorSources = {};
            
            allLogs.filter(log => log.level === 'error' || log.level === 'critical').forEach(log => {
                if (!errorSources[log.module]) {
                    errorSources[log.module] = {
                        count: 0,
                        lastError: log.timestamp
                    };
                }
                errorSources[log.module].count++;
                if (new Date(log.timestamp) > new Date(errorSources[log.module].lastError)) {
                    errorSources[log.module].lastError = log.timestamp;
                }
            });

            const tbody = document.getElementById('error-sources-tbody');
            tbody.innerHTML = '';

            Object.entries(errorSources)
                .sort((a, b) => b[1].count - a[1].count)
                .slice(0, 5)
                .forEach(([module, data]) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><strong>${module}</strong></td>
                        <td><span class="badge bg-danger">${data.count}</span></td>
                        <td><small>${formatTimestamp(data.lastError)}</small></td>
                    `;
                    tbody.appendChild(row);
                });

            if (tbody.children.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-muted text-center">No errors found</td></tr>';
            }
        }

        function updateRecentActivity() {
            const recentLogs = allLogs.slice(0, 5);
            const tbody = document.getElementById('recent-activity-tbody');
            tbody.innerHTML = '';

            recentLogs.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><small>${formatTimestamp(log.timestamp)}</small></td>
                    <td><span class="badge log-level-${log.level}">${log.level.toUpperCase()}</span></td>
                    <td><small>${log.message}</small></td>
                `;
                tbody.appendChild(row);
            });
        }

        function startLogTail() {
            if (isLogTailing) return;
            
            logTailInterval = setInterval(() => {
                // Simulate new log entries
                const newLog = generateRandomLogEntry();
                allLogs.unshift(newLog);
                
                // Keep only last 1000 logs
                if (allLogs.length > 1000) {
                    allLogs = allLogs.slice(0, 1000);
                }
                
                applyFilters();
                updateLogStatistics();
                updateErrorSources();
                updateRecentActivity();
            }, 2000);
            
            isLogTailing = true;
            document.getElementById('start-tail').disabled = true;
            document.getElementById('stop-tail').disabled = false;
            
            showAlert('Live log tail started', 'success');
        }

        function stopLogTail() {
            if (!isLogTailing) return;
            
            clearInterval(logTailInterval);
            isLogTailing = false;
            
            document.getElementById('start-tail').disabled = false;
            document.getElementById('stop-tail').disabled = true;
            
            showAlert('Live log tail stopped', 'info');
        }

        function generateRandomLogEntry() {
            const levels = ['info', 'warning', 'error', 'debug'];
            const modules = ['system', 'interface', 'vlan', 'routing', 'security'];
            const messages = [
                'Interface state change detected',
                'Configuration update applied',
                'Network connectivity check passed',
                'System health monitoring active',
                'Performance metrics updated'
            ];

            return {
                timestamp: new Date().toISOString(),
                level: levels[Math.floor(Math.random() * levels.length)],
                module: modules[Math.floor(Math.random() * modules.length)],
                message: messages[Math.floor(Math.random() * messages.length)],
                id: `log_${Date.now()}`
            };
        }

        function refreshLogs() {
            loadLogs();
            showAlert('Logs refreshed', 'success');
        }

        function clearSearch() {
            document.getElementById('search-input').value = '';
            applyFilters();
        }

        function clearLogs() {
            confirmAction('Clear all displayed logs?', function() {
                allLogs = [];
                filteredLogs = [];
                displayLogs();
                updateLogStatistics();
                updateErrorSources();
                updateRecentActivity();
                showAlert('Log display cleared', 'success');
            });
        }

        function exportLogs() {
            if (filteredLogs.length === 0) {
                showAlert('No logs to export', 'warning');
                return;
            }
            
            let content = 'Timestamp,Level,Module,Message\n';
            filteredLogs.forEach(log => {
                content += `"${log.timestamp}","${log.level}","${log.module}","${log.message}"\n`;
            });
            
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            exportConfig(content, `system-logs-${timestamp}.csv`);
        }

        function toggleLogView() {
            const container = document.getElementById('log-container');
            if (container.style.height === '800px') {
                container.style.height = '500px';
            } else {
                container.style.height = '800px';
            }
        }

        // Event listeners for checkboxes
        document.getElementById('show-timestamps').addEventListener('change', displayLogs);
        document.getElementById('word-wrap').addEventListener('change', displayLogs);

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (isLogTailing) {
                stopLogTail();
            }
        });
    </script>

    <style>
        .log-container {
            background-color: #1e1e1e;
            color: #ffffff;
        }

        .log-entry {
            border-color: #333 !important;
        }

        .log-entry:hover {
            background-color: #2a2a2a;
        }

        .log-timestamp {
            color: #888 !important;
        }

        .log-level-emergency,
        .log-level-alert,
        .log-level-critical {
            background-color: #dc3545 !important;
        }

        .log-level-error {
            background-color: #fd7e14 !important;
        }

        .log-level-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .log-level-notice,
        .log-level-info {
            background-color: #0dcaf0 !important;
            color: #000 !important;
        }

        .log-level-debug {
            background-color: #6c757d !important;
        }

        .log-message {
            color: #ffffff;
        }

        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</body>
</html>

