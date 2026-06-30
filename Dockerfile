# syntax=docker/dockerfile:1

# --- Stage 1: install PHP dependencies with Composer ---
FROM composer:2 AS vendor

WORKDIR /app
COPY . .
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress

# --- Stage 2: runtime image (php-fpm) ---
FROM php:8.3-fpm-alpine AS app

# Install the extensions Symfony/ramsey-uuid benefit from.
RUN apk add --no-cache icu-libs \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev \
    && docker-php-ext-install intl opcache \
    && apk del .build-deps

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

ENV APP_ENV=prod \
    APP_DEBUG=0

RUN mkdir -p var \
    && chown -R www-data:www-data var

USER www-data

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=3s --start-period=10s \
    CMD php -r "exit(extension_loaded('intl') ? 0 : 1);"

CMD ["php-fpm"]
