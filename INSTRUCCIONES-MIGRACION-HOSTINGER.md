# 🚀 Instrucciones para Migración a Hostinger

## ✅ Cambios Realizados

He optimizado tu tema de WordPress para que funcione correctamente en Hostinger. Los cambios incluyen:

### 1. **Mejoras en `functions.php`**
- ✅ Rutas absolutas para archivos CSS
- ✅ Cache busting para evitar problemas de caché
- ✅ Verificación de dependencias correctas
- ✅ Función de debug para identificar problemas
- ✅ Mejor manejo de versiones de archivos

### 2. **Mejoras en `calfiborti.php`**
- ✅ Verificación de existencia de archivos JS
- ✅ Dependencias correctas (jQuery, Bootstrap)
- ✅ Versión basada en fecha de modificación
- ✅ Logging de errores si el archivo no existe

### 3. **Archivo `.htaccess`**
- ✅ Configuración optimizada para Hostinger
- ✅ Compresión GZIP habilitada
- ✅ Caché para archivos estáticos
- ✅ Headers de seguridad
- ✅ Permisos específicos para CSS/JS

### 4. **Script de Verificación**
- ✅ `verificar-estilos.php` para diagnosticar problemas

## 🔧 Pasos para Completar la Migración

### Paso 1: Verificar Archivos
1. Sube todos los archivos modificados a tu servidor Hostinger
2. Asegúrate de que el archivo `.htaccess` esté en la raíz de tu sitio WordPress

### Paso 2: Verificar Permisos
Ejecuta estos comandos en el terminal de Hostinger (si tienes acceso SSH):
```bash
# Cambiar permisos del tema
chmod -R 755 /home/user/htdocs/srv980972.hstgr.cloud/wp-content/themes/opergali
chmod 644 /home/user/htdocs/srv980972.hstgr.cloud/wp-content/themes/opergali/*.css
chmod 644 /home/user/htdocs/srv980972.hstgr.cloud/wp-content/themes/opergali/*.js
chmod 644 /home/user/htdocs/srv980972.hstgr.cloud/wp-content/themes/opergali/js/*.js
```

### Paso 3: Limpiar Caché
1. Ve al panel de control de Hostinger
2. Busca la sección de "Caché" o "Cache"
3. Limpia el caché del sitio
4. También limpia el caché del navegador (Ctrl+F5)

### Paso 4: Verificar Funcionamiento
1. Visita tu sitio web
2. Abre las herramientas de desarrollador (F12)
3. Ve a la pestaña "Network" o "Red"
4. Verifica que los archivos CSS y JS se carguen correctamente

### Paso 5: Usar el Script de Verificación
1. Visita: `tu-sitio.com/wp-content/themes/opergali/verificar-estilos.php`
2. Revisa los resultados y corrige cualquier problema mostrado

## 🐛 Solución de Problemas Comunes

### Problema: Los estilos no se cargan
**Solución:**
1. Verifica que el archivo `style.css` existe y tiene permisos 644
2. Limpia el caché de Hostinger
3. Verifica que no hay errores en la consola del navegador

### Problema: Bootstrap no funciona
**Solución:**
1. Verifica la conexión a internet (los CDN requieren conexión)
2. Si los CDN fallan, descarga Bootstrap localmente
3. Verifica que jQuery se carga antes que Bootstrap

### Problema: JavaScript del calendario no funciona
**Solución:**
1. Verifica que el archivo `js/calfiborti.js` existe
2. Revisa la consola del navegador para errores
3. Asegúrate de que jQuery y Bootstrap se cargan primero

### Problema: Permisos denegados
**Solución:**
1. Cambia los permisos del directorio del tema a 755
2. Cambia los permisos de archivos CSS/JS a 644
3. Verifica el propietario de los archivos

## 📞 Soporte Adicional

Si sigues teniendo problemas:

1. **Activa el modo debug** en `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

2. **Revisa los logs** en `/wp-content/debug.log`

3. **Usa el script de verificación** para identificar problemas específicos

4. **Contacta al soporte de Hostinger** si hay problemas de servidor

## 🎯 Verificación Final

Después de completar todos los pasos, tu sitio debería:
- ✅ Cargar Bootstrap correctamente
- ✅ Mostrar todos los estilos del tema
- ✅ Funcionar el calendario Fiborti
- ✅ Cargar Font Awesome para los iconos
- ✅ Tener buena velocidad de carga

¡Tu migración a Hostinger debería estar completa! 🎉
