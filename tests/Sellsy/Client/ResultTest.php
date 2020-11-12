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
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Result;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * Class ResultTest.
 *
 * @covers \Teknoo\Sellsy\Client\Result
 *
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class ResultTest extends AbstractResultTest
{
    public function buildResultWithSuccess(): ResultInterface
    {
        $string = \json_encode(['status' => 'success', 'response' => ['foo' => ['bar' => 'hello'], 'bar' => 'world'], 'error' => null]);
        return new Result($string);
    }

    public function buildResultWithSuccessWithoutReturn(): ResultInterface
    {
        $string = \json_encode(['status' => 'success', 'response' => null, 'error' => null]);
        return new Result($string);
    }

    public function buildResultWithErrorString(): ResultInterface
    {
        $string = \json_encode(['status' => 'error', 'error' => 'fooBar']);
        return new Result($string);
    }

    public function buildResultWithErrorCodeCustom(): ResultInterface
    {
        $string = \json_encode(['status' => 'error', 'error' => ['message' => 'fooBar', 'more' => ['foo' => 'bar'], 'code' => 'E_CUSTOM']]);
        return new Result($string);
    }

    public function buildResultWithErrorIsNotJson(): ResultInterface
    {
        $string = 'fooBar';
        return new Result($string);
    }

    public function testGet()
    {
        $result = $this->buildResultWithSuccess();
        self::assertEquals('hello', $result->foo->bar);
        self::assertEquals('world', $result->bar);

        $result = $this->buildResultWithErrorCodeCustom();
        self::assertEquals('fooBar', $result->message);
        self::assertEquals('bar', $result->more->foo);
    }

    public function testGetNotAccessible()
    {
        $this->expectException(\InvalidArgumentException::class);

        $result = $this->buildResultWithSuccessWithoutReturn();
        $result->foo;
    }
}
