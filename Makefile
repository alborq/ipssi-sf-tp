.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

.PHONY: startServer ## Start symfony server
startServer:
	php bin/console server:run

.PHONY: fixtures ## Start symfony server
fixtures:
	php bin/console hautelook:fixtures:load

.PHONY: start ## Démarre le projet
start:
	docker-compose pull
	docker-compose build
	docker-compose up -d
	composer install
	php bin/console doctrine:database:create
	php bin/console doctrine:schema:update --force
	php bin/console make:migration
	php bin/console hautelook:fixtures:load -q

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