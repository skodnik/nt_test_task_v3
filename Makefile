#!/usr/bin/make

# Makefile readme (ru): http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html
# Makefile readme (en): https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents

CURRENT_DIR := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
CURRENT_TIME = $(shell date +"%F_%k-%M-%S")

.PHONY: help

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[.a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# phpunit
test: ## Run tests
	$(CURRENT_DIR)/vendor/bin/phpunit

# service
reset.db: ## Reset databases
	rm $(CURRENT_DIR)/tests/database_test.sqlite && touch $(CURRENT_DIR)/tests/database_test.sqlite

# doctrine
orm.schema.info:
	$(CURRENT_DIR)/vendor/bin/doctrine orm:info

orm.schema.validate:
	$(CURRENT_DIR)/vendor/bin/doctrine orm:validate-schema

orm.schema.create:
	$(CURRENT_DIR)/vendor/bin/doctrine orm:schema-tool:create

orm.schema.destroy:
	$(CURRENT_DIR)/vendor/bin/doctrine orm:schema-tool:drop --force

orm.schema.update:
	$(CURRENT_DIR)/vendor/bin/doctrine orm:schema-tool:update --force