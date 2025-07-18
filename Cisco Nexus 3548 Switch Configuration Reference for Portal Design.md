# Cisco Nexus 3548 Switch Configuration Reference for Portal Design

This document extracts all useful commands, settings, and configurations from the Cisco Nexus 3548 Switch documentation that can be implemented in a network management portal.

## Table of Contents

1. [Layer 2 Interface Configuration](#layer-2-interface-configuration)
2. [Layer 3 Interface Configuration](#layer-3-interface-configuration)
3. [Port-Channel (EtherChannel) Configuration](#port-channel-etherchannel-configuration)
4. [Static NAT Configuration](#static-nat-configuration)
5. [System Management & Monitoring](#system-management--monitoring)
6. [SNMP Configuration](#snmp-configuration)
7. [NTP Configuration](#ntp-configuration)
8. [System Logging Configuration](#system-logging-configuration)
9. [SPAN/ERSPAN Configuration](#spanerspan-configuration)
10. [Session Manager](#session-manager)
11. [Scheduler Configuration](#scheduler-configuration)
12. [Two-Stage Configuration Commit](#two-stage-configuration-commit)

---

## Layer 2 Interface Configuration

### Basic Interface Commands

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enter Interface Mode** | `interface ethernet <slot>/<port>` | Configure specific interface |
| **Interface Description** | `description <text>` | Add description to interface |
| **Shutdown/No Shutdown** | `shutdown` / `no shutdown` | Disable/enable interface |
| **Port Mode** | `switchport mode {access \| trunk}` | Set port as access or trunk |
| **Interface Speed** | `speed {10 \| 100 \| 1000 \| 10000 \| auto}` | Configure interface speed |
| **Auto Negotiation** | `negotiate auto` / `no negotiate auto` | Enable/disable auto negotiation |

### UDLD (Unidirectional Link Detection)

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable UDLD Globally** | `feature udld` | Enable UDLD feature on switch |
| **Disable UDLD Globally** | `no feature udld` | Disable UDLD feature on switch |
| **Interface UDLD Mode** | `udld {enable \| disable \| aggressive}` | Configure UDLD per interface |
| **Show UDLD Status** | `show udld global` | Display global UDLD status |
| **Show Interface UDLD** | `show udld interface` | Display interface UDLD status |

**Default UDLD Configuration:**
- Global state: Disabled
- Aggressive mode: Disabled
- Fiber-optic ports: Enabled by default
- Copper ports: Disabled by default

### CDP (Cisco Discovery Protocol)

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable/Disable CDP** | `cdp enable` / `no cdp enable` | Enable/disable CDP per interface |
| **CDP Timer** | `cdp timer <seconds>` | Set CDP update frequency (default: 60s) |
| **CDP Hold Time** | `cdp holdtime <seconds>` | Set CDP hold time (default: 180s) |
| **Show CDP Neighbors** | `show cdp neighbors` | Display CDP neighbor information |

### Error-Disabled State Management

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Error Detection** | `errdisable detect cause <cause>` | Enable error detection for specific cause |
| **Enable Auto Recovery** | `errdisable recovery cause <cause>` | Enable auto recovery for specific cause |
| **Recovery Interval** | `errdisable recovery interval <seconds>` | Set recovery time interval |
| **Show Error-Disabled** | `show interface status err-disabled` | Display error-disabled interfaces |

### SVI (Switch Virtual Interface) Autostate

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Interface-VLAN** | `feature interface-vlan` | Enable VLAN interface feature |
| **Create VLAN Interface** | `interface vlan <vlan-id>` | Create VLAN interface |
| **Disable Autostate** | `no autostate` | Disable autostate per SVI |
| **Global Autostate Setting** | `system default interface-vlan no autostate` | Set global autostate behavior |

### Hardware Profile and Port Modes

| Feature | Commands | Description |
|---------|----------|-------------|
| **Port Mode Configuration** | `hardware profile portmode 48x10G+4x40G` | Configure port mode (requires reload) |
| **Reset to Default** | `no hardware profile portmode` | Reset to default port mode |
| **Show Port Mode** | `show hardware profile portmode` | Display current port mode |

---

## Layer 3 Interface Configuration

### Routed Interfaces

| Feature | Commands | Description |
|---------|----------|-------------|
| **Convert to Layer 3** | `no switchport` | Convert switchport to routed interface |
| **IP Address** | `ip address <ip-address>/<length>` | Assign IP address to interface |
| **IPv6 Address** | `ipv6 address <ipv6-address>/<length>` | Assign IPv6 address to interface |

### VLAN Interfaces (SVIs)

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Feature** | `feature interface-vlan` | Enable VLAN interface feature |
| **Create VLAN Interface** | `interface vlan <vlan-id>` | Create VLAN interface |
| **IP Configuration** | `ip address <ip-address>/<length>` | Configure IP on VLAN interface |

### Subinterfaces

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create Subinterface** | `interface ethernet <slot>/<port>.<number>` | Create subinterface |
| **Encapsulation** | `encapsulation dot1q <vlan-id>` | Configure 802.1Q encapsulation |

### Loopback Interfaces

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create Loopback** | `interface loopback <instance>` | Create loopback interface |
| **IP Configuration** | `ip address <ip-address>/<length>` | Configure IP on loopback |

### VRF Assignment

| Feature | Commands | Description |
|---------|----------|-------------|
| **Assign to VRF** | `vrf member <vrf-name>` | Assign interface to VRF |
| **Show VRF Interfaces** | `show ip interface brief vrf <vrf-name>` | Display VRF interface status |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show ip interface brief` | Display Layer 3 interface summary |
| `show interface <type> <slot/port>` | Display detailed interface information |
| `show ip route` | Display routing table |
| `show ip route vrf <vrf-name>` | Display VRF routing table |

---

## Port-Channel (EtherChannel) Configuration

### Basic Port-Channel Setup

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create Port-Channel** | `interface port-channel <channel-number>` | Create port-channel interface |
| **Add Member Port** | `channel-group <channel-number> mode {on \| active \| passive}` | Add interface to port-channel |
| **Remove Member Port** | `no channel-group` | Remove interface from port-channel |

### LACP Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable LACP** | `feature lacp` | Enable LACP globally |
| **LACP Active Mode** | `channel-group <number> mode active` | Set LACP active mode |
| **LACP Passive Mode** | `channel-group <number> mode passive` | Set LACP passive mode |
| **LACP Port Priority** | `lacp port-priority <priority>` | Set LACP port priority |
| **LACP System Priority** | `lacp system-priority <priority>` | Set LACP system priority |
| **LACP Fast Timer** | `lacp rate fast` | Enable LACP fast timer |
| **Min Links** | `lacp min-links <number>` | Set minimum active links |

### Load Balancing

| Feature | Commands | Description |
|---------|----------|-------------|
| **Load Balance Method** | `port-channel load-balance ethernet {source-mac \| destination-mac \| source-dest-mac \| source-ip \| destination-ip \| source-dest-ip}` | Configure load balancing method |
| **Show Load Balance** | `show port-channel load-balance` | Display load balancing configuration |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show port-channel summary` | Display port-channel summary |
| `show port-channel database` | Display port-channel database |
| `show lacp neighbor` | Display LACP neighbor information |
| `show lacp interface <interface>` | Display LACP interface details |

---

## Static NAT Configuration

**Note: Requires LAN Base license**

### Basic NAT Setup

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable NAT** | `feature nat` | Enable NAT feature |
| **Inside Interface** | `ip nat inside` | Configure inside NAT interface |
| **Outside Interface** | `ip nat outside` | Configure outside NAT interface |

### Static NAT Rules

| Feature | Commands | Description |
|---------|----------|-------------|
| **Inside Source NAT** | `ip nat inside source static <local-ip> <global-ip>` | Configure inside source translation |
| **Outside Source NAT** | `ip nat outside source static <global-ip> <local-ip>` | Configure outside source translation |
| **Static PAT (TCP)** | `ip nat inside source static tcp <local-ip> <local-port> <global-ip> <global-port>` | Configure TCP port translation |
| **Static PAT (UDP)** | `ip nat inside source static udp <local-ip> <local-port> <global-ip> <global-port>` | Configure UDP port translation |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show ip nat translations` | Display NAT translation table |
| `show ip nat statistics` | Display NAT statistics |

---

## System Management & Monitoring

### Configuration Management

| Feature | Commands | Description |
|---------|----------|-------------|
| **Save Configuration** | `copy running-config startup-config` | Save running config to startup |
| **Show Running Config** | `show running-config` | Display current configuration |
| **Show Startup Config** | `show startup-config` | Display startup configuration |

### Checkpoint and Rollback

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create Checkpoint** | `checkpoint <name>` | Create configuration checkpoint |
| **Rollback to Checkpoint** | `rollback running-config checkpoint <name>` | Rollback to checkpoint |
| **Show Checkpoints** | `show checkpoint summary` | Display available checkpoints |
| **Compare Checkpoints** | `show diff rollback-patch <checkpoint1> <checkpoint2>` | Compare checkpoints |

### System Information

| Command | Description |
|---------|-------------|
| `show version` | Display system version information |
| `show module` | Display module information |
| `show environment` | Display environmental status |
| `show system uptime` | Display system uptime |
| `show clock` | Display system clock |

---

## SNMP Configuration

### Basic SNMP Setup

| Feature | Commands | Description |
|---------|----------|-------------|
| **SNMP User** | `snmp-server user <username> <group> [auth {md5 \| sha} <auth-password>] [priv {des \| aes-128} <priv-password>]` | Configure SNMP user |
| **SNMP Community** | `snmp-server community <string> group {ro \| rw}` | Configure SNMP community |
| **SNMP Host** | `snmp-server host <ip-address> [traps \| informs] [version {1 \| 2c \| 3}] <community>` | Configure SNMP notification receiver |

### SNMP Security

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enforce Encryption** | `snmp-server user <username> enforcePriv` | Enforce encryption for user |
| **Global Enforce Encryption** | `snmp-server globalEnforcePriv` | Enforce encryption globally |
| **Filter Requests** | `snmp-server community <string> use-acl <acl-name>` | Filter SNMP requests with ACL |

### SNMP Notifications

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Traps** | `snmp-server enable traps` | Enable SNMP traps globally |
| **Enable Link Traps** | `snmp-server enable traps link` | Enable link up/down traps |
| **Disable Interface Traps** | `snmp trap link-status` / `no snmp trap link-status` | Enable/disable per interface |

### SNMP System Information

| Feature | Commands | Description |
|---------|----------|-------------|
| **System Contact** | `snmp-server contact <text>` | Set system contact information |
| **System Location** | `snmp-server location <text>` | Set system location information |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show snmp` | Display SNMP configuration |
| `show snmp user` | Display SNMP users |
| `show snmp community` | Display SNMP communities |
| `show snmp host` | Display SNMP notification receivers |

---

## NTP Configuration

### Basic NTP Setup

| Feature | Commands | Description |
|---------|----------|-------------|
| **NTP Server** | `ntp server <ip-address> [key <key-id>] [prefer] [use-vrf <vrf-name>]` | Configure NTP server |
| **NTP Peer** | `ntp peer <ip-address> [key <key-id>] [prefer] [use-vrf <vrf-name>]` | Configure NTP peer |
| **NTP Source Interface** | `ntp source-interface <interface>` | Set NTP source interface |
| **NTP Source IP** | `ntp source <ip-address>` | Set NTP source IP address |

### NTP Authentication

| Feature | Commands | Description |
|---------|----------|-------------|
| **Authentication Key** | `ntp authentication-key <key-id> md5 <key-string>` | Configure authentication key |
| **Trusted Key** | `ntp trusted-key <key-id>` | Mark key as trusted |
| **Enable Authentication** | `ntp authenticate` | Enable NTP authentication |

### NTP Access Control

| Feature | Commands | Description |
|---------|----------|-------------|
| **Access Group** | `ntp access-group {peer \| serve \| serve-only \| query-only} <acl-name>` | Configure NTP access restrictions |

### NTP Logging

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Logging** | `ntp logging` | Enable NTP logging |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show ntp peers` | Display NTP peer status |
| `show ntp status` | Display NTP status |
| `show ntp associations` | Display NTP associations |
| `show ntp statistics` | Display NTP statistics |

---

## System Logging Configuration

### Console and Monitor Logging

| Feature | Commands | Description |
|---------|----------|-------------|
| **Console Logging** | `logging console [<severity-level>]` | Enable console logging (0-7) |
| **Monitor Logging** | `logging monitor [<severity-level>]` | Enable monitor logging for SSH/Telnet |
| **Disable Console Logging** | `no logging console` | Disable console logging |
| **Disable Monitor Logging** | `no logging monitor` | Disable monitor logging |

### File Logging

| Feature | Commands | Description |
|---------|----------|-------------|
| **Log to File** | `logging logfile <filename> [<severity-level>] [size <bytes>]` | Configure file logging |
| **Log File Size** | `logging logfile <filename> size <bytes>` | Set log file size |

### Syslog Server Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Syslog Server** | `logging server <ip-address> [<severity-level>] [port <port>] [use-vrf <vrf>]` | Configure remote syslog server |
| **Syslog Facility** | `logging server <ip-address> facility <facility>` | Set syslog facility |

### Module and Facility Logging

| Feature | Commands | Description |
|---------|----------|-------------|
| **Module Logging** | `logging module [<severity-level>]` | Configure module logging level |
| **Facility Logging** | `logging level <facility> <severity-level>` | Configure facility logging level |

### Logging Timestamps

| Feature | Commands | Description |
|---------|----------|-------------|
| **Timestamp Format** | `logging timestamp {microseconds \| milliseconds \| seconds}` | Configure timestamp precision |

### RFC 5424 Compliance

| Feature | Commands | Description |
|---------|----------|-------------|
| **RFC 5424 Format** | `logging rfc-strict 5424` | Enable RFC 5424 syslog format |
| **RFC 5424 Full** | `logging rfc-strict 5424 full` | Enable full RFC 5424 compliance |

### Severity Levels

| Level | Keyword | Description |
|-------|---------|-------------|
| 0 | emergency | System is unusable |
| 1 | alert | Action must be taken immediately |
| 2 | critical | Critical conditions |
| 3 | error | Error conditions |
| 4 | warning | Warning conditions |
| 5 | notification | Normal but significant condition |
| 6 | informational | Informational messages |
| 7 | debugging | Debug-level messages |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show logging` | Display logging configuration |
| `show logging console` | Display console logging configuration |
| `show logging monitor` | Display monitor logging configuration |
| `show logging server` | Display syslog server configuration |
| `show logging logfile` | Display log file contents |

---

## SPAN/ERSPAN Configuration

### SPAN Session Management

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create SPAN Session** | `monitor session <session-number>` | Create SPAN session |
| **Delete SPAN Session** | `no monitor session <session-number>` | Delete SPAN session |
| **Session Description** | `description <text>` | Add description to SPAN session |

### SPAN Source Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Source Interface** | `source interface <interface> [rx \| tx \| both]` | Configure source interface |
| **Source VLAN** | `source vlan <vlan-id> [rx \| tx \| both]` | Configure source VLAN |
| **Source Port-Channel** | `source port-channel <number> [rx \| tx \| both]` | Configure source port-channel |

### SPAN Destination Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Destination Interface** | `destination interface <interface>` | Configure destination interface |

### SPAN Session Control

| Feature | Commands | Description |
|---------|----------|-------------|
| **Activate Session** | `no shut` | Activate SPAN session |
| **Suspend Session** | `shut` | Suspend SPAN session |

### SPAN Filtering

| Feature | Commands | Description |
|---------|----------|-------------|
| **VLAN Filter** | `filter vlan <vlan-list>` | Filter specific VLANs |
| **Access List Filter** | `filter access-group <acl-name>` | Filter using access list |

### SPAN Sampling

| Feature | Commands | Description |
|---------|----------|-------------|
| **Sampling Rate** | `rate <rate>` | Configure sampling rate (1-1000000) |

### SPAN Truncation

| Feature | Commands | Description |
|---------|----------|-------------|
| **Truncate Size** | `mtu <bytes>` | Configure truncation size |

### ERSPAN Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **ERSPAN Source** | `monitor session <number> type erspan-source` | Create ERSPAN source session |
| **ERSPAN Destination** | `monitor session <number> type erspan-destination` | Create ERSPAN destination session |
| **ERSPAN ID** | `erspan-id <id>` | Configure ERSPAN session ID |
| **IP DSCP** | `ip dscp <value>` | Configure DSCP marking |
| **IP TTL** | `ip ttl <value>` | Configure TTL value |
| **VRF** | `vrf <vrf-name>` | Configure VRF for ERSPAN |
| **Destination IP** | `destination ip <ip-address>` | Configure destination IP |
| **Origin IP** | `origin ip-address <ip-address>` | Configure origin IP address |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show monitor session` | Display all SPAN sessions |
| `show monitor session <number>` | Display specific SPAN session |
| `show monitor session all` | Display detailed SPAN information |

---

## Session Manager

Session Manager allows batch configuration changes, particularly useful for ACL modifications.

### Session Management

| Feature | Commands | Description |
|---------|----------|-------------|
| **Create Session** | `configure session <name>` | Create configuration session |
| **Verify Session** | `verify` | Verify session configuration |
| **Commit Session** | `commit` | Commit session changes |
| **Abort Session** | `abort` | Abort session changes |
| **Save Session** | `save <filename>` | Save session to file |

### Session Operations

| Feature | Commands | Description |
|---------|----------|-------------|
| **Show Session** | `show configuration session [<name>]` | Display session configuration |
| **Show Session Status** | `show configuration session status` | Display session status |

### Example Session Workflow

```
configure session MyACLChanges
ip access-list MyACL
  10 permit tcp any any eq 80
  20 permit tcp any any eq 443
  30 deny ip any any
verify
commit
```

---

## Scheduler Configuration

### Basic Scheduler Setup

| Feature | Commands | Description |
|---------|----------|-------------|
| **Enable Scheduler** | `feature scheduler` | Enable scheduler feature |
| **Disable Scheduler** | `no feature scheduler` | Disable scheduler feature |

### Job Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Define Job** | `scheduler job name <job-name>` | Create scheduler job |
| **Job Command** | `<command>` | Add command to job |
| **Delete Job** | `no scheduler job name <job-name>` | Delete scheduler job |

### Time Table Configuration

| Feature | Commands | Description |
|---------|----------|-------------|
| **Define Time Table** | `scheduler schedule name <schedule-name>` | Create schedule |
| **Time Range** | `time daily <start-time> [to <end-time>]` | Set daily time range |
| **Time Weekly** | `time weekly <day> <start-time> [to <end-time>]` | Set weekly schedule |
| **Associate Job** | `job name <job-name>` | Associate job with schedule |

### Scheduler Logging

| Feature | Commands | Description |
|---------|----------|-------------|
| **Log File Size** | `scheduler logfile size <size>` | Set log file size (16-1024 KB) |
| **Clear Log** | `clear scheduler logfile` | Clear scheduler log |

### Remote Authentication

| Feature | Commands | Description |
|---------|----------|-------------|
| **User Authentication** | `scheduler aaa-authentication username <name> password <password>` | Configure remote user authentication |

### Verification Commands

| Command | Description |
|---------|-------------|
| `show scheduler config` | Display scheduler configuration |
| `show scheduler job` | Display configured jobs |
| `show scheduler schedule` | Display configured schedules |
| `show scheduler logfile` | Display scheduler log |

---

## Two-Stage Configuration Commit

Two-stage configuration commit provides a safety mechanism for configuration changes.

### Basic Two-Stage Commit

| Feature | Commands | Description |
|---------|----------|-------------|
| **Configure Terminal Commit** | `configure terminal commit` | Enter two-stage commit mode |
| **Commit Changes** | `commit` | Commit pending changes |
| **Abort Changes** | `abort` | Abort pending changes |
| **Show Commit ID** | `show commit list` | Display commit IDs |

### Rollback Operations

| Feature | Commands | Description |
|---------|----------|-------------|
| **Rollback to Commit** | `rollback running-config commit <commit-id>` | Rollback to specific commit |
| **Show Rollback** | `show rollback log` | Display rollback history |

### Session Configuration Viewing

| Feature | Commands | Description |
|---------|----------|-------------|
| **Show Session Config** | `show configuration session` | Display current session configuration |
| **Show Pending Changes** | `show configuration commit changes` | Display pending changes |

---

## Additional Useful Commands for Portal Implementation

### Interface Status and Statistics

| Command | Description |
|---------|-------------|
| `show interface brief` | Display interface summary |
| `show interface status` | Display interface status |
| `show interface counters` | Display interface counters |
| `show interface counters errors` | Display interface error counters |
| `show interface description` | Display interface descriptions |

### MAC Address and ARP Tables

| Command | Description |
|---------|-------------|
| `show mac address-table` | Display MAC address table |
| `show ip arp` | Display ARP table |
| `show ip arp vrf <vrf-name>` | Display VRF ARP table |

### VLAN Information

| Command | Description |
|---------|-------------|
| `show vlan` | Display VLAN information |
| `show vlan brief` | Display VLAN summary |
| `show spanning-tree` | Display spanning tree information |

### System Resources

| Command | Description |
|---------|-------------|
| `show processes cpu` | Display CPU utilization |
| `show system resources` | Display system resource usage |
| `show hardware capacity` | Display hardware capacity |

### License Information

| Command | Description |
|---------|-------------|
| `show license` | Display license information |
| `show license usage` | Display license usage |

---

## Portal Implementation Notes

### Command Categories for Portal Organization

1. **Interface Management**
   - Layer 2/3 interface configuration
   - Port-channel configuration
   - Interface monitoring

2. **Network Services**
   - SNMP configuration
   - NTP configuration
   - System logging

3. **Monitoring and Troubleshooting**
   - SPAN/ERSPAN configuration
   - Interface statistics
   - System health monitoring

4. **Security and Access Control**
   - NAT configuration
   - SNMP security
   - Access control lists

5. **System Management**
   - Configuration management
   - Scheduler configuration
   - Session management

### Validation Requirements

- Interface names must follow format: ethernet <slot>/<port>
- IP addresses must be valid IPv4/IPv6 format
- VLAN IDs must be in range 1-4094
- Port-channel numbers must be in valid range
- Severity levels must be 0-7 for logging
- Time formats must follow HH:MM format for scheduler

### Error Handling Considerations

- Check for feature enablement before configuration
- Validate interface existence before configuration
- Ensure proper licensing for advanced features
- Handle configuration dependencies (e.g., VRF creation before assignment)
- Implement rollback mechanisms for failed configurations

This reference provides a comprehensive foundation for implementing Cisco Nexus 3548 switch management capabilities in a network portal interface.

