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
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Sellsy\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface to define a transporter, able to initialize a PSR7 request for the client and send it to the Sellsy API
 * and return PSR7 response.
 *
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface TransportInterface
{
    /**
     * To initiate a PSR7 Uri instance to configure a request.
     */
    public function createUri(): UriInterface;

    /**
     * To initialize a PSR7 request for the Sellsy client.
     *
     * @param UriInterface $uri
     */
    public function createRequest(string $method, UriInterface $uri): RequestInterface;

    /**
     * To initialize a PSR7 Stream, compatible with the content type multipart/form-data, needed to execute the request,
     * Sellsy API accepts only requests with a content type defined to "multipart/form-data".
     *
     * @param array<mixed, mixed> $elements
     */
    public function createStream(array &$elements): StreamInterface;

    /**
     * To execute the PSR7 request, from the Sellsy client.
     */
    public function execute(RequestInterface $request): ResponseInterface;
}
