#!/bin/bash

while true
do
   /usr/local/bin/php /var/www/html/artisan schedule:run
   /bin/sleep 10;
done
