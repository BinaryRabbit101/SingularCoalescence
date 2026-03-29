<#
.SYNOPSIS
    Starts the Little Pocket Museum in production mode.
.DESCRIPTION
    This script prepares the environment by installing dependencies,
    running migrations, building assets, and optimizing the Laravel cache.
    It then starts the web server and queue worker.
#>

$ErrorActionPreference = "Stop"

# Helper function to run external commands safely
function Invoke-ExternalCommand {
    param([string]$Command, [string[]]$ArgumentList)
    $oldPreference = $ErrorActionPreference
    $ErrorActionPreference = "Continue"
    try {
        & $Command @ArgumentList 2>&1 | ForEach-Object {
            if ($_ -is [System.Management.Automation.ErrorRecord]) {
                # External commands writing to stderr are wrapped in ErrorRecords by 2>&1
                # We print them as normal text to avoid triggering a script stop
                Write-Host $_.ToString() -ForegroundColor Gray
            } else {
                Write-Output $_
            }
        }
        if ($LASTEXITCODE -ne 0) {
            throw "$Command failed with exit code $LASTEXITCODE"
        }
    }
    finally {
        $ErrorActionPreference = $oldPreference
    }
}

try {
    Write-Host "===============================================" -ForegroundColor Cyan
    Write-Host "   Little Pocket Museum - Production Startup   " -ForegroundColor Cyan
    Write-Host "===============================================" -ForegroundColor Cyan

    # Ensure we are in the right directory
    $RootDir = $PSScriptRoot
    Set-Location $RootDir

    # 0. Prerequisites Check
    Write-Host "[+] Checking prerequisites..." -ForegroundColor Gray
    $RequiredTools = @("php", "composer", "npm")
    foreach ($Tool in $RequiredTools) {
        if (-not (Get-Command $Tool -ErrorAction SilentlyContinue)) {
            Write-Host "[!] Error: '$Tool' is not installed or not in your PATH." -ForegroundColor Red
            if ($Tool -eq "npm") {
                Write-Host "    Note: The 'php.new' installer sets up PHP and Composer, but NOT Node.js." -ForegroundColor Yellow
                Write-Host "    Please install Node.js (LTS) from https://nodejs.org/ to get 'npm'." -ForegroundColor Yellow
            }
            exit 1
        }
    }

    # 1. Environment Check
    if (-not (Test-Path ".env")) {
        Write-Host "[!] .env file missing. Copying from .env.example..." -ForegroundColor Yellow
        Copy-Item ".env.production" ".env"
        php artisan key:generate
    }

    # 2. Composer Dependencies
    Write-Host "[+] Installing Composer dependencies (no-dev)..." -ForegroundColor Green
    Invoke-ExternalCommand -Command "composer" -ArgumentList "install", "--no-dev", "--optimize-autoloader", "--no-interaction"

    # 3. NPM Dependencies & Build
    # Note: We need devDependencies to run 'npm run build' (Vite)
    Write-Host "[+] Installing NPM dependencies..." -ForegroundColor Green
    Invoke-ExternalCommand -Command "npm" -ArgumentList "install"

    Write-Host "[+] Generating Wayfinder routes..." -ForegroundColor Green
    Invoke-ExternalCommand -Command "php" -ArgumentList "artisan", "wayfinder:generate"

    Write-Host "[+] Building frontend assets..." -ForegroundColor Green
    Invoke-ExternalCommand -Command "npm" -ArgumentList "run", "build"

    # 4. Database
    Write-Host "[+] Running migrations..." -ForegroundColor Green
    Invoke-ExternalCommand -Command "php" -ArgumentList "artisan", "migrate", "--force", "--no-interaction"

    # 5. Optimization
    Write-Host "[+] Optimizing Laravel cache..." -ForegroundColor Green
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    # 6. Storage Link
    if (-not (Test-Path "public/storage")) {
        Write-Host "[+] Creating storage link..." -ForegroundColor Green
        php artisan storage:link
    }

    # 7. Launch
    Write-Host ""
    Write-Host "Starting services..." -ForegroundColor Cyan

    # Cleanup any existing processes from previous runs
    Write-Host "[+] Cleaning up any existing museum processes..." -ForegroundColor Gray
    $ExistingQueue = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*queue:work*" }
    if ($ExistingQueue) { $ExistingQueue | Stop-Process -Force }

    # Start Queue Worker in background using Start-Process for better control
    Write-Host "[>] Starting Queue Worker..." -ForegroundColor Gray
    $QueueProcess = Start-Process php -ArgumentList "artisan queue:work --tries=3 --backoff=3" -WindowStyle Hidden -PassThru

    Write-Host "[>] Starting Web Server on port 80..." -ForegroundColor Gray
    Write-Host "Application is now running!" -ForegroundColor Green
    Write-Host "Press Ctrl+C to terminate all processes." -ForegroundColor Yellow

    try {
        # Using artisan serve for simplicity in this script.
        # In high-traffic production, use IIS/Nginx + PHP-FPM.
        php artisan serve --port=80 --host=0.0.0.0
    }
    finally {
        Write-Host "`nStopping services..." -ForegroundColor Yellow

        # Kill the queue worker process
        if ($QueueProcess -and -not $QueueProcess.HasExited) {
            Stop-Process -Id $QueueProcess.Id -Force -ErrorAction SilentlyContinue
        }

        # Double check for any orphaned php processes spawned by 'serve'
        # We look for php processes that might be serving this specific directory
        $OrphanedServe = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object {
            $_.CommandLine -like "*artisan*serve*" -or $_.CommandLine -like "*-S 0.0.0.0:80*"
        }
        if ($OrphanedServe) { $OrphanedServe | Stop-Process -Force -ErrorAction SilentlyContinue }

        Write-Host "Done." -ForegroundColor Gray
    }
}
catch {
    Write-Host "[!] An error occurred: $_" -ForegroundColor Red
    exit 1
}
finally {
    Read-Host
}
