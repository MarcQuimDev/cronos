# ✅ Checklist ANTES de Hacer Deploy en Render

**IMPORTANTE**: Completa esta checklist ANTES de hacer `git push`. Si no configuras todo correctamente, el deploy fallará.

## 1. ✅ Base de Datos MySQL Creada en Render

- [ ] He creado una base de datos MySQL en Render
- [ ] Tengo anotadas las credenciales:
  - Hostname (ej: `dpg-xxxxx.oregon-postgres.render.com`)
  - Port (normalmente `3306`)
  - Database name (ej: `cronos_db`)
  - Username (ej: `cronos_user`)
  - Password (la contraseña generada)

**Cómo crear la base de datos:**
1. Dashboard de Render → "New +" → "MySQL"
2. Sigue el wizard
3. Anota TODAS las credenciales

## 2. ✅ Variables de Entorno Configuradas en Render

- [ ] He configurado TODAS estas variables en Render Dashboard → Environment:

### Variables OBLIGATORIAS:

```
APP_NAME=Cronos
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=<hostname-de-tu-base-de-datos>
DB_PORT=3306
DB_DATABASE=<nombre-de-tu-base-de-datos>
DB_USERNAME=<usuario-de-base-de-datos>
DB_PASSWORD=<contraseña-de-base-de-datos>

LOG_CHANNEL=stderr
LOG_LEVEL=error
```

**⚠️ IMPORTANTE**:
- Deja `APP_KEY` vacío, se generará automáticamente
- Reemplaza `tu-app.onrender.com` con la URL real de Render
- Reemplaza TODOS los valores entre `< >`

## 3. ✅ Archivos Locales Verificados

- [ ] He ejecutado `npm install` localmente sin errores
- [ ] He ejecutado `npm run build` localmente sin errores
- [ ] El build genera archivos en `public/build/`:
  - `manifest.json`
  - `assets/app-XXXXX.css`
  - `assets/app-XXXXX.js`

**Comandos para verificar:**
```bash
npm install
npm run build
ls -la public/build/
```

Si ves el `manifest.json` y los archivos en `assets/`, estás listo.

## 4. ✅ Git Status Limpio

- [ ] He commiteado todos los cambios importantes
- [ ] He revisado que NO estoy committeando:
  - `node_modules/`
  - `vendor/`
  - `.env` (solo `.env.example` debe ir)
  - `public/build/` (se genera en cada deploy)

**Comando para verificar:**
```bash
git status
```

## 5. ✅ Último Paso - Push a Render

Si TODOS los checkboxes anteriores están marcados:

```bash
# 1. Limpia el build local (opcional pero recomendado)
rm -rf public/build

# 2. Verifica cambios
git status

# 3. Agrega los archivos modificados
git add .

# 4. Commit
git commit -m "fix: complete production deployment configuration"

# 5. Push (esto iniciará el deploy automáticamente en Render)
git push
```

## 6. ✅ Después del Deploy - Verificación

Una vez que Render termine el deploy, verifica:

- [ ] El build se completó sin errores rojos
- [ ] En los logs aparece: `✓ Created .env from .env.example`
- [ ] En los logs aparece: `✓ Build assets copied successfully`
- [ ] En los logs aparece: `✓ Custom Nginx config installed`
- [ ] La aplicación carga en el navegador
- [ ] Los estilos de Tailwind CSS se ven correctamente

## 🆘 Si Algo Sale Mal

### Error: "manifest.json not found"
**Causa**: El build de Vite falló
**Solución**: Revisa los logs del build en la sección "node-builder"

### Error: "No such file or directory: .env"
**Causa**: Problema durante el build
**Solución**: Ya está resuelto en el Dockerfile, verifica logs

### Error 500 en la web
**Causa**: Variables de entorno mal configuradas o BD no accesible
**Solución**:
1. Verifica las variables en Render Dashboard
2. Prueba la conexión a la BD
3. Revisa los logs: `Dashboard → Logs`

### Los estilos no se cargan
**Causa**: Problema con Nginx o assets
**Solución**:
1. F12 → Network → busca errores 404
2. Verifica que `manifest.json` existe en los logs
3. Revisa que Nginx config se instaló correctamente

## 📚 Documentación Completa

Para más detalles, lee: `RENDER_DEPLOY.md`
