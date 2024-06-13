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
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy\Guzzle6\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Teknoo\Sellsy\Guzzle6\Transport\Guzzle6;
use Teknoo\Sellsy\Transport\TransportInterface;
use Teknoo\Tests\Sellsy\Transport\AbstractTransportTests;

/**
 * @covers \Teknoo\Sellsy\Guzzle6\Transport\Guzzle6
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Guzzle6Test extends AbstractTransportTests
{
    /**
     * @return TransportInterface
     */
    public function buildTransport(): TransportInterface
    {
        $guzzle = $this->createMock(Client::class);
        $guzzle->expects($this->any())
            ->method('sendAsync')
            ->with($this->callback(function ($arg) {
                return $arg instanceof RequestInterface;
            }))
            ->willReturn($this->createMock(PromiseInterface::class));

        return new Guzzle6($guzzle);
    }
}
