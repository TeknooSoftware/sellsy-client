<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * it is available in LICENSE file at the root of this package
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 *
 * @link        https://teknoo.software/libraries/sellsy Project website
 *
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\HttpPlug\Transport;

use Http\Client\HttpAsyncClient;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\HttpPlug\Transport\Exception\RequestCreationException;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

use function preg_match;

/**
 * Define a transporter, using HttpPlug, able to initialize a PSR7 request for the client and send it to the Sellsy API
 * and return PSR7 response.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class HttpPlug implements TransportInterface
{
    private HttpAsyncClient $client;

    private UriFactoryInterface $uriFactory;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        HttpAsyncClient $client,
        UriFactoryInterface $uriFactory,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->client = $client;
        $this->uriFactory = $uriFactory;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }

    /**
     * @param UriInterface|string $uri
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $uri);
        $boundary = uniqid('', true);

        return $request->withHeader('Content-Type', 'multipart/form-data; boundary="' . $boundary . '"');
    }

    /**
     * @param array<string, string> $elements
     */
    public function createStream(array &$elements, ?RequestInterface $request = null): StreamInterface
    {
        if (!$request instanceof RequestInterface) {
            throw new RequestCreationException('Error, missing request, needed to create a Multipart Stream');
        }

        $builder = new MultipartStreamBuilder($this->streamFactory);
        foreach ($elements as &$value) {
            if (isset($value['name'], $value['contents'])) {
                $builder->addResource($value['name'], $value['contents']);
            }
        }

        $contentType = $request->getHeader('Content-Type');
        $boundary = [];
        preg_match('#multipart/form-data; boundary="([^"]+)"#iS', $contentType[0], $boundary);
        $builder->setBoundary($boundary[1]);

        return $builder->build();
    }

    public function asyncExecute(RequestInterface $request): PromiseInterface
    {
        $promise = $this->client->sendAsyncRequest($request);

        return new HttpPlugPromise($promise);
    }
}
