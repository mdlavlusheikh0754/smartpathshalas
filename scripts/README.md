# Smart Pathshala Scripts

This directory contains deployment and utility scripts for the Smart Pathshala system.

## 📜 Available Scripts

### Deployment Scripts
- **[deploy.sh](deploy.sh)** - Automated production deployment script for VPS servers
  ```bash
  ./scripts/deploy.sh
  ```

### ZKTime.Net Integration Scripts
- **[zktime_bidirectional_bridge.php](zktime_bidirectional_bridge.php)** - Bidirectional data sync between Laravel and ZKTime.Net
  ```bash
  php scripts/zktime_bidirectional_bridge.php
  ```

- **[zktime_sync_scheduler.bat](zktime_sync_scheduler.bat)** - Windows batch file for automated sync scheduling
  ```batch
  scripts\zktime_sync_scheduler.bat
  ```

## 🚀 Usage Instructions

### Production Deployment
1. Clone the repository to your VPS
2. Make the deploy script executable: `chmod +x scripts/deploy.sh`
3. Run the deployment: `./scripts/deploy.sh`

### ZKTime.Net Sync Setup
1. Install ZKTime.Net 3.3 software
2. Configure device connection
3. Run the bridge script manually or set up automated scheduling
4. Use the batch scheduler for Windows environments

## ⚙️ Configuration

Before running scripts, ensure:
- Proper file permissions are set
- Environment variables are configured
- Database connections are established
- ZKTime.Net software is installed (for device scripts)

## 📞 Support

For script-related issues:
- Check the main documentation in [docs/](../docs/)
- Review deployment guide for detailed instructions
- Create an issue on GitHub for bugs or improvements

---

**Smart Pathshala** - Professional Script Management 🔧