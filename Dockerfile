FROM php:8.3.4-fpm

# Atualiza os pacotes, instala a dependência do Postgres, instala as extensões e limpa o cache
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*