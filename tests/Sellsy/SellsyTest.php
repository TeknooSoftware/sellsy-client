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

namespace Teknoo\Tests\Sellsy;

use GuzzleHttp\Client;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\TransportInterface;
use Teknoo\Sellsy\Client\Client as SellsyClient;

/**
 * Class SellsyTest.
 *
 * @covers \Teknoo\Sellsy\Sellsy
 *
 *@copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class SellsyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Sellsy
     */
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
