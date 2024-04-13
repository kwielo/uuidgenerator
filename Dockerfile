FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY composer.lock .
COPY composer.json .
COPY config config/
COPY public public/
COPY src src/
# COPY nginx-site.conf conf/nginx/
#COPY vendor vendor/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN php composer.phar install -o
