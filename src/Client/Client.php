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
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @version     0.8.0
 */
namespace Teknoo\Sellsy\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Client\Collection\CollectionGeneratorInterface;
use Teknoo\Sellsy\Client\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;

/**
 * Class Client
 * Main implementation of ClientInterface to perform Sellsy API requests as a local methods.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Client implements ClientInterface
{
    /**
     * Sellsy collections of methods generator.
     *
     * @var CollectionGeneratorInterface
     */
    private $collectionGenerator;

    /**
     * @var RequestInterface
     */
    private $uri;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var callable
     */
    private $streamGenerator;

    /**
     * @var HttpClientBridgeInterface
     */
    private $httpClientBridge;

    /**
     * API End point.
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
     * Var to store the last request to facility debugging.
     *
     * @var array
     */
    private $lastRequest;

    /**
     * Var to store the last answer of Sellsy API to facility debugging.
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
     * @param UriInterface $uri
     * @param RequestInterface $request
     * @param callable $streamGenerator
     * @param HttpClientBridgeInterface $httpClientBridge
     * @param CollectionGeneratorInterface $collectionGenerator
     * @param string $apiUrl
     * @param string $oauthAccessToken
     * @param string $oauthAccessTokenSecret
     * @param string $oauthConsumerKey
     * @param string $oauthConsumerSecret
     * @param \DateTime|null $now
     */
    public function __construct(
        UriInterface $uri,
        RequestInterface $request,
        callable $streamGenerator,
        HttpClientBridgeInterface $httpClientBridge,
        CollectionGeneratorInterface $collectionGenerator,
        string $apiUrl = '',
        string $oauthAccessToken = '',
        string $oauthAccessTokenSecret = '',
        string $oauthConsumerKey = '',
        string $oauthConsumerSecret = '',
        \DateTime $now = null
    ) {
        $this->uri = $uri;
        $this->request = $request;
        $this->streamGenerator = $streamGenerator;
        $this->httpClientBridge = $httpClientBridge;
        $this->collectionGenerator = $collectionGenerator;
        $this->setApiUrl($apiUrl);
        $this->setOAuthAccessToken($oauthAccessToken);
        $this->setOAuthAccessTokenSecret($oauthAccessTokenSecret);
        $this->setOAuthConsumerKey($oauthConsumerKey);
        $this->setOAuthConsumerSecret($oauthConsumerSecret);
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
    public function setOAuthAccessToken(string $oauthAccessToken): ClientInterface
    {
        $this->oauthAccessToken = $oauthAccessToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthAccessTokenSecret(string $oauthAccessTokenSecret): ClientInterface
    {
        $this->oauthAccessTokenSecret = $oauthAccessTokenSecret;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthConsumerKey(string $oauthConsumerKey): ClientInterface
    {
        $this->oauthConsumerKey = $oauthConsumerKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthConsumerSecret(string $oauthConsumerSecret): ClientInterface
    {
        $this->oauthConsumerSecret = $oauthConsumerSecret;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    private function getNewRequest(): RequestInterface
    {
        return clone $this->request;
    }

    /**
     * Transform an array to HTTP headers OAuth string.
     *
     * @param array $oauth
     *
     * @return string
     */
    private function encodeOAuthHeaders(&$oauth)
    {
        $values = array();
        foreach ($oauth as $key => &$value) {
            $values[] = $key.'="'.\rawurlencode($value).'"';
        }

        return 'OAuth '.\implode(', ', $values);
    }

    /**
     * Internal method to generate HTTP headers to use for the API authentication with OAuth protocol.
     * @param RequestInterface $request
     */
    private function setOAuthHeaders(RequestInterface $request)
    {
        if ($this->now instanceof \DateTime) {
            $now = clone $this->now;
        } else {
            $now = new \DateTime();
        }

        //Generate HTTP headers
        $encodedKey = \rawurlencode($this->oauthConsumerSecret).'&'.\rawurlencode($this->oauthAccessTokenSecret);
        $oauthParams = array(
            'oauth_consumer_key' => $this->oauthConsumerKey,
            'oauth_token' => $this->oauthAccessToken,
            'oauth_nonce' => \md5($now->getTimestamp() + \rand(0, 1000)),
            'oauth_timestamp' => $now->getTimestamp(),
            'oauth_signature_method' => 'PLAINTEXT',
            'oauth_version' => '1.0',
            'oauth_signature' => $encodedKey,
        );

        $request->withHeader('Authorization', $this->encodeOAuthHeaders($oauthParams));
        $request->withHeader('Expect', '');
    }

    /**
     * @return UriInterface
     */
    private function getNewUri(): UriInterface
    {
        return clone $this->uri;
    }

    /**
     * @param RequestInterface $request
     */
    private function setUri(RequestInterface $request)
    {
        $uri = $this->getNewUri();

        if (!empty($this->apiUrl['scheme'])) {
            $uri->withScheme($this->apiUrl['scheme']);
        }

        if (!empty($this->apiUrl['host'])) {
            $uri->withHost($this->apiUrl['host']);
        }

        if (!empty($this->apiUrl['port'])) {
            $uri->withPort($this->apiUrl['port']);
        }

        if (!empty($this->apiUrl['path'])) {
            $uri->withPath($this->apiUrl['path']);
        }

        if (!empty($this->apiUrl['query'])) {
            $uri->withQuery($this->apiUrl['query']);
        }

        if (!empty($this->apiUrl['fragment'])) {
            $uri->withFragment($this->apiUrl['fragment']);
        }

        $request->withUri($uri);
    }

    /**
     * @return StreamInterface
     */
    private function getNewStream(): StreamInterface
    {
        return ($this->streamGenerator)();
    }

    /**
     * @param RequestInterface $request
     * @param array $requestSettings
     */
    private function setBodyRequest(RequestInterface $request, array &$requestSettings)
    {
        $stream = $this->getNewStream();
        $stream->rewind();
        $stream->write(\http_build_query($requestSettings));

        $request->withBody($stream);
    }

    /**
     * Method to perform a request to the api.
     *
     * @param array $requestSettings
     *
     * @return \stdClass
     *
     * @throws RequestFailureException is the request can not be performed on the server
     * @throws ErrorException          if the server returned an error for this request
     */
    public function requestApi(array $requestSettings)
    {
        //Arguments for the Sellsy API
        $this->lastRequest = $requestSettings;
        $this->lastResponse = null;
        $encodedRequest = array(
            'request' => 1,
            'io_mode' => 'json',
            'do_in' => \json_encode($requestSettings),
        );

        //Generate client request
        $request = $this->getNewRequest();

        //Configure to contact the api with POST request and return value
        $request->withMethod('POST');

        $this->setUri($request);
        $this->setOAuthHeaders($request);
        $this->setBodyRequest($request, $encodedRequest);

        //Execute the request
        try {
            $this->lastResponse = $this->httpClientBridge->execute($request);
        } catch (\Exception $e) {
            throw new RequestFailureException($e->getMessage(), $e->getCode(), $e);
        }

        $body = $this->lastResponse->getBody();
        if (!$body instanceof StreamInterface) {
            throw new ErrorException("Bad body response");
        }

        //OAuth issue, throw an exception
        $result = (string) $body->getContents();
        if (false !== \strpos($result, 'oauth_problem')) {
            throw new RequestFailureException($result);
        }

        $answer = \json_decode($result);

        //Bad request, error returned by the api, throw an error
        if (!empty($answer->status) && 'error' == $answer->status) {
            if (!empty($answer->error->message)) {
                //Retrieve error message like it's defined in Sellsy API documentation
                throw new ErrorException($answer->error->message);
            } elseif (\is_string($answer->error)) {
                //Retrieve error message (sometime, error is not an object...)
                throw new ErrorException($answer->error);
            } else {
                //Other case, return directly the answer
                throw new ErrorException($result);
            }
        }

        $this->lastResponse = $answer;

        return $this->lastResponse;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function getInfos()
    {
        $requestSettings = [
            'method' => 'Infos.getInfos',
            'params' => [],
        ];

        return $this->requestApi($requestSettings);
    }

    /**
     * {@inheritdoc}
     */
    public function accountData(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Accountdatas');
    }

    /**
     * {@inheritdoc}
     */
    public function accountPrefs(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'AccountPrefs');
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Purchase');
    }

    /**
     * {@inheritdoc}
     */
    public function agenda(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Agenda');
    }

    /**
     * {@inheritdoc}
     */
    public function annotations(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Annotations');
    }

    /**
     * {@inheritdoc}
     */
    public function catalogue(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Catalogue');
    }

    /**
     * {@inheritdoc}
     */
    public function customFields(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'CustomFields');
    }

    /**
     * {@inheritdoc}
     */
    public function client(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Client');
    }

    /**
     * {@inheritdoc}
     */
    public function staffs(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Staffs');
    }

    /**
     * {@inheritdoc}
     */
    public function peoples(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Peoples');
    }

    /**
     * {@inheritdoc}
     */
    public function document(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Document');
    }

    /**
     * {@inheritdoc}
     */
    public function mails(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Mails');
    }

    /**
     * {@inheritdoc}
     */
    public function event(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Event');
    }

    /**
     * {@inheritdoc}
     */
    public function expense(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Expense');
    }

    /**
     * {@inheritdoc}
     */
    public function opportunities(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Opportunities');
    }

    /**
     * {@inheritdoc}
     */
    public function prospects(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Prospects');
    }

    /**
     * {@inheritdoc}
     */
    public function smartTags(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'SmartTags');
    }

    /**
     * {@inheritdoc}
     */
    public function stat(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Stat');
    }

    /**
     * {@inheritdoc}
     */
    public function stock(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Stock');
    }

    /**
     * {@inheritdoc}
     */
    public function support(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Support');
    }

    /**
     * {@inheritdoc}
     */
    public function timeTracking(): CollectionInterface
    {
        return $this->collectionGenerator->getCollection($this, 'Timetracking');
    }
}
