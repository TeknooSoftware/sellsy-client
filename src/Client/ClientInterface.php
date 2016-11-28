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
use Psr\Http\Message\ResponseInterface;
use Teknoo\Sellsy\Client\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\RequestFailureException;

/**
 * Interface ClientInterface
 * Interface to define client implementing the Sellsy API.
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
interface ClientInterface
{
    /**
     * Update the api url.
     *
     * @param string $apiUrl
     *
     * @return ClientInterface
     */
    public function setApiUrl(string $apiUrl): ClientInterface;

    /**
     * Update the OAuth access token.
     *
     * @param string $oauthAccessToken
     *
     * @return ClientInterface
     */
    public function setOAuthAccessToken(string $oauthAccessToken): ClientInterface;

    /**
     * Update the OAuth access secret token.
     *
     * @param string $oauthAccessTokenSecret
     *
     * @return ClientInterface
     */
    public function setOAuthAccessTokenSecret(string $oauthAccessTokenSecret): ClientInterface;

    /**
     * Update the OAuth consumer key.
     *
     * @param string $oauthConsumerKey
     *
     * @return ClientInterface
     */
    public function setOAuthConsumerKey(string $oauthConsumerKey): ClientInterface;

    /**
     * Update the OAuth consumer secret.
     *
     * @param string $oauthConsumerSecret
     *
     * @return ClientInterface
     */
    public function setOAuthConsumerSecret(string $oauthConsumerSecret): ClientInterface;

    /**
     * @return RequestInterface
     */
    public function getLastRequest();

    /**
     * @return ResponseInterface
     */
    public function getLastResponse();

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
    public function requestApi(array $requestSettings);

    /**
     * @return \stdClass
     */
    public function getInfos();

    /**
     * Return collection methods of the api for Accountdatas.
     *
     * @return CollectionInterface
     */
    public function accountData(): CollectionInterface;

    /**
     * Return collection methods of the api for AccountPrefs.
     *
     * @return CollectionInterface
     */
    public function accountPrefs(): CollectionInterface;

    /**
     * Return collection methods of the api for Purchase.
     *
     * @return CollectionInterface
     */
    public function purchase(): CollectionInterface;

    /**
     * Return collection methods of the api for Agenda.
     *
     * @return CollectionInterface
     */
    public function agenda(): CollectionInterface;

    /**
     * Return collection methods of the api for Annotations.
     *
     * @return CollectionInterface
     */
    public function annotations(): CollectionInterface;

    /**
     * Return collection methods of the api for Catalogue.
     *
     * @return CollectionInterface
     */
    public function catalogue(): CollectionInterface;

    /**
     * Return collection methods of the api for CustomFields.
     *
     * @return CollectionInterface
     */
    public function customFields(): CollectionInterface;

    /**
     * Return collection methods of the api for Client.
     *
     * @return CollectionInterface
     */
    public function client(): CollectionInterface;

    /**
     * Return collection methods of the api for Staffs.
     *
     * @return CollectionInterface
     */
    public function staffs(): CollectionInterface;

    /**
     * Return collection methods of the api for Peoples.
     *
     * @return CollectionInterface
     */
    public function peoples(): CollectionInterface;

    /**
     * Return collection methods of the api for Document.
     *
     * @return CollectionInterface
     */
    public function document(): CollectionInterface;

    /**
     * Return collection methods of the api for Mails.
     *
     * @return CollectionInterface
     */
    public function mails(): CollectionInterface;

    /**
     * Return collection methods of the api for Event.
     *
     * @return CollectionInterface
     */
    public function event(): CollectionInterface;

    /**
     * Return collection methods of the api for Expense.
     *
     * @return CollectionInterface
     */
    public function expense(): CollectionInterface;

    /**
     * Return collection methods of the api for Opportunities.
     *
     * @return CollectionInterface
     */
    public function opportunities(): CollectionInterface;

    /**
     * Return collection methods of the api for Prospects.
     *
     * @return CollectionInterface
     */
    public function prospects(): CollectionInterface;

    /**
     * Return collection methods of the api for SmartTags.
     *
     * @return CollectionInterface
     */
    public function smartTags(): CollectionInterface;

    /**
     * Return collection methods of the api for Stat.
     *
     * @return CollectionInterface
     */
    public function stat(): CollectionInterface;

    /**
     * Return collection methods of the api for Stock.
     *
     * @return CollectionInterface
     */
    public function stock(): CollectionInterface;

    /**
     * Return collection methods of the api for Support.
     *
     * @return CollectionInterface
     */
    public function support(): CollectionInterface;

    /**
     * Return collection methods of the api for Timetracking.
     *
     * @return CollectionInterface
     */
    public function timeTracking(): CollectionInterface;
}
