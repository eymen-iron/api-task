
FROM php:8.1


RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

COPY . .

EXPOSE 3001

CMD php -S 0.0.0.0:3001 -t /var/www/html
