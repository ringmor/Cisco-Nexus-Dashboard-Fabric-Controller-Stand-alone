<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Port Channels - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-link"></i> Port Channel Status</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="createPortChannel()">
                    <i class="fas fa-plus"></i> Create Port Channel
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Port Channels</h5>
                        <h2 id="total-po">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active</h5>
                        <h2 id="active-po">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Suspended</h5>
                        <h2 id="suspended-po">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Members</h5>
                        <h2 id="total-members">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Port Channel Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Port Channel</th>
                        <th>Status</th>
                        <th>Protocol</th>
                        <th>Member Ports</th>
                        <th>Load Balance</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="portchannel-tbody">
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="spinner-border spinner-border-sm me-2"></div>
                            Loading port channels...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPortChannels();
        });

        function loadPortChannels() {
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'execute_command',
                    command: 'show port-channel summary'
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
                
                const portChannels = parsePortChannelData(data.result);
                displayPortChannels(portChannels);
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            })
            .catch(error => {
                console.error('Error loading port channels:', error);
                document.getElementById('port-channels-tbody').innerHTML = 
                    '<tr><td colspan="6" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parsePortChannelData(data) {
            const portChannels = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_channel) {
                        let rows = output.body.TABLE_channel.ROW_channel;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            portChannels.push({
                                interface: row.interface || '',
                                status: row.status || 'unknown',
                                protocol: row.protocol || 'none',
                                members: row.members || '',
                                load_balance: row.load_balance || 'src-dst-ip',
                                description: row.desc || ''
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing port channel data:', e);
            }
            
            return portChannels;
        }

        function displayPortChannels(portChannels) {
            const tbody = document.getElementById('portchannel-tbody');
            tbody.innerHTML = '';

            if (!portChannels || portChannels.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No port channels configured</td></tr>';
                return;
            }

            portChannels.forEach(po => {
                const row = document.createElement('tr');
                
                const statusBadge = po.status === 'up' ? 
                    '<span class="badge bg-success">UP</span>' : 
                    '<span class="badge bg-danger">DOWN</span>';
                
                const protocolBadge = po.protocol === 'LACP' ? 
                    '<span class="badge bg-info">LACP</span>' : 
                    '<span class="badge bg-secondary">Static</span>';

                row.innerHTML = `
                    <td><strong>${po.interface}</strong></td>
                    <td>${statusBadge}</td>
                    <td>${protocolBadge}</td>
                    <td><small>${po.members || 'No members'}</small></td>
                    <td><small>${po.load_balance}</small></td>
                    <td>${po.description || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="configurePortChannel('${po.interface}')">
                            Configure
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePortChannel('${po.interface}')">
                            Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updateSummaryCards(portChannels) {
            let total = portChannels.length;
            let active = 0;
            let suspended = 0;
            let totalMembers = 0;

            portChannels.forEach(po => {
                if (po.status === 'up') active++;
                else suspended++;
                
                if (po.members) {
                    totalMembers += po.members.split(',').length;
                }
            });

            document.getElementById('total-po').textContent = total;
            document.getElementById('active-po').textContent = active;
            document.getElementById('suspended-po').textContent = suspended;
            document.getElementById('total-members').textContent = totalMembers;
        }

        function refreshData() {
            loadPortChannels();
        }

        function createPortChannel() {
            alert('Create Port Channel feature - Coming soon!');
        }

        function configurePortChannel(poInterface) {
            alert('Configure ' + poInterface + ' - Coming soon!');
        }

        function deletePortChannel(poInterface) {
            if (confirm('Are you sure you want to delete ' + poInterface + '?')) {
                alert('Delete ' + poInterface + ' - Coming soon!');
            }
        }
    </script>
</body>
</html>

