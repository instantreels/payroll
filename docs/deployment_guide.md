# Deployment Guide

## Production Deployment

### Prerequisites

- **Server**: Linux (Ubuntu 20.04+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.0 or higher with required extensions
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **SSL Certificate**: Required for production
- **Domain**: Configured and pointing to server

### Step 1: Server Preparation

#### Update System
```bash
sudo apt update && sudo apt upgrade -y
```

#### Install Required Packages
```bash
# Apache, PHP, and MySQL
sudo apt install apache2 php8.0 php8.0-mysql php8.0-gd php8.0-mbstring php8.0-xml php8.0-curl php8.0-zip mysql-server -y

# Enable Apache modules
sudo a2enmod rewrite ssl headers
```

### Step 2: Database Setup

#### Secure MySQL Installation
```bash
sudo mysql_secure_installation
```

#### Create Database and User
```sql
-- Login to MySQL
sudo mysql -u root -p

-- Create database
CREATE DATABASE payroll_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'payroll_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON payroll_system.* TO 'payroll_user'@'localhost';
FLUSH PRIVILEGES;
```

### Step 3: Application Deployment

#### Upload Files
```bash
# Create application directory
sudo mkdir -p /var/www/payroll-system

# Upload files (using SCP, SFTP, or Git)
sudo chown -R www-data:www-data /var/www/payroll-system
```

#### Set Permissions
```bash
# Set proper ownership
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

### Step 4: Configuration

#### Database Configuration
```bash
sudo nano /var/www/payroll-system/config/database.php
```

Update with production credentials:
```php
private $host = 'localhost';
private $database = 'payroll_system';
private $username = 'payroll_user';
private $password = 'STRONG_PASSWORD_HERE';
```

#### Application Configuration
```bash
sudo nano /var/www/payroll-system/config/config.php
```

Update production settings:
```php
// Production URL
define('BASE_URL', 'https://your-domain.com');

// Disable debug mode
error_reporting(0);
ini_set('display_errors', 0);

// Email configuration
define('SMTP_HOST', 'your-smtp-server.com');
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-email-password');
```

### Step 5: Web Server Configuration

#### Apache Virtual Host
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
    
    # Error and Access Logs
    ErrorLog ${APACHE_LOG_DIR}/payroll_error.log
    CustomLog ${APACHE_LOG_DIR}/payroll_access.log combined
</VirtualHost>
```

#### Enable Site
```bash
sudo a2ensite payroll-system.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

### Step 6: SSL Certificate

#### Using Let's Encrypt
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Step 7: Database Import

```bash
# Import database schema
mysql -u payroll_user -p payroll_system < /var/www/payroll-system/database.sql
```

### Step 8: Security Hardening

#### Firewall Configuration
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

#### PHP Security
```bash
sudo nano /etc/php/8.0/apache2/php.ini
```

Update security settings:
```ini
# Hide PHP version
expose_php = Off

# Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen

# File upload limits
upload_max_filesize = 5M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M

# Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
```

#### MySQL Security
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
# Bind to localhost only
bind-address = 127.0.0.1

# Disable remote root login
skip-networking = 0

# Log slow queries
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

### Step 9: Backup Setup

#### Database Backup Script
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

#### Make Executable and Schedule
```bash
sudo chmod +x /usr/local/bin/backup-payroll-db.sh

# Add to crontab
sudo crontab -e

# Add this line for daily backup at 2 AM
0 2 * * * /usr/local/bin/backup-payroll-db.sh >> /var/log/payroll-backup.log 2>&1
```

### Step 10: Monitoring Setup

#### Log Rotation
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
```

#### System Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Check system resources
htop
df -h
free -h
```

### Step 11: Performance Optimization

#### Apache Optimization
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

#### MySQL Optimization
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
# InnoDB settings
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2

# Query cache
query_cache_type = 1
query_cache_size = 64M
query_cache_limit = 2M

# Connection settings
max_connections = 100
connect_timeout = 10
wait_timeout = 600
```

### Step 12: Final Testing

#### Test Checklist
- [ ] Application loads correctly
- [ ] Login functionality works
- [ ] Database connections successful
- [ ] File uploads working
- [ ] Email notifications sending
- [ ] SSL certificate valid
- [ ] Backup scripts running
- [ ] Performance acceptable

#### Load Testing
```bash
# Install Apache Bench
sudo apt install apache2-utils -y

# Test concurrent users
ab -n 1000 -c 10 https://your-domain.com/login
```

## Maintenance

### Regular Tasks

#### Daily
- Monitor system resources
- Check error logs
- Verify backups completed

#### Weekly
- Update system packages
- Review security logs
- Test backup restoration

#### Monthly
- Update application if needed
- Review user access
- Performance optimization

### Troubleshooting

#### Common Issues

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
sudo certbot renew
```

### Scaling Considerations

For high-traffic deployments:

1. **Load Balancer**: Use Nginx or HAProxy
2. **Database**: Consider MySQL clustering or read replicas
3. **Caching**: Implement Redis or Memcached
4. **CDN**: Use CloudFlare or AWS CloudFront
5. **Monitoring**: Implement comprehensive monitoring with tools like Nagios or Zabbix

---

**Important**: Always test deployment procedures in a staging environment before applying to production.