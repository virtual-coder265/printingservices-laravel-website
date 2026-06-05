#!/bin/bash

################################################################################
# PRODUCTION DEPLOYMENT SCRIPT - NO SUDO REQUIRED
# 
# Usage:
#   chmod +x deploy.sh
#   ./deploy.sh                           # Interactive mode
#   ./deploy.sh --non-interactive --env   # Non-interactive, copy .env-prod
#   ./deploy.sh --help                    # Show all options
#
# Prerequisites:
#   - SSH access as appuser (non-root)
#   - PHP 8.2+, Node 18+, Composer, MySQL CLI
#   - Database already created
#   - SSL certificate installed
#
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/printingservices"
LOG_FILE="/tmp/deployment_$(date +%Y%m%d_%H%M%S).log"
INTERACTIVE=true
COPY_ENV=false

# Function to print colored output
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a $LOG_FILE
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a $LOG_FILE
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $LOG_FILE
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $LOG_FILE
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to confirm action
confirm() {
    local prompt="$1"
    local response
    
    if [ "$INTERACTIVE" = false ]; then
        return 0  # Auto-confirm in non-interactive mode
    fi
    
    read -p "$(echo -e ${YELLOW}$prompt${NC} ' (y/N): ')" -n 1 -r
    echo
    [[ $REPLY =~ ^[Yy]$ ]]
}

# Help message
show_help() {
    cat << EOF
PRODUCTION DEPLOYMENT SCRIPT - No Sudo Required

Usage:
    $(basename $0) [OPTIONS]

Options:
    --non-interactive     Skip all prompts and use defaults
    --env                 Copy .env-prod to .env automatically
    --skip-migrations     Don't run database migrations
    --skip-seeds          Don't seed the database
    --skip-build          Don't build frontend assets
    --skip-deps           Don't install dependencies
    --help                Show this help message

Examples:
    # Interactive deployment
    ./deploy.sh

    # Automated deployment (CI/CD)
    ./deploy.sh --non-interactive --env

    # Deploy without rebuilding assets
    ./deploy.sh --skip-build

EOF
    exit 0
}

# Parse command line arguments
parse_args() {
    while [[ $# -gt 0 ]]; do
        case $1 in
            --non-interactive)
                INTERACTIVE=false
                shift
                ;;
            --env)
                COPY_ENV=true
                shift
                ;;
            --skip-migrations)
                SKIP_MIGRATIONS=true
                shift
                ;;
            --skip-seeds)
                SKIP_SEEDS=true
                shift
                ;;
            --skip-build)
                SKIP_BUILD=true
                shift
                ;;
            --skip-deps)
                SKIP_DEPS=true
                shift
                ;;
            --help)
                show_help
                ;;
            *)
                log_error "Unknown option: $1"
                show_help
                ;;
        esac
    done
}

# Step 1: Check Prerequisites
step_check_prerequisites() {
    log_info "Checking prerequisites..."
    
    # Check if running as non-root
    if [ "$(id -u)" = "0" ]; then
        log_error "This script must NOT be run as root. Run as 'appuser' instead."
        exit 1
    fi
    
    # Check required commands
    local required_commands=("php" "npm" "composer" "mysql" "git")
    for cmd in "${required_commands[@]}"; do
        if ! command_exists "$cmd"; then
            log_error "Required command not found: $cmd"
            exit 1
        fi
    done
    
    log_success "All prerequisites met"
    
    # Show versions
    log_info "Using PHP $(php -v | head -1)"
    log_info "Using Node $(node -v)"
    log_info "Using Composer $(composer --version | awk '{print $3}')"
}

# Step 2: Navigate to application directory
step_navigate_to_app() {
    log_info "Navigating to application directory..."
    
    if [ ! -d "$APP_DIR" ]; then
        log_error "Application directory not found: $APP_DIR"
        exit 1
    fi
    
    cd "$APP_DIR"
    log_success "Working directory: $(pwd)"
}

# Step 3: Set up environment
step_setup_environment() {
    log_info "Setting up environment file..."
    
    if [ "$COPY_ENV" = true ]; then
        if [ -f ".env-prod" ]; then
            cp .env-prod .env
            log_success "Copied .env-prod to .env"
        else
            log_error ".env-prod file not found"
            exit 1
        fi
    elif [ ! -f ".env" ]; then
        if [ -f ".env.example" ]; then
            cp .env.example .env
            log_warning ".env file created from .env.example - UPDATE WITH PRODUCTION VALUES!"
            exit 1
        else
            log_error "No .env or .env.example file found"
            exit 1
        fi
    fi
    
    # Generate APP_KEY if not set
    if ! grep -q "^APP_KEY=base64:" .env; then
        log_info "Generating APP_KEY..."
        php artisan key:generate
        log_success "APP_KEY generated"
    else
        log_success "APP_KEY already set"
    fi
}

# Step 4: Install dependencies
step_install_dependencies() {
    if [ "$SKIP_DEPS" = true ]; then
        log_info "Skipping dependency installation (--skip-deps)"
        return
    fi
    
    log_info "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader 2>&1 | tee -a $LOG_FILE
    log_success "PHP dependencies installed"
    
    log_info "Installing Node dependencies..."
    npm ci 2>&1 | tee -a $LOG_FILE
    log_success "Node dependencies installed"
}

# Step 5: Build frontend assets
step_build_assets() {
    if [ "$SKIP_BUILD" = true ]; then
        log_info "Skipping asset build (--skip-build)"
        return
    fi
    
    log_info "Building frontend assets..."
    npm run build 2>&1 | tee -a $LOG_FILE
    
    if [ -f "public/build/manifest.json" ]; then
        log_success "Frontend assets built successfully"
    else
        log_error "Asset build failed - manifest.json not found"
        exit 1
    fi
}

# Step 6: Clear caches
step_clear_caches() {
    log_info "Clearing application caches..."
    
    php artisan cache:clear 2>&1 | tee -a $LOG_FILE
    php artisan route:clear 2>&1 | tee -a $LOG_FILE
    php artisan view:clear 2>&1 | tee -a $LOG_FILE
    php artisan config:clear 2>&1 | tee -a $LOG_FILE
    
    log_success "Caches cleared"
}

# Step 7: Optimize configuration
step_optimize() {
    log_info "Optimizing application..."
    
    php artisan config:cache 2>&1 | tee -a $LOG_FILE
    php artisan route:cache 2>&1 | tee -a $LOG_FILE
    php artisan view:cache 2>&1 | tee -a $LOG_FILE
    composer dump-autoload --optimize 2>&1 | tee -a $LOG_FILE
    
    log_success "Application optimized"
}

# Step 8: Set up storage directories
step_setup_storage() {
    log_info "Setting up storage directories..."
    
    # Create necessary directories
    mkdir -p storage/app
    mkdir -p storage/framework/cache
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/logs
    mkdir -p bootstrap/cache
    
    # Set permissions (group-writable by www-data)
    chmod 775 storage
    chmod 775 storage/app
    chmod 775 storage/framework
    chmod 775 storage/framework/cache
    chmod 775 storage/framework/sessions
    chmod 775 storage/framework/views
    chmod 775 storage/logs
    chmod 775 bootstrap/cache
    
    log_success "Storage directories configured"
}

# Step 9: Run database migrations
step_run_migrations() {
    if [ "$SKIP_MIGRATIONS" = true ]; then
        log_info "Skipping migrations (--skip-migrations)"
        return
    fi
    
    log_info "Running database migrations..."
    
    # Show migration status first
    php artisan migrate:status 2>&1 | tee -a $LOG_FILE
    
    if confirm "Run pending migrations?"; then
        php artisan migrate --force 2>&1 | tee -a $LOG_FILE
        log_success "Migrations completed"
    else
        log_warning "Migrations skipped by user"
    fi
}

# Step 10: Seed database
step_seed_database() {
    if [ "$SKIP_SEEDS" = true ]; then
        log_info "Skipping database seeds (--skip-seeds)"
        return
    fi
    
    # Check if we should seed (only if this is first deployment)
    if php artisan tinker --execute "echo User::count();" 2>/dev/null | grep -q "^0"; then
        if confirm "Database is empty. Run seeders?"; then
            log_info "Running database seeders..."
            php artisan db:seed --force 2>&1 | tee -a $LOG_FILE
            log_success "Database seeded"
        fi
    else
        log_info "Database already populated - skipping seeds"
    fi
}

# Step 11: Verify deployment
step_verify_deployment() {
    log_info "Verifying deployment..."
    
    local errors=0
    
    # Check APP_KEY
    if ! grep -q "^APP_KEY=base64:" .env; then
        log_error "APP_KEY not set in .env"
        ((errors++))
    fi
    
    # Check database connection
    if ! php artisan tinker --execute "DB::connection()->getPdo();" 2>/dev/null | grep -q "^true"; then
        log_warning "Could not verify database connection (this is OK if DB is on different host)"
    else
        log_success "Database connection verified"
    fi
    
    # Check asset manifest
    if [ ! -f "public/build/manifest.json" ]; then
        log_error "Asset manifest not found"
        ((errors++))
    else
        log_success "Asset manifest found"
    fi
    
    # Check storage directory is writable
    if touch storage/.write-test > /dev/null 2>&1; then
        rm storage/.write-test
        log_success "Storage directory is writable"
    else
        log_error "Storage directory is not writable"
        ((errors++))
    fi
    
    # Check key Laravel directories exist
    for dir in app config database routes storage public vendor; do
        if [ ! -d "$dir" ]; then
            log_error "Required directory missing: $dir"
            ((errors++))
        fi
    done
    
    if [ $errors -eq 0 ]; then
        log_success "All verification checks passed"
        return 0
    else
        log_error "$errors verification check(s) failed"
        return 1
    fi
}

# Step 12: Display summary
step_summary() {
    echo ""
    echo "======================================================================="
    log_success "DEPLOYMENT COMPLETED SUCCESSFULLY"
    echo "======================================================================="
    echo ""
    echo "Next Steps:"
    echo "  1. Test the application: curl https://printingservices.opc.gov.mw/"
    echo "  2. Check logs: tail -f storage/logs/laravel.log"
    echo "  3. Monitor processes: ps aux | grep php-fpm"
    echo "  4. Verify admin panel: https://printingservices.opc.gov.mw/admin"
    echo ""
    echo "Deployment Log: $LOG_FILE"
    echo ""
}

# Step 13: Generate admin user (optional)
step_create_admin() {
    if confirm "Create/reset admin user?"; then
        log_info "Creating admin user..."
        
        php artisan tinker << 'TINKER'
use App\Models\User;
use Spatie\Permission\Models\Role;

// Delete existing admin if any
User::where('email', 'admin@printingservices.gov.mw')->delete();

// Create new admin
$admin = User::create([
    'name' => 'Administrator',
    'email' => 'admin@printingservices.gov.mw',
    'password' => bcrypt(bin2hex(random_bytes(8))),
    'is_staff' => true,
    'email_verified_at' => now(),
]);

$admin->assignRole('super_admin');

echo "Admin user created:\n";
echo "Email: " . $admin->email . "\n";
echo "Password: Reset via 'Forgot Password' link\n";
TINKER
        
        log_success "Admin user created"
    fi
}

# Main execution
main() {
    parse_args "$@"
    
    echo ""
    echo "======================================================================="
    echo "     PRODUCTION DEPLOYMENT - $(date '+%Y-%m-%d %H:%M:%S')"
    echo "======================================================================="
    echo ""
    
    step_check_prerequisites
    step_navigate_to_app
    step_setup_environment
    step_install_dependencies
    step_build_assets
    step_clear_caches
    step_setup_storage
    step_run_migrations
    step_seed_database
    step_optimize
    step_verify_deployment || exit 1
    step_summary
    step_create_admin
    
    log_success "Deployment finished at $(date '+%Y-%m-%d %H:%M:%S')"
}

# Run main function
main "$@"
