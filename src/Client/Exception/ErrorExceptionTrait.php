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
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace Teknoo\Sellsy\Client\Exception;

use Teknoo\Sellsy\Client\ResultInterface;
use Throwable;

/**
 * Exception threw when the Sellsy API has been returned an error.
 *
 * @copyright   Copyright (c) 2009-2021 EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) 2020-2021 SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

trait ErrorExceptionTrait
{
    private ResultInterface $result;

    public function __construct(ResultInterface $result, ?Throwable $previous = null)
    {
        $this->result = $result;

        parent::__construct($result->getErrorMessage(), 0, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->result->getErrorCode();
    }

    /**
     * @return mixed|null
     */
    public function getMore()
    {
        if (!isset($this->result->more)) {
            return null;
        }

        return $this->result->more;
    }
}
