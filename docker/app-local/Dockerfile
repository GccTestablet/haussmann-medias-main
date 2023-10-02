FROM rconstant/uzege-ortho:0.9.4

RUN rm -rf /var/www/haussmann-medias/app/var/*

COPY . /var/www/haussmann-medias
WORKDIR /var/www/haussmann-medias/app

RUN \
    # Copy configuration
    cp ../docker/app/config/www.haussmann-medias.local /etc/nginx/sites-enabled/

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