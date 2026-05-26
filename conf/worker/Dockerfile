FROM php:8.1-cli-alpine

ARG user=user_application
ARG uid=1000

RUN apk update && apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    rabbitmq-c-dev \
    supervisor \
    $PHPIZE_DEPS

RUN pecl install amqp && docker-php-ext-enable amqp

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql sockets pcntl zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./aplication/composer.json ./

RUN composer require vladimir-yuldashev/laravel-queue-rabbitmq --no-interaction --no-scripts

RUN adduser -D -G www-data -u $uid $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:www-data /home/$user

RUN mkdir -p /etc/supervisor/conf.d
COPY ./conf/worker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/laravel-worker.conf"]