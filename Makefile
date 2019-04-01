build:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 tests
	./vendor/bin/phpmd src text codesize,unusedcode,naming,design

precommit:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 tests

coverage:
	./vendor/bin/phpunit --testsuite=unit

.PHONY: build
