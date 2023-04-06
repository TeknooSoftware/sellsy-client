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
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\ClientInterface;

/**
 * Class ClientTest.
 *
 * @covers \Teknoo\Sellsy\Client\Client
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class ClientTest extends AbstractClientTests
{
    public function buildClient(
        string $uri,
        string $token,
        string $tokenSecret,
        string $consumerKey,
        string $consumerSecret
    ): ClientInterface {
        return new Client(
            $this->buildTransport(),
            $uri,
            $token,
            $tokenSecret,
            $consumerKey,
            $consumerSecret,
            $this->getDate()
        );
    }
}
