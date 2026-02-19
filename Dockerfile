FROM php:8.3-cli

# Instalar extensiones PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Copiar proyecto
WORKDIR /app
COPY . /app

# Instalar pdfkit
WORKDIR /app/Registros
RUN npm install pdfkit

# Volver a raíz del proyecto
WORKDIR /app

# Railway usa PORT dinámico
ENV PORT=8080

# Servidor embebido PHP
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t /app"]
