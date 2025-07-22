# Cisco Nexus NX-API Commands Reference

## 📡 **System Information Commands**

### **Basic System Info**
```bash
show version                          # Software version, uptime, hardware info
show hostname                         # Current hostname
show system uptime                    # System uptime details
show system resources                 # CPU, memory usage
show environment                      # Temperature, power, fans
show hardware                         # Hardware inventory
show module                          # Module information
show inventory                       # Hardware inventory with serial numbers
show license                         # License information
show feature                         # Enabled features
```

### **System Status**
```bash
show processes                       # Running processes
show processes cpu                   # CPU usage by process
show system internal sysmgr service # System manager services
show cores                          # Core dumps
show tech-support                   # Complete technical support info
```

## 🔌 **Interface Commands**

### **Interface Status & Configuration**
```bash
show interface brief                 # Brief interface status (MAIN COMMAND)
show interface status               # Interface status with reasons
show interface description          # Interface descriptions
show interface                      # Detailed interface information
show interface ethernet             # Ethernet interfaces only
show interface mgmt                 # Management interfaces
show interface loopback             # Loopback interfaces
show interface port-channel         # Port-channel interfaces
show interface vlan                 # VLAN interfaces (SVIs)
```

### **Interface Statistics**
```bash
show interface counters             # Interface packet/byte counters
show interface counters errors     # Interface error counters
show interface counters detailed   # Detailed interface statistics
show interface utilization         # Interface utilization
show interface transceiver         # SFP/transceiver information
show interface capabilities        # Interface capabilities
```

### **Interface Configuration**
```bash
show running-config interface      # Interface running configuration
show startup-config interface      # Interface startup configuration
```

## 🏗️ **VLAN Commands**

### **VLAN Information**
```bash
show vlan                          # VLAN database
show vlan brief                    # Brief VLAN information
show vlan summary                  # VLAN summary
show vlan id <vlan-id>            # Specific VLAN information
show vlan name <vlan-name>        # VLAN by name
```

### **VLAN Membership**
```bash
show vlan membership              # VLAN membership by interface
show interface switchport         # Switchport configuration
show interface trunk             # Trunk interface information
```

## 🛣️ **Routing Commands**

### **Routing Table**
```bash
show ip route                     # IP routing table
show ip route summary            # Routing table summary
show ip route vrf <vrf-name>     # VRF-specific routes
show ipv6 route                  # IPv6 routing table
```

### **Routing Protocols**
```bash
# OSPF
show ip ospf                     # OSPF process information
show ip ospf neighbor           # OSPF neighbors
show ip ospf database           # OSPF database
show ip ospf interface          # OSPF interfaces
show ip ospf border-routers     # OSPF border routers

# BGP
show ip bgp                     # BGP table
show ip bgp summary            # BGP summary
show ip bgp neighbors          # BGP neighbors
show ip bgp network            # BGP networks

# EIGRP
show ip eigrp neighbors        # EIGRP neighbors
show ip eigrp topology         # EIGRP topology
show ip eigrp interfaces       # EIGRP interfaces

# Static Routes
show ip route static           # Static routes only
```

### **ARP & MAC Tables**
```bash
show ip arp                    # ARP table
show ip arp vrf <vrf-name>     # VRF-specific ARP
show mac address-table         # MAC address table
show mac address-table dynamic # Dynamic MAC entries
show mac address-table static  # Static MAC entries
```

## 🛡️ **Security Commands**

### **Access Control Lists**
```bash
show ip access-lists           # IPv4 ACLs
show ipv6 access-lists        # IPv6 ACLs
show mac access-lists         # MAC ACLs
show access-lists summary     # ACL summary
```

### **Port Security**
```bash
show port-security            # Port security status
show port-security address   # Secure MAC addresses
show port-security interface # Port security by interface
```

### **AAA & Authentication**
```bash
show aaa authentication      # Authentication configuration
show aaa authorization       # Authorization configuration
show aaa accounting          # Accounting configuration
show radius-server           # RADIUS server status
show tacacs-server           # TACACS+ server status
show users                   # Logged in users
```

## 🌐 **Spanning Tree Commands**

```bash
show spanning-tree           # STP status
show spanning-tree summary   # STP summary
show spanning-tree brief     # Brief STP information
show spanning-tree interface # STP per interface
show spanning-tree vlan      # STP per VLAN
show spanning-tree mst       # MST information
show spanning-tree root      # Root bridge information
```

## 🔗 **Port Channel & VPC Commands**

```bash
show port-channel summary    # Port-channel summary
show port-channel load-balance # Load balancing
show vpc                     # VPC status
show vpc peer-keepalive      # VPC peer keepalive
show vpc consistency-parameters # VPC consistency
```

## 🎯 **QoS Commands**

```bash
show class-map              # Class maps
show policy-map             # Policy maps
show policy-map interface   # Applied policies
show qos interface          # QoS statistics
show queuing interface      # Queue statistics
```

## 📊 **Monitoring & Logging Commands**

### **System Logs**
```bash
show logging                # System logs
show logging last <number>  # Last N log entries
show logging level          # Logging levels
show logging server         # Syslog servers
```

### **SNMP**
```bash
show snmp                   # SNMP configuration
show snmp community         # SNMP communities
show snmp user              # SNMP users
show snmp group             # SNMP groups
```

### **NTP**
```bash
show ntp peers              # NTP peers
show ntp status             # NTP status
show clock                  # System clock
```

## 🔧 **Configuration Commands**

### **Configuration Files**
```bash
show running-config         # Running configuration
show startup-config         # Startup configuration
show running-config | include <pattern> # Filtered config
show checkpoint summary     # Configuration checkpoints
```

### **Configuration Differences**
```bash
show diff rollback-patch    # Configuration differences
show rollback-patch         # Rollback patch information
```

## 🌍 **VRF & MPLS Commands**

```bash
show vrf                    # VRF instances
show vrf interface          # VRF interfaces
show ip route vrf all       # All VRF routes
show mpls forwarding-table  # MPLS forwarding table
show mpls interface         # MPLS interfaces
```

## 🔄 **High Availability Commands**

### **HSRP**
```bash
show hsrp                   # HSRP status
show hsrp brief             # Brief HSRP information
show hsrp interface         # HSRP per interface
```

### **VRRP**
```bash
show vrrp                   # VRRP status
show vrrp brief             # Brief VRRP information
```

## 📈 **Performance & Statistics**

```bash
show system resources       # System resource usage
show processes cpu          # CPU usage
show processes memory       # Memory usage
show interface counters rate # Interface rates
show hardware capacity      # Hardware capacity
```

## 🛠️ **Troubleshooting Commands**

```bash
show tech-support           # Complete tech support bundle
show cores                  # Core files
show system internal       # Internal system information
show diagnostic result      # Diagnostic test results
ping <destination>          # Ping test
traceroute <destination>    # Traceroute
```

## 📡 **CDP & LLDP Commands**

```bash
show cdp neighbors          # CDP neighbors
show cdp neighbors detail   # Detailed CDP information
show lldp neighbors         # LLDP neighbors
show lldp neighbors detail  # Detailed LLDP information
```

## 🔌 **Power & Environmental**

```bash
show environment power      # Power consumption
show environment temperature # Temperature sensors
show environment fan        # Fan status
show power                  # Power supply status
```

## 📋 **Configuration Commands (Write Operations)**

### **Interface Configuration**
```bash
# Configure interface (requires configuration mode)
interface ethernet1/1
  description <description>
  switchport mode access
  switchport access vlan <vlan-id>
  speed <speed>
  duplex <duplex>
  shutdown / no shutdown
```

### **VLAN Configuration**
```bash
# VLAN configuration
vlan <vlan-id>
  name <vlan-name>
  state active/suspend
```

### **Routing Configuration**
```bash
# Static route
ip route <network> <mask> <next-hop>

# OSPF configuration
router ospf <process-id>
  router-id <router-id>
  network <network> <wildcard> area <area>
```

## 🎯 **Most Important Commands for Dashboard**

### **Real-time Monitoring (High Priority)**
1. `show interface brief` - Main interface status
2. `show interface counters` - Traffic statistics
3. `show vlan brief` - VLAN information
4. `show ip route summary` - Routing summary
5. `show system resources` - System performance
6. `show environment` - Environmental status
7. `show version` - System information

### **Configuration Management**
1. `show running-config` - Current configuration
2. `show interface <interface>` - Specific interface config
3. `show vlan id <vlan>` - Specific VLAN config
4. `show ip route` - Routing table

### **Security & Monitoring**
1. `show ip arp` - ARP table
2. `show mac address-table` - MAC table
3. `show logging` - System logs
4. `show processes cpu` - CPU usage

---

## 📝 **Notes for Implementation**

### **Command Categories by Dashboard Menu:**
- **Interfaces**: `show interface brief`, `show interface counters`, `show interface status`
- **VLANs**: `show vlan brief`, `show vlan membership`, `show interface switchport`
- **Routing**: `show ip route`, `show ip ospf neighbor`, `show ip bgp summary`
- **Security**: `show ip access-lists`, `show port-security`, `show aaa authentication`
- **Monitoring**: `show system resources`, `show environment`, `show logging`
- **System**: `show version`, `show module`, `show inventory`

### **Refresh Intervals Recommendation:**
- **High Frequency (5-10s)**: Interface status, system resources
- **Medium Frequency (30s)**: Routing tables, ARP tables
- **Low Frequency (60s+)**: Configuration, logs, environmental

This comprehensive list covers all major NX-API commands available on Cisco Nexus switches!

