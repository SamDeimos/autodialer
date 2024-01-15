FROM php:5.4.45-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli mysql

COPY . /var/www/html

RUN chmod 0775 -R /var/www/html/files_csv