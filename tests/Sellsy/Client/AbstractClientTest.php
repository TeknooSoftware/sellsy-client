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
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Sellsy\Client;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;
use Teknoo\Sellsy\Client\Exception\UnknownException;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class AbstractClientTest.
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
abstract class AbstractClientTest extends TestCase
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

            $this->transport->expects(self::any())->method('createUri')->willReturn($this->buildUri());
            $this->transport->expects(self::any())->method('createRequest')->willReturn($this->buildRequest());
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

    /**
     * @return ClientInterface
     */
    abstract public function buildClient(): ClientInterface;

    public function testSetApiUrl()
    {
        $client = $this->buildClient();
        self::assertInstanceOf(ClientInterface::class, $client->setApiUrl('http://foo.bar'));
    }

    public function testSetOAuthAccessToken()
    {
        $client = $this->buildClient();
        self::assertInstanceOf(ClientInterface::class, $client->setOAuthAccessToken('fooBar'));
    }

    public function testSetOAuthAccessTokenSecret()
    {
        $client = $this->buildClient();
        self::assertInstanceOf(ClientInterface::class, $client->setOAuthConsumerSecret('fooBar'));
    }

    public function testSetOAuthConsumerKey()
    {
        $client = $this->buildClient();
        self::assertInstanceOf(ClientInterface::class, $client->setOAuthConsumerKey('fooBar'));
    }

    public function testSetOAuthConsumerSecret()
    {
        $client = $this->buildClient();
        self::assertInstanceOf(ClientInterface::class, $client->setOAuthConsumerSecret('fooBar'));
    }

    public function testRun()
    {
        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::any())->method('getContents')->willReturn(\json_encode(['status' => 'success', 'response' => ['foo' => 'bar']]));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())->method('getBody')->willReturn($stream);

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willReturn($response);

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        self::assertInstanceOf(ResultInterface::class, $client->run($method, ['foo' => 'bar']));
        self::assertInstanceOf(RequestInterface::class, $client->getLastRequest());
        self::assertInstanceOf(ResponseInterface::class, $client->getLastResponse());
    }

    public function testRunReturnError()
    {
        $this->expectException(ErrorException::class);
        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::any())->method('getContents')->willReturn(\json_encode(['status' => 'error', 'error' => 'fooBar']));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())->method('getBody')->willReturn($stream);

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willReturn($response);

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        $client->run($method, ['foo' => 'bar']);
    }
    public function testRunReturnErrorWithNotManagedCode()
    {
        $this->expectException(UnknownException::class);
        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::any())->method('getContents')->willReturn(\json_encode(
            [
                'status' => 'error',
                'error' => [
                    'message' => 'fooBar',
                    'code' => 'E_NOT_MANAGED',
                ],
            ]
        ));

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())->method('getBody')->willReturn($stream);

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willReturn($response);

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        $client->run($method, ['foo' => 'bar']);
    }

    public function testRunWithExceptionOnExecute()
    {
        $this->expectException(RequestFailureException::class);
        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willThrowException(new \Exception('fooBar'));

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        $client->run($method, ['foo' => 'bar']);
    }

    public function testRunWithWithNoResponseStream()
    {
        $this->expectException(RequestFailureException::class);
        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())->method('getBody')->willReturn(null);

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willReturn($response);

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        $client->run($method, ['foo' => 'bar']);
    }

    public function testRunWithWithOAUthIssue()
    {
        $this->expectException(RequestFailureException::class);

        $uri = $this->uriString;

        $this->buildUri()
            ->expects(self::once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withHost')
            ->with('foo.bar')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPort')
            ->with('8080')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withPath')
            ->with('/path/api')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withQuery')
            ->with('method=toCall')
            ->willReturnSelf();

        $this->buildUri()
            ->expects(self::once())
            ->method('withFragment')
            ->with('archor=true')
            ->willReturnSelf();

        $now = $this->getDate();
        $oauth = [
            'oauth_consumer_key' => 'consumerKey',
            'oauth_token' => 'token',
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => 'consumerSecret&tokenSecret',
        ];

        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        $this->buildRequest()
            ->expects(self::exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Authorization'],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildTransport()
            ->expects(self::once())
            ->method('createStream')
            ->with([
                ['name' => 'request', 'contents' => 1],
                ['name' => 'io_mode', 'contents' => 'json'],
                ['name' => 'do_in', 'contents' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo' => 'bar'],
                ])],
            ])
            ->willReturn($this->buildStream());

        $this->buildRequest()
            ->expects(self::once())
            ->method('withBody')
            ->with($this->buildStream())
            ->willReturnSelf();

        $method = $this->createMock(MethodInterface::class);
        $method->expects(self::any())
            ->method('__toString')
            ->willReturn('collection.method');

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects(self::any())->method('getContents')->willReturn('oauth_problem=true');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::any())->method('getBody')->willReturn($stream);

        $this->buildTransport()
            ->expects(self::once())
            ->method('execute')
            ->with($this->buildRequest())
            ->willReturn($response);

        $client = $this->buildClient();

        $client->setApiUrl($uri);
        $client->setOAuthConsumerKey('consumerKey');
        $client->setOAuthConsumerSecret('consumerSecret');
        $client->setOAuthAccessToken('token');
        $client->setOAuthAccessTokenSecret('tokenSecret');
        $client->run($method, ['foo' => 'bar']);
    }
}
