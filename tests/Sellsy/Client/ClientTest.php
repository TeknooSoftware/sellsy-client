<?php

/**
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

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Client;
use Teknoo\Sellsy\Client\ClientInterface;

/**
 * Class ClientTest.
 *
 * @covers \Teknoo\Sellsy\Client\Client
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
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
