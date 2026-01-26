# 🔧 Solución: Connection Dial Timeout (502)

## ❌ El Problema

El error `"connection dial timeout"` significa que:
- El contenedor se está iniciando pero **no responde**
- El servidor FrankenPHP **no está arrancando correctamente**
- Hay un **error fatal** que impide que la aplicación inicie

---

## 🔍 Paso 1: Revisar Logs del Contenedor (CRÍTICO)

Los logs de "construcción" y "despliegue" solo muestran el build. Necesitas ver los logs del contenedor **en ejecución**:

### Opción A: Logs en Tiempo Real

1. En Railway, ve a tu servicio **"sistema-clinicas"**
2. Haz clic en la pestaña **"Logs"** (en la parte superior, NO "Deployments")
3. O ve a **"Metrics"** > **"Logs"**
4. Estos logs muestran lo que está pasando **ahora mismo** con tu aplicación

**Busca específicamente:**
- Errores de PHP (Fatal error, Parse error, Class not found)
- Errores relacionados con `APP_KEY`
- Errores de conexión a base de datos
- Mensajes que indiquen que el servidor está iniciando
- Cualquier línea en rojo o con "ERROR", "FATAL", "Exception"

### Opción B: Terminal del Contenedor

1. Ve a **"Deployments"** > Selecciona el último deployment
2. Haz clic en **"View Logs"** > **"Terminal"**
3. Ejecuta estos comandos uno por uno:

```bash
# Verificar que APP_KEY esté configurado
php artisan config:show | grep APP_KEY

# Ver logs de Laravel
cat storage/logs/laravel.log | tail -50

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/

# Intentar iniciar manualmente para ver errores
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🔑 Paso 2: Verificar Variables de Entorno (MUY IMPORTANTE)

El problema más común es que **falta `APP_KEY`** o está mal configurado.

Ve a Railway > Tu Servicio > **"Variables"** y verifica que estas variables estén configuradas **EXACTAMENTE** así:

### Variables OBLIGATORIAS:

```
APP_NAME=Sistema Clínica
APP_ENV=production
APP_KEY=base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=
APP_DEBUG=false
APP_URL=https://sistema-clinica-production-bee9.up.railway.app
```

**⚠️ CRÍTICO:** 
- `APP_KEY` **DEBE** empezar con `base64:`
- **NO** debe tener espacios antes o después
- **NO** debe estar vacío

### Variables de Base de Datos:

```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

### Variables de Sesiones:

```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

---

## 🛠️ Paso 3: Habilitar Debug Temporalmente

Para ver errores específicos, temporalmente cambia en Railway Variables:

```
APP_DEBUG=true
```

**⚠️ IMPORTANTE:** Solo haz esto temporalmente para diagnosticar. Después vuelve a ponerlo en `false`.

Esto mostrará errores detallados en lugar del 502 genérico.

---

## 🔧 Paso 4: Verificar Permisos de Storage

Laravel necesita permisos de escritura. En Railway Terminal, ejecuta:

```bash
chmod -R 775 storage bootstrap/cache
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod 664 storage/logs/laravel.log
```

---

## 🚨 Errores Comunes y Soluciones

### Error: "No application encryption key has been specified"

**Solución:**
1. Ve a Railway Variables
2. Verifica que `APP_KEY` esté configurado
3. El valor debe empezar con `base64:`
4. Si está vacío, agrega: `base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=`

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solución:**
- Verifica que MySQL esté corriendo en Railway
- Verifica que las variables de base de datos usen `${{MySQL.MYSQLHOST}}` (con las llaves)
- Asegúrate de que MySQL esté conectado a tu servicio

### Error: "The stream or file could not be opened"

**Solución:**
```bash
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache
```

### Error: "Class not found" o "Call to undefined function"

**Solución:**
```bash
composer dump-autoload
php artisan optimize:clear
php artisan config:cache
```

---

## 📋 Checklist de Diagnóstico

Marca cada uno después de verificar:

- [ ] Revisé los logs del contenedor en ejecución (pestaña "Logs")
- [ ] `APP_KEY` está configurado y empieza con `base64:`
- [ ] `APP_URL` coincide con el dominio de Railway
- [ ] Todas las variables de base de datos están configuradas
- [ ] Habilité `APP_DEBUG=true` temporalmente para ver errores
- [ ] Verifiqué permisos en `storage/` y `bootstrap/cache/`
- [ ] Revisé `storage/logs/laravel.log` para errores específicos

---

## 🆘 Acción Inmediata

**Por favor, haz esto AHORA:**

1. **Ve a Railway > Tu Servicio > "Logs"** (NO "Deployments")
2. **Copia las últimas 50-100 líneas** de los logs
3. **Compártelas conmigo** - especialmente cualquier línea con "ERROR", "FATAL", "Exception", o que mencione `APP_KEY`

4. **Verifica en Railway Variables** que `APP_KEY` esté configurado con el valor:
   ```
   base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=
   ```

5. **Temporalmente cambia** `APP_DEBUG=true` en Railway Variables para ver errores detallados

Con esa información podré identificar exactamente qué está impidiendo que la aplicación inicie.
