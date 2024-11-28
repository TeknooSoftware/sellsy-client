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

namespace Teknoo\Sellsy\Client\Exception;

use Teknoo\Sellsy\Client\ResultInterface;
use Throwable;

/**
 * Exception threw when the Sellsy API has been returned an error.
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
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
