<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Teknoo\Sellsy\Method\MethodInterface;
use Teknoo\Sellsy\Transport\PromiseInterface;

/**
 * Interface to define an HTTP+OAuth client to use the Sellsy API with your credentials to execute some operations on
 * your account.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface ClientInterface
{
    /*
     * Get the last PSR7 request sent to the Sellsy server. It's a method to help debug.
     */
    public function getLastRequest(): ?RequestInterface;

    /*
     * Get the last PSR7 response sent by the Sellsy server. It's a method to help debug.
     */
    public function getLastResponse(): ?ResponseInterface;

    /**
     * To execute a method, referenced by the $method instance on the Sellsy server via it's api, authenticated with
     * your credentials.
     * @param array<string, mixed> $params
     */
    public function run(MethodInterface $method, array $params = []): ResultInterface;

    /**
     * To execute a method, referenced by the $method instance on the Sellsy server via it's api, authenticated with
     * your credentials.
     * @param array<string, mixed> $params
     */
    public function promise(MethodInterface $method, array $params = []): PromiseInterface;
}
