<?php

/*
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

declare(strict_types=1);

namespace Teknoo\Sellsy\Transport;

use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;

class Guzzle6Promise implements PromiseInterface
{
    private GuzzlePromiseInterface $promise;

    /**
     * Guzzle6Promise constructor.
     * @param GuzzlePromiseInterface $promise
     */
    public function __construct(GuzzlePromiseInterface $promise)
    {
        $this->promise = $promise;
    }

    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): PromiseInterface
    {
        return new Guzzle6Promise($this->promise->then($onFulfilled, $onRejected));
    }

    public function otherwise(callable $onRejected): PromiseInterface
    {
        return new Guzzle6Promise($this->promise->otherwise($onRejected));
    }

    public function isPending(): bool
    {
        return GuzzlePromiseInterface::PENDING === $this->promise->getState();
    }

    public function isFulfilled(): bool
    {
        return GuzzlePromiseInterface::FULFILLED === $this->promise->getState();
    }

    public function isRejected(): bool
    {
        return GuzzlePromiseInterface::REJECTED === $this->promise->getState();
    }

    public function cancel(): void
    {
        $this->promise->cancel();
    }

    public function wait(bool $unwrap = true)
    {
        return $this->promise->wait($unwrap);
    }
}
