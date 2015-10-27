<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Curl\RequestGenerator;
use Teknoo\Sellsy\Client\ClientGenerator;
use Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface;

/**
 * Class ClientGeneratorTest.
 *
 * @covers Teknoo\Sellsy\Client\ClientGenerator
 */
class ClientGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionGeneratorInterface
     */
    protected function buildCollectionGeneratorMock()
    {
        return $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RequestGenerator
     */
    protected function buildRequestGeneratorMock()
    {
        return $this->getMock('Teknoo\Curl\RequestGenerator');
    }

    public function testConstructorWithOriginal()
    {
        $originalClient = $this->getMock('Teknoo\Sellsy\Client\ClientInterface', array(), array(), '', false);
        $clientGenerator = new ClientGenerator($originalClient);

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($originalClient, $client1);
        $this->assertNotSame($originalClient, $client2);
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client2);
        $this->assertNotInstanceOf('Teknoo\Sellsy\Client\Client', $client1);
        $this->assertNotInstanceOf('Teknoo\Sellsy\Client\Client', $client2);
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
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client2);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client2);
        $this->assertEquals('http://fooBar', $client1->getApiUrl());
        $this->assertEquals('http://fooBar', $client1->getApiUrl());
    }

    public function testConstructorEmpty()
    {
        $clientGenerator = new ClientGenerator();

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client2);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client2);
    }

    public function testConstructorEmptyWithScalarArgs()
    {
        $clientGenerator = new ClientGenerator(null, null, 'url1', 'token1', 'token2', 'token3', 'token4');

        $client1 = $clientGenerator->getClient();
        $client2 = $clientGenerator->getClient();
        $this->assertNotSame($client2, $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\ClientInterface', $client2);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client1);
        $this->assertInstanceOf('Teknoo\Sellsy\Client\Client', $client2);
        $this->assertEquals('url1', $client1->getApiUrl());
        $this->assertEquals('token1', $client1->getOAuthAccessToken());
        $this->assertEquals('token2', $client1->getOAuthAccessTokenSecret());
        $this->assertEquals('token3', $client1->getOAuthConsumerKey());
        $this->assertEquals('token4', $client1->getOAuthConsumerSecret());
        $this->assertEquals('url1', $client2->getApiUrl());
        $this->assertEquals('token1', $client2->getOAuthAccessToken());
        $this->assertEquals('token2', $client2->getOAuthAccessTokenSecret());
        $this->assertEquals('token3', $client2->getOAuthConsumerKey());
        $this->assertEquals('token4', $client2->getOAuthConsumerSecret());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorBadParams()
    {
        $clientGenerator = new ClientGenerator(new \stdClass());
    }
}
