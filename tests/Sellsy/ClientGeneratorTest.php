<?php

namespace UniAlteri\Tests\Sellsy\Client;

use UniAlteri\Curl\RequestGenerator;
use UniAlteri\Sellsy\Client\ClientGenerator;
use UniAlteri\Sellsy\Client\Collection\CollectionGeneratorInterface;

/**
 * Class ClientGeneratorTest.
 *
 * @covers UniAlteri\Sellsy\Client\ClientGenerator
 */
class ClientGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionGeneratorInterface
     */
    protected function buildCollectionGeneratorMock()
    {
        return $this->getMock('UniAlteri\Sellsy\Client\Collection\CollectionGeneratorInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RequestGenerator
     */
    protected function buildRequestGeneratorMock()
    {
        return $this->getMock('UniAlteri\Curl\RequestGenerator');
    }

    public function testConstructorWithOriginal()
    {
        $originalClient = $this->getMock('UniAlteri\Sellsy\Client\ClientInterface', array(), array(), '', false);
        $clientGenerator = new ClientGenerator($originalClient);

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($originalClient, $client1);
        $this->assertNotSame($originalClient, $client2);
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client2);
        $this->assertNotInstanceOf('UniAlteri\Sellsy\Client\Client', $client1);
        $this->assertNotInstanceOf('UniAlteri\Sellsy\Client\Client', $client2);
    }

    public function testConstructorWithParams()
    {
        $clientGenerator = new ClientGenerator(
            $this->buildRequestGeneratorMock(),
            $this->buildCollectionGeneratorMock(),
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret'
        );

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client2);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\Client', $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\Client', $client2);
        $this->assertEquals('http://fooBar', $client1->getApiUrl());
        $this->assertEquals('http://fooBar', $client1->getApiUrl());
    }

    public function testConstructorEmpty()
    {
        $clientGenerator = new ClientGenerator();

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\ClientInterface', $client2);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\Client', $client1);
        $this->assertInstanceOf('UniAlteri\Sellsy\Client\Client', $client2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorBadParams()
    {
        $clientGenerator = new ClientGenerator(new \stdClass());
    }
}
