#!/usr/bin/env bash

# Exit on any failure
set -e

# For phing
composer install --prefer-dist --no-interaction --no-scripts --no-progress
yarn install ---force

 # Necessary directories
mkdir -p public/media
mkdir -p public/tmp
mkdir -p var

# Permissions hack because setfacl does not work on Mac and Windows
chown -R www-data public/media
chown -R www-data public/tmp
chown -R www-data var

# SSH
echo -en '127.0.0.1 www.haussmann-medias.local' >> /etc/hosts

# NGINX
service nginx restart

yarn run dev


exec docker-php-entrypoint "$@"
