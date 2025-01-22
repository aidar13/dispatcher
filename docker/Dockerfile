FROM gitlab.spark-dev.team:5050/spark/docker-images/php:8.2-fpm-laravel

WORKDIR /srv/www/app

COPY --chown=www-data:www-data ./ /srv/www/app

RUN mkdir /srv/www/app/vendor && chown www-data:www-data -R /srv/www/app && \
    composer install && \
    chgrp -R www-data storage bootstrap/cache || true && \
    chmod -R ug+rwx storage bootstrap/cache || true && \
    chmod -R +x vendor/bin/* || true

USER www-data

ENTRYPOINT ["/bin/bash", "./docker/docker-entrypoint.sh"]
