# Production Troubleshooting Reference

Diagnose and fix common production issues using CLI commands only.

---

## Diagnostic Checklist

Run this first to get system status:

```bash
#!/bin/bash
# quick-diagnosis.sh

echo "=== SYSTEM STATUS ==="
echo "Current Time: $(date)"
echo "Uptime: $(uptime)"
echo "Disk: $(df -h /var/www | tail -1)"
echo "Memory: $(free -h | tail -1)"
echo ""

echo "=== WEB SERVER ==="
if command -v nginx &> /dev/null; then
    echo "Nginx Status: $(systemctl is-active nginx 2>/dev/null || echo 'N/A')"
    echo "Nginx Processes: $(ps aux | grep nginx | grep -v grep | wc -l)"
fi

if command -v apache2 &> /dev/null; then
    echo "Apache Status: $(systemctl is-active apache2 2>/dev/null || echo 'N/A')"
fi

echo ""

echo "=== PHP ==="
echo "PHP Version: $(php -v | head -1)"
php -r "echo 'PHP CLI Memory: ' . ini_get('memory_limit') . '\n';"
echo "PHP-FPM Processes: $(ps aux | grep php-fpm | grep -v grep | wc -l)"

echo ""

echo "=== LARAVEL ==="
cd /var/www/printingservices
echo "APP_ENV: $(grep '^APP_ENV=' .env | cut -d= -f2)"
echo "APP_DEBUG: $(grep '^APP_DEBUG=' .env | cut -d= -f2)"

echo ""

echo "=== DATABASE ==="
DB_HOST=$(grep '^DB_HOST=' .env | cut -d= -f2)
echo "DB_HOST: $DB_HOST"
if mysql -h $DB_HOST -u printing_opc_user -p$(grep '^DB_PASSWORD=' .env | cut -d= -f2) -e "SELECT 1;" 2>/dev/null; then
    echo "Database: ✅ Connected"
else
    echo "Database: ❌ Connection Failed"
fi

echo ""

echo "=== DISK USAGE (by directory) ==="
du -sh /var/www/printingservices/* 2>/dev/null | sort -h

echo ""

echo "=== RECENT ERRORS (last 10) ==="
tail -10 /var/www/printingservices/storage/logs/laravel.log | grep -i error || echo "No recent errors"
```

---

## 1. Application Returns 500 Error

### Step 1: Check Laravel Logs

```bash
cd /var/www/printingservices

# Last 50 lines
tail -50 storage/logs/laravel.log

# Search for specific error type
grep "ERROR\|CRITICAL" storage/logs/laravel.log | tail -10

# Follow log in real-time
tail -f storage/logs/laravel.log
```

### Step 2: Check PHP Errors

```bash
# Check PHP syntax
php -l index.php

# Check if PHP can load files
php -r "require 'vendor/autoload.php'; echo 'PHP: OK\n';"
```

### Step 3: Verify APP_KEY

```bash
# APP_KEY MUST be set
grep "^APP_KEY=" .env

# If missing or invalid (doesn't start with base64:)
php artisan key:generate

# If already set, regenerate
php artisan key:generate --force
```

### Step 4: Clear Caches

```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear

# Then rebuild
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Check File Permissions

```bash
# Storage must be writable
ls -la storage/ | head -3

# If not 755+ for owner:
chmod 755 storage
chmod 755 storage/logs
chmod 755 storage/framework

# If still failing:
chmod 775 storage/logs
chmod 775 bootstrap/cache

# Test write access
touch storage/test.txt && rm storage/test.txt && echo "✅ Writable"
```

### Step 6: Check Database Connection

```bash
# Extract credentials
DB_HOST=$(grep '^DB_HOST=' .env | cut -d= -f2)
DB_USER=$(grep '^DB_USERNAME=' .env | cut -d= -f2)
DB_PASS=$(grep '^DB_PASSWORD=' .env | cut -d= -f2)
DB_NAME=$(grep '^DB_DATABASE=' .env | cut -d= -f2)

# Test connection
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SELECT 1;" $DB_NAME

# If that fails:
# 1. Check credentials are correct
grep "^DB_" .env

# 2. Test if MySQL is accessible
telnet $DB_HOST 3306

# 3. Check if database exists
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SHOW DATABASES;" | grep $DB_NAME
```

### Step 7: Check Environment

```bash
# Is APP_DEBUG false? (must be in production)
grep "^APP_DEBUG=" .env

# Is APP_ENV=production?
grep "^APP_ENV=" .env

# Are all critical env vars set?
grep -E "^(APP_KEY|DB_HOST|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=" .env
```

---

## 2. Assets Not Loading (404 on CSS/JS)

### Step 1: Verify Manifest File

```bash
# Must exist after npm run build
ls -la public/build/manifest.json

# Check content
cat public/build/manifest.json | head -20

# If empty or missing:
npm run build
```

### Step 2: Check Vite Base Path

```bash
# VITE_BASE_PATH must match deployment
grep "^VITE_BASE_PATH=" .env

# For domain root: /
# For subdirectory: /printingservices/

# Rebuild if changed
npm run build
```

### Step 3: Verify Web Server Configuration

**Nginx:**
```bash
# Check assets are served from public/build
curl -I https://printingservices.opc.gov.mw/build/assets/app.XXXXX.js

# If 404, check nginx config includes static file handling
grep -A 5 "\.js\|\.css" /etc/nginx/sites-available/printingservices
```

**Apache:**
```bash
# Check .htaccess isn't blocking build directory
cat /var/www/printingservices/public/.htaccess | grep -i deny

# Check RewriteRule allows /build
grep "RewriteCond\|RewriteRule" /var/www/printingservices/public/.htaccess
```

### Step 4: Clear View Cache

```bash
php artisan view:clear
php artisan view:cache
```

### Step 5: Check File Permissions

```bash
# Build files must be readable
ls -la public/build/ | head -5

# If not world-readable:
chmod -R 755 public/build
```

---

## 3. Database Connection Failed

### Step 1: Test MySQL Connectivity

```bash
# From .env file
DB_HOST=$(grep '^DB_HOST=' .env | cut -d= -f2)
DB_PORT=$(grep '^DB_PORT=' .env | cut -d= -f2 || echo "3306")

# Test basic network connectivity
ping $DB_HOST

# Test port is open
telnet $DB_HOST $DB_PORT
# If connects, you'll see: Connected to $DB_HOST
# Type: quit

# Test with credentials
DB_USER=$(grep '^DB_USERNAME=' .env | cut -d= -f2)
DB_PASS=$(grep '^DB_PASSWORD=' .env | cut -d= -f2)
DB_NAME=$(grep '^DB_DATABASE=' .env | cut -d= -f2)

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME -e "SELECT 1;"
```

### Step 2: Check Credentials in .env

```bash
# Verify all DB_ variables are set
grep "^DB_" .env

# Common mistakes:
# - Typo in host/username/password
# - Password with special characters (must be quoted or escaped)
# - Wrong database name
```

### Step 3: Verify Database Exists

```bash
# Connect to MySQL
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SHOW DATABASES;" | grep $DB_NAME

# If not found, create it
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 4: Check User Permissions

```bash
# User must have all privileges on database
mysql -h $DB_HOST -u root -p -e "SHOW GRANTS FOR '$DB_USER'@'%';" 

# If not enough permissions:
mysql -h $DB_HOST -u root -p -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%'; FLUSH PRIVILEGES;"
```

### Step 5: Test in Laravel

```bash
php artisan tinker

# Try to connect
>>> use Illuminate\Support\Facades\DB;
>>> DB::connection()->getPdo()

# If error, check:
>>> config('database.connections.mysql')

# Try raw query
>>> DB::statement('SELECT 1')

>>> exit()
```

---

## 4. Admin Panel Not Accessible (/admin)

### Step 1: Verify Filament is Installed

```bash
# Check if Filament exists
ls -la vendor/filament/

# If not, install it
composer require filament/filament
```

### Step 2: Check Admin Routes

```bash
# List admin-related routes
php artisan route:list | grep admin

# Should show routes like:
# /admin
# /admin/login
# /admin/logout
```

### Step 3: Verify Admin User Exists and is Staff

```bash
php artisan tinker

# Check admin users exist
>>> use App\Models\User;
>>> User::where('is_staff', true)->get(['id', 'name', 'email', 'is_staff'])

# If no staff users, create one
>>> $admin = User::create([
>>>     'name' => 'Admin',
>>>     'email' => 'admin@example.com',
>>>     'password' => bcrypt('password123'),
>>>     'is_staff' => true,
>>>     'email_verified_at' => now()
>>> ]);

>>> // Assign role
>>> use Spatie\Permission\Models\Role;
>>> $admin->assignRole('super_admin');

>>> exit()
```

### Step 4: Check Admin Can Access Panel

```bash
# In canAccessPanel() middleware
php artisan tinker

>>> use App\Models\User;
>>> $user = User::find(1);  // Your admin user ID
>>> $user->canAccessPanel()  // Should return true

>>> exit()
```

### Step 5: Clear Filament Cache

```bash
# Filament has its own cache
php artisan filament:cache-components

# Clear all caches
php artisan cache:clear
```

### Step 6: Test Admin Panel Loads

```bash
# Should return 200 or 302 (redirect to login)
curl -I https://printingservices.opc.gov.mw/admin/

# Get the HTML to check for errors
curl -s https://printingservices.opc.gov.mw/admin/login | head -100
```

---

## 5. Slow Performance / High Memory Usage

### Step 1: Check System Resources

```bash
# Memory usage
free -h

# CPU load
uptime

# Disk I/O
iostat -x 1 5  # 1 second interval, 5 times

# PHP-FPM memory per process
ps aux | grep php-fpm | grep -v grep | awk '{print $6}' | paste -sd+ | bc
```

### Step 2: Check Database Performance

```bash
# List current processes
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SHOW PROCESSLIST;" | grep -v Sleep

# Check slow query log
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SELECT query_time, sql_text FROM mysql.slow_log ORDER BY query_time DESC LIMIT 10;"

# Enable slow query log (ask DBA if not your database)
mysql -h $DB_HOST -u root -p -e "SET GLOBAL slow_query_log = 'ON'; SET GLOBAL long_query_time = 2;"
```

### Step 3: Check Application Logs for Errors

```bash
# Look for slow logs or exceptions
grep -i "slow\|exception\|error" storage/logs/laravel.log | tail -20
```

### Step 4: Profile a Request

```bash
php artisan tinker

# Enable query logging
>>> DB::enableQueryLog();

# Manually trigger operations or use:
>>> // Load a page by simulating a request
>>> Route::dispatch(Request::create('/'))

# Check queries
>>> $queries = DB::getQueryLog();
>>> foreach($queries as $q) { echo $q['query'] . " (" . $q['time'] . "ms)\n"; }

>>> exit()
```

### Step 5: Check Cache Effectiveness

```bash
php artisan tinker

# Check cache hit rate
>>> Cache::put('test_key', 'value', 60);
>>> Cache::get('test_key')  // Should return 'value'

# Monitor cache statistics
>>> Cache::getStore()->connection()->info()

>>> exit()
```

---

## 6. Queue Jobs Not Processing

### Step 1: Check Queue Configuration

```bash
# Is queue enabled?
grep "^QUEUE_CONNECTION=" .env

# Should be: database, redis, or similar (not sync in production)
```

### Step 2: Check Pending Jobs

```bash
# List pending jobs
php artisan queue:failed

# Count them
php artisan queue:failed | wc -l
```

### Step 3: View Failed Jobs Details

```bash
php artisan tinker

# Get failed jobs
>>> use Illuminate\Queue\Failed\DatabaseFailedJobProvider;
>>> use DB;
>>> DB::table('failed_jobs')->get()

>>> exit()
```

### Step 4: Retry Failed Jobs

```bash
# Retry all failed jobs
php artisan queue:retry all

# Or retry specific ID
php artisan queue:retry 1

# Purge failed jobs (careful!)
php artisan queue:flush
```

### Step 5: Start Queue Listener

```bash
# Test in foreground first
php artisan queue:listen --tries=1 --timeout=0

# Should show:
# [queue:listen] Starting...

# In production, use supervisor (ask sysadmin) or run in background:
nohup php artisan queue:listen --tries=3 --timeout=60 >> storage/logs/queue.log 2>&1 &

# Monitor it
tail -f storage/logs/queue.log
```

### Step 6: Test Queue Job

```bash
php artisan tinker

# Dispatch test job
>>> dispatch(new \App\Jobs\SyncProductsFromErp());

# Check if it appears in queue
>>> DB::table('jobs')->count()

# Check if it was processed
>>> DB::table('jobs')->count()  // Should be 0 after processing

>>> exit()
```

---

## 7. Mail Not Sending

### Step 1: Check Mail Configuration

```bash
# Is mail configured?
grep "^MAIL_" .env

# Should have:
# MAIL_MAILER=smtp
# MAIL_HOST=...
# MAIL_PORT=...
# MAIL_USERNAME=...
# MAIL_PASSWORD=...
```

### Step 2: Test SMTP Connection

```bash
MAIL_HOST=$(grep '^MAIL_HOST=' .env | cut -d= -f2)
MAIL_PORT=$(grep '^MAIL_PORT=' .env | cut -d= -f2)

# Test if port is open
telnet $MAIL_HOST $MAIL_PORT

# If successful, type: quit
```

### Step 3: Test Mail in Laravel

```bash
php artisan tinker

# Send test email
>>> use Illuminate\Support\Facades\Mail;
>>> Mail::raw('Test message', function ($m) { $m->to('test@example.com'); });

# If it returns true, mail was accepted
# Check test@example.com inbox

# If it fails, check:
>>> config('mail.host')
>>> config('mail.port')
>>> config('mail.from')

>>> exit()
```

### Step 4: Check Mail Logs

```bash
# If MAIL_MAILER=log, check logs instead
grep -i "mail\|message" storage/logs/laravel.log | tail -20
```

### Step 5: Test Credentials

```bash
# Try to connect manually with credentials
MAIL_HOST=$(grep '^MAIL_HOST=' .env | cut -d= -f2)
MAIL_USER=$(grep '^MAIL_USERNAME=' .env | cut -d= -f2)
MAIL_PASS=$(grep '^MAIL_PASSWORD=' .env | cut -d= -f2)

# Use telnet to test SMTP
(sleep 1; echo "QUIT") | telnet $MAIL_HOST $(grep '^MAIL_PORT=' .env | cut -d= -f2)
```

---

## 8. Migrations Failing or Stuck

### Step 1: Check Migration Status

```bash
# See which migrations are pending or failed
php artisan migrate:status

# If shows errors, check:
php artisan migrate:status | grep -i fail
```

### Step 2: View Migration Details

```bash
# List all migration files
ls database/migrations/ | sort

# Check if migration table exists
php artisan tinker
>>> DB::table('migrations')->get(['id', 'migration', 'batch'])
>>> exit()
```

### Step 3: Reset & Retry (Careful in Production!)

```bash
# DO NOT DO THIS IN PRODUCTION unless you have backup!

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations (DANGEROUS!)
php artisan migrate:reset

# Then re-run
php artisan migrate --force
```

### Step 4: Run Specific Migration

```bash
# Run single migration
php artisan migrate --path=database/migrations/2024_01_01_000000_create_users_table.php

# Check if it worked
php artisan migrate:status | grep users
```

### Step 5: Check Migration File Syntax

```bash
# Check if migration PHP is valid
php -l database/migrations/2024_01_01_000000_create_users_table.php

# View the file to check for errors
cat database/migrations/2024_01_01_000000_create_users_table.php | head -50
```

---

## 9. Storage Directory Issues

### Step 1: Check Directory Structure

```bash
# Should have these directories
ls -la storage/

# Expected:
# app/
# framework/
# logs/

# If missing, create them:
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
```

### Step 2: Check Permissions

```bash
# Directories must be writable by web server
ls -la storage/logs/

# Should show rwx for owner
# Change permissions if needed:
chmod 775 storage/logs
chmod 775 storage/framework/cache
chmod 775 storage/framework/sessions

# Test write access:
touch storage/logs/test.log && rm storage/logs/test.log && echo "✅ Writable"
```

### Step 3: Check Disk Space

```bash
# Is disk full?
df -h /var/www

# If over 90%, clean up:
# Find large files
find storage/logs -type f -size +100M

# Delete old logs
find storage/logs -name "laravel-*.log" -mtime +30 -delete

# Or rotate logs:
php artisan make:command LogRotate
```

### Step 4: Storage Symlink

```bash
# Create public storage symlink if needed
php artisan storage:link

# Verify it exists
ls -la public/storage
```

---

## 10. SSL Certificate Issues

### Step 1: Check Certificate Status

```bash
# Check expiration date
echo | openssl s_client -servername printingservices.opc.gov.mw \
  -connect printingservices.opc.gov.mw:443 2>/dev/null | \
  openssl x509 -noout -dates

# Should show:
# notBefore=...
# notAfter=... (should be in future)
```

### Step 2: Check Certificate Files

```bash
# Verify certificate files exist
ls -la /etc/letsencrypt/live/printingservices.opc.gov.mw/

# Should have:
# fullchain.pem
# privkey.pem
```

### Step 3: Renew Certificate (Let's Encrypt)

```bash
# Test renewal (dry-run)
sudo certbot renew --dry-run

# Actually renew
sudo certbot renew

# Verify it worked
echo | openssl s_client -servername printingservices.opc.gov.mw \
  -connect printingservices.opc.gov.mw:443 2>/dev/null | \
  openssl x509 -noout -dates
```

### Step 4: Check Web Server Configuration

**Nginx:**
```bash
# Verify SSL certificate path
grep -i "ssl_certificate" /etc/nginx/sites-available/printingservices

# Should point to valid files:
# /etc/letsencrypt/live/printingservices.opc.gov.mw/fullchain.pem
# /etc/letsencrypt/live/printingservices.opc.gov.mw/privkey.pem

# Test nginx config
sudo nginx -t

# Restart if needed
sudo systemctl reload nginx
```

**Apache:**
```bash
# Verify SSL certificate path
grep -i "SSLCertificate" /etc/apache2/sites-available/printingservices.conf

# Test apache config
sudo apache2ctl configtest

# Restart if needed
sudo systemctl reload apache2
```

---

## Emergency Commands

### If Everything is Broken

```bash
# 1. Check what's wrong
tail -100 storage/logs/laravel.log | grep -i error

# 2. Rebuild immediately
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# 3. If still broken, check file permissions
chmod 755 storage
chmod 755 bootstrap/cache

# 4. If still broken, check database
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME -e "SELECT 1;"

# 5. Last resort - restore from backup
# See PRODUCTION_DEPLOYMENT_GUIDE.md for backup restoration
```

### Monitor Everything

```bash
#!/bin/bash
# watch-all.sh

while true; do
    clear
    echo "=== $(date) ==="
    echo ""
    echo "HTTP Status: $(curl -s -o /dev/null -w '%{http_code}' https://printingservices.opc.gov.mw/)"
    echo ""
    echo "Memory: $(free -h | tail -1)"
    echo ""
    echo "PHP Processes: $(ps aux | grep php-fpm | grep -v grep | wc -l)"
    echo ""
    echo "Recent Errors: $(tail -1 storage/logs/laravel.log | grep -i error || echo 'None')"
    echo ""
    sleep 5
done
```

---

**Version:** 1.0  
**Last Updated:** June 2026
