### Variables

# Applications
COMPOSER ?= /usr/bin/env composer

### Helpers
all: clean depend

.PHONY: all

### Dependencies
depend:
	${COMPOSER} install --prefer-source --no-interaction

.PHONY: depend

### QA
qa: lint phpstan phpcs phpcpd checkmethods

lint:
	find ./src ./definitions ./tools  -name "*.php" -exec /usr/bin/env php -l {} \; | grep "Parse error" > /dev/null && exit 1 || exit 0

phploc:
	vendor/bin/phploc src definitions tools

phpstan:
	vendor/bin/phpstan analyse src definitions --level max

phpcs:
	vendor/bin/phpcs --standard=PSR12 --extensions=php src/ definitions/ tools/

phpcpd:
	vendor/bin/phpcpd src/ tools/

checkmethods:
	php tools/console.php teknoo:sellsy:checks-methods https://api.sellsy.com/documentation/methods -i Accoundatas

.PHONY: qa lint phploc phpmd phpcs phpcpd checkmethods

### Testing
test:
	php -dxdebug.coverage_enable=1 vendor/bin/phpunit -c phpunit.xml -v --colors --coverage-text

.PHONY: test

### Cleaning
clean:
	rm -rf vendor

.PHONY: clean
