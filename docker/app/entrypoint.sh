#!/usr/bin/env bash

# Exit on any failure
set -e

# NGINX
service nginx restart

exec docker-php-entrypoint "$@"
