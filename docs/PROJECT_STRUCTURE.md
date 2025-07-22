# Project Structure Documentation

## Overview

The Cisco Nexus Dashboard Fabric Controller has been reorganized for better maintainability, scalability, and developer experience. This document outlines the new structure and explains the purpose of each directory and file.

## Directory Structure

```
Cisco-Nexus-Dashboard-Fabric-Controller-Stand-alone/
├── index.php                          # Main entry point with routing
├── .gitignore                         # Git ignore file
├── README.md                          # Main project documentation
├── src/                               # Source code directory
│   ├── php/                           # PHP application code
│   │   ├── controllers/               # Main PHP pages/controllers (35 files)
│   │   ├── includes/                  # Shared PHP files
│   │   │   ├── config.php             # Application configuration
│   │   │   └── functions.php          # Common utility functions
│   │   └── api/                       # API endpoints (empty - for future use)
│   ├── python/                        # Python scripts (2 files)
│   └── assets/                        # Static assets
│       ├── css/                       # Stylesheets (1 file)
│       ├── js/                        # JavaScript files (1 file)
│       ├── images/                    # Image files (1 file)
│       └── asset/                     # Additional assets (1 file)
├── docs/                              # Documentation (13 files)
├── config/                            # Configuration files (1 file)
├── data/                              # Data storage (3 files)
├── logs/                              # Log files (2 files)
├── tests/                             # Test files (3 subdirectories)
├── test/                              # Additional test files
└── scripts/                           # Utility scripts (1 file)
```

## Detailed Breakdown

### Root Directory Files

- **`index.php`** - Main entry point that handles routing to different pages
- **`.gitignore`** - Git ignore configuration
- **`README.md`** - Comprehensive project documentation

### Source Code (`src/`)

#### PHP Application (`src/php/`)

**Controllers (`src/php/controllers/`)**
Contains all the main PHP pages that handle specific functionality:
- `index.php` - Dashboard
- `interfaces.php` - Interface management
- `vlans.php` - VLAN configuration
- `routing.php` - Routing protocols
- `monitoring.php` - System monitoring
- `settings.php` - Application settings
- `logs.php` - System logs
- `backup.php` - Configuration backup
- `system.php` - System management
- `aaa.php` - Authentication, Authorization, Accounting
- `acl.php` - Access Control Lists
- `alerts.php` - Alert management
- `api_examples.php` - API examples
- `bgp.php` - BGP configuration
- `config_management.php` - Configuration management
- `cpu_memory.php` - CPU and memory monitoring
- `dhcp_snooping.php` - DHCP snooping
- `environment.php` - Environment monitoring
- `firmware.php` - Firmware management
- `hsrp.php` - HSRP configuration
- `interface_config.php` - Interface configuration
- `interface_counters.php` - Interface counters
- `multicast.php` - Multicast configuration
- `nat.php` - NAT configuration
- `ntp.php` - NTP configuration
- `ospf.php` - OSPF configuration
- `port_channels.php` - Port channel configuration
- `port_security.php` - Port security
- `qos.php` - Quality of Service
- `scheduled_tasks.php` - Scheduled tasks
- `snmp.php` - SNMP configuration
- `spanning_tree.php` - Spanning tree configuration
- `static_routes.php` - Static routes
- `svi.php` - Switch Virtual Interface
- `troubleshooting.php` - Troubleshooting tools
- `vpc.php` - Virtual Port Channel
- `vtp.php` - VLAN Trunking Protocol
- `navbar.php` - Navigation component
- `nxapi.php` - NX-API integration
- `data_manager.php` - Data management utilities

**Includes (`src/php/includes/`)**
Shared PHP files used across the application:
- `config.php` - Application configuration, constants, and settings
- `functions.php` - Common utility functions for logging, validation, API calls, etc.

**API (`src/php/api/`)**
Reserved for future API endpoint development.

#### Python Scripts (`src/python/`)
Python utilities and automation scripts:
- `main.py` - Main Python application
- `app.py` - Additional Python utilities

#### Assets (`src/assets/`)
Static files for the web interface:
- `css/` - Stylesheets (`styles.css`)
- `js/` - JavaScript files (`common.js`)
- `images/` - Image files (`image.png`)
- `asset/` - Additional assets (`list_of_all_api`)

### Documentation (`docs/`)
All project documentation:
- `README.md` - Main documentation
- `CHANGELOG.md` - Version history
- `FEATURES.md` - Feature documentation
- `VERSION.md` - Version information
- `STATUS_V6.md` - Status documentation
- `NEXUS_API_COMMANDS.md` - API command reference
- `PORT_STATUS_GUIDE.md` - Port status documentation
- `FINAL_COMPLETION.md` - Completion status
- `FINAL_PACKAGE_INFO.md` - Package information
- `FINAL_STATUS.md` - Final status
- `COMPLETION_STATUS.md` - Completion status
- `todo.md` - TODO list
- `Cisco Nexus 3548 Switch Configuration Reference for Portal Design.md` - Configuration reference

### Configuration (`config/`)
Configuration files:
- `requirements.txt` - Python dependencies

### Data (`data/`)
Application data storage:
- `backups/` - Configuration backups directory
- `dashboard_settings.json` - Dashboard settings
- `logs.json` - Application logs

### Logs (`logs/`)
Application log files:
- `nxapi_debug.log` - NX-API debug logs
- `data_manager_debug.log` - Data manager logs

### Tests (`tests/`)
Test files organized by type:
- `Unit/` - Unit tests
- `Integration/` - Integration tests
- `E2E/` - End-to-end tests

### Scripts (`scripts/`)
Utility scripts:
- `test-server.php` - Test server utility

## Benefits of New Structure

### 1. **Separation of Concerns**
- PHP controllers are separated from includes
- Static assets are organized by type
- Configuration is centralized

### 2. **Maintainability**
- Clear file organization makes it easier to find and modify code
- Shared functions are centralized in includes
- Configuration is externalized

### 3. **Scalability**
- Easy to add new controllers without cluttering the root
- API endpoints can be developed separately
- Assets are organized for better caching

### 4. **Developer Experience**
- Clear structure makes onboarding easier
- Documentation is centralized
- Consistent file naming and organization

### 5. **Security**
- Sensitive files are organized in appropriate directories
- Configuration can be properly secured
- Logs are separated from application code

## Migration Notes

### URL Changes
The application now uses a routing system. Old direct file access URLs like:
```
http://localhost/project/interfaces.php
```

Are now accessed via:
```
http://localhost/project/?page=interfaces
```

### File References
When updating file references in the code:
- CSS files: `src/assets/css/styles.css`
- JS files: `src/assets/js/common.js`
- Images: `src/assets/images/image.png`
- Includes: `src/php/includes/config.php`

### Configuration
Update any hardcoded paths to use the new structure or the constants defined in `config.php`.

## Future Enhancements

1. **API Development**: The `src/php/api/` directory is ready for REST API development
2. **View Templates**: Consider adding a `views/` directory for template files
3. **Database**: Add database configuration and models if needed
4. **Authentication**: Implement proper authentication system
5. **Caching**: Add caching layer for better performance

## Maintenance

### Adding New Controllers
1. Create the PHP file in `src/php/controllers/`
2. Add the route to `index.php` in the `$allowed_pages` array
3. Update this documentation if needed

### Adding New Assets
1. Place CSS files in `src/assets/css/`
2. Place JS files in `src/assets/js/`
3. Place images in `src/assets/images/`
4. Update references using the `asset_url()` function

### Configuration Changes
1. Update `src/php/includes/config.php` for application settings
2. Update `config/requirements.txt` for Python dependencies
3. Document changes in appropriate documentation files

This structure provides a solid foundation for continued development and maintenance of the Cisco Nexus Dashboard Fabric Controller. 