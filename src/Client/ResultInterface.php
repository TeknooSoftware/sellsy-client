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
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Sellsy\Client;

use Teknoo\Immutable\ImmutableInterface;

/**
 * Interface to implement immutable value object encapsuling result/response about a Sellsy operation.
 *
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface ResultInterface extends ImmutableInterface
{
    /**
     * To know if the method has been correctly executed.
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * To know if an error has been occurred during the execution.
     *
     * @return bool
     */
    public function isError(): bool;

    /**
     * To know the reason of the error.
     *
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * To extract the result in original value.
     *
     * @return string
     */
    public function getRaw(): string;

    /**
     * To get the answer, in an usable format (array).
     *
     * @return mixed
     */
    public function getResponse();
}
