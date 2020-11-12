<?php

/*
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
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Implementation to define a collection of methods, declared in the sellsy api :
 * https://api.sellsy.com/documentation/methods.
 *
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Collection implements CollectionInterface
{
    private ClientInterface $client;

    private string $name;

    /**
     * @var array<MethodInterface>
     */
    private array $methods = [];

    private bool $isAsync = false;

    public function __construct(ClientInterface $client, string $name)
    {
        $this->client = $client;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function registerMethod(MethodInterface $method): CollectionInterface
    {
        $this->methods[\strtolower($method->getName())] = $method;

        return $this;
    }

    public function listMethods(): array
    {
        return $this->methods;
    }

    public function __isset(string $methodName): bool
    {
        return isset($this->methods[\strtolower($methodName)]);
    }

    public function __get(string $methodName): MethodInterface
    {
        $methodName = \strtolower($methodName);
        if (!isset($this->methods[$methodName])) {
            throw new \DomainException("Error the method $methodName is not available for this collection");
        }

        $method = $this->methods[$methodName];

        if ($this->isAsync) {
            $method = $method->async();

            $this->isAsync = false;
        }

        return $method;
    }

    public function async(): CollectionInterface
    {
        $this->isAsync = true;

        return $this;
    }

    /**
     * @param array<mixed, mixed> $params
     * @return ResultInterface|PromiseInterface
     */
    public function __call(string $methodName, array $params)
    {
        $method = $this->{$methodName};

        return $method(...$params);
    }
}
