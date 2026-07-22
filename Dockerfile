FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --optimize-autoloader

FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates curl gnupg \
    && install -d -m 0755 /etc/apt/keyrings \
    && curl -fsSL https://pgp.mongodb.com/server-7.0.asc \
        | gpg --dearmor -o /etc/apt/keyrings/mongodb-server-7.0.gpg \
    && echo "deb [ arch=amd64,arm64 signed-by=/etc/apt/keyrings/mongodb-server-7.0.gpg ] https://repo.mongodb.org/apt/debian bookworm/mongodb-org/7.0 main" \
        > /etc/apt/sources.list.d/mongodb-org-7.0.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends mongodb-mongosh \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY --from=vendor /app/vendor ./vendor
COPY app ./app
COPY config ./config
COPY public ./public
COPY storage ./storage

RUN mkdir -p storage/logs \
    && chown -R www-data:www-data storage

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_DISPLAY_ERRORS=false \
    APP_LOG_ERRORS=true \
    APP_LOG_PATH=storage/logs/app.log \
    APP_TIMEZONE=Europe/Paris

EXPOSE 80
