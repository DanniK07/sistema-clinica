# 📋 Cómo Ver los Logs del Contenedor en Railway

## 🔍 Dónde Encontrar los Logs del Contenedor

Los logs del contenedor **en ejecución** están en una ubicación diferente a los logs de build/deploy.

### Opción 1: Pestaña "Logs" (Recomendado)

1. En Railway, ve a tu proyecto
2. Haz clic en tu servicio **"sistema-clinicas"**
3. Busca la pestaña **"Logs"** en la parte superior (junto a "Deployments", "Variables", "Metrics", "Settings")
4. **NO** vayas a "Metrics" > "Logs" - busca la pestaña principal "Logs"

Si no ves la pestaña "Logs", usa la Opción 2.

### Opción 2: Terminal del Deployment

1. Ve a **"Deployments"**
2. Haz clic en el **último deployment** (el más reciente)
3. Haz clic en **"View Logs"** o **"Logs"**
4. Busca un botón o pestaña que diga **"Terminal"** o **"Shell"**
5. En el terminal, ejecuta:

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# O ver los últimos 100 líneas
tail -100 storage/logs/laravel.log

# Ver si hay errores de PHP
php artisan config:show | grep APP_KEY

# Intentar ver qué está pasando
php artisan tinker --execute="echo 'Test';"
```

### Opción 3: Ver Logs del Sistema

En el terminal de Railway, ejecuta:

```bash
# Ver logs del servidor
journalctl -u frankenphp

# O ver procesos corriendo
ps aux

# Ver si el servidor está escuchando
netstat -tulpn | grep :8000
```

---

## 🎯 Lo que Necesito Ver

Cuando encuentres los logs, busca específicamente:

1. **Errores de PHP:**
   - "Fatal error"
   - "Parse error"
   - "Class not found"
   - "Call to undefined function"

2. **Errores de Laravel:**
   - "No application encryption key"
   - "SQLSTATE"
   - "Connection refused"
   - "The stream or file could not be opened"

3. **Errores del Servidor:**
   - "Failed to start"
   - "Address already in use"
   - "Permission denied"

4. **Mensajes de Inicio:**
   - "Server started"
   - "Listening on"
   - "Application ready"

---

## 🚨 Si No Puedes Encontrar los Logs

Si no encuentras la pestaña "Logs" o el terminal:

1. **Toma una captura de pantalla** de tu panel de Railway
2. **Muéstrame** todas las pestañas/opciones que ves
3. Así te puedo guiar exactamente dónde hacer clic

---

## 💡 Alternativa: Crear un Archivo de Log Manual

Si no puedes acceder a los logs, podemos crear un script que escriba información de diagnóstico:

En Railway Terminal, ejecuta:

```bash
# Crear un archivo de diagnóstico
php artisan tinker --execute="
echo 'APP_KEY: ' . config('app.key') . PHP_EOL;
echo 'APP_ENV: ' . config('app.env') . PHP_EOL;
echo 'APP_DEBUG: ' . (config('app.debug') ? 'true' : 'false') . PHP_EOL;
echo 'DB_CONNECTION: ' . config('database.default') . PHP_EOL;
try {
    DB::connection()->getPdo();
    echo 'DB: Connected' . PHP_EOL;
} catch (Exception \$e) {
    echo 'DB Error: ' . \$e->getMessage() . PHP_EOL;
}
" > /tmp/diagnostico.txt 2>&1

# Ver el diagnóstico
cat /tmp/diagnostico.txt
```

Esto te mostrará información clave sobre la configuración.
