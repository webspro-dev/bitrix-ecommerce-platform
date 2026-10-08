up:
	docker compose up -d

down:
	docker compose down

logs:
	docker compose logs -f

php:
	docker compose exec php bash

test:
	vendor/bin/phpunit
