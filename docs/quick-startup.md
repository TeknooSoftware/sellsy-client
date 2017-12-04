#Teknoo Software - Sellsy client - Quick startup

##Installation

To install this library with composer, run this command: 

    composer require teknoo/sellsy-client
    
##Get from your Sellsy account your API credentials

The client needs some credentials to be identified by the Sellsy API to be granted
to perform some requests. These credentials are available in your Sellsy account :

    - In the Top right menu "Setting"
    - Choose "API Access" in the right column
    - Create a new application by clicking on the button "Add" and fill the form
    - Open the new section corresponding to your new application
    - Click on "Generate an user token"
    - Keep keys displayed for the following step
    
To identify clients, the Sellsy API use the standard protocol OAuth.    
    
##Configuration
    
Since the version 2, the library follows the PSR-7 and needs a transport to communicate with Sellsy' servers.
The transport prepares all PSR7's messages instances, used by the client and executes requests.
By default, this library use Guzzle to implement PSR-7. A transport is available :

     //Create the HTTP client
     $guzzleClient = new GuzzleHttp\Client();

     //Create the transport bridge
     $transportBridge = new Teknoo\Sellsy\Transport\Guzzle($guzzleClient);

     //Create the front object
      $sellsy = new Teknoo\Sellsy\Sellsy(
         'https://apifeed.sellsy.com/0/',
         'User Token',
         'User Secret',
         'Consumer Token',
         'Consumer Secret'
      );
        
##Perform a request
        
All methods defined in the api <http://api.sellsy.com/documentation/methodes> are available via collection, directly
 callable on the client:
     
By example, for `Infos.getInfos` :
     
     print_r($sellsy->Infos()->getInfos());
     
to call the method `AccountPrefs.getCorpInfos`

    print_r($sellsy->AccountPrefs()->getCorpInfos());
    
To call a method with arguments, you need pass them in an array

    print_r($client->Agenda()->getOne(['id'=>$youAgendaId]);
    
On errors, the client can throw two types of exceptions :
    
    - `Teknoo\Sellsy\Client\Exception\RequestFailureException` 
    when the request fails (bad url, unavailable server, bad formed HTTP request, bad OAuth credentials)
    - `Teknoo\Sellsy\Client\Exception\ErrorException`
    when the API return an error, unknown method or bad arguments
    
Enjoy !
