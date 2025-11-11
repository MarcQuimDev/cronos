# Guía de Deployment en Render

## Variables de Entorno Requeridas

Para que la aplicación funcione correctamente en Render, debes configurar las siguientes variables de entorno en el dashboard de Render:

### Variables Básicas (OBLIGATORIAS)

```bash
# Application
APP_NAME=Cronos
APP_ENV=production
APP_KEY=                    # Se generará automáticamente en el primer deploy
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com

# Database - MySQL (OBLIGATORIO configurar una base de datos)
DB_CONNECTION=mysql
DB_HOST=tu-mysql-host.render.com
DB_PORT=3306
DB_DATABASE=tu_database_name
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### Variables Opcionales

```bash
# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (si necesitas enviar emails)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

## Pasos para Deployar en Render

### 1. Crear una Base de Datos MySQL en Render

1. Ve a tu dashboard de Render
2. Click en "New +" → "PostgreSQL" o "MySQL" (recomendado MySQL)
3. Anota las credenciales:
   - Hostname
   - Port
   - Database name
   - Username
   - Password

### 2. Crear el Web Service

1. Click en "New +" → "Web Service"
2. Conecta tu repositorio de GitHub
3. Configura:
   - **Name**: cronos-tdr (o el nombre que prefieras)
   - **Environment**: Docker
   - **Branch**: main
   - **Dockerfile Path**: Deja vacío (usa ./Dockerfile por defecto)

### 3. Configurar Variables de Entorno

En la sección "Environment Variables" del dashboard de Render, agrega todas las variables listadas arriba con tus valores reales.

**IMPORTANTE**: No olvides reemplazar:
- `tu-mysql-host.render.com` → El hostname de tu base de datos
- `tu_database_name` → El nombre de tu base de datos
- `tu_usuario` → Tu usuario de base de datos
- `tu_password` → Tu contraseña de base de datos
- `https://tu-app.onrender.com` → La URL que Render te asigne

### 4. Deploy

1. Click en "Create Web Service"
2. Render automáticamente:
   - Construirá la imagen Docker
   - Instalará dependencias de Composer
   - Compilará assets con Vite y Tailwind CSS
   - Ejecutará migraciones
   - Iniciará la aplicación

### 5. Verificar el Deploy

Después del deploy, verifica:
- ✓ El build se completó sin errores
- ✓ La aplicación responde en la URL asignada
- ✓ Los estilos se cargan correctamente
- ✓ Las migraciones se ejecutaron

## Troubleshooting

### Error 500 - Internal Server Error

**Causa**: Usualmente por variables de entorno faltantes o base de datos no configurada.

**Solución**:
1. Verifica que todas las variables de entorno estén configuradas
2. Verifica que la base de datos esté accesible
3. Revisa los logs en Render: Dashboard → Tu servicio → Logs

### PHP-FPM Socket Error

**Causa**: El script de deployment falló durante la inicialización.

**Solución**:
1. Revisa los logs para ver qué comando falló
2. Verifica la configuración de la base de datos
3. El nuevo script de deployment es más tolerante y debería seguir iniciando incluso con errores menores

### Assets/Estilos no se cargan

**Causa**: El archivo manifest.json no se generó correctamente.

**Solución**:
1. Verifica en los logs que `npm run build` se ejecutó correctamente
2. Busca el mensaje "✓ Vite manifest.json found" en los logs
3. Si no aparece, puede haber un problema con la configuración de Vite

### Base de Datos SQLite en Producción

Si prefieres usar SQLite en lugar de MySQL (no recomendado para producción):

```bash
DB_CONNECTION=sqlite
# Comenta o elimina las otras variables DB_*
```

**Nota**: SQLite no es recomendable para producción porque el filesystem en Render es efímero y podrías perder datos.

## Comandos Útiles

Si necesitas ejecutar comandos en el contenedor:

```bash
# Ver logs en tiempo real
# Ve a: Dashboard → Tu servicio → Logs

# Ejecutar comandos artisan (desde el shell de Render)
php artisan migrate
php artisan cache:clear
php artisan config:cache
```

## Recursos

- [Documentación de Render - Docker](https://render.com/docs/docker)
- [Documentación de Laravel - Deployment](https://laravel.com/docs/deployment)
- [Documentación de Vite - Production Build](https://vitejs.dev/guide/build.html)
