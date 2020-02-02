# VseInstrumenti.ru Test

## Info

App: [http://localhost:8080](http://localhost:8080)

Documentation: [http://localhost:8080/docs/](http://localhost:8080/docs/)

## Installation

- Install docker [https://docs.docker.com/install/](https://docs.docker.com/install/)
- Install docker-compose [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)
- Clone repository`git clone git@github.com:avoropaev/vseinstrumenti-test.git`
- Change directory `cd vseinstrumenti-test`
- Init project `make init`
- Check documentation [http://localhost:8080/docs/](http://localhost:8080/docs/)

## Commands

Environment: 
- Init project: `make init`
- Docker up: `make up`
- Docker down: `make down`
- Docker down + up: `make restart`

App:
- Composer install: `make api-composer-install`
- Composer update: `make api-composer-update`
- Generate docs: `make api-docs-generate`
- Generate migrations: `make api-migrations-generate`
- Run migrations: `make api-migrations-migrate`
- Run before commit: `make api-check`

Analyze code:
- php_lint + php_codesniffer: `make api-lint`
- psalm: `make api-analyze`

Tests:
- All tests: `make api-test`
- Unit tests: `make api-test-unit`
- All tests + coverage: `make api-test-coverage`
- Unit tests + coverage: `make api-test-unit-coverage`

> Path to code coverage: `/api/var/coverage`