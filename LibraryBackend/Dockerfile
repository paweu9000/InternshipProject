# Use official PHP image with Symfony support
FROM php:8.2-fpm

# Install system dependencies and PostgreSQL development libraries
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    libpq-dev \ 
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/symfony-app
WORKDIR /var/www/symfony-app

# Copy the Symfony app files into the container
COPY . .

# Install Symfony dependencies
RUN composer install

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
