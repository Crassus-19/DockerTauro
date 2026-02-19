# Usa la imagen oficial de PHP con Apache
FROM php:8.3-apache

# 🔥 FORZAR SOLO UN MPM (solución al error)
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Instalar Node.js 20 y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Definir directorio de trabajo para Node.js
WORKDIR /var/www/html/Registros

# Instalar pdfkit
RUN npm install pdfkit

# Volver al directorio raíz
WORKDIR /var/www/html/

# Copiar archivos
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
