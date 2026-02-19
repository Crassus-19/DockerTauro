# Imagen base ligera sin Apache
FROM php:8.3-cli

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar dependencias del sistema + Node 20
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Verificar instalación (opcional pero útil)
RUN node -v && npm -v

# Directorio de trabajo
WORKDIR /app

# Copiar proyecto
COPY . /app

# Instalar pdfkit SOLO si existe carpeta Registros
WORKDIR /app/Registros
RUN npm install pdfkit || true

# Volver a raíz
WORKDIR /app

# Puerto dinámico Railway
ENV PORT=8080

# Servidor PHP embebido
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t /app"]
