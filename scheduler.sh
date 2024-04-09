#!/bin/bash

while true
do
   /usr/local/bin/php /var/www/html/artisan subscribe:scheduler
   /bin/sleep 15;
done
