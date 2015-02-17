<?php

namespace UniAlteri\Sellsy\Client;

use UniAlteri\Sellsy\Client\Collection\CollectionInterface;
use UniAlteri\Sellsy\Client\Exception\ErrorException;
use UniAlteri\Sellsy\Client\Exception\RequestFailureException;

interface ClientInterface
{
    /**
     * Update the api url
     * @param string $apiUrl
     * @return $this
     */
    public function setApiUrl($apiUrl);

    /**
     * Get the api url
     * @return string
     */
    public function getApiUrl();

    /**
     * Update the OAuth access token
     * @param string $oauthAccessToken
     * @return $this
     */
    public function setOAuthAccessToken($oauthAccessToken);

    /**
     * Get the OAuth access token
     * @return string
     */
    public function getOAuthAccessToken();

    /**
     * Update the OAuth access secret token
     * @param string $oauthAccessTokenSecret
     * @return $this
     */
    public function setOAuthAccessTokenSecret($oauthAccessTokenSecret);

    /**
     * Get the OAuth access secret token
     * @return string
     */
    public function getOAuthAccessTokenSecret();

    /**
     * Update the OAuth consumer key
     * @param string $oauthConsumerKey
     * @return $this
     */
    public function setOAuthConsumerKey($oauthConsumerKey);

    /**
     * Get the OAuth consumer key
     * @return string
     */
    public function getOAuthConsumerKey();

    /**
     * Update the OAuth consumer secret
     * @param string $oauthConsumerSecret
     * @return $this
     */
    public function setOAuthConsumerSecret($oauthConsumerSecret);

    /**
     * Get the OAuth consumer secret
     * @return string
     */
    public function getOAuthConsumerSecret();

    /**
     * @return array
     */
    public function getLastRequest();

    /**
     * @return mixed|\stdClass
     */
    public function getLastAnswer();

    /**
     * Method to perform a request to the api
     * @param array $requestSettings
     * @return \stdClass
     * @throws RequestFailureException is the request can not be performed on the server
     * @throws ErrorException if the server returned an error for this request
     */
    public function requestApi($requestSettings);

    /**
     * @return \stdClass
     */
    public function getInfos();

    /**
     * Return collection methods of the api for Accountdatas
     * @return CollectionInterface
     */
    public function accountData();

    /**
     * Return collection methods of the api for AccountPrefs
     * @return CollectionInterface
     */
    public function accountPrefs();

    /**
     * Return collection methods of the api for Purchase
     * @return CollectionInterface
     */
    public function purchase();

    /**
     * Return collection methods of the api for Agenda
     * @return CollectionInterface
     */
    public function agenda();

    /**
     * Return collection methods of the api for Annotations
     * @return CollectionInterface
     */
    public function annotations();

    /**
     * Return collection methods of the api for Catalogue
     * @return CollectionInterface
     */
    public function catalogue();

    /**
     * Return collection methods of the api for CustomFields
     * @return CollectionInterface
     */
    public function customFields();

    /**
     * Return collection methods of the api for Client
     * @return CollectionInterface
     */
    public function client();

    /**
     * Return collection methods of the api for Staffs
     * @return CollectionInterface
     */
    public function staffs();

    /**
     * Return collection methods of the api for Peoples
     * @return CollectionInterface
     */
    public function peoples();

    /**
     * Return collection methods of the api for Document
     * @return CollectionInterface
     */
    public function document();

    /**
     * Return collection methods of the api for Mails
     * @return CollectionInterface
     */
    public function mails();

    /**
     * Return collection methods of the api for Event
     * @return CollectionInterface
     */
    public function event();

    /**
     * Return collection methods of the api for Expense
     * @return CollectionInterface
     */
    public function expense();

    /**
     * Return collection methods of the api for Opportunities
     * @return CollectionInterface
     */
    public function opportunities();

    /**
     * Return collection methods of the api for Prospects
     * @return CollectionInterface
     */
    public function prospects();

    /**
     * Return collection methods of the api for SmartTags
     * @return CollectionInterface
     */
    public function smartTags();

    /**
     * Return collection methods of the api for Stat
     * @return CollectionInterface
     */
    public function stat();

    /**
     * Return collection methods of the api for Stock
     * @return CollectionInterface
     */
    public function stock();

    /**
     * Return collection methods of the api for Support
     * @return CollectionInterface
     */
    public function support();

    /**
     * Return collection methods of the api for Timetracking
     * @return CollectionInterface
     */
    public function timeTracking();
}