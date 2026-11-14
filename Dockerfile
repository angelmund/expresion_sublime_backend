# Usa la imagen oficial optimizada
FROM serversideup/php:8.4-fpm-nginx

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar todo tu proyecto
COPY --chown=www-data:www-data . /var/www/html

# 1. Instalar dependencias de producción
# 2. Limpiar cachés viejas
# 3. Crear enlace simbólico para storage (VITAL para imágenes/archivos)
# 4. Dar permisos correctos a storage y bootstrap/cache
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    rm -f bootstrap/cache/*.php && \
    php artisan storage:link && \
    chmod -R 775 storage bootstrap/cache

# (Opcional) Si usas Vite en el frontend dentro de Laravel, descomenta:
# RUN npm ci && npm run build

# Serversideup por defecto corre en el puerto 8080
EXPOSE 8080