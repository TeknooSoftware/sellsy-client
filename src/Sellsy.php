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
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Sellsy;

use Teknoo\Sellsy\Client\Client as SellsyClient;
use GuzzleHttp\Client;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class Sellsy
 * Class helper to create automatically instances needed to dialog with the Sellsy API and interact with it.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Sellsy
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * Sellsy API End point.
     *
     * @var string
     */
    private $apiUrl;

    /**
     * OAuth access token (provided by Sellsy).
     *
     * @var string
     */
    private $oauthAccessToken;

    /**
     * OAuth secret (provided by Sellsy).
     *
     * @var string
     */
    private $oauthAccessTokenSecret;

    /**
     * OAuth consumer token (provided by Sellsy).
     *
     * @var string
     */
    private $oauthConsumerKey;

    /**
     * OAuth consumer secret  (provided by Sellsy).
     *
     * @var string
     */
    private $oauthConsumerSecret;

    /**
     * @var SellsyClient
     */
    private $client;

    /**
     * @var CollectionInterface[]
     */
    private $collections;

    /**
     * Sellsy constructor.
     *
     * @param string $apiUrl
     * @param string $accessToken
     * @param string $accessTokenSecret
     * @param string $consumerKey
     * @param string $consumerSecret
     */
    public function __construct(
        string $apiUrl,
        string $accessToken,
        string $accessTokenSecret,
        string $consumerKey,
        string $consumerSecret
    ) {
        $this->apiUrl = $apiUrl;
        $this->oauthAccessToken = $accessToken;
        $this->oauthAccessTokenSecret = $accessTokenSecret;
        $this->oauthConsumerKey = $consumerKey;
        $this->oauthConsumerSecret = $consumerSecret;
    }

    /**
     * Return and configure a guzzle client, on the flow.
     *
     * @return Client
     */
    public function getGuzzleClient(): Client
    {
        if (!$this->guzzleClient instanceof Client) {
            $this->guzzleClient = new Client();
        }

        return $this->guzzleClient;
    }

    /**
     * Return and configure a sellsy transport, on the flow.
     *
     * @return TransportInterface
     */
    public function getTransport(): TransportInterface
    {
        if (!$this->transport instanceof TransportInterface) {
            $this->transport = new Guzzle($this->getGuzzleClient());
        }

        return $this->transport;
    }

    /**
     * Return and configure a sellsy client, on the flow.
     *
     * @return SellsyClient
     */
    public function getClient(): SellsyClient
    {
        if (!$this->client instanceof SellsyClient) {
            $this->client = new SellsyClient(
                $this->getTransport(),
                $this->apiUrl,
                $this->oauthAccessToken,
                $this->oauthAccessTokenSecret,
                $this->oauthConsumerKey,
                $this->oauthConsumerSecret
            );
        }

        return $this->client;
    }

    /**
     * To define a specific Guzzle client instance to avoid to create it on the flow.
     *
     * @param Client $guzzleClient
     *
     * @return self
     */
    public function setGuzzleClient(Client $guzzleClient): Sellsy
    {
        $this->guzzleClient = $guzzleClient;

        return $this;
    }

    /**
     * To define a specific Sellsy transport instance to avoid to create it on the flow.
     *
     * @param TransportInterface $transport
     *
     * @return self
     */
    public function setTransport(TransportInterface $transport): Sellsy
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * To define a specific Sellsy client instance to avoid to create it on the flow.
     *
     * @param SellsyClient $client
     *
     * @return self
     */
    public function setClient(SellsyClient $client): Sellsy
    {
        $this->client = $client;

        return $this;
    }

    /**
     * To return the collection instance, initiated by the definition.
     *
     * @param string $collectionName
     * @param array  $arguments
     *
     * @return CollectionInterface
     *
     * @throws \DomainException  if the collection does not exist
     * @throws \RuntimeException if the collection's definition does not implementing the good interface
     */
    public function __call(string $collectionName, array $arguments): CollectionInterface
    {
        $lowerName = \strtolower($collectionName);
        if (isset($this->collections[$lowerName])) {
            return $this->collections[$lowerName];
        }

        if (!\class_exists($collectionName, true)) {
            $collectionClassName = 'Teknoo\\Sellsy\\Definitions\\'.$collectionName;

            if (!\class_exists($collectionClassName, true)) {
                throw new \DomainException("Error, the $collectionName has been not found");
            }
        } else {
            $collectionClassName = $collectionName;
        }

        $reflectionClass = new \ReflectionClass($collectionClassName);
        if (!$reflectionClass->implementsInterface(DefinitionInterface::class)) {
            throw new \RuntimeException(
                "Error, the definition of $collectionName must implement ".DefinitionInterface::class
            );
        }

        /**
         * @var callable $definitionInstance
         */
        $definitionInstance = $reflectionClass->newInstance();
        $this->collections[$lowerName] = $definitionInstance($this->getClient());

        return $this->collections[$lowerName];
    }
}
