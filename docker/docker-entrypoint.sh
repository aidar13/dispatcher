#!/bin/sh
set -e

# Export APP_ENV from .env file if not already set in OS
source /usr/local/bin/exportvariable APP_ENV /srv/www/app/.env

if [ "$APP_DEBUG" = true ]; then
  sudo sed -i "s/;zend_extension=xdebug.so/zend_extension=xdebug.so/" "$PHP_INI_DIR/conf.d/xdebug.ini"
fi

if [ $# -gt 0 ]; then
    exec "$@"
else
    if [ ! -f vendor/bin/phing ]; then
        exec php-fpm -F -O
    else
        vendor/bin/phing start && exec php-fpm -F -O
    fi
fi
