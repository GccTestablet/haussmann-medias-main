FROM gccteam/haussmann-medias:0.1.0

COPY . /var/www/haussmann-medias

EXPOSE 80

# Configure
COPY docker/app/config/*.local /etc/nginx/sites-enabled/
COPY docker/app/config/.env.ci /var/www/haussmann-medias/app/.env
COPY docker/app/config/behat.yml /var/www/haussmann-medias/app/

COPY docker/app/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

WORKDIR /var/www/haussmann-medias/app

RUN \
    # Prepare public folders
    mkdir -p public/tmp public/media \
    # Prepare logs/caches
    mkdir -p var/log var/cache \
    && rm -rf var/cache/* \
    && chmod -R 777 var/log var/cache \
    && chown -R www-data:www-data *

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]