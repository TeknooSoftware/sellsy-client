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

namespace Teknoo\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Interface to define a collection of methods, declared in the sellsy api :
 * https://api.sellsy.com/documentation/methods.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
interface CollectionInterface
{
    /*
     * Return the current collection name.
     */
    public function getName(): string;

    /*
     * To return the Sellsy client attached to this client.
     */
    public function getClient(): ClientInterface;

    /*
     * To register a new MethodInterface instance in this collection.
     */
    public function registerMethod(MethodInterface $method): CollectionInterface;

    /**
     * To list methods provided by this collection.
     *
     * @return array<MethodInterface>
     */
    public function listMethods(): array;

    public function async(): CollectionInterface;

    public function __isset(string $methodName): bool;

    /*
     * To get the MethodInterface instance, identified by $methodName.
     */
    public function __get(string $methodName): MethodInterface;

    /**
     * To call directly a method interface.
     * @param array<mixed, mixed> $params
     * @return ResultInterface|PromiseInterface
     */
    public function __call(string $methodName, array $params);
}
