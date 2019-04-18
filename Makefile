.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

.PHONY: start ## Démarre le projet
start:
	docker-compose up -d

.PHONY: exec ## Permet de se connecter a l'intérieur du container app
exec:
	docker-compose exec -u 1000:1000  app bash

.PHONY: tests ## Lance les tests de l'applications
tests:
	vendor/bin/phpcs src
	vendor/bin/phpstan analyse --level 6 src

.PHONY: tests-fix ## Fix le cs de mon app
tests-fix:
	vendor/bin/phpcbf src