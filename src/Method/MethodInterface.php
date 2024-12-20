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

namespace Teknoo\Sellsy\Method;

use Teknoo\Immutable\ImmutableInterface;
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Interface to define entity able to represent an available method in the Sellsy Api/
 * Instance are directly invokable, but parameters must passed into an array and not been passed like a
 * normal PHP method.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
interface MethodInterface extends ImmutableInterface
{
    /*
     * To know the collection attached to this method.
     */
    public function getCollection(): CollectionInterface;

    /*
     * To know the name of the method in the Sellsy API.
     */
    public function getName(): string;

    public function async(): MethodInterface;

    /**
     * To execute the method on the Sellsy API.
     *
     * @param array<mixed, mixed> $params
     * @return ResultInterface|PromiseInterface
     */
    public function __invoke(array $params = []);

    /*
     * To know the name of the method in the Sellsy api, accompanied with the collection name.
     */
    public function __toString(): string;
}
