FROM rconstant/uzege-ortho:0.9.4

COPY . /var/www/haussmann-medias

EXPOSE 80

# Configure
COPY docker/app/config/www.haussmann-medias.local /etc/nginx/sites-enabled/
COPY docker/app/config/.env.ci /var/www/haussmann-medias/app/.env

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