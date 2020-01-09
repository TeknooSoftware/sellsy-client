<?php

/*
 * Sellsy Client.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Client;

use Arrayy\Arrayy;
use Teknoo\Immutable\ImmutableTrait;

/**
 * Implementation immutable value object encapsuling result/response about a Sellsy operation.
 *
 * @copyright   Copyright (c) 2009-2020 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Result implements ResultInterface
{
    use ImmutableTrait;

    // Raw result from the Sellsy API.
    private string $raw;

    /**
     * @var array<string, mixed>
     */
    private array $decoded;

    // To know if the method has been correctly executed.
    private bool $isSuccess;

    // To know the reason of the error.
    private string $errorCode = '';

    // To know the reason of the error.
    private string $errorMessage = '';

    public function __construct(string &$result)
    {
        $this->raw = $result;
        try {
            $this->decoded = \json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $error) {
            $this->decoded = [];
        }

        $this->parseResult();

        $this->uniqueConstructorCheck();
    }

    private function parseResult(): void
    {
        if (!empty($this->decoded['status']) && 'error' !== $this->decoded['status']) {
            $this->isSuccess = true;

            return;
        }

        //Bad request, error returned by the api, throw an error
        $this->isSuccess = false;
        if (!empty($this->decoded['error']['message'])) {
            //Retrieve error message like it's defined in Sellsy API documentation
            $this->errorCode = (string) ($this->decoded['error']['code'] ?? 'E_UNKNOW');
            $this->errorMessage = (string) $this->decoded['error']['message'];

            return;
        }

        if (isset($this->decoded['error']) && \is_string($this->decoded['error'])) {
            //Retrieve error message (sometime, error is not an object...)
            $this->errorCode = 'E_UNKNOW';
            $this->errorMessage = (string) $this->decoded['error'];

            return;
        }

        //Other case, return directly the answer
        $this->errorCode = 'E_UNKNOW';
        $this->errorMessage = (string) $this->raw;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function isError(): bool
    {
        return !$this->isSuccess;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function hasResponse(): bool
    {
        return !empty($this->decoded['error']) || !empty($this->decoded['response']);
    }

    /**
     * To get the answer, in an usable format (array).
     * @return string|array<mixed, mixed>
     */
    public function getResponse()
    {
        if (!empty($this->decoded['response'])) {
            return $this->decoded['response'];
        }

        if (!empty($this->decoded['error'])) {
            return $this->decoded['error'];
        }

        throw new \RuntimeException('No response available');
    }

    /**
     * @return mixed|array<string, mixed>
     */
    public function __get(string $name)
    {
        if (isset($this->decoded['response'][$name]) && !\is_array($this->decoded['response'][$name])) {
            return $this->decoded['response'][$name];
        }

        if (isset($this->decoded['error'][$name]) && !\is_array($this->decoded['error'][$name])) {
            return $this->decoded['error'][$name];
        }

        if (isset($this->decoded['error'][$name])) {
            return (new Arrayy($this->decoded['error']))[$name];
        }

        if (isset($this->decoded['response'][$name])) {
            return (new Arrayy($this->decoded['response']))[$name];
        }

        throw new \InvalidArgumentException("$name does not exist in the response");
    }

    public function __isset(string $name): bool
    {
        return isset($this->decoded['response'][$name]) || isset($this->decoded['error'][$name]);
    }
}
