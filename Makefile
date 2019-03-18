build:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 src tests
	./vendor/bin/phpmd src text codesize,unusedcode,naming,design

precommit:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 src tests

coverage:
	./vendor/bin/phpunit

.PHONY: build
