# QUICK REFERENCE - Production Deployment & Operations

## Fast Deployment (All CLI)

### Fresh Deployment on New Server

```bash
# 1. SSH into server as appuser
ssh appuser@production-server

# 2. Clone or receive application
cd /var/www
git clone https://your-repo.git printingservices
cd printingservices

# 3. Run automated deployment script
chmod +x deploy.sh
./deploy.sh --non-interactive --env

# 4. Verify it's live
curl -I https://printingservices.opc.gov.mw/
curl -I https://printingservices.opc.gov.mw/admin
```

### Quick Deployment (Already Installed)

```bash
cd /var/www/printingservices

# Update code
git pull origin main

# Install new dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Clear caches & optimize
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Done
curl -I https://printingservices.opc.gov.mw/
```

### Emergency: Clear Cache Only

```bash
cd /var/www/printingservices

php artisan cache:clear
php artisan route:clear
php artisan view:clear

# If still broken, rebuild
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check logs
tail -50 storage/logs/laravel.log
```

---

## Common Operations

### View Application Logs

```bash
# Live tail (last 50 lines, then new)
tail -f storage/logs/laravel.log

# Last 100 lines
tail -100 storage/logs/laravel.log

# Search for errors
grep "ERROR\|CRITICAL\|ALERT" storage/logs/laravel.log

# Count errors by day
grep "2026-06" storage/logs/laravel.log | grep ERROR | wc -l
```

### Database Operations

```bash
# Connect to database
mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025

# Inside MySQL:
SHOW TABLES;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM products;
SELECT COUNT(*) FROM orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY);
EXIT;

# Backup database
mysqldump -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025 | gzip > backup_$(date +%Y%m%d).sql.gz

# Restore database (CAREFUL!)
gunzip < backup_20260604.sql.gz | mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025
```

### Check User/Permissions

```bash
# In Laravel tinker
php artisan tinker

# View all users
>>> User::get(['id', 'name', 'email', 'is_staff'])->toArray()

# Check user roles
>>> User::find(1)->getRoleNames()

# Add role to user
>>> $user = User::find(1);
>>> $user->assignRole('super_admin');

# View all roles
>>> use Spatie\Permission\Models\Role;
>>> Role::get(['id', 'name'])->toArray()

# Exit
>>> exit()
```

### Verify Admin Access

```bash
# Test admin login page loads
curl -s https://printingservices.opc.gov.mw/admin/login | grep -i "filament\|sign in" | head -3

# Create test admin (if needed)
php artisan tinker
>>> use App\Models\User; use Spatie\Permission\Models\Role;
>>> $admin = User::create(['name' => 'Test Admin', 'email' => 'test@admin.local', 'password' => bcrypt('test123'), 'is_staff' => true, 'email_verified_at' => now()]);
>>> $admin->assignRole('super_admin');
>>> echo "Created admin: test@admin.local / test123\n";
>>> exit()
```

### Monitor Server Health

```bash
# Check disk space
df -h

# Check memory usage
free -h

# Check CPU load
uptime

# Check running PHP processes
ps aux | grep php-fpm | grep -v grep | wc -l

# Check Nginx/Apache processes
ps aux | grep -E 'nginx|apache' | grep -v grep | wc -l

# Check network connections
ss -tnap | grep LISTEN | grep -E ':(80|443|3306)'

# Check Laravel queue (if using)
php artisan queue:failed
```

### Restart Services (No Sudo)

```bash
# Services that DON'T require sudo (systemctl uses sudo automatically)
# Note: Contact your sysadmin if you need to restart these

# Check if PHP-FPM can be restarted
php -v  # Just verify PHP is working

# Graceful restart of app
cd /var/www/printingservices
php artisan down --message "Maintenance in progress"
php artisan up  # Bring back online

# For web server restart, ask sysadmin:
# sudo systemctl restart nginx
# sudo systemctl restart apache2
```

### Test Email Sending

```bash
php artisan tinker

# Test sending email
>>> use Illuminate\Support\Facades\Mail;
>>> Mail::raw('Test message', function ($m) { $m->to('your-email@example.com'); });

# Or using Laravel's built-in command
>>> exit()

# Command line
php artisan mail:send --help
```

### Run Database Query Directly

```bash
# One-time query
php artisan tinker
>>> DB::table('users')->where('is_staff', true)->get(['name', 'email'])

# Check order count
>>> DB::table('orders')->count()

# Check recent quotations
>>> DB::table('quotations')->where('created_at', '>', now()->subDays(7))->count()

# Check for errors
>>> DB::table('job_status_logs')->where('status', 'failed')->latest()->limit(5)->get()

>>> exit()
```

---

## Deployment Checklist (Print This)

**Date:** ________  **Deployed By:** ________

### Pre-Deployment
- [ ] Backup current database
- [ ] Verify database credentials
- [ ] Test SSL certificate
- [ ] Notify team of maintenance window

### Deployment
- [ ] Pull latest code: `git pull origin main`
- [ ] Install dependencies: `composer install --no-dev`
- [ ] Build assets: `npm run build`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear caches: `php artisan cache:clear && php artisan config:cache`

### Verification
- [ ] Homepage loads: `curl -I https://printingservices.opc.gov.mw/`
- [ ] Admin panel loads: `curl -I https://printingservices.opc.gov.mw/admin`
- [ ] No errors in logs: `tail -20 storage/logs/laravel.log`
- [ ] Database responsive: `php artisan tinker` → `DB::connection()->getPdo()`
- [ ] Static assets load properly
- [ ] Forms submit successfully

### Post-Deployment
- [ ] Monitor logs for 30 minutes
- [ ] Test key user flows
- [ ] Verify backup automated jobs running
- [ ] Update deployment log
- [ ] Notify team deployment complete

**Notes:** ___________________________________________________________

---

## Emergency Procedures

### If Application is Down

1. **Check logs first:**
   ```bash
   tail -100 storage/logs/laravel.log | grep -E 'ERROR|CRITICAL'
   ```

2. **Common issues:**
   ```bash
   # Missing .env file
   ls -la .env
   
   # No APP_KEY
   grep "APP_KEY=" .env
   
   # Storage not writable
   touch storage/test.txt && rm storage/test.txt
   
   # Database down
   mysql -h dbweb.boma.gov.mw -u printing_opc_user -p -e "SELECT 1;" pressrvc_db2025
   ```

3. **Quick recovery:**
   ```bash
   cd /var/www/printingservices
   php artisan cache:clear
   php artisan config:cache
   curl -I https://printingservices.opc.gov.mw/
   ```

4. **Last resort - rollback:**
   ```bash
   # Get previous backup
   ls -lh /backups/printingservices/
   
   # Restore
   tar -xzf /backups/printingservices/app_PREVIOUS_TIMESTAMP.tar.gz -C /var/www
   
   # Test
   curl -I https://printingservices.opc.gov.mw/
   ```

### If Database is Down

1. **Test connection:**
   ```bash
   mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025 -e "SELECT 1;"
   ```

2. **Check MySQL status (ask sysadmin):**
   ```bash
   # This will tell you if MySQL is running
   telnet dbweb.boma.gov.mw 3306
   ```

3. **If local SQLite database:**
   ```bash
   # Check if database file exists
   ls -la database/database.sqlite
   
   # If corrupted, restore from backup
   cp /backups/printingservices/database_TIMESTAMP.sqlite database/database.sqlite
   ```

### If Assets Won't Load

```bash
# Rebuild assets
npm run build

# Clear cache
php artisan view:clear

# Verify manifest
cat public/build/manifest.json | head -5

# Check VITE_BASE_PATH in .env
grep "VITE_BASE_PATH=" .env
```

---

## Performance Quick Checks

### Is the Site Fast?

```bash
# Measure homepage load time
time curl -s https://printingservices.opc.gov.mw/ > /dev/null

# Check memory usage
free -h

# Check disk space
df -h /var/www

# Check PHP processes
ps aux | grep php-fpm | wc -l

# Monitor in real-time
watch -n 1 'free -h && echo "---" && df -h /var/www'
```

### Database Performance

```bash
php artisan tinker

# Enable query logging
>>> DB::enableQueryLog();

# Run a sample operation (load a product page, etc.)
# Then check queries:
>>> foreach(DB::getQueryLog() as $query) { echo $query['query'] . " (" . $query['time'] . "ms)\n"; }

>>> exit()
```

### Slow Query Log

```bash
# Check for slow queries (MySQL)
mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025 -e \
  "SELECT * FROM mysql.slow_log ORDER BY query_time DESC LIMIT 10;"

# Check current process list
mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025 -e "SHOW PROCESSLIST;"
```

---

## Deployment Logs Location

```bash
# Latest deployment log
ls -lt /tmp/deployment_*.log | head -1 | awk '{print $NF}'

# View specific log
tail -100 /tmp/deployment_20260604_120000.log

# Search all deployments
grep -l "Migration completed" /tmp/deployment_*.log
```

---

## Contacts & Escalation

| Role | Contact | Notes |
|------|---------|-------|
| **Database Admin** | dba@example.com | MySQL maintenance, backups |
| **Infrastructure** | infra@example.com | Server, network, firewall |
| **Application Owner** | app-owner@example.com | Product decisions |
| **On-Call** | oncall@example.com | After hours emergency |

---

## Essential Commands Reference

| Task | Command |
|------|---------|
| Deploy everything | `./deploy.sh` |
| Update code only | `git pull origin main` |
| Clear all caches | `php artisan cache:clear && php artisan config:cache` |
| Database backup | `mysqldump -h [host] -u [user] -p [db] \| gzip > backup_$(date +%Y%m%d).sql.gz` |
| Check logs | `tail -f storage/logs/laravel.log` |
| Monitor site | `watch -n 5 'curl -s https://printingservices.opc.gov.mw/ \| head -10'` |
| Admin access | `php artisan tinker` |
| Server status | `uptime && free -h && df -h` |
| Restart PHP | Ask sysadmin: `sudo systemctl restart php8.2-fpm` |

---

**Version:** 1.0  
**Last Updated:** June 2026  
**Maintained By:** DevOps Team
