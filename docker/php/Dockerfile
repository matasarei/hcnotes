# Minor-version tag on purpose: `make up` builds with --pull, so every patch
# release lands on rebuild. tests/Integration/RuntimeSecurityTest.php guards the
# PHP2Shell floor (8.4.27).
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    sqlite \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    intl \
    zip \
    mbstring \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP
COPY php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /var/www/html

# Create www-data writable dirs
RUN mkdir -p /var/www/html/data /var/www/html/content \
    && chown -R www-data:www-data /var/www/html

USER www-data
