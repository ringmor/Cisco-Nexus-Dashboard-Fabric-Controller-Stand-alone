<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VLAN Configuration - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-sitemap"></i> VLAN Configuration</h2>
            <div>
                <button class="btn btn-success" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="createVlan()">
                    <i class="fas fa-plus"></i> Create VLAN
                </button>
            </div>
        </div>

        <!-- VLAN Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total VLANs</h5>
                        <h2 id="total-vlans">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active VLANs</h5>
                        <h2 id="active-vlans">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Suspended VLANs</h5>
                        <h2 id="suspended-vlans">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">With SVIs</h5>
                        <h2 id="svi-vlans">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- VLAN Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>VLAN ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Ports</th>
                        <th>SVI</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vlan-tbody">
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="spinner-border spinner-border-sm me-2"></div>
                            Loading VLAN configuration...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit VLAN Modal -->
    <div class="modal fade" id="vlanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vlanModalTitle">Create VLAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="vlanForm">
                        <div class="mb-3">
                            <label for="vlan-id" class="form-label">VLAN ID</label>
                            <input type="number" class="form-control" id="vlan-id" min="1" max="4094" required>
                        </div>
                        <div class="mb-3">
                            <label for="vlan-name" class="form-label">VLAN Name</label>
                            <input type="text" class="form-control" id="vlan-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="vlan-state" class="form-label">State</label>
                            <select class="form-select" id="vlan-state">
                                <option value="active">Active</option>
                                <option value="suspend">Suspend</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create-svi">
                                <label class="form-check-label" for="create-svi">
                                    Create SVI Interface
                                </label>
                            </div>
                        </div>
                        <div id="svi-config" style="display: none;">
                            <div class="mb-3">
                                <label for="svi-ip" class="form-label">SVI IP Address</label>
                                <input type="text" class="form-control" id="svi-ip" placeholder="192.168.1.1/24">
                            </div>
                            <div class="mb-3">
                                <label for="svi-description" class="form-label">SVI Description</label>
                                <input type="text" class="form-control" id="svi-description">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveVlan()">Save VLAN</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let vlansData = [];
        let editingVlan = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadVlans();
            
            // Show/hide SVI config
            document.getElementById('create-svi').addEventListener('change', function() {
                const sviConfig = document.getElementById('svi-config');
                sviConfig.style.display = this.checked ? 'block' : 'none';
            });
        });

        function loadVlans() {
            document.getElementById('vlan-tbody').innerHTML = 
                '<tr><td colspan="7" class="text-center"><div class="spinner-border spinner-border-sm me-2"></div>Loading VLANs...</td></tr>';

            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=show vlan brief'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const vlans = parseVlanData(data);
                vlansData = vlans;
                displayVlans(vlans);
                updateSummaryCards(vlans);
            })
            .catch(error => {
                console.error('Error loading VLANs:', error);
                document.getElementById('vlan-tbody').innerHTML = 
                    '<tr><td colspan="7" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
            });
        }

        function parseVlanData(data) {
            const vlans = [];
            
            try {
                if (data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    
                    if (output.body && output.body.TABLE_vlanbriefxbrief) {
                        let rows = output.body.TABLE_vlanbriefxbrief.ROW_vlanbriefxbrief;
                        
                        if (!Array.isArray(rows)) {
                            rows = [rows];
                        }
                        
                        rows.forEach(row => {
                            vlans.push({
                                vlan_id: row.vlanshowbr_vlanid || '',
                                name: row.vlanshowbr_vlanname || '',
                                state: row.vlanshowbr_vlanstate || 'unknown',
                                type: row.vlanshowbr_vlantype || 'enet',
                                ports: row.vlanshowbr_vlanports || '',
                                svi_exists: false // Will be checked separately
                            });
                        });
                    }
                }
            } catch (e) {
                console.error('Error parsing VLAN data:', e);
            }
            
            return vlans;
        }

        function displayVlans(vlans) {
            const tbody = document.getElementById('vlan-tbody');
            tbody.innerHTML = '';

            if (!vlans || vlans.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No VLANs configured</td></tr>';
                return;
            }

            vlans.forEach(vlan => {
                const row = document.createElement('tr');
                
                const stateBadge = vlan.state === 'active' ? 
                    '<span class="badge bg-success">Active</span>' : 
                    '<span class="badge bg-warning">Suspended</span>';
                
                const sviBadge = vlan.svi_exists ? 
                    '<span class="badge bg-info">Yes</span>' : 
                    '<span class="badge bg-secondary">No</span>';

                const portCount = vlan.ports ? vlan.ports.split(',').length : 0;

                row.innerHTML = `
                    <td><strong>${vlan.vlan_id}</strong></td>
                    <td>${vlan.name}</td>
                    <td>${stateBadge}</td>
                    <td><small>${vlan.type}</small></td>
                    <td><small>${portCount} ports</small></td>
                    <td>${sviBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editVlan('${vlan.vlan_id}')">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="assignPorts('${vlan.vlan_id}')">
                            Assign
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteVlan('${vlan.vlan_id}')">
                            Delete
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updateSummaryCards(vlans) {
            let total = vlans.length;
            let active = 0;
            let suspended = 0;
            let withSvi = 0;

            vlans.forEach(vlan => {
                if (vlan.state === 'active') active++;
                else suspended++;
                
                if (vlan.svi_exists) withSvi++;
            });

            document.getElementById('total-vlans').textContent = total;
            document.getElementById('active-vlans').textContent = active;
            document.getElementById('suspended-vlans').textContent = suspended;
            document.getElementById('svi-vlans').textContent = withSvi;
        }

        function createVlan() {
            editingVlan = null;
            document.getElementById('vlanModalTitle').textContent = 'Create VLAN';
            document.getElementById('vlanForm').reset();
            document.getElementById('svi-config').style.display = 'none';
            new bootstrap.Modal(document.getElementById('vlanModal')).show();
        }

        function editVlan(vlanId) {
            const vlan = vlansData.find(v => v.vlan_id === vlanId);
            if (!vlan) return;

            editingVlan = vlan;
            document.getElementById('vlanModalTitle').textContent = 'Edit VLAN ' + vlanId;
            document.getElementById('vlan-id').value = vlan.vlan_id;
            document.getElementById('vlan-name').value = vlan.name;
            document.getElementById('vlan-state').value = vlan.state;
            
            new bootstrap.Modal(document.getElementById('vlanModal')).show();
        }

        function saveVlan() {
            const vlanId = document.getElementById('vlan-id').value;
            const vlanName = document.getElementById('vlan-name').value;
            const vlanState = document.getElementById('vlan-state').value;
            const createSvi = document.getElementById('create-svi').checked;
            const sviIp = document.getElementById('svi-ip').value;
            const sviDescription = document.getElementById('svi-description').value;

            if (!vlanId || !vlanName) {
                alert('Please fill in all required fields');
                return;
            }

            // Build configuration commands
            let commands = [];
            
            if (editingVlan) {
                // Edit existing VLAN
                commands.push(`vlan ${vlanId}`);
                commands.push(`name ${vlanName}`);
                commands.push(`state ${vlanState}`);
            } else {
                // Create new VLAN
                commands.push(`vlan ${vlanId}`);
                commands.push(`name ${vlanName}`);
                commands.push(`state ${vlanState}`);
            }

            if (createSvi && sviIp) {
                commands.push(`interface vlan${vlanId}`);
                if (sviDescription) {
                    commands.push(`description ${sviDescription}`);
                }
                commands.push(`ip address ${sviIp}`);
                commands.push(`no shutdown`);
            }

            // Send configuration to switch
            const configCmd = commands.join(' ; ');
            
            fetch('nxapi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'cmd=' + encodeURIComponent(configCmd) + '&type=config'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error saving VLAN: ' + data.error);
                } else {
                    alert('VLAN saved successfully');
                    bootstrap.Modal.getInstance(document.getElementById('vlanModal')).hide();
                    loadVlans();
                }
            })
            .catch(error => {
                alert('Error saving VLAN: ' + error.message);
            });
        }

        function assignPorts(vlanId) {
            alert('Assign ports to VLAN ' + vlanId + ' - Feature coming soon!');
        }

        function deleteVlan(vlanId) {
            if (confirm('Are you sure you want to delete VLAN ' + vlanId + '?')) {
                fetch('nxapi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cmd=no vlan ' + vlanId + '&type=config'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error deleting VLAN: ' + data.error);
                    } else {
                        alert('VLAN deleted successfully');
                        loadVlans();
                    }
                })
                .catch(error => {
                    alert('Error deleting VLAN: ' + error.message);
                });
            }
        }

        function refreshData() {
            loadVlans();
        }
    </script>
</body>
</html>

