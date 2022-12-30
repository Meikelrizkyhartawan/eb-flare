FROM php:7.4.27-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY . /var/www/html
RUN usermod -u 1000 www-data;
EXPOSE 80
