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

namespace Teknoo\Tests\Sellsy;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;

/**
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class DefinitionsTest extends \PHPUnit\Framework\TestCase
{
    public function testDefinitionsInvoke()
    {
        $client = $this->createMock(ClientInterface::class);

        $collectionsList = \scandir(__DIR__.'/../definitions');
        foreach ($collectionsList as $collectionName) {
            if ('.' === $collectionName || '..' === $collectionName) {
                continue;
            }

            $collectionName = \str_replace('.php', '', $collectionName);

            $collectionClassName = 'Teknoo\\Sellsy\\Definitions\\'.$collectionName;

            if (!\class_exists($collectionClassName, true)) {
                $this->fail(
                    "The $collectionClassName is not a valid definition into the namesapce Teknoo\\Sellsy\\Definitions"
                );
            }

            $reflectionClass = new \ReflectionClass($collectionClassName);
            if (!$reflectionClass->implementsInterface(DefinitionInterface::class)) {
                $this->fail(
                    "Error, the definition of $collectionName must implement ".DefinitionInterface::class
                );
            }

            /**
             * @var callable $definitionInstance
             */
            $definitionInstance = $reflectionClass->newInstance();
            self::assertInstanceOf(CollectionInterface::class, $definitionInstance($client));
        }
    }
}