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
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 *
 * @link        https://teknoo.software/libraries/sellsy Project website
 *
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy\Method;

use PHPUnit\Framework\TestCase;
use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Class AbstractMethodTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractMethodTests extends TestCase
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var CollectionInterface
     */
    private $collection;

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    public function buildClient()
    {
        if (!$this->client instanceof ClientInterface) {
            $this->client = $this->createMock(ClientInterface::class);
        }

        return $this->client;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionInterface
     */
    public function buildCollection()
    {
        if (!$this->collection instanceof CollectionInterface) {
            $this->collection = $this->createMock(CollectionInterface::class);

            $this->collection->expects($this->any())->method('getName')->willReturn('collectionName');
            $this->collection->expects($this->any())->method('getClient')->willReturn($this->buildClient());
        }

        return $this->collection;
    }

    abstract public function buildMethod(): MethodInterface;

    public function testGetCollection()
    {
        $method = $this->buildMethod();
        self::assertInstanceOf(CollectionInterface::class, $method->getCollection());
    }

    public function testAsync()
    {
        $method = $this->buildMethod();
        self::assertInstanceOf(MethodInterface::class, $method->async());
    }

    public function testGetName()
    {
        $method = $this->buildMethod();
        self::assertIsString($method->getName());
        self::assertEquals('fooBar', $method->getName());
    }

    public function testInvoke()
    {
        /**
         * @var callable
         */
        $method = $this->buildMethod();

        $response = $this->createMock(ResultInterface::class);

        $this->buildClient()
            ->expects($this->never())
            ->method('promise');

        $this->buildClient()
            ->expects($this->once())
            ->method('run')
            ->with($method, ['foo' => 'bar'])
            ->willReturn($response);

        self::assertInstanceOf(ResultInterface::class, $method(['foo' => 'bar']));
    }

    public function testInvokeAsync()
    {
        /**
         * @var callable
         */
        $method = $this->buildMethod()->async();

        $promise = $this->createMock(PromiseInterface::class);

        $this->buildClient()
            ->expects($this->never())
            ->method('run');

        $this->buildClient()
            ->expects($this->once())
            ->method('promise')
            ->with($method, ['foo' => 'bar'])
            ->willReturn($promise);

        self::assertInstanceOf(PromiseInterface::class, $method(['foo' => 'bar']));
    }

    public function testToString()
    {
        $method = $this->buildMethod();
        self::assertIsString((string) $method);
        self::assertEquals('collectionName.fooBar', (string) $method);
    }
}
