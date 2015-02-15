<?php

namespace UniAlteri\Sellsy\Client;

use UniAlteri\Curl\RequestGenerator;
use UniAlteri\Sellsy\Client\Collection\CollectionGenerator;
use UniAlteri\Sellsy\Client\Collection\CollectionGeneratorInterface;

class ClientGenerator
{
    /**
     * @var ClientInterface
     */
    protected $originalClient;

    /**
     * @param RequestGenerator|ClientInterface $requestGenerator
     * @param CollectionGeneratorInterface $collectionGenerator
     * @param string $apiUrl
     * @param string $oauthAccessToken
     * @param string $oauthAccessTokenSecret
     * @param string $oauthConsumerKey
     * @param string $oauthConsumerSecret
     */
    public function __construct(
        $requestGenerator=null,
        $collectionGenerator=null,
        $apiUrl='',
        $oauthAccessToken='',
        $oauthAccessTokenSecret='',
        $oauthConsumerKey='',
        $oauthConsumerSecret=''
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
            //Clone next clients with default request generator and collection generator
            $this->originalClient = new Client(
                new RequestGenerator(),
                new CollectionGenerator()
            );
        } else {
            throw new \InvalidArgumentException('Error, invalid arguments passed to the Sellsy client generator');
        }
    }

    /**
     * Return a new instance of the client
     * @return ClientInterface
     */
    public function getClient()
    {
        return clone $this->originalClient;
    }
}