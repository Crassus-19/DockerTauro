FROM php:8.3-cli

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar Node si realmente lo usas
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Copiar proyecto completo
WORKDIR /app
COPY . /app

# Si usas pdfkit
WORKDIR /app/Registros
RUN npm install pdfkit || true

WORKDIR /app

# Railway puerto dinámico
ENV PORT=8080

# Servidor PHP embebido
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t /app"]
