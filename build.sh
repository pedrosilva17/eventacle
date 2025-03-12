#!/bin/bash

if [ ! -f ".env" ]; then
  cp .env.example .env
fi

if [ ! -d "vendor" ]; then
  composer install
fi

if [ -n "$1" ]; then
  ./sail build "$1"
else
  ./sail build
fi

if [ $? -eq 0 ]; then

  if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    php artisan migrate --force
    php artisan db:seed --force
  fi

  npm ci && npm run build
else
  echo "Error during sail build"
  exit 1
fi