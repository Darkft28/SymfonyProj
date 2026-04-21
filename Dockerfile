FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/symfony/var

EXPOSE 9000
