SHELL := $(shell which bash)
.SHELLFLAGS = -c

ARGS = $(filter-out $@,$(MAKECMDGOALS))

.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
Makefile: ;              # skip prerequisite discovery
.DEFAULT_GOAL := default

.PHONY: up
up:
	docker-compose up -d

.PHONY: env
env:
	cp .env.example .env

.PHONY: ps
ps:
	docker-compose ps

.PHONY: stop
stop:
	docker-compose stop

.PHONY: rebuild
rebuild: stop
	docker-compose rm laravel
	docker-compose rm node
	docker-compose rm nginx
	echo 'y' > docker rmi -f ghcr.io/setnemo/php:latest
	echo 'y' > docker rmi -f ghcr.io/setnemo/nginx:latest
	echo 'y' > docker rmi -f ghcr.io/setnemo/node:latest
	docker-compose up -d

.PHONY: bash
bash:
	docker-compose exec laravel bash

.PHONY: front
front:
	docker-compose exec node npm run build

.PHONY: node
node:
	docker-compose exec node bash

.PHONY: vite
vite:
	docker-compose exec node npm run dev -- --host

.PHONY: folders
folders:
	docker-compose exec laravel chmod -R 777 /var/www/html/storage && echo "Make writeable storage..."
	docker-compose exec laravel chmod -R 777 /var/www/html/bootstrap && echo "Make writeable bootstrap..."

.PHONY: logs
logs:
	docker-compose logs -f

.PHONY: restart
restart:
	docker-compose stop && docker-compose up -d

.PHONY: artisan
artisan:
	docker-compose exec laravel php artisan ${ARGS}

.PHONY: migrate
migrate:
	docker-compose exec laravel php artisan migrate

.PHONY: localdb
localdb:
	cat tests/data/init.sql | docker-compose exec -T postgres psql -Upostgres

.PHONY: clear
clear:
	docker-compose exec laravel php artisan cache:clear
	docker-compose exec laravel php artisan config:clear
	docker-compose exec laravel php artisan event:clear
	docker-compose exec laravel php artisan route:clear
	docker-compose exec laravel php artisan route:clear
	docker-compose exec laravel php artisan queue:clear
	docker-compose exec laravel php artisan schedule:clear-cache
	docker-compose exec laravel php artisan optimize:clear

.PHONY: install
install:
	docker-compose exec laravel composer install
	docker-compose exec node npm install

.PHONY: chown
chown:
    sudo chown -R "${USER}:${USER}" ./

.PHONY: up-dev
up-dev:
	docker-compose -f ./docker-compose.dev.yml up -d

.PHONY: ps-dev
ps-dev:
	docker-compose -f ./docker-compose.dev.yml ps

.PHONY: stop-dev
stop-dev:
	docker-compose -f ./docker-compose.dev.yml stop

.PHONY: rebuild-dev
rebuild-dev: stop
	docker-compose -f ./docker-compose.dev.yml rm laravel
	docker-compose -f ./docker-compose.dev.yml rm node
	docker-compose -f ./docker-compose.dev.yml rm nginx
	echo 'y' > docker rmi -f ghcr.io/setnemo/php:latest
	echo 'y' > docker rmi -f ghcr.io/setnemo/nginx:latest
	echo 'y' > docker rmi -f ghcr.io/setnemo/node:latest
	docker-compose -f ./docker-compose.dev.yml up -d

.PHONY: bash-dev
bash-dev:
	docker-compose  --user root -f ./docker-compose.dev.yml exec laravel bash

.PHONY: logs-dev
logs-dev:
	docker-compose -f ./docker-compose.dev.yml logs -f

.PHONY: restart-dev
restart-dev:
	docker-compose -f ./docker-compose.dev.yml stop && docker-compose -f ./docker-compose.dev.yml up -d

.PHONY: artisan-dev
artisan-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan ${ARGS}

.PHONY: migrate-dev
migrate-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan migrate

.PHONY: clear-dev
clear-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan cache:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan config:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan event:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan route:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan route:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan queue:clear
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan schedule:clear-cache
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan optimize:clear

.PHONY: install-dev
install-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel composer install
	docker-compose -f ./docker-compose.dev.yml exec node npm install

.41+PHONY: front-dev
front-dev:
	docker-compose -f ./docker-compose.dev.yml exec exec node npm run build

.PHONY: folders-dev
folders-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel chmod -R 777 /var/www/html/storage && echo "Make writeable storage..."
	docker-compose -f ./docker-compose.dev.yml exec laravel chmod -R 777 /var/www/html/bootstrap && echo "Make writeable bootstrap..."


.PHONY: default
default: up

%:
	@:

