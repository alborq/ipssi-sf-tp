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
	docker-compose build \
	&& docker-compose up -d \
	&& composer install \
	&& docker-compose exec app php bin/console d:d:c --if-not-exists \
	&& docker-compose exec app php bin/console m:m \
	&& docker-compose exec app php bin/console h:f:l

.PHONY: exec ## Permet de se connecter a l'intérieur du container app
exec:
	docker-compose exec app sh

.PHONY: tests ## Lance les tests de l'applications
tests:
	vendor/bin/phpcs src
	vendor/bin/phpstan analyse --level 6 src

.PHONY: tests-fix ## Fix le cs de mon app
fix:
	vendor/bin/phpcbf src