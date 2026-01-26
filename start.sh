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
echo "=========================================="
echo "Starting FrankenPHP"
echo "PORT=${PORT:-8080}"
echo "Working directory: $(pwd)"
echo "Public directory exists: $([ -d public ] && echo 'YES' || echo 'NO')"
echo "=========================================="

# Verificar que Laravel puede iniciar sin errores
php artisan route:list --columns=uri,method > /dev/null 2>&1 && echo "Laravel routes OK" || echo "WARNING: Laravel routes check failed"

# Iniciar FrankenPHP
exec frankenphp run
