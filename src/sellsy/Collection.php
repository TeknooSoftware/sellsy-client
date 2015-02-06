<?php

namespace UniAlteri\Sellsy\Client;

class Collection
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $collectionName;

    /**
     * @param Client $client
     * @param string $collectionName
     */
    public function __construct($client, $collectionName)
    {
        $this->client = $client;
        $this->collectionName = $collectionName;
    }

    /**
     * Return the current client
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Return the current collection name
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * Magic call to API
     * @param string $name
     * @param array $arguments
     * @return \stdClass
     */
    public function __call($name, $arguments)
    {
        if (empty($arguments) || !is_array($arguments)) {
            $arguments = [];
        } else {
            $arguments = (array) array_pop($arguments);
        }
        return $this->client->requestApi(
            [
                'method' => $this->collectionName.'.'.$name,
                'params' => $arguments
            ]
        );
    }
}