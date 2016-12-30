<?php

namespace Teknoo\Tests\Sellsy\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class AbstractTransportTest
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
abstract class AbstractTransportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return TransportInterface
     */
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