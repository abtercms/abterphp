install:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	php composer.phar install

update:
	php composer.phar update

build:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 tests
	./vendor/bin/phpmd src text codesize,unusedcode,naming,design

precommit:
	./vendor/bin/phpunit --no-coverage
	./vendor/bin/phpcs
	./vendor/bin/phpcs -p --colors --cache --standard=PSR12 tests

unit:
	./vendor/bin/phpunit --no-coverage --testsuite=unit

integration:
	./vendor/bin/phpunit --no-coverage --testsuite=integration

coverage:
	./vendor/bin/phpunit --testsuite=unit

.PHONY: install update build precommit unit integration coverage
