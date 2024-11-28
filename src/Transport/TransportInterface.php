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

namespace Teknoo\Sellsy\Transport;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Interface to define a transporter, able to initialize a PSR7 request for the client and send it to the Sellsy API
 * and return PSR7 response.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
interface TransportInterface extends RequestFactoryInterface, UriFactoryInterface
{
    /**
     * To initialize a PSR7 Stream, compatible with the content type multipart/form-data, needed to execute the request,
     * Sellsy API accepts only requests with a content type defined to "multipart/form-data".
     *
     * @param array<mixed, mixed> $elements
     */
    public function createStream(array &$elements, ?RequestInterface $request = null): StreamInterface;

    /*
     * To execute the PSR7 request, from the Sellsy client.
     */
    public function asyncExecute(RequestInterface $request): PromiseInterface;
}
