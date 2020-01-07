<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

namespace Teknoo\Sellsy\Client;

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

