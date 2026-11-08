# Usa la imagen oficial optimizada
FROM serversideup/php:8.4-fpm-nginx

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar todo tu proyecto y asignar permisos
COPY --chown=www-data:www-data . /var/www/html

# Instalar dependencias y destruir cualquier caché residual
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    rm -f bootstrap/cache/*.php