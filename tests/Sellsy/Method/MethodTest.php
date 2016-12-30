<?php

namespace Teknoo\Tests\Sellsy\Method;

use Teknoo\Sellsy\Method\Method;
use Teknoo\Sellsy\Method\MethodInterface;

/**
 * Class MethodTest
 * @covers \Teknoo\Sellsy\Method\Method
 * @copyright   Copyright (c) 2009-2017 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class MethodTest extends AbstractMethodTest
{
    /**
     * @return MethodInterface
     */
    public function buildMethod(): MethodInterface
    {
        return new Method($this->buildCollection(), 'fooBar');
    }
}