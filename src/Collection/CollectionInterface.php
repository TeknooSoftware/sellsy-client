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
namespace Teknoo\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Interface CollectionInterface
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 *
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
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * @param MethodInterface $method
     * @return CollectionInterface
     */
    public function registerMethod(MethodInterface $method): CollectionInterface;

    /**
     * @return MethodInterface[]
     */
    public function listMethods(): array;

    /**
     * @param string $methodName
     * @return MethodInterface
     */
    public function __get(string $methodName): MethodInterface;
}
