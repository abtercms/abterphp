build:
	$(MAKE) unit
	$(MAKE) integration

install:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
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

integration:
	./vendor/bin/phpunit --no-coverage --testsuite=integration

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

flush:
	./apex abterphp:flushcache

.PHONY: build install update precommit unit integration coverage flush
