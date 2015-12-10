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
    
All clients are instantiated by the generator :
     
     //Create the generator
     $clientGenerator = new Teknoo\Sellsy\Client\ClientGenerator();
     //Get a new client
     $client = $clientGenerator->getClient();
     //Configure the client to use the API
     $client->setApiUrl('https://apifeed.sellsy.com/0/')
        ->setOAuthAccessToken('Consumer Token')
        ->setOAuthAccessTokenSecret('Consumer Secret')
        ->setOAuthConsumerKey('User Token')
        ->setOAuthConsumerSecret('User Secret');
        
##Perform a request
        
All methods defined in the api <http://api.sellsy.com/documentation/methodes> are available by this client :
     
The method `Infos.getInfos` is directly accessible via the client
     
     print_r($client->getInfos());
     
To execute all others methods, you must go through the collection attached.
By example, to call the method `AccountPrefs.getCorpInfos`

    print_r($client->accountPrefs()->getCorpInfos());
    
To call a method with arguments, you need pass them in an array

    print_r($client->agenda()->getOne(['id'=>$youAgendaId]);
    
On errors, the client can throw two types of exceptions :
    
    - `Teknoo\Sellsy\Client\Exception\RequestFailureException` 
    when the request fails (bad url, unavailable server, bad formed HTTP request, bad OAuth credentials)
    - `Teknoo\Sellsy\Client\Exception\ErrorException`
    when the API return an error, unknown method or bad arguments
    
Enjoy !
