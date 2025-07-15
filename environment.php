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
                <button class="btn btn-success" onclick="refreshData()">
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
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Temperature</h5>
                        <h2 id="avg-temp">--°C</h2>
                        <small>Average</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Power</h5>
                        <h2 id="total-power">--W</h2>
                        <small>Total Consumption</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Fans</h5>
                        <h2 id="fan-status">--</h2>
                        <small>Status</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Power Supplies</h5>
                        <h2 id="psu-status">--</h2>
                        <small>Status</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Temperature Monitoring -->
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
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody id="temperature-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
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

        <!-- Power and Fan Status -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plug"></i> Power Supply Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PSU</th>
                                        <th>Status</th>
                                        <th>Model</th>
                                        <th>Power</th>
                                        <th>Efficiency</th>
                                    </tr>
                                </thead>
                                <tbody id="power-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading power data...
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
                        <h5><i class="fas fa-fan"></i> Fan Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fan</th>
                                        <th>Status</th>
                                        <th>Speed (RPM)</th>
                                        <th>Direction</th>
                                    </tr>
                                </thead>
                                <tbody id="fan-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading fan data...
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> Environmental History</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Last updated: <span id="last-update">Never</span></p>
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Temperature Trends</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-arrow-up text-danger"></i> Highest: <span id="max-temp">--°C</span></li>
                                    <li><i class="fas fa-arrow-down text-success"></i> Lowest: <span id="min-temp">--°C</span></li>
                                    <li><i class="fas fa-thermometer-half text-info"></i> Average: <span id="avg-temp-history">--°C</span></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Power Consumption</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-bolt text-warning"></i> Current: <span id="current-power">--W</span></li>
                                    <li><i class="fas fa-chart-bar text-info"></i> Average: <span id="avg-power">--W</span></li>
                                    <li><i class="fas fa-percentage text-success"></i> Efficiency: <span id="power-efficiency">--%</span></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>System Health</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check-circle text-success"></i> Operational Fans: <span id="operational-fans">--</span></li>
                                    <li><i class="fas fa-check-circle text-success"></i> Operational PSUs: <span id="operational-psus">--</span></li>
                                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Alerts: <span id="env-alerts">--</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let autoRefreshInterval;
        let isAutoRefreshing = false;

        document.addEventListener('DOMContentLoaded', function() {
            loadEnvironmentData();
        });

        function loadEnvironmentData() {
            loadTemperatureData();
            loadPowerData();
            loadFanData();
        }

        function loadTemperatureData() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show environment temperature'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const tempData = parseTemperatureData(data);
                displayTemperatureData(tempData);
                updateTemperatureSummary(tempData);
            })
            .catch(error => {
                console.error('Error loading temperature data:', error);
                document.getElementById('temperature-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadPowerData() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show environment power'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const powerData = parsePowerData(data);
                displayPowerData(powerData);
                updatePowerSummary(powerData);
            })
            .catch(error => {
                console.error('Error loading power data:', error);
                document.getElementById('power-tbody').innerHTML = 
                    '<tr><td colspan="5" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function loadFanData() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show environment fan'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const fanData = parseFanData(data);
                displayFanData(fanData);
                updateFanSummary(fanData);
                
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            })
            .catch(error => {
                console.error('Error loading fan data:', error);
                document.getElementById('fan-tbody').innerHTML = 
                    '<tr><td colspan="4" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseTemperatureData(data) {
            const sensors = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_tempinfo) {
                        let rows = output.body.TABLE_tempinfo.ROW_tempinfo;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            sensors.push({
                                sensor: row.sensor || '',
                                current_temp: parseFloat(row.curtemp || 0),
                                status: row.status || 'unknown',
                                threshold: parseFloat(row.thresh || 0),
                                critical: parseFloat(row.critical || 0),
                                location: row.location || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing temperature data:', e);
            }
            
            return sensors;
        }

        function parsePowerData(data) {
            const psus = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_psinfo) {
                        let rows = output.body.TABLE_psinfo.ROW_psinfo;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            psus.push({
                                psu: row.psnum || '',
                                status: row.status || 'unknown',
                                model: row.model || '',
                                power: parseFloat(row.watts || 0),
                                efficiency: parseFloat(row.efficiency || 0)
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing power data:', e);
            }
            
            return psus;
        }

        function parseFanData(data) {
            const fans = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_faninfo) {
                        let rows = output.body.TABLE_faninfo.ROW_faninfo;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            fans.push({
                                fan: row.fanname || '',
                                status: row.status || 'unknown',
                                speed: parseInt(row.speed || 0),
                                direction: row.direction || 'unknown'
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing fan data:', e);
            }
            
            return fans;
        }

        function displayTemperatureData(sensors) {
            const tbody = document.getElementById('temperature-tbody');
            tbody.innerHTML = '';

            if (!sensors || sensors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No temperature sensors found</td></tr>';
                return;
            }

            sensors.forEach(sensor => {
                const row = document.createElement('tr');
                
                let statusBadge = '<span class="badge bg-success">OK</span>';
                if (sensor.status.toLowerCase().includes('fail') || sensor.status.toLowerCase().includes('error')) {
                    statusBadge = '<span class="badge bg-danger">FAIL</span>';
                } else if (sensor.status.toLowerCase().includes('warn')) {
                    statusBadge = '<span class="badge bg-warning">WARN</span>';
                }

                row.innerHTML = `
                    <td><strong>${sensor.sensor}</strong></td>
                    <td>${sensor.current_temp}°C</td>
                    <td>${statusBadge}</td>
                    <td>${sensor.threshold}°C</td>
                    <td>${sensor.critical}°C</td>
                    <td><small>${sensor.location}</small></td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayPowerData(psus) {
            const tbody = document.getElementById('power-tbody');
            tbody.innerHTML = '';

            if (!psus || psus.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No power supply data found</td></tr>';
                return;
            }

            psus.forEach(psu => {
                const row = document.createElement('tr');
                
                let statusBadge = '<span class="badge bg-success">OK</span>';
                if (psu.status.toLowerCase().includes('fail') || psu.status.toLowerCase().includes('error')) {
                    statusBadge = '<span class="badge bg-danger">FAIL</span>';
                }

                row.innerHTML = `
                    <td><strong>${psu.psu}</strong></td>
                    <td>${statusBadge}</td>
                    <td><small>${psu.model}</small></td>
                    <td>${psu.power}W</td>
                    <td>${psu.efficiency}%</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function displayFanData(fans) {
            const tbody = document.getElementById('fan-tbody');
            tbody.innerHTML = '';

            if (!fans || fans.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No fan data found</td></tr>';
                return;
            }

            fans.forEach(fan => {
                const row = document.createElement('tr');
                
                let statusBadge = '<span class="badge bg-success">OK</span>';
                if (fan.status.toLowerCase().includes('fail') || fan.status.toLowerCase().includes('error')) {
                    statusBadge = '<span class="badge bg-danger">FAIL</span>';
                }

                row.innerHTML = `
                    <td><strong>${fan.fan}</strong></td>
                    <td>${statusBadge}</td>
                    <td>${fan.speed} RPM</td>
                    <td>${fan.direction}</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updateTemperatureSummary(sensors) {
            if (sensors.length > 0) {
                const avgTemp = sensors.reduce((sum, sensor) => sum + sensor.current_temp, 0) / sensors.length;
                document.getElementById('avg-temp').textContent = avgTemp.toFixed(1) + '°C';
                
                const maxTemp = Math.max(...sensors.map(s => s.current_temp));
                const minTemp = Math.min(...sensors.map(s => s.current_temp));
                
                document.getElementById('max-temp').textContent = maxTemp.toFixed(1) + '°C';
                document.getElementById('min-temp').textContent = minTemp.toFixed(1) + '°C';
                document.getElementById('avg-temp-history').textContent = avgTemp.toFixed(1) + '°C';
            }
        }

        function updatePowerSummary(psus) {
            if (psus.length > 0) {
                const totalPower = psus.reduce((sum, psu) => sum + psu.power, 0);
                const avgEfficiency = psus.reduce((sum, psu) => sum + psu.efficiency, 0) / psus.length;
                const operationalPsus = psus.filter(psu => !psu.status.toLowerCase().includes('fail')).length;
                
                document.getElementById('total-power').textContent = totalPower + 'W';
                document.getElementById('current-power').textContent = totalPower + 'W';
                document.getElementById('avg-power').textContent = totalPower + 'W';
                document.getElementById('power-efficiency').textContent = avgEfficiency.toFixed(1) + '%';
                document.getElementById('operational-psus').textContent = operationalPsus + '/' + psus.length;
                document.getElementById('psu-status').textContent = operationalPsus + '/' + psus.length;
            }
        }

        function updateFanSummary(fans) {
            if (fans.length > 0) {
                const operationalFans = fans.filter(fan => !fan.status.toLowerCase().includes('fail')).length;
                document.getElementById('operational-fans').textContent = operationalFans + '/' + fans.length;
                document.getElementById('fan-status').textContent = operationalFans + '/' + fans.length;
            }
        }

        function refreshData() {
            loadEnvironmentData();
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('auto-refresh-btn');
            
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
                isAutoRefreshing = false;
                btn.innerHTML = '<i class="fas fa-play"></i> Auto Refresh';
                btn.className = 'btn btn-warning';
            } else {
                autoRefreshInterval = setInterval(loadEnvironmentData, 30000); // Refresh every 30 seconds
                isAutoRefreshing = true;
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop Auto';
                btn.className = 'btn btn-danger';
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

