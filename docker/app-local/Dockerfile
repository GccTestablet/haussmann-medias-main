FROM gccteam/haussmann-medias:0.1.0

RUN rm -rf /var/www/haussmann-medias/app/var/*

COPY . /var/www/haussmann-medias
WORKDIR /var/www/haussmann-medias/app

# Chromium and ChromeDriver
ENV PANTHER_NO_SANDBOX 1
# Not mandatory, but recommended
#ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
ENV PANTHER_CHROME_ARGUMENTS="--disable-dev-shm-usage --disable-extensions --no-sandbox --whitelisted-ips=''"

RUN apt-get update \
    && apt-get install --no-install-recommends -y \
    chromium-driver

RUN \
    # Copy configuration
    cp ../docker/app/config/*.local /etc/nginx/sites-enabled/

RUN \
    # Prepare public folders
    mkdir -p public/tmp public/media \
    # Prepare logs/caches
    mkdir -p var/log var/cache \
    && rm -rf var/cache/* \
    && chmod -R 777 var/log var/cache

EXPOSE 80
EXPOSE 443

COPY docker/app-local/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]