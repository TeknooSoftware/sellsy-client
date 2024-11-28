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

namespace Teknoo\Sellsy\HttpPlug\Transport;

use Http\Promise\Promise as HttpPlugPromiseInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class HttpPlugPromise implements PromiseInterface
{
    /**
     * @var HttpPlugPromiseInterface<mixed>
     */
    private HttpPlugPromiseInterface $promise;

    /**
     * @param HttpPlugPromiseInterface<mixed> $promise
     */
    public function __construct(HttpPlugPromiseInterface $promise)
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
        return HttpPlugPromiseInterface::PENDING === $this->promise->getState();
    }

    public function isFulfilled(): bool
    {
        return HttpPlugPromiseInterface::FULFILLED === $this->promise->getState();
    }

    public function isRejected(): bool
    {
        return HttpPlugPromiseInterface::REJECTED === $this->promise->getState();
    }

    public function wait(bool $unwrap = true)
    {
        return $this->promise->wait($unwrap);
    }
}
