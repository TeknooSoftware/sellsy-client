<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Result;
use Teknoo\Sellsy\Client\ResultInterface;

/**
 * Class ResultTest
 * @covers \Teknoo\Sellsy\Client\Result
 *
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
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
            \json_encode(['status'=>'success','response'=>['foo'=>'bar']])
        );
    }

    public function buildResultWithError(): ResultInterface
    {
        return new Result(
            \json_encode(['status'=>'error','error'=>['message'=>'fooBar']])
        );
    }

    public function buildResultWithNoResponse(): ResultInterface
    {
        return new Result(
            \json_encode(['status'=>'success'])
        );
    }

    public function testGetErrorMessageString()
    {
        $result = new Result(
            \json_encode(['status'=>'error','error'=>'fooBar'])
        );
        self::assertInternalType('string', $result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());
    }
}