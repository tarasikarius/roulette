SHELL := /bin/bash
POSTGRES_USER := roulette
POSTGRES_DB := roulette

up:
	docker-compose up -d
	make setup_tests
stop:
	docker-compose stop
down:
	docker-compose stop
	docker-compose down
.PHONY: up stop down

setup_tests:
	bin/console doctrine:database:create -etest
	bin/console doctrine:schema:update --force -etest
	bin/console doctrine:fixtures:load -n -etest
test:
	bin/phpunit
.PHONY: setup_tests test

psql:
	docker exec -it test_roulette_db_1 psql -U ${POSTGRES_USER} ${POSTGRES_DB}
.PHONY: psql



