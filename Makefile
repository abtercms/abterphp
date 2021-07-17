build:
	XDEBUG_MODE=off ./vendor/bin/phpunit
	XDEBUG_MODE=off ./vendor/bin/phpcs -p --colors --cache src
	XDEBUG_MODE=off ./vendor/bin/phan --color

install:
ifeq (,$(wildcard /usr/local/bin/composer))
	./bin/composer-install.sh
	mv composer.phar /usr/local/bin/composer
endif
	XDEBUG_MODE=off composer install --no-progress --prefer-dist --optimize-autoloader

setup:
	XDEBUG_MODE=off ./abterphp abterphp:generatesecrets
	XDEBUG_MODE=off ./abterphp abterphp:setup
	XDEBUG_MODE=off ./abterphp migrations:up

update:
	XDEBUG_MODE=off composer update
	XDEBUG_MODE=debug XDEBUG_SESSION=1 PHP_IDE_CONFIG="serverName=abtercms.test" ./abterphp migrations:up
	XDEBUG_MODE=off ./abterphp migrations:up

fix:
	XDEBUG_MODE=off ./vendor/bin/php-cs-fixer fix src

unit:
	XDEBUG_MODE=off ./vendor/bin/phpunit -v

coverage:
	XDEBUG_MODE=coverage ./vendor/bin/phpunit -c phpunit-cov.xml

flush:
	XDEBUG_MODE=off ./abterphp abterphp:flushcache

pull:
	git pull
	git submodule update --recursive --remote

.PHONY: build install setup update fix unit coverage flush pull