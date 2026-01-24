# Configuración para Railway - Sistema Clínica

## Variables de Entorno Requeridas en Railway

Configura estas variables en el panel de Railway (Settings > Variables):

### Variables Obligatorias

```
APP_NAME=Sistema Clínica
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://sistema-clinica-production-bee9.up.railway.app
APP_TIMEZONE=UTC
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
```

### Base de Datos (MySQL)

Railway proporciona una base de datos MySQL. Configura estas variables:

```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**Nota:** Railway usa variables de referencia como `${{MySQL.MYSQLHOST}}` para conectar servicios.

### Sesiones y Cache

```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Mail (Opcional - para producción)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sistema-clinica.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Pasos para Desplegar

1. **Conecta tu repositorio a Railway**
   - Ve a tu proyecto en Railway
   - Conecta tu repositorio de GitHub/GitLab

2. **Agrega una base de datos MySQL**
   - En Railway, ve a "New" > "Database" > "Add MySQL"
   - Railway creará automáticamente las variables de entorno para la base de datos

3. **Configura las variables de entorno**
   - Ve a Settings > Variables
   - Agrega todas las variables listadas arriba
   - **IMPORTANTE:** Para `APP_KEY`, ejecuta localmente: `php artisan key:generate --show` y copia el valor

4. **Configura el Build Command**
   - En Settings > Build, asegúrate de que el comando de build sea:
   ```
   composer install --no-dev --optimize-autoloader && npm install && npm run build
   ```

5. **Configura el Start Command**
   - Railway detectará automáticamente FrankenPHP, pero puedes configurar:
   ```
   php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```

6. **Despliega**
   - Railway desplegará automáticamente cuando hagas push a tu repositorio
   - O haz clic en "Deploy" manualmente

## Solución de Problemas

### Error 502 Bad Gateway

1. **Verifica las variables de entorno:**
   - Asegúrate de que `APP_KEY` esté configurado
   - Verifica que `APP_URL` coincida con tu dominio de Railway
   - Confirma que todas las variables de base de datos estén correctas

2. **Verifica los logs:**
   - Ve a "Deployments" > "View Logs" en Railway
   - Busca errores de conexión a la base de datos o problemas de configuración

3. **Ejecuta migraciones:**
   - Si la base de datos está vacía, las migraciones deben ejecutarse automáticamente
   - Puedes ejecutarlas manualmente desde el terminal de Railway:
     ```
     php artisan migrate --force
     ```

4. **Verifica el puerto:**
   - Railway usa el puerto automáticamente, no necesitas configurar `PORT`
   - FrankenPHP se encarga de esto

5. **Limpia la caché:**
   - Ejecuta en el terminal de Railway:
     ```
     php artisan config:clear
     php artisan cache:clear
     php artisan route:clear
     php artisan view:clear
     ```

## Comandos Útiles en Railway

Accede al terminal de Railway (Deployments > View Logs > Terminal):

```bash
# Verificar configuración
php artisan config:show

# Ejecutar migraciones
php artisan migrate --force

# Limpiar caché
php artisan optimize:clear

# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Verificar conexión a base de datos
php artisan tinker
>>> DB::connection()->getPdo();
```

## Notas Importantes

- Railway usa FrankenPHP por defecto para aplicaciones PHP
- El archivo `frankenphp.json` ya está configurado correctamente
- Asegúrate de que `APP_DEBUG=false` en producción
- Las migraciones se ejecutan automáticamente si están en el build command
- Railway proporciona HTTPS automáticamente
