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
 * @link https://api.sellsy.com/documentation/methods#accountdatasgettaxes
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class AccountDatas implements DefinitionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Accountdatas');

        $collection->registerMethod(new Method($collection, 'getTaxes'));
        $collection->registerMethod(new Method($collection, 'getTaxe'));
        $collection->registerMethod(new Method($collection, 'createTaxe'));
        $collection->registerMethod(new Method($collection, 'updateTaxe'));
        $collection->registerMethod(new Method($collection, 'deleteTaxe'));
        $collection->registerMethod(new Method($collection, 'updatePayMediums'));
        $collection->registerMethod(new Method($collection, 'createPayMediums'));
        $collection->registerMethod(new Method($collection, 'createShippings'));
        $collection->registerMethod(new Method($collection, 'createPackagings'));
        $collection->registerMethod(new Method($collection, 'updatePackagings'));
        $collection->registerMethod(new Method($collection, 'updateTaxes'));
        $collection->registerMethod(new Method($collection, 'createTaxes'));
        $collection->registerMethod(new Method($collection, 'updateShippings'));
        $collection->registerMethod(new Method($collection, 'getUnits'));
        $collection->registerMethod(new Method($collection, 'getUnit'));
        $collection->registerMethod(new Method($collection, 'createUnit'));
        $collection->registerMethod(new Method($collection, 'createUnits'));
        $collection->registerMethod(new Method($collection, 'updateUnit'));
        $collection->registerMethod(new Method($collection, 'updateUnits'));
        $collection->registerMethod(new Method($collection, 'deleteUnit'));
        $collection->registerMethod(new Method($collection, 'getPackagingList'));
        $collection->registerMethod(new Method($collection, 'getPackaging'));
        $collection->registerMethod(new Method($collection, 'recordPackaging'));
        $collection->registerMethod(new Method($collection, 'deletePackaging'));
        $collection->registerMethod(new Method($collection, 'getShippingList'));
        $collection->registerMethod(new Method($collection, 'getShipping'));
        $collection->registerMethod(new Method($collection, 'recordShipping'));
        $collection->registerMethod(new Method($collection, 'deleteShipping'));
        $collection->registerMethod(new Method($collection, 'getPayMediums'));
        $collection->registerMethod(new Method($collection, 'getPayMedium'));
        $collection->registerMethod(new Method($collection, 'createPayMedium'));
        $collection->registerMethod(new Method($collection, 'updatePayMedium'));
        $collection->registerMethod(new Method($collection, 'getRateCategories'));
        $collection->registerMethod(new Method($collection, 'getRateCategory'));
        $collection->registerMethod(new Method($collection, 'getDocLayouts'));
        $collection->registerMethod(new Method($collection, 'getPayDates'));
        $collection->registerMethod(new Method($collection, 'getTranslationLanguages'));
        $collection->registerMethod(new Method($collection, 'deletePayMedium'));

        return $collection;
    }
}
