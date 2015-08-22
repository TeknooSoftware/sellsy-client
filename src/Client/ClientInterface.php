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
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/sellsy-client Project website
 *
 * @license     http://teknoo.it/sellsy-client/license/mit         MIT License
 * @license     http://teknoo.it/sellsy-client/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @version     0.8.0
 */

namespace UniAlteri\Sellsy\Client;

use UniAlteri\Sellsy\Client\Collection\CollectionInterface;
use UniAlteri\Sellsy\Client\Exception\ErrorException;
use UniAlteri\Sellsy\Client\Exception\RequestFailureException;

/**
 * Interface ClientInterface
 * Interface to define client implementing the Sellsy API.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/sellsy-client Project website
 *
 * @license     http://teknoo.it/sellsy-client/license/mit         MIT License
 * @license     http://teknoo.it/sellsy-client/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
interface ClientInterface
{
    /**
     * Update the api url.
     *
     * @param string $apiUrl
     *
     * @return $this
     */
    public function setApiUrl($apiUrl);

    /**
     * Get the api url.
     *
     * @return string
     */
    public function getApiUrl();

    /**
     * Update the OAuth access token.
     *
     * @param string $oauthAccessToken
     *
     * @return $this
     */
    public function setOAuthAccessToken($oauthAccessToken);

    /**
     * Get the OAuth access token.
     *
     * @return string
     */
    public function getOAuthAccessToken();

    /**
     * Update the OAuth access secret token.
     *
     * @param string $oauthAccessTokenSecret
     *
     * @return $this
     */
    public function setOAuthAccessTokenSecret($oauthAccessTokenSecret);

    /**
     * Get the OAuth access secret token.
     *
     * @return string
     */
    public function getOAuthAccessTokenSecret();

    /**
     * Update the OAuth consumer key.
     *
     * @param string $oauthConsumerKey
     *
     * @return $this
     */
    public function setOAuthConsumerKey($oauthConsumerKey);

    /**
     * Get the OAuth consumer key.
     *
     * @return string
     */
    public function getOAuthConsumerKey();

    /**
     * Update the OAuth consumer secret.
     *
     * @param string $oauthConsumerSecret
     *
     * @return $this
     */
    public function setOAuthConsumerSecret($oauthConsumerSecret);

    /**
     * Get the OAuth consumer secret.
     *
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
     * Method to perform a request to the api.
     *
     * @param array $requestSettings
     *
     * @return \stdClass
     *
     * @throws RequestFailureException is the request can not be performed on the server
     * @throws ErrorException          if the server returned an error for this request
     */
    public function requestApi($requestSettings);

    /**
     * @return \stdClass
     */
    public function getInfos();

    /**
     * Return collection methods of the api for Accountdatas.
     *
     * @return CollectionInterface
     */
    public function accountData();

    /**
     * Return collection methods of the api for AccountPrefs.
     *
     * @return CollectionInterface
     */
    public function accountPrefs();

    /**
     * Return collection methods of the api for Purchase.
     *
     * @return CollectionInterface
     */
    public function purchase();

    /**
     * Return collection methods of the api for Agenda.
     *
     * @return CollectionInterface
     */
    public function agenda();

    /**
     * Return collection methods of the api for Annotations.
     *
     * @return CollectionInterface
     */
    public function annotations();

    /**
     * Return collection methods of the api for Catalogue.
     *
     * @return CollectionInterface
     */
    public function catalogue();

    /**
     * Return collection methods of the api for CustomFields.
     *
     * @return CollectionInterface
     */
    public function customFields();

    /**
     * Return collection methods of the api for Client.
     *
     * @return CollectionInterface
     */
    public function client();

    /**
     * Return collection methods of the api for Staffs.
     *
     * @return CollectionInterface
     */
    public function staffs();

    /**
     * Return collection methods of the api for Peoples.
     *
     * @return CollectionInterface
     */
    public function peoples();

    /**
     * Return collection methods of the api for Document.
     *
     * @return CollectionInterface
     */
    public function document();

    /**
     * Return collection methods of the api for Mails.
     *
     * @return CollectionInterface
     */
    public function mails();

    /**
     * Return collection methods of the api for Event.
     *
     * @return CollectionInterface
     */
    public function event();

    /**
     * Return collection methods of the api for Expense.
     *
     * @return CollectionInterface
     */
    public function expense();

    /**
     * Return collection methods of the api for Opportunities.
     *
     * @return CollectionInterface
     */
    public function opportunities();

    /**
     * Return collection methods of the api for Prospects.
     *
     * @return CollectionInterface
     */
    public function prospects();

    /**
     * Return collection methods of the api for SmartTags.
     *
     * @return CollectionInterface
     */
    public function smartTags();

    /**
     * Return collection methods of the api for Stat.
     *
     * @return CollectionInterface
     */
    public function stat();

    /**
     * Return collection methods of the api for Stock.
     *
     * @return CollectionInterface
     */
    public function stock();

    /**
     * Return collection methods of the api for Support.
     *
     * @return CollectionInterface
     */
    public function support();

    /**
     * Return collection methods of the api for Timetracking.
     *
     * @return CollectionInterface
     */
    public function timeTracking();
}
