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
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Sellsy\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class Client
 * Implementation of an HTTP+OAuth client to use the Sellsy API with your credentials to execute some operations on
 * your account.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Client implements ClientInterface
{
    /**
     * Transport instance to dialog with Sellsy api.
     *
     * @var TransportInterface
     */
    private $transport;

    /**
     * Sellsy API End point.
     *
     * @var array
     */
    private $apiUrl;

    /**
     * OAuth access token (provided by Sellsy).
     *
     * @var string
     */
    private $oauthAccessToken;

    /**
     * OAuth secret (provided by Sellsy).
     *
     * @var string
     */
    private $oauthAccessTokenSecret;

    /**
     * OAuth consumer token (provided by Sellsy).
     *
     * @var string
     */
    private $oauthConsumerKey;

    /**
     * OAuth consumer secret  (provided by Sellsy).
     *
     * @var string
     */
    private $oauthConsumerSecret;

    /**
     * Var to store the last PSR7 request to facility debugging.
     *
     * @var array
     */
    private $lastRequest;

    /**
     * Var to store the last PSR7 answer of Sellsy API to facility debugging.
     *
     * @var mixed|\stdClass
     */
    private $lastResponse;

    /**
     * @var \DateTime
     */
    private $now;

    /**
     * Client constructor.
     *
     * @param TransportInterface $transport
     * @param string             $apiUrl
     * @param string             $accessToken
     * @param string             $accessTokenSecret
     * @param string             $consumerKey
     * @param string             $consumerSecret
     * @param \DateTime|null     $now
     */
    public function __construct(
        TransportInterface $transport,
        string $apiUrl = '',
        string $accessToken = '',
        string $accessTokenSecret = '',
        string $consumerKey = '',
        string $consumerSecret = '',
        \DateTime $now = null
    ) {
        $this->transport = $transport;
        $this->setApiUrl($apiUrl);
        $this->setOAuthAccessToken($accessToken);
        $this->setOAuthAccessTokenSecret($accessTokenSecret);
        $this->setOAuthConsumerKey($consumerKey);
        $this->setOAuthConsumerSecret($consumerSecret);
        $this->now = $now;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiUrl(string $apiUrl): ClientInterface
    {
        $this->apiUrl = \parse_url($apiUrl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthAccessToken(string $accessToken): ClientInterface
    {
        $this->oauthAccessToken = $accessToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthAccessTokenSecret(string $accessTokenSecret): ClientInterface
    {
        $this->oauthAccessTokenSecret = $accessTokenSecret;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthConsumerKey(string $consumerKey): ClientInterface
    {
        $this->oauthConsumerKey = $consumerKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthConsumerSecret(string $consumerSecret): ClientInterface
    {
        $this->oauthConsumerSecret = $consumerSecret;

        return $this;
    }

    /**
     * To get a new PSR7 request from transport instance to be able to dialog with Sellsy API.
     *
     * @param string       $method
     * @param UriInterface $uri
     *
     * @return RequestInterface
     */
    private function getNewRequest(string $method, UriInterface $uri): RequestInterface
    {
        return $this->transport->createRequest($method, $uri);
    }

    /**
     * Transform an the OAuth array configuration to HTTP headers OAuth string.
     *
     * @param array $oauth
     *
     * @return string
     */
    private function encodeOAuthHeaders(&$oauth)
    {
        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        return 'OAuth '.\implode(', ', $values);
    }

    /**
     * Internal method to generate HTTP headers to use for the API authentication with OAuth protocol.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    private function setOAuthHeaders(RequestInterface $request): RequestInterface
    {
        $now = new \DateTime();
        if ($this->now instanceof \DateTime) {
            $now = clone $this->now;
        }

        //Generate HTTP headers
        $encodedKey = \rawurlencode($this->oauthConsumerSecret).'&'.\rawurlencode($this->oauthAccessTokenSecret);
        $oauthParams = [
            'oauth_consumer_key' => $this->oauthConsumerKey,
            'oauth_token' => $this->oauthAccessToken,
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => $encodedKey,
        ];

        $request = $request->withHeader('Authorization', $this->encodeOAuthHeaders($oauthParams));

        return $request->withHeader('Expect', '');
    }

    /**
     * @return UriInterface
     */
    private function getNewUri(): UriInterface
    {
        return $this->transport->createUri();
    }

    /**
     * To get the PSR7 Uri instance to configure the PSR7 request to be able to dialog with the Sellsy API.
     *
     * @return UriInterface
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
            $uri = $uri->withPort($this->apiUrl['port']);
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
     *
     * @param RequestInterface $request
     * @param array            $requestSettings
     *
     * @return RequestInterface
     */
    private function setBodyRequest(RequestInterface $request, array &$requestSettings): RequestInterface
    {
        //$request = $request->withHeader('Content-Type', 'multipart/form-data');

        $multipartBody = [];
        foreach ($requestSettings as $key => &$value) {
            $multipartBody[] = [
                'name' => $key,
                'contents' => $value
            ];
        }

        return $request->withBody($this->transport->createStream($multipartBody));
    }

    /**
     * {@inheritdoc}
     */
    public function run(MethodInterface $method, array $params = []): ResultInterface
    {
        //Arguments for the Sellsy API
        $this->lastResponse = null;
        $encodedRequest = [
            'request' => 1,
            'io_mode' => 'json',
            'do_in' => \json_encode([
                'method' => (string) $method,
                'params' => $params,
            ]),
        ];

        //Configure to contact the api with POST request and return value
        //Generate client request
        $request = $this->getNewRequest('POST', $this->getUri());

        $request = $this->setOAuthHeaders($request);
        $request = $this->setBodyRequest($request, $encodedRequest);

        $this->lastRequest = $request;

        //Execute the request
        try {
            $this->lastResponse = $this->transport->execute($request);
        } catch (\Exception $e) {
            throw new RequestFailureException($e->getMessage(), $e->getCode(), $e);
        }

        $body = $this->lastResponse->getBody();
        if (!$body instanceof StreamInterface) {
            throw new RequestFailureException('Bad body response');
        }

        //OAuth issue, throw an exception
        $result = (string) $body->getContents();
        if (false !== \strpos($result, 'oauth_problem')) {
            throw new RequestFailureException($result);
        }

        $answer = new Result($result);

        if ($answer->isError()) {
            //Bad request, error returned by the api, throw an error
            throw new ErrorException($answer->getErrorMessage());
        }

        return $answer;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
}
