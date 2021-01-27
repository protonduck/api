FROM php:7.2.11-fpm

RUN apt-get update
RUN apt-get -y install gcc make build-essential autoconf libssl-dev \
  git bash nano wget libc-dev pkg-config librabbitmq-dev libmcrypt-dev zlib1g-dev libicu-dev g++ libpng-dev zip \
            curl \
            imagemagick \
            libfreetype6-dev \
            libcurl3-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libmagickwand-dev \
            libpq-dev \
            libxml2-dev \
            libzip-dev \
            mysql-client \
            openssh-client \
            unzip
RUN pecl install mcrypt-1.0.1

# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl
# json ldap mbstring mcrypt mssql mysql mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci
# pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap
# sockets spl standard sybase_ct sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip

# Install phpunit
RUN wget https://phar.phpunit.de/phpunit-6.0.phar && \
        chmod +x phpunit-6.0.phar && \
        mv phpunit-6.0.phar /usr/local/bin/phpunit

RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pdo_pgsql \
        pcntl \
        sockets

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

# Add GITHUB_API_TOKEN support for composer
RUN chmod 700 \
        /usr/local/bin/docker-php-entrypoint \
        /usr/local/bin/composer

# Install Yii framework bash autocompletion
RUN curl -L https://raw.githubusercontent.com/yiisoft/yii2/master/contrib/completion/bash/yii \
        -o /etc/bash_completion.d/yii

RUN echo "php_admin_value[error_reporting] = E_ALL & ~E_STRICT & ~E_DEPRECATED" >> /usr/local/etc/php-fpm.d/www.conf \
 && echo "php_admin_value[display_startup_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
 && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
 && echo "php_flag[display_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
 && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
 && mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install node.js
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
 && apt-get install -y nodejs

VOLUME ["/var/www"]
WORKDIR /var/www
