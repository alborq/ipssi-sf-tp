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
start:
	docker-compose up -d

.PHONY: exec #Permet de se connecter à l'interieur du container
exec:
	docker-compose exec -u 1000:1000 app bash

.PHONY: tests ##Lancer les tests de l'application
tests:
	phpcs src 
	phpstan analyse --level 6 src

.PHONY: tests-fix ## Fix le cs de mon appli
tests-fix:
	phpcbf src
