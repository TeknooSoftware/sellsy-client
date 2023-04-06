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

namespace Teknoo\Tests\Sellsy\Transport;

use PHPUnit\Framework\TestCase;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Class AbstractPromiseTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractPromiseTests extends TestCase
{
    abstract public function buildPromise(): PromiseInterface;

    abstract public function buildFulfilledPromise(): PromiseInterface;

    abstract public function buildRejectedPromise(): PromiseInterface;

    public function testThen()
    {
        $promise1 = $this->buildPromise();
        $promise2 = $promise1->then(function () {
        });

        self::assertInstanceOf(PromiseInterface::class, $promise2);
        self::assertNotSame($promise1, $promise2);
    }

    public function testOtherwise()
    {
        $promise1 = $this->buildPromise();
        $promise2 = $promise1->otherwise(function () {
        });

        self::assertInstanceOf(PromiseInterface::class, $promise2);
        self::assertNotSame($promise1, $promise2);
    }

    public function testIsPending()
    {
        self::assertTrue($this->buildPromise()->isPending());
        self::assertFalse($this->buildFulfilledPromise()->isPending());
        self::assertFalse($this->buildRejectedPromise()->isPending());
    }

    public function testIsFulfilled()
    {
        self::assertFalse($this->buildPromise()->isFulfilled());
        self::assertTrue($this->buildFulfilledPromise()->isFulfilled());
        self::assertFalse($this->buildRejectedPromise()->isFulfilled());
    }

    public function testIsRejected()
    {
        self::assertFalse($this->buildPromise()->isRejected());
        self::assertFalse($this->buildFulfilledPromise()->isRejected());
        self::assertTrue($this->buildRejectedPromise()->isRejected());
    }

    public function testWait()
    {
        self::assertEquals('foo', $this->buildFulfilledPromise()->wait());
    }
}
