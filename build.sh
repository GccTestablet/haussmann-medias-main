#!/bin/bash -xe

# Validate composer.json and composer.lock
composer validate

composer install -o --prefer-dist --no-interaction --no-scripts
yarn install --force

yarn run build