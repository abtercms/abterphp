install:
ifeq (,$(wildcard /usr/local/bin/composer))
	./bin/composer-install.sh
	mv composer.phar /usr/local/bin/composer
endif
	XDEBUG_MODE=off composer install --no-progress --prefer-dist --optimize-autoloader
	XDEBUG_MODE=off ./apex abterphp:generatesecrets
	XDEBUG_MODE=off ./apex abterphp:setup

update:
	XDEBUG_MODE=off composer update

flush:
	XDEBUG_MODE=off ./apex abterphp:flushcache

.PHONY: install update flush