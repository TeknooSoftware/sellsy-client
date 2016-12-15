<?php

namespace Teknoo\Tests\Sellsy\Transport;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Transport\TransportInterface;

class GuzzleTest extends AbstractTransportTest
{
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