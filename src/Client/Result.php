<?php

/**
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
 *
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @version     0.8.0
 */
namespace Teknoo\Sellsy\Client;

class Result implements ResultInterface
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @var array
     */
    private $decodedResult;

    /**
     * @var bool
     */
    private $isSuccess;

    /**
     * @var string
     */
    private $errorMessage = '';

    /**
     * Result constructor.
     * @param string $result
     */
    public function __construct($result)
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
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * {@inheritdoc}
     */
    public function isError(): bool
    {
        return !$this->isSuccess;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getRaw(): string
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
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