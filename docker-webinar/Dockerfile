FROM composer:latest AS composer
FROM ubuntu:20.04

WORKDIR /usr/src/webinar

# sets variables that only live during the build
ARG DEBIAN_FRONTEND=noninteractive

# Locale
ENV TZ=Europe/Moscow

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/*
RUN sed -i -e \
  's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen \
   && locale-gen

ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU:ru
ENV LC_LANG ru_RU.UTF-8
ENV LC_ALL ru_RU.UTF-8

# apache2 and utils, redis
RUN apt-get update && apt-get install -y apache2 curl wget git software-properties-common mc net-tools iputils-ping apt-utils && rm -rf /var/lib/apt/lists/*

# php 7.4
RUN add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y php7.4 php7.4-mysql php7.4-sqlite3 \
                       php7.4-mcrypt php7.4-mbstring php7.4-gd php7.4-memcache \
                       libapache2-mod-php7.4 \
                       php7.4-curl php7.4-xml php7.4-soap php7.4-intl php7.4-dom php7.4-xmlrpc php7.4-zip \
    libfreetype6-dev \
    libjpeg62-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
#    redis \
    && rm -rf /var/lib/apt/lists/*

#RUN sed -i 's/;\s*mbstring.func_overload\s*=/mbstring.func_overload = 2/' /etc/php/7.4/apache2/php.ini
#RUN sed -i 's/;mbstring.internal_encoding\s*=/mbstring.internal_encoding = utf-8/' /etc/php/7.4/apache2/php.ini

#RUN sed -i 's/error_reporting.*=.*E_ALL.*&.*~E_DEPRECATED.*&.*~E_STRICT/error_reporting = E_ALL/' /etc/php/7.4/apache2/php.ini
#RUN sed -i 's/display_errors.*=.*Off/display_errors = On/' /etc/php/7.4/apache2/php.ini
#RUN sed -i 's/display_startup_errors.*=.*Off/display_startup_errors = On/' /etc/php/7.4/apache2/php.ini

COPY /7.4/apache2/php.ini /etc/php/7.4/apache2/php.ini
COPY /7.4/cli/php.ini /etc/php/7.4/cli/php.ini
COPY /7.4/mods-available/opcache.ini /etc/php/7.4/mods-available/opcache.ini

# bitrix session
RUN \
    mkdir -p /tmp/php_upload /tmp/php_sessions \
 && chown -R 33:33 /tmp/php_upload /tmp/php_sessions
COPY /tmpfiles.d/bitrix.conf /etc/tmpfiles.d/bitrix.conf

# xdebug 7.4
RUN add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install php7.4-xdebug
#RUN echo 'zend_extension=xdebug\n' >> /etc/php/7.4/apache2/php.ini
RUN echo '\nzend_extension=/usr/lib/php/20190902/xdebug.so\n' >> /etc/php/7.4/cli/php.ini
RUN echo '\nzend_extension=/usr/lib/php/20190902/xdebug.so\n' >> /etc/php/7.4/apache2/php.ini
RUN echo '\
xdebug.show_error_trace = 1\n\
xdebug.remote_autostart = 1\n\
xdebug.remote_enable=1\n\
xdebug.idekey=PHPSTORM\n\
# устанавливается при запуске через --env
# xdebug.remote_host=192.168.100.33\n\
# возвращает под docker ip вида 172.17.0.1
# xdebug.discover_client_host=1\n\
#xdebug.remote_log_level = 10\n\
#xdebug.remote_log = /var/log/xdebug_log\n\
xdebug.remote_port=9000\
' > /etc/php/7.4/mods-available/xdebug.ini
RUN rm -rf /var/lib/apt/lists/*

# composer 
ENV COMPOSER_ALLOW_SUPERUSER 1
#RUN curl -sS https://getcomposer.org/installer | php -- \
#        --filename=composer \
#        --install-dir=/usr/local/bin && \
#        echo "alias composer='composer'" >> /root/.bashrc \
#	&& composer 
COPY --from=composer /usr/bin/composer /usr/bin/composer

# apache vhost
RUN cd /var/www \
 && mkdir -p -m 775 /var/www/webinar \
 && chown -R 1000:33 /var/www/webinar

ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_LOG_DIR=/var/log/apache2
ENV APACHE_LOCK_DIR=/var/lock/apache2
ENV APACHE_PID_FILE=/var/run/apache2.pid
ENV APACHE_RUN_DIR=/var/run/apache2

RUN mkdir -p -m 775 /var/log/apache2/webinar \
 && chown -R 33:33 /var/log/apache2/webinar \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY .htaccess /var/www/html
COPY mpm.conf /etc/apache2/conf-available
COPY webinar.conf /etc/apache2/sites-available

RUN a2disconf other-vhosts-access-log
#RUN a2enconf mpm
RUN a2enmod rewrite
RUN a2ensite webinar
RUN a2dissite 000-default

RUN sed -i 's/<VirtualHost\ \*:80>/<VirtualHost\ 62.109.24.91:80>/' /etc/apache2/sites-available/000-default.conf
RUN sed -i '/DocumentRoot/ a <Directory\ \/var\/www\/html>\n AllowOverride\ All \n<\/Directory>' /etc/apache2/sites-available/000-default.conf

RUN a2ensite 000-default
RUN service apache2 restart

CMD ["apachectl", "-DFOREGROUND"]
