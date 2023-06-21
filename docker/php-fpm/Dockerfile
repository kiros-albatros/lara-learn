ARG WWWGROUP
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15.1
ARG PHP_EXTENSION_INSTALLER_VERSION=latest
ARG PHP_VERSION=8.2
ARG COMPOSER_VERSION=latest

FROM composer:${COMPOSER_VERSION} AS composer

FROM mlocati/php-extension-installer:${PHP_EXTENSION_INSTALLER_VERSION} AS php_extension_installer

FROM php:${PHP_VERSION}-fpm AS base

LABEL maintainer="Semin Artem"

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Europe/Moscow

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/

# persistent / runtime deps
RUN apt-get update \
    && apt-get install -y gnupg gosu vim curl ca-certificates zip unzip git  \
    supervisor libcap2-bin libpng-dev python3 wget libpq-dev iproute2 findutils vim lsb-release\
    && curl -sLS https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && sh -c 'echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list' \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo pdo_pgsql pgsql gd curl imap mbstring xml zip bcmath  \
    intl readline ldap msgpack igbinary redis swoole pcov sockets

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./docker/php-fpm/prod/php.ini        $PHP_INI_DIR/php.ini
COPY ./docker/php-fpm/prod/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
    composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/cms

# build for production
ENV APP_ENV=prod

COPY ./ /srv/cms
COPY ./docker/php-fpm/prod/start-container /usr/local/bin/start-container
COPY ./docker/php-fpm/prod/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/php-fpm/prod/supervisor/conf.d/processes.conf /etc/supervisor/conf.d/processes.conf
COPY ./docker/php-fpm/prod/php.ini /usr/local/etc/php/conf.d/php-ini-overrides.ini
RUN chmod +x /usr/local/bin/start-container

RUN composer dump-autoload -o \
    && chown -R :www-data /srv/cms \
    && chmod -R 775 /srv/cms/storage /srv/cms/bootstrap/cache

ENTRYPOINT ["start-container"]

FROM base as dev

ARG WWWGROUP

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

RUN install-php-extensions xdebug

WORKDIR /srv/cms

COPY ./docker/php-fpm/dev/start-container /usr/local/bin/start-container
COPY ./docker/php-fpm/dev/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php-fpm/dev/php.ini /usr/local/etc/php/conf.d/php-ini-overrides.ini
COPY ./docker/php-fpm/dev/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]