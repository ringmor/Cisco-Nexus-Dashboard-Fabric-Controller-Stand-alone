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
            <div>
                <button class="btn btn-success" onclick="refreshCounters()">
                    <i class="fas fa-sync-alt"></i> Refresh
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
            <div class="col-md-6">
                <select class="form-select" id="counter-type" onchange="loadCounters()">
                    <option value="">All Counters</option>
                    <option value="errors">Error Counters</option>
                    <option value="detailed">Detailed Counters</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Interface</th>
                        <th>Input Packets</th>
                        <th>Input Bytes</th>
                        <th>Output Packets</th>
                        <th>Output Bytes</th>
                        <th>Input Errors</th>
                        <th>Output Errors</th>
                        <th>Utilization</th>
                    </tr>
                </thead>
                <tbody id="counters-tbody">
                    <tr>
                        <td colspan="8" class="text-center">
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
            const counterType = document.getElementById('counter-type').value;
            let command = 'show interface counters';
            
            if (counterType === 'errors') {
                command = 'show interface counters errors';
            } else if (counterType === 'detailed') {
                command = 'show interface counters detailed';
            }

            document.getElementById('counters-tbody').innerHTML = 
                '<tr><td colspan="8" class="text-center"><div class="spinner-border spinner-border-sm me-2"></div>Loading counters...</td></tr>';

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(command)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const counters = parseCountersData(data);
                countersData = counters;
                displayCounters(counters);
            })
            .catch(error => {
                console.error('Error loading counters:', error);
                document.getElementById('counters-tbody').innerHTML = 
                    '<tr><td colspan="8" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
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
                                input_packets: row.eth_inpkts || '0',
                                input_bytes: row.eth_inbytes || '0',
                                output_packets: row.eth_outpkts || '0',
                                output_bytes: row.eth_outbytes || '0',
                                input_errors: row.eth_inerr || '0',
                                output_errors: row.eth_outerr || '0',
                                utilization: calculateUtilization(row)
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing counters data:', e);
            }
            
            return counters;
        }

        function calculateUtilization(row) {
            // Simple utilization calculation (placeholder)
            const inBytes = parseInt(row.eth_inbytes || 0);
            const outBytes = parseInt(row.eth_outbytes || 0);
            const totalBytes = inBytes + outBytes;
            
            if (totalBytes > 1000000000) return '> 1%';
            if (totalBytes > 100000000) return '< 1%';
            return '0%';
        }

        function displayCounters(counters) {
            const tbody = document.getElementById('counters-tbody');
            tbody.innerHTML = '';

            if (!counters || counters.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No counter data available</td></tr>';
                return;
            }

            counters.forEach(counter => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong>${counter.interface}</strong></td>
                    <td>${formatNumber(counter.input_packets)}</td>
                    <td>${formatBytes(counter.input_bytes)}</td>
                    <td>${formatNumber(counter.output_packets)}</td>
                    <td>${formatBytes(counter.output_bytes)}</td>
                    <td class="${counter.input_errors > 0 ? 'text-danger' : ''}">${formatNumber(counter.input_errors)}</td>
                    <td class="${counter.output_errors > 0 ? 'text-danger' : ''}">${formatNumber(counter.output_errors)}</td>
                    <td>${counter.utilization}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function loadInterfaceList() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show interface brief'
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
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=clear counters'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error clearing counters: ' + data.error);
                    } else {
                        alert('Counters cleared successfully');
                        loadCounters();
                    }
                })
                .catch(error => {
                    alert('Error clearing counters: ' + error.message);
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
    </script>
</body>
</html>

