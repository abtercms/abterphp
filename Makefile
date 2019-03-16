build:
	docker-compose exec php sh -c './vendor/bin/phpunit --no-coverage'
	docker-compose exec php sh -c './vendor/bin/phpcs -p --colors --cache --standard=PSR12 src tests'
	docker-compose exec php sh -c './vendor/bin/phpmd src text codesize,unusedcode,naming,design'

precommit:
	docker-compose exec -T php sh -c './vendor/bin/phpunit --no-coverage'
	docker-compose exec -T php sh -c './vendor/bin/phpcs -p --colors --cache --standard=PSR12 src tests'

coverage:
	docker-compose exec php sh -c './vendor/bin/phpunit'

.PHONY: build
