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
 * @link https://api.sellsy.com/documentation/methods#cataloguegetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Catalogue implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Catalogue');

        $collection->registerMethod(new Method($collection, 'getList'));
        $collection->registerMethod(new Method($collection, 'getOne'));
        $collection->registerMethod(new Method($collection, 'create'));
        $collection->registerMethod(new Method($collection, 'update'));
        $collection->registerMethod(new Method($collection, 'delete'));
        $collection->registerMethod(new Method($collection, 'updateOwner'));
        $collection->registerMethod(new Method($collection, 'getVariations'));
        $collection->registerMethod(new Method($collection, 'getVariation'));
        $collection->registerMethod(new Method($collection, 'getVariationFields'));
        $collection->registerMethod(new Method($collection, 'getVariationFieldsItem'));
        $collection->registerMethod(new Method($collection, 'createVariationField'));
        $collection->registerMethod(new Method($collection, 'updateVariationField'));
        $collection->registerMethod(new Method($collection, 'deleteVariationCollection'));
        $collection->registerMethod(new Method($collection, 'deleteVariationFields'));
        $collection->registerMethod(new Method($collection, 'activateVariations'));
        $collection->registerMethod(new Method($collection, 'createVariations'));
        $collection->registerMethod(new Method($collection, 'updateVariation'));
        $collection->registerMethod(new Method($collection, 'deleteVariation'));
        $collection->registerMethod(new Method($collection, 'getPrices'));
        $collection->registerMethod(new Method($collection, 'updatePrice'));
        $collection->registerMethod(new Method($collection, 'getBarCodes'));
        $collection->registerMethod(new Method($collection, 'createBarCode'));
        $collection->registerMethod(new Method($collection, 'updateBarCode'));
        $collection->registerMethod(new Method($collection, 'deleteBarCode'));
        $collection->registerMethod(new Method($collection, 'getCategories'));
        $collection->registerMethod(new Method($collection, 'getCategory'));
        $collection->registerMethod(new Method($collection, 'getParentCategories'));
        $collection->registerMethod(new Method($collection, 'getChildrenFromParentId'));
        $collection->registerMethod(new Method($collection, 'createCategory'));
        $collection->registerMethod(new Method($collection, 'updateCategory'));
        $collection->registerMethod(new Method($collection, 'deleteCategory'));
        $collection->registerMethod(new Method($collection, 'addPictureToGallery'));
        $collection->registerMethod(new Method($collection, 'updateTranslations'));
        $collection->registerMethod(new Method($collection, 'updateSharingGroups'));
        $collection->registerMethod(new Method($collection, 'getOneByRef'));

        return $collection;
    }
}
