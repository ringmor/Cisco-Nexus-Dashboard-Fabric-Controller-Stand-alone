<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Counters - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar"></i> Interface Counters</h2>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-success btn-sm" onclick="refreshCounters()" id="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-warning btn-sm" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto
                </button>
                <button class="btn btn-warning" onclick="clearCounters()">
                    <i class="fas fa-trash"></i> Clear Counters
                </button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <select class="form-select" id="interface-filter" onchange="filterInterfaces()">
                    <option value="">All Interfaces</option>
                </select>
            </div>
            <!-- Removed counter-type dropdown -->
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-network-wired"></i> Port</th>
                        <th><i class="fas fa-arrow-down"></i> Input Packets</th>
                        <th><i class="fas fa-arrow-up"></i> Output Packets</th>
                        <th><i class="fas fa-tachometer-alt"></i> Input Rate (Mbps)</th>
                        <th><i class="fas fa-tachometer-alt"></i> Output Rate (Mbps)</th>
                        <th><i class="fas fa-tachometer-alt"></i> Utilization</th>
                    </tr>
                </thead>
                <tbody id="counters-tbody">
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="spinner-border spinner-border-sm me-2"></div>
                            Loading interface counters...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let countersData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadCounters();
            loadInterfaceList();
        });

        function loadCounters() {
            // Always use 'show interface counters brief'
            const command = 'show interface counters brief';

            document.getElementById('counters-tbody').innerHTML = 
                '<tr><td colspan="6" class="text-center"><div class="spinner-border spinner-border-sm me-2"></div>Loading counters...</td></tr>';

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: command
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                
                const counters = parseCountersData(data.result);
                countersData = counters;
                displayCounters(counters);
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            })
            .catch(error => {
                console.error('Error loading counters:', error);
                document.getElementById('counters-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseCountersData(data) {
            const counters = [];
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_interface) {
                        let rows = output.body.TABLE_interface.ROW_interface;
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        rows.forEach(row => {
                            counters.push({
                                interface: row.interface || '',
                                input_packets: row.eth_inframes1 || '0',
                                output_packets: row.eth_outframes1 || '0',
                                input_rate: row.eth_inrate1 || '0.00',
                                output_rate: row.eth_outrate1 || '0.00',
                                utilization: calculateUtilizationBrief(row)
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing counters data:', e);
            }
            return counters;
        }

        function calculateUtilizationBrief(row) {
            // Use the higher of input/output rate (as Mbps, 0-100 scale for bar)
            const inRate = parseFloat(row.eth_inrate1 || '0');
            const outRate = parseFloat(row.eth_outrate1 || '0');
            const maxRate = Math.max(inRate, outRate);
            // Assume 10000 Mbps port for scale (customize as needed)
            const percent = Math.min((maxRate / 10000) * 100, 100);
            return percent;
        }

        function displayCounters(counters) {
            const tbody = document.getElementById('counters-tbody');
            tbody.innerHTML = '';

            if (!counters || counters.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No counter data available</td></tr>';
                return;
            }

            counters.forEach((counter, idx) => {
                const row = document.createElement('tr');
                // Utilization bar (0-100%)
                const utilBar = `<div style=\"background:#e9ecef;width:100px;height:12px;border-radius:6px;overflow:hidden;display:inline-block;vertical-align:middle;\"><div style=\"background:#0d6efd;width:${counter.utilization}%;height:100%;\"></div></div> <span style=\"font-size:12px;\">${counter.utilization.toFixed(2)}%</span>`;
                row.innerHTML = `
                    <td><strong>Port ${idx + 1}</strong></td>
                    <td>${formatNumber(counter.input_packets)}</td>
                    <td>${formatNumber(counter.output_packets)}</td>
                    <td>${parseFloat(counter.input_rate).toFixed(2)}</td>
                    <td>${parseFloat(counter.output_rate).toFixed(2)}</td>
                    <td>${utilBar}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function loadInterfaceList() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show interface brief'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const interfaces = parseInterfaceList(data);
                    populateInterfaceFilter(interfaces);
                }
            })
            .catch(error => console.error('Error loading interface list:', error));
        }

        function parseInterfaceList(data) {
            const interfaces = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_interface) {
                        let rows = output.body.TABLE_interface.ROW_interface;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            interfaces.push(row.interface);
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing interface list:', e);
            }
            
            return interfaces;
        }

        function populateInterfaceFilter(interfaces) {
            const select = document.getElementById('interface-filter');
            
            interfaces.forEach(intf => {
                const option = document.createElement('option');
                option.value = intf;
                option.textContent = intf;
                select.appendChild(option);
            });
        }

        function filterInterfaces() {
            const filter = document.getElementById('interface-filter').value;
            const rows = document.querySelectorAll('#counters-tbody tr');
            
            rows.forEach(row => {
                const interfaceCell = row.querySelector('td:first-child');
                if (interfaceCell) {
                    const interfaceName = interfaceCell.textContent.trim();
                    if (!filter || interfaceName.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        function refreshCounters() {
            loadCounters();
        }

        function clearCounters() {
            if (confirm('Are you sure you want to clear all interface counters?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'execute_command',
                        command: 'clear counters'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Counters cleared successfully', 'success');
                        loadCounters();
                    } else {
                        showAlert('Error clearing counters: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error clearing counters', 'danger');
                });
            }
        }

        function formatNumber(num) {
            if (!num || num === '0') return '0';
            return parseInt(num).toLocaleString();
        }

        function formatBytes(bytes) {
            if (!bytes || bytes === '0') return '0 B';
            
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        }

        // --- Auto Refresh Logic ---
        let autoRefreshInterval = null;
        let isAutoRefreshing = false;
        function toggleAutoRefresh() {
            const btn = document.getElementById('auto-refresh-btn');
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
                isAutoRefreshing = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Auto';
                btn.className = 'btn btn-warning btn-sm';
            } else {
                autoRefreshInterval = setInterval(loadCounters, 10000); // 10 seconds
                isAutoRefreshing = true;
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop';
                btn.className = 'btn btn-danger btn-sm';
            }
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

