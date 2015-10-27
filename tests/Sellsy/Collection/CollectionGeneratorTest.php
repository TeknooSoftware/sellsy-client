<?php

namespace Teknoo\Tests\Sellsy\Client\Collection;

use Teknoo\Sellsy\Client\Collection\CollectionGenerator;

/**
 * Class CollectionGeneratorTest.
 *
 * @covers Teknoo\Sellsy\Client\Collection\CollectionGenerator
 */
class CollectionGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCollectionEmptyOriginal()
    {
        $client = $this->getMock('Teknoo\Sellsy\Client\Client', [], [], '', false);
        $collectionGenerator = new CollectionGenerator();
        $collection1 = $collectionGenerator->getCollection($client, 'fooBar');
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Collection\Collection', $collection1);
        $this->assertSame($client, $collection1->getClient());
        $this->assertEquals('fooBar', $collection1->getCollectionName());
        $collection2 = $collectionGenerator->getCollection($client, 'fooBar');
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Collection\Collection', $collection2);
        $this->assertSame($client, $collection2->getClient());
        $this->assertEquals('fooBar', $collection2->getCollectionName());
        $this->assertNotSame($collection1, $collection2);
    }

    public function testGetCollectionNotEmptyOriginal()
    {
        $collectionOriginal = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $client = $this->getMock('Teknoo\Sellsy\Client\Client', [], [], '', false);
        $collectionGenerator = new CollectionGenerator($collectionOriginal);
        $collection1 = $collectionGenerator->getCollection($client, 'fooBar');
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Collection\CollectionInterface', $collection1);
        $this->assertNotInstanceOf('Teknoo\Sellsy\Client\Collection\Collection', $collection1);
        $collection2 = $collectionGenerator->getCollection($client, 'fooBar');
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Collection\CollectionInterface', $collection2);
        $this->assertNotInstanceOf('Teknoo\Sellsy\Client\Collection\Collection', $collection2);
        $this->assertNotSame($collection1, $collection2);
    }
}
