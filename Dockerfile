FROM php:8.3-cli

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Установка расширений PHP
RUN docker-php-ext-install pdo_pgsql zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/bin/symfony

WORKDIR /app
COPY . .

RUN composer install

# Открываем порт 8000
EXPOSE 8000

# Команда запуска сервера Symfony
CMD ["symfony", "server:start", "--no-tls", "--port=8000", "--allow-http"]
