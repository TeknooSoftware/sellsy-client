<?php

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
    /**
     * @inheritdoc
     */
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