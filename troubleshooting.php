<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Troubleshooting - Nexus Dashboard</title>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tools"></i> Network Troubleshooting</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-nexus btn-sm" onclick="clearAllResults()">
                            <i class="fas fa-trash"></i> Clear All Results
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportResults()">
                            <i class="fas fa-download"></i> Export Results
                        </button>
                    </div>
                </div>

                <!-- Quick Diagnostic Tools -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-search"></i> Ping Test</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runPingTest(event)">
                                    <div class="mb-3">
                                        <label class="form-label">Target IP/Hostname</label>
                                        <input type="text" class="form-control" id="ping-target" placeholder="192.168.1.1" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Count</label>
                                            <input type="number" class="form-control" id="ping-count" value="5" min="1" max="100">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Size (bytes)</label>
                                            <input type="number" class="form-control" id="ping-size" value="64" min="32" max="1500">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Source Interface</label>
                                        <select class="form-select" id="ping-source">
                                            <option value="">Default</option>
                                            <option value="mgmt0">mgmt0</option>
                                            <option value="vlan1">VLAN 1</option>
                                            <option value="vlan10">VLAN 10</option>
                                            <option value="loopback0">Loopback 0</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-play"></i> Run Ping
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-route"></i> Traceroute</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runTraceroute(event)">
                                    <div class="mb-3">
                                        <label class="form-label">Target IP/Hostname</label>
                                        <input type="text" class="form-control" id="trace-target" placeholder="8.8.8.8" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Max Hops</label>
                                            <input type="number" class="form-control" id="trace-hops" value="30" min="1" max="64">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Timeout (s)</label>
                                            <input type="number" class="form-control" id="trace-timeout" value="5" min="1" max="30">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Source Interface</label>
                                        <select class="form-select" id="trace-source">
                                            <option value="">Default</option>
                                            <option value="mgmt0">mgmt0</option>
                                            <option value="vlan1">VLAN 1</option>
                                            <option value="vlan10">VLAN 10</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-play"></i> Run Traceroute
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-search-plus"></i> ARP Lookup</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runArpLookup(event)">
                                    <div class="mb-3">
                                        <label class="form-label">IP Address</label>
                                        <input type="text" class="form-control" id="arp-ip" placeholder="192.168.1.100">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">VLAN</label>
                                        <select class="form-select" id="arp-vlan">
                                            <option value="">All VLANs</option>
                                            <option value="1">VLAN 1</option>
                                            <option value="10">VLAN 10</option>
                                            <option value="20">VLAN 20</option>
                                            <option value="100">VLAN 100</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search"></i> Lookup ARP
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="showArpTable()">
                                            <i class="fas fa-table"></i> Show ARP Table
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-search-location"></i> MAC Lookup</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runMacLookup(event)">
                                    <div class="mb-3">
                                        <label class="form-label">MAC Address</label>
                                        <input type="text" class="form-control" id="mac-address" placeholder="00:11:22:33:44:55">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">VLAN</label>
                                        <select class="form-select" id="mac-vlan">
                                            <option value="">All VLANs</option>
                                            <option value="1">VLAN 1</option>
                                            <option value="10">VLAN 10</option>
                                            <option value="20">VLAN 20</option>
                                            <option value="100">VLAN 100</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-search"></i> Lookup MAC
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" onclick="showMacTable()">
                                            <i class="fas fa-table"></i> Show MAC Table
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Diagnostic Tools -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-bug"></i> Port Diagnostics</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runPortDiagnostics(event)">
                                    <div class="mb-3">
                                        <label class="form-label">Interface</label>
                                        <select class="form-select" id="diag-interface" required>
                                            <option value="">Select Interface</option>
                                            <option value="Ethernet1/1">Ethernet1/1</option>
                                            <option value="Ethernet1/2">Ethernet1/2</option>
                                            <option value="Ethernet1/3">Ethernet1/3</option>
                                            <option value="Ethernet1/4">Ethernet1/4</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="diag-cable" checked>
                                            <label class="form-check-label" for="diag-cable">Cable Test</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="diag-link" checked>
                                            <label class="form-check-label" for="diag-link">Link Quality</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="diag-errors">
                                            <label class="form-check-label" for="diag-errors">Error Analysis</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-stethoscope"></i> Run Diagnostics
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-network-wired"></i> VLAN Diagnostics</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runVlanDiagnostics(event)">
                                    <div class="mb-3">
                                        <label class="form-label">VLAN ID</label>
                                        <input type="number" class="form-control" id="vlan-diag-id" min="1" max="4094" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="vlan-ports" checked>
                                            <label class="form-check-label" for="vlan-ports">Port Membership</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="vlan-stp" checked>
                                            <label class="form-check-label" for="vlan-stp">STP Status</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="vlan-mac">
                                            <label class="form-check-label" for="vlan-mac">MAC Addresses</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary w-100">
                                        <i class="fas fa-diagnoses"></i> Diagnose VLAN
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-route"></i> Routing Diagnostics</h5>
                            </div>
                            <div class="card-body">
                                <form onsubmit="runRoutingDiagnostics(event)">
                                    <div class="mb-3">
                                        <label class="form-label">Destination Network</label>
                                        <input type="text" class="form-control" id="route-dest" placeholder="192.168.1.0/24" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="route-table" checked>
                                            <label class="form-check-label" for="route-table">Route Lookup</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="route-neighbors">
                                            <label class="form-check-label" for="route-neighbors">Neighbor Status</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="route-protocols">
                                            <label class="form-check-label" for="route-protocols">Protocol Status</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-dark w-100">
                                        <i class="fas fa-map-signs"></i> Diagnose Routing
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Display Area -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-terminal"></i> Diagnostic Results</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="toggleResultsView()">
                                    <i class="fas fa-expand-arrows-alt"></i> Toggle View
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="copyResults()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="results-container" class="bg-dark text-light p-3 rounded" style="min-height: 300px; font-family: 'Courier New', monospace; white-space: pre-wrap; overflow-y: auto;">
                            <div class="text-muted">Diagnostic results will appear here...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="common.js"></script>
    <script>
        let diagnosticResults = [];

        // Page-specific refresh function
        window.pageRefreshFunction = function() {
            // Refresh any ongoing diagnostics
        };

        function runPingTest(event) {
            event.preventDefault();
            
            const target = document.getElementById('ping-target').value;
            const count = document.getElementById('ping-count').value;
            const size = document.getElementById('ping-size').value;
            const source = document.getElementById('ping-source').value;
            
            const timestamp = new Date().toLocaleString();
            const command = `ping ${target} count ${count} packet-size ${size}${source ? ` source ${source}` : ''}`;
            
            addResult(`[${timestamp}] Running: ${command}`);
            addResult('');
            
            // Simulate ping results
            setTimeout(() => {
                const results = generateMockPingResults(target, parseInt(count));
                results.forEach(line => addResult(line));
                addResult('');
                addResult('--- Ping test completed ---');
                addResult('');
            }, 1000);
        }

        function runTraceroute(event) {
            event.preventDefault();
            
            const target = document.getElementById('trace-target').value;
            const hops = document.getElementById('trace-hops').value;
            const timeout = document.getElementById('trace-timeout').value;
            const source = document.getElementById('trace-source').value;
            
            const timestamp = new Date().toLocaleString();
            const command = `traceroute ${target} max-hops ${hops} timeout ${timeout}${source ? ` source ${source}` : ''}`;
            
            addResult(`[${timestamp}] Running: ${command}`);
            addResult('');
            
            // Simulate traceroute results
            setTimeout(() => {
                const results = generateMockTracerouteResults(target, parseInt(hops));
                results.forEach(line => addResult(line));
                addResult('');
                addResult('--- Traceroute completed ---');
                addResult('');
            }, 2000);
        }

        function runArpLookup(event) {
            event.preventDefault();
            
            const ip = document.getElementById('arp-ip').value;
            const vlan = document.getElementById('arp-vlan').value;
            
            const timestamp = new Date().toLocaleString();
            const command = `show ip arp${ip ? ` ${ip}` : ''}${vlan ? ` vlan ${vlan}` : ''}`;
            
            addResult(`[${timestamp}] Running: ${command}`);
            addResult('');
            
            // Simulate ARP results
            setTimeout(() => {
                const results = generateMockArpResults(ip, vlan);
                results.forEach(line => addResult(line));
                addResult('');
            }, 500);
        }

        function showArpTable() {
            const timestamp = new Date().toLocaleString();
            addResult(`[${timestamp}] Running: show ip arp`);
            addResult('');
            
            setTimeout(() => {
                const results = generateMockArpTable();
                results.forEach(line => addResult(line));
                addResult('');
            }, 500);
        }

        function runMacLookup(event) {
            event.preventDefault();
            
            const mac = document.getElementById('mac-address').value;
            const vlan = document.getElementById('mac-vlan').value;
            
            const timestamp = new Date().toLocaleString();
            const command = `show mac address-table${mac ? ` address ${mac}` : ''}${vlan ? ` vlan ${vlan}` : ''}`;
            
            addResult(`[${timestamp}] Running: ${command}`);
            addResult('');
            
            setTimeout(() => {
                const results = generateMockMacResults(mac, vlan);
                results.forEach(line => addResult(line));
                addResult('');
            }, 500);
        }

        function showMacTable() {
            const timestamp = new Date().toLocaleString();
            addResult(`[${timestamp}] Running: show mac address-table`);
            addResult('');
            
            setTimeout(() => {
                const results = generateMockMacTable();
                results.forEach(line => addResult(line));
                addResult('');
            }, 500);
        }

        function runPortDiagnostics(event) {
            event.preventDefault();
            
            const interface = document.getElementById('diag-interface').value;
            const cable = document.getElementById('diag-cable').checked;
            const link = document.getElementById('diag-link').checked;
            const errors = document.getElementById('diag-errors').checked;
            
            const timestamp = new Date().toLocaleString();
            addResult(`[${timestamp}] Running port diagnostics for ${interface}`);
            addResult('');
            
            setTimeout(() => {
                if (cable) {
                    const cableResults = generateMockCableTest(interface);
                    cableResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (link) {
                    const linkResults = generateMockLinkQuality(interface);
                    linkResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (errors) {
                    const errorResults = generateMockErrorAnalysis(interface);
                    errorResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                addResult('--- Port diagnostics completed ---');
                addResult('');
            }, 1500);
        }

        function runVlanDiagnostics(event) {
            event.preventDefault();
            
            const vlanId = document.getElementById('vlan-diag-id').value;
            const ports = document.getElementById('vlan-ports').checked;
            const stp = document.getElementById('vlan-stp').checked;
            const mac = document.getElementById('vlan-mac').checked;
            
            const timestamp = new Date().toLocaleString();
            addResult(`[${timestamp}] Running VLAN ${vlanId} diagnostics`);
            addResult('');
            
            setTimeout(() => {
                if (ports) {
                    const portResults = generateMockVlanPorts(vlanId);
                    portResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (stp) {
                    const stpResults = generateMockVlanStp(vlanId);
                    stpResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (mac) {
                    const macResults = generateMockVlanMac(vlanId);
                    macResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                addResult('--- VLAN diagnostics completed ---');
                addResult('');
            }, 1000);
        }

        function runRoutingDiagnostics(event) {
            event.preventDefault();
            
            const dest = document.getElementById('route-dest').value;
            const table = document.getElementById('route-table').checked;
            const neighbors = document.getElementById('route-neighbors').checked;
            const protocols = document.getElementById('route-protocols').checked;
            
            const timestamp = new Date().toLocaleString();
            addResult(`[${timestamp}] Running routing diagnostics for ${dest}`);
            addResult('');
            
            setTimeout(() => {
                if (table) {
                    const routeResults = generateMockRouteLookup(dest);
                    routeResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (neighbors) {
                    const neighborResults = generateMockNeighborStatus();
                    neighborResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                if (protocols) {
                    const protocolResults = generateMockProtocolStatus();
                    protocolResults.forEach(line => addResult(line));
                    addResult('');
                }
                
                addResult('--- Routing diagnostics completed ---');
                addResult('');
            }, 1200);
        }

        function addResult(text) {
            diagnosticResults.push(text);
            const container = document.getElementById('results-container');
            container.textContent = diagnosticResults.join('\n');
            container.scrollTop = container.scrollHeight;
        }

        function clearAllResults() {
            diagnosticResults = [];
            document.getElementById('results-container').innerHTML = '<div class="text-muted">Diagnostic results will appear here...</div>';
        }

        function exportResults() {
            if (diagnosticResults.length === 0) {
                showAlert('No results to export', 'warning');
                return;
            }
            
            const content = diagnosticResults.join('\n');
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            exportConfig(content, `diagnostic-results-${timestamp}.txt`);
        }

        function toggleResultsView() {
            const container = document.getElementById('results-container');
            if (container.style.height === '600px') {
                container.style.height = '300px';
            } else {
                container.style.height = '600px';
            }
        }

        function copyResults() {
            const content = diagnosticResults.join('\n');
            copyToClipboard(content);
        }

        // Mock data generators
        function generateMockPingResults(target, count) {
            const results = [];
            results.push(`PING ${target} (${target}): 56 data bytes`);
            
            for (let i = 1; i <= count; i++) {
                const time = (Math.random() * 10 + 1).toFixed(1);
                const ttl = Math.floor(Math.random() * 10) + 60;
                results.push(`64 bytes from ${target}: icmp_seq=${i} ttl=${ttl} time=${time} ms`);
            }
            
            const loss = Math.random() > 0.9 ? Math.floor(Math.random() * 20) : 0;
            results.push('');
            results.push(`--- ${target} ping statistics ---`);
            results.push(`${count} packets transmitted, ${count - Math.floor(count * loss / 100)} received, ${loss}% packet loss`);
            
            return results;
        }

        function generateMockTracerouteResults(target, maxHops) {
            const results = [];
            results.push(`traceroute to ${target} (${target}), ${maxHops} hops max, 40 byte packets`);
            
            const hops = Math.min(Math.floor(Math.random() * 8) + 3, maxHops);
            for (let i = 1; i <= hops; i++) {
                const ip = `192.168.${Math.floor(Math.random() * 255)}.${Math.floor(Math.random() * 255)}`;
                const time1 = (Math.random() * 20 + 1).toFixed(1);
                const time2 = (Math.random() * 20 + 1).toFixed(1);
                const time3 = (Math.random() * 20 + 1).toFixed(1);
                results.push(`${i.toString().padStart(2)}  ${ip}  ${time1} ms  ${time2} ms  ${time3} ms`);
            }
            
            return results;
        }

        function generateMockArpResults(ip, vlan) {
            const results = [];
            results.push('Address         Age (min)  Hardware Addr   Interface');
            results.push('-------         ---------  -------------   ---------');
            
            if (ip) {
                const mac = generateRandomMac();
                const age = Math.floor(Math.random() * 60);
                const intf = `Vlan${vlan || '1'}`;
                results.push(`${ip.padEnd(15)} ${age.toString().padStart(9)}  ${mac}   ${intf}`);
            } else {
                for (let i = 0; i < 5; i++) {
                    const testIp = `192.168.1.${100 + i}`;
                    const mac = generateRandomMac();
                    const age = Math.floor(Math.random() * 60);
                    const intf = `Vlan${vlan || Math.floor(Math.random() * 3) + 1}`;
                    results.push(`${testIp.padEnd(15)} ${age.toString().padStart(9)}  ${mac}   ${intf}`);
                }
            }
            
            return results;
        }

        function generateMockArpTable() {
            const results = [];
            results.push('IP ARP Table for context default');
            results.push('Total number of entries: 12');
            results.push('Address         Age (min)  Hardware Addr   Interface');
            results.push('-------         ---------  -------------   ---------');
            
            for (let i = 1; i <= 12; i++) {
                const ip = `192.168.1.${100 + i}`;
                const mac = generateRandomMac();
                const age = Math.floor(Math.random() * 120);
                const vlan = Math.floor(Math.random() * 3) + 1;
                results.push(`${ip.padEnd(15)} ${age.toString().padStart(9)}  ${mac}   Vlan${vlan}`);
            }
            
            return results;
        }

        function generateMockMacResults(mac, vlan) {
            const results = [];
            results.push('Legend: * - primary entry, G - Gateway MAC, (R) - Routed MAC, O - Overlay MAC');
            results.push('        age - seconds since last seen,+ - primary entry using vPC Peer-Link');
            results.push('   VLAN     MAC Address      Type      age     Secure NTFY Ports');
            results.push('---------+-----------------+--------+---------+------+----+------------------');
            
            if (mac) {
                const testVlan = vlan || Math.floor(Math.random() * 100) + 1;
                const port = `Eth1/${Math.floor(Math.random() * 48) + 1}`;
                const age = Math.floor(Math.random() * 300);
                results.push(`${testVlan.toString().padStart(8)} ${mac.padEnd(17)} dynamic ${age.toString().padStart(8)}   F    F  ${port}`);
            } else {
                for (let i = 0; i < 8; i++) {
                    const testMac = generateRandomMac();
                    const testVlan = vlan || Math.floor(Math.random() * 100) + 1;
                    const port = `Eth1/${Math.floor(Math.random() * 48) + 1}`;
                    const age = Math.floor(Math.random() * 300);
                    results.push(`${testVlan.toString().padStart(8)} ${testMac.padEnd(17)} dynamic ${age.toString().padStart(8)}   F    F  ${port}`);
                }
            }
            
            return results;
        }

        function generateMockMacTable() {
            const results = [];
            results.push('Legend: * - primary entry, G - Gateway MAC, (R) - Routed MAC, O - Overlay MAC');
            results.push('        age - seconds since last seen,+ - primary entry using vPC Peer-Link');
            results.push('   VLAN     MAC Address      Type      age     Secure NTFY Ports');
            results.push('---------+-----------------+--------+---------+------+----+------------------');
            
            for (let i = 0; i < 15; i++) {
                const mac = generateRandomMac();
                const vlan = Math.floor(Math.random() * 100) + 1;
                const port = `Eth1/${Math.floor(Math.random() * 48) + 1}`;
                const age = Math.floor(Math.random() * 300);
                results.push(`${vlan.toString().padStart(8)} ${mac.padEnd(17)} dynamic ${age.toString().padStart(8)}   F    F  ${port}`);
            }
            
            results.push('');
            results.push('Total MAC Addresses for this criterion: 15');
            
            return results;
        }

        function generateMockCableTest(interface) {
            const results = [];
            results.push(`Cable Test Results for ${interface}:`);
            results.push('=====================================');
            results.push('Pair A: OK (Length: 45m)');
            results.push('Pair B: OK (Length: 45m)');
            results.push('Pair C: OK (Length: 45m)');
            results.push('Pair D: OK (Length: 45m)');
            results.push('');
            results.push('Cable Status: PASS');
            results.push('Cable Type: Cat6');
            results.push('Link Quality: Excellent');
            
            return results;
        }

        function generateMockLinkQuality(interface) {
            const results = [];
            results.push(`Link Quality Analysis for ${interface}:`);
            results.push('========================================');
            results.push('Signal Strength: -12 dBm (Excellent)');
            results.push('SNR: 35 dB (Good)');
            results.push('BER: 1e-12 (Excellent)');
            results.push('Link Stability: 99.9%');
            results.push('Auto-negotiation: Success');
            results.push('Duplex: Full');
            results.push('Flow Control: Enabled');
            
            return results;
        }

        function generateMockErrorAnalysis(interface) {
            const results = [];
            results.push(`Error Analysis for ${interface}:`);
            results.push('===============================');
            results.push('Input Errors: 0');
            results.push('Output Errors: 0');
            results.push('CRC Errors: 0');
            results.push('Frame Errors: 0');
            results.push('Overrun Errors: 0');
            results.push('Collision Count: 0');
            results.push('Late Collisions: 0');
            results.push('');
            results.push('Error Status: CLEAN');
            
            return results;
        }

        function generateMockVlanPorts(vlanId) {
            const results = [];
            results.push(`VLAN ${vlanId} Port Membership:`);
            results.push('============================');
            results.push('Access Ports: Eth1/5, Eth1/6, Eth1/7');
            results.push('Trunk Ports: Eth1/1, Eth1/2');
            results.push('Total Ports: 5');
            results.push('Active Ports: 4');
            results.push('Inactive Ports: 1');
            
            return results;
        }

        function generateMockVlanStp(vlanId) {
            const results = [];
            results.push(`VLAN ${vlanId} Spanning Tree Status:`);
            results.push('=================================');
            results.push('STP Mode: RSTP');
            results.push('Root Bridge: 32768.0011.2233.4455');
            results.push('Local Bridge: 32768.0011.2233.4466');
            results.push('Root Port: Eth1/1');
            results.push('Designated Ports: Eth1/5, Eth1/6');
            results.push('Blocked Ports: None');
            
            return results;
        }

        function generateMockVlanMac(vlanId) {
            const results = [];
            results.push(`VLAN ${vlanId} MAC Address Table:`);
            results.push('===============================');
            
            for (let i = 0; i < 5; i++) {
                const mac = generateRandomMac();
                const port = `Eth1/${Math.floor(Math.random() * 8) + 5}`;
                const age = Math.floor(Math.random() * 300);
                results.push(`${mac} - ${port} (age: ${age}s)`);
            }
            
            results.push('');
            results.push(`Total MAC addresses in VLAN ${vlanId}: 5`);
            
            return results;
        }

        function generateMockRouteLookup(dest) {
            const results = [];
            results.push(`Route Lookup for ${dest}:`);
            results.push('========================');
            results.push('Best Route: 192.168.1.0/24 via 10.0.0.1');
            results.push('Protocol: Static');
            results.push('Administrative Distance: 1');
            results.push('Metric: 0');
            results.push('Next Hop: 10.0.0.1');
            results.push('Interface: Vlan10');
            results.push('Age: 2d3h');
            
            return results;
        }

        function generateMockNeighborStatus() {
            const results = [];
            results.push('Routing Protocol Neighbors:');
            results.push('===========================');
            results.push('OSPF Neighbors:');
            results.push('  10.0.0.2 - Full/DR (Vlan10)');
            results.push('  10.0.0.3 - Full/BDR (Vlan10)');
            results.push('');
            results.push('BGP Neighbors:');
            results.push('  192.168.100.1 - Established (AS 65001)');
            results.push('  192.168.100.2 - Established (AS 65002)');
            
            return results;
        }

        function generateMockProtocolStatus() {
            const results = [];
            results.push('Routing Protocol Status:');
            results.push('========================');
            results.push('OSPF Process 1:');
            results.push('  Status: Running');
            results.push('  Router ID: 10.0.0.1');
            results.push('  Areas: 0.0.0.0');
            results.push('  Neighbors: 2');
            results.push('');
            results.push('BGP AS 65000:');
            results.push('  Status: Running');
            results.push('  Router ID: 10.0.0.1');
            results.push('  Neighbors: 2');
            results.push('  Prefixes: 150000');
            
            return results;
        }

        function generateRandomMac() {
            const hex = '0123456789abcdef';
            let mac = '';
            for (let i = 0; i < 6; i++) {
                if (i > 0) mac += ':';
                mac += hex[Math.floor(Math.random() * 16)];
                mac += hex[Math.floor(Math.random() * 16)];
            }
            return mac;
        }
    </script>
</body>
</html>

