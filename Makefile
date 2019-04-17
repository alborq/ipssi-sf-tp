FIG=docker-compose
CONSOLE=bin/console
.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

##
## Project setup & day to day shortcuts
##---------------------------------------------------------------------------

.PHONY: fstart ## Start the project (The first launch of the app)
fstart: docker-compose.override.yml
	$(FIG) pull || true
	$(FIG) build
	$(FIG) up -d
	$(FIG) exec -u 1000:1000 app composer install
	$(FIG) exec -u 1000:1000 app $(CONSOLE) doctrine:database:create

.PHONY: start ## Start the project
start: docker-compose.override.yml
	$(FIG) up -d
	$(FIG) exec -u 1000:1000 app composer install

.PHONY: dup ## restart the project
dup:
	$(FIG) stop
	$(FIG) up -d

.PHONY: stop ## stop the project
stop:
	$(FIG) down

.PHONY: exe ## Run bash in the app container
exe:
	$(FIG) exec -u 1000:1000 app /bin/bash

.PHONY: tests ## Lance les tests de l'applications
tests:
	vendor/bin/phpcs src
	vendor/bin/phpstan analyse --level 6 src
	vendor/bin/phpcbf src

##
## Dependencies Files
##---------------------------------------------------------------------------

docker-compose.override.yml: docker-compose.override.yml.dist
	$(RUN) cp docker-compose.override.yml.dist docker-compose.override.yml
