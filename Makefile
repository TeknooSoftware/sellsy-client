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
qa: lint phpmd phpcs phpcpd checkmethods

lint:
	find ./src ./definitions ./tools  -name "*.php" -exec /usr/bin/env php -l {} \; | grep "Parse error" > /dev/null && exit 1 || exit 0

phploc:
	vendor/bin/phploc src definitions tools

phpmd:
	vendor/bin/phpmd --suffixes php src/ text codesize,design,naming,unusedcode,controversial
	vendor/bin/phpmd --suffixes php definitions/ text codesize,design,naming,unusedcode,controversial
	vendor/bin/phpmd --suffixes php tools/ text codesize,design,naming,unusedcode,controversial

phpcs:
	vendor/bin/phpcs --standard=PSR2 --extensions=php src/ definitions/ tools/

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
