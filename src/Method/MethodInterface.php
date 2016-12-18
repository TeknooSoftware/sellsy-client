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
namespace Teknoo\Sellsy\Method;

use Teknoo\Immutable\ImmutableInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * Interface to define entity able to represent an available method in the Sellsy Api/
 * Instance are directly invokable, but parameters must passed into an array and not been passed like a normal PHP method.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface MethodInterface extends ImmutableInterface
{
    /**
     * To know the collection attached to this method.
     *
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface;

    /**
     * To know the name of the method in the Sellsy API
     *
     * @return string
     */
    public function getName(): string;

    /**
     * To execute the method on the Sellsy API
     *
     * @param array $params
     * @return ResultInterface
     */
    public function __invoke(array $params = []): ResultInterface;

    /**
     * To know the name of the method in the Sellsy api, accompanied with the collection name
     * @return string
     */
    public function __toString(): string;
}