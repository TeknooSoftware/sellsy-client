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

namespace Teknoo\Tests\Sellsy\Client\Exception;

use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\ParameterInvalidException;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * @covers \Teknoo\Sellsy\Client\Exception\ParameterInvalidException
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class ParameterInvalidExceptionTest extends AbstractExceptionTests
{
    protected function buildException(
        string $codeError,
        string $message,
        ?\Throwable $previous = null,
        $more = null
    ): ErrorException {
        $result = $this->createMock(ResultInterface::class);
        $result->expects($this->any())->method('getErrorMessage')->willReturn($message);
        $result->expects($this->any())->method('getErrorCode')->willReturn($codeError);
        $result->expects($this->any())->method('__isset')->willReturn(null !== $more);
        $result->expects($this->any())->method('__get')->willReturn($more);

        return new ParameterInvalidException($result, $previous);
    }
}
