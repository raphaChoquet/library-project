FROM php:7.4-fpm

RUN DOCKER_USER_UID=1000;
RUN DOCKER_USER_GID=1000;

RUN apt-get -y update
RUN apt-get upgrade -y

RUN apt-get install -y vim git-all libzip-dev libpq-dev

RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install zip
COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD ["php-fpm", "-F"]
WORKDIR /var/www

EXPOSE 9000
