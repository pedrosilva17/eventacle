#!/bin/bash
if [ ! -f ".env" ]; then
  cp .env.example .env
fi
if [ ! -d "vendor" ]; then
  composer install
fi

if [ -n "$1" ]; then
  ./sail build $1;
else
  ./sail build;
fi

./sail artisan config:clear

if [ ! -f "database/database.sqlite" ]; then
  touch database/database.sqlite
  php artisan migrate --force
fi

npm ci && npm run build