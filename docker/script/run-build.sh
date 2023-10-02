#!/bin/bash -xe

# Prepare builder image
docker build -t haussmann-medias-main-builder - < docker/app-builder/Dockerfile

# Run build.sh inside docker (with same UID as current user)
docker run \
  -v$(pwd):/var/www/haussmann-medias \
  -w /var/www/haussmann-medias/app \
  haussmann-medias-main-builder:latest \
  bash -c '
      ../build.sh
'
