<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environment - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-thermometer-half"></i> Environmental Monitoring</h2>
            <div>
                <button class="btn btn-success" onclick="loadEnvironmentData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-warning" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto Refresh
                </button>
            </div>
        </div>

        <!-- Status Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Temperature</h5>
                        <h2 id="avg-temp">--°C</h2>
                        <small>Average Chipset</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Requested Power</h5>
                        <h2 id="summary-watts-requested">-- W</h2>
                        <small>Watts Requested</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Fans</h5>
                        <h2 id="fan-status" class="display-6">--</h2>
                        <small>Status</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Power Supplies</h5>
                        <p class="mb-2">Power Supply 1: <span id="psu1-status" class="h4">--</span></p>
                        <p class="mb-0">Power Supply 2: <span id="psu2-status" class="h4">--</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Temperature Sensors Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-thermometer-half"></i> Temperature Sensors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sensor</th>
                                        <th>Current Temp</th>
                                        <th>Status</th>
                                        <th>Threshold</th>
                                        <th>Critical</th>
                                    </tr>
                                </thead>
                                <tbody id="temperature-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading temperature data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Environmental History -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Temperature Trends</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-arrow-up text-danger"></i> Highest: <span id="max-temp">--°C</span></li>
                            <li><i class="fas fa-arrow-down text-success"></i> Lowest: <span id="min-temp">--°C</span></li>
                            <li><i class="fas fa-thermometer-half text-info"></i> Average: <span id="avg-temp-history">--°C</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Power Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-bolt text-warning"></i> Total Capacity: <span id="total-capacity">--W</span></li>
                            <li><i class="fas fa-plug text-info"></i> Available: <span id="available-power">--W</span></li>
                            <li><i class="fas fa-chart-bar text-success"></i> Efficiency: <span id="power-efficiency">--%</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>System Health</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Operational Fans: <span id="operational-fans">--</span></li>
                            <li><i class="fas fa-check-circle text-success"></i> Operational PSUs: <span id="operational-psus">--</span></li>
                            <li><i class="fas fa-exclamation-triangle text-danger"></i> Alerts: <span id="env-alerts">--</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let autoRefreshInterval;
        let isAutoRefreshing = false;

        document.addEventListener('DOMContentLoaded', loadEnvironmentData);

        function loadEnvironmentData() {
            loadTemperatureData();
            loadPowerData();
            loadFanData();
            updateLastUpdate();
        }

        function loadTemperatureData() {
            fetch('nxapi.php', { 
                method: 'POST', 
                headers: {'Content-Type':'application/json'}, 
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show environment temperature'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                const out = data.result.ins_api.outputs.output.body;
                let rows = out.TABLE_tempinfo.ROW_tempinfo;
                if (!Array.isArray(rows)) rows = [rows];

                // Calculate trends
                const temps = rows.map(r => parseFloat(r.curtemp));
                const max = Math.max(...temps).toFixed(1);
                const min = Math.min(...temps).toFixed(1);
                const avgHistory = (temps.reduce((a, b) => a + b, 0) / temps.length).toFixed(1);

                document.getElementById('max-temp').textContent = max + '°C';
                document.getElementById('min-temp').textContent = min + '°C';
                document.getElementById('avg-temp-history').textContent = avgHistory + '°C';

                // Calculate average chipset
                let sumChip = 0, countChip = 0;
                rows.forEach(r => {
                    if (r.sensor === 'ASIC') {
                        sumChip += parseFloat(r.curtemp);
                        countChip++;
                    }
                });
                const avgChip = countChip ? (sumChip / countChip).toFixed(1) : '--';
                document.getElementById('avg-temp').textContent = avgChip + (avgChip !== '--' ? '°C' : '');

                // Populate table
                const tbody = document.getElementById('temperature-tbody');
                tbody.innerHTML = '';
                rows.forEach(r => {
                    const badge = r.alarmstatus.toLowerCase().includes('ok')
                        ? '<span class="badge bg-success">OK</span>'
                        : '<span class="badge bg-danger">FAIL</span>';

                    tbody.insertAdjacentHTML('beforeend',
                        `<tr>
                            <td>${r.sensor}</td>
                            <td>${r.curtemp}°C</td>
                            <td>${badge}</td>
                            <td>${r.minthres}°C</td>
                            <td>${r.majthres}°C</td>
                        </tr>`
                    );
                });
            })
            .catch(err => {
                document.getElementById('temperature-tbody').innerHTML =
                    `<tr><td colspan="5" class="text-center text-danger">Error: ${err.message}</td></tr>`;
            });
        }

        function loadPowerData() {
            fetch('nxapi.php', { 
                method: 'POST', 
                headers: {'Content-Type':'application/json'}, 
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show environment power'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                const p = data.result.ins_api.outputs.output.body.powersup;
                const mod = p.TABLE_mod_pow_info.ROW_mod_pow_info;
                const summary = p.power_summary;

                // Summary
                document.getElementById('summary-watts-requested').textContent = parseFloat(mod.watts_requested) + ' W';
                document.getElementById('total-capacity').textContent = parseFloat(summary.tot_pow_capacity) + 'W';
                document.getElementById('available-power').textContent = parseFloat(summary.available_pow) + 'W';
                const psList = Array.isArray(p.TABLE_psinfo.ROW_psinfo)
                    ? p.TABLE_psinfo.ROW_psinfo
                    : [p.TABLE_psinfo.ROW_psinfo];
                const effSum = psList.reduce((sum, ps) => sum + (parseFloat(ps.watts) || 0), 0);
                const avgEff = (effSum / psList.length).toFixed(1);
                document.getElementById('power-efficiency').textContent = avgEff + '%';

                // PSUs
                let okPS = 0;
                psList.forEach((ps, idx) => {
                    const ok = ps.ps_status.toLowerCase() === 'ok';
                    if (ok) okPS++;
                    document.getElementById('psu' + (idx + 1) + '-status').innerHTML =
                        ok
                        ? '<span class="badge bg-success">OK</span>'
                        : '<span class="badge bg-danger">FAIL</span>';
                });
                document.getElementById('operational-psus').textContent = okPS + '/' + psList.length;
            });
        }

        function loadFanData() {
            fetch('nxapi.php',{ 
                method: 'POST', 
                headers: {'Content-Type':'application/json'}, 
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show environment fan'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                const f = data.result.ins_api.outputs.output.body.fandetails.TABLE_faninfo.ROW_faninfo;
                const rows = Array.isArray(f) ? f : [f];
                const failures = rows.filter(r => r.fanstatus.toLowerCase() !== 'ok');

                const fStatusElem = document.getElementById('fan-status');
                if (failures.length === 0) {
                    fStatusElem.innerHTML = '<strong class="small fw-bold">ALL OK</strong>';
                } else {
                    const list = failures.map(r => r.fanname).join(', ');
                    fStatusElem.innerHTML =
                        `<span class="badge bg-danger">FAIL</span> ${list}`;
                }
                document.getElementById('operational-fans').textContent = (rows.length - failures.length) + '/' + rows.length;
            });
        }

        function updateLastUpdate() {
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('auto-refresh-btn');
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
                isAutoRefreshing = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Auto Refresh';
                btn.className = 'btn btn-warning';
            } else {
                autoRefreshInterval = setInterval(loadEnvironmentData, 30000);
                isAutoRefreshing = true;
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop Auto';
                btn.className = 'btn btn-danger';
            }
        }
    </script>
</body>
</html>
