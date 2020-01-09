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
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Sellsy\Client\Exception;

use Teknoo\Sellsy\Client\Exception\DoInParamMissingException;
use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * @covers \Teknoo\Sellsy\Client\Exception\DoInParamMissingException
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class DoInParamMissingExceptionTest extends AbstractExceptionTest
{
    protected function buildException(
        string $codeError,
        string $message,
        ?\Throwable $previous = null,
        $more = null
    ): ErrorException {
        $result = $this->createMock(ResultInterface::class);
        $result->expects(self::any())->method('getErrorMessage')->willReturn($message);
        $result->expects(self::any())->method('getErrorCode')->willReturn($codeError);
        $result->expects(self::any())->method('__isset')->willReturn(null !== $more);
        $result->expects(self::any())->method('__get')->willReturn($more);

        return new DoInParamMissingException($result, $previous);
    }
}
