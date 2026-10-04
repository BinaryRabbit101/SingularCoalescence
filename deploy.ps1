<#
.SYNOPSIS
  Deploys Singular Coalescence to the mini-PC (default) or the public DigitalOcean droplet.

.DESCRIPTION
  Neither server has a git checkout, so this is a push deploy over SSH (tar):
    1. `php artisan optimize:clear` then `npm run build` locally (Wayfinder generates
       resources/js/{routes,actions} during the build, so stale route caches must go first).
    2. tar the working tree (minus vendor, node_modules, .env*, the SQLite database,
       storage/app, logs, framework caches, .git, public/hot, public/storage) over ssh.
    3. On the box: composer install --no-dev, fix group/perms, migrate --force,
       storage:link if missing, StorySeeder (idempotent story content, never users), optimize.
    4. Smoke: GET / and GET /characters must be 200.
  Each server keeps its own .env (APP_KEY), database/database.sqlite and storage/app
  (character art, music, novel cover); the deploy never touches them. Never run the plain
  `db:seed` on a server: DatabaseSeeder creates a default admin.

  The two servers are separate copies with separate databases: the mini-PC is the owner's
  private copy (http://192.168.0.164:82), the droplet is the public one at
  https://singularcoalescence.thecorgisquad.com. Deploy both when a change should reach both.
  See docs/DROPLET.md.

  tar adds and overwrites files but never deletes: a file removed from the repo stays on
  the server until removed by hand.

.PARAMETER SkipBuild
  Reuse the existing public/build instead of rebuilding.

.PARAMETER Target
  minipc (default) or droplet.
#>
param([switch]$SkipBuild, [ValidateSet('minipc', 'droplet')][string]$Target = 'minipc')

$ErrorActionPreference = 'Stop'
$root = $PSScriptRoot
if ($Target -eq 'droplet') {
    $remote = 'deploy@157.245.116.253'
    $dest = '/var/www/singularcoalescence'
    $site = 'https://singularcoalescence.thecorgisquad.com'
    $live = $site
} else {
    # The mini-PC copy is not a git checkout (no .git on the box); this tar deploy replaces
    # the old scp-the-changed-files routine.
    $remote = 'gemini@192.168.0.164'
    $dest = '/home/gemini/websites/SingularCoalescence'
    $site = 'http://192.168.0.164:82'
    $live = "$site  (tailnet: https://minipc.jackal-hippocampus.ts.net:445)"
}

if (-not $SkipBuild) {
    Push-Location $root
    try {
        php artisan optimize:clear | Out-Null
        if ($LASTEXITCODE -ne 0) { throw 'php artisan optimize:clear failed' }
        npm run build
        if ($LASTEXITCODE -ne 0) { throw 'npm run build failed' }
    } finally { Pop-Location }
}
if (-not (Test-Path (Join-Path $root 'public\build\manifest.json'))) { throw 'public/build/manifest.json missing; run without -SkipBuild' }

# Git Bash wants /c/Users/... not C:\Users\...
$posixRoot = $root.Replace('\', '/')
if ($posixRoot -match '^([A-Za-z]):(.*)$') { $posixRoot = '/' + $Matches[1].ToLower() + $Matches[2] }

Write-Host "Uploading the app to ${remote}:${dest} ..."
# --anchored: every pattern matches from the start of the member name (./...), so e.g.
# ./vendor never catches some nested */vendor directory.
$bash = @"
set -e
cd '$posixRoot'
tar czf - --anchored \
  --exclude=./.git --exclude=./vendor --exclude=./node_modules --exclude='./.env*' \
  --exclude='./database/*.sqlite' --exclude='./database/*.sqlite-*' \
  --exclude='./storage/app/*' --exclude='./storage/logs/*' --exclude=./storage/pail \
  --exclude='./storage/framework/cache/data/*' --exclude='./storage/framework/sessions/*' \
  --exclude='./storage/framework/views/*' --exclude='./bootstrap/cache/*.php' --exclude=./bootstrap/ssr \
  --exclude=./public/hot --exclude=./public/storage \
  --exclude=./.phpunit.cache --exclude=./.phpunit.result.cache --exclude=./.claude --exclude=./.idea --exclude=./.vscode \
  . | ssh $remote 'mkdir -p $dest && tar xzf - --no-same-owner -C $dest'
ssh -n $remote 'set -eo pipefail; cd $dest \
  && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
  && composer install --no-dev --optimize-autoloader --no-interaction --quiet \
  && (chgrp -R www-data storage bootstrap/cache database 2>/dev/null; chmod -R ug+rwX storage bootstrap/cache database 2>/dev/null || true) \
  && php artisan migrate --force | tail -3 \
  && ([ -L public/storage ] || php artisan storage:link) \
  && php artisan db:seed --class=StorySeeder --force | tail -1 \
  && php artisan optimize | tail -1'
"@
# Git Bash may not be on PATH in every PowerShell host (scheduled tasks, tool shells).
$bashExe = (Get-Command bash -ErrorAction SilentlyContinue).Source
if (-not $bashExe) { $bashExe = 'C:\Program Files\Git\bin\bash.exe' }
$bash | & $bashExe -s
if ($LASTEXITCODE -ne 0) { throw 'deploy failed' }

Write-Host '--- smoke ---'
$failed = $false
foreach ($path in '/', '/characters') {
    $code = & curl.exe -s -o NUL -w '%{http_code}' "$site$path"
    Write-Host "$path $code"
    if ($code -ne '200') { $failed = $true }
}
if ($failed) { throw 'smoke test failed' }
Write-Host "`nLive: $live"
