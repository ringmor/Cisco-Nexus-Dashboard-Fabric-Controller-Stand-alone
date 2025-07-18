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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-server"></i> System Information</h2>
            <div>
                <button class="btn btn-success" onclick="loadAllSystemData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-warning" onclick="toggleAutoRefresh()" id="auto-refresh-btn">
                    <i class="fas fa-play"></i> Auto Refresh
                </button>
            </div>
        </div>

        <!-- Status Overview Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Hostname</h5>
                        <h3 id="hostname" class="text-success">--</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Uptime</h5>
                        <h3 id="system-uptime" class="text-success">--</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Software Version</h5>
                        <h3 id="software-version" class="text-info">--</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Hardware Model</h5>
                        <h3 id="hardware-model" class="text-warning">--</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Serial Number</h5>
                        <h3 id="serial-number" class="text-secondary">--</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">CPU Usage</h5>
                        <h3 id="cpu-usage" class="text-danger">--%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Memory Usage</h5>
                        <h3 id="memory-usage" class="text-danger">--%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables for Modules, Inventory, Licenses, Processes -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#module-collapse" style="cursor: pointer;">
                        <h5><i class="fas fa-puzzle-piece"></i> Module Information</h5>
                        <i class="fas fa-chevron-down" id="module-icon"></i>
                    </div>
                    <div class="collapse show" id="module-collapse">
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Module</th><th>Ports</th><th>Model</th><th>Status</th><th>Serial</th><th>MAC</th><th>HW Ver</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-tbody">
                                    <tr><td colspan="7" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm me-2"></div>Loading modules...
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#license-collapse" style="cursor: pointer;">
                        <h5><i class="fas fa-certificate"></i> License Information</h5>
                        <i class="fas fa-chevron-down" id="license-icon"></i>
                    </div>
                    <div class="collapse show" id="license-collapse">
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Feature</th><th>Type</th><th>Status</th><th>Count</th><th>Expiry</th></tr>
                                </thead>
                                <tbody id="licenses-tbody">
                                    <tr><td colspan="5" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm me-2"></div>Loading licenses...
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Enabled Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#features-collapse" style="cursor: pointer;">
                        <h5><i class="fas fa-list"></i> Features Enabled</h5>
                        <i class="fas fa-chevron-down" id="features-icon"></i>
                    </div>
                    <div class="collapse show" id="features-collapse">
                        <div class="card-body table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>Feature Name</th><th>Instance</th><th>State</th></tr>
                                </thead>
                                <tbody id="features-tbody">
                                    <tr><td colspan="3" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm me-2"></div>Loading features...
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#processes-collapse" style="cursor: pointer;">
                <h5><i class="fas fa-cogs"></i> Processes</h5>
                <div>
                    <button class="btn btn-sm btn-info" onclick="loadProcesses(); event.stopPropagation();"><i class="fas fa-sync-alt"></i> Refresh</button>
                    <i class="fas fa-chevron-down ms-2" id="processes-icon"></i>
                </div>
            </div>
            <div class="collapse show" id="processes-collapse">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr><th>PID</th><th>Name</th><th>CPU%</th><th>Mem%</th><th>Status</th><th>Runtime</th></tr>
                        </thead>
                        <tbody id="processes-tbody">
                            <tr><td colspan="6" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm me-2"></div>Loading processes...
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        let autoRefreshInterval, isAutoRefreshing=false;
        document.addEventListener('DOMContentLoaded',loadAllSystemData);
        function loadAllSystemData(){
            loadCommand('show hostname','displayHostname');
            loadCommand('show system uptime','displayUptime');
            loadCommand('show version','displayVersion');
            loadCommand('show system resources','displayResources');
            loadCommand('show feature','displayFeaturesTable'); // CHANGED
            loadCommand('show module','displayModules');
            loadCommand('show inventory','displayInventory');
            loadCommand('show license usage','displayLicenseUsage');
            loadProcesses();
        }
        function loadCommand(cmd, callback){
            fetch('nxapi.php',{
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify({
                    action: 'execute_command',
                    command: cmd
                })
            })
            .then(r=>r.json())
            .then(data => {
                if (data.success && data.result) {
                    const output = data.result.ins_api.outputs.output;
                    if (callback === 'displayFeaturesTable') {
                        window[callback]({ clierror: output.clierror || '' });
                    } else {
                        window[callback](output.body);
                    }
                } else {
                    console.error('API error:', data.message || 'Unknown error');
                }
            })
            .catch(e=>console.error(cmd,e));
        }
        function displayHostname(body){document.getElementById('hostname').textContent=body.hostname;}
        function displayUptime(body){
            const d=body.sys_up_days, h=body.sys_up_hrs, m=body.sys_up_mins, s=body.sys_up_secs;
            document.getElementById('system-uptime').textContent = `${d}d ${h}h ${m}m ${s}s`;
        }
        function displayVersion(body){
    // Populate cards from show version
    document.getElementById('software-version').textContent = body.sys_ver_str || '--';
    document.getElementById('hardware-model').textContent = body.chassis_id || '--';
    document.getElementById('serial-number').textContent = body.proc_board_id || '--';
}
        function displayResources(body){
    // Calculate CPU usage as user + kernel
    const user = parseFloat(body.cpu_state_user || body.cpu_state_user || 0);
    const kernel = parseFloat(body.cpu_state_kernel || 0);
    const cpuUsage = user + kernel;
    document.getElementById('cpu-usage').textContent = cpuUsage.toFixed(1) + '%';

    // Calculate Memory usage percentage
    const totalMem = parseFloat(body.memory_usage_total || 0);
    const usedMem = parseFloat(body.memory_usage_used || 0);
    const memUsage = totalMem ? (usedMem / totalMem * 100) : 0;
    document.getElementById('memory-usage').textContent = memUsage.toFixed(1) + '%';
}
        // New function to parse and display features table
        function displayFeaturesTable(body){
            const tb = document.getElementById('features-tbody');
            tb.innerHTML = '';
            let clierror = body.clierror || '';
            // Remove header lines and dashes
            const lines = clierror.split('\n').filter(line => line.trim() && !line.includes('Feature Name') && !/^[- ]+$/.test(line));
            lines.forEach(line => {
                // Split by whitespace, but keep feature name together
                // Feature Name is up to column 20, Instance is next 8, State is rest
                const feature = line.substring(0, 20).trim();
                const instance = line.substring(20, 30).trim();
                const state = line.substring(30).trim();
                tb.insertAdjacentHTML('beforeend',
                    `<tr>
                        <td>${feature}</td>
                        <td>${instance}</td>
                        <td><span class="badge ${state==='enabled' ? 'bg-success' : 'bg-secondary'}">${state}</span></td>
                    </tr>`
                );
            });
        }
        function displayModules(body){
            // Extract and normalize arrays from each table
            const modinfoRows = body.TABLE_modinfo && body.TABLE_modinfo.ROW_modinfo ? (Array.isArray(body.TABLE_modinfo.ROW_modinfo) ? body.TABLE_modinfo.ROW_modinfo : [body.TABLE_modinfo.ROW_modinfo]) : [];
            const modmacRows = body.TABLE_modmacinfo && body.TABLE_modmacinfo.ROW_modmacinfo ? (Array.isArray(body.TABLE_modmacinfo.ROW_modmacinfo) ? body.TABLE_modmacinfo.ROW_modmacinfo : [body.TABLE_modmacinfo.ROW_modmacinfo]) : [];
            const modwwnRows = body.TABLE_modwwninfo && body.TABLE_modwwninfo.ROW_modwwninfo ? (Array.isArray(body.TABLE_modwwninfo.ROW_modwwninfo) ? body.TABLE_modwwninfo.ROW_modwwninfo : [body.TABLE_modwwninfo.ROW_modwwninfo]) : [];

            // Index by module number for easy merging
            const macByMod = {};
            modmacRows.forEach(m => { macByMod[m.modmac] = m; });
            const wwnByMod = {};
            modwwnRows.forEach(w => { wwnByMod[w.modwwn] = w; });

            const tb = document.getElementById('modules-tbody');
            tb.innerHTML = '';
            modinfoRows.forEach(m => {
                const modnum = m.modinf;
                const macInfo = macByMod[modnum] || {};
                const wwnInfo = wwnByMod[modnum] || {};
                tb.insertAdjacentHTML('beforeend',
                    `<tr>
                        <td>${modnum}</td>
                        <td>${m.ports}</td>
                        <td>${m.modtype ? m.modtype.trim() : ''}</td>
                        <td><span class="badge ${m.status && m.status.trim().toLowerCase().startsWith('active') ? 'bg-success' : 'bg-danger'}">${m.status ? m.status.trim() : ''}</span></td>
                        <td>${macInfo.serialnum || ''}</td>
                        <td>${macInfo.mac || ''}</td>
                        <td>${wwnInfo.hw ? wwnInfo.hw.trim() : ''}</td>
                    </tr>`
                );
            });
        }
        // Replace displayLicenses with displayLicenseUsage
        function displayLicenseUsage(body){
            const rows = body.TABLE_lic_usage.ROW_lic_usage;
            const arr = Array.isArray(rows) ? rows : [rows];
            const tb = document.getElementById('licenses-tbody');
            tb.innerHTML = '';
            arr.forEach(l => tb.insertAdjacentHTML('beforeend',
                `<tr>
                    <td>${l.feature_name}</td>
                    <td>${l.install_status}</td>
                    <td><span class="badge ${l.status==='In Use' ? 'bg-success' : 'bg-secondary'}">${l.status}</span></td>
                    <td>${l.lic_count}</td>
                    <td>${l.expiry ? l.expiry.trim() : '-'}</td>
                </tr>`
            ));
        }
        function loadProcesses(){loadCommand('show processes cpu','displayProcesses');}
        function displayProcesses(body){
            const rows=body.TABLE_process.ROW_process;const arr=Array.isArray(rows)?rows:[rows];
            const tb=document.getElementById('processes-tbody');tb.innerHTML='';
            arr.forEach(p=>tb.insertAdjacentHTML('beforeend',
                `<tr><td>${p.pid}</td><td>${p.cmd}</td><td>${p.pcpu}</td><td>${p.pmem}</td><td>${p.status}</td><td>${p.elapsed}</td></tr>`));
        }
        function toggleAutoRefresh(){const btn=document.getElementById('auto-refresh-btn');if(isAutoRefreshing){clearInterval(autoRefreshInterval);isAutoRefreshing=false;btn.innerHTML='<i class="fas fa-play"></i> Auto Refresh';btn.className='btn btn-warning';}else{autoRefreshInterval=setInterval(loadAllSystemData,30000);isAutoRefreshing=true;btn.innerHTML='<i class="fas fa-pause"></i> Stop Auto';btn.className='btn btn-danger';}}
        
        // Add collapse functionality for icons
        document.addEventListener('DOMContentLoaded', function() {
            // Module collapse
            document.getElementById('module-collapse').addEventListener('show.bs.collapse', function() {
                document.getElementById('module-icon').className = 'fas fa-chevron-down';
            });
            document.getElementById('module-collapse').addEventListener('hide.bs.collapse', function() {
                document.getElementById('module-icon').className = 'fas fa-chevron-right';
            });
            
            // License collapse
            document.getElementById('license-collapse').addEventListener('show.bs.collapse', function() {
                document.getElementById('license-icon').className = 'fas fa-chevron-down';
            });
            document.getElementById('license-collapse').addEventListener('hide.bs.collapse', function() {
                document.getElementById('license-icon').className = 'fas fa-chevron-right';
            });
            
            // Features collapse
            document.getElementById('features-collapse').addEventListener('show.bs.collapse', function() {
                document.getElementById('features-icon').className = 'fas fa-chevron-down';
            });
            document.getElementById('features-collapse').addEventListener('hide.bs.collapse', function() {
                document.getElementById('features-icon').className = 'fas fa-chevron-right';
            });
            
            // Processes collapse
            document.getElementById('processes-collapse').addEventListener('show.bs.collapse', function() {
                document.getElementById('processes-icon').className = 'fas fa-chevron-down';
            });
            document.getElementById('processes-collapse').addEventListener('hide.bs.collapse', function() {
                document.getElementById('processes-icon').className = 'fas fa-chevron-right';
            });
        });
    </script>
</body>
</html>
