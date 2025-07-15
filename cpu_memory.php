<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU & Memory - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-microchip"></i> CPU & Memory Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()" id="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-warning" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto Refresh
                </button>
            </div>
        </div>

        <!-- System Overview Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">CPU Usage</h5>
                        <h2 id="cpu-usage">0%</h2>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-white" id="cpu-progress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Memory Usage</h5>
                        <h2 id="memory-usage">0%</h2>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-white" id="memory-progress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Load Average</h5>
                        <h2 id="load-average">0.00</h2>
                        <small>1 min average</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Processes</h5>
                        <h2 id="process-count">0</h2>
                        <small>Running processes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> CPU Usage History</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="cpuChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-area"></i> Memory Usage History</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="memoryChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Top Processes by CPU</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Process</th>
                                        <th>CPU %</th>
                                        <th>Memory %</th>
                                        <th>PID</th>
                                    </tr>
                                </thead>
                                <tbody id="processes-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading processes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> System Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td><strong>Total Memory:</strong></td><td id="total-memory">Loading...</td></tr>
                            <tr><td><strong>Free Memory:</strong></td><td id="free-memory">Loading...</td></tr>
                            <tr><td><strong>Used Memory:</strong></td><td id="used-memory">Loading...</td></tr>
                            <tr><td><strong>CPU Cores:</strong></td><td id="cpu-cores">Loading...</td></tr>
                            <tr><td><strong>System Uptime:</strong></td><td id="system-uptime">Loading...</td></tr>
                            <tr><td><strong>Last Update:</strong></td><td id="last-update">Never</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cpuChart, memoryChart;
        let cpuData = [];
        let memoryData = [];
        let timeLabels = [];
        let autoRefreshInterval;
        let isAutoRefreshing = false;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            loadSystemResources();
            loadProcesses();
        });

        function initializeCharts() {
            // CPU Chart
            const cpuCtx = document.getElementById('cpuChart').getContext('2d');
            cpuChart = new Chart(cpuCtx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'CPU Usage %',
                        data: cpuData,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Memory Chart
            const memoryCtx = document.getElementById('memoryChart').getContext('2d');
            memoryChart = new Chart(memoryCtx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'Memory Usage %',
                        data: memoryData,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        function loadSystemResources() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show system resources'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const resources = parseResourceData(data);
                updateResourceDisplay(resources);
                updateCharts(resources);
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            })
            .catch(error => {
                console.error('Error loading system resources:', error);
                // Show error in cards
                document.getElementById('cpu-usage').textContent = 'Error';
                document.getElementById('memory-usage').textContent = 'Error';
            });
        }

        function parseResourceData(data) {
            const resources = {
                cpu_usage: 0,
                memory_usage: 0,
                total_memory: 0,
                free_memory: 0,
                used_memory: 0,
                load_average: 0,
                process_count: 0,
                cpu_cores: 0,
                uptime: 'Unknown'
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const body = output.body;
                        
                        // Parse CPU usage
                        if (body.cpu_usage_kernel) {
                            resources.cpu_usage = parseFloat(body.cpu_usage_kernel) + parseFloat(body.cpu_usage_user || 0);
                        }
                        
                        // Parse memory usage
                        if (body.memory_usage_total && body.memory_usage_used) {
                            resources.total_memory = parseInt(body.memory_usage_total);
                            resources.used_memory = parseInt(body.memory_usage_used);
                            resources.free_memory = resources.total_memory - resources.used_memory;
                            resources.memory_usage = (resources.used_memory / resources.total_memory) * 100;
                        }
                        
                        // Parse other metrics
                        resources.load_average = parseFloat(body.load_avg_1min || 0);
                        resources.process_count = parseInt(body.processes_total || 0);
                        resources.cpu_cores = parseInt(body.cpu_count || 1);
                    }
                }
            } catch (e) {
                console.error('Error parsing resource data:', e);
            }
            
            return resources;
        }

        function updateResourceDisplay(resources) {
            // Update cards
            document.getElementById('cpu-usage').textContent = resources.cpu_usage.toFixed(1) + '%';
            document.getElementById('memory-usage').textContent = resources.memory_usage.toFixed(1) + '%';
            document.getElementById('load-average').textContent = resources.load_average.toFixed(2);
            document.getElementById('process-count').textContent = resources.process_count;

            // Update progress bars
            document.getElementById('cpu-progress').style.width = resources.cpu_usage + '%';
            document.getElementById('memory-progress').style.width = resources.memory_usage + '%';

            // Update system info table
            document.getElementById('total-memory').textContent = formatBytes(resources.total_memory * 1024);
            document.getElementById('free-memory').textContent = formatBytes(resources.free_memory * 1024);
            document.getElementById('used-memory').textContent = formatBytes(resources.used_memory * 1024);
            document.getElementById('cpu-cores').textContent = resources.cpu_cores;
            document.getElementById('system-uptime').textContent = resources.uptime;
        }

        function updateCharts(resources) {
            const now = new Date().toLocaleTimeString();
            
            // Add new data point
            timeLabels.push(now);
            cpuData.push(resources.cpu_usage);
            memoryData.push(resources.memory_usage);
            
            // Keep only last 20 data points
            if (timeLabels.length > 20) {
                timeLabels.shift();
                cpuData.shift();
                memoryData.shift();
            }
            
            // Update charts
            cpuChart.update();
            memoryChart.update();
        }

        function loadProcesses() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show processes cpu'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const processes = parseProcessData(data);
                displayProcesses(processes);
            })
            .catch(error => {
                console.error('Error loading processes:', error);
                document.getElementById('processes-tbody').innerHTML = 
                    '<tr><td colspan="4" class="text-center text-danger">Error loading processes</td></tr>';
            });
        }

        function parseProcessData(data) {
            const processes = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_process) {
                        let rows = output.body.TABLE_process.ROW_process;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            processes.push({
                                name: row.process_name || '',
                                cpu: parseFloat(row.cpu_percent || 0),
                                memory: parseFloat(row.memory_percent || 0),
                                pid: row.pid || ''
                            });
                        });
                        
                        // Sort by CPU usage
                        processes.sort((a, b) => b.cpu - a.cpu);
                    }
                }
            } catch (e) {
                console.error('Error parsing process data:', e);
            }
            
            return processes.slice(0, 10); // Top 10 processes
        }

        function displayProcesses(processes) {
            const tbody = document.getElementById('processes-tbody');
            tbody.innerHTML = '';

            if (!processes || processes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No process data available</td></tr>';
                return;
            }

            processes.forEach(process => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><small>${process.name}</small></td>
                    <td><span class="badge bg-primary">${process.cpu.toFixed(1)}%</span></td>
                    <td><span class="badge bg-success">${process.memory.toFixed(1)}%</span></td>
                    <td><small>${process.pid}</small></td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function refreshData() {
            const btn = document.getElementById('refresh-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading';
            btn.disabled = true;
            
            loadSystemResources();
            loadProcesses();
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                btn.disabled = false;
            }, 2000);
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('auto-refresh-btn');
            
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
                isAutoRefreshing = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Auto Refresh';
                btn.className = 'btn btn-warning';
            } else {
                autoRefreshInterval = setInterval(() => {
                    loadSystemResources();
                    loadProcesses();
                }, 5000); // Refresh every 5 seconds
                isAutoRefreshing = true;
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop Auto';
                btn.className = 'btn btn-danger';
            }
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
            }
        });
    </script>
</body>
</html>

