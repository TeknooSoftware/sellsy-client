<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the 3-Clause BSD license
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
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy\Client;

use PHPUnit\Framework\TestCase;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * Class AbstractResultTests.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractResultTests extends TestCase
{
    /**
     * @return ResultInterface
     */
    abstract public function buildResultWithSuccess(): ResultInterface;

    /**
     * @return ResultInterface
     */
    abstract public function buildResultWithSuccessWithoutReturn(): ResultInterface;

    /**
     * @return ResultInterface
     */
    abstract public function buildResultWithErrorString(): ResultInterface;

    /**
     * @return ResultInterface
     */
    abstract public function buildResultWithErrorCodeCustom(): ResultInterface;

    /**
     * @return ResultInterface
     */
    abstract public function buildResultWithErrorIsNotJson(): ResultInterface;


    public function testIsSuccess()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsBool($result->isSuccess());
        self::assertTrue($result->isSuccess());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsBool($result->isSuccess());
        self::assertTrue($result->isSuccess());

        $result = $this->buildResultWithErrorString();
        self::assertIsBool($result->isSuccess());
        self::assertFalse($result->isSuccess());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsBool($result->isSuccess());
        self::assertFalse($result->isSuccess());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsBool($result->isSuccess());
        self::assertFalse($result->isSuccess());
    }

    public function testIsError()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsBool($result->isError());
        self::assertFalse($result->isError());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsBool($result->isError());
        self::assertFalse($result->isError());

        $result = $this->buildResultWithErrorString();
        self::assertIsBool($result->isError());
        self::assertTrue($result->isError());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsBool($result->isError());
        self::assertTrue($result->isError());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsBool($result->isError());
        self::assertTrue($result->isError());
    }

    public function testGetErrorCode()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsString($result->getErrorCode());
        self::assertEmpty($result->getErrorCode());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsString($result->getErrorCode());
        self::assertEmpty($result->getErrorCode());

        $result = $this->buildResultWithErrorString();
        self::assertIsString($result->getErrorCode());
        self::assertEquals('E_UNKNOW', $result->getErrorCode());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsString($result->getErrorCode());
        self::assertEquals('E_CUSTOM', $result->getErrorCode());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsString($result->getErrorCode());
        self::assertEquals('E_UNKNOW', $result->getErrorCode());
    }

    public function testGetErrorMessage()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsString($result->getErrorMessage());
        self::assertEmpty($result->getErrorMessage());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsString($result->getErrorMessage());
        self::assertEmpty($result->getErrorMessage());

        $result = $this->buildResultWithErrorString();
        self::assertIsString($result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsString($result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsString($result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());
    }

    public function testGetRaw()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsString($result->getRaw());
        self::assertNotEmpty($result->getRaw());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsString($result->getRaw());
        self::assertNotEmpty($result->getRaw());

        $result = $this->buildResultWithErrorString();
        self::assertIsString($result->getRaw());
        self::assertNotEmpty($result->getRaw());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsString($result->getRaw());
        self::assertNotEmpty($result->getRaw());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsString($result->getRaw());
        self::assertNotEmpty($result->getRaw());
    }

    public function testHasResponse()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsBool($result->hasResponse());
        self::assertTrue($result->hasResponse());

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertIsBool($result->hasResponse());
        self::assertFalse($result->hasResponse());

        $result = $this->buildResultWithErrorString();
        self::assertIsBool($result->hasResponse());
        self::assertTrue($result->hasResponse());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsBool($result->hasResponse());
        self::assertTrue($result->hasResponse());

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertIsBool($result->hasResponse());
        self::assertFalse($result->hasResponse());
    }

    public function testGetResponse()
    {
        $result = $this->buildResultWithSuccess();
        self::assertIsArray($result->getResponse());
        self::assertNotEmpty($result->getResponse());

        $result = $this->buildResultWithErrorString();
        self::assertIsString($result->getResponse());
        self::assertNotEmpty($result->getResponse());

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertIsArray($result->getResponse());
        self::assertNotEmpty($result->getResponse());
    }

    public function testGetResponseExceptionWithSuccessWithoutReturn()
    {
        $this->expectException(\RuntimeException::class);

        $result = $this->buildResultWithSuccessWithoutReturn();
        $result->getResponse();
    }

    public function testGetResponseExceptionWithErrorAsString()
    {
        $this->expectException(\RuntimeException::class);

        $result = $this->buildResultWithErrorIsNotJson();
        $result->getResponse();
    }

    public function testIsset()
    {
        $result = $this->buildResultWithSuccess();
        self::assertTrue(isset($result->foo));
        self::assertTrue(isset($result->bar));
        self::assertFalse(isset($result->message));

        $result = $this->buildResultWithSuccessWithoutReturn();
        self::assertFalse(isset($result->foo));
        self::assertFalse(isset($result->bar));
        self::assertFalse(isset($result->message));

        $result = $this->buildResultWithErrorString();
        self::assertFalse(isset($result->foo));
        self::assertFalse(isset($result->bar));
        self::assertFalse(isset($result->message));

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertFalse(isset($result->foo));
        self::assertFalse(isset($result->bar));
        self::assertTrue(isset($result->message));

        $result = $this->buildResultWithErrorIsNotJson();
        self::assertFalse(isset($result->foo));
        self::assertFalse(isset($result->bar));
        self::assertFalse(isset($result->message));
    }
}
