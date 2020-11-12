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
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Client;

use Teknoo\Immutable\ImmutableInterface;

/**
 * Interface to implement immutable value object encapsuling result/response about a Sellsy operation.
 *
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
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

    /**
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
