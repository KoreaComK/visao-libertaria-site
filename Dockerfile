FROM php:8.0-alpine

RUN apk add --no-cache openrc linux-headers

# PHP dependencies
RUN apk update && apk add --no-cache ${PHPIZE_DEPS} \
    bash\
    curl-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    curl \
    gd \
    simplexml \
    xml \
    mbstring \
    dom \
    intl \
    ctype \
    tokenizer \
    xmlwriter

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# docker-php-ext-extensions
RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions

# Composer
RUN install-php-extensions @composer

# Apache 2
RUN apk add --no-cache apache2
RUN rc-update add apache2
COPY .docker/apache2.conf /etc/apache2/httpd.conf

COPY .docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

COPY . /var/www/localhost/htdocs
WORKDIR /var/www/localhost/htdocs

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]