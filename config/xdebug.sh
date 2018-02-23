#!/usr/bin/env bash

    pecl install xdebug
    #touch /var/log/xdebug_remote.log && chmod 755 /var/log/xdebug_remote.log
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_host=https://inspi-local.sgpdev.com" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.scream=on" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.show_error_trace=on" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini
