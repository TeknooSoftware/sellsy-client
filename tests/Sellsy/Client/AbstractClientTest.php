<?php

namespace Teknoo\Tests\Sellsy\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class AbstractClientTest
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
abstract class AbstractClientTest extends \PHPUnit_Framework_TestCase
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
            $this->transport->expects(self::any())->method('createStream')->willReturn($this->buildStream());
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
                ['Authorization', ],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('rewind')
            ->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('write')
            ->with(\http_build_query([
                'request' => 1,
                'io_mode' => 'json',
                'do_in' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo'=>'bar'],
                ])
            ]))
            ->willReturnSelf();

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
        $stream->expects(self::any())->method('getContents')->willReturn(\json_encode(['status'=>'success', 'response'=>['foo'=>'bar']]));

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
        self::assertInstanceOf(ResultInterface::class, $client->run($method, ['foo'=>'bar']));
        self::assertInstanceOf(RequestInterface::class, $client->getLastRequest());
        self::assertInstanceOf(ResponseInterface::class, $client->getLastResponse());
    }

    /**
     * @expectedException \Teknoo\Sellsy\Client\Exception\ErrorException
     */
    public function testRunReturnError()
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
                ['Authorization', ],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('rewind')
            ->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('write')
            ->with(\http_build_query([
                'request' => 1,
                'io_mode' => 'json',
                'do_in' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo'=>'bar'],
                ])
            ]))
            ->willReturnSelf();

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
        $stream->expects(self::any())->method('getContents')->willReturn(\json_encode(['status'=>'error', 'error'=>'fooBar']));

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
        $client->run($method, ['foo'=>'bar']);
    }

    /**
     * @expectedException \Teknoo\Sellsy\Client\Exception\RequestFailureException
     */
    public function testRunWithExceptionOnExecute()
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
                ['Authorization', ],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('rewind')
            ->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('write')
            ->with(\http_build_query([
                'request' => 1,
                'io_mode' => 'json',
                'do_in' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo'=>'bar'],
                ])
            ]))
            ->willReturnSelf();

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
        $client->run($method, ['foo'=>'bar']);
    }

    /**
     * @expectedException \Teknoo\Sellsy\Client\Exception\RequestFailureException
     */
    public function testRunWithWithNoResponseStream()
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
                ['Authorization', ],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('rewind')
            ->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('write')
            ->with(\http_build_query([
                'request' => 1,
                'io_mode' => 'json',
                'do_in' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo'=>'bar'],
                ])
            ]))
            ->willReturnSelf();

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
        $client->run($method, ['foo'=>'bar']);
    }

    /**
    * @expectedException \Teknoo\Sellsy\Client\Exception\RequestFailureException
    */
    public function testRunWithWithOAUthIssue()
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
                ['Authorization', ],
                ['Expect', '']
            )->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('rewind')
            ->willReturnSelf();

        $this->buildStream()
            ->expects(self::once())
            ->method('write')
            ->with(\http_build_query([
                'request' => 1,
                'io_mode' => 'json',
                'do_in' => \json_encode([
                    'method' => 'collection.method',
                    'params' => ['foo'=>'bar'],
                ])
            ]))
            ->willReturnSelf();

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
        $client->run($method, ['foo'=>'bar']);
    }
}