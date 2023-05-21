FROM php:8.1.19-fpm

# Install PHP
RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Create a user for the container that mirrors the host machine's user
ARG USERNAME=default
ARG USER_UID=1000
ARG USER_GID=1000

RUN groupadd --gid $USER_GID $USERNAME \
    && useradd --uid $USER_UID --gid $USER_GID -m $USERNAME

USER $USERNAME

# Install all dependancies in case you have a fresh install
RUN composer install

# Generate the keypair for JWT authentication
RUN php bin/console lexik:jwt:generate-keypair

# Create the schema (database is already there thanks to docker-compose)
RUN php bin/console doctrine:schema:create

# Add data in the database (this will purge the database, comment if you want to keep your data)
RUN php bin/console doctrine:fixtures:load --no-interaction