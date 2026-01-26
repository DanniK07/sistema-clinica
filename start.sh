#!/bin/bash
set -e

# Crear directorios necesarios si no existen
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Establecer permisos
chmod -R 775 storage bootstrap/cache || true

# Limpiar caché de configuración
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Optimizar para producción
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Iniciar FrankenPHP
# Railway proporciona la variable PORT automáticamente
# FrankenPHP/Caddy detecta automáticamente PORT
# Simplemente ejecutamos frankenphp run - Railway maneja el puerto
echo "Starting FrankenPHP (Railway will handle PORT automatically)"
exec frankenphp run
