FROM ubuntu:latest
MAINTAINER Romans <me@nearly.guru>

# Install base packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && \
    apt-get -yq install \
        curl \
        git \
        apache2 \
        libapache2-mod-php5 \
        php5-mysql \
        php5-gd \
        php5-curl \
        php-pear \
        php-apc && \
    rm -rf /var/lib/apt/lists/*

RUN sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php5/apache2/php.ini
RUN sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php5/cli/php.ini
RUN sed -i "s/# StrictHostKeyChecking ask/ StrictHostKeyChecking no/g" /etc/ssh/ssh_config
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite

# Add image configuration and scripts
ADD run.sh /run.sh
RUN chmod 755 /*.sh


# Configure /app folder with sample app
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app/admin/public /var/www/html

# Add application code onbuild
RUN rm -fr /app
ADD . /app
ADD config-dist.php /app/config.php
ADD dam.conf /etc/apache2/sites-enabled/000-default.conf
RUN chown www-data:www-data /app -R
RUN cd /app && composer install
RUN mkdir /var/www/.ssh
RUN chown www-data /var/www/.ssh

# Install or upgrade database
# ONBUILD RUN cd /app && php bootstrap.php

EXPOSE 80
WORKDIR /app
CMD ["/run.sh"]
