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

namespace Teknoo\Sellsy\HttpPlug\Transport;

use Http\Promise\Promise as HttpPLugPromiseInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class HttpPlugPromise implements PromiseInterface
{
    private HttpPLugPromiseInterface $promise;

    public function __construct(HttpPLugPromiseInterface $promise)
    {
        $this->promise = $promise;
    }

    public function then(?callable $onFulfilled = null, ?callable $onRejected = null): PromiseInterface
    {
        return new HttpPlugPromise($this->promise->then($onFulfilled, $onRejected));
    }

    public function otherwise(callable $onRejected): PromiseInterface
    {
        return new HttpPlugPromise($this->promise->then(null, $onRejected));
    }

    public function isPending(): bool
    {
        return HttpPLugPromiseInterface::PENDING === $this->promise->getState();
    }

    public function isFulfilled(): bool
    {
        return HttpPLugPromiseInterface::FULFILLED === $this->promise->getState();
    }

    public function isRejected(): bool
    {
        return HttpPLugPromiseInterface::REJECTED === $this->promise->getState();
    }

    public function wait(bool $unwrap = true)
    {
        return $this->promise->wait($unwrap);
    }
}
