#!/bin/sh
php /var/www/html/artisan migrate
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
/usr/sbin/apachectl -D FOREGROUND