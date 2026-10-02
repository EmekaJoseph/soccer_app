# Soccer App

A football tournament manager. Owners create **cup** (groups + knock-outs) or **league** tournaments, add teams and players,
schedule fixtures, record results (with penalty shoot-outs) and score matches **live**. Every tournament gets a public stats
page with fixtures, live scores, results, standings and team ratings, where fans can also predict the top three and send feedback.
Owners can add **scorers** (sub-users) who may only schedule matches, record results and run live scores.

| Part | Stack |
| --- | --- |
| `___backend` | Laravel 13 API (PHP 8.3+), Sanctum tokens, MySQL, Pusher broadcasting, Intervention Image 3 |
| `___frontend` | Vue 3.5 + TypeScript SPA, Vite 8, Pinia, Vue Router, Bootstrap 5, laravel-echo + pusher-js |

## Run locally

```bash
# API
cd ___backend
cp .env.example .env          # fill in DB + Pusher + mail settings
composer install
php artisan key:generate
php artisan migrate --seed    # seed = demo@soccer.test / password (owner), scorer@soccer.test / password
php artisan serve             # http://127.0.0.1:8000

# SPA
cd ___frontend
cp .env.example .env          # VITE_API_URL=http://127.0.0.1:8000
npm install
npm run dev                   # http://127.0.0.1:8383
```

## Tests

```bash
cd ___backend && php artisan test     # feature tests (SQLite in memory)
cd ___frontend && npm test            # unit tests (Vitest)
cd ___frontend && npm run build       # type-check + production build
```

## Deploying over an existing database

Production databases were created from an SQL dump, so their `migrations` table is incomplete. The create-table migrations
skip tables that already exist, so `php artisan migrate --force` is safe: it only adds what is missing (`tbl_players`,
`password_reset_tokens`, `tbl_live.match_id`) and fixes the `tbl_results.match_id` collation.

`.env` variable names changed with the framework upgrade; the old ones still work, but prefer
`BROADCAST_CONNECTION` (was `BROADCAST_DRIVER`) and `CACHE_STORE` (was `CACHE_DRIVER`), and add `FRONTEND_URL`.
