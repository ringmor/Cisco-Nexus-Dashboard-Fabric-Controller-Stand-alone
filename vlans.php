<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VLAN Management - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-sitemap"></i> VLAN Management</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="showCreateVlanModal()">
                            <i class="fas fa-plus"></i> Create VLAN
                        </button>
                        <button class="btn btn-nexus" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- VLAN Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="total-vlans">-</div>
                            <div class="metric-label">Total VLANs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-success" id="active-vlans">-</div>
                            <div class="metric-label">Active VLANs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-info" id="svi-vlans">-</div>
                            <div class="metric-label">VLANs with SVI</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value text-warning" id="unused-vlans">-</div>
                            <div class="metric-label">Unused VLANs</div>
                        </div>
                    </div>
                </div>

                <!-- VLAN Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> VLAN Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="vlans-table">
                                <thead>
                                    <tr>
                                        <th>VLAN ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Ports</th>
                                        <th>SVI</th>
                                        <th>IP Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="vlans-tbody">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="loading-spinner"></div> Loading VLANs...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                                <form id="vlan-form">
                                    <input type="hidden" id="vlan-action" value="create">
                                    <input type="hidden" id="original-vlan-id">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">VLAN ID *</label>
                                        <input type="number" class="form-control" id="vlan-id" 
                                               min="1" max="4094" required>
                                        <div class="form-text">Valid range: 1-4094</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">VLAN Name *</label>
                                        <input type="text" class="form-control" id="vlan-name" 
                                               maxlength="32" required>
                                        <div class="form-text">Maximum 32 characters</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <select class="form-select" id="vlan-state">
                                            <option value="active">Active</option>
                                            <option value="suspend">Suspend</option>
                                        </select>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="create-svi">
                                        <label class="form-check-label" for="create-svi">
                                            Create SVI (Switch Virtual Interface)
                                        </label>
                                    </div>

                                    <div id="svi-config" style="display: none;">
                                        <hr>
                                        <h6>SVI Configuration</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label">IP Address</label>
                                                    <input type="text" class="form-control" id="svi-ip" 
                                                           placeholder="192.168.1.1">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Subnet</label>
                                                    <select class="form-select" id="svi-subnet">
                                                        <option value="24">/24</option>
                                                        <option value="25">/25</option>
                                                        <option value="26">/26</option>
                                                        <option value="27">/27</option>
                                                        <option value="28">/28</option>
                                                        <option value="29">/29</option>
                                                        <option value="30">/30</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" id="svi-description" 
                                                   placeholder="SVI description">
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="svi-no-shutdown" checked>
                                            <label class="form-check-label" for="svi-no-shutdown">
                                                Enable SVI (no shutdown)
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-nexus" onclick="saveVlan()">
                                    <span id="save-btn-text">Create VLAN</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VLAN Assignment Modal -->
                <div class="modal fade" id="assignmentModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">VLAN Port Assignment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="assignment-vlan-id">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Available Interfaces</h6>
                                        <div class="border rounded p-3" style="height: 300px; overflow-y: auto;">
                                            <div id="available-interfaces">
                                                <div class="text-center">
                                                    <div class="loading-spinner"></div> Loading...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Assigned Interfaces</h6>
                                        <div class="border rounded p-3" style="height: 300px; overflow-y: auto;">
                                            <div id="assigned-interfaces">
                                                <!-- Assigned interfaces will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Bulk Assignment</label>
                                            <input type="text" class="form-control" id="bulk-interfaces" 
                                                   placeholder="e.g., Eth1/1-10, Eth1/15">
                                            <div class="form-text">Comma-separated list or ranges</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">&nbsp;</label>
                                            <div>
                                                <button class="btn btn-outline-primary" onclick="bulkAssignVlan()">
                                                    <i class="fas fa-plus"></i> Bulk Assign
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-nexus" onclick="applyVlanAssignments()">
                                    Apply Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        let vlansData = [];
        let interfacesData = [];
        let vlanAssignments = {};

        // Page-specific refresh function
        window.pageRefreshFunction = loadVlans;

        // Load VLANs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadVlans();
            loadInterfaces();
            setupSviToggle();
        });

        function loadVlans() {
            executeCommand('show vlan brief', function(data) {
                if (data && data.ins_api && data.ins_api.outputs && data.ins_api.outputs.output) {
                    const output = data.ins_api.outputs.output;
                    if (output.body && output.body.TABLE_vlanbriefxbrief) {
                        vlansData = output.body.TABLE_vlanbriefxbrief.ROW_vlanbriefxbrief || [];
                        if (!Array.isArray(vlansData)) {
                            vlansData = [vlansData];
                        }
                    }
                } else {
                    // Mock data for demonstration
                    vlansData = generateMockVlanData();
                }
                displayVlans();
                updateSummaryCards();
            });
        }

        // Helper to expand a VLAN list string like "1-5,10,20-22" to an array of strings
        function expandVlanList(vlanListStr) {
            const result = [];
            if (!vlanListStr) return result;
            vlanListStr.split(',').forEach(part => {
                if (part.includes('-')) {
                    const [start, end] = part.split('-').map(Number);
                    for (let i = start; i <= end; i++) {
                        result.push(i.toString());
                    }
                } else {
                    result.push(part.trim());
                }
            });
            return result;
        }

        // Use show interface switchport output for accurate port-to-VLAN mapping
        function getAssignedPorts(vlanId) {
            // interfacesData should be from show interface switchport
            return interfacesData.filter(intf => {
                if (intf.oper_mode === 'access') {
                    return intf.access_vlan === vlanId;
                } else if (intf.oper_mode === 'trunk') {
                    const vlans = expandVlanList(intf.trunk_vlans);
                    return vlans.includes(vlanId);
                }
                return false;
            });
        }

        function checkSviExists(vlanId) {
            // Mock check - in real implementation, this would check for SVI existence
            return ['10', '20', '100'].includes(vlanId);
        }

        function getSviIpAddress(vlanId) {
            // Mock IP addresses - in real implementation, this would get actual SVI IPs
            const sviIps = {
                '10': '192.168.10.1/24',
                '20': '192.168.20.1/24',
                '100': '192.168.100.1/24'
            };
            return sviIps[vlanId];
        }

        function displayVlans() {
            const tbody = document.getElementById('vlans-tbody');
            tbody.innerHTML = '';

            vlansData.forEach(vlan => {
                const row = document.createElement('tr');
                // Use dash field names for real API output, fallback to underscore for mock/demo
                const vlanId = vlan['vlanshowbr-vlanid'] || vlan.vlanshowbr_vlanid || vlan.vlan_id;
                const vlanName = vlan['vlanshowbr-vlanname'] || vlan.vlanshowbr_vlanname || vlan.vlan_name;
                const vlanState = vlan['vlanshowbr-vlanstate'] || vlan.vlanshowbr_vlanstate || vlan.vlan_state;
                // Get assigned ports using new logic
                const assignedPorts = getAssignedPorts(vlanId);
                const portCount = assignedPorts.length;
                const portListHtml = assignedPorts.map(p => `<span class='badge bg-light text-dark me-1'>${p.interface}</span>`).join(' ');
                const hasSvi = checkSviExists(vlanId);
                const sviIp = getSviIpAddress(vlanId);
                row.innerHTML = `
                    <td><span class="vlan-tag vlan-active">${vlanId}</span></td>
                    <td><strong>${vlanName}</strong></td>
                    <td>${formatVlanStatus(vlanState)}</td>
                    <td>Ethernet</td>
                    <td>
                        <span class="badge bg-secondary">${portCount} ports</span>
                        <button class="btn btn-sm btn-outline-info ms-1" 
                                onclick="showVlanAssignment('${vlanId}', '${vlanName}')"
                                data-bs-toggle="tooltip" title="Manage Port Assignment">
                            <i class="fas fa-network-wired"></i>
                        </button>
                        <div class="mt-1">${portListHtml || '<span class=\'text-muted\'>None</span>'}</div>
                    </td>
                    <td>
                        ${hasSvi ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-muted"></i>'}
                        <button class="btn btn-sm btn-outline-primary ms-1" 
                                onclick="manageSvi('${vlanId}', '${vlanName}')"
                                data-bs-toggle="tooltip" title="Manage SVI">
                            <i class="fas fa-cog"></i>
                        </button>
                    </td>
                    <td>${sviIp || '--'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editVlan('${vlanId}', '${vlanName}', '${vlanState}')"
                                data-bs-toggle="tooltip" title="Edit VLAN">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${vlanId !== '1' ? `
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="deleteVlan('${vlanId}', '${vlanName}')"
                                data-bs-toggle="tooltip" title="Delete VLAN">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
            initializeTooltips();
        }

        function updateSummaryCards() {
            const total = vlansData.length;
            const active = vlansData.filter(v => {
                const state = v['vlanshowbr-vlanstate'] || v.vlanshowbr_vlanstate || v.vlan_state;
                return state === 'active';
            }).length;
            const withSvi = vlansData.filter(v => {
                const vlanId = v['vlanshowbr-vlanid'] || v.vlanshowbr_vlanid || v.vlan_id;
                return checkSviExists(vlanId);
            }).length;
            const unused = vlansData.filter(v => {
                const vlanId = v['vlanshowbr-vlanid'] || v.vlanshowbr_vlanid || v.vlan_id;
                const assignedPorts = getAssignedPorts(vlanId);
                return assignedPorts.length === 0;
            }).length;

            document.getElementById('total-vlans').textContent = total;
            document.getElementById('active-vlans').textContent = active;
            document.getElementById('svi-vlans').textContent = withSvi;
            document.getElementById('unused-vlans').textContent = unused;
        }

        function showCreateVlanModal() {
            document.getElementById('vlan-action').value = 'create';
            document.getElementById('vlanModalTitle').textContent = 'Create VLAN';
            document.getElementById('save-btn-text').textContent = 'Create VLAN';
            
            // Reset form
            document.getElementById('vlan-form').reset();
            document.getElementById('vlan-id').disabled = false;
            document.getElementById('create-svi').checked = false;
            document.getElementById('svi-config').style.display = 'none';
            
            const modal = new bootstrap.Modal(document.getElementById('vlanModal'));
            modal.show();
        }

        function editVlan(vlanId, vlanName, vlanState) {
            document.getElementById('vlan-action').value = 'edit';
            document.getElementById('vlanModalTitle').textContent = `Edit VLAN ${vlanId}`;
            document.getElementById('save-btn-text').textContent = 'Update VLAN';
            
            // Populate form
            document.getElementById('vlan-id').value = vlanId;
            document.getElementById('vlan-id').disabled = true;
            document.getElementById('original-vlan-id').value = vlanId;
            document.getElementById('vlan-name').value = vlanName;
            document.getElementById('vlan-state').value = vlanState;
            
            // Check if SVI exists and populate SVI config
            if (checkSviExists(vlanId)) {
                document.getElementById('create-svi').checked = true;
                document.getElementById('svi-config').style.display = 'block';
                
                const sviIp = getSviIpAddress(vlanId);
                if (sviIp) {
                    const [ip, subnet] = sviIp.split('/');
                    document.getElementById('svi-ip').value = ip;
                    document.getElementById('svi-subnet').value = subnet;
                }
            }
            
            const modal = new bootstrap.Modal(document.getElementById('vlanModal'));
            modal.show();
        }

        function setupSviToggle() {
            document.getElementById('create-svi').addEventListener('change', function() {
                const sviConfig = document.getElementById('svi-config');
                sviConfig.style.display = this.checked ? 'block' : 'none';
            });
        }

        function saveVlan() {
            const action = document.getElementById('vlan-action').value;
            const vlanId = document.getElementById('vlan-id').value;
            const vlanName = document.getElementById('vlan-name').value;
            const vlanState = document.getElementById('vlan-state').value;
            const createSvi = document.getElementById('create-svi').checked;

            if (!validateVlanId(vlanId)) {
                showAlert('Invalid VLAN ID. Must be between 1 and 4094.', 'danger');
                return;
            }

            if (!vlanName.trim()) {
                showAlert('VLAN name is required.', 'danger');
                return;
            }

            let commands = [];

            if (action === 'create') {
                commands.push(`vlan ${vlanId}`);
                commands.push(`name ${vlanName}`);
                if (vlanState === 'suspend') {
                    commands.push('state suspend');
                }
            } else {
                commands.push(`vlan ${vlanId}`);
                commands.push(`name ${vlanName}`);
                commands.push(`state ${vlanState}`);
            }

            if (createSvi) {
                const sviIp = document.getElementById('svi-ip').value;
                const sviSubnet = document.getElementById('svi-subnet').value;
                const sviDescription = document.getElementById('svi-description').value;
                const sviNoShutdown = document.getElementById('svi-no-shutdown').checked;

                if (sviIp && validateIP(sviIp)) {
                    commands.push(`interface vlan${vlanId}`);
                    if (sviDescription) {
                        commands.push(`description ${sviDescription}`);
                    }
                    commands.push(`ip address ${sviIp}/${sviSubnet}`);
                    if (sviNoShutdown) {
                        commands.push('no shutdown');
                    }
                }
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`${action === 'create' ? 'Create' : 'Update'} VLAN ${vlanId} with the following configuration?\n\n${configCommands}`, function() {
                executeCommand(configCommands.split('\n').join(' ; '), function(data) {
                    showAlert(`VLAN ${vlanId} ${action === 'create' ? 'created' : 'updated'} successfully`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('vlanModal')).hide();
                    setTimeout(loadVlans, 2000);
                }, 'cli_conf');
            });
        }

        function deleteVlan(vlanId, vlanName) {
            confirmAction(`Are you sure you want to delete VLAN ${vlanId} (${vlanName})?\n\nThis action cannot be undone.`, function() {
                const commands = [
                    `no vlan ${vlanId}`,
                    `no interface vlan${vlanId}`
                ];
                executeCommand(commands.join(' ; '), function(data) {
                    showAlert(`VLAN ${vlanId} deleted successfully`, 'success');
                    setTimeout(loadVlans, 2000);
                }, 'cli_conf');
            });
        }

        function showVlanAssignment(vlanId, vlanName) {
            document.getElementById('assignment-vlan-id').value = vlanId;
            document.querySelector('#assignmentModal .modal-title').textContent = `VLAN ${vlanId} (${vlanName}) Port Assignment`;
            
            loadVlanAssignmentData(vlanId);
            
            const modal = new bootstrap.Modal(document.getElementById('assignmentModal'));
            modal.show();
        }

        function loadVlanAssignmentData(vlanId) {
            const availableDiv = document.getElementById('available-interfaces');
            const assignedDiv = document.getElementById('assigned-interfaces');
            
            // Get interfaces assigned to this VLAN
            const assignedInterfaces = interfacesData.filter(intf => intf.access_vlan === vlanId || intf.trunk_vlans.includes(vlanId));
            const availableInterfaces = interfacesData.filter(intf => intf.access_vlan !== vlanId && intf.trunk_vlans.filter(v => expandVlanList(v).includes(vlanId)).length === 0 && !intf.interface.includes('mgmt'));
            
            // Display available interfaces
            availableDiv.innerHTML = '';
            availableInterfaces.forEach(intf => {
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input" type="checkbox" id="avail-${intf.interface}" 
                           value="${intf.interface}">
                    <label class="form-check-label" for="avail-${intf.interface}">
                        ${intf.interface} <small class="text-muted">(VLAN ${intf.access_vlan || intf.trunk_vlans.filter(v => expandVlanList(v).includes(vlanId)).length > 0 ? intf.trunk_vlans.filter(v => expandVlanList(v).includes(vlanId))[0] : 'N/A'})</small>
                    </label>
                `;
                availableDiv.appendChild(div);
            });

            // Display assigned interfaces
            assignedDiv.innerHTML = '';
            assignedInterfaces.forEach(intf => {
                const div = document.createElement('div');
                div.className = 'form-check mb-2';
                div.innerHTML = `
                    <input class="form-check-input" type="checkbox" id="assigned-${intf.interface}" 
                           value="${intf.interface}">
                    <label class="form-check-label" for="assigned-${intf.interface}">
                        ${intf.interface} <i class="fas fa-check text-success"></i>
                    </label>
                `;
                assignedDiv.appendChild(div);
            });
        }

        function bulkAssignVlan() {
            const bulkInterfaces = document.getElementById('bulk-interfaces').value;
            const vlanId = document.getElementById('assignment-vlan-id').value;
            
            if (!bulkInterfaces.trim()) {
                showAlert('Please enter interface names or ranges.', 'warning');
                return;
            }

            // Parse bulk interface input (simplified)
            const interfaces = bulkInterfaces.split(',').map(s => s.trim());
            
            interfaces.forEach(intfName => {
                // Check available interfaces and select them
                const checkbox = document.getElementById(`avail-${intfName}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });

            showAlert(`Selected ${interfaces.length} interfaces for assignment.`, 'info');
        }

        function applyVlanAssignments() {
            const vlanId = document.getElementById('assignment-vlan-id').value;
            
            // Get selected available interfaces (to assign)
            const availableChecked = document.querySelectorAll('#available-interfaces input:checked');
            const assignedChecked = document.querySelectorAll('#assigned-interfaces input:checked');
            
            let commands = [];
            
            // Assign selected available interfaces to this VLAN
            availableChecked.forEach(checkbox => {
                const interfaceName = checkbox.value;
                commands.push(`interface ${interfaceName}`);
                commands.push('switchport');
                commands.push('switchport mode access');
                commands.push(`switchport access vlan ${vlanId}`);
            });
            
            // Remove selected assigned interfaces from this VLAN (assign to VLAN 1)
            assignedChecked.forEach(checkbox => {
                const interfaceName = checkbox.value;
                commands.push(`interface ${interfaceName}`);
                commands.push('switchport access vlan 1');
            });

            if (commands.length === 0) {
                showAlert('No changes to apply.', 'info');
                return;
            }

            const configCommands = commands.join('\n');
            
            confirmAction(`Apply VLAN assignments?\n\n${configCommands}`, function() {
                executeCommand(`configure terminal\n${configCommands}`, function(data) {
                    showAlert('VLAN assignments applied successfully', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('assignmentModal')).hide();
                    setTimeout(() => {
                        loadVlans();
                        loadInterfaces();
                    }, 2000);
                });
            });
        }

        function manageSvi(vlanId, vlanName) {
            // This would open a dedicated SVI management modal
            showAlert(`SVI management for VLAN ${vlanId} - Feature coming soon!`, 'info');
        }
    </script>
</body>
</html>

