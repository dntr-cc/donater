#!/bin/bash

function install_website() {
    if [ $(md5sum package-lock.json | awk '{ print $1 }') == $(cat npm.md5) ] ; then
        date | sed 's/$/: SKIP npm install/'
    else
        date | sed 's/$/: RUN npm install/'
        npm i --quiet --no-progress > /dev/null 2>&1 || npm i --quiet --no-progress >> /var/log/supervisor/laravel-deploy.log
        md5sum package-lock.json | awk '{ print $1 }' > npm.md5
    fi
    date | sed 's/$/: RUN npm run build/'
    npm run build --silent > /dev/null 2>&1 || npm run build --silent >> /var/log/supervisor/laravel-deploy.log
    date | sed 's/$/: FINISH NODE DEPLOY STEPS/'
}

for i in {1..360} ; do
    FILE=./deploy.pid
    if [ -f "$FILE" ] ; then
        install_website
        touch ./deploy.php.pid
        sleep 60
    else
        sleep 1
    fi
done
