FROM php:8.2-fpm

RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN pecl install redis
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update \
     && docker-php-ext-install mysqli pdo pdo_mysql pcntl \
     && docker-php-ext-enable pdo_mysql \
     && docker-php-ext-enable redis \
     && docker-php-ext-enable pcntl

WORKDIR /app/backend

COPY . .

EXPOSE 8000

CMD composer install && php artisan serve --host=0.0.0.0
