FROM php:8.2-apache

# Instalar solo lo esencial para que PHP hable con PostgreSQL y maneje archivos
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Configurar el DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código al servidor
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
# Usamos --no-scripts para evitar que intente correr artisan antes de tiempo
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Al iniciar: limpia caché, corre migraciones y arranca Apache
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground