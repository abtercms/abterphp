install:
ifeq (,$(wildcard /usr/local/bin/composer))
	./bin/composer-install.sh
	mv composer.phar /usr/local/bin/composer
endif
	XDEBUG_MODE=off composer install --no-progress --prefer-dist --optimize-autoloader

build:
	# not added yet

setup:
	XDEBUG_MODE=off ./apex abterphp:generatesecrets
	XDEBUG_MODE=off ./apex abterphp:setup
	XDEBUG_MODE=off ./apex migrations:up

update:
	XDEBUG_MODE=off composer update
	XDEBUG_MODE=off ./apex migrations:up

flush:
	XDEBUG_MODE=off ./apex abterphp:flushcache

.PHONY: install build setup update flush