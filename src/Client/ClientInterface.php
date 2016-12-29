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
use Psr\Http\Message\ResponseInterface;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Interface ClientInterface
 * Interface to define an HTTP+OAuth client to use the Sellsy API with your credentials to execute some operations on
 * your account.
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface ClientInterface
{
    /**
     * Update the api url to use to execute an operation.
     *
     * @param string $apiUrl
     *
     * @return ClientInterface
     */
    public function setApiUrl(string $apiUrl): ClientInterface;

    /**
     * Update the OAuth access token to use to authenticate with your account.
     *
     * @param string $oauthAccessToken
     *
     * @return ClientInterface
     */
    public function setOAuthAccessToken(string $oauthAccessToken): ClientInterface;

    /**
     * Update the OAuth access secret token to use to authenticate with your account.
     *
     * @param string $oauthAccessTokenSecret
     *
     * @return ClientInterface
     */
    public function setOAuthAccessTokenSecret(string $oauthAccessTokenSecret): ClientInterface;

    /**
     * Update the OAuth consumer key to use to authenticate with your account.
     *
     * @param string $oauthConsumerKey
     *
     * @return ClientInterface
     */
    public function setOAuthConsumerKey(string $oauthConsumerKey): ClientInterface;

    /**
     * Update the OAuth consumer secret to use to authenticate with your account.
     *
     * @param string $oauthConsumerSecret
     *
     * @return ClientInterface
     */
    public function setOAuthConsumerSecret(string $oauthConsumerSecret): ClientInterface;

    /**
     * Get the last PSR7 request sent to the Sellsy server. It's a method to help debug.
     *
     * @return RequestInterface|null
     */
    public function getLastRequest();

    /**
     * Get the last PSR7 response sent by the Sellsy server. It's a method to help debug.
     *
     * @return ResponseInterface|null
     */
    public function getLastResponse();

    /**
     * To execute a method, referenced by the $method instance on the Sellsy server via it's api, authenticated with
     * your credentials.
     *
     * @param MethodInterface $method
     * @param array           $params
     *
     * @return ResultInterface
     */
    public function run(MethodInterface $method, array $params = []): ResultInterface;
}
