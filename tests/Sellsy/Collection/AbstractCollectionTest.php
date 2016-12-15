<?php

namespace Teknoo\Tests\Sellsy\Collection;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Method\MethodInterface;

abstract class AbstractCollectionTest extends \PHPUnit_Framework_TestCase
{
    abstract public function buildCollection(): CollectionInterface;

    public function testGetName()
    {
        $collection = $this->buildCollection();
        self::assertInternalType('string', $collection->getName());
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
                'method2' => $method2
            ],
            $collection->listMethods()
        );
    }

    public function testGet()
    {
        $collection = $this->buildCollection();
        $method1 = $this->createMock(MethodInterface::class);
        $method1->expects(self::any())->method('getName')->willReturn('method1');
        $collection->registerMethod($method1);
        $method2 = $this->createMock(MethodInterface::class);
        $method2->expects(self::any())->method('getName')->willReturn('method2');
        $collection->registerMethod($method2);

        self::assertEquals('method1', $collection->method1->getName());
        self::assertEquals('method2', $collection->method2->getName());
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetUnknown()
    {
        $collection = $this->buildCollection();
        $collection->method1;
    }
}