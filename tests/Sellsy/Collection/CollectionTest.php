<?php

namespace Teknoo\Tests\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\Collection;
use Teknoo\Sellsy\Collection\CollectionInterface;

/**
 * Class CollectionTest
 * @covers \Teknoo\Sellsy\Collection\Collection
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class CollectionTest extends AbstractCollectionTest
{
    /**
     * @return CollectionInterface
     */
    public function buildCollection(): CollectionInterface
    {
        return new Collection(
            $this->createMock(ClientInterface::class),
            'fooBar'
        );
    }
}