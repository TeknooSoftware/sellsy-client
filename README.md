Teknoo Software - Sellsy client
==========================

[![Latest Stable Version](https://poser.pugx.org/teknoo/sellsy-client/v/stable)](https://packagist.org/packages/teknoo/sellsy-client)
[![Latest Unstable Version](https://poser.pugx.org/teknoo/sellsy-client/v/unstable)](https://packagist.org/packages/teknoo/sellsy-client)
[![Total Downloads](https://poser.pugx.org/teknoo/sellsy-client/downloads)](https://packagist.org/packages/teknoo/sellsy-client)
[![License](https://poser.pugx.org/teknoo/sellsy-client/license)](https://packagist.org/packages/teknoo/sellsy-client)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

PHP library to connect your applications to your [Sellsy account](http://sellsy.com/) account using the 
[Sellsy API](http://api.sellsy.com/) and build your websites and your platforms on the Sellsy technology.

Simple Example
--------------

        <?php

        use GuzzleHttp\Client;
        use Teknoo\Sellsy\Guzzle6\Transport\Guzzle6;
        use Teknoo\Sellsy\Sellsy;
        
        include 'vendor/autoload.php';
        
        //Create the HTTP client
        $guzzleClient = new Client();
        
        //Create the transport bridge
        $transportBridge = new Guzzle6($guzzleClient);
        
        //Create the front object
        $sellsy = new Sellsy(
            'https://apifeed.sellsy.com/0/',
            $userToken,
            $userSecret,
            $consumerKey,
            $consumerSecret
        );
        
        $sellsy->setTransport($transportBridge);
        
        //Example of request, follow the API documentation of Sellsy API.
        print $sellsy->infos()->getInfos()->getResponse()['consumerdatas']['id'];
        //Show your ConsumerDatas id, like 9001
        
        print $sellsy->Infos()->getInfos()->consumerdatas->id;
        //Show again your ConsumerDatas id, like 9001
        
        $sellsy->Infos()->async()->getInfos()->then(function (\Teknoo\Sellsy\Client\ResultInterface $result) {
            print $result->consumerdatas->id.PHP_EOL;
        })->wait();
        //Show again your ConsumerDatas id, like 9001
        
        print $sellsy->AccountPrefs()->getCorpInfos()->getResponse()['email'];
        //Show your email, like contact@teknoo.software
        
        print $sellsy->AccountPrefs()->getCorpInfos()->email;
        //Show your email, like contact@teknoo.software
        
        $sellsy->AccountPrefs()->async()->getCorpInfos()->then(function (\Teknoo\Sellsy\Client\ResultInterface $result) {
            print $result->email.PHP_EOL;
        })->wait();
        //Show your email, like contact@teknoo.software
        
        $sellsy->AccountDatas()->deleteTaxe();
        //Thrown an exception : Teknoo\Sellsy\Client\Exception\ParameterMissingException: id is missing

How-to
------
Quick How-to to learn how use this library : [Startup](docs/quick-startup.md).
Manage Sellsy Rate Limiting : [Rate Limiting](docs/rate-limiting.md).

Support this project
---------------------

This project is free and will remain free, but it is developed on my personal time. 
If you like it and help me maintain it and evolve it, don't hesitate to support me on [Patreon](https://patreon.com/teknoo_software).
Thanks :) Richard. 

Installation & Requirements
---------------------------
To install this library with composer, run this command :

    composer require teknoo/sellsy-client
    
To use the embedded Guzzle transport    

    composer require guzzlehttp/guzzle

This library requires :

    * PHP 7.4+
    * A PHP autoloader (Composer is recommended)
    * Teknoo/Immutable.
    * A PSR-7 implementation

News from Teknoo Sellsy Client 3.0
----------------------------------

This library requires PHP 7.4 or newer. Some change causes bc breaks :

- PHP 7.4 is the minimum required
- Improve errors management from returns of API. All errors and exceptions thrown by the API
  are now mapped to an explicit PHP exception
- Improve result management: key/values are directly accessible, as object's property from the result object, thanks to voku/arrayy
- Improve result object, error message is now accessible from dedicated getter.
- Add Asynchronous requests capabilities
- Switch to typed properties
- Remove some PHP useless DockBlocks
- Replace array_merge by "..." operators
- Most methods have been updated to include type hints where applicable. Please check your extension points to make sure the function signatures are correct.
_ All files use strict typing. Please make sure to not rely on type coercion.
- Switch to PHPStan in QA Tools and disable PHPMd

Special Thanks
--------------
Julien Herr <julien@herr.fr> : RateLimitTransport and documentation about Sellsy's rate limit in its API.

Credits
-------
Richard Déloge - <richard@teknoo.software> - Lead developer.
Teknoo Software - <http://teknoo.software>

About Teknoo Software
---------------------
**Teknoo Software** is a PHP software editor, founded by Richard Déloge. 
Teknoo Software's DNA is simple : Provide to our partners and to the community a set of high quality services or software,
 sharing knowledge and skills.
 
License
-------
Sellsy is licensed under the MIT Licenses - see the licenses folder for details

Contribute :)
-------------

You are welcome to contribute to this project. [Fork it on Github](CONTRIBUTING.md)
