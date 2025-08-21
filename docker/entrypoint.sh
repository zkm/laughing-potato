#!/usr/bin/env bash
set -euo pipefail

# Ensure we are at /var/www/html
cd /var/www/html

# If vendor/autoload.php is missing, install dependencies
if [ ! -f vendor/autoload.php ]; then
  if [ -f composer.json ]; then
    echo "Installing PHP dependencies..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader
  else
    echo "composer.json not found; skipping composer install."
  fi
fi

# Hand over to Apache's default command
exec docker-php-entrypoint apache2-foreground
