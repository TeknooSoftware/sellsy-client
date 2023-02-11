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

namespace Teknoo\Tests\Sellsy\HttpPlug\Transport;

use Http\Promise\Promise as HttpPLugPromiseInterface;
use Teknoo\Sellsy\HttpPlug\Transport\HttpPlugPromise;
use Teknoo\Tests\Sellsy\Transport\AbstractPromiseTests;

/**
 * @covers \Teknoo\Sellsy\HttpPlug\Transport\HttpPlugPromise
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class HttpPlugPromiseTest extends AbstractPromiseTests
{
    public function buildPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(HttpPLugPromiseInterface::PENDING);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }

    public function buildFulfilledPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(HttpPLugPromiseInterface::FULFILLED);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }

    public function buildRejectedPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(HttpPLugPromiseInterface::REJECTED);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }
}
