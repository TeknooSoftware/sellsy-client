<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\ClientInterface;

class ClientTest extends AbstractClientTest
{
    public function buildClient(): ClientInterface
    {
        return new Client(
            $this->buildTransport(),
            $this->uriString,
            '',
            '',
            '',
            '',
            $this->getDate()
        );
    }
}