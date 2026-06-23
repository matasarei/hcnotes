# hcnotes — deploy helper.
#
#   make            # same as `make update`
#   make update     # fetch + pull + clear cache + re-index content, in one run
#
# App commands run inside the running php container via docker compose.
# On the server the env (APP_ENV=prod) comes from .env.local, so cache:clear
# targets the right environment automatically.

# Detect container engine and compose command
COMPOSE_CMD := $(shell command -v podman-compose >/dev/null 2>&1 && echo "podman-compose" || (command -v docker-compose >/dev/null 2>&1 && echo "docker-compose" || echo "docker compose"))
ENGINE_CMD := $(shell command -v podman >/dev/null 2>&1 && echo "podman" || echo "docker")

# Define how to execute commands inside the PHP container.
# With podman, exec as root (-u 0) via native podman to avoid rootless permission issues.
ifeq ($(ENGINE_CMD),podman)
EXEC := podman exec -u 0 aicmf_php
else
EXEC := $(COMPOSE_CMD) exec -T php
endif

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
	$(COMPOSE_CMD) up -d --build

install: ## Install PHP dependencies (production)
	$(EXEC) composer install --no-dev --optimize-autoloader

help: ## List available targets
	@grep -E '^[a-zA-Z_-]+:.*## ' $(MAKEFILE_LIST) \
		| awk 'BEGIN{FS=":.*## "}{printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'
