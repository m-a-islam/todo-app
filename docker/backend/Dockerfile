# Start from an official PHP-FPM image. FPM is designed to work with Nginx.
FROM php:8.2-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/symfony

# Install system dependencies needed for Symfony and common extensions
# git, zip/unzip are for Composer. icu is for the Intl PHP extension.
RUN apk add --no-cache git icu-dev icu-data-full zip unzip

# Install PHP extensions that Symfony commonly needs
# pdo_mysql for database access, intl for internationalization, opcache for performance
RUN docker-php-ext-install pdo_mysql intl opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get the latest version of Composer, the PHP package manager
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# This is a Docker caching trick! By copying only the dependency files first,
# Docker won't need to re-run "composer install" every time you change a .php file.
COPY backend/composer.json backend/composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-autoloader --no-progress

# Now copy the rest of your Symfony application code
COPY backend/ .

# Re-run composer to generate autoloader and run scripts
RUN composer dump-autoload --optimize && \
    composer run-script post-install-cmd

# Set correct permissions. The user 'www-data' is the user Nginx/PHP-FPM runs as.
RUN chown -R www-data:www-data var

# The base php-fpm image already has the right CMD to start the service.
# We just need to expose the port it listens on.
EXPOSE 9000