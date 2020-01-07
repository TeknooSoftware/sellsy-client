<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

namespace Teknoo\Sellsy\Client;

/**
 * This is the extension of the Sellsy client generator.
 * Its aim is to provide the extended Sellsy client. 
 */ 
class Webapic_ClientGenerator extends ClientGenerator {

	 /**
     * Contructor to initialize the generator.
     *
     * @param RequestGenerator|ClientInterface $requestGenerator
     * @param CollectionGeneratorInterface     $collectionGenerator
     * @param string                           $apiUrl
     * @param string                           $oauthAccessToken
     * @param string                           $oauthAccessTokenSecret
     * @param string                           $oauthConsumerKey
     * @param string                           $oauthConsumerSecret
     */
    public function __construct(
        $requestGenerator = null,
        $collectionGenerator = null,
        $apiUrl = '',
        $oauthAccessToken = '',
        $oauthAccessTokenSecret = '',
        $oauthConsumerKey = '',
        $oauthConsumerSecret = ''
    ) {
        if ($requestGenerator instanceof ClientInterface) {
            //Clone next clients from an existant model
            return $this->originalClient = $requestGenerator;
        } elseif ($requestGenerator instanceof RequestGenerator
            && $collectionGenerator instanceof CollectionGeneratorInterface) {

            //Clone nexts client from the Client with arguments defined
            $this->originalClient = new Client(
                $requestGenerator,
                $collectionGenerator,
                $apiUrl,
                $oauthAccessToken,
                $oauthAccessTokenSecret,
                $oauthConsumerKey,
                $oauthConsumerSecret
            );
        } elseif (empty($requestGenerator)) {
			exit('foobar');
            //Clone next clients with default request generator and collection generator
            $this->originalClient = new Webapic_Client(
                new RequestGenerator(),
                new CollectionGenerator(),
                $apiUrl,
                $oauthAccessToken,
                $oauthAccessTokenSecret,
                $oauthConsumerKey,
                $oauthConsumerSecret
            );
        } else {
            throw new \InvalidArgumentException('Error, invalid arguments passed to the Sellsy client generator');
        }
    }
}

