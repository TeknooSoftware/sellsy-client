<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\Result;
use Teknoo\Sellsy\Client\ResultInterface;

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