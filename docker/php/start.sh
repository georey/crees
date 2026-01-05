#!/bin/bash

# Corregir permisos de storage y cache en cada inicio
# Esto es necesario porque los archivos montados desde Windows pueden tener permisos incorrectos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Asegurarse que los directorios de sesión existen
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs

# Aplicar permisos específicos para sesiones
chmod -R 775 /var/www/html/storage/framework/sessions
chown -R www-data:www-data /var/www/html/storage/framework/sessions

# Configurar PHP para sesiones
echo "session.save_handler = files" >> /usr/local/etc/php/conf.d/session.ini
echo "session.save_path = \"/var/www/html/storage/framework/sessions\"" >> /usr/local/etc/php/conf.d/session.ini
echo "session.gc_probability = 1" >> /usr/local/etc/php/conf.d/session.ini
echo "session.gc_divisor = 100" >> /usr/local/etc/php/conf.d/session.ini

# Limpiar output buffering que puede causar problemas
echo "output_buffering = 4096" >> /usr/local/etc/php/conf.d/output.ini
echo "implicit_flush = Off" >> /usr/local/etc/php/conf.d/output.ini

# Iniciar Apache
apache2-foreground