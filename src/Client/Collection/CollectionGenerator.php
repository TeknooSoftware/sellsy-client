<?php

namespace UniAlteri\Sellsy\Client\Collection;

use UniAlteri\Sellsy\Client\Client;

class CollectionGenerator implements CollectionGeneratorInterface
{
    /**
     * @var CollectionInterface
     */
    protected $originalCollection;

    /**
     * @param CollectionInterface|null $originalCollection
     */
    public function __construct($originalCollection=null)
    {
        $this->originalCollection = $originalCollection;
    }

    /**
     * Return a new instance of a CollectionInterface instance
     * @return null|CollectionInterface
     */
    protected function prepareNewCollectionInstance()
    {
        if (!$this->originalCollection instanceof CollectionInterface) {
            $this->originalCollection = new Collection();
        }

        return clone $this->originalCollection;
    }

    /**
     * Generate a new collection object to manage the Sellsy api's methods
     * @param Client $client
     * @param string $collectionName
     * @return Collection
     */
    public function getCollection(Client $client, $collectionName)
    {
        $newCollection = $this->prepareNewCollectionInstance();
        $newCollection->setClient($client);
        $newCollection->setCollectionName($collectionName);

        return $newCollection;
    }
}