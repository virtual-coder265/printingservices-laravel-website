# Production Environment Configuration Guide

This guide explains each environment variable required for production deployment.

## How to Use This Guide

1. Copy `.env.example` or `.env-prod` to `.env`
2. Update each variable according to your infrastructure
3. Never commit `.env` to version control
4. Verify with: `php artisan config:cache && php artisan tinker`

---

## Application Configuration

### `APP_NAME`
**Purpose:** Application display name  
**Production Value:** `"Printing Services"`  
**Example:**
```bash
APP_NAME="Printing Services"
```

### `APP_ENV`
**Purpose:** Environment identifier  
**Production Value:** `production` (not `local` or `dev`)  
**Example:**
```bash
APP_ENV=production
```

### `APP_DEBUG`
**Purpose:** Enable/disable debug mode  
**Production Value:** `false` (CRITICAL: never `true` in production)  
**Security:** Exposing debug info shows sensitive code/data  
**Example:**
```bash
APP_DEBUG=false
```

### `APP_KEY`
**Purpose:** Application encryption key for sessions/cookies  
**How to Generate:**
```bash
php artisan key:generate
```
**Format:** Must start with `base64:` prefix  
**Example:**
```bash
APP_KEY=base64:I3SStH3Q9IvJ4XURFsalyJvOy46mN+TYoo1E3c6RzRY=
```
**Important:** Keep this secret, regenerate if compromised

### `APP_URL`
**Purpose:** Full base URL where application lives  
**Production Example:**
```bash
APP_URL=https://printingservices.opc.gov.mw
```
**Important:** Must use HTTPS, no trailing slash

---

## Asset & Vite Configuration

### `VITE_BASE_PATH`
**Purpose:** Base path for built assets  
**For Domain Root:**
```bash
VITE_BASE_PATH=/
```
**For Subdirectory:**
```bash
VITE_BASE_PATH=/printingservices/
```
**Note:** Ensure `public/build/manifest.json` exists after build

### `ASSET_URL` (Optional)
**Purpose:** CDN URL for assets (if using CDN)  
**If Using CDN:**
```bash
ASSET_URL=https://cdn.example.com/assets
```
**If Not Using CDN:** Leave empty to use `APP_URL`

---

## Localization Configuration

### `APP_LOCALE`
**Purpose:** Default application language  
**Production Value:** `en`  
**Example:**
```bash
APP_LOCALE=en
```

### `APP_FALLBACK_LOCALE`
**Purpose:** Fallback language if translation missing  
**Production Value:** `en`  
**Example:**
```bash
APP_FALLBACK_LOCALE=en
```

### `APP_FAKER_LOCALE`
**Purpose:** Language for Faker library (dev/testing)  
**Production Value:** `en_US`  
**Example:**
```bash
APP_FAKER_LOCALE=en_US
```

---

## Database Configuration

### `DB_CONNECTION`
**Purpose:** Which database driver to use  
**Options:** `mysql`, `pgsql`, `sqlite`  
**Production Value:** `mysql` (for this application)  
**Example:**
```bash
DB_CONNECTION=mysql
```

### `DB_HOST`
**Purpose:** Database server hostname/IP  
**Example:**
```bash
DB_HOST=dbweb.boma.gov.mw
```
**Verify Connection:**
```bash
mysql -h dbweb.boma.gov.mw -u printing_opc_user -p pressrvc_db2025 -e "SELECT 1;"
```

### `DB_PORT`
**Purpose:** Database server port  
**Default MySQL Port:** `3306`  
**Example:**
```bash
DB_PORT=3306
```

### `DB_DATABASE`
**Purpose:** Database name to use  
**Example:**
```bash
DB_DATABASE=pressrvc_db2025
```
**Create if Missing:**
```bash
mysql -h dbweb.boma.gov.mw -u root -p -e "CREATE DATABASE pressrvc_db2025 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### `DB_USERNAME`
**Purpose:** Database user username  
**Example:**
```bash
DB_USERNAME=printing_opc_user
```
**Create if Missing:**
```bash
mysql -h dbweb.boma.gov.mw -u root -p -e "CREATE USER 'printing_opc_user'@'%' IDENTIFIED BY 'your-password';"
```

### `DB_PASSWORD`
**Purpose:** Database user password  
**Security:** Use strong password (32+ chars with special characters)  
**Example:**
```bash
DB_PASSWORD=xLGmHbW9EsVlH3d
```
**Grant Permissions:**
```bash
mysql -h dbweb.boma.gov.mw -u root -p -e "GRANT ALL PRIVILEGES ON pressrvc_db2025.* TO 'printing_opc_user'@'%'; FLUSH PRIVILEGES;"
```

---

## Session & Security Configuration

### `SESSION_DRIVER`
**Purpose:** Where to store user sessions  
**Options:** `database`, `file`, `redis`, `cookie`  
**Production Value:** `database` (recommended)  
**Example:**
```bash
SESSION_DRIVER=database
```

### `SESSION_LIFETIME`
**Purpose:** Minutes before session expires  
**Production Value:** `1440` (24 hours)  
**Example:**
```bash
SESSION_LIFETIME=1440
```

### `SESSION_ENCRYPT`
**Purpose:** Encrypt session data  
**Production Value:** `false` (DB handles encryption)  
**Example:**
```bash
SESSION_ENCRYPT=false
```

### `SESSION_PATH`
**Purpose:** Session cookie path  
**For Root Domain:**
```bash
SESSION_PATH=/
```
**Leave Empty for Auto-Detect:**
```bash
SESSION_PATH=
```

### `SESSION_DOMAIN`
**Purpose:** Cookie domain (for subdomain sharing)  
**Production Value:**
```bash
SESSION_DOMAIN=.opc.gov.mw
```
**Or Specific Domain:**
```bash
SESSION_DOMAIN=printingservices.opc.gov.mw
```

---

## Cache Configuration

### `CACHE_STORE`
**Purpose:** Cache backend  
**Options:** `database`, `file`, `redis`, `memcached`  
**Production Value:** `database` (simple & reliable)  
**Example:**
```bash
CACHE_STORE=database
```
**Clear Cache Command:**
```bash
php artisan cache:clear
```

### `CACHE_PREFIX` (Optional)
**Purpose:** Prefix for all cache keys  
**Example:**
```bash
CACHE_PREFIX=printserv_
```

---

## Queue Configuration

### `QUEUE_CONNECTION`
**Purpose:** Where background jobs are queued  
**Options:** `database`, `redis`, `beanstalk`, `sqs`  
**Production Value:** `database`  
**Example:**
```bash
QUEUE_CONNECTION=database
```
**Start Queue Listener:**
```bash
php artisan queue:listen --tries=3 --timeout=90
```

---

## Mail Configuration

### `MAIL_MAILER`
**Purpose:** Which mail driver to use  
**Options:** `smtp`, `sendmail`, `mailgun`, `postmark`  
**Production Value:** `smtp` (most reliable)  
**Example:**
```bash
MAIL_MAILER=smtp
```

### `MAIL_HOST`
**Purpose:** SMTP server hostname  
**Example:**
```bash
MAIL_HOST=smtp.gmail.com
```
**Test Connection:**
```bash
telnet smtp.gmail.com 587
```

### `MAIL_PORT`
**Purpose:** SMTP server port  
**Standard Ports:**
- `25` - Unencrypted (legacy)
- `587` - TLS (recommended)
- `465` - SSL
**Example:**
```bash
MAIL_PORT=587
```

### `MAIL_USERNAME`
**Purpose:** SMTP authentication username  
**Example:**
```bash
MAIL_USERNAME=noreply@printingservices.gov.mw
```

### `MAIL_PASSWORD`
**Purpose:** SMTP authentication password  
**Security:** Use application-specific password (if supported by provider)  
**Example:**
```bash
MAIL_PASSWORD=your-smtp-password
```

### `MAIL_ENCRYPTION`
**Purpose:** TLS or SSL encryption  
**Options:** `tls`, `ssl`, empty (none)  
**Production Value:** `tls`  
**Example:**
```bash
MAIL_ENCRYPTION=tls
```

### `MAIL_FROM_ADDRESS`
**Purpose:** Default sender email address  
**Example:**
```bash
MAIL_FROM_ADDRESS=noreply@printingservices.gov.mw
```

### `MAIL_FROM_NAME`
**Purpose:** Display name for sender  
**Example:**
```bash
MAIL_FROM_NAME="Printing Services"
```

**Test Email Sending:**
```bash
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('your-email@example.com'); });
>>> exit()
```

---

## Logging Configuration

### `LOG_CHANNEL`
**Purpose:** Which logger to use  
**Options:** `stack`, `single`, `daily`  
**Production Value:** `stack`  
**Example:**
```bash
LOG_CHANNEL=stack
```

### `LOG_STACK`
**Purpose:** Which logs to include in stack  
**Example:**
```bash
LOG_STACK=single
```

### `LOG_LEVEL`
**Purpose:** Minimum log level to record  
**Options:** `debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`  
**Production Value:** `notice` (not `debug`)  
**Example:**
```bash
LOG_LEVEL=notice
```

**View Logs:**
```bash
tail -f storage/logs/laravel.log
grep ERROR storage/logs/laravel.log | tail -20
```

---

## File Storage Configuration

### `FILESYSTEM_DISK`
**Purpose:** Default storage disk  
**Options:** `local`, `s3`  
**Production Value:** `local` (uploads stored on server)  
**Example:**
```bash
FILESYSTEM_DISK=local
```

**For S3 (AWS):**
```bash
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

---

## Broadcasting Configuration

### `BROADCAST_CONNECTION`
**Purpose:** Real-time broadcast method  
**Options:** `log`, `redis`, `pusher`  
**Production Value:** `log` (unless using real-time features)  
**Example:**
```bash
BROADCAST_CONNECTION=log
```

---

## Redis Configuration (Optional)

### `REDIS_HOST`
**Purpose:** Redis server hostname  
**Default:** `127.0.0.1`  
**Example:**
```bash
REDIS_HOST=redis.example.com
```

### `REDIS_PASSWORD`
**Purpose:** Redis authentication password  
**Example:**
```bash
REDIS_PASSWORD=your-redis-password
```

### `REDIS_PORT`
**Purpose:** Redis server port  
**Default:** `6379`  
**Example:**
```bash
REDIS_PORT=6379
```

---

## ERP Integration Configuration

### `ERP_SYNC_ENABLED`
**Purpose:** Enable/disable ERP synchronization  
**Production Value:** `true` (if using ERP)  
**Example:**
```bash
ERP_SYNC_ENABLED=true
```

### `ERP_BASE_URL`
**Purpose:** ERP system API base URL  
**Example:**
```bash
ERP_BASE_URL=https://erp.internal.gov.mw
```

### `ERP_API_KEY`
**Purpose:** API key for ERP authentication  
**Security:** Store securely, rotate periodically  
**Example:**
```bash
ERP_API_KEY=sk_prod_abc123xyz789def456ghi789
```

### `ERP_TIMEOUT`
**Purpose:** Request timeout in seconds  
**Default:** `30`  
**Example:**
```bash
ERP_TIMEOUT=30
```

### `ERP_WEBHOOK_SECRET`
**Purpose:** Secret for webhook validation from ERP  
**Security:** Strong random string  
**Example:**
```bash
ERP_WEBHOOK_SECRET=whsk_abc123xyz789def456ghi789jkl012mno
```

### `ERP_ESTIMATION_URL_TEMPLATE`
**Purpose:** Template URL for generating quotation estimates  
**Example:**
```bash
ERP_ESTIMATION_URL_TEMPLATE=https://erp.internal.gov.mw/api/quotations/estimate?item_code={item_code}&quantity={quantity}
```

---

## Quotation Request Configuration

### `QUOTATION_REFERENCE_PREFIX`
**Purpose:** Prefix for quotation reference numbers  
**Default:** `WEB`  
**Format:** `[PREFIX]-YYYY-####`  
**Example:**
```bash
QUOTATION_REFERENCE_PREFIX=WEB
```

### `QUOTATION_RATE_LIMIT`
**Purpose:** Max quotation requests per `QUOTATION_RATE_LIMIT_DECAY`  
**Example:**
```bash
QUOTATION_RATE_LIMIT=5
```

### `QUOTATION_RATE_LIMIT_DECAY`
**Purpose:** Time window in seconds for rate limit  
**Example:**
```bash
QUOTATION_RATE_LIMIT_DECAY=60
```
**Note:** 5 requests per 60 seconds = 5 per minute

### `QUOTATION_MAX_FILES`
**Purpose:** Maximum files per quotation request  
**Example:**
```bash
QUOTATION_MAX_FILES=5
```

### `QUOTATION_MAX_FILE_SIZE_KB`
**Purpose:** Maximum file size in kilobytes  
**Example:**
```bash
QUOTATION_MAX_FILE_SIZE_KB=10240
```
**Note:** 10240 KB = 10 MB

### `QUOTATION_CAPTCHA_REQUIRED`
**Purpose:** Require CAPTCHA on quotation form  
**Options:** `true`, `false`  
**Example:**
```bash
QUOTATION_CAPTCHA_REQUIRED=false
```

### `QUOTATION_NOTIFICATION_EMAIL`
**Purpose:** Email for quotation request notifications  
**Example:**
```bash
QUOTATION_NOTIFICATION_EMAIL=sales@printingservices.gov.mw
```

---

## Maintenance Mode

### `APP_MAINTENANCE_DRIVER`
**Purpose:** Where to store maintenance mode flag  
**Options:** `file`, `database`  
**Example:**
```bash
APP_MAINTENANCE_DRIVER=file
```

**Enable Maintenance Mode:**
```bash
php artisan down --message "System maintenance in progress" --secret=YOUR_SECRET_KEY
```

**Disable Maintenance Mode:**
```bash
php artisan up
```

---

## Complete Production .env Example

```bash
# Application
APP_NAME="Printing Services"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_URL=https://printingservices.opc.gov.mw

# Assets
VITE_BASE_PATH=/

# Localization
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

# Database
DB_CONNECTION=mysql
DB_HOST=dbweb.boma.gov.mw
DB_PORT=3306
DB_DATABASE=pressrvc_db2025
DB_USERNAME=printing_opc_user
DB_PASSWORD=your-secure-password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=1440
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.opc.gov.mw

# Cache
CACHE_STORE=database
CACHE_PREFIX=

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@printingservices.gov.mw
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@printingservices.gov.mw
MAIL_FROM_NAME="Printing Services"

# Logging
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=notice

# File Storage
FILESYSTEM_DISK=local

# Broadcasting
BROADCAST_CONNECTION=log

# Maintenance
APP_MAINTENANCE_DRIVER=file

# ERP Integration
ERP_SYNC_ENABLED=true
ERP_BASE_URL=https://erp.internal.gov.mw
ERP_API_KEY=your-erp-api-key
ERP_TIMEOUT=30
ERP_WEBHOOK_SECRET=your-webhook-secret
ERP_ESTIMATION_URL_TEMPLATE=https://erp.internal.gov.mw/api/quotations/estimate?item_code={item_code}&quantity={quantity}

# Quotation Configuration
QUOTATION_REFERENCE_PREFIX=WEB
QUOTATION_RATE_LIMIT=5
QUOTATION_RATE_LIMIT_DECAY=60
QUOTATION_MAX_FILES=5
QUOTATION_MAX_FILE_SIZE_KB=10240
QUOTATION_CAPTCHA_REQUIRED=false
QUOTATION_NOTIFICATION_EMAIL=sales@printingservices.gov.mw
```

---

## Verification Commands

After setting up `.env`, verify each section:

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit()

# Test cache
php artisan cache:test

# Test mail configuration
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });
>>> exit()

# Cache all configurations
php artisan config:cache

# Verify APP_KEY is set
grep "^APP_KEY=" .env

# Check critical values
php artisan tinker
>>> echo "APP_ENV: " . config('app.env') . "\n";
>>> echo "DB_HOST: " . config('database.connections.mysql.host') . "\n";
>>> echo "MAIL_HOST: " . config('mail.host') . "\n";
>>> exit()
```

---

## Security Checklist

- [ ] `APP_DEBUG=false` (never `true` in production)
- [ ] `APP_KEY` is unique and strong
- [ ] Database password is 32+ characters
- [ ] MAIL credentials use application-specific password
- [ ] ERP_API_KEY and ERP_WEBHOOK_SECRET are strong
- [ ] `.env` file is in `.gitignore`
- [ ] `.env` file permissions are `600` (readable by owner only)
- [ ] No sensitive data in `APP_URL` or public variables
- [ ] All external service URLs use HTTPS

---

**Version:** 1.0  
**Last Updated:** June 2026
