# Instrucciones para correr el proyecto con Docker

## Pasos para inicializar:

1. **Reconstruir los contenedores (importante después de cambiar el Dockerfile)**:
   ```bash
   docker-compose down -v
   docker-compose build --no-cache
   docker-compose up -d
   ```

2. **Verificar que los contenedores están corriendo**:
   ```bash
   docker-compose ps
   ```

3. **Entrar al contenedor para verificar permisos**:
   ```bash
   docker exec -it laravel_app bash
   ls -la storage/framework/sessions
   ```

4. **Limpiar la cache y sesiones antiguas** (ejecutar dentro del contenedor):
   ```bash
   docker exec -it laravel_app bash -c "rm -rf storage/framework/sessions/* && rm -rf storage/framework/views/*"
   ```

5. **Verificar los logs de Apache** si hay errores:
   ```bash
   docker logs laravel_app
   ```

## URLs de acceso:

- Aplicación: http://localhost:8000
- phpMyAdmin: http://localhost:8080

## Problemas comunes y soluciones:

### Problema de permisos
Si sigues teniendo problemas de permisos, ejecuta:
```bash
docker exec -it laravel_app bash -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```

### Problema de sesiones
Si las sesiones no funcionan:
1. Verifica que el directorio existe: `docker exec -it laravel_app ls -la storage/framework/sessions`
2. Verifica los permisos: deben ser `drwxrwxr-x` con dueño `www-data:www-data`
3. Limpia las sesiones viejas: `docker exec -it laravel_app rm -rf storage/framework/sessions/*`

### Verificar la configuración de sesiones desde dentro del contenedor
```bash
docker exec -it laravel_app php artisan tinker
# Dentro de tinker:
config('session.driver')  # Debe retornar 'file'
config('session.files')   # Debe retornar la ruta del storage
```

## Solución de problemas con sesiones que no persisten

Si después de login te redirige de vuelta al login, ejecuta estos comandos:

```bash
# 1. Limpiar cache, sesiones y vistas compiladas
docker exec -it laravel_app bash -c "php artisan cache:clear && php artisan view:clear && rm -rf storage/framework/sessions/* && rm -rf storage/framework/views/*"

# 2. Verificar permisos
docker exec -it laravel_app bash -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# 3. Reiniciar el contenedor
docker-compose restart app

# 4. Limpiar cookies del navegador en localhost:8000
# Presiona F12 > Application > Cookies > Eliminar todas las cookies de localhost:8000

# 5. restaturar base
docker cp backup.sql laravel_db:/tmp/backup.sql
docker exec -it laravel_db mysql -u root -proot db_credito -e "source /tmp/backup.sql"
```
