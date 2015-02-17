<?php

namespace UniAlteri\Sellsy\Client\Collection;

use UniAlteri\Sellsy\Client\Client;

interface CollectionGeneratorInterface
{
    /**
     * Generate a new collection object to manage the Sellsy api's methods
     * @param Client $client
     * @param string $collectionName
     * @return Collection
     */
    public function getCollection(Client $client, $collectionName);
}