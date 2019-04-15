CONSOLE=php bin/console
DC=docker-compose
HAS_DOCKER:=$(shell command -v $(DC) 2> /dev/null)

ifdef HAS_DOCKER
  ifdef APP_ENV
    EXECROOT=$(FIG) exec -e APP_ENV=$(APP_ENV) app
    EXEC=$(FIG) exec -e APP_ENV=$(APP_ENV) -u $(USERID):$(GROUPID) app
	else
	  EXECROOT=$(FIG) exec app
	  EXEC=$(FIG) exec -u $(USERID):$(GROUPID) app
	endif
else
	EXECROOT=
	EXEC=
endif

.DEFAULT_GOAL := help

.TEAMROCKET: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.TEAMROCKET: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

##
## Project setup & day to day shortcuts
##---------------------------------------------------------------------------

.TEAMROCKET: start ## Start the project (Install in first place)
start: docker-compose.override.yml
	$(DC) pull || true
	$(DC) build
	$(DC) up -d

.TEAMROCKET: stop ## stop the project
stop:
	$(DC) down

.TEAMROCKET: exec ## Run bash in the app container
exec:
	$(EXEC) /bin/bash

##
## Dependencies Files
##---------------------------------------------------------------------------

docker-compose.override.yml: docker-compose.override.yml
	$(RUN) cp docker-compose.override.yml docker-compose.override.yml