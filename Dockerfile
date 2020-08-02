FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
  libpq-dev \
  wget \
  zip \
  git

RUN pecl install xdebug-2.9.6 && \
  echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
  echo "xdebug.reemote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini && \
  docker-php-ext-install pdo pdo_mysql pdo_pgsql && \
  docker-php-ext-enable xdebug

RUN rm -rf /var/lib/apt/lists/* && rm -rf /tmp/pear && rm -rf /tmp/* && \
  apt-get clean -y && apt-get autoclean -y

WORKDIR /workspace
