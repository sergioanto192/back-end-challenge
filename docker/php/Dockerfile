# Definir a imagem base
FROM php:8.1-apache

# Atualizar pacotes e instalar dependências
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev git unzip \
    libpq-dev \  
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql  

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar configuração do Apache e ativar mod_rewrite
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite && service apache2 restart

# Definir permissões para o Apache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expor a porta 80
EXPOSE 80