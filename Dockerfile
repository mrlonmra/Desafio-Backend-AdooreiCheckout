# Imagem oficial do PHP para o ambiente de produção
FROM php:8.2.12-fpm

# Diretório de trabalho dentro do contêiner
WORKDIR /var/www/html

# Instalar e ativar extensões do PHP necessárias
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install zip pdo_mysql \
    && apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copia os arquivos necessários para aproveitar o cache
COPY composer.json composer.lock ./

# Instala o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.5

# Instala as dependências do Composer, otimizando o processo de construção
RUN set -eux; \
    composer install --no-scripts --no-autoloader --prefer-dist --no-interaction; \
    composer clear-cache

# Copia o restante dos arquivos
COPY . .

# Carregua as dependências do Composer
RUN composer dump-autoload --optimize

# Executa as migrações
RUN php artisan migrate --force

# Executa as seeds "Popular as tabelas para fim de teste"
RUN php artisan db:seed --force

# Remove o servidor de desenvolvimento do CMD, já que não é recomendado para ambientes de produção
CMD php artisan serve --host=0.0.0.0 --port=8000
