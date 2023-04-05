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

namespace Teknoo\Sellsy\Definitions;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\Collection;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;
use Teknoo\Sellsy\Method\Method;

/**
 * @link https://api.sellsy.com/documentation/methods#electronicsigncreate
 *
 * Class ElectronicSign
 * @package Teknoo\Sellsy\Definitions
 */
class ElectronicSign implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'ElectronicSign');

        $collection->registerMethod(new Method($collection, 'create'));
        $collection->registerMethod(new Method($collection, 'delete'));
        $collection->registerMethod(new Method($collection, 'getStatus'));
        $collection->registerMethod(new Method($collection, 'getForDoc'));

        return $collection;
    }
}
