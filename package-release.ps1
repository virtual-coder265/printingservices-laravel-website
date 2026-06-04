param(
    [string]$OutputDir = "dist",
    [string]$ReleaseName = (Get-Date -Format "yyyyMMdd-HHmmss"),
    [switch]$IncludeVendor
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$outputRoot = Join-Path $root $OutputDir
$stagingRoot = Join-Path $outputRoot "staging-$ReleaseName"
$zipPath = Join-Path $outputRoot "printingservices-deploy-$ReleaseName.zip"

function Copy-ReleaseItem {
    param([string]$RelativePath)

    $source = Join-Path $root $RelativePath
    if (-not (Test-Path -LiteralPath $source)) {
        return
    }

    $destination = Join-Path $stagingRoot $RelativePath
    $destinationParent = Split-Path -Parent $destination

    if ($destinationParent) {
        New-Item -ItemType Directory -Force -Path $destinationParent | Out-Null
    }

    Copy-Item -LiteralPath $source -Destination $destination -Recurse -Force
}

function Remove-IfExists {
    param([string]$RelativePath)

    $target = Join-Path $stagingRoot $RelativePath
    if (Test-Path -LiteralPath $target) {
        Remove-Item -LiteralPath $target -Recurse -Force
    }
}

Set-Location $root

if (-not (Get-Command npm.cmd -ErrorAction SilentlyContinue)) {
    throw "npm.cmd was not found. Build the frontend on a machine with Node.js installed."
}

Write-Host "Building frontend assets..."
& npm.cmd run build

if (-not (Test-Path -LiteralPath (Join-Path $root "public/build/manifest.json"))) {
    throw "Vite build did not produce public/build/manifest.json."
}

New-Item -ItemType Directory -Force -Path $outputRoot | Out-Null

if (Test-Path -LiteralPath $stagingRoot) {
    Remove-Item -LiteralPath $stagingRoot -Recurse -Force
}

if (Test-Path -LiteralPath $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

New-Item -ItemType Directory -Force -Path $stagingRoot | Out-Null

$releaseItems = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "storage",
    ".editorconfig",
    ".env.example",
    ".gitattributes",
    ".gitignore",
    ".htaccess",
    "artisan",
    "composer.json",
    "composer.lock",
    "index.php",
    "package-lock.json",
    "package.json",
    "phpunit.xml",
    "postcss.config.js",
    "README.md",
    "tailwind.config.js",
    "vite.config.js"
)

if ($IncludeVendor -and (Test-Path -LiteralPath (Join-Path $root "vendor"))) {
    $releaseItems += "vendor"
}

foreach ($item in $releaseItems) {
    Copy-ReleaseItem -RelativePath $item
}

# Remove machine-specific caches and writable runtime artifacts from the package.
Remove-IfExists -RelativePath "bootstrap/cache/config.php"
Remove-IfExists -RelativePath "bootstrap/cache/packages.php"
Remove-IfExists -RelativePath "bootstrap/cache/services.php"
Remove-IfExists -RelativePath "storage/logs"
Remove-IfExists -RelativePath "storage/framework/cache"
Remove-IfExists -RelativePath "storage/framework/sessions"
Remove-IfExists -RelativePath "storage/framework/views"
Remove-IfExists -RelativePath "public/storage"

New-Item -ItemType Directory -Force -Path (Join-Path $stagingRoot "storage/logs") | Out-Null
New-Item -ItemType Directory -Force -Path (Join-Path $stagingRoot "storage/framework/cache/data") | Out-Null
New-Item -ItemType Directory -Force -Path (Join-Path $stagingRoot "storage/framework/sessions") | Out-Null
New-Item -ItemType Directory -Force -Path (Join-Path $stagingRoot "storage/framework/views") | Out-Null
New-Item -ItemType Directory -Force -Path (Join-Path $stagingRoot "bootstrap/cache") | Out-Null

Write-Host "Creating archive $zipPath ..."
Compress-Archive -Path (Join-Path $stagingRoot "*") -DestinationPath $zipPath -Force

Remove-Item -LiteralPath $stagingRoot -Recurse -Force

Write-Host "Release package created:"
Write-Host $zipPath
