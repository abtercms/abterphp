build:
	docker-compose exec php sh -c './vendor/bin/phpunit --no-coverage'
	docker-compose exec php sh -c './vendor/bin/phpcs -p --colors --cache --standard=PSR12 src'
	docker-compose exec php sh -c './vendor/bin/phpcs -p --colors --cache --standard=PSR12 tests'
	docker-compose exec php sh -c './vendor/bin/phpmd src text codesize,unusedcode,naming,design'

coverage:
	docker-compose exec php sh -c './vendor/bin/phpunit'

.PHONY: build
