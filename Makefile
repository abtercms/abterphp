build:
	$(MAKE) integration
	$(MAKE) coverage

install:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	php composer.phar install
	openssl genrsa -passout pass:$OAUTH_PRIVATE_KEY_PASSWORD -out private/private.key 2048
	openssl rsa -in private.key -passin pass:$OAUTH_PRIVATE_KEY_PASSWORD -pubout -out private/public.key

update:
	php composer.phar update

precommit:
	./vendor/bin/phpunit --no-coverage

unit:
	./vendor/bin/phpunit --no-coverage --testsuite=unit

integration:
	./vendor/bin/phpunit --no-coverage --testsuite=integration

coverage:
	./vendor/bin/phpunit --testsuite=unit

flush:
	./apex abterphp:flushcache

.PHONY: build install update precommit unit integration coverage flush
