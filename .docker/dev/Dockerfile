FROM php:8.2.0-fpm-alpine AS php-dev

# install software
RUN apk add wget curl

# icu full && timezone
RUN apk add icu-data-full && apk add tzdata && cp /usr/share/zoneinfo/Europe/Berlin /etc/localtime && echo "Europe/Berlin" > /etc/timezone && apk del tzdata

# install php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && install-php-extensions opcache bcmath xdebug

# php.ini
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
COPY ./.docker/dev/php-ini-overrides.ini /usr/local/etc/php/conf.d/

CMD ["php-fpm"]
