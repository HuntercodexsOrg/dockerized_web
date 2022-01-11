FROM php:8.0-fpm

ENV DIR_WWW "/var/www/phphunter"

## UPDATE
RUN apt-get update && apt-get upgrade -y \
	libonig-dev \
	libmcrypt-dev \
	zlib1g-dev \
	libpng-dev \
	libxml2-dev

## LIBRARIES
RUN apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev

## Node
RUN apt-get install -y gnupg2
RUN rm -rf /var/lib/apt/lists/ && \
    curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install nodejs -y

## ZIP
RUN apt-get install -y libzip-dev && \
    apt-get install -y zip && \
    docker-php-ext-install zip

## EXTRAS
RUN apt install -y nano
RUN apt install -y wget
RUN apt install -y ufw
RUN apt install -y lsof
RUN apt install -y alien
#RUN apt install -y ldconfig
RUN apt-get install -y libaio1
RUN apt-get install -y tzdata

## ESSENTIAL
RUN apt-get install -y zstd
RUN apt install -y php-common/stable

## PDO
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo pdo_mysql
#RUN docker-php-ext-install pdo pdo_pgsql

## GD
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN docker-php-ext-install gd mysqli
RUN docker-php-ext-install dom
RUN docker-php-ext-install intl

## PHP LIBRARIES
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

## COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

## PDO-SQLSRV (MSSQL) (Ubuntu 20.04)
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update && apt-get upgrade -y
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools
#RUN echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
#RUN source ~/.bashrc
RUN apt-get install -y unixodbc-dev
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/php.ini
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN printf "; priority=20\nextension=sqlsrv.so\n" > /usr/local/etc/php/conf.d/sqlsrv.ini
RUN printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /usr/local/etc/php/conf.d/pdo_sqlsrv.ini
RUN phpenmod sqlsrv pdo_sqlsrv

## POSTGRES
RUN apt install -y postgresql postgresql-contrib
RUN wget https://archlinux.org/packages/extra/x86_64/php-pgsql/download -O php-pgsql-8.0.10-1-x86_64.pkg.tar.zst
RUN tar -I zstd -xvf php-pgsql-8.0.10-1-x86_64.pkg.tar.zst
RUN cp usr/lib/php/modules/pgsql.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/
RUN cp usr/lib/php/modules/pdo_pgsql.so /usr/local/lib/php/extensions/no-debug-non-zts-20200930/
#RUN printf "; priority=40\nextension=pgsql.so\n" > /usr/local/etc/php/conf.d/pgsql.ini
RUN printf "; priority=50\nextension=pdo_pgsql.so\n" > /usr/local/etc/php/conf.d/pdo_pgsql.ini
RUN phpenmod pgsql pdo_pgsql

## MONGODB
RUN pecl install mongodb
RUN printf "; priority=60\nextension=mongodb.so\n" > /usr/local/etc/php/conf.d/mongodb.ini
RUN phpenmod mongodb
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/php.ini
RUN mkdir -p $DIR_WWW
RUN chmod 777 $DIR_WWW
RUN cd $DIR_WWW
RUN composer require mongodb/mongodb

## ORACLE
#configurando as variáveis de ambiente dos drives da oracle
#LINK: https://rosemberg.net.br/pt/php7-com-pdo_oci-e-oci8-no-docker/
#LINK: https://github.com/rosemberg-al/docker-php7-pdo_oci-oci8/blob/master/Dockerfile
#LINK: https://medium.com/@gabrielveigalima/trabalhando-com-python-oracle-database-e-docker-1cb04fc82b46
#LINK: https://github.com/gabrielveigalima/python-oracle-databse-docker/blob/master/Dockerfile

ENV LD_LIBRARY_PATH="/usr/lib/oracle/11.2/client64/lib/:$LD_LIBRARY_PATH"
ENV ORACLE_HOME="/usr/lib/oracle/11.2/client64/"
ENV PATH="/usr/lib/oracle/11.2/:$PATH"

#TODO: Tentar usar baixando os drivers diretamente do PECL
#RUN pecl install oci8
#RUN pecl install pdo_oci

RUN mkdir -p /data/oracle_files/
#COPY ./oracle/instantclient/oracle-instantclient11.2-devel-11.2.0.4.0-1.x86_64.rpm /data/oracle_files/
#COPY ./oracle/instantclient/oracle-instantclient11.2-sqlplus-11.2.0.4.0-1.x86_64.rpm /data/oracle_files/
#COPY ./oracle/instantclient/oracle-instantclient11.2-basic-11.2.0.4.0-1.x86_64.rpm /data/oracle_files/
COPY ./oracle/instantclient/*.rpm /data/oracle_files/
COPY ./oracle/lib /data/oracle_files/

RUN echo "[ALIEN]"
RUN alien -i /data/oracle_files/oracle-instantclient11.2-basic-11.2.0.4.0-1.x86_64.rpm
RUN alien -i /data/oracle_files/oracle-instantclient11.2-sqlplus-11.2.0.4.0-1.x86_64.rpm
RUN alien -i /data/oracle_files/oracle-instantclient11.2-devel-11.2.0.4.0-1.x86_64.rpm
RUN echo "/usr/lib/oracle/11.2/client64/lib" > /etc/ld.so.conf.d/oracle.conf
RUN ldconfig
RUN export ORACLE_HOME
RUN export LD_LIBRARY_PATH
RUN export PATH
#RUN C_INCLUDE_PATH=/usr/include/oracle/11.2/client64 pecl install oci8
#RUN pecl install oci8
#RUN pecl install pdo_oci
#-------------------------------------------------------------------------------------------
RUN ls /data/oracle_files/
RUN cd /data/oracle_files/oci8/  && \
    phpize  && \
    ./configure --with-oci8=instantclient,/usr/lib/oracle/11.2/client64/lib  && \
    make install  && \
#    echo "extension=oci8.so" > /etc/php/7.4/mods-available/oci8.ini   && \
#    ln -s /etc/php/7.4/mods-available/oci8.ini /etc/php/7.4/apache2/conf.d/oci8.ini  && \
#    ln -s /etc/php/7.4/mods-available/oci8.ini /etc/php/7.4/cli/conf.d/oci8.ini  && \
    cd /data/oracle_files/pdo_oci/  && \
    phpize  && \
    ./configure --with-pdo-oci=instantclient,/usr/lib/oracle/11.2/client64/lib  && \
    make install
#    echo "extension=pdo_oci.so" > /etc/php/7.4/mods-available/pdo_oci.ini  && \
#    ln -s /etc/php/7.4/mods-available/pdo_oci.ini /etc/php/7.4/apache2/conf.d/pdo_oci.ini && \
#    ln -s /etc/php/7.4/mods-available/pdo_oci.ini /etc/php/7.4/cli/conf.d/pdo_oci.ini && \
#    cp /data/oracle_files/apache2.conf /etc/apache2/apache2.conf && \
#    a2enmod rewrite && \
#-------------------------------------------------------------------------------------------
RUN printf "; priority=70\nextension=oci8.so\n" > /usr/local/etc/php/conf.d/oci8.ini
RUN printf "; priority=80\nextension=pdo_oci.so\n" > /usr/local/etc/php/conf.d/pdo_oci.ini
RUN phpenmod oci8 pdo_oci
RUN rm -rf /data/oracle_files

RUN groupadd devel -g 999
RUN useradd devel -g devel -d $DIR_WWW

EXPOSE 9000
CMD ["php-fpm"]