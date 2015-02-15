<?php

namespace UniAlteri\Sellsy\Client\Collection;

use UniAlteri\Sellsy\Client\Client;

interface CollectionInterface
{
    /**
     * To update the client to use with this collection
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client);

    /**
     * Return the current client
     * @return Client
     */
    public function getClient();

    /**
     * To update the name of this collection
     * @param string $collectionName
     * @return $this
     */
    public function setCollectionName($collectionName);

    /**
     * Return the current collection name
     * @return string
     */
    public function getCollectionName();
}