FROM php:7.2-apache

RUN apt-get update \
    && apt-get install -y apt-utils \
    && apt-get install -y build-essential \
    && pecl install xdebug \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && apt-get install -y unzip libaio-dev libmcrypt-dev \
    && apt-get install -y libsodium-dev \
    && apt-get clean -y \
    && apt-get install -y nano

ADD /config/php.ini /usr/local/etc/php/
ADD /config/000-default.conf /etc/apache2/sites-available

# Setup the custom configuration
ADD /config/mysql/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

# Add composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer


COPY . /var/www/html
COPY config/php.ini /usr/local/etc/php/
WORKDIR /var/www/html/public
EXPOSE 80 3306

#COPY config/config.sh /usr/local/bin/config.sh
#RUN cd /usr/local/bin && ./config.sh

#CMD [ "php", "./public/index.php" ]

