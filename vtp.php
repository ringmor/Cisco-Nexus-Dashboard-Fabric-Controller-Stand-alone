<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTP Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-network-wired"></i> VTP Configuration</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="configureVtp()">
                    <i class="fas fa-cog"></i> Configure VTP
                </button>
            </div>
        </div>

        <!-- VTP Status Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">VTP Mode</h5>
                        <h3 id="vtp-mode">Loading...</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Domain Name</h5>
                        <h6 id="vtp-domain">Loading...</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Version</h5>
                        <h3 id="vtp-version">Loading...</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Revision</h5>
                        <h3 id="vtp-revision">Loading...</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- VTP Detailed Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> VTP Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td><strong>VTP Operating Mode:</strong></td><td id="vtp-operating-mode">Loading...</td></tr>
                            <tr><td><strong>Domain Name:</strong></td><td id="vtp-domain-detail">Loading...</td></tr>
                            <tr><td><strong>VTP Version:</strong></td><td id="vtp-version-detail">Loading...</td></tr>
                            <tr><td><strong>Configuration Revision:</strong></td><td id="vtp-config-revision">Loading...</td></tr>
                            <tr><td><strong>Maximum VLANs:</strong></td><td id="vtp-max-vlans">Loading...</td></tr>
                            <tr><td><strong>Number of VLANs:</strong></td><td id="vtp-num-vlans">Loading...</td></tr>
                            <tr><td><strong>Last Updater:</strong></td><td id="vtp-last-updater">Loading...</td></tr>
                            <tr><td><strong>Last Update:</strong></td><td id="vtp-last-update">Loading...</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> VTP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="vtpConfigForm">
                            <div class="mb-3">
                                <label for="vtp-mode-select" class="form-label">VTP Mode</label>
                                <select class="form-select" id="vtp-mode-select">
                                    <option value="server">Server</option>
                                    <option value="client">Client</option>
                                    <option value="transparent">Transparent</option>
                                    <option value="off">Off</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="vtp-domain-input" class="form-label">Domain Name</label>
                                <input type="text" class="form-control" id="vtp-domain-input" placeholder="Enter domain name">
                            </div>
                            <div class="mb-3">
                                <label for="vtp-version-select" class="form-label">VTP Version</label>
                                <select class="form-select" id="vtp-version-select">
                                    <option value="1">Version 1</option>
                                    <option value="2">Version 2</option>
                                    <option value="3">Version 3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="vtp-password" class="form-label">VTP Password (Optional)</label>
                                <input type="password" class="form-control" id="vtp-password" placeholder="Enter VTP password">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vtp-pruning">
                                    <label class="form-check-label" for="vtp-pruning">
                                        Enable VTP Pruning
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="applyVtpConfig()">
                                Apply Configuration
                            </button>
                            <button type="button" class="btn btn-warning" onclick="resetVtpConfig()">
                                Reset to Default
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- VTP Statistics -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> VTP Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Statistic</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody id="vtp-stats-tbody">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Loading VTP statistics...
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

    <script>
        let vtpData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadVtpStatus();
        });

        function loadVtpStatus() {
            // Load VTP status
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show vtp status'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const vtpStatus = parseVtpStatus(data);
                vtpData = vtpStatus;
                displayVtpStatus(vtpStatus);
                populateConfigForm(vtpStatus);
            })
            .catch(error => {
                console.error('Error loading VTP status:', error);
                displayVtpError(error.message);
            });

            // Load VTP statistics
            loadVtpStatistics();
        }

        function parseVtpStatus(data) {
            const vtp = {
                mode: 'Unknown',
                domain: 'Unknown',
                version: 'Unknown',
                revision: '0',
                operating_mode: 'Unknown',
                max_vlans: '0',
                num_vlans: '0',
                last_updater: 'Unknown',
                last_update: 'Unknown'
            };
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body) {
                        const body = output.body;
                        
                        vtp.mode = body.vtp_mode || 'Unknown';
                        vtp.domain = body.vtp_domain || 'Unknown';
                        vtp.version = body.vtp_version || 'Unknown';
                        vtp.revision = body.vtp_revision || '0';
                        vtp.operating_mode = body.vtp_operating_mode || 'Unknown';
                        vtp.max_vlans = body.vtp_max_vlans || '0';
                        vtp.num_vlans = body.vtp_num_vlans || '0';
                        vtp.last_updater = body.vtp_last_updater || 'Unknown';
                        vtp.last_update = body.vtp_last_update || 'Unknown';
                    }
                }
            } catch (e) {
                console.error('Error parsing VTP status:', e);
            }
            
            return vtp;
        }

        function displayVtpStatus(vtp) {
            // Update overview cards
            document.getElementById('vtp-mode').textContent = vtp.mode;
            document.getElementById('vtp-domain').textContent = vtp.domain;
            document.getElementById('vtp-version').textContent = vtp.version;
            document.getElementById('vtp-revision').textContent = vtp.revision;

            // Update detailed information
            document.getElementById('vtp-operating-mode').textContent = vtp.operating_mode;
            document.getElementById('vtp-domain-detail').textContent = vtp.domain;
            document.getElementById('vtp-version-detail').textContent = vtp.version;
            document.getElementById('vtp-config-revision').textContent = vtp.revision;
            document.getElementById('vtp-max-vlans').textContent = vtp.max_vlans;
            document.getElementById('vtp-num-vlans').textContent = vtp.num_vlans;
            document.getElementById('vtp-last-updater').textContent = vtp.last_updater;
            document.getElementById('vtp-last-update').textContent = vtp.last_update;
        }

        function populateConfigForm(vtp) {
            document.getElementById('vtp-mode-select').value = vtp.mode.toLowerCase();
            document.getElementById('vtp-domain-input').value = vtp.domain !== 'Unknown' ? vtp.domain : '';
            document.getElementById('vtp-version-select').value = vtp.version;
        }

        function displayVtpError(error) {
            const elements = ['vtp-mode', 'vtp-domain', 'vtp-version', 'vtp-revision'];
            elements.forEach(id => {
                document.getElementById(id).textContent = 'Error';
            });
        }

        function loadVtpStatistics() {
            // Mock VTP statistics since actual command may vary
            const stats = [
                { name: 'Summary Advertisements Sent', value: '0', description: 'Number of summary advertisements sent' },
                { name: 'Summary Advertisements Received', value: '0', description: 'Number of summary advertisements received' },
                { name: 'Subset Advertisements Sent', value: '0', description: 'Number of subset advertisements sent' },
                { name: 'Subset Advertisements Received', value: '0', description: 'Number of subset advertisements received' },
                { name: 'Advertisement Requests Sent', value: '0', description: 'Number of advertisement requests sent' },
                { name: 'Advertisement Requests Received', value: '0', description: 'Number of advertisement requests received' },
                { name: 'Configuration Errors', value: '0', description: 'Number of configuration errors detected' },
                { name: 'Checksum Errors', value: '0', description: 'Number of checksum errors detected' }
            ];

            displayVtpStatistics(stats);
        }

        function displayVtpStatistics(stats) {
            const tbody = document.getElementById('vtp-stats-tbody');
            tbody.innerHTML = '';

            stats.forEach(stat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${stat.name}</strong></td>
                    <td><span class="badge bg-primary">${stat.value}</span></td>
                    <td><small class="text-muted">${stat.description}</small></td>
                `;
                tbody.appendChild(row);
            });
        }

        function applyVtpConfig() {
            const mode = document.getElementById('vtp-mode-select').value;
            const domain = document.getElementById('vtp-domain-input').value;
            const version = document.getElementById('vtp-version-select').value;
            const password = document.getElementById('vtp-password').value;
            const pruning = document.getElementById('vtp-pruning').checked;

            let commands = [];
            
            // Build VTP configuration commands
            commands.push(`vtp mode ${mode}`);
            
            if (domain) {
                commands.push(`vtp domain ${domain}`);
            }
            
            commands.push(`vtp version ${version}`);
            
            if (password) {
                commands.push(`vtp password ${password}`);
            }
            
            if (pruning) {
                commands.push(`vtp pruning`);
            } else {
                commands.push(`no vtp pruning`);
            }

            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error applying VTP configuration: ' + data.error);
                } else {
                    alert('VTP configuration applied successfully');
                    loadVtpStatus();
                }
            })
            .catch(error => {
                alert('Error applying VTP configuration: ' + error.message);
            });
        }

        function resetVtpConfig() {
            if (confirm('Are you sure you want to reset VTP configuration to default?')) {
                const commands = [
                    'no vtp mode',
                    'no vtp domain',
                    'no vtp password',
                    'no vtp pruning'
                ];

                const configCmd = commands.join(' ; ');
                
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error resetting VTP configuration: ' + data.error);
                    } else {
                        alert('VTP configuration reset successfully');
                        loadVtpStatus();
                    }
                })
                .catch(error => {
                    alert('Error resetting VTP configuration: ' + error.message);
                });
            }
        }

        function configureVtp() {
            // Scroll to configuration section
            document.querySelector('#vtpConfigForm').scrollIntoView({ behavior: 'smooth' });
        }

        function refreshData() {
            loadVtpStatus();
        }
    </script>
</body>
</html>

