# Contributing

 * Coding standard for the project is [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * Any contribution must provide tests for additional introduced conditions
 * Any un-confirmed issue needs a failing test case before being accepted
 * Pull requests must be sent from a new hotfix/feature branch, not from `master`.

## Installation

To install the project and run the tests, you need to clone it first:

```sh
$ git clone git://github.com/TeknooSoftware/sellsy-client
```

You will then need to run a composer installation:

```sh
$ cd Instantiator
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar update
```

## Testing

The PHPUnit version to be used is the one installed as a dev- dependency via composer:

```sh
$ ./vendor/bin/phpunit
```

Accepted coverage for new contributions is 90%. Any contribution not satisfying this requirement
won't be merged.

For any questions, contact me : [richard@teknoo.software](richard@teknoo.software) :)

## Support this project

This project is free and will remain free, but it is developed on my personal time. 
If you like it and help me maintain it and evolve it, don't hesitate to support me on [Patreon](https://patreon.com/teknoo_software).
Thanks :) Richard. 
