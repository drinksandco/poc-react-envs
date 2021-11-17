FROM php:8.0

RUN apt-get update && apt-get install -y git zip zlib1g-dev libicu-dev g++ libpq-dev libxml2-dev autoconf pkg-config libssh-dev libonig-dev

RUN docker-php-ext-install pdo_pgsql bcmath iconv pcntl mbstring intl calendar sockets

RUN git clone https://github.com/amphp/ext-fiber \
  && cd ext-fiber && phpize && ./configure && make && make install \
  && docker-php-ext-enable fiber

#RUN apt-get update && apt-get install -y libev-dev
#RUN pecl install -o -f ev \
#  && rm -rf /tmp/pear \
#  && docker-php-ext-enable ev

RUN usermod -u 1000 www-data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
