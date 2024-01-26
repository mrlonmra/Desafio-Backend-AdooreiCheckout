FROM php:8.2.12-fpm

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html

# Instala dependências necessárias e limpa o cache
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install zip pdo_mysql \
    && apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copia apenas os arquivos necessários para aproveitar o cache
COPY composer.json composer.lock ./

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.5

# Instala as dependências do Composer
RUN composer install --no-scripts --no-autoloader

# Copia o restante dos arquivos
COPY . .

# Carrega as dependências do Composer
RUN composer dump-autoload

# Comando padrão para executar o servidor web do Laravel (apenas para desenvolvimento)
CMD php artisan serve --host=0.0.0.0 --port=8000