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
 * Interface to define a collection of methods, declared in the sellsy api :
 * https://api.sellsy.com/documentation/methods.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface CollectionInterface
{
    /**
     * Return the current collection name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * To return the Sellsy client attached to this client.
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * To register a new MethodInterface instance in this collection.
     *
     * @param MethodInterface $method
     *
     * @return CollectionInterface
     */
    public function registerMethod(MethodInterface $method): CollectionInterface;

    /**
     * To list methods provided by this collection.
     *
     * @return MethodInterface[]
     */
    public function listMethods(): array;

    /**
     * To get the MethodInterface instance, identified by $methodName.
     *
     * @param string $methodName
     *
     * @return MethodInterface
     */
    public function __get(string $methodName): MethodInterface;

    /**
     * To call directly a method interface.
     *
     * @param string $methodName
     * @param array  $params
     *
     * @return ResultInterface
     */
    public function __call(string $methodName, array $params): ResultInterface;
}
