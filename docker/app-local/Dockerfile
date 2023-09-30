FROM rconstant/uzege-ortho:0.9.4

RUN rm -rf /var/www/haussmann-medias/app/var/*

COPY . /var/www/haussmann-medias
WORKDIR /var/www/haussmann-medias/app

RUN \
    # Copy configuration
    cp ../docker/app/config/www.haussmann-medias.local /etc/nginx/sites-enabled/

EXPOSE 80
EXPOSE 443

COPY docker/app-local/entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]