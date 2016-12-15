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
use Psr\Http\Message\ResponseInterface;;
use Teknoo\Sellsy\Method\MethodInterface;

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
     * @param MethodInterface $method
     * @param array $params
     * @return ResultInterface
     */
    public function run(MethodInterface $method, array $params = []): ResultInterface;
}
