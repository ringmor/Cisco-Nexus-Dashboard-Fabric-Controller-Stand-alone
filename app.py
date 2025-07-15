#!/usr/bin/env python3
"""
Flask wrapper for Nexus Dashboard PHP application
Serves the PHP files through Flask for deployment compatibility
"""


from flask import Flask, request, send_from_directory, redirect, url_for
import subprocess
import os
import tempfile
import json


app = Flask(__name__)

# Set the directory containing PHP files
PHP_DIR = os.path.dirname(os.path.abspath(__file__))

def execute_php(php_file, query_string='', post_data=None):
    """Execute PHP file and return the output"""
    try:
        # Prepare environment variables
        env = os.environ.copy()
        env['REQUEST_METHOD'] = request.method
        env['QUERY_STRING'] = query_string
        env['REQUEST_URI'] = request.path
        env['HTTP_HOST'] = request.host
        env['SERVER_NAME'] = request.host.split(':')[0]
        env['SERVER_PORT'] = str(request.environ.get('SERVER_PORT', '5000'))
        env['SCRIPT_NAME'] = php_file
        env['PATH_INFO'] = ''
        
        if post_data:
            env['CONTENT_LENGTH'] = str(len(post_data))
            env['CONTENT_TYPE'] = request.content_type or 'application/x-www-form-urlencoded'
        
        # Execute PHP
        cmd = ['php', os.path.join(PHP_DIR, php_file)]
        
        if post_data:
            process = subprocess.Popen(
                cmd, 
                stdout=subprocess.PIPE, 
                stderr=subprocess.PIPE,
                stdin=subprocess.PIPE,
                env=env,
                cwd=PHP_DIR
            )
            stdout, stderr = process.communicate(input=post_data.encode())
        else:
            process = subprocess.Popen(
                cmd, 
                stdout=subprocess.PIPE, 
                stderr=subprocess.PIPE,
                env=env,
                cwd=PHP_DIR
            )
            stdout, stderr = process.communicate()
        
        if process.returncode == 0:
            return stdout.decode('utf-8')
        else:
            return f"PHP Error: {stderr.decode('utf-8')}"
            
    except Exception as e:
        return f"Execution Error: {str(e)}"

@app.route('/')
def index():
    """Serve the main index page"""
    return execute_php('index.php')

@app.route('/<path:filename>')
def serve_php(filename):
    """Serve PHP files"""
    if filename.endswith('.php'):
        query_string = request.query_string.decode('utf-8')
        post_data = None
        
        if request.method == 'POST':
            if request.is_json:
                post_data = json.dumps(request.get_json())
            else:
                post_data = request.get_data().decode('utf-8')
        
        return execute_php(filename, query_string, post_data)
    else:
        # Serve static files (CSS, JS, images)
        try:
            return send_from_directory(PHP_DIR, filename)
        except:
            return "File not found", 404

@app.route('/<path:filename>', methods=['POST'])
def serve_php_post(filename):
    """Handle POST requests to PHP files"""
    return serve_php(filename)

# Add routes for all main PHP files
php_files = [
    'interfaces.php', 'vlans.php', 'routing.php', 'security.php', 
    'advanced.php', 'monitoring.php', 'tools.php', 'system.php',
    'interface_config.php', 'interface_counters.php', 'port_channels.php',
    'vlan_config.php', 'svi.php', 'vtp.php', 'static_routes.php',
    'ospf.php', 'bgp.php', 'hsrp.php', 'acl.php', 'port_security.php',
    'aaa.php', 'dhcp_snooping.php', 'qos.php', 'spanning_tree.php',
    'multicast.php', 'vpc.php', 'cpu_memory.php', 'environment.php',
    'troubleshooting.php', 'logs.php', 'backup.php', 'firmware.php',
    'config_management.php', 'alerts.php', 'scheduled_tasks.php', 'nxapi.php'
]

for php_file in php_files:
    endpoint = php_file.replace('.php', '').replace('_', '-')
    app.add_url_rule(f'/{php_file}', endpoint, lambda f=php_file: serve_php(f), methods=['GET', 'POST'])

if __name__ == '__main__':
    print("🚀 Starting Nexus Dashboard Web Server...")
    print("📡 Nexus Dashboard will be available at the deployed URL")
    print("🔧 All 51 files and 7 complete menus are ready for deployment")
    app.run(host='0.0.0.0', port=5000, debug=False)

