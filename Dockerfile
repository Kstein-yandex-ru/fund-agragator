#syntax=docker/dockerfile:1.4

# Versions
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


# Base FrankenPHP image
FROM frankenphp_upstream AS frankenphp_base

# UID/GID - нужны для того, чтобы у подключаемых через volume дитекторий был корректный владелец. Поскольку контейнер
# будет запускаться не под рутом, права нужно установить заранее, на этапе сборки. Соответственно, сборка должна
# производиться с тем же пользователем, под которым будет запускаться контейнер.

ARG UID
ARG GID

# Create user with the same identificator as the host user
# -U, --user-group  Create a group with the same name as the user, and add the user to this group.
# -m, --create-home Create the user's home directory if it does not exist.
RUN useradd -m -u ${UID} --user-group host-user

# set owner to caddy and composer directories as we run the container as non-root user

RUN mkdir -p /config && chown -R host-user:host-user /config
RUN mkdir -p /data && chown -R host-user:host-user /data

RUN mkdir -p /.cache/composer && chown -R host-user:host-user /.cache/composer

WORKDIR /app

RUN rm -f /etc/apt/apt.conf.d/docker-clean; echo 'Binary::apt::APT::Keep-Downloaded-Packages "true";' > /etc/apt/apt.conf.d/keep-cache

# persistent / runtime deps
# hadolint ignore=DL3008
RUN --mount=id=debian12_apt_lib,target=/var/lib/apt,type=cache,sharing=locked \
    --mount=id=debian12_apt_cache,target=/var/cache/apt,type=cache,sharing=locked \
	apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
	;


###> recipes ###
###> doctrine/doctrine-bundle ###
RUN install-php-extensions pdo_pgsql
###< doctrine/doctrine-bundle ###
###< recipes ###

COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# Dev FrankenPHP image
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev XDEBUG_MODE=off

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Uncomment if xdebug is needed
# RUN set -eux; \
# 	install-php-extensions \
# 		xdebug \
# 	;

COPY --link frankenphp/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/

USER host-user

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

# Prod FrankenPHP image

# Комментирую, поскольку не тестировал и после моих изменений для запуска из-под пользователя могут потребоваться
# какие-то доработки. Так что когда до дела дойдет - можно раскоммментировать и проверить/доработать.

# FROM frankenphp_base AS frankenphp_prod

# ENV APP_ENV=prod
# ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# COPY --link frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
# COPY --link frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

# # prevent the reinstallation of vendors at every changes in the source code
# COPY --link composer.* symfony.* ./
# RUN set -eux; \
# 	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# # copy sources
# COPY --link . ./
# RUN rm -Rf frankenphp/

# RUN set -eux; \
# 	mkdir -p var/cache var/log; \
# 	composer dump-autoload --classmap-authoritative --no-dev; \
# 	composer dump-env prod; \
# 	composer run-script --no-dev post-install-cmd; \
# 	chmod +x bin/console; sync;
