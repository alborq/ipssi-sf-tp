FIG=docker-compose
EXEC= $(FIG) exec app
HAS_DOCKER:=$(shell command -v $(FIG) 2> /dev/null)
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
## This is how to setup and launch the project :
##---------------------------------------------------------------------------

.PHONY: start ## Start the project with logs
start:
	$(FIG) pull || true
	$(FIG) build
	$(FIG) up -d

.PHONY: exec ## Run bash in the app container
exec:
	$(EXEC) /bin/bash

.PHONY: dup ## stop and restart the project
dup:
	$(FIG) stop
	$(FIG) up -d
	$(EXEC) /bin/bash

.PHONY: stop ## stop and restart the project
stop:
	$(FIG) down

.PHONY: tests ## Test the code
tests:
	$(EXEC) vendor/bin/phpstan analyse src


