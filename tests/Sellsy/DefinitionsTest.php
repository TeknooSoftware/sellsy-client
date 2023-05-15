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
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Tests\Sellsy;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class DefinitionsTest extends \PHPUnit\Framework\TestCase
{
    public function testDefinitionsInvoke()
    {
        $client = $this->createMock(ClientInterface::class);

        $collectionsList = \scandir(__DIR__.'/../../definitions');
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
