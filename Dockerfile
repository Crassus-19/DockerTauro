# Usa la imagen oficial de PHP con Apache
FROM php:8.3-apache

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs npm

# Definir directorio de trabajo para Node.js y copiar archivos de la aplicación
WORKDIR /var/www/html/Registros

# Instalar pdfkit y cualquier otra dependencia de Node.js
RUN npm install pdfkit

# Volver al directorio raíz
WORKDIR /var/www/html/

# Copiar archivos de la aplicación
COPY . /var/www/html/

# Otorgar permisos adecuados
RUN chown -R www-data:www-data /var/www/html/

# Exponer el puerto 80
EXPOSE 80
