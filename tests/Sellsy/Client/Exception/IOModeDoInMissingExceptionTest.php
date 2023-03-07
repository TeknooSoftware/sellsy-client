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

namespace Teknoo\Tests\Sellsy\Client\Exception;

use Teknoo\Sellsy\Client\Exception\ErrorException;
use Teknoo\Sellsy\Client\Exception\IOModeDoInMissingException;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * @covers \Teknoo\Sellsy\Client\Exception\IOModeDoInMissingException
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class IOModeDoInMissingExceptionTest extends AbstractExceptionTests
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

        return new IOModeDoInMissingException($result, $previous);
    }
}
