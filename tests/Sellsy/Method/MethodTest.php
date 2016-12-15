<?php

namespace Teknoo\Tests\Sellsy\Method;

use Teknoo\Sellsy\Method\Method;
use Teknoo\Sellsy\Method\MethodInterface;

class MethodTest extends AbstractMethodTest
{
    public function buildMethod(): MethodInterface
    {
        return new Method($this->buildCollection(), 'fooBar');
    }
}