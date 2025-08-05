<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the 3-Clause BSD license
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
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
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
 * @link https://api.sellsy.com/documentation/methods#documentgetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Document implements DefinitionInterface
{
    public function __invoke(ClientInterface $client): CollectionInterface
    {
        $collection = new Collection($client, 'Document');

        $collection->registerMethod(new Method($collection, 'getList'));
        $collection->registerMethod(new Method($collection, 'getOne'));
        $collection->registerMethod(new Method($collection, 'getPublicLink'));
        $collection->registerMethod(new Method($collection, 'getPublicLink_v2'));
        $collection->registerMethod(new Method($collection, 'getNextIdent'));
        $collection->registerMethod(new Method($collection, 'create'));
        $collection->registerMethod(new Method($collection, 'update'));
        $collection->registerMethod(new Method($collection, 'delete'));
        $collection->registerMethod(new Method($collection, 'updateDeadlines'));
        $collection->registerMethod(new Method($collection, 'updateOwner'));
        $collection->registerMethod(new Method($collection, 'updateStep'));
        $collection->registerMethod(new Method($collection, 'getModel'));
        $collection->registerMethod(new Method($collection, 'updateDeliveryStep'));
        $collection->registerMethod(new Method($collection, 'sendDocByMail'));
        $collection->registerMethod(new Method($collection, 'getPaymentList'));
        $collection->registerMethod(new Method($collection, 'getForCopy'));
        $collection->registerMethod(new Method($collection, 'createPayment'));
        $collection->registerMethod(new Method($collection, 'updatePayment'));
        $collection->registerMethod(new Method($collection, 'deletePayment'));
        $collection->registerMethod(new Method($collection, 'getPaymentUrl'));
        $collection->registerMethod(new Method($collection, 'updateFields'));
        $collection->registerMethod(new Method($collection, 'linkToDoc'));
        $collection->registerMethod(new Method($collection, 'getLinkedDocuments'));
        $collection->registerMethod(new Method($collection, 'getTree'));
        $collection->registerMethod(new Method($collection, 'getPayment'));
        $collection->registerMethod(new Method($collection, 'getNumberingDraftStatus'));
        $collection->registerMethod(new Method($collection, 'linkPurchase'));
        $collection->registerMethod(new Method($collection, 'updateSharingGroups'));
        $collection->registerMethod(new Method($collection, 'enablePublicLink'));
        $collection->registerMethod(new Method($collection, 'disablePublicLink'));
        $collection->registerMethod(new Method($collection, 'validate'));

        return $collection;
    }
}
