FROM kinglok/php:8.2-apache

ENV PHP_UPLOAD_MAX_FILESIZE=20M \
    PHP_POST_MAX_SIZE=100M \
    PHP_MEMORY_LIMIT=256M \
    NODE_MAJOR=20

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

RUN apt-get update -y && apt-get upgrade -y \
    && apt-get install -y ca-certificates curl gnupg \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update -y \
    && apt-get install -y supervisor nodejs \
    && sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/g' ${APACHE_CONFDIR}/sites-enabled/000-default.conf \
    && touch storage/logs/laravel.log \
    && chown -R www-data:www-data storage/logs/laravel.log \
    && chmod -R 775 . \
    && echo "upload_max_filesize=\${PHP_UPLOAD_MAX_FILESIZE}" >> ${PHP_INI_DIR}/conf.d/php.ini \
    && echo "post_max_size=\${PHP_POST_MAX_SIZE}" >> ${PHP_INI_DIR}/conf.d/php.ini \
    && echo "memory_limit=\${PHP_MEMORY_LIMIT}" >> ${PHP_INI_DIR}/conf.d/php.ini \
    && echo "expose_php=off" >> ${PHP_INI_DIR}/conf.d/php.ini \
    && find . -type f -exec chmod -R 664 {} + \
    && service apache2 restart \
    && rm -rf docker

COPY ./docker/supervisord/conf.d/. /etc/supervisor/conf.d/
COPY ./docker/init.sh /root/init.sh

CMD ["sh", "/root/init.sh"]

EXPOSE 80
