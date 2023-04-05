<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Method;

use Teknoo\Immutable\ImmutableTrait;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Implementation to define entity able to represent an available method in the Sellsy Api/
 * Instance are directly invokable, but parameters must passed into an array and not been passed
 * like a normal PHP method.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Method implements MethodInterface
{
    use ImmutableTrait;

    private CollectionInterface $collection;

    private string $name;

    private bool $isAsync = false;

    public function __construct(CollectionInterface $collection, string $name)
    {
        $this->collection = $collection;
        $this->name = $name;

        $this->collection->registerMethod($this);

        $this->uniqueConstructorCheck();
    }

    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function async(): MethodInterface
    {
        $newThis = clone $this;
        $newThis->isAsync = true;

        return $newThis;
    }

    /**
     * @param array<string, mixed> $params
     * @return ResultInterface|PromiseInterface
     */
    public function __invoke(array $params = [])
    {
        $client = $this->collection->getClient();

        if (true === $this->isAsync) {
            return $client->promise($this, $params);
        }

        return $client->run($this, $params);
    }

    public function __toString(): string
    {
        return ($this->collection->getName()) . '.' . $this->name;
    }
}
