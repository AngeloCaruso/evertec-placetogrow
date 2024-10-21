FROM php:8.2-fpm-alpine

# Arguments defined in docker-compose.yml
ARG user
ARG uid

RUN docker-php-ext-install pdo pdo_mysql

RUN apk update && apk add --no-cache supervisor shadow wget

RUN mkdir -p "/etc/supervisor/logs"

COPY ./supervisord.conf /etc/supervisor/supervisord.conf

RUN echo "* * * * * cd /var/www/html && php artisan schedule:run >> /var/www/html/storage/logs/schedule.log 2>&1" >> /etc/crontabs/root

COPY ./crontab.entrypoint.sh /usr/local/bin/crontab.entrypoint.sh

RUN chmod +x /usr/local/bin/crontab.entrypoint.sh

RUN useradd -G www-data,root -u $uid -d /home/$user $user

ENTRYPOINT ["/usr/local/bin/crontab.entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]