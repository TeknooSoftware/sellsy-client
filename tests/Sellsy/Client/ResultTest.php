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

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Result;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * Class ResultTest.
 *
 * @covers \Teknoo\Sellsy\Client\Result
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
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
        return new Result(
            \json_encode(['status' => 'success', 'response' => ['foo' => 'bar']])
        );
    }

    public function buildResultWithError(): ResultInterface
    {
        return new Result(
            \json_encode(['status' => 'error', 'error' => ['message' => 'fooBar']])
        );
    }

    public function buildResultWithNoResponse(): ResultInterface
    {
        return new Result(
            \json_encode(['status' => 'success'])
        );
    }

    public function testGetErrorMessageString()
    {
        $result = new Result(
            \json_encode(['status' => 'error', 'error' => 'fooBar'])
        );
        self::assertIsString($result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());
    }
}
