<?php

/**
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @version     0.8.0
 */
namespace Teknoo\Sellsy\Client\Collection;

use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\ClientInterface;

/**
 * Class Collection
 * Class to create collection of methods, like on the Sellsy API.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
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
     * Constructor.
     *
     * @param Client $client
     * @param string $collectionName
     */
    public function __construct(Client $client = null, $collectionName = null)
    {
        if ($client instanceof ClientInterface) {
            $this->setClient($client);
        }

        if (!empty($collectionName)) {
            $this->setCollectionName($collectionName);
        }
    }

    /**
     * To update the client to use with this collection.
     *
     * @param Client $client
     *
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return the current client.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * To update the name of this collection.
     *
     * @param string $collectionName
     *
     * @return $this
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;

        return $this;
    }

    /**
     * Return the current collection name.
     *
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * Magic call to API.
     *
     * @param string $name
     * @param array  $arguments
     *
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
                'params' => $arguments,
            )
        );
    }
}
