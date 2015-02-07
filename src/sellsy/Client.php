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
    public function __construct(
        RequestGenerator $requestGenerator,
        $apiUrl='',
        $oauthAccessToken='',
        $oauthAccessTokenSecret='',
        $oauthConsumerKey='',
        $oauthConsumerSecret=''
    ) {
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
     * @param array $requestSettings
     * @return \stdClass
     */
    public function requestApi($requestSettings)
    {
        //Arguments for the Sellsy API
        $this->lastRequest = [
            'request' => 1,
            'io_mode' => 'json',
            'do_in' => json_encode($requestSettings)
        ];

        //Generate client request
        $request = $this->requestGenerator->getRequest();

        //Arguments for the HTTP Client
        $request->setOptionArray(
            [
                CURLOPT_HTTPHEADER => $this->computeHeaders(),
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $this->lastRequest,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => !preg_match("!^https!i",$this->apiUrl)
            ]
        );

        $result = $request->execute();

        if (false !== strpos($result, 'oauth_problem')){
            throw new \RuntimeException($result);
        }

        $this->lastAnswer = json_decode($result);

        if (!empty($this->lastAnswer) && 'error' == $this->lastAnswer) {
            throw new \RuntimeException($this->lastAnswer->error);
        }

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

    /**
     * Return collection methods of the api for Accountdatas
     * @return Collection
     */
    public function accountdatas()
    {
        return new Collection($this, 'Accountdatas');
    }

    /**
     * Return collection methods of the api for AccountPrefs
     * @return Collection
     */
    public function accountPrefs()
    {
        return new Collection($this, 'AccountPrefs');
    }

    /**
     * Return collection methods of the api for Purchase
     * @return Collection
     */
    public function purchase()
    {
        return new Collection($this, 'Purchase');
    }

    /**
     * Return collection methods of the api for Agenda
     * @return Collection
     */
    public function agenda()
    {
        return new Collection($this, 'Agenda');
    }

    /**
     * Return collection methods of the api for Annotations
     * @return Collection
     */
    public function annotations()
    {
        return new Collection($this, 'Annotations');
    }

    /**
     * Return collection methods of the api for Catalogue
     * @return Collection
     */
    public function catalogue()
    {
        return new Collection($this, 'Catalogue');
    }

    /**
     * Return collection methods of the api for CustomFields
     * @return Collection
     */
    public function customFields()
    {
        return new Collection($this, 'CustomFields');
    }

    /**
     * Return collection methods of the api for Client
     * @return Collection
     */
    public function client()
    {
        return new Collection($this, 'Client');
    }

    /**
     * Return collection methods of the api for Staffs
     * @return Collection
     */
    public function staffs()
    {
        return new Collection($this, 'Staffs');
    }

    /**
     * Return collection methods of the api for Peoples
     * @return Collection
     */
    public function peoples()
    {
        return new Collection($this, 'Peoples');
    }

    /**
     * Return collection methods of the api for Document
     * @return Collection
     */
    public function document()
    {
        return new Collection($this, 'Document');
    }

    /**
     * Return collection methods of the api for Mails
     * @return Collection
     */
    public function mails()
    {
        return new Collection($this, 'Mails');
    }

    /**
     * Return collection methods of the api for Event
     * @return Collection
     */
    public function event()
    {
        return new Collection($this, 'Event');
    }

    /**
     * Return collection methods of the api for Expense
     * @return Collection
     */
    public function expense()
    {
        return new Collection($this, 'Expense');
    }

    /**
     * Return collection methods of the api for Opportunities
     * @return Collection
     */
    public function opportunities()
    {
        return new Collection($this, 'Opportunities');
    }

    /**
     * Return collection methods of the api for Prospects
     * @return Collection
     */
    public function prospects()
    {
        return new Collection($this, 'Prospects');
    }

    /**
     * Return collection methods of the api for SmartTags
     * @return Collection
     */
    public function smartTags()
    {
        return new Collection($this, 'SmartTags');
    }

    /**
     * Return collection methods of the api for Stat
     * @return Collection
     */
    public function stat()
    {
        return new Collection($this, 'Stat');
    }

    /**
     * Return collection methods of the api for Stock
     * @return Collection
     */
    public function stock()
    {
        return new Collection($this, 'Stock');
    }

    /**
     * Return collection methods of the api for Support
     * @return Collection
     */
    public function support()
    {
        return new Collection($this, 'Support');
    }

    /**
     * Return collection methods of the api for Timetracking
     * @return Collection
     */
    public function timetracking()
    {
        return new Collection($this, 'Timetracking');
    }
}