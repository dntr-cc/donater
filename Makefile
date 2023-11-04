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

.PHONY: ps
ps:
	docker-compose ps

.PHONY: stop
stop:
	docker-compose stop

.PHONY: rebuild
rebuild: stop
	docker-compose rm laravel
	echo 'y' | docker rmi -f ghcr.io/setnemo/php:latest
	docker-compose up -d

.PHONY: bash
bash:
	docker-compose exec laravel bash

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

.PHONY: clear
clear:
	docker-compose exec laravel php artisan cache:clear
	docker-compose exec laravel php artisan config:clear
	docker-compose exec laravel php artisan event:clear
	docker-compose exec laravel php artisan route:clear
	docker-compose exec laravel php artisan view:clear

.PHONY: install-all
install-all:
	docker-compose exec laravel composer install
	docker-compose exec laravel npm install
	docker-compose exec laravel npm run production

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
	echo 'y' | docker rmi -f ghcr.io/setnemo/php:latest
	docker-compose -f ./docker-compose.dev.yml up -d

.PHONY: bash-dev
bash-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel bash

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
	docker-compose -f ./docker-compose.dev.yml exec laravel php artisan view:clear

.PHONY: install-all-dev
install-all-dev:
	docker-compose -f ./docker-compose.dev.yml exec laravel composer install
	docker-compose -f ./docker-compose.dev.yml exec laravel npm install
	docker-compose -f ./docker-compose.dev.yml exec laravel npm run production

.PHONY: default
default: up

%:
	@:

