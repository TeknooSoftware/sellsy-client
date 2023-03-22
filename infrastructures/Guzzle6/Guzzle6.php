<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Guzzle6\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Define a transporter, using Guzzle, able to initialize a PSR7 request for the client and send it to the Sellsy API
 * and return PSR7 response.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Guzzle6 implements TransportInterface
{
    /**
     * Guzzle instance.
     */
    private Client $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }

    /**
     * @param UriInterface|string $uri
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return new Request($method, $uri);
    }

    /**
     * @param array<mixed, mixed> $elements
     */
    public function createStream(array &$elements, ?RequestInterface $request = null): StreamInterface
    {
        return new MultipartStream($elements);
    }

    public function asyncExecute(RequestInterface $request): PromiseInterface
    {
        $guzzlePromise = $this->guzzleClient->sendAsync($request);

        return new Guzzle6Promise($guzzlePromise);
    }
}
