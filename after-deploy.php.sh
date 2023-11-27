#!/bin/bash

function install_website() {
    for i in {1..360} ; do
        FILE=./deploy.npm.pid
        if [ -f "$FILE" ] ; then
            sleep 1
        else
            rm -f ./deploy.pid
            break
        fi
    done
    date | sed 's/$/: CREATE restart supervisorctl pid file/'
    touch supervisor-restart.pid > /dev/null 2>&1 || touch supervisor-restart.pid >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: RUN chmod -R 777 storage/'
    chmod -R 777 storage > /dev/null 2>&1 || chmod -R 777 storage >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: RUN chmod -R 777 public/'
    chmod -R 777 public > /dev/null 2>&1 || chmod -R 777 public >> /var/log/supervisor/laravel-deploy.log
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
    date | sed 's/$/: RUN clear caches/'
    php artisan config:clear
    php artisan event:clear
    php artisan route:clear
    php artisan route:clear
    php artisan queue:clear
    php artisan schedule:clear-cache
    php artisan up
}

for i in {1..60} ; do
    FILE=./deploy.npm.pid
    if [ -f "$FILE" ] ; then
        install_website
    else
        sleep 1
    fi
done
