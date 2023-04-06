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

namespace Teknoo\Tests\Sellsy\Client\Exception;

use PHPUnit\Framework\TestCase;
use Teknoo\Sellsy\Client\Exception\ErrorException;

/**
 * Class AbstractResultTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractExceptionTests extends TestCase
{
    abstract protected function buildException(
        string $codeError,
        string $message,
        ?\Throwable $previous=null,
        $more = null
    ): ErrorException;

    public function testGetMessage()
    {
        $codeError = 'E_CUSTOM';
        $message = 'fooBar';
        self::assertEquals($message, $this->buildException($codeError, $message)->getMessage());
    }

    public function testGetCode()
    {
        $codeError = 'E_CUSTOM';
        $message = 'fooBar';
        self::assertIsInt($this->buildException($codeError, $message)->getCode());
    }

    public function testGetErrorCode()
    {
        $codeError = 'E_CUSTOM';
        $message = 'fooBar';
        self::assertEquals($codeError, $this->buildException($codeError, $message)->getErrorCode());
    }

    public function testGetPrevious()
    {
        $codeError = 'E_CUSTOM';
        $message = 'fooBar';
        $previous = $this->createMock(\Throwable::class);
        self::assertEquals($previous, $this->buildException($codeError, $message, $previous)->getPrevious());
    }

    public function testGetMore()
    {
        $codeError = 'E_CUSTOM';
        $message = 'fooBar';
        $previous = $this->createMock(\Throwable::class);
        $more = 'foo';
        self::assertEquals($more, $this->buildException($codeError, $message, $previous, $more)->getMore());
        self::assertEmpty($this->buildException($codeError, $message)->getMore());
    }
}
