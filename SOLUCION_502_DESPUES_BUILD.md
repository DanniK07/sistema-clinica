# 🔧 Solución: Error 502 Después de Build Exitoso

## ✅ Lo que Funciona
- ✅ Build completado exitosamente
- ✅ Migraciones ejecutadas correctamente
- ✅ Dependencias instaladas

## ❌ El Problema
La aplicación no responde (Error 502), lo que significa que el servidor FrankenPHP no puede iniciar o hay un error fatal.

---

## 🔍 Paso 1: Revisar Logs del Deployment

**Esto es CRÍTICO** - Los logs te dirán exactamente qué está fallando:

1. En Railway, ve a **"Deployments"**
2. Haz clic en el **último deployment** (el que acabas de hacer)
3. Haz clic en **"View Logs"** o **"Logs"**
4. **Desplázate hacia abajo** hasta encontrar errores (busca líneas en rojo o mensajes de error)

**Busca específicamente:**
- Errores de PHP (Fatal error, Parse error, etc.)
- Errores de conexión a base de datos
- Errores relacionados con `APP_KEY`
- Errores de permisos en `storage/` o `bootstrap/cache/`

---

## 🔑 Paso 2: Verificar Variables de Entorno CRÍTICAS

Ve a Railway > Tu Servicio > **"Variables"** y verifica que estas variables estén configuradas:

### Variables OBLIGATORIAS:

| Variable | Valor Esperado |
|---------|----------------|
| `APP_KEY` | `base64:...` (debe empezar con `base64:`) |
| `APP_URL` | `https://sistema-clinica-production-bee9.up.railway.app` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |

### Variables de Base de Datos:

| Variable | Valor Esperado |
|---------|----------------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

**⚠️ IMPORTANTE:** Si `APP_KEY` está vacío o mal configurado, la aplicación NO iniciará.

---

## 🛠️ Paso 3: Verificar Permisos de Storage

Laravel necesita permisos de escritura en `storage/` y `bootstrap/cache/`.

En Railway, ve a **"Deployments" > "View Logs" > "Terminal"** y ejecuta:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

O si eso no funciona:

```bash
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🔍 Paso 4: Probar la Ruta de Health Check

Laravel tiene una ruta de health check en `/up`. Prueba acceder a:

```
https://sistema-clinica-production-bee9.up.railway.app/up
```

**Si esto funciona**, significa que Laravel está corriendo pero hay un problema con las rutas.

**Si esto NO funciona**, significa que Laravel no está iniciando correctamente.

---

## 🐛 Paso 5: Habilitar Debug Temporalmente

Para ver errores específicos, temporalmente cambia en Railway Variables:

```
APP_DEBUG=true
```

**⚠️ IMPORTANTE:** Solo haz esto temporalmente para diagnosticar. Después vuelve a ponerlo en `false`.

---

## 🔧 Paso 6: Verificar Logs de Laravel

En Railway Terminal, ejecuta:

```bash
tail -f storage/logs/laravel.log
```

O para ver los últimos errores:

```bash
cat storage/logs/laravel.log | tail -50
```

Esto te mostrará errores específicos de Laravel.

---

## 🚨 Errores Comunes y Soluciones

### Error: "No application encryption key has been specified"

**Solución:** 
- Verifica que `APP_KEY` esté configurado en Railway Variables
- El valor debe empezar con `base64:`
- Ejemplo: `base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=`

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solución:**
- Verifica que las variables de base de datos estén correctas
- Asegúrate de que MySQL esté corriendo en Railway
- Verifica que `DB_HOST` use `${{MySQL.MYSQLHOST}}`

### Error: "The stream or file could not be opened"

**Solución:**
- Ejecuta en Railway Terminal:
  ```bash
  mkdir -p storage/logs
  chmod -R 775 storage bootstrap/cache
  ```

### Error: "Class not found" o "Call to undefined function"

**Solución:**
- Limpia el autoloader:
  ```bash
  composer dump-autoload
  php artisan optimize:clear
  ```

---

## 📋 Checklist de Diagnóstico

Marca cada uno después de verificar:

- [ ] Revisé los logs del deployment y encontré el error específico
- [ ] `APP_KEY` está configurado y empieza con `base64:`
- [ ] `APP_URL` coincide con el dominio de Railway
- [ ] Todas las variables de base de datos están configuradas
- [ ] Probé la ruta `/up` para verificar si Laravel responde
- [ ] Revisé `storage/logs/laravel.log` para errores específicos
- [ ] Verifiqué que MySQL esté corriendo en Railway

---

## 🆘 Si Nada Funciona

1. **Comparte los logs del deployment** - Especialmente las últimas 50 líneas
2. **Comparte el error específico** de `storage/logs/laravel.log`
3. **Verifica que todas las variables** estén exactamente como se muestra arriba

---

## 💡 Comando Rápido de Diagnóstico

Ejecuta esto en Railway Terminal para un diagnóstico completo:

```bash
php artisan config:show | grep APP_KEY
php artisan tinker --execute="echo 'Laravel funciona';"
php artisan route:list | head -10
```

Si alguno de estos comandos falla, ese es tu problema.
