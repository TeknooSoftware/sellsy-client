### Variables

# Applications
COMPOSER ?= /usr/bin/env composer
DEPENDENCIES ?= lastest

### Helpers
all: clean depend

.PHONY: all

### Dependencies
depend:
ifeq ($(DEPENDENCIES), lowest)
	${COMPOSER} update --prefer-lowest --prefer-dist --no-interaction --ignore-platform-reqs;
else
	${COMPOSER} update --prefer-dist --no-interaction --ignore-platform-reqs;
endif

.PHONY: depend

### QA
qa: lint phpstan phpcs phpcpd checkmethods

lint:
	find ./infrastructures ./src ./definitions ./tools  -name "*.php" -exec /usr/bin/env php -l {} \; | grep "Parse error" > /dev/null && exit 1 || exit 0

phploc:
	vendor/bin/phploc src infrastructures definitions tools

phpstan:
	php -d memory_limit=512M vendor/bin/phpstan analyse src infrastructures definitions --level max

phpcs:
	vendor/bin/phpcs --standard=PSR12 --extensions=php src/ infrastructures/ definitions/ tools/

phpcpd:
	vendor/bin/phpcpd src/ tools/ infrastructures/

checkmethods:
	php tools/console.php teknoo:sellsy:checks-methods https://api.sellsy.com/documentation/methods -i Accoundatas,Docrows

.PHONY: qa lint phploc phpmd phpcs phpcpd checkmethods

### Testing
test:
	XDEBUG_MODE=coverage php -dzend_extension=xdebug.so -dxdebug.coverage_enable=1 vendor/bin/phpunit -c phpunit.xml -v --colors --coverage-text

.PHONY: test

### Cleaning
clean:
	rm -rf vendor

.PHONY: clean
