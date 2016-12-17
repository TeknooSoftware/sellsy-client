<?php

namespace Teknoo\Tests\Sellsy\Client;

use Teknoo\Sellsy\Client\ResultInterface;

abstract class AbstractResultTest extends \PHPUnit_Framework_TestCase
{
    abstract public function buildResultWithSuccess(): ResultInterface;

    abstract public function buildResultWithError(): ResultInterface;

    abstract public function buildResultWithNoResponse(): ResultInterface;

    public function testIsSuccess()
    {
        $result = $this->buildResultWithSuccess();
        self::assertInternalType('bool', $result->isSuccess());
        self::assertTrue($result->isSuccess());
        $result = $this->buildResultWithError();
        self::assertInternalType('bool', $result->isSuccess());
        self::assertFalse($result->isSuccess());
    }

    public function testIsError()
    {
        $result = $this->buildResultWithSuccess();
        self::assertInternalType('bool', $result->isError());
        self::assertFalse($result->isError());
        $result = $this->buildResultWithError();
        self::assertInternalType('bool', $result->isError());
        self::assertTrue($result->isError());
    }

    public function testGetErrorMessage()
    {
        $result = $this->buildResultWithSuccess();
        self::assertInternalType('string', $result->getErrorMessage());
        self::assertEmpty($result->getErrorMessage());
        $result = $this->buildResultWithError();
        self::assertInternalType('string', $result->getErrorMessage());
        self::assertEquals('fooBar', $result->getErrorMessage());
    }

    public function testGetRaw()
    {
        $result = $this->buildResultWithSuccess();
        self::assertInternalType('string', $result->getRaw());
        self::assertNotEmpty($result->getRaw());
    }

    public function testGetResponse()
    {
        $result = $this->buildResultWithSuccess();
        self::assertInternalType('array', $result->getResponse());
        self::assertNotEmpty($result->getResponse());
        $result = $this->buildResultWithError();
        self::assertInternalType('array', $result->getResponse());
        self::assertNotEmpty($result->getResponse());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetResponseException()
    {
        $result = $this->buildResultWithNoResponse();
        $result->getResponse();
    }
}