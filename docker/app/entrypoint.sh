#!/bin/sh
set -e

chmod -R 777 storage bootstrap/cache 2>/dev/null || true

exec docker-php-entrypoint php-fpm
