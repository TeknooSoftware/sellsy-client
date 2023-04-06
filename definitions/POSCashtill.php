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
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Definitions;

use Teknoo\Sellsy\Client\ClientInterface;
use Teknoo\Sellsy\Collection\Collection;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Collection\DefinitionInterface;
use Teknoo\Sellsy\Method\Method;

/**
 * @link https://api.sellsy.com/documentation/methods#receiptgetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class POSCashtill implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'POSCashtill');

        $collection->registerMethod(new Method($collection, 'getList'));
        $collection->registerMethod(new Method($collection, 'open'));
        $collection->registerMethod(new Method($collection, 'close'));
        $collection->registerMethod(new Method($collection, 'getX'));
        $collection->registerMethod(new Method($collection, 'getPayMediums'));
        $collection->registerMethod(new Method($collection, 'getStaffs'));
        $collection->registerMethod(new Method($collection, 'updateLastSync'));

        return $collection;
    }
}
