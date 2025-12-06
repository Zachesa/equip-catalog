# equip-catalog/Dockerfile
FROM php:8.4-fpm-bookworm

# Устанавливаем ВСЕ зависимости в ОДНОМ слое
RUN apt-get update && apt-get install -y \
    # Для mbstring
    libonig-dev \
    pkg-config \
    # Для GD
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libwebp-dev \
    # Для Nginx и Supervisor
    nginx \
    supervisor \
    && docker-php-ext-configure gd \
        --enable-gd \
        --with-jpeg \
        --with-freetype \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Настройка PHP-FPM
RUN sed -i 's/;clear_env = no/clear_env = no/' /usr/local/etc/php-fpm.d/www.conf

# Конфигурация
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Проект
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && composer install --no-dev --optimize-autoloader --no-interaction

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]