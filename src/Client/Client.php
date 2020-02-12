<?php

/*
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

declare(strict_types=1);

namespace Teknoo\Sellsy\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Client\Exception\CustomErrorException;
use Teknoo\Sellsy\Client\Exception\DoInParamMissingException;
use Teknoo\Sellsy\Client\Exception\DoInWrongFormatException;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\IOModeDoesNotExistException;
use Teknoo\Sellsy\Client\Exception\IOModeDoInMissingException;
use Teknoo\Sellsy\Client\Exception\ListDoesNotExistException;
use Teknoo\Sellsy\Client\Exception\MaxPaginationReachedException;
use Teknoo\Sellsy\Client\Exception\MethodDoesNotExistException;
use Teknoo\Sellsy\Client\Exception\NotAllowedException;
use Teknoo\Sellsy\Client\Exception\ObjectNotEditableException;
use Teknoo\Sellsy\Client\Exception\ObjectNotLoadableException;
use Teknoo\Sellsy\Client\Exception\ObjectNotLoadedException;
use Teknoo\Sellsy\Client\Exception\ParameterInvalidException;
use Teknoo\Sellsy\Client\Exception\ParameterMissingException;
use Teknoo\Sellsy\Client\Exception\ParameterRequiredException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;
use Teknoo\Sellsy\Client\Exception\SubscribtionRequiredException;
use Teknoo\Sellsy\Client\Exception\UnknownException;
use Teknoo\Sellsy\Client\Exception\UserNotLoggedException;
use Teknoo\Sellsy\Client\Exception\ValueDoesNotInListException;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Implementation of an HTTP+OAuth client to use the Sellsy API with your credentials to execute some operations on
 * your account.
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Client implements ClientInterface
{
    // Transport to dialog with Sellsy api.
    private TransportInterface $transport;

    /**
     * Sellsy API End point.
     *
     * @var array<string, string>
     */
    private array $apiUrl;

    // OAuth access token (provided by Sellsy).
    private string $oauthUserToken;

    // OAuth secret (provided by Sellsy).
    private string $oauthUserSecret;

    //OAuth consumer token (provided by Sellsy).
    private string $oauthConsumerKey;

    // OAuth consumer secret  (provided by Sellsy).
    private string $oauthConsumerSecret;

    // Var to store the last PSR7 request to facility debugging.
    private ?RequestInterface $lastRequest = null;

    // Var to store the last PSR7 answer of Sellsy API to facility debugging.
    private ?ResponseInterface $lastResponse = null;

    private ?\DateTimeInterface $now = null;

    /**
     * @var array<string, class-string<ErrorException>>
     */
    private array $errorsExceptionMapping = [
        'E_USER_NOT_LOGGED' => UserNotLoggedException::class,
        'E_IO_MODE_DONT_EXIST' => IOModeDoesNotExistException::class,
        'E_IO_MODE_DO_IN_MISSING' => IOModeDoInMissingException::class,
        'E_DO_IN_WRONG_FORMAT' => DoInWrongFormatException::class,
        'E_METHOD_DONT_EXIT' => MethodDoesNotExistException::class,
        'E_DO_IN_PARAM_MISSING' => DoInParamMissingException::class,
        'E_PRIV_NOT_ALLOWED' => NotAllowedException::class,
        'E_SUBSCRIBE_HAVETO' => SubscribtionRequiredException::class,
        'E_PARAM_MISSING' => ParameterMissingException::class,
        'E_PARAM_INVALID' => ParameterInvalidException::class,
        'E_PARAM_REQUIRED' => ParameterRequiredException::class,
        'E_OBJ_NOT_LOADABLE' => ObjectNotLoadableException::class,
        'E_OBJ_NOT_EDITABLE' => ObjectNotEditableException::class,
        'E_OBJ_NOT_LOADED' => ObjectNotLoadedException::class,
        'E_LIST_DONT_EXIST' => ListDoesNotExistException::class,
        'E_LIST_VALUE_DONT_EXIST' => ValueDoesNotInListException::class,
        'E_PAGINATION_MAX' => MaxPaginationReachedException::class,
        'E_UNKNOW' => UnknownException::class,
        'E_CUSTOM' => CustomErrorException::class,
    ];

    public function __construct(
        TransportInterface $transport,
        string $apiUrl,
        string $accessToken,
        string $accessTokenSecret,
        string $consumerKey,
        string $consumerSecret,
        \DateTimeInterface $now = null
    ) {
        $this->transport = $transport;
        $this->apiUrl = \parse_url($apiUrl);
        $this->oauthUserToken = $accessToken;
        $this->oauthUserSecret = $accessTokenSecret;
        $this->oauthConsumerKey = $consumerKey;
        $this->oauthConsumerSecret = $consumerSecret;
        $this->now = $now;
    }

    /**
     * To get a new PSR7 request from transport instance to be able to dialog with Sellsy API.
     */
    private function createNewRequest(string $method, UriInterface $uri): RequestInterface
    {
        return $this->transport->createRequest($method, $uri);
    }

    /**
     * Transform an the OAuth array configuration to HTTP headers OAuth string.
     * @param array<string, mixed> $oauth
     */
    private function encodeOAuthHeaders(array &$oauth): string
    {
        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key . '="' . \rawurlencode((string) $value) . '"';
        }

        return 'OAuth ' . \implode(', ', $values);
    }

    /**
     * Internal method to generate HTTP headers to use for the API authentication with OAuth protocol.
     * @throws \Throwable
     */
    private function setOAuthHeaders(RequestInterface $request): RequestInterface
    {
        $now = new \DateTime();
        if ($this->now instanceof \DateTime) {
            $now = clone $this->now;
        }

        //Generate HTTP headers
        $encodedKey = \rawurlencode($this->oauthConsumerSecret) . '&' . \rawurlencode($this->oauthUserSecret);
        $oauthParams = [
            'oauth_consumer_key' => $this->oauthConsumerKey,
            'oauth_token' => $this->oauthUserToken,
            'oauth_nonce' => \sha1(\microtime(true) . \random_int(10000, 99999)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => $encodedKey,
        ];

        $request = $request->withHeader('Authorization', $this->encodeOAuthHeaders($oauthParams));

        return $request->withHeader('Expect', '');
    }

    private function getNewUri(): UriInterface
    {
        return $this->transport->createUri();
    }

    /**
     * To get the PSR7 Uri instance to configure the PSR7 request to be able to dialog with the Sellsy API.
     */
    private function getUri(): UriInterface
    {
        $uri = $this->getNewUri();

        if (!empty($this->apiUrl['scheme'])) {
            $uri = $uri->withScheme($this->apiUrl['scheme']);
        }

        if (!empty($this->apiUrl['host'])) {
            $uri = $uri->withHost($this->apiUrl['host']);
        }

        if (!empty($this->apiUrl['port'])) {
            $uri = $uri->withPort((int) $this->apiUrl['port']);
        }

        if (!empty($this->apiUrl['path'])) {
            $uri = $uri->withPath($this->apiUrl['path']);
        }

        if (!empty($this->apiUrl['query'])) {
            $uri = $uri->withQuery($this->apiUrl['query']);
        }

        if (!empty($this->apiUrl['fragment'])) {
            $uri = $uri->withFragment($this->apiUrl['fragment']);
        }

        return $uri;
    }

    /**
     * To register method's argument in the request for the Sellsy API.
     * @param array<string, string> $requestSettings
     **/
    private function setBodyRequest(RequestInterface $request, array &$requestSettings): RequestInterface
    {
        $multipartBody = [];
        foreach ($requestSettings as $key => &$value) {
            $multipartBody[] = [
                'name' => $key,
                'contents' => $value
            ];
        }

        return $request->withBody($this->transport->createStream($multipartBody, $request));
    }

    /**
     * @param array<string, mixed> $params
     * @throws \Throwable
     */
    private function prepareRequest(MethodInterface $method, array $params = []): RequestInterface
    {
        //Arguments for the Sellsy API
        $this->lastResponse = null;
        $encodedRequest = [
            'request' => 1,
            'io_mode' => 'json',
            'do_in' => \json_encode(
                [
                    'method' => (string) $method,
                    'params' => $params,
                ],
                JSON_THROW_ON_ERROR
            ),
        ];

        //Configure to contact the api with POST request and return value
        //Generate client request
        $request = $this->createNewRequest('POST', $this->getUri());

        $request = $this->setOAuthHeaders($request);
        $request = $this->setBodyRequest($request, $encodedRequest);

        return $request;
    }

    /**
     * @param ResponseInterface $response
     */
    private function parseResponse(ResponseInterface $response): ResultInterface
    {
        $body = $response->getBody();
        if (!$body instanceof StreamInterface) {
            throw new RequestFailureException('Bad body response', 500);
        }

        //OAuth issue, throw an exception
        $result = (string) $body->getContents();
        if (false !== \strpos($result, 'oauth_problem')) {
            throw new RequestFailureException($result);
        }

        return new Result($result);
    }

    /**
     * @throws ErrorException
     */
    private function parseError(ResultInterface $result): void
    {
        if (isset($this->errorsExceptionMapping[$result->getErrorCode()])) {
            $classException = $this->errorsExceptionMapping[$result->getErrorCode()];
            throw new $classException($result);
        }

        throw new UnknownException($result);
    }

    /**
     * {@inheritdoc}
     * @param array<string, mixed> $params
     * @throws RequestFailureException
     * @throws ErrorException
     */
    public function run(MethodInterface $method, array $params = []): ResultInterface
    {
        $result = null;
        $promise = $this->promise($method, $params);

        return $promise->wait();
    }

    /**
     * {@inheritdoc}
     * @param array<string, mixed> $params
     * @throws RequestFailureException
     * @throws ErrorException
     */
    public function promise(MethodInterface $method, array $params = []): PromiseInterface
    {
        try {
            $this->lastRequest = $request = $this->prepareRequest($method, $params);

            //Execute the request
            $promise = $this->transport->asyncExecute($request);
            $promise = $promise->then(
                function (ResponseInterface $response) {
                    $this->lastResponse = $response;

                    $answer = $this->parseResponse($response);

                    if ($answer->isError()) {
                        //Bad request, error returned by the api, throw an error
                        $this->parseError($answer);
                    }

                    return $answer;
                },
                function (\Throwable $e) {
                    throw new RequestFailureException($e->getMessage(), $e->getCode(), $e);
                }
            );

            return $promise;
        } catch (\Throwable $e) {
            throw new RequestFailureException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLastRequest(): ?RequestInterface
    {
        return $this->lastRequest;
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }
}
