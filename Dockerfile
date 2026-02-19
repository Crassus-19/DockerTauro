FROM php:8.3-apache

# Deshabilitar todos los MPM primero
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2dismod mpm_prefork || true

# Habilitar solo prefork (requerido para mod_php)
RUN a2enmod mpm_prefork

# Verificar configuración en build (importante)
RUN apachectl configtest

# Extensiones PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# Dependencias
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html/Registros
RUN npm install pdfkit

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
