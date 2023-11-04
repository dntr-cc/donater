#!/bin/bash

function install_website() {
    date | sed 's/$/: RUN restart supervisorctl/'
    supervisorctl restart scheduler > /dev/null 2>&1 || supervisorctl restart scheduler >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: RUN chmod -R 777 storage/'
    chmod -R 777 storage > /dev/null 2>&1 || chmod -R 777 storage >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: RUN updating files owner/'
    chown -R nginx:nginx ./ > /dev/null 2>&1 || chown -R nginx:nginx ./ >> /var/log/supervisor/laravel-deploy.log
    if [ $(md5sum composer.lock | awk '{ print $1 }') == $(cat composer.md5) ] ; then
        date | sed 's/$/: SKIP composer install/'
    else
        date | sed 's/$/: RUN composer install/'
        composer install > /dev/null 2>&1 || composer install >> /var/log/supervisor/laravel-deploy.log
        md5sum composer.lock | awk '{ print $1 }' > composer.md5
    fi
    if [ $(tar -cf - database/migrations | md5sum | awk '{ print $1 }') == $(cat migrations.md5) ] ; then
        date | sed 's/$/: SKIP migrations/'
    else
        date | sed 's/$/: RUN migrations/'
        php artisan migrate --force > /dev/null 2>&1 || php artisan migrate --force >> /var/log/supervisor/laravel-deploy.log
        tar -cf - database/migrations | md5sum | awk '{ print $1 }' > migrations.md5
    fi
    if [ $(md5sum package-lock.json | awk '{ print $1 }') == $(cat npm.md5) ] ; then
        date | sed 's/$/: SKIP npm install/'
    else
        date | sed 's/$/: RUN npm install/'
        npm i --quiet --no-progress > /dev/null 2>&1 || npm i --quiet --no-progress >> /var/log/supervisor/laravel-deploy.log
        md5sum package-lock.json | awk '{ print $1 }' > npm.md5
    fi
    date | sed 's/$/: RUN npm run production/'
    npm run prod --silent > /dev/null 2>&1 || npm run prod --silent >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: RUN clear caches/'
    php artisan cache:clear
    php artisan config:clear
    php artisan event:clear
    php artisan route:clear
    php artisan view:clear
    date | sed 's/$/: RUN disable maintanance mode/'
    php artisan up > /dev/null 2>&1 || php artisan up >> /var/log/supervisor/laravel-deploy.log
    rm -f ./deploy.pid
}

for i in {1..60} ; do
    FILE=./deploy.pid
    if [ -f "$FILE" ] ; then
        install_website
    else
        sleep 1
    fi
done
