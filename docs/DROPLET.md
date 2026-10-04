# The public copy on DigitalOcean

Singular Coalescence runs in **two places with two separate databases**. They never sync.

| | Mini-PC | Droplet |
|---|---|---|
| Address | `http://192.168.0.164:82`, `https://minipc.jackal-hippocampus.ts.net:445` | `https://singularcoalescence.thecorgisquad.com` |
| Who | the owner's private copy | the public |
| Data | the original | seeded 2026-10-04 from a copy of the mini-PC database and `storage/app/public` (admin login, diary entries, music, art); separate since then |
| Image generation | OpenRouter key set | **unconfigured** (no key, on purpose) |
| Deploy | `.\deploy.ps1` | `.\deploy.ps1 -Target droplet` |

Deploy both when a change should reach both. Each server keeps its own `.env`, SQLite
database (`database/database.sqlite`) and `storage/app` (character art, mp3s, novel
cover); the deploy never touches them. Neither server has a `.git`: `deploy.ps1` builds
locally and ships a tar, then runs composer, migrations, `StorySeeder` and `optimize`.
Never run the plain `db:seed` on a server (DatabaseSeeder creates a default admin).
tar never deletes, so a file removed from the repo stays on the servers until removed by hand.

## Droplet facts

- **squad-web-1**, shared with Kitchen: DigitalOcean NYC3, 1 GB, Ubuntu 26.04,
  `157.245.116.253`. SSH as `deploy@` only (passwordless sudo).
- App at `/var/www/singularcoalescence`, nginx vhost
  `/etc/nginx/sites-available/singularcoalescence`.
- PHP 8.5-FPM in **its own pool** (`/etc/php/8.5/fpm/pool.d/singularcoalescence.conf`,
  socket `/run/php/php8.5-fpm-singularcoalescence.sock`, ondemand, max 3 workers,
  uploads up to 20 MB / post 24 MB; nginx `client_max_body_size 25m`). Kitchen's `www`
  pool and the global `php.ini` (10 MB uploads) are untouched.
- `/storage/*` files (art, mp3s) are served by nginx directly, so HTTP Range (206) works
  for the music player; `/stream/*` (Laravel) supports Range too.
- **`/register` is blocked at nginx** (404, including `/index.php/register`). Registration
  was still open in the app code when the copy went public; the block stays even after the
  code removes it.
- TLS: Let's Encrypt via certbot (auto-renew timer), cert
  `/etc/letsencrypt/live/singularcoalescence.thecorgisquad.com/`. Issued with the DNS
  records grey-clouded, then proxied. Cloudflare zone SSL is **Full (strict)**; nginx
  restores the visitor IP from `CF-Connecting-IP` (`/etc/nginx/conf.d/cloudflare-realip.conf`).
- DNS: proxied A + AAAA `singularcoalescence.thecorgisquad.com`. The root
  `thecorgisquad.com` stays the Corgi Squad landing page; never redirect it here.
- `.env`: production, `APP_DEBUG=false`, its **own `APP_KEY`** generated on the droplet
  (not the mini-PC's; no user had 2FA, so nothing depended on the old key), sqlite,
  database sessions/cache/queue, secure cookies, `MAIL_MAILER=log`, daily logs at warning.
- No queue worker and no scheduler cron (the app needs neither yet).
- **Not backed up yet.** Lifeboat on the droplet covers only Kitchen.
