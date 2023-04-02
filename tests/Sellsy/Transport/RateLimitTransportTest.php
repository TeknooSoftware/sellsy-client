<?php
declare(strict_types=1);

namespace Teknoo\Tests\Sellsy\Transport;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Transport\RateLimitTransport;
use Teknoo\Sellsy\Transport\TransportInterface;
use function microtime;

class RateLimitTransportTest extends TestCase
{
    public function testWithoutLimit(): void
    {
        $transport = $this->createMock(TransportInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $before = microtime(true);
        for ($i = 1; $i <= 10; $i++) {
            $transport->asyncExecute($request);
        }
        $after = microtime(true);
        self::assertLessThan(1., $after - $before);
    }

    public function testWithLimit(): void
    {
        $transport = $this->createMock(TransportInterface::class);
        $transport = new RateLimitTransport($transport);

        $request = $this->createMock(RequestInterface::class);
        $before = microtime(true);
        for ($i = 1; $i <= 10; $i++) {
            $transport->asyncExecute($request);
        }
        $after = microtime(true);
        self::assertGreaterThan(1., $after - $before);
    }

    public function testCreateUri(): void
    {
        $mock = $this->createMock(TransportInterface::class);
        $result = $this->createMock(UriInterface::class);
        $mock->expects(self::once())
            ->method('createUri')
            ->with('uri')
            ->will(self::returnValue($result));

        $transport = new RateLimitTransport($mock);
        $uri = $transport->createUri('uri');
        self::assertEquals($result, $uri);
    }

    public function testCreateRequest(): void
    {
        $mock = $this->createMock(TransportInterface::class);
        $result = $this->createMock(RequestInterface::class);
        $mock->expects(self::once())
            ->method('createRequest')
            ->with('method', 'uri')
            ->will(self::returnValue($result));

        $transport = new RateLimitTransport($mock);
        $request = $transport->createRequest('method', 'uri');
        self::assertEquals($result, $request);
    }

    public function testCreateStream(): void
    {
        $mock = $this->createMock(TransportInterface::class);
        $result = $this->createMock(StreamInterface::class);
        $param = [];
        $mock->expects(self::once())
            ->method('createStream')
            ->with($param)
            ->will(self::returnValue($result));

        $transport = new RateLimitTransport($mock);
        $request = $transport->createStream($param);
        self::assertEquals($result, $request);
    }
}
