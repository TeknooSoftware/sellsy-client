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

namespace Teknoo\Sellsy\Method;

use Teknoo\Immutable\ImmutableInterface;;

/**
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface ParameterInterface extends ImmutableInterface
{
    /**
     * To know the name of the parameter in the Sellsy API.
     */
    public function getName(): string;

    /**
     * To define types attempted for this parameter
     * @return array<string|int, string|ParameterInterface>
     */
    public function getTypes(): array;

    /**
     * If this parameter represent an array
     */
    public function isArray(): bool;

    /**
     * Return an array of definitions to pass to filter_var_array
     * @return array
     */
    public function getFilterDefinition(): array;
}
