build:
	$(MAKE) unit

install:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	php composer.phar install
	php apex abterphp:generatesecrets
	php apex abterphp:setup

update:
	php composer.phar update

precommit:
	./vendor/bin/phpunit --no-coverage

unit:
	./vendor/bin/phpunit --no-coverage --testsuite=unit

pre-coverage:
	curl -L --output phpcov.phar https://phar.phpunit.de/phpcov.phar
	chmod +x phpcov.phar
	mkdir -p ./tmp/cov
	cd ./vendor/abterphp/framework; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/admin; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/website; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/contact; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/files; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/bootstrap4-website; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/propeller-admin; mkdir vendor; cd vendor; ln -s ../../../autoload.php
	cd ./vendor/abterphp/website-creative; mkdir vendor; cd vendor; ln -s ../../../autoload.php

coverage:
	./vendor/bin/phpunit -c ./vendor/abterphp/framework/phpunit.xml --coverage-php ./tmp/cov/framework.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/admin/phpunit.xml --coverage-php ./tmp/cov/admin.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/website-creative/phpunit.xml --coverage-php ./tmp/cov/website-creative.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/bootstrap4-website/phpunit.xml --coverage-php ./tmp/cov/bootstrap4-website.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/website/phpunit.xml --coverage-php ./tmp/cov/website.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/contact/phpunit.xml --coverage-php ./tmp/cov/contact.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/files/phpunit.xml --coverage-php ./tmp/cov/files.cov
	./vendor/bin/phpunit -c ./vendor/abterphp/propeller-admin/phpunit.xml --coverage-php ./tmp/cov/propeller-admin.cov

post-coverage:
	./phpcov.phar merge -vvv --clover tmp/report/clover.xml tmp/cov/
	./php-coveralls.phar -v --coverage_clover=./tmp/report/clover.xml --json_path=./tmp/coveralls-upload.json

send-coverage:
	curl -L --output php-coveralls.phar https://github.com/php-coveralls/php-coveralls/releases/download/v2.1.0/php-coveralls.phar
	chmod +x php-coveralls.phar
	./php-coveralls.phar -v --coverage_clover=./tmp/report/clover.xml --json_path=./tmp/coveralls-upload.json

flush:
	./apex abterphp:flushcache

.PHONY: build install update precommit unit coverage flush
