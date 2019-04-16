.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

## test
## -----------------------------

.PHONY: start ## Démarre le projet
start:
	docker-compose up -d

.PHONY: exec ## Permet de se connecter a l'intérieur du container app
exec:
	docker-compose exec -u 1000:1000  app bash


