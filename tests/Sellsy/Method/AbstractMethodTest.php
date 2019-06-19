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

namespace Teknoo\Tests\Sellsy\Method;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Class AbstractMethodTest.
 *
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
abstract class AbstractMethodTest extends \PHPUnit\Framework\TestCase
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

            $this->collection->expects(self::any())->method('getName')->willReturn('collectionName');
            $this->collection->expects(self::any())->method('getClient')->willReturn($this->buildClient());
        }

        return $this->collection;
    }

    abstract public function buildMethod(): MethodInterface;

    public function testGetCollection()
    {
        $method = $this->buildMethod();
        self::assertInstanceOf(CollectionInterface::class, $method->getCollection());
    }

    public function testGetName()
    {
        $method = $this->buildMethod();
        self::assertInternalType('string', $method->getName());
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
            ->expects(self::once())
            ->method('run')
            ->with($method, ['foo' => 'bar'])
            ->willReturn($response);

        self::assertInstanceOf(ResultInterface::class, $method(['foo' => 'bar']));
    }

    public function testToString()
    {
        $method = $this->buildMethod();
        self::assertInternalType('string', (string) $method);
        self::assertEquals('collectionName.fooBar', (string) $method);
    }
}
