#!/usr/bin/env bash
# Salir si hay un error
set -o errexit

composer install --no-dev --optimize-autoloader

# Instalar dependencias de JS
npm install
npm run build

# --- ESTA LÍNEA ES CLAVE ---
chmod -R 775 storage bootstrap/cache
# ---------------------------

# Limpiar y recrear cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Correr migraciones automáticamente
php artisan migrate --force