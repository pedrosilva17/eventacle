#!/bin/bash
if [ ! -f ".env" ]; then
  cp .env.example .env
fi
if [ ! -d "vendor" ]; then
  composer install
fi

./sail build --no-cache

if [ ! -f "database/database.sqlite" ]; then
  touch database/database.sqlite
  php artisan migrate --force
fi

npm ci && npm run build