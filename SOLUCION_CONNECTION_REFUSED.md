# 🔧 Solución: "Connection Refused" - Servidor No Inicia

## ❌ El Problema

El error **"connection refused"** significa que:
- El contenedor se inicia correctamente
- Pero el servidor FrankenPHP **NO está escuchando** en el puerto
- Railway no puede conectarse al servidor

---

## ✅ Solución: Configurar Start Command Correcto

Railway necesita un comando de inicio explícito para FrankenPHP. Sigue estos pasos:

### Paso 1: Ir a Settings en Railway

1. En Railway, ve a tu servicio **"sistema-clinicas"**
2. Haz clic en la pestaña **"Settings"**
3. Busca la sección **"Start Command"** o **"Start"**

### Paso 2: Configurar Start Command

En el campo **"Start Command"**, configura **EXACTAMENTE** esto:

```bash
bash start.sh
```

O si Railway no acepta scripts, usa directamente:

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && frankenphp run
```

**⚠️ IMPORTANTE:** 
- NO uses `frankenphp run --config /etc/frankenphp/conf.d/frankenphp.conf`
- Usa simplemente `frankenphp run`
- Railway maneja la configuración automáticamente

### Paso 3: Verificar que el Archivo start.sh Existe

He actualizado el archivo `start.sh` en tu proyecto. Asegúrate de que esté en el repositorio:

1. Haz commit del archivo `start.sh` si no lo has hecho
2. Haz push a tu repositorio
3. Railway lo detectará automáticamente

### Paso 4: Redeploy

Después de configurar el Start Command:

1. Haz clic en **"Redeploy"** o espera a que Railway redesplegue automáticamente
2. Espera a que termine el deployment
3. Intenta acceder a tu aplicación

---

## 🔍 Alternativa: Usar Comando Directo

Si el script `start.sh` no funciona, configura el Start Command directamente:

```bash
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache && chmod -R 775 storage bootstrap/cache && php artisan config:cache && php artisan route:cache && php artisan view:cache && frankenphp run
```

---

## 📋 Verificación

Después de configurar el Start Command y hacer redeploy:

1. Ve a **"Deployments" > Último deployment > "Deploy Logs"**
2. Busca mensajes que indiquen que el servidor está iniciando
3. Busca errores relacionados con FrankenPHP
4. Intenta acceder a tu aplicación

---

## 🆘 Si Sigue Fallando

Si después de configurar el Start Command sigue el error:

1. **Verifica en Deploy Logs** si hay errores al iniciar FrankenPHP
2. **Comparte los Deploy Logs** completos del último deployment
3. Especialmente busca líneas con:
   - "ERROR"
   - "FATAL"
   - "frankenphp"
   - "Failed to start"
   - "Permission denied"

---

## 💡 Nota Importante

Railway detecta automáticamente aplicaciones PHP y debería usar FrankenPHP, pero a veces necesita un Start Command explícito. El comando `frankenphp run` sin argumentos adicionales debería funcionar porque Railway maneja la configuración automáticamente.
