#!/bin/sh

set -e

# Функция ожидания готовности сервиса
wait_for_service() {
  host=$1
  port=$2
  echo "Ожидание готовности $host:$port..."
  while ! nc -z $host $port; do
    sleep 1
  done
  echo "$host:$port готов."
}

# Ожидание основной базы данных
wait_for_service db 3306

# Установка зависимостей Composer
if [ -f composer.lock ]; then
  echo "Установка зависимостей Composer из composer.lock..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "composer.lock не найден. Установка зависимостей Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "Копирование .env.example в .env..."
cp .env.example .env

echo "Генерация ключа приложения..."
php artisan key:generate

echo "Установка прав доступа для директорий..."
chmod 777 /var/www/storage
chmod 777 /var/www/storage/logs
chmod 777 /var/www/storage/framework/views/
chmod 777 /var/www/bootstrap/cache

# Выполнение миграций основной базы данных
echo "Выполнение миграций основной базы данных..."
php artisan migrate --force

# Создание символических ссылок для хранилища
echo "Создание символических ссылок для хранилища..."
php artisan storage:link

# Запуск основного процесса
echo "Запуск основного процесса..."
exec "$@"
