# Automated Deployment (CI/CD) Guide

Enable zero-downtime deployments with GitHub Actions, GitLab CI, or similar.

---

## GitHub Actions Setup

### Step 1: Store Secrets in GitHub

Navigate to **Settings → Secrets and Variables → Actions** in your GitHub repository.

Add these secrets:

| Secret Name | Value | Example |
|-------------|-------|---------|
| `SSH_HOST` | Production server hostname | `production.opc.gov.mw` |
| `SSH_USER` | Application user (non-root) | `appuser` |
| `SSH_KEY` | Private SSH key (paste full content) | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `SSH_PORT` | SSH port (usually 22) | `22` |
| `DEPLOY_ENV_FILE` | Content of `.env` for production | See [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md) |

### Step 2: Generate SSH Key Pair (if needed)

On your local machine or CI system:

```bash
# Generate new SSH key
ssh-keygen -t ed25519 -f deploy_key -N "" -C "deploy@ci"

# Add public key to production server
# As appuser on production server:
cat deploy_key.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# Copy private key to GitHub Secrets as SSH_KEY
cat deploy_key
```

### Step 3: Create GitHub Actions Workflow

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main
      - master
  workflow_dispatch:  # Manual trigger

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4
      
      - name: Deploy via SSH
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          port: ${{ secrets.SSH_PORT }}
          script: |
            cd /var/www/printingservices
            
            # Pull latest code
            git fetch origin
            git checkout origin/main
            
            # Update .env from GitHub Secrets
            cat > .env << 'EOF'
            ${{ secrets.DEPLOY_ENV_FILE }}
            EOF
            
            # Run deployment script
            ./deploy.sh --non-interactive --skip-build
            
            # Verify deployment
            php artisan migrate:status
            
            echo "Deployment completed at $(date)"
      
      - name: Notify on success
        if: success()
        run: echo "✅ Deployment successful"
      
      - name: Notify on failure
        if: failure()
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          port: ${{ secrets.SSH_PORT }}
          script: |
            echo "❌ Deployment failed - check logs at /tmp/deployment_*.log"
            tail -100 /tmp/deployment_*.log | tail -1
```

### Step 4: Deploy on Push

```bash
# Push to main branch triggers automatic deployment
git commit -m "Feature: add new quotation fields"
git push origin main

# Check deployment progress in GitHub Actions tab
# View logs: Actions → Latest workflow → Deploy job
```

---

## GitLab CI Setup

### Step 1: Store Secrets in GitLab

Navigate to **Settings → CI/CD → Variables** in your GitLab project.

Add these variables:

```yaml
SSH_HOST: production.opc.gov.mw
SSH_USER: appuser
SSH_KEY: (paste private key)
SSH_PORT: 22
DEPLOY_ENV_FILE: (paste .env content)
```

### Step 2: Create GitLab CI/CD Pipeline

Create `.gitlab-ci.yml`:

```yaml
stages:
  - test
  - deploy

variables:
  PHP_VERSION: "8.2"
  NODE_VERSION: "18"

# Optional: Run tests before deploy
test:
  stage: test
  image: php:8.2
  script:
    - composer install --no-dev
    - php artisan test
  only:
    - merge_requests

deploy:production:
  stage: deploy
  image: alpine:latest
  before_script:
    - apk add --no-cache openssh-client
    - mkdir -p ~/.ssh
    - chmod 700 ~/.ssh
    - echo "$SSH_KEY" > ~/.ssh/deploy_key
    - chmod 600 ~/.ssh/deploy_key
    - ssh-keyscan -p $SSH_PORT -H $SSH_HOST >> ~/.ssh/known_hosts 2>/dev/null
  script:
    - ssh -i ~/.ssh/deploy_key -p $SSH_PORT $SSH_USER@$SSH_HOST << 'DEPLOY'
        cd /var/www/printingservices
        git fetch origin
        git checkout origin/$CI_COMMIT_BRANCH
        
        cat > .env << 'EOF'
        $DEPLOY_ENV_FILE
        EOF
        
        ./deploy.sh --non-interactive
        
        echo "✅ Deployment completed at $(date)"
      DEPLOY
  environment:
    name: production
    url: https://printingservices.opc.gov.mw
  only:
    - main
    - master
  when: manual  # Require manual trigger for safety
```

### Step 3: Trigger Deployment

```bash
git push origin main
# Go to GitLab project → CI/CD → Pipelines
# Click "Deploy" button on pipeline
```

---

## Manual Deployment Script (No CI/CD)

If not using GitHub Actions or GitLab CI:

```bash
#!/bin/bash
# File: deploy_remote.sh
# Usage: ./deploy_remote.sh appuser production.opc.gov.mw

set -e

DEPLOY_USER=$1
DEPLOY_HOST=$2
DEPLOY_DIR="/var/www/printingservices"

if [ -z "$DEPLOY_USER" ] || [ -z "$DEPLOY_HOST" ]; then
    echo "Usage: $0 <user> <host>"
    echo "Example: $0 appuser production.opc.gov.mw"
    exit 1
fi

echo "🚀 Deploying to $DEPLOY_HOST..."

# SSH and run deployment
ssh -t $DEPLOY_USER@$DEPLOY_HOST << 'REMOTE_COMMANDS'
    set -e
    
    cd /var/www/printingservices
    
    echo "📥 Pulling latest code..."
    git fetch origin
    git checkout origin/main
    
    echo "📦 Installing dependencies..."
    composer install --no-dev --optimize-autoloader
    npm ci
    
    echo "🏗️  Building assets..."
    npm run build
    
    echo "🔄 Running migrations..."
    php artisan migrate --force
    
    echo "⚡ Optimizing..."
    php artisan cache:clear
    php artisan config:cache
    php artisan route:cache
    
    echo "✅ Deployment complete!"
    echo "🌐 Testing: curl -I https://printingservices.opc.gov.mw/"
    curl -I https://printingservices.opc.gov.mw/ | head -5
REMOTE_COMMANDS

echo "✅ Remote deployment finished!"
```

Run it:
```bash
chmod +x deploy_remote.sh
./deploy_remote.sh appuser production.opc.gov.mw
```

---

## Zero-Downtime Deployment Strategy

### Traditional Approach (Brief Downtime)

```bash
# 1. Enable maintenance mode
php artisan down --secret=abc123def456

# 2. Pull code, install, build
git pull
composer install --no-dev
npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Clear caches
php artisan cache:clear
php artisan config:cache

# 5. Disable maintenance mode
php artisan up
```

**Downtime:** ~2-5 minutes

### Zero-Downtime Approach (No Downtime)

```bash
#!/bin/bash
# deploy-zero-downtime.sh

set -e

APP_DIR="/var/www/printingservices"
RELEASES_DIR="$APP_DIR/releases"
CURRENT_LINK="$APP_DIR/current"
STORAGE_DIR="$APP_DIR/storage"
SHARED_DIR="$APP_DIR/shared"

# Create directories
mkdir -p $RELEASES_DIR
mkdir -p $SHARED_DIR

# 1. Create new release directory
RELEASE_TIME=$(date +%Y%m%d_%H%M%S)
RELEASE_DIR="$RELEASES_DIR/$RELEASE_TIME"

echo "📁 Creating release: $RELEASE_TIME"
mkdir -p $RELEASE_DIR

# 2. Clone/copy application to release directory
cd $RELEASE_DIR
git clone -b main --depth 1 https://your-repo.git ./

# 3. Install dependencies (in new directory, doesn't affect current)
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# 4. Create symlinks to shared resources
echo "🔗 Setting up shared files..."
ln -nfs $SHARED_DIR/storage storage
ln -nfs $SHARED_DIR/.env .env

# 5. Run migrations (still doesn't affect current app)
echo "🔄 Running migrations..."
php artisan migrate --force

# 6. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Atomic switch: Point current symlink to new release
echo "🔀 Switching to new release..."
ln -nfs $RELEASE_DIR $CURRENT_LINK

# 8. Warm up caches (post-switch)
php artisan cache:clear
php artisan optimize

echo "✅ Zero-downtime deployment complete!"

# 9. Cleanup old releases (keep last 5)
echo "🧹 Cleaning up old releases..."
cd $RELEASES_DIR
ls -1t | tail -n +6 | xargs -r rm -rf

# 10. Verify new release
echo "🔍 Verifying..."
curl -I https://printingservices.opc.gov.mw/ | head -3
```

**Benefits:**
- ✅ No downtime
- ✅ Instant rollback (symlink switch)
- ✅ Old release still available

**Run it:**
```bash
chmod +x deploy-zero-downtime.sh
./deploy-zero-downtime.sh
```

**Rollback (if needed):**
```bash
# Link back to previous release
ln -nfs /var/www/printingservices/releases/PREVIOUS_TIMESTAMP /var/www/printingservices/current

# Done - no code redeploy needed!
curl -I https://printingservices.opc.gov.mw/
```

---

## Pre-Deployment Checks

Add to deployment script before running migrations:

```bash
#!/bin/bash
# Check if safe to deploy

APP_DIR="/var/www/printingservices"
cd $APP_DIR

echo "🔍 Pre-deployment checks..."

# 1. Check database connectivity
if ! php artisan tinker --execute "DB::connection()->getPdo();" 2>/dev/null | grep -q "^true"; then
    echo "❌ Database unreachable"
    exit 1
fi

# 2. Verify migrations can run
if ! php artisan migrate:status 2>/dev/null | head -5; then
    echo "❌ Migration status check failed"
    exit 1
fi

# 3. Check storage is writable
if ! touch storage/.deploy-test && rm storage/.deploy-test; then
    echo "❌ Storage directory not writable"
    exit 1
fi

# 4. Check for uncommitted changes (should not happen in prod)
if [ -d ".git" ]; then
    if ! git diff --quiet; then
        echo "⚠️  Uncommitted changes detected"
        # Decide: exit 1 (fail) or git stash (auto-stash)
    fi
fi

# 5. Verify disk space
FREE_SPACE=$(df /var/www | awk 'NR==2 {print $4}')
if [ $FREE_SPACE -lt 1000000 ]; then  # Less than 1GB
    echo "❌ Insufficient disk space"
    exit 1
fi

echo "✅ All pre-deployment checks passed"
```

---

## Post-Deployment Verification

```bash
#!/bin/bash
# Verify deployment success

APP_DIR="/var/www/printingservices"
cd $APP_DIR

echo "✅ Verifying deployment..."

# 1. Check HTTP response
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" https://printingservices.opc.gov.mw/)
if [ "$RESPONSE" != "200" ] && [ "$RESPONSE" != "302" ] && [ "$RESPONSE" != "301" ]; then
    echo "❌ Unexpected HTTP response: $RESPONSE"
    exit 1
fi
echo "✅ HTTP response: $RESPONSE"

# 2. Check admin panel
ADMIN=$(curl -s -o /dev/null -w "%{http_code}" https://printingservices.opc.gov.mw/admin)
if [ "$ADMIN" != "200" ] && [ "$ADMIN" != "302" ] && [ "$ADMIN" != "401" ]; then
    echo "❌ Admin panel error: $ADMIN"
    exit 1
fi
echo "✅ Admin panel accessible: $ADMIN"

# 3. Check logs for errors
if grep -i "error\|critical" storage/logs/laravel.log | tail -5 | grep -q "error"; then
    echo "⚠️  Recent errors found in logs:"
    grep -i "error\|critical" storage/logs/laravel.log | tail -5
fi

# 4. Check queue status (if using queues)
FAILED=$(php artisan queue:failed 2>/dev/null | tail -1 | awk '{print $1}')
if [ ! -z "$FAILED" ] && [ "$FAILED" != "0" ]; then
    echo "⚠️  Failed jobs in queue: $FAILED"
fi

echo "✅ All verification checks passed"
```

---

## Monitoring During Deployment

Watch logs in real-time:

```bash
# Terminal 1: Watch application logs
tail -f /var/www/printingservices/storage/logs/laravel.log

# Terminal 2: Watch web server logs
tail -f /var/log/nginx/printingservices-error.log

# Terminal 3: Monitor server resources
watch -n 1 'free -h && echo "---" && df -h /var/www'

# Terminal 4: Monitor PHP processes
watch -n 2 'ps aux | grep php-fpm | grep -v grep | wc -l'
```

---

## Deployment Status Notifications

Add email notifications to deployment script:

```bash
#!/bin/bash

send_notification() {
    local status=$1
    local message=$2
    
    cat << EOF | mail -s "Deployment: $status" ops@example.com
    
    Deployment Status: $status
    Timestamp: $(date)
    Host: $(hostname)
    User: $(whoami)
    
    Message: $message
    
    Application: https://printingservices.opc.gov.mw
    Logs: https://your-log-viewer.example.com/printingservices
    
EOF
}

# In your deployment script:

if ./deploy.sh --non-interactive; then
    send_notification "SUCCESS" "Deployment completed successfully"
else
    send_notification "FAILED" "Deployment failed - check logs"
    exit 1
fi
```

---

## Automated Deployment Schedule

Run deployments at off-peak times:

```bash
# Add to appuser's crontab
# crontab -e

# Deploy nightly at 2 AM
0 2 * * * cd /var/www/printingservices && ./deploy.sh --non-interactive >> /var/log/printingservices-auto-deploy.log 2>&1

# Deploy weekly (Sunday 3 AM)
0 3 * * 0 cd /var/www/printingservices && ./deploy.sh --non-interactive >> /var/log/printingservices-auto-deploy.log 2>&1
```

---

## Deployment Rollback Procedure

If deployment fails:

```bash
#!/bin/bash
# rollback.sh - Quick rollback to previous release

set -e

RELEASES_DIR="/var/www/printingservices/releases"
CURRENT_LINK="/var/www/printingservices/current"

echo "Rolling back to previous release..."

# Get previous release directory
CURRENT_RELEASE=$(readlink $CURRENT_LINK)
PREVIOUS_RELEASE=$(ls -1t $RELEASES_DIR | head -2 | tail -1)

if [ -z "$PREVIOUS_RELEASE" ]; then
    echo "❌ No previous release found"
    exit 1
fi

PREVIOUS_PATH="$RELEASES_DIR/$PREVIOUS_RELEASE"

echo "Switching to: $PREVIOUS_PATH"

# Atomic switch
ln -nfs $PREVIOUS_PATH $CURRENT_LINK

# Clear caches
cd $CURRENT_LINK
php artisan cache:clear
php artisan config:cache

echo "✅ Rollback complete"
curl -I https://printingservices.opc.gov.mw/ | head -3
```

---

**Version:** 1.0  
**Last Updated:** June 2026
