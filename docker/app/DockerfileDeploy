FROM php:7.4-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/

# composerをインストールする
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# パッケージをインストールする
RUN apt-get update \
  && apt-get install -y git zip unzip \
  && apt-get install -y libicu-dev \
  && apt-get install -y libonig-dev \
  && apt-get install -y sqlite3 \
  && docker-php-ext-install pdo_mysql intl

# Laravelで必要になるmodRewriteを有効化する
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

# htmlディレクトリの読み書き権限を設定する
RUN chmod -R 777 /var/www/html

# 作業ディレクトリを指定する
WORKDIR /var/www/html/laravelapp
