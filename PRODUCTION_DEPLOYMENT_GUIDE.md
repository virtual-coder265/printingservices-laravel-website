# Production Deployment Guide

Terminal-only deployment guide for the `printingservices.gov.mw` Laravel application on a Linux server where you do not have `sudo` or root access.

## What This App Needs

From the codebase:

- Laravel `12.x` on PHP `8.2+`
- Composer `2.x`
- MySQL or MariaDB for production
- Writable `storage/` and `bootstrap/cache/`
- Database-backed `sessions`, `cache`, and `queue`
- A queue runner and a scheduler runner
- Public file storage via `storage/app/public`
- Private quotation attachments via `storage/app/private`
- Correct `APP_URL` and `VITE_BASE_PATH`, especially for subdirectory installs

App-specific deployment facts:

- Public site routes live at `/`
- Admin panel lives at `/admin`
- Health check route is `/up`
- ERP sync jobs are scheduled daily and only run when `ERP_SYNC_ENABLED=true`
- `APP_URL` may include a subdirectory, and the app adjusts session paths from it
- The project root contains an `index.php` and `.htaccess` that can forward requests to `public/` when you cannot point the web root directly at `public/`

## Important Warnings

Do not run `php artisan db:seed --force` in production unless you intentionally want the full demo/bootstrap data.

`DatabaseSeeder` calls `AdminUserSeeder`, which creates:

- `admin@printing.gov.mw` with password `password`
- `test@example.com` with password `password`

For production, seed only the specific seeders you need and create the real admin user manually with a strong password.

Also note that this app uses the database cache store. Commands such as `schedule:list` and `optimize:clear` can touch the database, so run them only after the production database credentials are correct and migrations have been applied.

## Assumptions

This guide assumes:

- The server already has PHP, Composer, a web server, and MySQL/MariaDB installed by someone with root access
- You can SSH into the server as an unprivileged deploy user
- A database and database user already exist
- You can either:
  - point the site document root to `current/public`, or
  - upload the full app into the web root and rely on the repo-root `index.php` and `.htaccess`

The repo-root forwarder is mainly useful on Apache/shared-hosting style setups. If you are using Nginx, the preferred approach is still to point the site root at `current/public`.

## Recommended Release Layout

Use a simple release-based layout in your home directory:

```bash
export APP_NAME=printingservices
export APP_ROOT="$HOME/apps/$APP_NAME"

mkdir -p "$APP_ROOT/releases" "$APP_ROOT/shared"
mkdir -p "$APP_ROOT/shared/storage/app/private"
mkdir -p "$APP_ROOT/shared/storage/app/public"
mkdir -p "$APP_ROOT/shared/storage/framework/cache/data"
mkdir -p "$APP_ROOT/shared/storage/framework/sessions"
mkdir -p "$APP_ROOT/shared/storage/framework/views"
mkdir -p "$APP_ROOT/shared/storage/logs"
```

Result:

```text
$APP_ROOT/
  current -> releases/20260604123000
  releases/
  shared/
    .env
    storage/
```

## 1. Build A Deployable Release

Choose one of these two approaches.

### Option A: Build On The Server

Use this if the server already has working `composer`, `node`, and `npm` access.

### Option B: Build Locally And Upload An Artifact

Use this if the server does not have Node.js or does not have internet/package access.

From your local checkout:

```bash
cd /path/to/printingservices.gov.mw

composer check-platform-reqs
composer install --no-dev --prefer-dist --optimize-autoloader
npm run build

tar \
  --exclude=.git \
  --exclude=node_modules \
  --exclude=tests \
  --exclude=.env \
  --exclude=.env.* \
  --exclude=storage/logs/* \
  --exclude=storage/framework/cache/* \
  --exclude=storage/framework/sessions/* \
  --exclude=storage/framework/views/* \
  --exclude=bootstrap/cache/*.php \
  --exclude=public/storage \
  -czf /tmp/printingservices-release.tgz .
```

On Windows PowerShell, use `npm.cmd run build` instead of `npm run build`.

## 2. Create The Production `.env`

SSH to the server and keep that shell open:

```bash
ssh youruser@yourserver

export APP_NAME=printingservices
export APP_ROOT="$HOME/apps/$APP_NAME"

APP_KEY_VALUE="$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")"
printf '%s\n' "$APP_KEY_VALUE"
```

Create `shared/.env` on the server:

```bash
cat > "$APP_ROOT/shared/.env" <<ENV
APP_NAME="Printing Services"
APP_ENV=production
APP_KEY=$APP_KEY_VALUE
APP_DEBUG=false

# Domain root example:
# APP_URL=https://printingservices.example.gov.mw
# VITE_BASE_PATH=/

# Subdirectory example:
# APP_URL=https://example.gov.mw/printingservices
# VITE_BASE_PATH=/printingservices/

APP_URL=https://example.gov.mw/printingservices
ASSET_URL=
VITE_BASE_PATH=/printingservices/

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=printingservices
DB_USERNAME=printingservices_user
DB_PASSWORD=change-me

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="Printing Services"

ERP_SYNC_ENABLED=false
ERP_BASE_URL=
ERP_API_KEY=
ERP_TIMEOUT=30
ERP_WEBHOOK_SECRET=
ERP_ESTIMATION_URL_TEMPLATE=

QUOTATION_REFERENCE_PREFIX=WEB
QUOTATION_RATE_LIMIT=5
QUOTATION_RATE_LIMIT_DECAY=60
QUOTATION_MAX_FILES=5
QUOTATION_MAX_FILE_SIZE_KB=10240
QUOTATION_CAPTCHA_REQUIRED=false
QUOTATION_NOTIFICATION_EMAIL=ops@example.gov.mw
ENV
```

Notes:

- If the app is served from the domain root, set `VITE_BASE_PATH=/`
- If the app is served from a subdirectory, `APP_URL` and `VITE_BASE_PATH` must match that subdirectory
- Leave `SESSION_PATH=` blank unless you have a specific reason to override it

## 3. Upload And Unpack A Release

From your local machine, pick a release name and upload the tarball:

```bash
export RELEASE="$(date +%Y%m%d%H%M%S)"
scp /tmp/printingservices-release.tgz youruser@yourserver:"apps/printingservices/releases/$RELEASE.tgz"
```

Then, in the server shell, unpack it:

```bash
export RELEASE=20260604123000  # reuse the same value from the upload step

mkdir -p "$APP_ROOT/releases/$RELEASE"
tar -xzf "$APP_ROOT/releases/$RELEASE.tgz" -C "$APP_ROOT/releases/$RELEASE"

ln -sfn "$APP_ROOT/shared/.env" "$APP_ROOT/releases/$RELEASE/.env"
rm -rf "$APP_ROOT/releases/$RELEASE/storage"
ln -sfn "$APP_ROOT/shared/storage" "$APP_ROOT/releases/$RELEASE/storage"

mkdir -p "$APP_ROOT/releases/$RELEASE/bootstrap/cache"
```

If you are building on the server instead of uploading a ready-made artifact, replace the `tar` step with either a `git clone` or an `rsync` upload into `$APP_ROOT/releases/$RELEASE`, then run:

```bash
cd "$APP_ROOT/releases/$RELEASE"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci
npm run build
```

## 4. First-Time Bootstrap For A New Production Environment

Run these commands inside the new release:

```bash
cd "$APP_ROOT/releases/$RELEASE"

php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
php artisan db:seed --class=CatalogSeeder --force
php artisan db:seed --class=SiteSettingsSeeder --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Create the first real admin user with a strong password:

```bash
export ADMIN_EMAIL=admin@example.gov.mw
export ADMIN_NAME="Production Admin"
export ADMIN_PASSWORD="$(php -r "echo bin2hex(random_bytes(16));")"

printf 'Temporary admin password: %s\n' "$ADMIN_PASSWORD"

php artisan tinker <<'TINKER'
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['email' => getenv('ADMIN_EMAIL')],
    [
        'name' => getenv('ADMIN_NAME'),
        'password' => Hash::make(getenv('ADMIN_PASSWORD')),
        'is_staff' => true,
        'email_verified_at' => now(),
    ]
);

$user->assignRole('super_admin');
TINKER
```

Do not run `AdminUserSeeder` in production.

## 5. Activate The Release

Switch the `current` symlink only after the new release has migrated and cached successfully:

```bash
ln -sfn "$APP_ROOT/releases/$RELEASE" "$APP_ROOT/current"
```

If your web server can be pointed directly at the app, use:

```text
$APP_ROOT/current/public
```

If you cannot change the document root and must deploy into a web-accessible directory, this repository can still work because the repo root forwards requests to `public/` through the checked-in `index.php` and `.htaccess`.

## 6. Queue And Scheduler Without `sudo`

Because the app uses:

- `QUEUE_CONNECTION=database`
- daily scheduled ERP sync jobs

you should run both the scheduler and the queue from your user account.

The simplest no-sudo approach is a user crontab:

```bash
( crontab -l 2>/dev/null | grep -v 'apps/printingservices' || true
  echo "* * * * * cd $APP_ROOT/current && php artisan schedule:run >> $APP_ROOT/shared/storage/logs/schedule.log 2>&1"
  echo "* * * * * cd $APP_ROOT/current && php artisan queue:work --stop-when-empty --tries=3 --max-time=55 >> $APP_ROOT/shared/storage/logs/queue.log 2>&1"
) | crontab -
```

This is suitable when you do not have `systemd` access. If the host does not allow user crontabs, use the hosting control panel scheduler or keep a long-running worker alive in `tmux` or `screen`.

## 7. Rolling Deployments

For later releases:

```bash
cd "$APP_ROOT/releases/$RELEASE"

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

ln -sfn "$APP_ROOT/releases/$RELEASE" "$APP_ROOT/current"
```

Only rerun the content seeders if you deliberately want to refresh seeded catalog or site-setting data.

## 8. Smoke Tests

After switching the release:

```bash
cd "$APP_ROOT/current"

php artisan about --only=environment,cache,drivers
php artisan migrate:status
php artisan queue:failed
php artisan schedule:list

curl -I https://example.gov.mw/printingservices/up
curl -I https://example.gov.mw/printingservices/admin/login

tail -n 100 storage/logs/laravel.log
tail -n 100 "$APP_ROOT/shared/storage/logs/queue.log"
tail -n 100 "$APP_ROOT/shared/storage/logs/schedule.log"
```

Expected outcomes:

- `/up` returns a healthy response
- `/admin/login` loads
- `php artisan migrate:status` shows all migrations applied
- `public/storage` exists and uploaded artwork can be served

## 9. Rollback

List releases:

```bash
ls -1 "$APP_ROOT/releases"
```

Point `current` back to the previous release:

```bash
export PREVIOUS_RELEASE=20260604123000
ln -sfn "$APP_ROOT/releases/$PREVIOUS_RELEASE" "$APP_ROOT/current"
```

Then recheck:

```bash
cd "$APP_ROOT/current"
php artisan about --only=environment,cache,drivers
curl -I https://example.gov.mw/printingservices/up
```

## 10. Quick Checklist

Before go-live:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` is correct
- `VITE_BASE_PATH` matches the real URL path
- database credentials are correct
- `php artisan migrate --force` completed
- `php artisan storage:link` completed
- `current/public` is the document root, or the repo-root forwarder is in use
- queue and scheduler are running from user cron or another unprivileged runner
- no default seeded admin passwords are in use
