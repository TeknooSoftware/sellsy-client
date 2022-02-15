<?php

/**
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Sellsy\Collection;

use PHPUnit\Framework\TestCase;
use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Class AbstractCollectionTest.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
abstract class AbstractCollectionTest extends TestCase
{
    /**
     * @return CollectionInterface
     */
    abstract public function buildCollection(): CollectionInterface;

    public function testGetName()
    {
        $collection = $this->buildCollection();
        self::assertIsString($collection->getName());
        self::assertEquals('fooBar', $collection->getName());
    }

    public function testGetClient()
    {
        $collection = $this->buildCollection();
        self::assertInstanceOf(ClientInterface::class, $collection->getClient());
    }

    public function testRegisterMethod()
    {
        $collection = $this->buildCollection();
        self::assertInstanceOf(
            CollectionInterface::class,
            $collection->registerMethod($this->createMock(MethodInterface::class))
        );
    }

    public function testListMethods()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $collection->registerMethod($method1);
        $method2 = $this->createMock(MethodInterface::class);
        $method2->expects(self::any())->method('getName')->willReturn('method2');
        $collection->registerMethod($method2);

        self::assertEquals(
            [
                'method1' => $method1,
                'method2' => $method2,
            ],
            $collection->listMethods()
        );
    }

    public function testAsync()
    {
        $collection = $this->buildCollection();
        self::assertInstanceOf(CollectionInterface::class, $collection->async());
    }

    public function testGet()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $method1->expects(self::never())->method('async');
        $collection->registerMethod($method1);
        $method2 = $this->createMock(MethodInterface::class);
        $method2->expects(self::any())->method('getName')->willReturn('method2');
        $method2->expects(self::never())->method('async');
        $collection->registerMethod($method2);

        self::assertEquals('method1', $collection->method1->getName());
        self::assertEquals('method2', $collection->method2->getName());
    }

    public function testGetAsync()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $method1->expects(self::once())->method('async')->willReturn($method1);
        $collection->registerMethod($method1);
        $method2 = $this->createMock(MethodInterface::class);
        $method2->expects(self::any())->method('getName')->willReturn('method2');
        $method2->expects(self::once())->method('async')->willReturn($method2);
        $collection->registerMethod($method2);

        self::assertEquals('method1', $collection->async()->method1->getName());
        self::assertEquals('method2', $collection->async()->method2->getName());
        self::assertEquals('method1', $collection->method1->getName());
        self::assertEquals('method2', $collection->method2->getName());
    }

    public function testGetUnknown()
    {
        $this->expectException(\DomainException::class);

        $collection = $this->buildCollection();
        $collection->method1;
    }

    public function testIsset()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $collection->registerMethod($method1);

        self::assertTrue(isset($collection->method1));
        self::assertFalse(isset($collection->method2));
    }

    public function testCall()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())
            ->method('__invoke')
            ->with([])
            ->willReturn($this->createMock(ResultInterface::class));
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $collection->registerMethod($method1);

        $method2 = $this->createMock(MethodInterface::class);
        $method2->expects(self::any())
            ->method('__invoke')
            ->with(['foo'=>'bar'])
            ->willReturn($this->createMock(ResultInterface::class));
        $method2->expects(self::any())->method('getName')->willReturn('method2');
        $collection->registerMethod($method2);

        self::assertInstanceOf(ResultInterface::class, $collection->method1());
        self::assertInstanceOf(ResultInterface::class, $collection->method2(['foo'=>'bar']));
    }

    public function testCalltUnknown()
    {
        $this->expectException(\DomainException::class);

        $collection = $this->buildCollection();
        $collection->method1();
    }
}
