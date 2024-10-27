FROM php:8.3-fpm

# Установка системных зависимостей и PHP расширений
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    netcat-traditional \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www

# Копирование остальных файлов приложения
COPY . .

# Обеспечение наличия скрипта entrypoint и установка прав
RUN chmod +x /var/www/docker-entrypoint.sh

# Точка входа и команда
ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
CMD ["php-fpm"]
