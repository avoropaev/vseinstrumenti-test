init: docker-down-clear docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
restart: down up
lint: api-lint
analyze: api-analyze

docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-down-clear:
	docker-compose down -v --remove-orphans
docker-pull:
	docker-compose pull
docker-build:
	docker-compose build


api-init: api-composer-install api-permissions api-migrations-migrate
api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var

api-check: api-lint api-analyze api-test

api-test:
	docker-compose run --rm api-php-cli composer test
api-test-coverage:
	docker-compose run --rm api-php-cli composer test-coverage
api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit
api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=unit
api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check
api-analyze:
	docker-compose run --rm api-php-cli composer psalm


api-composer-install:
	docker-compose run --rm api-php-cli composer install
api-composer-update:
	docker-compose run --rm api-php-cli composer update


api-migrations-generate:
	docker-compose run --rm api-php-cli composer migrations diff -- --filter-expression '~^(?!orders_seq|products_seq)~'
api-migrations-migrate:
	docker-compose run --rm api-php-cli composer migrations migrate


api-docs-generate:
	docker-compose run --rm api-php-cli composer openapi src/Controller -- --output public/docs/openapi.json


connect-db:
	docker-compose exec api-db psql
