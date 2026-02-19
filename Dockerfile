FROM php:8.3-fpm

# Instalar extensiones PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Instalar pdfkit
WORKDIR /app
COPY . /app

RUN npm install pdfkit

# Permisos
RUN chown -R www-data:www-data /app

# Railway usa PORT dinámico
ENV PORT=8080

# Usar servidor embebido de PHP
CMD php -S 0.0.0.0:$PORT -t /app
