FROM php:8.3-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    nginx git \
    && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

COPY . /var/www/html/
COPY ./infra/nginx/default.conf /etc/nginx/conf.d/default.conf


WORKDIR /var/www/html

RUN composer dump-autoload

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["sh", "/var/www/html/infra/docker-entrypoint.sh"]

