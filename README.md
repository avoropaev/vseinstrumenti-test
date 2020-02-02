# VseInstrumenti.ru Test

## Info

App: [http://localhost:8080](http://localhost:8080)

Documentation: [http://localhost:8080/docs/](http://localhost:8080/docs/)

## Commands

Environment: 
- Init project: `make init`
- Docker up: `make up`
- Docker down: `make down`
- Docker down + up: `make restart`

App:
- Composer install: `make api-composer-install`
- Composer update: `make api-composer--update`
- Generate docs: `make docs`
- Generate migrations: `make api-migrations-generate`
- Run migrations: `make api-migrations-migrate`
- Run fixtures: `make fixtures`

Analyze code:
- php_lint + php_codesniffer: `make api-lint`
- psalm: `make api-analyze`

Tests:
- All tests: `make test`
- Unit tests: `make test-unit`
- All tests + coverage: `make test-coverage`
- Unit tests + coverage: `make test-unit-coverage`

> Path to code coverage: `/app/var/coverage`