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
            margin: 20px 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
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
            grid-template-columns: repeat(12, 1fr);
            gap: 8px;
            margin: 20px 0;
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
            font-size: 8px;
            margin-bottom: 2px;
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
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        
        .switch-info {
            background: rgba(52, 73, 94, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }
        
        .status-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .status-card {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .status-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .status-label {
            font-size: 12px;
            opacity: 0.8;
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
        
        .refresh-controls {
            position: fixed;
            top: 80px;
            right: 20px;
            background: rgba(52, 73, 94, 0.95);
            padding: 15px;
            border-radius: 10px;
            color: white;
            z-index: 1000;
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Refresh Controls -->
        <div class="refresh-controls">
            <div class="mb-2">
                <button class="btn btn-success btn-sm" onclick="refreshInterfaces()" id="refresh-btn">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <div class="mb-2">
                <button class="btn btn-warning btn-sm" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto
                </button>
            </div>
            <div class="small">
                <span id="last-update">Never</span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-network-wired"></i> Interface Status - Visual Switch</h2>
                
                <!-- Switch Information -->
                <div class="switch-info">
                    <div class="row">
                        <div class="col-md-8">
                            <h4><i class="fas fa-server"></i> Cisco Nexus Switch</h4>
                            <p class="mb-1"><strong>Model:</strong> <span id="switch-model">Loading...</span></p>
                            <p class="mb-1"><strong>Software:</strong> <span id="switch-version">Loading...</span></p>
                            <p class="mb-0"><strong>Uptime:</strong> <span id="switch-uptime">Loading...</span></p>
                        </div>
                        <div class="col-md-4">
                            <div class="status-summary">
                                <div class="status-card">
                                    <div class="status-number text-success" id="ports-up">0</div>
                                    <div class="status-label">UP</div>
                                </div>
                                <div class="status-card">
                                    <div class="status-number text-danger" id="ports-down">0</div>
                                    <div class="status-label">DOWN</div>
                                </div>
                                <div class="status-card">
                                    <div class="status-number text-warning" id="ports-admin-down">0</div>
                                    <div class="status-label">ADMIN</div>
                                </div>
                                <div class="status-card">
                                    <div class="status-number text-info" id="total-ports">0</div>
                                    <div class="status-label">TOTAL</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Switch Chassis -->
                <div class="switch-chassis" id="switch-chassis">
                    <div class="switch-label">CISCO NEXUS</div>
                    
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-text">
                            <i class="fas fa-spinner fa-spin"></i> Loading interfaces from switch...
                        </div>
                    </div>
                    
                    <!-- Ethernet Ports -->
                    <div class="port-panel" id="ethernet-ports">
                        <!-- Ports will be dynamically generated -->
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
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show version'
            })
            .then(response => response.json())
            .then(data => {
                if (data && !data.error) {
                    try {
                        const version = data.ins_api.outputs.output.body;
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
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show interface brief'
            })
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const interfaces = parseInterfaceData(data);
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
            const ethernetContainer = document.getElementById('ethernet-ports');
            const mgmtContainer = document.getElementById('mgmt-ports-container');
            
            ethernetContainer.innerHTML = '';
            mgmtContainer.innerHTML = '';
            
            interfaces.forEach(intf => {
                if (intf.interface.startsWith('Ethernet')) {
                    createEthernetPort(intf, ethernetContainer);
                } else if (intf.interface.startsWith('mgmt')) {
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
            let up = 0, down = 0, adminDown = 0, total = 0;
            
            interfaces.forEach(intf => {
                if (intf.interface.startsWith('Ethernet')) {
                    total++;
                    if (intf.admin_state === 'down') {
                        adminDown++;
                    } else if (intf.state === 'up') {
                        up++;
                    } else {
                        down++;
                    }
                }
            });
            
            document.getElementById('ports-up').textContent = up;
            document.getElementById('ports-down').textContent = down;
            document.getElementById('ports-admin-down').textContent = adminDown;
            document.getElementById('total-ports').textContent = total;
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

