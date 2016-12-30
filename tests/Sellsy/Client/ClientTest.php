<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\ClientInterface;

/**
 * Class ClientTest
 * @covers \Teknoo\Sellsy\Client\Client
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class ClientTest extends AbstractClientTest
{
    /**
     * @return ClientInterface
     */
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