<?php

namespace Teknoo\Tests\Sellsy;

use GuzzleHttp\Client;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\TransportInterface;
use Teknoo\Sellsy\Client\Client as SellsyClient;

/**
 * Class SellsyTest
 * @covers \Teknoo\Sellsy\Sellsy
 */
class SellsyTest extends \PHPUnit_Framework_TestCase
{
    public function buildSellsy()
    {
        return new Sellsy('https://foo.bar', 'bar', 'foo', 'hello', 'world');
    }

    public function testGetGuzzleClient()
    {
        self::assertInstanceOf(
            Client::class,
            $this->buildSellsy()->getGuzzleClient()
        );
    }

    public function testSetGuzzleClient()
    {
        $sellsy = $this->buildSellsy();

        $gc = $this->createMock(Client::class);

        self::assertInstanceOf(
            Sellsy::class,
            $sellsy->setGuzzleClient($gc)
        );

        self::assertEquals(
            $gc,
            $sellsy->getGuzzleClient()
        );
    }

    public function testGetTransport()
    {
        self::assertInstanceOf(
            TransportInterface::class,
            $this->buildSellsy()->getTransport()
        );
    }

    public function testSetTransport()
    {
        $sellsy = $this->buildSellsy();

        $t = $this->createMock(TransportInterface::class);

        self::assertInstanceOf(
            Sellsy::class,
            $sellsy->setTransport($t)
        );

        self::assertEquals(
            $t,
            $sellsy->getTransport()
        );
    }

    public function testGetClient()
    {
        self::assertInstanceOf(
            SellsyClient::class,
            $this->buildSellsy()->getClient()
        );
    }

    public function testSetClient()
    {
        $sellsy = $this->buildSellsy();

        $c = $this->createMock(SellsyClient::class);

        self::assertInstanceOf(
            Sellsy::class,
            $sellsy->setClient($c)
        );

        self::assertEquals(
            $c,
            $sellsy->getClient()
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testDefinitionsNotFound()
    {
        $this->buildSellsy()->foooBar();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDefinitionsBadDefinition()
    {
        $this->buildSellsy()->DateTime();
    }

    public function testDefinitions()
    {
        $sellsy = $this->buildSellsy();

        $definitionDir = dirname(dirname(__DIR__)).'/definitions';
        foreach (scandir($definitionDir) as $item) {
            if ('.' != $item && '..' != $item) {
                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{str_replace('.php', '', $item)}()
                );

                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{str_replace('.php', '', $item)}()
                );
            }
        }
    }
}