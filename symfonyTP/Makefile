.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14
.PHONY: start ## Start the project (Install in first place)
start:
    docker-compose up -d

.PHONY: exec ###Permet d se connecter dans le container
exec:
    docker-compose exec -u 1000:1000 app bash

.PHONY: test ##Lance les tests de l'app
tests:
    vendor/bin/phpcs src
    vendor/bin/phpstan analyse ---level 6 src

.PHONY: tests-fix ##Fixe le cs de l'app
tests-fix:
    vendor/bin/phpcbf src