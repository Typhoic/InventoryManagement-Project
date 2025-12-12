<#
PowerShell helper to set up the backend locally with MySQL.
Usage: Run from the `backend` folder in PowerShell: .\scripts\setup-local.ps1
#>

Write-Host "== InventoryManagement Backend Local Setup (MySQL) ==" -ForegroundColor Cyan

# Ensure composer is available
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Composer not found in PATH. Please install Composer and re-run." -ForegroundColor Red
    exit 1
}

# Install PHP dependencies
if (-not (Test-Path .\vendor)) {
    Write-Host "Installing Composer dependencies..."
    composer install
} else {
    Write-Host "Composer dependencies already installed."
}

# Copy .env if missing
if (-not (Test-Path .\.env)) {
    Write-Host "Creating .env from .env.example"
    Copy-Item .\.env.example .\.env
} else {
    Write-Host ".env already exists; skipping copy."
}

Write-Host "\n== Create MySQL database and user ==" -ForegroundColor Cyan
Write-Host "This script will attempt to run the SQL helper (.\database\create_mysql_user.sql) using your MySQL root user."
Write-Host "You will be prompted for the MySQL root password. If you prefer, run the SQL file manually." -ForegroundColor Yellow
Write-Host "Command shown below — run it now or press Enter to run automatically."
Write-Host "mysql -u root -p < .\\database\\create_mysql_user.sql" -ForegroundColor Green

$runNow = Read-Host "Run the command now? (Y/n)"
if ($runNow -eq '' -or $runNow -match '^[Yy]') {
    try {
        & mysql -u root -p < .\database\create_mysql_user.sql
    } catch {
        Write-Host "Failed to run mysql. Ensure 'mysql' client is in PATH and MySQL server is running." -ForegroundColor Red
        Write-Host "You can run: mysql -u root -p < .\database\create_mysql_user.sql" -ForegroundColor Yellow
        exit 1
    }
} else {
    Write-Host "Skipping DB creation — run the SQL helper manually when ready." -ForegroundColor Yellow
}

Write-Host "\n== Application setup ==" -ForegroundColor Cyan
Write-Host "Generating app key..."
php artisan key:generate

Write-Host "Running migrations and seeders..."
php artisan migrate --seed --force

Write-Host "\nSetup complete. You can start the server with: php artisan serve --host=127.0.0.1 --port=8000" -ForegroundColor Green
Write-Host "Visit: http://127.0.0.1:8000/api/health" -ForegroundColor Green
