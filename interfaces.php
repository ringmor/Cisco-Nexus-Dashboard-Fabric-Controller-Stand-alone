<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Status - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
    <style>
        .switch-chassis {
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 15px;
            padding: 30px;
            margin: 0;
            box-shadow: none;
            position: relative;
        }
        
        .switch-label {
            position: absolute;
            top: 10px;
            left: 20px;
            color: #ecf0f1;
            font-size: 14px;
            font-weight: bold;
        }
        
        .port-panel {
            display: grid;
            grid-template-columns: repeat(24, 1fr);
            gap: 6px;
            margin: 0 0 18px 0;
        }
        .port-row {
            margin-bottom: 18px;
        }
        @media (max-width: 1200px) {
            .port-panel {
                grid-template-columns: repeat(12, 1fr);
            }
        }
        @media (max-width: 768px) {
            .port-panel {
                grid-template-columns: repeat(6, 1fr);
            }
        }
        @media (max-width: 480px) {
            .port-panel {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .port {
            width: 60px;
            height: 35px;
            border-radius: 6px;
            border: 2px solid #34495e;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: white;
            font-weight: bold;
        }
        
        .port:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .port.up {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border-color: #27ae60;
            box-shadow: 0 0 10px rgba(39, 174, 96, 0.5);
        }
        
        .port.down {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-color: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
        }
        
        .port.admin-down {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border-color: #f39c12;
            box-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
        }
        
        .port-number {
            font-size: 18px;
            margin-bottom: 2px;
            line-height: 1.1;
        }
        
        .port-speed {
            font-size: 7px;
            opacity: 0.8;
        }
        
        .port.up::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        
        /* Remove .switch-info styles */
        .switch-info { display: none; }
        /* New bottom summary styles */
        .status-summary-bottom {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 40px 0 0 0;
            padding: 25px 0 10px 0;
            border-radius: 16px;
            background: linear-gradient(90deg, #232526 0%, #414345 100%);
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
        }
        .status-card-bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 18px 28px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            min-width: 90px;
        }
        .status-card-bottom.up {
            background: linear-gradient(135deg, #27ae60 60%, #2ecc71 100%);
            color: #fff;
        }
        .status-card-bottom.down {
            background: linear-gradient(135deg, #e74c3c 60%, #c0392b 100%);
            color: #fff;
        }
        .status-card-bottom.total {
            background: linear-gradient(135deg, #2980b9 60%, #6dd5fa 100%);
            color: #fff;
        }
        .status-number {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 6px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        .status-label {
            font-size: 1rem;
            opacity: 0.92;
            letter-spacing: 1px;
        }
        
        .mgmt-ports {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #34495e;
        }
        
        .mgmt-port {
            width: 80px;
            height: 25px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: 2px solid #3498db;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .refresh-controls, .refresh-controls-fixed, .status-summary-bottom { display: none; }
        .switch-chassis {
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 15px;
            padding: 30px;
            margin: 0;
            box-shadow: none;
            position: relative;
        }
        .switch-label {
            position: absolute;
            top: 10px;
            left: 20px;
            color: #ecf0f1;
            font-size: 14px;
            font-weight: bold;
        }
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            z-index: 100;
        }
        .loading-text {
            color: white;
            font-size: 16px;
        }
        .card.text-center h3#total-ports,
        .card.text-center h3#ports-up,
        .card.text-center h3#ports-down {
            font-weight: 900;
            font-size: 2.6rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-network-wired"></i> Interface Status</h2>
            <div>
                <button class="btn btn-success" onclick="refreshInterfaces()" id="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-warning" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto
                </button>
                <!-- Removed last-update span -->
            </div>
        </div>
        <!-- Status Overview Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success">UP</h5>
                        <h3 id="ports-up" class="text-success">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-danger">DOWN</h5>
                        <h3 id="ports-down" class="text-danger">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-info">TOTAL</h5>
                        <h3 id="total-ports" class="text-info">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Card for Switch Chassis and Ports -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="switch-chassis" id="switch-chassis">
                    <div class="switch-label">CISCO NEXUS</div>
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-text">
                            <i class="fas fa-spinner fa-spin"></i> Loading interfaces from switch...
                        </div>
                    </div>
                    <!-- Ethernet Ports: 24 top, 24 bottom, close together -->
                    <div id="ethernet-ports-rows">
                        <div class="port-panel port-row" id="ethernet-ports-top"></div>
                        <div class="port-panel port-row" id="ethernet-ports-bottom"></div>
                    </div>
                    <!-- Management Ports -->
                    <div class="mgmt-ports">
                        <h6 style="color: #ecf0f1; margin-bottom: 10px;">Management Ports</h6>
                        <div id="mgmt-ports-container">
                            <!-- Management ports will be added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Port Details Modal -->
    <div class="modal fade" id="portModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Port Details - <span id="modal-port-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Status Information</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Interface:</strong></td><td id="modal-interface"></td></tr>
                                <tr><td><strong>Status:</strong></td><td id="modal-status"></td></tr>
                                <tr><td><strong>Admin State:</strong></td><td id="modal-admin"></td></tr>
                                <tr><td><strong>Speed:</strong></td><td id="modal-speed"></td></tr>
                                <tr><td><strong>Duplex:</strong></td><td id="modal-duplex"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Configuration</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Description:</strong></td><td id="modal-description"></td></tr>
                                <tr><td><strong>VLAN:</strong></td><td id="modal-vlan"></td></tr>
                                <tr><td><strong>Type:</strong></td><td id="modal-type"></td></tr>
                                <tr><td><strong>MTU:</strong></td><td id="modal-mtu"></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="configurePort()">Configure</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let interfacesData = [];
        let autoRefreshInterval;
        let isAutoRefreshing = false;
        let currentPortData = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadSystemInfo();
            loadInterfaces();
        });

        function loadSystemInfo() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show version'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success && data.result && !data.result.error) {
                    try {
                        const version = data.result.ins_api.outputs.output.body;
                        document.getElementById('switch-model').textContent = version.chassis_id || 'Nexus Switch';
                        document.getElementById('switch-version').textContent = version.kickstart_ver_str || 'NX-OS';
                        document.getElementById('switch-uptime').textContent = version.kern_uptm_days + ' days' || 'Unknown';
                    } catch (e) {
                        console.error('Error parsing version data:', e);
                    }
                }
            })
            .catch(error => console.error('Error loading system info:', error));
        }

        function loadInterfaces() {
            document.getElementById('loading-overlay').style.display = 'flex';
            
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
                console.log('API Response:', data);
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (!data.success || !data.result) {
                    throw new Error('Invalid response format');
                }
                
                const interfaces = parseInterfaceData(data.result);
                interfacesData = interfaces;
                displayInterfaces(interfaces);
                updateSummaryStats(interfaces);
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
                
            })
            .catch(error => {
                console.error('Error loading interfaces:', error);
                document.getElementById('loading-overlay').innerHTML = 
                    '<div class="loading-text text-danger"><i class="fas fa-exclamation-triangle"></i> Error: ' + error.message + '</div>';
                
                setTimeout(() => {
                    document.getElementById('loading-overlay').style.display = 'none';
                }, 3000);
            })
            .finally(() => {
                setTimeout(() => {
                    document.getElementById('loading-overlay').style.display = 'none';
                }, 1000);
            });
        }

        function parseInterfaceData(data) {
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
                            interfaces.push({
                                interface: row.interface || '',
                                state: row.state || 'unknown',
                                admin_state: row.admin_state || 'unknown',
                                desc: row.desc || '',
                                vlan: row.vlan || '',
                                speed: row.speed || '',
                                duplex: row.duplex || '',
                                type: row.type || '',
                                mtu: row.mtu || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing interface data:', e);
            }
            
            return interfaces;
        }

        function displayInterfaces(interfaces) {
            const ethernetTop = document.getElementById('ethernet-ports-top');
            const ethernetBottom = document.getElementById('ethernet-ports-bottom');
            const mgmtContainer = document.getElementById('mgmt-ports-container');

            ethernetTop.innerHTML = '';
            ethernetBottom.innerHTML = '';
            mgmtContainer.innerHTML = '';

            // Gather all Ethernet ports
            const ethPorts = interfaces.filter(intf => intf.interface.startsWith('Ethernet'));
            ethPorts.slice(0, 24).forEach(intf => createEthernetPort(intf, ethernetTop));
            ethPorts.slice(24, 48).forEach(intf => createEthernetPort(intf, ethernetBottom));

            interfaces.forEach(intf => {
                if (intf.interface.startsWith('mgmt')) {
                    createMgmtPort(intf, mgmtContainer);
                }
            });
        }

        function createEthernetPort(intf, container) {
            const port = document.createElement('div');
            port.className = 'port';
            port.onclick = () => showPortDetails(intf);
            
            // Determine status class
            let statusClass = 'down';
            if (intf.admin_state === 'down') {
                statusClass = 'admin-down';
            } else if (intf.state === 'up') {
                statusClass = 'up';
            }
            
            port.classList.add(statusClass);
            
            // Extract port number
            const portNum = intf.interface.replace('Ethernet1/', '');
            const speedDisplay = getSpeedDisplay(intf.speed);
            
            port.innerHTML = `
                <div class="port-number">${portNum}</div>
                <div class="port-speed">${speedDisplay}</div>
            `;
            
            container.appendChild(port);
        }

        function createMgmtPort(intf, container) {
            const port = document.createElement('div');
            port.className = 'mgmt-port';
            port.onclick = () => showPortDetails(intf);
            
            if (intf.state === 'up') {
                port.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
            }
            
            port.textContent = intf.interface;
            container.appendChild(port);
        }

        function getSpeedDisplay(speed) {
            if (!speed || speed === 'auto') return 'AUTO';
            if (speed === '1000') return '1G';
            if (speed === '10000') return '10G';
            if (speed === '25000') return '25G';
            if (speed === '40000') return '40G';
            if (speed === '100000') return '100G';
            return speed;
        }

        function updateSummaryStats(interfaces) {
            let up = 0, down = 0;
            // Count all Ethernet ports for total
            const ethPorts = interfaces.filter(intf => intf.interface.startsWith('Ethernet'));
            ethPorts.forEach(intf => {
                if (intf.admin_state === 'down') {
                    // skip admin down for up/down
                } else if (intf.state === 'up') {
                    up++;
                } else {
                    down++;
                }
            });
            document.getElementById('ports-up').textContent = up;
            document.getElementById('ports-down').textContent = down;
            document.getElementById('total-ports').textContent = ethPorts.length;
        }

        function showPortDetails(intf) {
            currentPortData = intf;
            
            document.getElementById('modal-port-name').textContent = intf.interface;
            document.getElementById('modal-interface').textContent = intf.interface;
            document.getElementById('modal-status').innerHTML = 
                intf.state === 'up' ? '<span class="badge bg-success">UP</span>' : '<span class="badge bg-danger">DOWN</span>';
            document.getElementById('modal-admin').innerHTML = 
                intf.admin_state === 'up' ? '<span class="badge bg-success">UP</span>' : '<span class="badge bg-warning">DOWN</span>';
            document.getElementById('modal-speed').textContent = intf.speed || 'auto';
            document.getElementById('modal-duplex').textContent = intf.duplex || 'auto';
            document.getElementById('modal-description').textContent = intf.desc || 'No description';
            document.getElementById('modal-vlan').textContent = intf.vlan || 'N/A';
            document.getElementById('modal-type').textContent = intf.type || 'ethernet';
            document.getElementById('modal-mtu').textContent = intf.mtu || '1500';
            
            new bootstrap.Modal(document.getElementById('portModal')).show();
        }

        function configurePort() {
            if (currentPortData) {
                window.location.href = `interface_config.php?interface=${currentPortData.interface}`;
            }
        }

        function refreshInterfaces() {
            const btn = document.getElementById('refresh-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading';
            btn.disabled = true;
            
            loadInterfaces();
            
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
                btn.innerHTML = '<i class="fas fa-play"></i> Auto';
                btn.className = 'btn btn-warning btn-sm';
            } else {
                autoRefreshInterval = setInterval(loadInterfaces, 10000);
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

