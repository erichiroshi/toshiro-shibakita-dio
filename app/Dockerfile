FROM php:8.1-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y unzip git

# Habilitar extensões do PHP
RUN docker-php-ext-install mysqli

# Instalar o Composer (correto e oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia arquivos necessários para rodar composer
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist

# Copia o restante da aplicação
COPY . .

# Permissão para Apache
RUN chown -R www-data:www-data /var/www/html
