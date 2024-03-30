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
    date | sed -e 's/$/: CREATE restart supervisorctl pid file/'
    touch supervisor-restart.pid > /dev/null 2>&1 || touch supervisor-restart.pid >> /var/log/supervisor/laravel-deploy.log
    date | sed -e 's/$/: RUN chmod -R 777 short_url\/storage/'
    chmod -R 777 short_url/storage > /dev/null 2>&1 || chmod -R 777 short_url/storage >> /var/log/supervisor/laravel-deploy.log
    date | sed -e 's/$/: RUN chmod -R 777 storage/'
    chmod -R 777 storage > /dev/null 2>&1 || chmod -R 777 storage >> /var/log/supervisor/laravel-deploy.log
    date | sed -e 's/$/: RUN chmod -R 777 public/'
    chmod -R 777 public > /dev/null 2>&1 || chmod -R 777 public >> /var/log/supervisor/laravel-deploy.log
    date | sed -e 's/$/: RUN chmod -R 777 bootstrap/'
    chmod -R 777 bootstrap > /dev/null 2>&1 || chmod -R 777 bootstrap >> /var/log/supervisor/laravel-deploy.log
    if [ $(md5sum composer.lock | awk '{ print $1 }') == $(cat composer.md5) ] ; then
        date | sed -e 's/$/: SKIP composer install/'
    else
        date | sed -e 's/$/: RUN composer install/'
        composer install --no-dev || composer install --no-dev >> /var/log/supervisor/laravel-deploy.log || echo 'migrations finished with errors'
        md5sum composer.lock | awk '{ print $1 }' > composer.md5
    fi
    if [ $(tar -cf - database/migrations | md5sum | awk '{ print $1 }') == $(cat migrations.md5) ] ; then
        date | sed -e 's/$/: SKIP migrations/'
    else
        date | sed -e 's/$/: RUN migrations/'
        php artisan migrate --force > /dev/null 2>&1 || php artisan migrate --force >> /var/log/supervisor/laravel-deploy.log && echo 'migrations finished with errors'
        tar -cf - database/migrations | md5sum | awk '{ print $1 }' > migrations.md5
    fi
    date | sed -e 's/$/: RUN clear caches/'
    php artisan config:clear
    php artisan event:clear
    php artisan route:clear
    php artisan route:clear
    php artisan queue:clear
    php artisan schedule:clear-cache
    php artisan optimize:clear
    date | sed -e 's/$/: DISABLE maintenance/'
    php artisan up || echo 'already run'
}

for i in {1..60} ; do
    FILE=./deploy.npm.pid
    if [ -f "$FILE" ] ; then
        install_website
    else
        sleep 1
    fi
done
