# Usa la imagen oficial optimizada que incluye PHP 8.4 y Nginx
FROM serversideup/php:8.4-fpm-nginx

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar todo tu proyecto de Laravel y asignar los permisos correctos
COPY --chown=www-data:www-data . /var/www/html

# Instalar las dependencias de PHP para producción
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Optimizar la caché de Laravel para que sea más rápido
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache