# defines variables
include Make.config

.PHONY : all help install up down tests db db-test fix phpstan migrate

all: help

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-14s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	$(DC) exec web composer install

up:	## Up web, nginx and db containers
	$(DC) up -d

down:	## Down all containers
	$(DC) down

fix:	## Run code style fixer (php-cs-fixer)
	$(COMPOSER) run fix

phpstan:	## Run static code analysis (PHPStan)
	$(COMPOSER) run phpstan

db:	## Create migration table
	$(ARTISAN) migrate:install

migrate:	## Run migration
	$(ARTISAN) migrate

db-test:	## Prepare DB for tests
	$(DC) exec -u postgres postgres createdb postgres_test
	$(ARTISAN) migrate --env=testing
	$(ARTISAN) db:seed --env=testing
