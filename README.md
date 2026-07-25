# SSD — Statistics/Status Dashboard

A personal, self-hosted dashboard that shows live personal stats and statuses on a
single page: what's playing on Spotify, Steam activity, uptime of monitored sites,
tech news, and weather radar. Built in plain PHP (no framework), styled with SCSS,
and served with PHP-FPM behind Caddy in Docker.

## Widgets

| Integration | Widgets |
|---|---|
| **Spotify** | Now-playing player |
| **Steam** | Profile status, playtime last 2 weeks, top 10 played games (personal, global, and per-platform: Windows/Mac/Linux) |
| **UptimeRobot** | Monitor overview |
| **Tweakers** | News feed |
| **Buienradar** | Rain radar (city and country) |

Widgets are server-rendered PHP partials. A widget with a `refresh-rate` attribute is
automatically re-fetched in the browser on that interval via the AJAX widget endpoint,
so the dashboard stays live without page reloads.

## Pages

| URL | Purpose |
|---|---|
| `/` or `/dashboard` | The main dashboard |
| `/widgets` | Overview page showing every available widget |
| `/spotify-auth` | (Re)authorize the Spotify integration via OAuth |

Routing is handled by Caddy ([docker/caddy/Caddyfile](docker/caddy/Caddyfile)): `/name`
maps to `views/name.php`, and static assets are served from `public/`. The
[.htaccess](.htaccess) file is a deprecated Apache equivalent.

### Access control

There is no login system. Every page requires a secret URL query parameter and shows
`Unauthorized!` without it:

```
https://<host>/dashboard?<URL_SECRET_KEY>=<URL_SECRET_VALUE>
```

Both the key and the value are defined in `config.php`.

## Requirements

- Docker (the container ships PHP 8.5-FPM, Composer, Node.js, and npm)
- A separately running Caddy instance attached to the external `docker_server_caddy`
  Docker network — [docker-compose.yml](docker-compose.yml) joins that network, and
  [docker/caddy/Caddyfile](docker/caddy/Caddyfile) is the site config for that Caddy
  instance (serves `ssd.local` locally)

## Getting started

1. Create your config from the example and fill it in:

   ```bash
   cp config-example.php config.php
   ```

2. Start the container and install everything (Composer + npm dependencies and a
   production asset build):

   ```bash
   ./develop up -d --build
   ./develop checkout
   ```

3. Open `https://ssd.local/?<URL_SECRET_KEY>=<URL_SECRET_VALUE>` in your browser.

On Windows, run the same commands from Git Bash; the [develop.cmd](develop.cmd) shim
forwards `develop ...` calls to it.

## Configuration

All configuration lives in `config.php` (gitignored; see
[config-example.php](config-example.php)):

| Constant | Purpose |
|---|---|
| `BASE_URL` | Base URL of the dashboard on this machine, no trailing slash |
| `URL_SECRET_KEY` / `URL_SECRET_VALUE` | Query-parameter name and value that grant access |
| `API_SPOTIFY_CLIENT_ID` / `API_SPOTIFY_CLIENT_SECRET` | Spotify app credentials |
| `API_KEY_STEAM` / `API_STEAM_ID` | Steam Web API key and the Steam ID to display |
| `API_KEY_UPTIME_ROBOT_READ_ONLY` | UptimeRobot read-only API key |

### Spotify authorization

Spotify needs a one-time OAuth consent on top of the client credentials. Visit
`/spotify-auth?<URL_SECRET_KEY>=<URL_SECRET_VALUE>`, approve access, and you'll be
redirected back automatically. The resulting refresh token is stored in the gitignored
`config/spotify-tokens.json`. If the integration ever loses access, visit the same URL
again to re-authorize.

## Development

The [develop](develop) script wraps `docker compose` and runs dev commands inside the
`php_ssd` container:

```
./develop checkout          # composer + npm install and asset build
./develop update            # composer update + npm update
./develop cqa               # quality gate: normalize + validate + rector + cs-fix + phpstan + phpunit
./develop composer <args>   # any composer command in the container
./develop npm <args>        # any npm command, e.g. ./develop npm run watch
./develop <anything else>   # passed through to docker compose (up, down, logs, ps, ...)
```

### Assets

Frontend assets are built with Vite ([vite.config.js](vite.config.js)):

- `scss/style.scss` compiles to `public/css/style.css`
- `static/` (fonts, site JS) is copied into `public/`
- jQuery is npm-managed and copied from `node_modules` into `public/js/`

`public/` is the generated webroot and is gitignored. Use
`./develop npm run watch` to rebuild on change during development.

### Code structure

```
integrations/   PSR-4 API clients per integration (SSD\Integrations\*)
php/            Shared bootstrap: session/auth and helper functions
views/          Pages, layout elements, and widget partials
scss/, static/  Asset sources (compiled/copied into public/ by Vite)
docker/         PHP-FPM Dockerfile and Caddy site config
tests/          PHPUnit tests (SSD\Test)
```

### Quality tooling & CI

`./develop cqa` runs the full quality gate. The individual Composer scripts are
`cs-check`/`cs-fix` (PHP-CS-Fixer), `phpstan`, `phpunit`, and
`rector-check`/`rector-fix`. GitHub Actions runs the same checks plus the Vite asset
build on every pull request to `master`.

## Deployment

On the server, run [./deploy](deploy) from the project directory. It hard-resets the
working tree to `origin/master`, rebuilds the Docker image from scratch, restarts the
containers, installs production dependencies (`composer install --no-dev`), and builds
the assets.

## License

[MIT](LICENSE)
