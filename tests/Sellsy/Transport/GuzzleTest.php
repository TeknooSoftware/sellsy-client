<?php

namespace Teknoo\Tests\Sellsy\Transport;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * Class GuzzleTest
 * @covers \Teknoo\Sellsy\Transport\Guzzle
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class GuzzleTest extends AbstractTransportTest
{
    /**
     * @return TransportInterface
     */
    public function buildTransport(): TransportInterface
    {
        $guzzle = $this->createMock(Client::class);
        $guzzle->expects(self::any())
            ->method('send')
            ->with($this->callback(function ($arg) {return $arg instanceof RequestInterface;}))
            ->willReturn($this->createMock(ResponseInterface::class));

        return new Guzzle($guzzle);
    }
}