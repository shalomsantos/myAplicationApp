FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
	libpq-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli gd pgsql pdo_pgsql

RUN docker-php-ext-configure zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/user_application user_application
RUN mkdir -p /home/user_application/.composer && \
    chown -R user_application:user_application /home/user_application

RUN pecl --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable xdebug

USER user_application

EXPOSE 9000
