<?php

namespace Teknoo\Tests\Sellsy\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

abstract class AbstractTransportTest extends \PHPUnit_Framework_TestCase
{
    abstract public function buildTransport(): TransportInterface;

    public function testCreateUri()
    {
        self::assertInstanceOf(UriInterface::class, $this->buildTransport()->createUri());
    }

    public function testCreateRequest()
    {
        self::assertInstanceOf(
            RequestInterface::class,
            $this->buildTransport()->createRequest('POST', $this->createMock(UriInterface::class))
        );
    }

    public function testCreateStream()
    {
        self::assertInstanceOf(StreamInterface::class, $this->buildTransport()->createStream());
    }

    public function testExecute()
    {
        self::assertInstanceOf(
            ResponseInterface::class,
            $this->buildTransport()->execute($this->createMock(RequestInterface::class))
        );
    }
}