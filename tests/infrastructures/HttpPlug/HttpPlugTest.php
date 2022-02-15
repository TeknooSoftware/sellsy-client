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

namespace Teknoo\Tests\Sellsy\HttpPlug\Transport;

use Http\Client\HttpAsyncClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\HttpPlug\Transport\HttpPlug;
use Teknoo\Sellsy\Transport\TransportInterface;
use Teknoo\Tests\Sellsy\Transport\AbstractTransportTest;

/**
 * @covers \Teknoo\Sellsy\HttpPlug\Transport\HttpPlug
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class HttpPlugTest extends AbstractTransportTest
{
    /**
     * @return TransportInterface
     */
    public function buildTransport(): TransportInterface
    {
        $client = $this->createMock(HttpAsyncClient::class);
        $uriFactory = $this->createMock(UriFactory::class);
        $requestFactory = $this->createMock(RequestFactory::class);
        $streamFactory = $this->createMock(StreamFactory::class);

        $uriFactory->expects(self::any())
            ->method('createUri')
            ->willReturn($this->createMock(UriInterface::class));

        $request = $this->createMock(RequestInterface::class);
        $request->expects(self::any())->method('withHeader')->willReturn($request);
        $request->expects(self::any())->method('getHeader')->willReturn(['multipart/form-data; boundary="fooBar"']);

        $requestFactory->expects(self::any())
            ->method('createRequest')
            ->willReturn($request);

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::any())->method('getMetadata')->willReturn('foo');

        $streamFactory->expects(self::any())
            ->method('createStream')
            ->willReturn($stream);

        $client->expects(self::any())
            ->method('sendAsyncRequest')
            ->with($this->callback(function ($arg) {
                return $arg instanceof RequestInterface;
            }))
            ->willReturn($this->createMock(Promise::class));

        return new HttpPlug($client, $uriFactory, $requestFactory, $streamFactory);
    }

    public function testCreateStream()
    {
        $body = [
            ['name' => 'foo', 'contents' => 'bar']
        ];

        $request = $this->createMock(RequestInterface::class);
        $request->expects(self::any())->method('withHeader')->willReturn($request);
        $request->expects(self::any())->method('getHeader')->willReturn(['multipart/form-data; boundary="fooBar"']);

        self::assertInstanceOf(
            StreamInterface::class,
            $this->buildTransport()->createStream(
                $body,
                $request
            )
        );
    }

    public function testCreateStreamWithoutRequest()
    {
        $this->expectException(\RuntimeException::class);

        $body = [
            ['name' => 'foo', 'contents' => 'bar']
        ];

        self::assertInstanceOf(
            StreamInterface::class,
            $this->buildTransport()->createStream(
                $body
            )
        );
    }
}
