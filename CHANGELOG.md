#Teknoo Software - Sellsy client library - Change Log

##[3.0.6] - 2020-10-15
###Stable Release
- Fix minimum stability required

##[3.0.5] - 2020-10-12
###Stable Release
- Prepare library to support also PHP8.

##[3.0.4] - 2020-09-18
###Stable Release
- Update QA and CI tools
- fix minimum requirement about psr/http-factory and psr/http-message

##[3.0.3] - 2020-08-25
###Stable Release
###Update
- Update libs and dev libs requirements

##[3.0.2] - 2020-07-17
###Stable Release
###Change
- Add travis run also with lowest dependencies.

##[3.0.1] - 2020-07-09
##Stable Release
###Fix
- Hashbang has to be #!/usr/bin/env php, not #!/usr/bin/php (@MoogyG)

##[3.0.0] - 2020-06-12
##Stable Release
###Add
- Improve errors management from returns of API. All errors and exceptions thrown by the API
  are now mapped to an explicit PHP exception
- Improve result management: key/values are directly accessible, as object's property from the result object, thanks to voku/arrayy
- Improve result object, error message is now accessible from dedicated getter.
- Add Asynchronous requests capabilities

###Changes
- Rename oauthAccessToken to oauthUserToken to follow Sellsy api change
- Change makefile behavior for test target to auto enable xdebug to check coverage
- Remove dependence to php-http/async-client-implementation
- Remove some methods into PromiseInterface to keep only essentials methods.
- Migrate some methods into TransportInterface to use some PSR 17 interface (RequestFactory and UriInterface)
- Update TransportInterface 's Stream factory method to pass also Request object
- Add HttpPlug implementation as Support to support other libraries 
- Most methods have been updated to include type hints where applicable. Please check your extension points to make sure the function signatures are correct.
_ All files use strict typing. Please make sure to not rely on type coercion.
- PHP 7.4 is the minimum required
- Switch to typed properties
- Remove some PHP useless DockBlocks
- Replace array_merge by "..." operators
- Enable PHPStan in QA Tools
- Update copyright
- Fix PSR4 issue with tests
- Synchronize API definitions
- Switch Guzzle6 and HttpPlug implementations from main namespace to dedicated infrastructures namespace :
    * `Teknoo\Sellsy\Transport\Guzzle6` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6`
    * `Teknoo\Sellsy\Transport\Guzzle6Promise` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise`
    * `Teknoo\Sellsy\Transport\HttpPlug` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlug`
    * `Teknoo\Sellsy\Transport\HttpPlugPromise` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlugPromise`

##[3.0.0-beta9] - 2020-05-29
###Fix
- Remove empty Expect HTTP header in Client
- Fix "400 Bad request" on Document.create #22 (Thanks @fdglefevre)

##[3.0.0-beta8] - 2020-03-12
###Change
- Switch Guzzle6 and HttpPlug implementations from main namespace to dedicated infrastructures namespace :
    * `Teknoo\Sellsy\Transport\Guzzle6` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6`
    * `Teknoo\Sellsy\Transport\Guzzle6Promise` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise`
    * `Teknoo\Sellsy\Transport\HttpPlug` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlug`
    * `Teknoo\Sellsy\Transport\HttpPlugPromise` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlugPromise`

##[3.0.0-beta8] - 2020-03-12
###Change
- Switch Guzzle6 and HttpPlug implementations from main namespace to dedicated infrastructures namespace :
    * `Teknoo\Sellsy\Transport\Guzzle6` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6`
    * `Teknoo\Sellsy\Transport\Guzzle6Promise` become `Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise`
    * `Teknoo\Sellsy\Transport\HttpPlug` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlug`
    * `Teknoo\Sellsy\Transport\HttpPlugPromise` become `Teknoo\Sellsy\Guzzle6\Transport\HttpPlugPromise`

##[3.0.0-beta7] - 2020-03-11
###Change
- Fix PSR4 issue with tests
- Synchronize API definitions

##[3.0.0-beta6] - 2020-02-12
###Change
- Rename oauthAccessToken to oauthUserToken to follow Sellsy api change

##[3.0.0-beta5] - 2020-02-12
###Change
- Rename oauthAccessToken to oauthUserToken to follow Sellsy api change
- Change makefile behavior for test target to auto enable xdebug to check coverage

##[3.0.0-beta4] - 2020-01-29
###Change
- Remove dependence to php-http/async-client-implementation

##[3.0.0-beta3] - 2020-01-29
###Change
- Fix QA
- Update requirement for dev tools

##[3.0.0-beta2] - 2020-01-26
###Change
- Remove some methods into PromiseInterface to keep only essentials methods.
- Migrate some methods into TransportInterface to use some PSR 17 interface (RequestFactory and UriInterface)
- Update TransportInterface 's Stream factory method to pass also Request object
- Add HttpPlug implementation as Support to support other libraries 

##[3.0.0-beta1] - 2020-01-15
###Change
- Most methods have been updated to include type hints where applicable. Please check your extension points to make sure the function signatures are correct.
_ All files use strict typing. Please make sure to not rely on type coercion.
- PHP 7.4 is the minimum required
- Switch to typed properties
- Remove some PHP useless DockBlocks
- Replace array_merge by "..." operators
- Enable PHPStan in QA Tools
- Update copyright

###Add
- Improve errors management from returns of API. All errors and exceptions thrown by the API
  are now mapped to an explicit PHP exception
- Improve result management: key/values are directly accessible, as object's property from the result object, thanks to voku/arrayy
- Improve result object, error message is now accessible from dedicated getter.
- Add Asynchronous requests capabilities
  
#[2.0.8] - 2019-12-27
###Update
- Replace PHPMd by PHPStan
- Fix QA issues spotted by PHPStan
- Register method `Catalogue.getOneByRef`
- Enable check with PHP7.4 in travis

#[2.0.7] - 2019-06-19
###Update
- Methods definitions update
- Add new definition for ElectronicSign, thanks to @aguerin.
- Update copyright declaration

#[2.0.6] - 2019-04-14
###Update
- Methods definitions, thanks to @aguerin.

#[2.0.5] - 2018-12-21
###Fix
- Synchronize definitions with API documentations
- Supplier getList error #14

#[2.0.4] - 2018-08-03
###Add
Add Supplier collection methods from Sellsy Api Documentation

#[2.0.3] - 2018-07-11
###Fix
Change oauth_nonce generation to avoid collision on several calls. (bis)

#[2.0.2] - 2018-07-11
###Fix
Change oauth_nonce generation to avoid collision on several calls

#[2.0.1] - 2018-05-01
###Add
Add reference to API endpoint : Document.enablePublicLink and Document.disablePublicLink

##[2.0.0] - 2018-04-14
###Stable release

##[2.0.0-beta2] - 2017-12-07
###Fix
- Fix issue in client, query's parameters must be sent following
  "Content-Type: multipart/form-data" and not url-form-encoded. (Thanks to Benjamin JeanJean for the help)
- Fix mistake in the documentation

###Updated
- Set insensitive to the case the collection name and method name (the sellsy api has many inconsistencies)
- Add tools to check if all methods defined in the Sellsy Api are registered into this library, in definitions
- Update makefile to checks if all methods of the api are available at each build (daily)
- Update definitions list from the Sellsy api

##[2.0.0-beta1] - 2017-08-01
###Updated
- Update dev libraries used for this project and use now PHPUnit 6.2 for tests.

##[2.0.0-alpha4] - 2017-07-24
###Fix
- Fixing a missing return call on Uri PSR7 instance on the Client #4 (Thanks to @gouaille)

##[2.0.0-alpha3] - 2017-02-15
###Fix
- Code style fix
- License file follow Github specs
- Add tools to checks QA, use `make qa` and `make test`, `make` to initalize the project, (or `composer update`).
- Update Travis to use this tool
- Fix QA Errors

##[2.0.0-alpha2] - 2016-12-30
###Updated
- Add an helper into collection to execute directly method without use "{}" in PHP.

##[2.0.0-alpha1] - 2016-12-30
- First release on new version

###Updated
- New management of Collections and Methods with dedicated class, used as proxy to configure the client.
- Refactoring client to be simpler and the library architecture to facilitating future developments.
- Refactoring of tests.

###Added
- Support of PSR-7 and by default this library is provided with Guzzle.
- Transport behavior, build on PSR-7, to customize Sellsy's requests.
- Result interface to encapsulate Sellsy's return.
- A front class, used to generate automatically client and collections instance to perform naturally requests to Sellsy.

###Removed
- Dependency to Teknoo/curl-request.
- Remove Generator.

##[1.0.6] - 2016-08-04
###Fixed
- Improve optimization on call to native function and optimized

##[1.0.5] - 2016-07-26
###Fixed
- Fix code style with cs-fixer

###Updated
- Improve documentation and add API Doc

##[1.0.4] - 2016-04-09
###Fixed
- Fix code style with cs-fixer

##[1.0.3] - 2016-02-02
###Fixed
- Fix composer minimum requirements

##[1.0.2] - 2016-01-27
###Fixed
- Clean .gitignore

##[1.0.1] - 2015-10-27
###Changed
- Migrate from Uni Alteri Organization to Teknoo Software

##[1.0.0] - 2015-08-23
###Fixed
- Documentation

##[0.8.3-RC] - 2015-05-24
###Added
- Add travis file to support IC outside Uni Alteri's server

##[0.8.2-beta] - 2015-05-06
###Fixed
- Code style fixe
- Wrong parameter for exception - Github issue #1

##[0.8.1-beta] - 2015-03-06
###Changed
- Update composer requirements
- Update documentation

##[0.8.0-beta] - 2015-02-20
###Fixed
- Constructor arguments are not mandatory
- Several bugs in query processing
- Code style

###Changed
- Use default array notation to be compliant with PHp 5.3

###Added
- Documentation
- Tests
- Client generator

##[0.1.1] - 2015-02-06
###Added
- Methods to update client configuration
- Methods collection like on the Sellsy API.

##[0.1.0.0] - 2015-02-06
- Initial version of this library

###Changed
- Fork from official Sellsy library 
- Redesign of this library
- Fix issues and guidelines violations

###Added
- Composer
- Uni Alteri cUrl Request library instead of cUrl extension.
