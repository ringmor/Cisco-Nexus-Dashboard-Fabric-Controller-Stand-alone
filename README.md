# Cisco Nexus Dashboard Fabric Controller

A comprehensive web-based dashboard for managing Cisco Nexus switches and fabric controllers.

## Project Structure

The project has been reorganized for better maintainability and scalability:

```
Cisco-Nexus-Dashboard-Fabric-Controller-Stand-alone/
├── index.php                          # Main entry point
├── src/                               # Source code
│   ├── php/                           # PHP application
│   │   ├── controllers/               # Main PHP pages/controllers
│   │   ├── includes/                  # Shared PHP files (config, functions)
│   │   └── api/                       # API endpoints
│   ├── python/                        # Python scripts
│   └── assets/                        # Static assets
│       ├── css/                       # Stylesheets
│       ├── js/                        # JavaScript files
│       ├── images/                    # Image files
│       └── asset/                     # Additional assets
├── docs/                              # Documentation
├── config/                            # Configuration files
├── data/                              # Data storage
├── logs/                              # Log files
├── tests/                             # Test files
└── scripts/                           # Utility scripts
```

## Features

- **Interface Management**: Configure and monitor switch interfaces
- **VLAN Management**: Create and manage VLANs
- **Routing Configuration**: Configure static routes, OSPF, BGP
- **Monitoring**: Real-time monitoring of switch status
- **Backup & Restore**: Configuration backup and restore
- **System Management**: Firmware, AAA, SNMP configuration
- **Security**: ACL, port security, DHCP snooping
- **Troubleshooting**: Diagnostic tools and logs

## Installation

1. **Prerequisites**:
   - PHP 7.4 or higher
   - Python 3.7 or higher
   - Web server (Apache/Nginx)
   - XAMPP/WAMP (for Windows)

2. **Setup**:
   ```bash
   # Clone the repository
   git clone <repository-url>
   cd Cisco-Nexus-Dashboard-Fabric-Controller-Stand-alone
   
   # Install Python dependencies
   pip install -r config/requirements.txt
   
   # Configure web server to point to the project directory
   # Access via: http://localhost/Cisco-Nexus-Dashboard-Fabric-Controller-Stand-alone/
   ```

3. **Configuration**:
   - Edit `src/php/includes/config.php` for application settings
   - Configure switch credentials in the web interface
   - Set up logging in `config/` directory

## Usage

### Main Entry Point

The application uses a single entry point (`index.php`) with routing:

```
http://localhost/project/?page=dashboard
http://localhost/project/?page=interfaces
http://localhost/project/?page=vlans
```

### Available Pages

- `dashboard` - Main dashboard
- `interfaces` - Interface management
- `vlans` - VLAN configuration
- `routing` - Routing protocols
- `monitoring` - System monitoring
- `settings` - Application settings
- `logs` - System logs
- `backup` - Configuration backup
- `system` - System management
- `aaa` - Authentication, Authorization, Accounting
- `acl` - Access Control Lists
- `alerts` - Alert management
- `api_examples` - API examples
- `bgp` - BGP configuration
- `config_management` - Configuration management
- `cpu_memory` - CPU and memory monitoring
- `dhcp_snooping` - DHCP snooping
- `environment` - Environment monitoring
- `firmware` - Firmware management
- `hsrp` - HSRP configuration
- `interface_config` - Interface configuration
- `interface_counters` - Interface counters
- `multicast` - Multicast configuration
- `nat` - NAT configuration
- `ntp` - NTP configuration
- `ospf` - OSPF configuration
- `port_channels` - Port channel configuration
- `port_security` - Port security
- `qos` - Quality of Service
- `scheduled_tasks` - Scheduled tasks
- `snmp` - SNMP configuration
- `spanning_tree` - Spanning tree configuration
- `static_routes` - Static routes
- `svi` - Switch Virtual Interface
- `troubleshooting` - Troubleshooting tools
- `vpc` - Virtual Port Channel
- `vtp` - VLAN Trunking Protocol

## File Organization

### PHP Controllers (`src/php/controllers/`)
All main PHP pages are organized here. Each file handles a specific functionality:
- `index.php` - Dashboard
- `interfaces.php` - Interface management
- `vlans.php` - VLAN configuration
- etc.

### Includes (`src/php/includes/`)
Shared PHP files:
- `config.php` - Application configuration
- `functions.php` - Common utility functions

### Assets (`src/assets/`)
Static files:
- `css/` - Stylesheets
- `js/` - JavaScript files
- `images/` - Image files

### Python Scripts (`src/python/`)
Python utilities and automation scripts:
- `main.py` - Main Python application
- `app.py` - Additional Python utilities

### Documentation (`docs/`)
All documentation files:
- `README.md` - This file
- `CHANGELOG.md` - Version history
- `FEATURES.md` - Feature documentation
- API documentation
- Configuration guides

### Configuration (`config/`)
Configuration files:
- `requirements.txt` - Python dependencies
- Environment-specific configurations

### Data (`data/`)
Application data storage:
- `backups/` - Configuration backups
- `dashboard_settings.json` - Dashboard settings
- `logs.json` - Application logs

### Logs (`logs/`)
Application log files:
- `application.log` - Main application log
- `nxapi_debug.log` - NX-API debug logs
- `data_manager_debug.log` - Data manager logs

## Development

### Adding New Features

1. Create a new PHP controller in `src/php/controllers/`
2. Add the route to `index.php` in the `$allowed_pages` array
3. Create any necessary includes in `src/php/includes/`
4. Add CSS/JS files to `src/assets/` as needed

### Code Standards

- Use consistent indentation (4 spaces)
- Follow PSR-4 autoloading standards
- Include proper error handling
- Add logging for important operations
- Sanitize all user inputs
- Use prepared statements for database queries

## Security

- All user inputs are sanitized
- CSRF protection is implemented
- Session management with timeout
- Secure cookie settings
- Input validation for IP addresses and MAC addresses

## Logging

The application uses a centralized logging system:
- Log levels: DEBUG, INFO, WARNING, ERROR
- Log file location: `logs/application.log`
- API requests are logged for debugging
- Error logging is enabled

## API Integration

The application integrates with Cisco Nexus switches via:
- NX-API REST API
- SSH connections
- SNMP for monitoring
- Custom automation scripts

## Troubleshooting

1. Check log files in `logs/` directory
2. Verify switch connectivity
3. Check PHP error logs
4. Validate configuration files
5. Test API connectivity

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions:
- Check the documentation in `docs/`
- Review the troubleshooting guide
- Check the logs for error messages
- Contact the development team

## Version History

See `docs/CHANGELOG.md` for detailed version history and changes. 