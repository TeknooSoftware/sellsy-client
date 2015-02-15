<?php

namespace UniAlteri\Sellsy\Client\Collection;

use UniAlteri\Sellsy\Client\Client;

class Collection implements CollectionInterface
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
    public function __construct(Client $client=null, $collectionName=null)
    {
        $this->setClient($client);
        $this->setCollectionName($collectionName);
    }

    /**
     * To update the client to use with this collection
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
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
     * To update the name of this collection
     * @param string $collectionName
     * @return $this
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;

        return $this;
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
            $arguments = array();
        } else {
            $arguments = (array) array_pop($arguments);
        }
        return $this->client->requestApi(
            array(
                'method' => $this->collectionName.'.'.$name,
                'params' => $arguments
            )
        );
    }
}