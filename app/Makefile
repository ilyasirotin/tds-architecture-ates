-include .env
-include ./auth/.env

DOCKER_COMPOSE ?= docker compose

OAUTH_KEYS_ROOT=./auth/var/keys
OAUTH_PRIVATE_KEY=./auth/var/keys/private.key
OAUTH_PUBLIC_KEY=./auth/var/keys/public.key

make-env:
	@cp -n .env.tmpl .env
	@cp -n ./auth/.env.tmpl ./auth/.env
	@cp -n ./tasks/.env.tmpl ./tasks/.env
	@cp -n ./billing/.env.tmpl ./billing/.env
.PHONY: make-env

doctrine-migrate:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
	$(DOCKER_COMPOSE) exec tasks-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
	$(DOCKER_COMPOSE) exec billing-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
.PHONY: doctrine-migrate

oauth2-gen-keys:
	@rm -rf $(OAUTH_KEYS_ROOT) && mkdir $(OAUTH_KEYS_ROOT)
	openssl genrsa -out $(OAUTH_PRIVATE_KEY) 2048
	openssl rsa -in $(OAUTH_PRIVATE_KEY) -pubout -out $(OAUTH_PUBLIC_KEY)
.PHONY: oauth2-gen-keys

oauth2-create-clients:
	@echo "TASKS SERVICE OAUTH2 CLIENT CREDENTIALS:"
	@$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console league:oauth2-server:create-client \
		--redirect-uri=http://tasks.localhost/login/callback \
		--grant-type=authorization_code --scope='profile' \
        tasks_auth_client
	@echo "BILLING SERVICE OAUTH2 CLIENT CREDENTIALS:"
	@$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console league:oauth2-server:create-client \
		--redirect-uri=http://billing.localhost/login/callback \
		--grant-type=authorization_code --scope='profile' \
        billing_auth_client
.PHONY: oauth2-create-clients

fixtures-load:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console doctrine:fixtures:load --append
.PHONY: fixtures-load

bootstrap-app:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console app:create-users
.PHONY: bootstrap-app

composer-install:
	$(DOCKER_COMPOSE) run --rm auth-php-fpm composer install
	$(DOCKER_COMPOSE) run --rm tasks-php-fpm composer install
	$(DOCKER_COMPOSE) run --rm billing-php-fpm composer install
.PHONY: composer-install

start-consumers:
	$(DOCKER_COMPOSE) exec -d tasks-php-fpm supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
	$(DOCKER_COMPOSE) exec -d billing-php-fpm supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
.PHONY: start-consumers

build:
	$(DOCKER_COMPOSE) build && docker image prune --force
.PHONY: build

up:
	$(DOCKER_COMPOSE) up -d
.PHONY: up

down:
	$(DOCKER_COMPOSE) down
.PHONY: down

down-clear:
	$(DOCKER_COMPOSE) down -v --remove-orphans
.PHONY: down-clear

init: build up composer-install doctrine-migrate fixtures-load start-consumers bootstrap-app
.PHONY: init

ssh-auth:
	$(DOCKER_COMPOSE) exec -it auth-php-fpm sh
.PHONY: ssh-auth

ssh-tasks:
	$(DOCKER_COMPOSE) exec -it tasks-php-fpm sh
.PHONY: ssh-tasks

ssh-billing:
	$(DOCKER_COMPOSE) exec -it billing-php-fpm sh
.PHONY: ssh-billing
