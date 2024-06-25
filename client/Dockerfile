FROM php:8.0.18-cli-alpine3.15 as compile

ENV PHP_SWOOLE_VERSION=v4.8.12

RUN \
  apk add --no-cache --virtual .deps \
  make \
  automake \
  autoconf \
  gcc \
  g++ \
  git \
  openssl-dev

RUN docker-php-ext-install sockets

FROM compile AS swoole

RUN \
  git clone --depth 1 --branch $PHP_SWOOLE_VERSION https://github.com/swoole/swoole-src.git && \
  cd swoole-src && \
  phpize && \
  ./configure --enable-sockets --enable-http2 --enable-openssl && \
  make && make install && \
  cd ..

FROM php:8.0.18-cli-alpine3.15 as final
# This fixes the problem:
# FROM phpswoole/swoole:4.8.12-php8.0-alpine as final

RUN \
  apk update \
  && apk add --no-cache --virtual .deps \
  make \
  automake \
  autoconf \
  gcc \
  g++ \
  curl-dev \
  && apk add --no-cache \
  libstdc++ \
  docker-cli \
  && docker-php-ext-install sockets \
  && apk del .deps \
  && rm -rf /var/cache/apk/*

WORKDIR /usr/local/
COPY ./ /usr/local

COPY --from=swoole /usr/local/lib/php/extensions/no-debug-non-zts-20200930/swoole.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/
RUN echo extension=swoole.so >> /usr/local/etc/php/conf.d/swoole.ini

EXPOSE 5000

CMD [ "php", "http.php" ]