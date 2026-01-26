# 📋 Guía Paso a Paso: Cómo Agregar Variables de Entorno en Railway

## 🎯 Ubicación de las Variables de Entorno

Las variables de entorno se configuran en el panel de Railway, en la sección **"Variables"** de tu proyecto.

---

## 📍 Paso 1: Acceder a la Configuración de Variables

1. **Abre tu proyecto en Railway**
   - Ve a [railway.app](https://railway.app)
   - Inicia sesión en tu cuenta
   - Selecciona tu proyecto "sistema-clinica-production"

2. **Navega a la sección de Variables**
   - En el menú lateral izquierdo, busca y haz clic en **"Variables"**
   - O también puedes hacer clic en tu servicio (el servicio de tu aplicación) y luego en la pestaña **"Variables"**

---

## ➕ Paso 2: Agregar Variables de Entorno

En la página de Variables verás:
- Una lista de variables existentes (si hay alguna)
- Un botón **"+ New Variable"** o **"Add Variable"**
- Campos para **"Name"** (nombre) y **"Value"** (valor)

### Para cada variable, sigue estos pasos:

1. Haz clic en **"+ New Variable"** o **"Add Variable"**
2. En el campo **"Name"**, escribe el nombre de la variable (por ejemplo: `APP_NAME`)
3. En el campo **"Value"**, escribe el valor (por ejemplo: `Sistema Clínica`)
4. Haz clic en **"Add"** o **"Save"**

---

## 📝 Lista Completa de Variables a Agregar

Agrega estas variables **UNA POR UNA** siguiendo el proceso anterior:

### 🔴 Variables Críticas (OBLIGATORIAS)

| Nombre de Variable | Valor |
|-------------------|-------|
| `APP_NAME` | `Sistema Clínica` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://sistema-clinica-production-bee9.up.railway.app` |

### 🗄️ Variables de Base de Datos (si ya agregaste MySQL)

Si ya agregaste MySQL en Railway, estas variables deberían aparecer automáticamente. Si no, agrégalas manualmente:

| Nombre de Variable | Valor |
|-------------------|-------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

**Nota:** Si Railway ya creó estas variables automáticamente cuando agregaste MySQL, NO las dupliques. Solo verifica que existan.

### 💾 Variables de Sesiones y Caché

Agrega estas variables **UNA POR UNA**:

| Nombre de Variable | Valor |
|-------------------|-------|
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | `120` |
| `CACHE_DRIVER` | `database` |
| `QUEUE_CONNECTION` | `database` |

---

## 🎬 Ejemplo Visual del Proceso

```
1. Haz clic en "+ New Variable"
   ↓
2. Aparecen dos campos:
   [Name:  ]  ← Escribe aquí: SESSION_DRIVER
   [Value: ]  ← Escribe aquí: database
   ↓
3. Haz clic en "Add" o "Save"
   ↓
4. La variable aparece en la lista
   ↓
5. Repite para la siguiente variable
```

---

## ✅ Verificación

Después de agregar todas las variables, deberías ver una lista similar a esta:

```
✅ APP_NAME = Sistema Clínica
✅ APP_ENV = production
✅ APP_KEY = base64:Br9icP05zOI8Y2tUcCQpEcgg6hB5FZ3O0dCuzuIG5iM=
✅ APP_DEBUG = false
✅ APP_URL = https://sistema-clinica-production-bee9.up.railway.app
✅ DB_CONNECTION = mysql
✅ DB_HOST = ${{MySQL.MYSQLHOST}}
✅ DB_PORT = ${{MySQL.MYSQLPORT}}
✅ DB_DATABASE = ${{MySQL.MYSQLDATABASE}}
✅ DB_USERNAME = ${{MySQL.MYSQLUSER}}
✅ DB_PASSWORD = ${{MySQL.MYSQLPASSWORD}}
✅ SESSION_DRIVER = database
✅ SESSION_LIFETIME = 120
✅ CACHE_DRIVER = database
✅ QUEUE_CONNECTION = database
```

---

## 🔧 Si No Encuentras la Sección "Variables"

Si no ves la opción "Variables" en el menú:

1. **Haz clic en tu servicio** (el servicio de tu aplicación Laravel)
2. Ve a la pestaña **"Variables"** en la parte superior
3. O busca **"Environment Variables"** en el menú

---

## ⚠️ Importante

- **NO** incluyas espacios antes o después del signo `=` en Railway
- Railway usa **solo el nombre** de la variable en el campo "Name"
- Railway usa **solo el valor** de la variable en el campo "Value"
- NO escribas `SESSION_DRIVER=database` todo junto, sino:
  - Name: `SESSION_DRIVER`
  - Value: `database`

---

## 🚀 Después de Agregar las Variables

1. Railway **redesplegará automáticamente** tu aplicación
2. O puedes hacer clic en **"Deploy"** manualmente
3. Espera a que termine el deployment
4. Verifica que el error 502 haya desaparecido

---

## 🆘 Si Tienes Problemas

Si después de agregar las variables sigue el error 502:

1. **Verifica los logs:**
   - Ve a "Deployments" > Selecciona el último deployment > "View Logs"
   - Busca errores específicos

2. **Verifica que todas las variables estén correctas:**
   - Revisa que no haya espacios extra
   - Verifica que `APP_KEY` tenga el valor correcto
   - Confirma que `APP_URL` coincida con tu dominio

3. **Ejecuta migraciones:**
   - Ve a "Deployments" > "View Logs" > "Terminal"
   - Ejecuta: `php artisan migrate --force`
