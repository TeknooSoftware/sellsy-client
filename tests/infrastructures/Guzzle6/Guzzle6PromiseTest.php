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

use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;
use Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise;
use Teknoo\Tests\Sellsy\Transport\AbstractPromiseTests;

/**
 * @covers \Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Guzzle6PromiseTest extends AbstractPromiseTests
{
    public function buildPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(GuzzlePromiseInterface::PENDING);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }

    public function buildFulfilledPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(GuzzlePromiseInterface::FULFILLED);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('resolve')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('reject')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('cancel')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }

    public function buildRejectedPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects($this->any())->method('getState')->willReturn(GuzzlePromiseInterface::REJECTED);
        $promise->expects($this->any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects($this->any())->method('resolve')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('reject')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('cancel')->willThrowException(new \RuntimeException('foo'));
        $promise->expects($this->any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }
}
