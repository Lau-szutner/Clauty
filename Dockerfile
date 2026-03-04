FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones de PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# 2. Habilitar mod_rewrite (necesario para las rutas de Laravel)
RUN a2enmod rewrite

# 3. Configurar el DocumentRoot para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar el código al contenedor
WORKDIR /var/www/html
COPY . .

# 6. Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. Permisos correctos para storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponer el puerto 80
EXPOSE 80

# 9. COMANDO DE INICIO CRÍTICO:
# Usamos 'migrate:fresh --seed' para limpiar la DB y cargar tus datos.
# 'apache2-foreground' es necesario para que el contenedor no se apague en Render.
CMD php artisan config:clear && php artisan migrate:fresh --force --seed && apache2-foreground