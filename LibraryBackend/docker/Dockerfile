# docker/Dockerfile
FROM php:8.2-fpm

RUN docker-php-ext-install opcache

# Add OPcache configuration
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    zip \
    libpq-dev \
    && docker-php-ext-install intl pdo_pgsql opcache zip # Replace pdo_mysql with pdo_pgsql

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install Symfony CLI (optional, for development)
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose the PHP service port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
