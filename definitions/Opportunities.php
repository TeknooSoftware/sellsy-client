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
 * @link        https://teknoo.software/libraries/sellsy Project website
 *
 * @license     https://teknoo.software/license/mit         MIT License
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
 * @link https://api.sellsy.com/documentation/methods#opportunitiesgetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Opportunities implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Opportunities');

        $collection->registerMethod(new Method($collection, 'getList'));
        $collection->registerMethod(new Method($collection, 'getOne'));
        $collection->registerMethod(new Method($collection, 'create'));
        $collection->registerMethod(new Method($collection, 'update'));
        $collection->registerMethod(new Method($collection, 'delete'));
        $collection->registerMethod(new Method($collection, 'getFunnels'));
        $collection->registerMethod(new Method($collection, 'getStepsForFunnel'));
        $collection->registerMethod(new Method($collection, 'getSources'));
        $collection->registerMethod(new Method($collection, 'getSource'));
        $collection->registerMethod(new Method($collection, 'createSource'));
        $collection->registerMethod(new Method($collection, 'updateSource'));
        $collection->registerMethod(new Method($collection, 'deleteSource'));
        $collection->registerMethod(new Method($collection, 'updateStatus'));
        $collection->registerMethod(new Method($collection, 'updateStep'));
        $collection->registerMethod(new Method($collection, 'updateOwner'));
        $collection->registerMethod(new Method($collection, 'updateLinkedDocuments'));
        $collection->registerMethod(new Method($collection, 'getCurrentIdent'));
        $collection->registerMethod(new Method($collection, 'updateSharingStaffs'));
        $collection->registerMethod(new Method($collection, 'updateSharingGroups'));
        $collection->registerMethod(new Method($collection, 'updateDefaultDocument'));

        return $collection;
    }
}
