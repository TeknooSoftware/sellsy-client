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

use Teknoo\Immutable\ImmutableTrait;

/**
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Parameter implements ParameterInterface
{
    use ImmutableTrait;

    private string $name;

    /**
     * @var array<string|int, string|ParameterInterface>
     */
    private array $types;

    private bool $isArray;

    /**
     * @param array<string|ParameterInterface> $types
     */
    public function __construct(string $name, array $types, bool $isArray)
    {
        $this->name = $name;
        $this->types = $types;
        $this->isArray = $isArray;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function getFilterDefinition(): array
    {
        if (!$this->isArray && !empty($this->types)) {
            $filter = $this->types & (
                FILTER_VALIDATE_INT | FILTER_VALIDATE_BOOLEAN | FILTER_VALIDATE_FLOAT | FILTER_REQUIRE_SCALAR
            );
        } elseif ($this->isArray) {
            $filter = FILTER_REQUIRE_ARRAY;
        }

        return [$this->name => [
            'filter' => $filter
        ]];
    }
}
