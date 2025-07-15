<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Information - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-server"></i> System Information</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-nexus btn-sm" onclick="refreshSystemInfo()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportSystemInfo()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                <!-- System Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary">System Uptime</h5>
                                <h3 class="text-success" id="system-uptime">Loading...</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Software Version</h5>
                                <h6 class="text-info" id="software-version">Loading...</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Hardware Model</h5>
                                <h6 class="text-warning" id="hardware-model">Loading...</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Serial Number</h5>
                                <h6 class="text-secondary" id="serial-number">Loading...</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> System Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Hostname:</strong></td>
                                            <td id="hostname">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Domain Name:</strong></td>
                                            <td id="domain-name">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>System Image:</strong></td>
                                            <td id="system-image">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Boot Image:</strong></td>
                                            <td id="boot-image">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kickstart Version:</strong></td>
                                            <td id="kickstart-version">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>System Version:</strong></td>
                                            <td id="system-version">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kernel Uptime:</strong></td>
                                            <td id="kernel-uptime">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Reset:</strong></td>
                                            <td id="last-reset">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-microchip"></i> Hardware Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Chassis:</strong></td>
                                            <td id="chassis">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>CPU:</strong></td>
                                            <td id="cpu-info">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Memory:</strong></td>
                                            <td id="memory-info">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Flash:</strong></td>
                                            <td id="flash-info">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bootflash:</strong></td>
                                            <td id="bootflash-info">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Power Supplies:</strong></td>
                                            <td id="power-supplies">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fans:</strong></td>
                                            <td id="fans-info">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Temperature:</strong></td>
                                            <td id="temperature-info">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-puzzle-piece"></i> Module Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Ports</th>
                                        <th>Model</th>
                                        <th>Status</th>
                                        <th>Serial Number</th>
                                        <th>MAC Address</th>
                                        <th>Hardware Version</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-tbody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading module information...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- License Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-certificate"></i> License Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Feature</th>
                                        <th>License Type</th>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody id="licenses-tbody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading license information...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Process Information -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Running Processes</h5>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshProcesses()">
                                <i class="fas fa-sync-alt"></i> Refresh Processes
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>PID</th>
                                        <th>Process Name</th>
                                        <th>CPU %</th>
                                        <th>Memory %</th>
                                        <th>Status</th>
                                        <th>Runtime</th>
                                    </tr>
                                </thead>
                                <tbody id="processes-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading process information...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        // Page-specific refresh function
        window.pageRefreshFunction = loadSystemInfo;

        // Load system information on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSystemInfo();
        });

        function loadSystemInfo() {
            // Load system information using mock data
            setTimeout(() => {
                displaySystemInfo();
                displayModuleInfo();
                displayLicenseInfo();
                displayProcessInfo();
            }, 1000);
        }

        function displaySystemInfo() {
            // Mock system information
            const systemInfo = {
                uptime: '15 days, 8 hours, 23 minutes',
                softwareVersion: 'NX-OS 9.3(8)',
                hardwareModel: 'Nexus 9000 Series',
                serialNumber: 'FDO24261234',
                hostname: 'nexus-switch-01',
                domainName: 'company.local',
                systemImage: 'bootflash:///nxos.9.3.8.bin',
                bootImage: 'bootflash:///n9000-dk9.9.3.8.bin',
                kickstartVersion: '9.3(8)',
                systemVersion: '9.3(8)',
                kernelUptime: '15 days, 8 hours, 23 minutes',
                lastReset: 'Power-on reset',
                chassis: 'Nexus9000 C9300-48T',
                cpu: 'Intel(R) Xeon(R) CPU @ 2.40GHz (4 cores)',
                memory: '16 GB total, 8.2 GB used, 7.8 GB free',
                flash: '64 GB total, 12.5 GB used, 51.5 GB free',
                bootflash: '64 GB total, 12.5 GB used, 51.5 GB free',
                powerSupplies: '2 installed, 2 OK',
                fans: '6 installed, 6 OK',
                temperature: '42°C (Normal)'
            };

            // Update system overview cards
            document.getElementById('system-uptime').textContent = systemInfo.uptime;
            document.getElementById('software-version').textContent = systemInfo.softwareVersion;
            document.getElementById('hardware-model').textContent = systemInfo.hardwareModel;
            document.getElementById('serial-number').textContent = systemInfo.serialNumber;

            // Update system details
            document.getElementById('hostname').textContent = systemInfo.hostname;
            document.getElementById('domain-name').textContent = systemInfo.domainName;
            document.getElementById('system-image').textContent = systemInfo.systemImage;
            document.getElementById('boot-image').textContent = systemInfo.bootImage;
            document.getElementById('kickstart-version').textContent = systemInfo.kickstartVersion;
            document.getElementById('system-version').textContent = systemInfo.systemVersion;
            document.getElementById('kernel-uptime').textContent = systemInfo.kernelUptime;
            document.getElementById('last-reset').textContent = systemInfo.lastReset;

            // Update hardware information
            document.getElementById('chassis').textContent = systemInfo.chassis;
            document.getElementById('cpu-info').textContent = systemInfo.cpu;
            document.getElementById('memory-info').textContent = systemInfo.memory;
            document.getElementById('flash-info').textContent = systemInfo.flash;
            document.getElementById('bootflash-info').textContent = systemInfo.bootflash;
            document.getElementById('power-supplies').textContent = systemInfo.powerSupplies;
            document.getElementById('fans-info').textContent = systemInfo.fans;
            document.getElementById('temperature-info').textContent = systemInfo.temperature;
        }

        function displayModuleInfo() {
            const modules = [
                {
                    module: '1',
                    ports: '48',
                    model: 'N9K-C9300-48T',
                    status: 'OK',
                    serialNumber: 'FDO24261234',
                    macAddress: '00:11:22:33:44:55',
                    hardwareVersion: '1.0'
                },
                {
                    module: '2',
                    ports: '4',
                    model: 'N9K-C9300-4Q',
                    status: 'OK',
                    serialNumber: 'FDO24261235',
                    macAddress: '00:11:22:33:44:56',
                    hardwareVersion: '1.0'
                }
            ];

            const tbody = document.getElementById('modules-tbody');
            tbody.innerHTML = '';

            modules.forEach(module => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${module.module}</strong></td>
                    <td>${module.ports}</td>
                    <td>${module.model}</td>
                    <td><span class="badge bg-success">${module.status}</span></td>
                    <td>${module.serialNumber}</td>
                    <td><code>${module.macAddress}</code></td>
                    <td>${module.hardwareVersion}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayLicenseInfo() {
            const licenses = [
                {
                    feature: 'LAN_ENTERPRISE_SERVICES_PKG',
                    type: 'Permanent',
                    status: 'In Use',
                    count: '1',
                    expiry: 'Never'
                },
                {
                    feature: 'SECURITY_PKG',
                    type: 'Permanent',
                    status: 'In Use',
                    count: '1',
                    expiry: 'Never'
                },
                {
                    feature: 'VDC_PKG',
                    type: 'Permanent',
                    status: 'Not in Use',
                    count: '4',
                    expiry: 'Never'
                },
                {
                    feature: 'MPLS_PKG',
                    type: 'Evaluation',
                    status: 'In Use',
                    count: '1',
                    expiry: '2025-12-31'
                }
            ];

            const tbody = document.getElementById('licenses-tbody');
            tbody.innerHTML = '';

            licenses.forEach(license => {
                const row = document.createElement('tr');
                const statusClass = license.status === 'In Use' ? 'bg-success' : 
                                  license.status === 'Not in Use' ? 'bg-secondary' : 'bg-warning';
                
                row.innerHTML = `
                    <td><strong>${license.feature}</strong></td>
                    <td>${license.type}</td>
                    <td><span class="badge ${statusClass}">${license.status}</span></td>
                    <td>${license.count}</td>
                    <td>${license.expiry}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function displayProcessInfo() {
            const processes = [
                { pid: '1', name: 'init', cpu: '0.0', memory: '0.1', status: 'S', runtime: '15d 8h' },
                { pid: '2345', name: 'bgp', cpu: '2.1', memory: '5.2', status: 'R', runtime: '15d 8h' },
                { pid: '3456', name: 'ospf', cpu: '1.8', memory: '3.1', status: 'S', runtime: '15d 8h' },
                { pid: '4567', name: 'vshd', cpu: '0.5', memory: '2.3', status: 'S', runtime: '15d 8h' },
                { pid: '5678', name: 'netstack', cpu: '3.2', memory: '8.7', status: 'R', runtime: '15d 8h' },
                { pid: '6789', name: 'adjmgr', cpu: '0.3', memory: '1.5', status: 'S', runtime: '15d 8h' },
                { pid: '7890', name: 'urib', cpu: '1.1', memory: '4.2', status: 'S', runtime: '15d 8h' },
                { pid: '8901', name: 'mrib', cpu: '0.8', memory: '2.8', status: 'S', runtime: '15d 8h' },
                { pid: '9012', name: 'l2rib', cpu: '0.6', memory: '3.5', status: 'S', runtime: '15d 8h' },
                { pid: '1023', name: 'icmpv6', cpu: '0.1', memory: '0.8', status: 'S', runtime: '15d 8h' }
            ];

            const tbody = document.getElementById('processes-tbody');
            tbody.innerHTML = '';

            processes.forEach(process => {
                const row = document.createElement('tr');
                const statusClass = process.status === 'R' ? 'text-success' : 'text-secondary';
                
                row.innerHTML = `
                    <td><code>${process.pid}</code></td>
                    <td><strong>${process.name}</strong></td>
                    <td>${process.cpu}%</td>
                    <td>${process.memory}%</td>
                    <td><span class="${statusClass}">${process.status}</span></td>
                    <td>${process.runtime}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function refreshSystemInfo() {
            showAlert('Refreshing system information...', 'info');
            loadSystemInfo();
        }

        function refreshProcesses() {
            showAlert('Refreshing process information...', 'info');
            displayProcessInfo();
        }

        function exportSystemInfo() {
            const systemData = {
                timestamp: new Date().toISOString(),
                hostname: document.getElementById('hostname').textContent,
                uptime: document.getElementById('system-uptime').textContent,
                version: document.getElementById('software-version').textContent,
                model: document.getElementById('hardware-model').textContent,
                serial: document.getElementById('serial-number').textContent
            };
            
            const content = JSON.stringify(systemData, null, 2);
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            exportConfig(content, `system-info-${timestamp}.json`);
        }
    </script>
</body>
</html>

