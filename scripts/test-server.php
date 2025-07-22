<?php
/**
 * Simple PHP Test Server for Nexus Dashboard
 * Run with: php test-server.php
 * Then open: http://localhost:8080
 */

$host = 'localhost';
$port = 8080;
$docroot = __DIR__;

echo "🚀 Starting Nexus Dashboard Test Server...\n";
echo "📁 Document Root: $docroot\n";
echo "🌐 Server URL: http://$host:$port\n";
echo "📋 Available Pages:\n";
echo "   • Interface Status: http://$host:$port/interfaces.php\n";
echo "   • Interface Config: http://$host:$port/interface_config.php\n";
echo "   • VLAN Management: http://$host:$port/vlans.php\n";
echo "   • SVI Management: http://$host:$port/svi.php\n";
echo "   • Route Table: http://$host:$port/routing.php\n";
echo "\n💡 Tips:\n";
echo "   • All features work with mock data\n";
echo "   • Test responsive design on mobile\n";
echo "   • Try auto-refresh and filtering features\n";
echo "   • Use Ctrl+C to stop the server\n";
echo "\n" . str_repeat("=", 50) . "\n";

// Start the built-in PHP server
$command = "php -S $host:$port -t $docroot";
passthru($command);
?>

