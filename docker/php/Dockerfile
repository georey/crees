FROM php:5.6-apache-jessie

# Repositorios EOL
RUN echo "deb http://archive.debian.org/debian jessie main" > /etc/apt/sources.list \
 && echo "deb http://archive.debian.org/debian-security jessie/updates main" >> /etc/apt/sources.list \
 && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid \
 && echo 'Acquire::AllowInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99no-check-valid

# Apache
RUN a2enmod rewrite

# Dependencias + mcrypt + GD
RUN apt-get update --allow-unauthenticated \
 && apt-get install -y --allow-unauthenticated \
    libxml2-dev \
    libmcrypt-dev \
    libpng-dev \
    libjpeg-dev \
    unzip \
    git \
    libzip-dev \
    zip \
 && docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    mcrypt \
    gd \
    zip \
 && rm -rf /var/lib/apt/lists/*

# Laravel public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Script de inicio (copiarlo primero, antes del código completo)
COPY docker/php/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Copiar todo el código de la aplicación al contenedor
COPY . /var/www/html

# Crear directorios de Laravel con permisos correctos
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && mkdir -p vendor/mpdf/mpdf/tmp/mpdf \
    && chown -R www-data:www-data storage bootstrap/cache vendor/mpdf/mpdf/tmp \
    && chmod -R 775 storage bootstrap/cache vendor/mpdf/mpdf/tmp

CMD ["/usr/local/bin/start.sh"]
