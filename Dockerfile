FROM php:8.3-apache

# Eliminar otros MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_event.* \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.* \
    && a2enmod mpm_prefork

# PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# Dependencias
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www/html/Registros
RUN npm install pdfkit

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/

# 🔥 Railway dynamic port
ENV PORT=8080
RUN sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

EXPOSE 8080
