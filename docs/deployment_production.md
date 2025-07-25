# Production Deployment Guide

## Prerequisites

### Server Requirements
- **OS**: Ubuntu 20.04 LTS or CentOS 8+
- **CPU**: 2+ cores (4+ recommended)
- **RAM**: 4GB minimum (8GB+ recommended)
- **Storage**: 50GB+ SSD
- **Network**: Stable internet connection

### Software Stack
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.0+ with required extensions
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **SSL**: Valid SSL certificate
- **Backup**: Automated backup solution

## Step 1: Server Preparation

### Update System
```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

### Install Required Packages
```bash
# Ubuntu/Debian
sudo apt install -y apache2 php8.0 php8.0-mysql php8.0-gd php8.0-mbstring \
    php8.0-xml php8.0-curl php8.0-zip mysql-server unzip git

# CentOS/RHEL
sudo yum install -y httpd php php-mysql php-gd php-mbstring \
    php-xml php-curl php-zip mysql-server unzip git
```

### Configure PHP
```bash
sudo nano /etc/php/8.0/apache2/php.ini
```

Update these settings:
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
date.timezone = Asia/Kolkata
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
```

## Step 2: Database Setup

### Secure MySQL Installation
```bash
sudo mysql_secure_installation
```

### Create Database and User
```sql
-- Login to MySQL
sudo mysql -u root -p

-- Create database
CREATE DATABASE payroll_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'payroll_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON payroll_system.* TO 'payroll_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Import Database Schema
```bash
mysql -u payroll_user -p payroll_system < /path/to/database.sql
```

## Step 3: Application Deployment

### Create Application Directory
```bash
sudo mkdir -p /var/www/payroll-system
sudo chown -R www-data:www-data /var/www/payroll-system
```

### Deploy Application Files
```bash
# Upload files via SCP, SFTP, or Git
# Example using Git:
cd /var/www/payroll-system
sudo git clone https://github.com/your-repo/payroll-system.git .

# Or upload via SCP:
scp -r ./payroll-system/* user@server:/var/www/payroll-system/
```

### Set Permissions
```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/payroll-system

# Set directory permissions
sudo find /var/www/payroll-system -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/payroll-system -type f -exec chmod 644 {} \;

# Make uploads directory writable
sudo chmod -R 777 /var/www/payroll-system/uploads/

# Secure config files
sudo chmod 600 /var/www/payroll-system/config/*.php
```

## Step 4: Configuration

### Database Configuration
```bash
sudo nano /var/www/payroll-system/config/database.php
```

Update with production credentials:
```php
<?php
class Database {
    private $host = 'localhost';
    private $database = 'payroll_system';
    private $username = 'payroll_user';
    private $password = 'STRONG_PASSWORD_HERE';
    // ... rest of the configuration
}
```

### Application Configuration
```bash
sudo nano /var/www/payroll-system/config/config.php
```

Update production settings:
```php
<?php
// Production settings
define('APP_NAME', 'PayrollPro Enterprise');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'https://your-domain.com');

// Security
define('SESSION_TIMEOUT', 1800);
define('CSRF_TOKEN_EXPIRY', 3600);

// Disable debug mode
error_reporting(0);
ini_set('display_errors', 0);

// Email configuration
define('SMTP_HOST', 'your-smtp-server.com');
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-email-password');
define('FROM_EMAIL', 'noreply@your-domain.com');
define('FROM_NAME', 'PayrollPro System');
```

## Step 5: Web Server Configuration

### Apache Configuration
```bash
sudo nano /etc/apache2/sites-available/payroll-system.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/payroll-system/public
    
    # Redirect to HTTPS
    Redirect permanent / https://your-domain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName your-domain.com
    DocumentRoot /var/www/payroll-system/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
    
    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; img-src 'self' data: https:; font-src 'self' https:;"
    
    # Directory Configuration
    <Directory /var/www/payroll-system/public>
        AllowOverride All
        Require all granted
        
        # Rewrite rules
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php [QSA,L]
    </Directory>
    
    # Deny access to sensitive directories
    <Directory /var/www/payroll-system/config>
        Require all denied
    </Directory>
    
    <Directory /var/www/payroll-system/app>
        Require all denied
    </Directory>
    
    <Directory /var/www/payroll-system/supabase>
        Require all denied
    </Directory>
    
    # Error and Access Logs
    ErrorLog ${APACHE_LOG_DIR}/payroll_error.log
    CustomLog ${APACHE_LOG_DIR}/payroll_access.log combined
    LogLevel warn
</VirtualHost>
```

### Enable Site and Modules
```bash
# Enable required modules
sudo a2enmod rewrite ssl headers

# Enable site
sudo a2ensite payroll-system.conf
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

## Step 6: SSL Certificate

### Using Let's Encrypt
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run

# Setup auto-renewal cron job
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

## Step 7: Security Hardening

### Firewall Configuration
```bash
# Enable UFW
sudo ufw enable

# Allow SSH, HTTP, and HTTPS
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443

# Check status
sudo ufw status
```

### File Security
```bash
# Create .htaccess for additional security
sudo nano /var/www/payroll-system/public/.htaccess
```

```apache
# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Hide sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(sql|log|conf)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# Compress files
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### MySQL Security
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
# Bind to localhost only
bind-address = 127.0.0.1

# Security settings
local-infile = 0
skip-show-database

# Performance settings
innodb_buffer_pool_size = 256M
query_cache_size = 64M
max_connections = 100

# Logging
log_error = /var/log/mysql/error.log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

## Step 8: Backup Configuration

### Database Backup Script
```bash
sudo nano /usr/local/bin/backup-payroll-db.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/payroll"
DB_NAME="payroll_system"
DB_USER="payroll_user"
DB_PASS="STRONG_PASSWORD_HERE"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/payroll-system/uploads/

# Remove backups older than 30 days
find $BACKUP_DIR -name "*.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

### Make Executable and Schedule
```bash
sudo chmod +x /usr/local/bin/backup-payroll-db.sh

# Add to crontab for daily backup at 2 AM
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-payroll-db.sh >> /var/log/payroll-backup.log 2>&1
```

## Step 9: Monitoring Setup

### Log Rotation
```bash
sudo nano /etc/logrotate.d/payroll-system
```

```
/var/log/apache2/payroll_*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 root root
    postrotate
        systemctl reload apache2
    endscript
}

/var/log/payroll-backup.log {
    weekly
    missingok
    rotate 12
    compress
    delaycompress
    notifempty
    create 644 root root
}
```

### System Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs fail2ban -y

# Configure fail2ban for SSH protection
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## Step 10: Performance Optimization

### Apache Optimization
```bash
sudo nano /etc/apache2/mods-available/mpm_prefork.conf
```

```apache
<IfModule mpm_prefork_module>
    StartServers 5
    MinSpareServers 5
    MaxSpareServers 10
    MaxRequestWorkers 150
    MaxConnectionsPerChild 3000
</IfModule>
```

### Enable Compression
```bash
sudo a2enmod deflate
sudo systemctl restart apache2
```

### MySQL Optimization
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
# InnoDB settings
innodb_buffer_pool_size = 512M
innodb_log_file_size = 128M
innodb_flush_log_at_trx_commit = 2

# Query cache
query_cache_type = 1
query_cache_size = 128M
query_cache_limit = 4M

# Connection settings
max_connections = 200
connect_timeout = 10
wait_timeout = 600
interactive_timeout = 600
```

## Step 11: Final Testing

### Test Checklist
- [ ] Application loads correctly
- [ ] Login functionality works
- [ ] Database connections successful
- [ ] File uploads working
- [ ] Email notifications sending
- [ ] SSL certificate valid and working
- [ ] Backup scripts running
- [ ] Performance acceptable
- [ ] Security headers present
- [ ] Error logging working

### Load Testing
```bash
# Install Apache Bench
sudo apt install apache2-utils -y

# Test concurrent users
ab -n 1000 -c 10 https://your-domain.com/login

# Test specific endpoints
ab -n 500 -c 5 https://your-domain.com/dashboard
```

### Security Testing
```bash
# Test SSL configuration
openssl s_client -connect your-domain.com:443

# Check security headers
curl -I https://your-domain.com

# Verify file permissions
ls -la /var/www/payroll-system/
```

## Step 12: Go Live

### DNS Configuration
- Point your domain to the server IP
- Configure any CDN if using
- Set up monitoring for DNS propagation

### Final Steps
1. Change default admin password
2. Create additional user accounts
3. Configure company settings
4. Import employee data
5. Set up payroll periods
6. Test all functionality
7. Train users
8. Monitor system performance

## Maintenance

### Daily Tasks
- Monitor system resources
- Check error logs
- Verify backups completed
- Review security logs

### Weekly Tasks
- Update system packages
- Review user access
- Check performance metrics
- Test backup restoration

### Monthly Tasks
- Security audit
- Performance optimization
- User training updates
- System documentation updates

## Troubleshooting

### Common Issues

**Database Connection Errors:**
```bash
# Check MySQL status
sudo systemctl status mysql

# Check connection
mysql -u payroll_user -p payroll_system
```

**Permission Issues:**
```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/payroll-system
sudo chmod -R 755 /var/www/payroll-system
sudo chmod -R 777 /var/www/payroll-system/uploads/
```

**SSL Certificate Issues:**
```bash
# Check certificate status
sudo certbot certificates

# Renew certificate
sudo certbot renew --force-renewal
```

**Performance Issues:**
```bash
# Check system resources
htop
df -h
free -h

# Check Apache status
sudo systemctl status apache2

# Check MySQL processes
mysqladmin -u root -p processlist
```

## Support

For production support:
- Monitor error logs: `/var/log/apache2/payroll_error.log`
- Check application logs: `/var/www/payroll-system/logs/`
- Review system logs: `/var/log/syslog`
- Database logs: `/var/log/mysql/error.log`

---

**Important**: Always test deployment procedures in a staging environment before applying to production.