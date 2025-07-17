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
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Features Enabled</h5>
                        <h3 id="feature-count" class="text-info">--</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables for Modules, Inventory, Licenses, Processes -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-puzzle-piece"></i> Module Information</h5>
                    </div>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-certificate"></i> License Information</h5>
                    </div>
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

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-cogs"></i> Processes</h5>
                <button class="btn btn-sm btn-info" onclick="loadProcesses()"><i class="fas fa-sync-alt"></i> Refresh</button>
            </div>
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

    <script>
        let autoRefreshInterval, isAutoRefreshing=false;
        document.addEventListener('DOMContentLoaded',loadAllSystemData);
        function loadAllSystemData(){
            loadCommand('show hostname','displayHostname');
            loadCommand('show system uptime','displayUptime');
            loadCommand('show version','displayVersion');
            loadCommand('show system resources','displayResources');
            loadCommand('show feature','displayFeatures');
            loadCommand('show module','displayModules');
            loadCommand('show inventory','displayInventory');
            loadCommand('show license','displayLicenses');
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
                    window[callback](data.result.ins_api.outputs.output.body);
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
        function displayFeatures(body){
            document.getElementById('feature-count').textContent=Object.keys(body.feature_list||{}).length;
        }
        function displayModules(body){
            const rows=body.TABLE_module.ROW_module;const arr=Array.isArray(rows)?rows:[rows];
            const tb=document.getElementById('modules-tbody');tb.innerHTML='';
            arr.forEach(m=>tb.insertAdjacentHTML('beforeend',
                `<tr><td>${m.modnum}</td><td>${m.portcnt}</td><td>${m.modname}</td><td><span class="badge ${m.modstatus==='OK'?'bg-success':'bg-danger'}">${m.modstatus}</span></td><td>${m.serialnum}</td><td>${m.macaddr}</td><td>${m.hw_ver}</td></tr>`));
        }
        function displayLicenses(body){
            const rows=body.TABLE_license.ROW_license;const arr=Array.isArray(rows)?rows:[rows];
            const tb=document.getElementById('licenses-tbody');tb.innerHTML='';
            arr.forEach(l=>tb.insertAdjacentHTML('beforeend',
                `<tr><td>${l.licname}</td><td>${l.type}</td><td><span class="badge ${l.status==='In Use'? 'bg-success':'bg-secondary'}">${l.status}</span></td><td>${l.count}</td><td>${l.expiry}</td></tr>`));
        }
        function loadProcesses(){loadCommand('show processes cpu','displayProcesses');}
        function displayProcesses(body){
            const rows=body.TABLE_process.ROW_process;const arr=Array.isArray(rows)?rows:[rows];
            const tb=document.getElementById('processes-tbody');tb.innerHTML='';
            arr.forEach(p=>tb.insertAdjacentHTML('beforeend',
                `<tr><td>${p.pid}</td><td>${p.cmd}</td><td>${p.pcpu}</td><td>${p.pmem}</td><td>${p.status}</td><td>${p.elapsed}</td></tr>`));
        }
        function toggleAutoRefresh(){const btn=document.getElementById('auto-refresh-btn');if(isAutoRefreshing){clearInterval(autoRefreshInterval);isAutoRefreshing=false;btn.innerHTML='<i class="fas fa-play"></i> Auto Refresh';btn.className='btn btn-warning';}else{autoRefreshInterval=setInterval(loadAllSystemData,30000);isAutoRefreshing=true;btn.innerHTML='<i class="fas fa-pause"></i> Stop Auto';btn.className='btn btn-danger';}}
    </script>
</body>
</html>
