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

namespace Teknoo\Sellsy\Definitions;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\Collection;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;
use Teknoo\Sellsy\Method\Method;

class Stock implements DefinitionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Stock');

        $collection->registerMethod(new Method($collection, 'getMoves'));
        $collection->registerMethod(new Method($collection, 'getForItem'));
        $collection->registerMethod(new Method($collection, 'getWarehouses'));
        $collection->registerMethod(new Method($collection, 'getWarehouse'));
        $collection->registerMethod(new Method($collection, 'createWarehouse'));
        $collection->registerMethod(new Method($collection, 'updateWarehouse'));
        $collection->registerMethod(new Method($collection, 'deleteWarehouse'));
        $collection->registerMethod(new Method($collection, 'setDefaultWarehouse'));
        $collection->registerMethod(new Method($collection, 'activate'));
        $collection->registerMethod(new Method($collection, 'desactivate'));
        $collection->registerMethod(new Method($collection, 'reactivate'));
        $collection->registerMethod(new Method($collection, 'add'));
        $collection->registerMethod(new Method($collection, 'updateThresHold'));

        return $collection;
    }
}
