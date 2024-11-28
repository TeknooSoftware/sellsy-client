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

namespace Teknoo\Tests\Sellsy\Client;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use ReflectionClass;
use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;
use Teknoo\Sellsy\Client\Exception\UnknownException;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;
use function hash;
use function random_int;
use function rawurlencode;

/**
 * Class AbstractClientTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractClientTests extends TestCase
{
    /**
     * @var string
     */
    protected $uriString = 'https://foo.bar:8080/path/api?method=toCall#archor=true';

    /**
     * @var UriInterface;
     */
    private $uri;

    /**
     * @var RequestInterface;
     */
    private $request;

    /**
     * @var StreamInterface;
     */
    private $stream;

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @return UriInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildUri(): UriInterface
    {
        if (!$this->uri instanceof UriInterface) {
            $this->uri = $this->createMock(UriInterface::class);
        }

        return $this->uri;
    }

    /**
     * @return RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildRequest(): RequestInterface
    {
        if (!$this->request instanceof RequestInterface) {
            $this->request = $this->createMock(RequestInterface::class);
        }

        return $this->request;
    }

    /**
     * @return StreamInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildStream(): StreamInterface
    {
        if (!$this->stream instanceof StreamInterface) {
            $this->stream = $this->createMock(StreamInterface::class);
        }

        return $this->stream;
    }

    /**
     * @return TransportInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function buildTransport(): TransportInterface
    {
        if (!$this->transport instanceof TransportInterface) {
            $this->transport = $this->createMock(TransportInterface::class);

            $this->transport->expects($this->any())->method('createUri')->willReturn($this->buildUri());
            $this->transport->expects($this->any())->method('createRequest')->willReturn($this->buildRequest());
        }

        return $this->transport;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return new \DateTime('2016-12-11 10:09:08');
    }

    abstract public function buildClient(
        string $uri,
        string $token,
        string $tokenSecret,
        string $consumerKey,
        string $consumerSecret
    ): ClientInterface;

    private function prepareTestRun($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $request = $this->buildRequest();
        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $request
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('getContents')->willReturn(\json_encode(['status' => 'success', 'response' => ['foo' => 'bar']]));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback) use (&$cb, $promise) {
                $cb = $callback;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb($response);
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRun()
    {
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareTestRun($method);
        self::assertInstanceOf(ResultInterface::class, $client->run($method, ['foo' => 'bar']));
        self::assertInstanceOf(RequestInterface::class, $client->getLastRequest());
        self::assertInstanceOf(ResponseInterface::class, $client->getLastResponse());
    }

    public function testPromise()
    {
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareTestRun($method);
        self::assertInstanceOf(PromiseInterface::class, $promise = $client->promise($method, ['foo' => 'bar']));
        self::assertInstanceOf(RequestInterface::class, $client->getLastRequest());
        self::assertNull($client->getLastResponse());
        self::assertInstanceOf(ResultInterface::class, $promise->wait());
        self::assertInstanceOf(ResponseInterface::class, $client->getLastResponse());
    }

    private function prepareRunReturnError($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('getContents')->willReturn(\json_encode(['status' => 'error', 'error' => 'fooBar']));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback) use (&$cb, $promise) {
                $cb = $callback;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb($response);
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunReturnError()
    {
        $this->expectException(ErrorException::class);
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunReturnError($method);
        $client->run($method, ['foo' => 'bar']);
    }

    public function testPromiseReturnError()
    {
        $this->expectException(ErrorException::class);
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunReturnError($method);
        $promise = $client->promise($method, ['foo' => 'bar']);
        $promise->wait();
    }

    private function prepareRunReturnErrorWithNotManagedCode($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('getContents')->willReturn(\json_encode(
            [
                'status' => 'error',
                'error' => [
                    'message' => 'fooBar',
                    'code' => 'E_NOT_MANAGED',
                ],
            ]
        ));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback) use (&$cb, $promise) {
                $cb = $callback;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb($response);
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunReturnErrorWithNotManagedCode()
    {
        $this->expectException(UnknownException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunReturnErrorWithNotManagedCode($method);
        $client->run($method, ['foo' => 'bar']);
    }


    public function testPromiseReturnErrorWithNotManagedCode()
    {
        $this->expectException(UnknownException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunReturnErrorWithNotManagedCode($method);
        $promise = $client->promise($method, ['foo' => 'bar']);

        $promise->wait();
    }

    private function prepareRunWithExceptionOnExecute($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willThrowException(new \Exception('fooBar'));

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunWithExceptionOnExecute()
    {
        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithExceptionOnExecute($method);
        $client->run($method, ['foo' => 'bar']);
    }

    public function testPromiseWithExceptionOnExecute()
    {
        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithExceptionOnExecute($method);
        $client->promise($method, ['foo' => 'bar']);
    }

    private function privateRunWithNoResponseStream($method)
    {
        $rc = new ReflectionClass(ResponseInterface::class);
        $rm = $rc->getMethod('getBody');
        $rt = $rm->getReturnType();
        if (null !== $rt && !$rt->allowsNull()) {
            self::markTestSkipped('Not needed with last PSR 7');
        }

        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn(null);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback) use (&$cb, $promise) {
                $cb = $callback;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb($response);
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunWithNoResponseStream()
    {
        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->privateRunWithNoResponseStream($method);
        $client->run($method, ['foo' => 'bar']);
    }

    public function testPromiseWithNoResponseStream()
    {
        $rc = new ReflectionClass(ResponseInterface::class);
        $rm = $rc->getMethod('getBody');
        $rt = $rm->getReturnType();
        if (null !== $rt && !$rt->allowsNull()) {
            self::markTestSkipped('Not needed with last PSR 7');
        }

        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->privateRunWithNoResponseStream($method);
        $promise = $client->promise($method, ['foo' => 'bar']);

        $promise->wait();
    }

    private function prepareRunWithOAUthIssue($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('getContents')->willReturn('oauth_problem=true');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback) use (&$cb, $promise) {
                $cb = $callback;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb($response);
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunWithOAUthIssue()
    {
        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithOAUthIssue($method);
        $client->run($method, ['foo' => 'bar']);
    }

    public function testPromiseWithOAUthIssue()
    {
        $this->expectException(RequestFailureException::class);
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithOAUthIssue($method);
        $promise = $client->promise($method, ['foo' => 'bar']);

        $promise->wait();
    }

    private function prepareRunWithOtherNonInterceptedError($method)
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects($this->once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => hash('sha256', (string) ($now->getTimestamp() + random_int(0, 1000))),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $this->buildRequest()
            ->expects($this->atLeastOnce())
            ->method('withHeader')
            ->with('Authorization')
            ->willReturnSelf();

        $this->buildTransport()
            ->expects($this->once())
            ->method('createStream')
            ->with(
                [
                    ['name' => 'request', 'contents' => 1],
                    ['name' => 'io_mode', 'contents' => 'json'],
                    ['name' => 'do_in', 'contents' => \json_encode([
                        'method' => 'collection.method',
                        'params' => ['foo' => 'bar'],
                    ])],
                ],
                $this->buildRequest()
            )
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects($this->once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('getContents')->willReturn('oauth_problem=true');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $cb = null;
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->any())->method('then')->willReturnCallback(
            function (callable $callback, $errorHandler) use (&$cb, $promise) {
                $cb = $errorHandler;
                return $promise;
            }
        );

        $promise->expects($this->any())->method('wait')->willReturnCallback(
            function () use (&$cb, $response) {
                if (!\is_callable($cb)) {
                    return null;
                }

                return $cb(new \Exception('fooBar'));
            }
        );

        $this->buildTransport()
            ->expects($this->once())
            ->method('asyncExecute')
            ->with($this->buildRequest())
            ->willReturn($promise);

        return $this->buildClient($uri, 'token', 'tokenSecret', 'consumerKey', 'consumerSecret');
    }

    public function testRunWithOtherNonInterceptedError()
    {
        $this->expectException(RequestFailureException::class);

        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithOAUthIssue($method);
        $client->run($method, ['foo' => 'bar']);
    }

    public function testPromiseWithOtherNonInterceptedError()
    {
        $this->expectException(RequestFailureException::class);
        $method = $this->createMock(MethodInterface::class);
        $method->expects($this->any())
            ->method('__toString')
            ->willReturn('collection.method');

        $client = $this->prepareRunWithOtherNonInterceptedError($method);
        $promise = $client->promise($method, ['foo' => 'bar']);

        $promise->wait();
    }
}
