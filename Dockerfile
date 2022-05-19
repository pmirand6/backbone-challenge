# imagen base
FROM php:8.1-fpm-alpine

# definición de los argumentos
ARG USERID=1000
ARG USERNAME=1000

# instalación de composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN set -ex; \
    addgroup -g $USERID $USERNAME &&\
    adduser -G $USERNAME -g $USERNAME -u $USERID -s /bin/sh -D $USERNAME; \
    mkdir -p /app &&\
    chown $USERNAME:$USERNAME /app; \
    # dependencias
    apk add --no-cache $PHPIZE_DEPS \
    gmp \
    libpng \
    libpng-dev \
    zlib \
    zlib-dev \
    zip \
    git \
    curl \
    libxml2-dev \
    libzip-dev; \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    soap \
    zip; \
    pecl install -o -f redis &&\
    pecl install xdebug && \
    docker-php-ext-enable \
    redis \
    xdebug; \
    rm -rf /tmp/pear;

RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del build-dependencies build-base openssl-dev autoconf \
  && rm -rf /var/cache/apk/*;

# Setup GD extension
#RUN apk add --no-cache \
#      freetype \
#      libjpeg-turbo \
#      libpng \
#      freetype-dev \
#      libjpeg-turbo-dev \
#      libpng-dev \
#    && docker-php-ext-configure gd \
#      --with-freetype=/usr/include/ \
#      # --with-png=/usr/include/ \ # No longer necessary as of 7.4; https://github.com/docker-library/php/pull/910#issuecomment-559383597
#      --with-jpeg=/usr/include/ \
#    && docker-php-ext-install -j$(nproc) gd \
#    && docker-php-ext-enable gd \
#    && apk del --no-cache \
#      freetype-dev \
#      libjpeg-turbo-dev \
#      libpng-dev \
#    && rm -rf /tmp/*


COPY ./configs-docker/php.ini /usr/local/etc/php/php.ini
COPY ./configs-docker/fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./configs-docker/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# directorio de trabajo
WORKDIR /app

USER $USERNAME

CMD ["php-fpm"]

EXPOSE 9000
