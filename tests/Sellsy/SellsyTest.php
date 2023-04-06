<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * it is available in LICENSE file at the root of this package
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy;

use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\TransportInterface;
use Teknoo\Sellsy\Client\Client as SellsyClient;

/**
 * @covers \Teknoo\Sellsy\Sellsy
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class SellsyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return Sellsy
     */
    public function buildSellsy()
    {
        return new Sellsy('https://foo.bar', 'bar', 'foo', 'hello', 'world');
    }

    public function testGetTransport()
    {
        self::assertInstanceOf(
            TransportInterface::class,
            $this->buildSellsy()
                ->setTransport($this->createMock(TransportInterface::class))
                ->getTransport()
        );
    }

    public function testExceptionOnGetTransportWithNoTransport()
    {
        $this->expectException(\RuntimeException::class);
        self::assertInstanceOf(
            TransportInterface::class,
            $this->buildSellsy()
                ->getTransport()
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
            $this->buildSellsy()
                ->setTransport($this->createMock(TransportInterface::class))
                ->getClient()
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

    public function testDefinitionsNotFound()
    {
        $this->expectException(\DomainException::class);
        $this->buildSellsy()->foooBar();
    }

    public function testDefinitionsBadDefinition()
    {
        $this->expectException(\RuntimeException::class);
        $this->buildSellsy()->DateTime();
    }

    public function testDefinitions()
    {
        $sellsy = $this->buildSellsy();
        $sellsy->setTransport($this->createMock(TransportInterface::class));

        $definitionDir = dirname(__DIR__).'/../definitions';
        foreach (scandir($definitionDir) as $item) {
            if ('.' !== $item && '..' !== $item) {
                $definitionName = str_replace('.php', '', $item);
                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{$definitionName}()
                );

                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{$definitionName}()
                );
            }
        }
    }

    public function testDefinitionsWithCamelCase()
    {
        $sellsy = $this->buildSellsy();
        $sellsy->setTransport($this->createMock(TransportInterface::class));

        $definitionDir = dirname(__DIR__).'/../definitions';
        foreach (scandir($definitionDir) as $item) {
            if ('.' !== $item && '..' !== $item) {
                $definitionName = str_replace('.php', '', $item);
                $definitionNameInCamel = \lcfirst($definitionName);

                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{$definitionNameInCamel}()
                );

                self::assertInstanceOf(
                    CollectionInterface::class,
                    $sellsy->{$definitionNameInCamel}()
                );

                self::assertEquals(
                    $sellsy->{$definitionName}(),
                    $sellsy->{$definitionNameInCamel}()
                );
            }
        }
    }
}
