#!/bin/sh
set -e

# Demo: SQLite di filesystem container (ephemeral), jadi dibuat ulang + di-seed tiap start.
touch database/database.sqlite
php artisan migrate:fresh --seed --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
