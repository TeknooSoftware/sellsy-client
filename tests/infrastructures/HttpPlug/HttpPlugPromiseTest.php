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

namespace Teknoo\Tests\Sellsy\HttpPlug\Transport;

use Http\Promise\Promise as HttpPLugPromiseInterface;
use Teknoo\Sellsy\HttpPlug\Transport\HttpPlugPromise;
use Teknoo\Tests\Sellsy\Transport\AbstractPromiseTests;

/**
 * @covers \Teknoo\Sellsy\HttpPlug\Transport\HttpPlugPromise
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class HttpPlugPromiseTest extends AbstractPromiseTests
{
    public function buildPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(HttpPLugPromiseInterface::PENDING);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }

    public function buildFulfilledPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(HttpPLugPromiseInterface::FULFILLED);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }

    public function buildRejectedPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(HttpPLugPromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(HttpPLugPromiseInterface::REJECTED);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(HttpPLugPromiseInterface::class));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new HttpPlugPromise($promise);
    }
}
