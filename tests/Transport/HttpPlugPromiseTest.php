<?php

/**
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Sellsy\Transport;

use Http\Promise\Promise as HttpPLugPromiseInterface;
use Teknoo\Sellsy\Transport\HttpPlugPromise;

/**
 * @covers \Teknoo\Sellsy\Transport\HttpPlugPromise
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class HttpPlugPromiseTest extends AbstractPromiseTest
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
