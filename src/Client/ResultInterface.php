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

namespace Teknoo\Sellsy\Client;

use Teknoo\Immutable\ImmutableInterface;

/**
 * Interface to implement immutable value object encapsuling result/response about a Sellsy operation.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
interface ResultInterface extends ImmutableInterface
{
    /**
     * @return mixed|array<string, mixed>
     */
    public function __get(string $name);

    public function __isset(string $name): bool;

    public function isSuccess(): bool;

    public function isError(): bool;

    public function getErrorCode(): string;

    public function getErrorMessage(): string;

    /*
     * To extract the result in original value.
     */
    public function getRaw(): string;

    public function hasResponse(): bool;

    /**
     * To get the answer, in an usable format (array).
     * @return string|array<mixed, mixed>
     */
    public function getResponse();
}
