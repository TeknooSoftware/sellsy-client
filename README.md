Teknoo Software - Sellsy client
==========================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e053f347-f92a-47d9-b8b3-9f415d407889/mini.png)](https://insight.sensiolabs.com/projects/e053f347-f92a-47d9-b8b3-9f415d407889) [![Build Status](https://travis-ci.org/TeknooSoftware/sellsy-client.svg?branch=master)](https://travis-ci.org/TeknooSoftware/sellsy-client)

PHP library to connect your applications to your [Sellsy account](http://sellsy.com/) account using the 
[Sellsy API](http://api.sellsy.com/) and build your websites and your platforms on the Sellsy technology.

Simple Example
--------------

      <?php

      use GuzzleHttp\Client;
      use Teknoo\Sellsy\Transport\Guzzle;
      use Teknoo\Sellsy\Sellsy;

      include 'vendor/autoload.php';

      //Create the HTTP client
      $guzzleClient = new Client();

      //Create the transport bridge
      $transportBridge = new Guzzle($guzzleClient);

      //Create the front object
      $sellsy = new Sellsy(
          'https://apifeed.sellsy.com/0/',
          'User Token',
          'User Secret',
          'Consumer Token',
          'Consumer Secret'
      );

      $sellsy->setTransport($transportBridge);

      //Example of request, follow the API documentation of Sellsy API.
      print $sellsy->Infos()->getInfos()->getResponse()['consumerdatas']['id'].PHP_EOL;
      print $sellsy->AccountPrefs()->getCorpInfos()->getResponse()['email'].PHP_EOL;

How-to
------
Quick How-to to learn how use this library : [Startup](docs/quick-startup.md).

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

    * PHP 7+
    * A PSR-7 implementation

Credits
-------
Richard Déloge - <richarddeloge@gmail.com> - Lead developer.
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
