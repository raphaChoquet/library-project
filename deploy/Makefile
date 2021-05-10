include .env

DOCK=docker-compose -p $(APP_NAME)
EXEC_NGINX=$(DOCK) exec nginx
EXEC_PHP=$(DOCK) exec php
EXEC_DATABASE=$(DOCK) exec database

# Default cmd
build: ## Docker - Build Image
	$(DOCK) build

up: ## Docker - Up
	$(DOCK) up -d

kill: ## Docker - Kill
	$(DOCK) kill

remove: ## Docker - Remove
	$(DOCK) rm -vf

stop: kill remove

# Shell
db-shell: ## Base Create SF DB
	$(EXEC_PGSQL) /bin/bash

php-shell: ## Base Create SF DB
	$(EXEC_PHP) /bin/bash

nginx-shell: ## Base Create SF DB
	$(EXEC_NGINX) /bin/bash

# install
init: up composer-install
	$(EXEC_PHP) bin/console doctrine:database:create
	$(EXEC_PHP) bin/console doctrine:migration:migrate -n
	$(EXEC_PHP) bin/console hautelook:fixtures:load -n
	$(EXEC_PHP) chmod -R 777 ./public/media/

# PHP command
composer-install:
	$(EXEC_PHP) composer install

composer-update:
	$(EXEC_PHP) composer update
