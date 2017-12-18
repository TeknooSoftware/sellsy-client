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
          '27ea6ef9d319d615d5ad9fc81c831cf80be769d0',
          'fe9cf54290cf38a0ec7cd9508413926f5f8f495e',
          '9c601a9504f497bae67358488c3d8a68597d2020',
          'd68c188386a5d6798375fb799e02e1aa4aaae5b8'
      );

      $sellsy->setTransport($transportBridge);

      //Example of request, follow the API documentation of Sellsy API.
      print $sellsy->Infos()->getInfos()->getResponse()['consumerdatas']['id'].PHP_EOL;
      print $sellsy->AccountPrefs()->getCorpInfos()->getResponse()['email'].PHP_EOL;

How-to
------
Quick How-to to learn how use this library : [Startup](docs/quick-startup.md).

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
