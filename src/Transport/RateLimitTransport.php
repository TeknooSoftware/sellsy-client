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
 * @author      Julien Herr <julien@herr.fr>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use function intval;
use function microtime;
use function usleep;

/**
 * Be careful: this class has a blocking behavior because it uses sleep
 */
class RateLimitTransport implements TransportInterface
{
    public const RATE_LIMIT = 5;

    private TransportInterface $transport;
    private int $rateLimit;
    private int $cnt;
    private float $time;

    public function __construct(
        TransportInterface $transport,
        int $rateLimit = self::RATE_LIMIT
    ) {
        $this->transport = $transport;
        $this->rateLimit = $rateLimit;
        $this->cnt = 0;
        $this->time = microtime(true);
    }

    public function createRequest(string $method, $uri): RequestInterface
    {
        return $this->transport->createRequest($method, $uri);
    }

    public function createStream(array &$elements, ?RequestInterface $request = null): StreamInterface
    {
        return $this->transport->createStream($elements, $request);
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return $this->transport->createUri($uri);
    }

    public function asyncExecute(RequestInterface $request): PromiseInterface
    {
        $this->cnt++;
        if ($this->cnt === $this->rateLimit) {
            $stop = microtime(true);
            $sleep = 1000000 - intval(($stop - $this->time) * 1000000);
            if ($sleep > 0) {
                usleep($sleep);
            }
            $this->time = microtime(true);
            $this->cnt = 0;
        }
        return $this->transport->asyncExecute($request);
    }
}
