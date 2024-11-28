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

namespace Teknoo\Tests\Sellsy\Transport;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class AbstractTransportTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractTransportTests extends TestCase
{
    /**
     * @return TransportInterface
     */
    abstract public function buildTransport(): TransportInterface;

    public function testCreateUri()
    {
        self::assertInstanceOf(UriInterface::class, $this->buildTransport()->createUri());
    }

    public function testCreateRequest()
    {
        self::assertInstanceOf(
            RequestInterface::class,
            $this->buildTransport()->createRequest('POST', $this->createMock(UriInterface::class))
        );
    }

    public function testCreateStreamWithBadRequest()
    {
        $this->expectException(\TypeError::class);

        $object = new \stdClass();
        $this->buildTransport()->createStream($object);
    }

    public function testCreateStream()
    {
        $body = [
            ['name' => 'foo', 'contents' => 'bar']
        ];

        self::assertInstanceOf(
            StreamInterface::class,
            $this->buildTransport()->createStream(
                $body,
                $this->createMock(RequestInterface::class)
            )
        );
    }

    public function testAsyncExecute()
    {
        self::assertInstanceOf(
            PromiseInterface::class,
            $this->buildTransport()->asyncExecute($this->createMock(RequestInterface::class))
        );
    }
}
