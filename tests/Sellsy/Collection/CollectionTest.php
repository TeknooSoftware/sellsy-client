<?php

namespace Teknoo\Tests\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\Collection;
use Teknoo\Sellsy\Collection\CollectionInterface;

class CollectionTest extends AbstractCollectionTest
{
    public function buildCollection(): CollectionInterface
    {
        return new Collection(
            $this->createMock(ClientInterface::class),
            'fooBar'
        );
    }
}