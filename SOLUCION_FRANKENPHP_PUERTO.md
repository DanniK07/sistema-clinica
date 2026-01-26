# 🔧 Solución: FrankenPHP No Escucha en el Puerto Correcto

## ❌ El Problema

Los logs muestran que:
- ✅ Caddy inicia correctamente ("admin endpoint started")
- ✅ Los caches se crean correctamente
- ❌ Pero **FrankenPHP NO está escuchando** en el puerto HTTP
- ❌ Railway no puede conectarse → 502 Bad Gateway

---

## ✅ Solución: Especificar Puerto Explícitamente

He actualizado el archivo `start.sh` para que FrankenPHP use explícitamente la variable `PORT` de Railway.

### Paso 1: Hacer Commit y Push

1. Haz commit del archivo `start.sh` actualizado:
   ```bash
   git add start.sh
   git commit -m "Fix: FrankenPHP ahora usa PORT explícitamente"
   git push
   ```

2. Railway detectará automáticamente el cambio y redesplegará

### Paso 2: Verificar Start Command

Asegúrate de que en Railway Settings > Start Command esté configurado:

```bash
bash start.sh
```

### Paso 3: Redeploy

1. Ve a Railway > Tu Servicio
2. Haz clic en **"Redeploy"**
3. Espera a que termine el deployment

### Paso 4: Verificar Logs

Después del redeploy, ve a **"Deployments" > Último deployment > "Deploy Logs"** y busca:

```
Starting FrankenPHP on port [número]
```

Si ves este mensaje, significa que FrankenPHP está intentando iniciar en el puerto correcto.

---

## 🔍 Si Sigue Fallando

Si después de este cambio sigue el 502:

1. **Verifica en Deploy Logs** si aparece el mensaje "Starting FrankenPHP on port..."
2. **Busca errores** relacionados con:
   - "Address already in use"
   - "Permission denied"
   - "Failed to bind"
   - Cualquier error de PHP

3. **Comparte los Deploy Logs completos** del último deployment

---

## 💡 Explicación Técnica

Railway proporciona automáticamente la variable de entorno `PORT`, pero FrankenPHP puede no detectarla automáticamente. Al especificar explícitamente `--listen "0.0.0.0:$PORT"`, le decimos a FrankenPHP que escuche en:
- **0.0.0.0**: Todas las interfaces de red (necesario para Railway)
- **$PORT**: El puerto que Railway asigna automáticamente

Esto asegura que Railway pueda conectarse al servidor.
