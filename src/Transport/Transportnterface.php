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
namespace Teknoo\Sellsy\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

interface TransportInterface
{
    /**
     * @return UriInterface
     */
    public function createUri(): UriInterface;

    /**
     * @param string $method
     * @param UriInterface $uri
     * @return RequestInterface
     */
    public function createRequest(string $method, UriInterface $uri): RequestInterface;

    /**
     * @return StreamInterface
     */
    public function createStream(): StreamInterface;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function execute(RequestInterface $request): ResponseInterface;
}