#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install git wget zlib1g-dev -yqq

# Install zip
apt-get install zip libzip-dev -yqq
docker-php-ext-install zip

# Install xdebug
pecl install xdebug -yqq
docker-php-ext-enable xdebug

#install composer
wget https://composer.github.io/installer.sig -O - -q | tr -d '\n' > installer.sig
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir /usr/local/bin --filename=composer
php -r "unlink('composer-setup.php'); unlink('installer.sig');"
