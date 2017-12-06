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

namespace Teknoo\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Implementation to define a collection of methods, declared in the sellsy api :
 * https://api.sellsy.com/documentation/methods.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Collection implements CollectionInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $name;

    /**
     * @var MethodInterface[]
     */
    private $methods = [];

    /**
     * Collection constructor.
     *
     * @param ClientInterface $client
     * @param string          $name
     */
    public function __construct(ClientInterface $client, string $name)
    {
        $this->client = $client;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function registerMethod(MethodInterface $method): CollectionInterface
    {
        $this->methods[\strtolower($method->getName())] = $method;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listMethods(): array
    {
        return $this->methods;
    }

    /**
     * {@inheritdoc}
     */
    public function __get(string $methodName): MethodInterface
    {
        $methodName = \strtolower($methodName);
        if (!isset($this->methods[$methodName])) {
            throw new \DomainException("Error the method $methodName is not available for this collection");
        }

        return $this->methods[$methodName];
    }

    /**
     * {@inheritdoc}
     */
    public function __call(string $methodName, array $params): ResultInterface
    {
        $method = $this->{$methodName};

        return $method(...$params);
    }
}
