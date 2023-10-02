#!/bin/bash -xe

# Prepare builder image
docker build -t uzege-ortho-main-builder - < docker/app-builder/Dockerfile

# Run build.sh inside docker (with same UID as current user)
docker run \
  -v$(pwd):/var/www/uzege-ortho \
  -w /var/www/uzege-ortho/app \
  uzege-ortho-main-builder:latest \
  bash -c '
      ../build.sh
'
