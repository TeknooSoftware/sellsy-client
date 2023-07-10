### Variables

# Applications
COMPOSER ?= /usr/bin/env composer
DEPENDENCIES ?= lastest
PHP ?= /usr/bin/env php

### Helpers
all: clean depend

.PHONY: all

### Dependencies
depend:
ifeq ($(DEPENDENCIES), lowest)
	${COMPOSER} update --prefer-lowest --prefer-dist --no-interaction;
else
	${COMPOSER} update --prefer-dist --no-interaction;
endif

.PHONY: depend

### QA
qa: lint phpstan phpcs composerunsed checkmethods audit
qa-offline: lint phpstan phpcs composerunsed

lint:
	find ./infrastructures ./src ./definitions ./tools  -name "*.php" -exec ${PHP} -l {} \; | grep "Parse error" > /dev/null && exit 1 || exit 0

phpstan:
	${PHP} -d memory_limit=512M vendor/bin/phpstan analyse src infrastructures definitions --level max

phpcs:
	${PHP} vendor/bin/phpcs --standard=PSR12 --extensions=php src/ infrastructures/ definitions/ tools/

checkmethods:
	${PHP} tools/console.php teknoo:sellsy:checks-methods https://api.sellsy.com/documentation/methods -i Accoundatas,Docrows

composerunsed:
	${PHP} vendor/bin/composer-unused

audit:
	${COMPOSER} audit

.PHONY: qa qa-offline lint phpmd phpcs composerunsed checkmethods audit

### Testing
test:
	XDEBUG_MODE=coverage ${PHP} -d memory_limit=512M -dzend_extension=xdebug.so -dxdebug.mode=coverage vendor/bin/phpunit -c phpunit.xml --colors --coverage-text

.PHONY: test

### Cleaning
clean:
	rm -rf vendor

.PHONY: clean
