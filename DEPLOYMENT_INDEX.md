# Production Deployment Documentation Index

Complete CLI-only, no-sudo production deployment guides for **Printing Services** (Laravel 12 + Filament).

---

## 📚 Documentation Files

### 1. **[PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md)** — Main Deployment Guide
**Read this first for comprehensive production setup**

- ✅ Prerequisites & version requirements
- ✅ Server environment setup (non-root user)
- ✅ Application deployment steps
- ✅ Database migrations & seeding
- ✅ Web server configuration (Nginx & Apache)
- ✅ SSL/TLS setup with Let's Encrypt
- ✅ Post-deployment verification
- ✅ Backup & recovery procedures
- ✅ Monitoring & maintenance tasks
- ✅ Troubleshooting common issues

**When to use:** First-time production deployment, migrating servers, complete setup reference

---

### 2. **[deploy.sh](deploy.sh)** — Automated Deployment Script
**Use this for fast, consistent deployments**

```bash
# Interactive deployment
chmod +x deploy.sh
./deploy.sh

# Automated (CI/CD)
./deploy.sh --non-interactive --env

# Skip specific steps
./deploy.sh --skip-build --skip-migrations
```

**Features:**
- ✅ Dependency installation (PHP & Node)
- ✅ Asset building with Vite
- ✅ Database migrations
- ✅ Cache optimization
- ✅ Pre-deployment verification
- ✅ Admin user creation

**When to use:** Every deployment, automated CI/CD pipelines, quick updates

---

### 3. **[DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md)** — Quick Cheat Sheet
**Fast reference for common operations**

**Sections:**
- ⚡ Fast deployment commands
- 🔍 View application logs
- 💾 Database operations
- 👤 User/permissions management
- 📊 Server health monitoring
- 🚨 Emergency procedures
- 📋 Pre-deployment checklist (printable)
- 📞 Contacts & escalation

**When to use:** Daily operations, quick lookups, during incidents

---

### 4. **[ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md)** — Environment Setup
**Complete reference for all environment variables**

**Includes:**
- 📝 Application settings (APP_NAME, APP_ENV, APP_DEBUG, etc.)
- 🗄️ Database configuration
- 💬 Mail & SMTP setup
- 🔒 Security & SSL settings
- 📊 Cache & session configuration
- 🔌 ERP integration settings
- ✅ Verification commands
- 🛡️ Security checklist

**When to use:** Setting up .env files, understanding configuration, troubleshooting specific variables

---

### 5. **[CI_CD_DEPLOYMENT_GUIDE.md](CI_CD_DEPLOYMENT_GUIDE.md)** — Automated Deployments
**GitHub Actions, GitLab CI, and zero-downtime strategies**

**Includes:**
- 🚀 GitHub Actions setup (complete workflow)
- 🦊 GitLab CI setup (complete pipeline)
- 🔄 Zero-downtime deployment strategy
- ✅ Pre-deployment checks
- ✅ Post-deployment verification
- 📧 Status notifications
- ⏰ Scheduled deployments
- 🔙 Automated rollback procedure

**When to use:** Setting up CI/CD, automating deployments, implementing zero-downtime strategy

---

### 6. **[PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md)** — Troubleshooting Guide
**Diagnostic procedures for common issues**

**Covers:**
- 🔍 Diagnostic checklist (run first!)
- 🔴 500 Internal Server Error
- 📦 Missing CSS/JS assets
- 🗄️ Database connection failures
- 🔓 Admin panel access issues
- ⚡ Slow performance / memory issues
- 📬 Queue jobs not processing
- 📧 Mail not sending
- 🔄 Migration failures
- 💾 Storage directory issues
- 🔐 SSL certificate problems
- 🆘 Emergency commands

**When to use:** Application is broken, diagnosing issues, emergency response

---

## 🚀 Quick Start Paths

### I. Fresh Production Deployment

1. Read: [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md) **Prerequisites** section
2. Follow: **Server Environment Setup** → **Application Deployment**
3. Use: [deploy.sh](deploy.sh) to automate steps 2-7
4. Verify: [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md) **Post-Deployment Verification**
5. Reference: [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md)

**Estimated Time:** 30-45 minutes

### II. Setting Up Environment File

1. Review: [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md) for each variable
2. Use: Example `.env` template from [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md)
3. Update: With your infrastructure credentials
4. Verify: Security checklist in [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md)

**Estimated Time:** 15-20 minutes

### III. Setting Up Automated Deployments

1. Read: [CI_CD_DEPLOYMENT_GUIDE.md](CI_CD_DEPLOYMENT_GUIDE.md) **Step 1-2**
2. Choose: GitHub Actions or GitLab CI
3. Implement: Copy workflow/pipeline YAML
4. Store: Secrets in GitHub/GitLab
5. Test: Trigger first deployment

**Estimated Time:** 20-30 minutes

### IV. Application is Down

1. Run: Diagnostic checklist from [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md)
2. Find: Your issue in the 10 common problems
3. Follow: Step-by-step solution
4. Verify: Application is back online
5. Implement: Monitoring from [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md)

**Estimated Time:** 5-30 minutes (depending on severity)

### V. Daily Operations

**Morning check:**
```bash
# From DEPLOYMENT_QUICK_REFERENCE.md
tail -20 /var/www/printingservices/storage/logs/laravel.log
curl -I https://printingservices.opc.gov.mw/
```

**Weekly maintenance:**
```bash
# From PRODUCTION_DEPLOYMENT_GUIDE.md → Monitoring & Maintenance
# Run weekly maintenance script
/home/appuser/weekly-maintenance.sh
```

**When deploying:**
```bash
# From deploy.sh
cd /var/www/printingservices
./deploy.sh

# Or CI/CD handles it automatically
```

---

## 📋 Deployment Workflow

```
┌─────────────────────────────────┐
│  Ready to Deploy?               │
│  • Code committed & pushed      │
│  • .env configured              │
│  • Backup taken                 │
└─────────────────────┬───────────┘
                      │
                      ▼
        ┌─────────────────────────┐
        │ Choose Deployment Type  │
        └─────────────────────────┘
                    │
         ┌──────────┼──────────┐
         │          │          │
         ▼          ▼          ▼
      Manual    GitHub CI   GitLab CI
      Deploy    Actions      Pipeline
         │          │          │
         └──────────┼──────────┘
                    │
                    ▼
        ┌─────────────────────────┐
        │  Run Deploy Script      │
        │  ./deploy.sh            │
        └─────────────────────────┘
                    │
         ┌──────────┴──────────┐
         │                     │
         ▼                     ▼
   ✅ Success           ❌ Failed
   │                    │
   ▼                    ▼
 Verify             Troubleshoot
 • Test app         • Check logs
 • Check logs       • See Troubleshooting
 • Monitor 30min    • Rollback if needed
```

---

## 🔧 Essential CLI Commands

### Deployment
```bash
./deploy.sh --non-interactive --env          # Full deployment
git pull origin main                         # Update code
npm run build                                # Build assets
php artisan migrate --force                  # Run migrations
php artisan db:seed                          # Seed database
```

### Monitoring
```bash
tail -f storage/logs/laravel.log             # Watch logs
curl -I https://printingservices.opc.gov.mw/ # Test site
ps aux | grep php-fpm | wc -l               # Check processes
df -h                                        # Check disk
free -h                                      # Check memory
```

### Maintenance
```bash
php artisan cache:clear                      # Clear cache
php artisan config:cache                     # Optimize config
php artisan route:cache                      # Optimize routes
php artisan tinker                           # Debug console
```

### Backups
```bash
mysqldump -h $HOST -u $USER -p $DB | gzip > backup_$(date +%Y%m%d).sql.gz
tar -czf backup_app_$(date +%Y%m%d).tar.gz app/ config/ routes/
```

---

## 🚨 Emergency Response Checklist

| Severity | Action | Reference |
|----------|--------|-----------|
| **App Down** | 1. Check logs<br>2. Run diagnostic<br>3. Follow troubleshooting | [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md) |
| **DB Down** | 1. Test connection<br>2. Verify credentials<br>3. Check network | [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md#3-database-connection-failed) |
| **Disk Full** | 1. Check disk usage<br>2. Clean logs<br>3. Delete old backups | [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md#check-disk-space) |
| **Slow Site** | 1. Check memory/CPU<br>2. Monitor database<br>3. Review logs | [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md#5-slow-performance--high-memory-usage) |
| **Bad Deploy** | 1. Check logs<br>2. Rollback<br>3. Investigate | [CI_CD_DEPLOYMENT_GUIDE.md](CI_CD_DEPLOYMENT_GUIDE.md#deployment-rollback-procedure) |

---

## 📞 When to Contact Sysadmin

You can handle most operations via CLI (no sudo). Contact sysadmin for:

- ✓ Restarting web server (nginx/apache)
- ✓ Restarting PHP-FPM
- ✓ Restarting MySQL
- ✓ Firewall rules
- ✓ SSL certificate installation (manual certs)
- ✓ System-level file permissions
- ✓ Server updates/patches

**You can handle yourself:**
- ✓ Deployments (via deploy.sh)
- ✓ Database migrations
- ✓ Cache clearing
- ✓ Application logs
- ✓ Backups & restores
- ✓ User management
- ✓ Configuration changes
- ✓ Monitoring & alerts

---

## 📅 Recommended Schedule

| Frequency | Task | Reference |
|-----------|------|-----------|
| **Daily** | Monitor logs & errors | [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md) |
| **Daily** | Auto-backups run | [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md#automated-daily-backups) |
| **Weekly** | Run maintenance script | [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md#weekly-maintenance-tasks) |
| **Weekly** | Check disk usage | [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md#check-disk-space) |
| **Monthly** | Review security logs | [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md) |
| **Quarterly** | Update dependencies | [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md#weekly-maintenance-tasks) |
| **As Needed** | Deploy updates | [deploy.sh](deploy.sh) |

---

## 🎓 Learning Path

**New to this application?** Follow this order:

1. **Day 1:** Read [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md) — Understand the big picture
2. **Day 2:** Run through [deploy.sh](deploy.sh) in test environment — Get hands-on
3. **Day 3:** Review [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md) — Understand configuration
4. **Day 4:** Study [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md) — Know how to fix things
5. **Day 5:** Set up monitoring from [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md) — Proactive management

---

## 🔗 File Organization

All deployment files are in the root of the Laravel project:

```
/var/www/printingservices/
├── PRODUCTION_DEPLOYMENT_GUIDE.md     ← Main guide
├── deploy.sh                          ← Auto deployment script
├── DEPLOYMENT_QUICK_REFERENCE.md      ← Cheat sheet
├── ENV_CONFIGURATION_GUIDE.md         ← .env reference
├── CI_CD_DEPLOYMENT_GUIDE.md          ← Automation guide
├── PRODUCTION_TROUBLESHOOTING.md      ← Debugging guide
│
├── .env                               ← (DO NOT COMMIT)
├── .env-prod                          ← Production template
├── .env.example                       ← Template example
│
├── app/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
└── public/
```

---

## ✅ Pre-Production Checklist

- [ ] All documentation reviewed
- [ ] Server prerequisites met
- [ ] SSH access configured
- [ ] Database created & credentials verified
- [ ] SSL certificate obtained
- [ ] .env file prepared
- [ ] Backup system in place
- [ ] Monitoring scripts installed
- [ ] Cron jobs scheduled
- [ ] Team trained on procedures
- [ ] On-call escalation plan documented

---

## 📞 Support Resources

- **Technical Issues:** Check [PRODUCTION_TROUBLESHOOTING.md](PRODUCTION_TROUBLESHOOTING.md)
- **Configuration Help:** See [ENV_CONFIGURATION_GUIDE.md](ENV_CONFIGURATION_GUIDE.md)
- **Deployment Questions:** Review [PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md)
- **Quick Commands:** Use [DEPLOYMENT_QUICK_REFERENCE.md](DEPLOYMENT_QUICK_REFERENCE.md)
- **Automation Setup:** Follow [CI_CD_DEPLOYMENT_GUIDE.md](CI_CD_DEPLOYMENT_GUIDE.md)

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | June 2026 | Initial comprehensive deployment guides |

---

## 🎯 Key Principles

1. **CLI-Only:** All operations via command line, no GUI tools
2. **No Sudo:** Application user (appuser) handles all deployment operations
3. **Reproducible:** Same steps work across all environments
4. **Automated:** Deploy with single script or CI/CD pipeline
5. **Recoverable:** Backup & rollback procedures in place
6. **Monitorable:** Health checks and logs readily available
7. **Documented:** Each guide is self-contained and comprehensive

---

**Created:** June 2026  
**For:** Government Press Printing Services  
**Application:** Laravel 12 + Filament Admin  
**Maintained By:** DevOps Team

---

**Questions?** Review the relevant guide from the index above.
