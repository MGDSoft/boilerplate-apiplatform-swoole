# Makefile for boilerplate
# vim: set ft=make ts=8 noet

# Variables
# UNAME		:= $(shell uname -s)

.EXPORT_ALL_VARIABLES:

# this is godly
# https://news.ycombinator.com/item?id=11939200
.PHONY: help
help:	### this screen. Keep it first target to be default
ifeq ($(UNAME), Linux)
	@grep -P '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
else
	@# this is not tested, but prepared in advance for you, Mac drivers
	@awk -F ':.*###' '$$0 ~ FS {printf "%15s%s\n", $$1 ":", $$2}' \
		$(MAKEFILE_LIST) | grep -v '@awk' | sort
endif

.PHONY: app-run-fresh
app-run-fresh: docker-build-no-cache git-pre-commit-conf docker-up composer-install symfony-setup  ### Install everything and up

.PHONY: app-run
app-run: git-pre-commit-conf docker-up ### Run without build

.PHONY: app-test
app-test: ### Execute tests
	@docker-compose exec backend php bin/phpunit

.PHONY: composer-install
composer-install: ### Composer install
	@docker-compose exec -T backend composer install

.PHONY: symfony-setup
symfony-setup: ### Configure environment
	@docker-compose exec -T backend php bin/console doc:sc:up --force
	@docker-compose exec -T backend php bin/console doc:sc:vali

.PHONY: docker-build-no-cache
docker-build-no-cache: ### Docker build no--cache
	@docker-compose build --no-cache

.PHONY: docker-up
docker-up: ### Docker up
	@docker-compose up -d

.PHONY: git-pre-commit-conf
git-pre-commit-conf: ### Configure git pre-commit
	mkdir -p .git/hooks/ && cp git-pre-commit.sh .git/hooks/pre-commit -f && chmod +x .git/hooks/pre-commit

.PHONY: git-pre-commit-check
git-pre-commit-check: git-pre-commit-conf ### Execute git pre-commit
	@./.git/hooks/pre-commit check

