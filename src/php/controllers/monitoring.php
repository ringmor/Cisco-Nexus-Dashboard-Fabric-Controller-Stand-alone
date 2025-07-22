<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Monitoring - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-chart-line"></i> Performance Monitoring</h2>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="refresh-interval" onchange="setRefreshInterval()">
                            <option value="5">5 seconds</option>
                            <option value="10" selected>10 seconds</option>
                            <option value="30">30 seconds</option>
                            <option value="60">1 minute</option>
                        </select>
                        <button class="btn btn-success btn-sm" onclick="startMonitoring()" id="start-monitoring">
                            <i class="fas fa-play"></i> Start
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="stopMonitoring()" id="stop-monitoring" disabled>
                            <i class="fas fa-pause"></i> Stop
                        </button>
                        <button class="btn btn-nexus btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- System Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="cpu-usage">-</div>
                            <div class="metric-label">CPU Usage</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="memory-usage">-</div>
                            <div class="metric-label">Memory Usage</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="uptime">-</div>
                            <div class="metric-label">Uptime</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="temperature">-</div>
                            <div class="metric-label">Temperature</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="power-usage">-</div>
                            <div class="metric-label">Power Usage</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="metric-card">
                            <div class="metric-value" id="fan-status">-</div>
                            <div class="metric-label">Fan Status</div>
                        </div>
                    </div>
                </div>

                <!-- Performance Charts -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-microchip"></i> CPU & Memory Usage</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="cpu-memory-chart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-network-wired"></i> Network Traffic</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="network-traffic-chart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interface Performance Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-ethernet"></i> Interface Performance</h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="interface-filter" onchange="filterInterfaces()">
                                    <option value="">All Interfaces</option>
                                    <option value="up">Up Interfaces</option>
                                    <option value="down">Down Interfaces</option>
                                    <option value="high-utilization">High Utilization</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm" onclick="exportInterfaceStats()">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Interface</th>
                                        <th>Status</th>
                                        <th>Speed</th>
                                        <th>Utilization</th>
                                        <th>Input Rate</th>
                                        <th>Output Rate</th>
                                        <th>Input Packets</th>
                                        <th>Output Packets</th>
                                        <th>Errors</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="interface-performance-tbody">
                                    <!-- Interface performance data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- System Health Monitoring -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-thermometer-half"></i> Environmental Monitoring</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Sensor</th>
                                                <th>Current</th>
                                                <th>Threshold</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="environmental-tbody">
                                            <!-- Environmental data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> Power Supply Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>PSU</th>
                                                <th>Status</th>
                                                <th>Input</th>
                                                <th>Output</th>
                                                <th>Efficiency</th>
                                            </tr>
                                        </thead>
                                        <tbody id="power-supply-tbody">
                                            <!-- Power supply data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Protocol Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-layer-group"></i> Protocol Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Spanning Tree</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-success" id="stp-root-ports">-</div>
                                                    <small>Root Ports</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-warning" id="stp-blocked-ports">-</div>
                                                    <small>Blocked</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">OSPF</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-info" id="ospf-neighbors">-</div>
                                                    <small>Neighbors</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-primary" id="ospf-routes">-</div>
                                                    <small>Routes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">BGP</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-success" id="bgp-peers">-</div>
                                                    <small>Peers</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-warning" id="bgp-prefixes">-</div>
                                                    <small>Prefixes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">VLAN</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-primary" id="active-vlans">-</div>
                                                    <small>Active</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <div class="h4 text-secondary" id="total-vlans">-</div>
                                                    <small>Total</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Alerts -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Performance Alerts</h5>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearAllAlerts()">
                                <i class="fas fa-trash"></i> Clear All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="alerts-container">
                            <!-- Performance alerts will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/common.js"></script>
    <script>
        let monitoringInterval;
        let cpuMemoryChart;
        let networkTrafficChart;
        let isMonitoring = false;
        let performanceData = {
            cpu: [],
            memory: [],
            networkIn: [],
            networkOut: [],
            timestamps: []
        };

        // Page-specific refresh function
        window.pageRefreshFunction = loadMonitoringData;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            loadMonitoringData();
            startMonitoring();
        });

        function initializeCharts() {
            // CPU & Memory Chart
            const cpuMemoryCtx = document.getElementById('cpu-memory-chart').getContext('2d');
            cpuMemoryChart = new Chart(cpuMemoryCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'CPU Usage (%)',
                        data: [],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Memory Usage (%)',
                        data: [],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
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
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Network Traffic Chart
            const networkTrafficCtx = document.getElementById('network-traffic-chart').getContext('2d');
            networkTrafficChart = new Chart(networkTrafficCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Inbound (Mbps)',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Outbound (Mbps)',
                        data: [],
                        borderColor: 'rgb(255, 205, 86)',
                        backgroundColor: 'rgba(255, 205, 86, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        function loadMonitoringData() {
            // Generate mock system data
            const systemData = generateMockSystemData();
            updateSystemMetrics(systemData);
            updatePerformanceCharts(systemData);
            
            // Load interface performance data
            const interfaceData = generateMockInterfaceData();
            displayInterfacePerformance(interfaceData);
            
            // Load environmental data
            const environmentalData = generateMockEnvironmentalData();
            displayEnvironmentalData(environmentalData);
            
            // Load power supply data
            const powerData = generateMockPowerData();
            displayPowerSupplyData(powerData);
            
            // Load protocol statistics
            const protocolData = generateMockProtocolData();
            updateProtocolStatistics(protocolData);
            
            // Load performance alerts
            const alerts = generateMockAlerts();
            displayPerformanceAlerts(alerts);
        }

        function generateMockSystemData() {
            return {
                cpu: Math.floor(Math.random() * 30) + 20, // 20-50%
                memory: Math.floor(Math.random() * 40) + 30, // 30-70%
                uptime: '15d 8h 23m',
                temperature: Math.floor(Math.random() * 10) + 35, // 35-45°C
                power: Math.floor(Math.random() * 50) + 150, // 150-200W
                fanStatus: 'OK',
                networkIn: Math.floor(Math.random() * 100) + 50, // 50-150 Mbps
                networkOut: Math.floor(Math.random() * 80) + 30 // 30-110 Mbps
            };
        }

        function generateMockInterfaceData() {
            return [
                {
                    interface: 'Ethernet1/1',
                    status: 'up',
                    speed: '10 Gbps',
                    utilization: Math.floor(Math.random() * 30) + 10,
                    inputRate: Math.floor(Math.random() * 1000) + 500,
                    outputRate: Math.floor(Math.random() * 800) + 300,
                    inputPackets: Math.floor(Math.random() * 1000000) + 5000000,
                    outputPackets: Math.floor(Math.random() * 800000) + 4000000,
                    errors: Math.floor(Math.random() * 10)
                },
                {
                    interface: 'Ethernet1/2',
                    status: 'up',
                    speed: '1 Gbps',
                    utilization: Math.floor(Math.random() * 60) + 20,
                    inputRate: Math.floor(Math.random() * 100) + 50,
                    outputRate: Math.floor(Math.random() * 80) + 30,
                    inputPackets: Math.floor(Math.random() * 500000) + 2000000,
                    outputPackets: Math.floor(Math.random() * 400000) + 1500000,
                    errors: Math.floor(Math.random() * 5)
                },
                {
                    interface: 'Ethernet1/3',
                    status: 'down',
                    speed: '1 Gbps',
                    utilization: 0,
                    inputRate: 0,
                    outputRate: 0,
                    inputPackets: 0,
                    outputPackets: 0,
                    errors: 0
                }
            ];
        }

        function generateMockEnvironmentalData() {
            return [
                { sensor: 'CPU Temperature', current: '42°C', threshold: '85°C', status: 'OK' },
                { sensor: 'Intake Temperature', current: '28°C', threshold: '45°C', status: 'OK' },
                { sensor: 'Exhaust Temperature', current: '35°C', threshold: '55°C', status: 'OK' },
                { sensor: 'Fan 1 Speed', current: '3200 RPM', threshold: '1000 RPM', status: 'OK' },
                { sensor: 'Fan 2 Speed', current: '3150 RPM', threshold: '1000 RPM', status: 'OK' }
            ];
        }

        function generateMockPowerData() {
            return [
                { psu: 'PSU 1', status: 'OK', input: '110V AC', output: '12V DC', efficiency: '92%' },
                { psu: 'PSU 2', status: 'OK', input: '110V AC', output: '12V DC', efficiency: '91%' }
            ];
        }

        function generateMockProtocolData() {
            return {
                stp: { rootPorts: 2, blockedPorts: 1 },
                ospf: { neighbors: 3, routes: 25 },
                bgp: { peers: 2, prefixes: 150000 },
                vlan: { active: 15, total: 20 }
            };
        }

        function generateMockAlerts() {
            const alerts = [];
            if (Math.random() > 0.7) {
                alerts.push({
                    level: 'warning',
                    message: 'Interface Ethernet1/2 utilization is above 80%',
                    timestamp: new Date().toLocaleString()
                });
            }
            if (Math.random() > 0.8) {
                alerts.push({
                    level: 'critical',
                    message: 'CPU temperature approaching threshold',
                    timestamp: new Date().toLocaleString()
                });
            }
            return alerts;
        }

        function updateSystemMetrics(data) {
            document.getElementById('cpu-usage').textContent = data.cpu + '%';
            document.getElementById('memory-usage').textContent = data.memory + '%';
            document.getElementById('uptime').textContent = data.uptime;
            document.getElementById('temperature').textContent = data.temperature + '°C';
            document.getElementById('power-usage').textContent = data.power + 'W';
            document.getElementById('fan-status').textContent = data.fanStatus;

            // Update metric card colors based on values
            updateMetricCardColor('cpu-usage', data.cpu, 80, 90);
            updateMetricCardColor('memory-usage', data.memory, 80, 90);
            updateMetricCardColor('temperature', data.temperature, 70, 80);
        }

        function updateMetricCardColor(elementId, value, warningThreshold, criticalThreshold) {
            const element = document.getElementById(elementId);
            element.className = 'metric-value';
            
            if (value >= criticalThreshold) {
                element.classList.add('text-danger');
            } else if (value >= warningThreshold) {
                element.classList.add('text-warning');
            } else {
                element.classList.add('text-success');
            }
        }

        function updatePerformanceCharts(data) {
            const now = new Date().toLocaleTimeString();
            
            // Update performance data arrays
            performanceData.timestamps.push(now);
            performanceData.cpu.push(data.cpu);
            performanceData.memory.push(data.memory);
            performanceData.networkIn.push(data.networkIn);
            performanceData.networkOut.push(data.networkOut);
            
            // Keep only last 20 data points
            if (performanceData.timestamps.length > 20) {
                performanceData.timestamps.shift();
                performanceData.cpu.shift();
                performanceData.memory.shift();
                performanceData.networkIn.shift();
                performanceData.networkOut.shift();
            }
            
            // Update CPU & Memory chart
            cpuMemoryChart.data.labels = performanceData.timestamps;
            cpuMemoryChart.data.datasets[0].data = performanceData.cpu;
            cpuMemoryChart.data.datasets[1].data = performanceData.memory;
            cpuMemoryChart.update('none');
            
            // Update Network Traffic chart
            networkTrafficChart.data.labels = performanceData.timestamps;
            networkTrafficChart.data.datasets[0].data = performanceData.networkIn;
            networkTrafficChart.data.datasets[1].data = performanceData.networkOut;
            networkTrafficChart.update('none');
        }

        function displayInterfacePerformance(interfaces) {
            const tbody = document.getElementById('interface-performance-tbody');
            tbody.innerHTML = '';

            interfaces.forEach(intf => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${intf.interface}</strong></td>
                    <td>
                        <span class="badge ${intf.status === 'up' ? 'bg-success' : 'bg-danger'}">
                            ${intf.status.toUpperCase()}
                        </span>
                    </td>
                    <td>${intf.speed}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar ${getUtilizationColor(intf.utilization)}" 
                                 style="width: ${intf.utilization}%">
                                ${intf.utilization}%
                            </div>
                        </div>
                    </td>
                    <td>${intf.inputRate.toLocaleString()} pps</td>
                    <td>${intf.outputRate.toLocaleString()} pps</td>
                    <td>${intf.inputPackets.toLocaleString()}</td>
                    <td>${intf.outputPackets.toLocaleString()}</td>
                    <td>
                        <span class="badge ${intf.errors > 0 ? 'bg-warning' : 'bg-success'}">
                            ${intf.errors}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" onclick="showInterfaceDetails('${intf.interface}')">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" onclick="resetInterfaceCounters('${intf.interface}')">
                            <i class="fas fa-redo"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function getUtilizationColor(utilization) {
            if (utilization >= 80) return 'bg-danger';
            if (utilization >= 60) return 'bg-warning';
            return 'bg-success';
        }

        function displayEnvironmentalData(data) {
            const tbody = document.getElementById('environmental-tbody');
            tbody.innerHTML = '';

            data.forEach(sensor => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${sensor.sensor}</td>
                    <td><strong>${sensor.current}</strong></td>
                    <td>${sensor.threshold}</td>
                    <td>
                        <span class="badge ${sensor.status === 'OK' ? 'bg-success' : 'bg-danger'}">
                            ${sensor.status}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayPowerSupplyData(data) {
            const tbody = document.getElementById('power-supply-tbody');
            tbody.innerHTML = '';

            data.forEach(psu => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${psu.psu}</strong></td>
                    <td>
                        <span class="badge ${psu.status === 'OK' ? 'bg-success' : 'bg-danger'}">
                            ${psu.status}
                        </span>
                    </td>
                    <td>${psu.input}</td>
                    <td>${psu.output}</td>
                    <td>${psu.efficiency}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function updateProtocolStatistics(data) {
            document.getElementById('stp-root-ports').textContent = data.stp.rootPorts;
            document.getElementById('stp-blocked-ports').textContent = data.stp.blockedPorts;
            document.getElementById('ospf-neighbors').textContent = data.ospf.neighbors;
            document.getElementById('ospf-routes').textContent = data.ospf.routes;
            document.getElementById('bgp-peers').textContent = data.bgp.peers;
            document.getElementById('bgp-prefixes').textContent = data.bgp.prefixes.toLocaleString();
            document.getElementById('active-vlans').textContent = data.vlan.active;
            document.getElementById('total-vlans').textContent = data.vlan.total;
        }

        function displayPerformanceAlerts(alerts) {
            const container = document.getElementById('alerts-container');
            container.innerHTML = '';

            if (alerts.length === 0) {
                container.innerHTML = '<div class="alert alert-success">No performance alerts at this time.</div>';
                return;
            }

            alerts.forEach(alert => {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${alert.level === 'critical' ? 'danger' : 'warning'} alert-dismissible`;
                alertDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${alert.level.toUpperCase()}:</strong> ${alert.message}
                            <br><small class="text-muted">${alert.timestamp}</small>
                        </div>
                        <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                    </div>
                `;
                container.appendChild(alertDiv);
            });
        }

        function startMonitoring() {
            if (isMonitoring) return;
            
            const interval = parseInt(document.getElementById('refresh-interval').value) * 1000;
            monitoringInterval = setInterval(loadMonitoringData, interval);
            isMonitoring = true;
            
            document.getElementById('start-monitoring').disabled = true;
            document.getElementById('stop-monitoring').disabled = false;
            
            showAlert('Real-time monitoring started', 'success');
        }

        function stopMonitoring() {
            if (!isMonitoring) return;
            
            clearInterval(monitoringInterval);
            isMonitoring = false;
            
            document.getElementById('start-monitoring').disabled = false;
            document.getElementById('stop-monitoring').disabled = true;
            
            showAlert('Real-time monitoring stopped', 'info');
        }

        function setRefreshInterval() {
            if (isMonitoring) {
                stopMonitoring();
                startMonitoring();
            }
        }

        function filterInterfaces() {
            const filter = document.getElementById('interface-filter').value;
            const rows = document.querySelectorAll('#interface-performance-tbody tr');
            
            rows.forEach(row => {
                const status = row.cells[1].textContent.toLowerCase();
                const utilization = parseInt(row.cells[3].querySelector('.progress-bar').textContent);
                
                let show = true;
                
                switch (filter) {
                    case 'up':
                        show = status.includes('up');
                        break;
                    case 'down':
                        show = status.includes('down');
                        break;
                    case 'high-utilization':
                        show = utilization >= 80;
                        break;
                }
                
                row.style.display = show ? '' : 'none';
            });
        }

        function exportInterfaceStats() {
            const data = 'Interface,Status,Speed,Utilization,Input Rate,Output Rate,Input Packets,Output Packets,Errors\n';
            exportConfig(data, 'interface-performance.csv');
        }

        function showInterfaceDetails(interfaceName) {
            showAlert(`Interface details for ${interfaceName} - Feature coming soon!`, 'info');
        }

        function resetInterfaceCounters(interfaceName) {
            confirmAction(`Reset counters for ${interfaceName}?`, function() {
                showAlert(`Counters reset for ${interfaceName}`, 'success');
            });
        }

        function clearAllAlerts() {
            document.getElementById('alerts-container').innerHTML = 
                '<div class="alert alert-success">No performance alerts at this time.</div>';
            showAlert('All alerts cleared', 'success');
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (isMonitoring) {
                stopMonitoring();
            }
        });
    </script>
</body>
</html>

