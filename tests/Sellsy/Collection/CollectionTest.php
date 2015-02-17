<?php

namespace UniAlteri\Tests\Sellsy\Client\Collection;

use UniAlteri\Sellsy\Client\Collection\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetClient()
    {
        $client = $this->getMock('UniAlteri\Sellsy\Client\Client', [], [], '', false);
        $collection = new Collection();
        $this->assertNull($collection->getClient());
        $this->assertSame($collection, $collection->setClient($client));
        $this->assertSame($client, $collection->getClient());
    }

    public function testGetSetCollectionName()
    {
        $collection = new Collection();
        $this->assertNull($collection->getCollectionName());
        $this->assertSame($collection, $collection->setCollectionName('fooBar'));
        $this->assertSame('fooBar', $collection->getCollectionName());
    }

    public function testConstructor()
    {
        $client = $this->getMock('UniAlteri\Sellsy\Client\Client', [], [], '', false);
        $collection = new Collection($client, 'fooBar');
        $this->assertSame($client, $collection->getClient());
        $this->assertSame('fooBar', $collection->getCollectionName());
    }

    public function testCallNotArgs()
    {
        $client = $this->getMock('UniAlteri\Sellsy\Client\Client', [], [], '', false);
        $collection = new Collection($client, 'fooBar');

        $client->expects($this->once())
            ->method('requestApi')
            ->with(
                $this->equalTo(
                    array(
                        'method' => 'fooBar.callMethod',
                        'params' => array()
                    )
                )
            )
            ->willReturn(new \stdClass());

        $this->assertEquals(new \stdClass(), $collection->callMethod());
    }

    public function testCallWithArgs()
    {
        $client = $this->getMock('UniAlteri\Sellsy\Client\Client', [], [], '', false);
        $collection = new Collection($client, 'fooBar');

        $client->expects($this->once())
            ->method('requestApi')
            ->with(
                $this->equalTo(
                    array(
                        'method' => 'fooBar.callMethod',
                        'params' => array('foo'=>'bar')
                    )
                )
            )
            ->willReturn(new \stdClass());

        $this->assertEquals(new \stdClass(), $collection->callMethod(array('foo'=>'bar')));
    }
}