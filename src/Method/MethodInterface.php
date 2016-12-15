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

interface MethodInterface extends ImmutableInterface
{
    /**
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $params
     * @return ResultInterface
     */
    public function __invoke(array $params = []): ResultInterface;

    /**
     * @return string
     */
    public function __toString(): string;
}