<?php
namespace Teknoo\Sellsy\Client;

use Teknoo\Curl\RequestGenerator;
use Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface;
use Teknoo\Sellsy\Client\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;

/**
 * This is the extension of the Sellsy client.
 * Its aim is to provide some methods that are missing in the parent class, such as supplier(). 
 */
class Webapic_Client extends Client {

	/**
     * Return collection methods of the api for Supplier.
     *
     * @return CollectionInterface
     */
    public function supplier()
    {
        return $this->collectionGenerator->getCollection($this, 'Supplier');
    }
}


