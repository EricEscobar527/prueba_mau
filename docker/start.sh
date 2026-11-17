#!/bin/sh
set -e

[ -f .env ] || cp .env.example .env

composer install
npm install
npm run build

grep -q '^APP_KEY=$' .env && php artisan key:generate

php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=8000
