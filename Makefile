FIG=docker-compose
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

.PHONY: start ## Start the project (Install in first place)
start: docker-compose.override.yml
	$(FIG) pull || true
	$(FIG) build
	$(FIG) up -d
	$(FIG) exec -u 1000:1000 app composer install

.PHONY: stop ## stop the project
stop:
	$(FIG) down

.PHONY: exec ## Run bash in the app container
exec:
	$(EXEC) /bin/bash

##
## Dependencies Files
##---------------------------------------------------------------------------

docker-compose.override.yml: docker-compose.override.yml.dist
	$(RUN) cp docker-compose.override.yml.dist docker-compose.override.yml
