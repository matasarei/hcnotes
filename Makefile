# hcnotes — deploy helper.
#
#   make            # same as `make update`
#   make update     # fetch + pull + clear cache + re-index content, in one run
#
# App commands run inside the running php container via docker compose.
# On the server the env (APP_ENV=prod) comes from .env.local, so cache:clear
# targets the right environment automatically.

EXEC := docker compose exec -T php

.DEFAULT_GOAL := update
.PHONY: update fetch pull cache-clear sync up install help

update: fetch pull cache-clear sync ## Pull latest code and refresh the running site
	@echo "✓ hcnotes updated"

fetch: ## Fetch latest refs from origin
	git fetch --prune origin

pull: ## Fast-forward the working tree to origin
	git pull --ff-only

cache-clear: ## Clear & warm the Symfony cache, restore var/ ownership
	$(EXEC) php bin/console cache:clear
	$(EXEC) php bin/console cache:warmup
	-$(EXEC) chown -R www-data:www-data var

sync: ## Re-index /content into the SQLite database
	$(EXEC) php bin/console app:sync

up: ## Build & (re)start the containers
	docker compose up -d --build

install: ## Install PHP dependencies (production)
	$(EXEC) composer install --no-dev --optimize-autoloader

help: ## List available targets
	@grep -E '^[a-zA-Z_-]+:.*## ' $(MAKEFILE_LIST) \
		| awk 'BEGIN{FS=":.*## "}{printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'
