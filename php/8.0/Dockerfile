FROM php:8.0-fpm

RUN apt-get update && apt-get upgrade -y \
	libonig-dev \
	libmcrypt-dev \
	zlib1g-dev \
	libpng-dev \
	libxml2-dev

## Node
RUN apt-get install -y gnupg2
RUN rm -rf /var/lib/apt/lists/ && curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install nodejs -y

## ZIP
RUN apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

## PDO
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo pdo_mysql

## CONFIGURE
RUN docker-php-ext-configure gd

## GD
RUN docker-php-ext-install gd
RUN docker-php-ext-install gd mysqli
RUN docker-php-ext-install dom
RUN docker-php-ext-install intl

## LIBS
RUN docker-php-ext-install opcache
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install mbstring

## XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN echo "xdebug.mode=develop,coverage,debug,gcstats,profile,trace" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/xdebug.ini

## REDIS
RUN pecl install redis
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini
RUN echo "date.timezone=America/Sao_Paulo" > /usr/local/etc/php/conf.d/timezone_sao_paulo.ini
RUN echo "memory_limit = 1024M" > /usr/local/etc/php/conf.d/memory_limit.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt install nano
RUN apt install wget -y

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m
USER dev

EXPOSE 9000
CMD ["php-fpm"]