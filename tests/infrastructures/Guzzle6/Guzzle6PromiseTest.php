<?php

/*
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

namespace Teknoo\Tests\Sellsy\Guzzle6\Transport;

use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;
use Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise;
use Teknoo\Tests\Sellsy\Transport\AbstractPromiseTests;

/**
 * @covers \Teknoo\Sellsy\Guzzle6\Transport\Guzzle6Promise
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Guzzle6PromiseTest extends AbstractPromiseTests
{
    public function buildPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(GuzzlePromiseInterface::PENDING);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }

    public function buildFulfilledPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(GuzzlePromiseInterface::FULFILLED);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('resolve')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('reject')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('cancel')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }

    public function buildRejectedPromise(): \Teknoo\Sellsy\Transport\PromiseInterface
    {
        $promise = $this->createMock(GuzzlePromiseInterface::class);
        $promise->expects(self::any())->method('getState')->willReturn(GuzzlePromiseInterface::REJECTED);
        $promise->expects(self::any())->method('then')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('otherwise')->willReturn($this->createMock(GuzzlePromiseInterface::class));
        $promise->expects(self::any())->method('resolve')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('reject')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('cancel')->willThrowException(new \RuntimeException('foo'));
        $promise->expects(self::any())->method('wait')->willReturn('foo');

        return new Guzzle6Promise($promise);
    }
}
