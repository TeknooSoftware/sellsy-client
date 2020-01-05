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
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Sellsy\Client;

use Teknoo\Immutable\ImmutableTrait;

/**
 * Implementation immutable value object encapsuling result/response about a Sellsy operation.
 *
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
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
    private string $result;

    /**
     * Decoded result from Sellsy API.
     *
     * @var array<string, mixed>
     */
    private array $decodedResult;

    // To know if the method has been correctly executed.
    private bool $isSuccess;

    // To know the reason of the error.
    private string $errorMessage = '';

    public function __construct(string $result)
    {
        $this->result = $result;
        $this->decodedResult = \json_decode($result, true);

        //Bad request, error returned by the api, throw an error
        if (!empty($this->decodedResult['status']) && 'error' == $this->decodedResult['status']) {
            $this->isSuccess = false;
            if (!empty($this->decodedResult['error']['message'])) {
                //Retrieve error message like it's defined in Sellsy API documentation
                $this->errorMessage = $this->decodedResult['error']['message'];
            } elseif (\is_string($this->decodedResult['error'])) {
                //Retrieve error message (sometime, error is not an object...)
                $this->errorMessage = $this->decodedResult['error'];
            } else {
                //Other case, return directly the answer
                $this->errorMessage = $result;
            }
        } else {
            $this->isSuccess = true;
        }

        $this->uniqueConstructorCheck();
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function isError(): bool
    {
        return !$this->isSuccess;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getRaw(): string
    {
        return $this->result;
    }

    public function getResponse()
    {
        if (!isset($this->decodedResult['response'])) {
            if (isset($this->decodedResult['error'])) {
                return $this->decodedResult['error'];
            }

            throw new \RuntimeException('Missing response value');
        }

        return $this->decodedResult['response'];
    }
}
