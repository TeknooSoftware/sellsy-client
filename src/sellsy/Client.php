<?php

namespace UniAlteri\Sellsy\Client;

use UniAlteri\Curl\RequestGenerator;

/**
 * Class Client
 * @package UniAlteri\Sellsy\Client
 */
class Client
{
    /**
     * @var RequestGenerator $requestGenerator
     */
    protected $requestGenerator;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $oauthAccessToken;

    /**
     * @var string
     */
    private $oauthAccessTokenSecret;

    /**
     * @var string
     */
    protected $oauthConsumerKey;

    /**
     * @var string
     */
    private $oauthConsumerSecret;

    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $lastRequest;

    /**
     * @var mixed|\stdClass
     */
    protected $lastAnswer;

    /**
     * Constructor
     * @param RequestGenerator $requestGenerator
     * @param string $apiUrl
     * @param string $oauthAccessToken
     * @param string $oauthAccessTokenSecret
     * @param string $oauthConsumerKey
     * @param string $oauthConsumerSecret
     */
    public function __construct(RequestGenerator $requestGenerator, $apiUrl, $oauthAccessToken, $oauthAccessTokenSecret, $oauthConsumerKey, $oauthConsumerSecret)
    {
        $this->requestGenerator = $requestGenerator;
        $this->apiUrl = $apiUrl;
        $this->oauthAccessToken = $oauthAccessToken;
        $this->oauthAccessTokenSecret = $oauthAccessTokenSecret;
        $this->oauthConsumerKey = $oauthConsumerKey;
        $this->oauthConsumerSecret = $oauthConsumerSecret;
    }

    /**
     * Update the api url
     * @param string $apiUrl
     * @return $this
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        //Clean header to rebuild them
        $this->header = null;

        return $this;
    }

    /**
     * Get the api url
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Update the OAuth access token
     * @param string $oauthAccessToken
     * @return $this
     */
    public function setOAuthAccessToken($oauthAccessToken)
    {
        $this->oauthAccessToken = $oauthAccessToken;

        //Clean header to rebuild them
        $this->header = null;

        return $this;
    }

    /**
     * Get the OAuth access token
     * @return string
     */
    public function getOAuthAccessToken()
    {
        return $this->oauthAccessToken;
    }

    /**
     * Update the OAuth access secret token
     * @param string $oauthConsumerSecret
     * @return $this
     */
    public function setOAuthAccessTokenSecret($oauthConsumerSecret)
    {
        $this->oauthConsumerSecret = $oauthConsumerSecret;

        //Clean header to rebuild them
        $this->header = null;

        return $this;
    }

    /**
     * Get the OAuth access secret token
     * @return string
     */
    public function getOAuthAccessTokenSecret()
    {
        return $this->oauthConsumerSecret;
    }

    /**
     * Update the OAuth consumer key
     * @param string $oauthConsumerKey
     * @return $this
     */
    public function setOAuthConsumerKey($oauthConsumerKey)
    {
        $this->oauthConsumerKey = $oauthConsumerKey;

        //Clean header to rebuild them
        $this->header = null;

        return $this;
    }

    /**
     * Get the OAuth consumer key
     * @return string
     */
    public function getOAuthConsumerKey()
    {
        return $this->oauthConsumerKey;
    }

    /**
     * Update the OAuth consumer secret
     * @param string $oauthConsumerSecret
     * @return $this
     */
    public function setOAuthConsumerSecret($oauthConsumerSecret)
    {
        $this->oauthConsumerSecret = $oauthConsumerSecret;

        //Clean header to rebuild them
        $this->header = null;

        return $this;
    }

    /**
     * Get the OAuth consumer secret
     * @return string
     */
    public function getOAuthConsumerSecret()
    {
        return $this->oauthConsumerSecret;
    }

    /**
     * Transform an array to HTTP headers OAuth string
     * @param array $oauth
     * @return string
     */
    protected function encodeHeaders(&$oauth)
    {
        $values = [];
        foreach ($oauth as $key => &$value) {
            $values[] = '$key="'.rawurlencode($value).'"';
        }

        return 'Authorization: OAuth '.implode(', ', $values);
    }

    /**
     * Internal method to generate HTTP headers to use for the API authentication with OAuth protocol
     */
    protected function computeHeaders()
    {
        if (empty($this->header)) {
            //Generate HTTP headers
            $encodedKey = rawurlencode($this->oauthConsumerSecret) . '&' . rawurlencode($this->oauthAccessTokenSecret);
            $oauthParams = [
                'oauth_consumer_key' => $this->oauthConsumerKey,
                'oauth_token' => $this->oauthAccessToken,
                'oauth_nonce' => md5(time() + rand(0, 1000)),
                'oauth_timestamp' => time(),
                'oauth_signature_method' => 'PLAINTEXT',
                'oauth_version' => '1.0',
                'oauth_signature' => $encodedKey
            ];

            //Generate header
            $this->header = [$this->encodeHeaders($oauthParams), 'Expect:'];
        }

        return $this->header;
    }

    /**
     * @return array
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return mixed|\stdClass
     */
    public function getLastAnswer()
    {
        return $this->lastAnswer;
    }

    /**
     * Method to perform a request to the api
     * @param string $requestSettings
     * @return \stdClass
     */
    public function requestApi($requestSettings)
    {
        //Arguments for the Sellsy API
        $this->lastRequest = [
            'request' => 1,
            'io_mode' =>  'json',
            'do_in' => json_encode($requestSettings)
        ];

        //Generate client request
        $request = $this->requestGenerator->getRequest();

        //Arguments for the HTTP Client
        $request->setOptionArray(
            [
                CURLOPT_HTTPHEADER => $this->computeHeaders(),
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $this->lastRequest,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => !preg_match("!^https!i",$this->apiUrl)
            ]
        );

        $this->lastAnswer = json_decode($request->execute());

        return $this->lastAnswer;
    }

    /**
     * @return \stdClass
     */
    public function getInfos()
    {
        $requestSettings = [
            'method' => 'Infos.getInfos',
            'params' => []
        ];

        return $this->requestApi($requestSettings);
    }
}