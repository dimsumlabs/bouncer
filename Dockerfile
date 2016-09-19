FROM php:5.6-apache
RUN apt-get update && apt-get install -y libzmq3-dev 
RUN pecl install zmq-beta && echo "extension=zmq.so" > /usr/local/etc/php/conf.d/zeromq.ini 
COPY . /var/www/html/
MKDIR /var/bouncer/
