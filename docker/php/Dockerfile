# Usar a imagem oficial do PHP 8.3 com FPM
FROM php:8.3-fpm

# Instalar pacotes necessários
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql mbstring bcmath

RUN apt-get update && apt-get install -y libicu-dev && docker-php-ext-install intl


# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os ficheiros do projeto Laravel para o container
COPY . /var/www/html

# Expor porta do PHP-FPM
EXPOSE 9000

# Comando padrão ao iniciar o container
CMD ["php-fpm"]



