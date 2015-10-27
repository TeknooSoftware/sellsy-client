<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Curl\ErrorException;
use Teknoo\Curl\RequestGenerator;
use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;

/**
 * Class ClientTest.
 *
 * @covers Teknoo\Sellsy\Client\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectionGeneratorInterface
     */
    protected function buildCollectionGeneratorMock()
    {
        return $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RequestGenerator
     */
    protected function buildRequestGeneratorMock()
    {
        return $this->getMock('Teknoo\Curl\RequestGenerator');
    }

    public function testSetApiUrl()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $this->assertSame($client, $client->setApiUrl('http://fooBar'));
        $this->assertEquals('http://fooBar', $client->getApiUrl());
    }

    public function testGetApiUrl()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator, 'http://fooBar');
        $this->assertEquals('http://fooBar', $client->getApiUrl());
        $client2 = new Client($requestGenerator, $collectionGenerator);
        $this->assertEmpty($client2->getApiUrl());
    }

    public function testSetOAuthAccessToken()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $this->assertSame($client, $client->setOAuthAccessToken('fooBar'));
        $this->assertEquals('fooBar', $client->getOAuthAccessToken());
    }

    public function testGetOAuthAccessToken()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator, '', 'fooBar');
        $this->assertEquals('fooBar', $client->getOAuthAccessToken());
        $client2 = new Client($requestGenerator, $collectionGenerator);
        $this->assertEmpty($client2->getOAuthAccessToken());
    }

    public function testSetOAuthAccessTokenSecret()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $this->assertSame($client, $client->setOAuthAccessTokenSecret('fooBar'));
        $this->assertEquals('fooBar', $client->getOAuthAccessTokenSecret());
    }

    public function testGetOAuthAccessTokenSecret()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator, '', '', 'fooBar');
        $this->assertEquals('fooBar', $client->getOAuthAccessTokenSecret());
        $client2 = new Client($requestGenerator, $collectionGenerator);
        $this->assertEmpty($client2->getOAuthAccessTokenSecret());
    }

    public function testSetOAuthConsumerKey()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $this->assertSame($client, $client->setOAuthConsumerKey('fooBar'));
        $this->assertEquals('fooBar', $client->getOAuthConsumerKey());
    }

    public function testGetOAuthConsumerKey()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator, '', '', '', 'fooBar');
        $this->assertEquals('fooBar', $client->getOAuthConsumerKey());
        $client2 = new Client($requestGenerator, $collectionGenerator);
        $this->assertEmpty($client2->getOAuthConsumerKey());
    }

    public function testSetOAuthConsumerSecret()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $this->assertSame($client, $client->setOAuthConsumerSecret('fooBar'));
        $this->assertEquals('fooBar', $client->getOAuthConsumerSecret());
    }

    public function testGetOAuthConsumerSecret()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator, '', '', '', '', 'fooBar');
        $this->assertEquals('fooBar', $client->getOAuthConsumerSecret());
        $client2 = new Client($requestGenerator, $collectionGenerator);
        $this->assertEmpty($client2->getOAuthConsumerSecret());
    }

    public function testRequestApiBadCall()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willThrowException(new ErrorException('Bad Request'));

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (RequestFailureException $e) {
            $this->assertInstanceOf('Teknoo\Curl\ErrorException', $e->getPrevious());
        } catch (\Exception $e) {
            $this->fail('Error, on request error, the client must throw an RequestFailureException exception');
        }
    }

    public function testRequestApiOAuthError()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn('oauth_problem error');

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (RequestFailureException $e) {
            $this->assertNull($e->getPrevious());
        } catch (\Exception $e) {
            $this->fail('Error, on oauth error, the client must throw an RequestFailureException exception');
        }
    }

    public function testRequestApiServerError()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => 'message error',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Teknoo\Sellsy\Client\Exception\ErrorException $e) {
            $this->assertEquals('message error', $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Error, on bad request, the client must throw an ErrorException exception');
        }
    }

    public function testRequestApiServerError2()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => array(
                            'code' => 'code',
                            'message' => 'message error object',
                        ),
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Teknoo\Sellsy\Client\Exception\ErrorException $e) {
            $this->assertEquals('message error object', $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Error, on bad request, the client must throw an ErrorException exception');
        }
    }

    public function testRequestApiServerError3()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => array(
                            'code' => 'code',
                            'fooBar' => 'message error object',
                        ),
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Teknoo\Sellsy\Client\Exception\ErrorException $e) {
            $this->assertEquals(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => array(
                            'code' => 'code',
                            'fooBar' => 'message error object',
                        ),
                    )
                ),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            $this->fail('Error, on bad request, the client must throw an ErrorException exception');
        }
    }

    public function testRequestApiGood()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            )
        );
    }

    public function testRequestApiGoodAutoDateTime()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret'
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            )
        );
    }

    public function testRequestApiGoodSsl()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('https://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => true,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'https://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            )
        );
    }

    public function testGetLastRequestNotExec()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => 'message error',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Exception $e) {
            //Do nothing
        }

        $this->assertEquals(
            array(
                'method' => 'collectionName.methodName',
                'params' => array(
                    'foo' => 'bar',
                ),
            ),
            $client->getLastRequest()
        );
    }

    public function testGetLastRequestExec()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('https://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => true,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'https://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $client->requestApi(
            array(
                'method' => 'collectionName.methodName',
                'params' => array(
                    'foo' => 'bar',
                ),
            )
        );

        $this->assertEquals(
            array(
                'method' => 'collectionName.methodName',
                'params' => array(
                    'foo' => 'bar',
                ),
            ),
            $client->getLastRequest()
        );
    }

    public function testGetLastAnswerNotExec()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('http://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => false,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'error',
                        'error' => 'message error',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'http://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Exception $e) {
            //Do nothing
        }

        $this->assertEmpty($client->getLastAnswer());
    }

    public function testGetLastAnswerExec()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('https://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => true,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'https://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $client->requestApi(
            array(
                'method' => 'collectionName.methodName',
                'params' => array(
                    'foo' => 'bar',
                ),
            )
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->getLastAnswer()
        );
    }

    public function testGetLastAnswerErrorAfterSuccess()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('setUrl')
            ->with($this->equalTo('https://fooBar'))
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->atLeastOnce())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => true,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $counter = 0;
        $request->expects($this->atLeastOnce())
            ->method('execute')
            ->willReturnCallback(
                function () use (&$counter) {
                    if (0 == $counter++) {
                        return json_encode(
                            array(
                                'status' => 'success',
                                'result' => 'ok',
                            )
                        );
                    } else {
                        return 'oauth_problem error';
                    }
                }
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'https://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $client->requestApi(
            array(
                'method' => 'collectionName.methodName',
                'params' => array(
                    'foo' => 'bar',
                ),
            )
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->getLastAnswer()
        );

        try {
            $client->requestApi(
                array(
                    'method' => 'collectionName.methodName',
                    'params' => array(
                        'foo' => 'bar',
                    ),
                )
            );
        } catch (\Exception $e) {
            //do nothing
        }

        $this->assertEmpty($client->getLastAnswer());
    }

    public function testGetInfos()
    {
        $request = $this->getMock('Teknoo\Curl\RequestInterface');

        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $requestGenerator->expects($this->atLeastOnce())
            ->method('getRequest')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setMethod')
            ->with($this->equalTo('POST'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setUrl')
            ->with($this->equalTo('https://fooBar'))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('setReturnValue')
            ->with($this->equalTo(true))
            ->willReturn($request);

        //Need for PHP 5.3
        $that = $this;
        $request->expects($this->once())
            ->method('setOptionArray')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($args) use ($request, $that) {
                    $oAuth = explode(', ', $args[CURLOPT_HTTPHEADER][0]);
                    unset($oAuth[2]);
                    $args[CURLOPT_HTTPHEADER][0] = implode(', ', $oAuth);
                    $that->assertEquals(
                        array(
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: OAuth oauth_consumer_key="key", oauth_token="token", oauth_timestamp="1424260800", oauth_signature_method="PLAINTEXT", oauth_version="1.0", oauth_signature="cSecret%26secret"',
                                'Expect:',
                            ),
                            CURLOPT_POSTFIELDS => array(
                                'request' => 1,
                                'io_mode' => 'json',
                                'do_in' => '{"method":"collectionName.methodName","params":{"foo":"bar"}}',
                            ),
                            CURLOPT_SSL_VERIFYPEER => true,
                        ),
                        $args
                    );

                    return $request;
                }
            )
            ->willReturn($request);

        $request->expects($this->once())
            ->method('execute')
            ->willReturn(
                json_encode(
                    array(
                        'status' => 'success',
                        'result' => 'ok',
                    )
                )
            );

        $client = new Client(
            $requestGenerator,
            $collectionGenerator,
            'https://fooBar',
            'token',
            'secret',
            'key',
            'cSecret',
            new \DateTime('2015-02-18 12:00:00', new \DateTimeZone('UTC'))
        );

        $this->assertEquals(
            (object) array(
                'status' => 'success',
                'result' => 'ok',
            ),
            $client->getInfos()
        );
    }

    public function testAccountData()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Accountdatas')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->accountData());
    }

    public function testAccountPrefs()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('AccountPrefs')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->accountPrefs());
    }

    public function testPurchase()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Purchase')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->purchase());
    }

    public function testAgenda()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Agenda')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->agenda());
    }

    public function testAnnotations()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Annotations')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->annotations());
    }

    public function testCatalogue()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Catalogue')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->catalogue());
    }

    public function testCustomFields()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('CustomFields')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->customFields());
    }

    public function testClient()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Client')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->client());
    }

    public function testStaffs()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Staffs')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->staffs());
    }

    public function testPeoples()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Peoples')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->peoples());
    }

    public function testDocument()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Document')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->document());
    }

    public function testMails()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Mails')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->mails());
    }

    public function testEvent()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Event')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->event());
    }

    public function testExpense()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Expense')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->expense());
    }

    public function testOpportunities()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Opportunities')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->opportunities());
    }

    public function testProspects()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Prospects')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->prospects());
    }

    public function testSmartTags()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('SmartTags')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->smartTags());
    }

    public function testStat()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Stat')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->stat());
    }

    public function testStock()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Stock')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->stock());
    }

    public function testSupport()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Support')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->support());
    }

    public function testTimeTracking()
    {
        $collectionGenerator = $this->buildCollectionGeneratorMock();
        $requestGenerator = $this->buildRequestGeneratorMock();
        $client = new Client($requestGenerator, $collectionGenerator);

        $collection = $this->getMock('Teknoo\Sellsy\Client\Collection\CollectionInterface');
        $collectionGenerator->expects($this->once())
            ->method('getCollection')
            ->with(
                $this->equalTo($client),
                $this->equalTo('Timetracking')
            )
            ->willReturn($collection);

        $this->assertSame($collection, $client->timeTracking());
    }
}
