#!/usr/bin/env sh

set -e

printf "\n\nPreparing app configs...\n\n"

composer install -n --prefer-dist

APP_KEY_LINE=$(grep "^APP_KEY=" ".env")

if echo "$APP_KEY_LINE" | grep -Eq '^APP_KEY=base64:[A-Za-z0-9+/]+={0,2}$'; then
    echo "APP_KEY already exists. Skipping..."
else
    echo "APP_KEY not found. Generating..."
    php artisan key:generate
fi

php artisan migrate
php artisan db:seed
php artisan storage:link

npm install && npm run build

printf "\n\nStarting PHP 8.2 daemon...\n\n"

exec "$@"
