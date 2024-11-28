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
 * @link https://api.sellsy.com/documentation/methods#clientsgetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Client implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Client');

        $collection->registerMethod(new Method($collection, 'getList'));
        $collection->registerMethod(new Method($collection, 'getOne'));
        $collection->registerMethod(new Method($collection, 'getAddress'));
        $collection->registerMethod(new Method($collection, 'getContact'));
        $collection->registerMethod(new Method($collection, 'getContactList'));
        $collection->registerMethod(new Method($collection, 'create'));
        $collection->registerMethod(new Method($collection, 'update'));
        $collection->registerMethod(new Method($collection, 'delete'));
        $collection->registerMethod(new Method($collection, 'updateOwner'));
        $collection->registerMethod(new Method($collection, 'addAddress'));
        $collection->registerMethod(new Method($collection, 'addContact'));
        $collection->registerMethod(new Method($collection, 'updateAddress'));
        $collection->registerMethod(new Method($collection, 'updateContact'));
        $collection->registerMethod(new Method($collection, 'deleteAddress'));
        $collection->registerMethod(new Method($collection, 'deleteContact'));
        $collection->registerMethod(new Method($collection, 'updateContactPicture'));
        $collection->registerMethod(new Method($collection, 'updatePrefs'));
        $collection->registerMethod(new Method($collection, 'transformToProspect'));
        $collection->registerMethod(new Method($collection, 'getBankAccountList'));
        $collection->registerMethod(new Method($collection, 'sendContactPwd'));
        $collection->registerMethod(new Method($collection, 'updateThirdPicture'));
        $collection->registerMethod(new Method($collection, 'updateSharingStaffs'));
        $collection->registerMethod(new Method($collection, 'updateSharingGroups'));
        $collection->registerMethod(new Method($collection, 'getMargin'));
        $collection->registerMethod(new Method($collection, 'getBillingContact'));

        return $collection;
    }
}
