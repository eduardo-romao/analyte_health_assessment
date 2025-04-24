#!/bin/bash
set -e

composer dump-autoload
composer install --no-interaction

service nginx start && php-fpm

exec "$@"