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
use Teknoo\Sellsy\Collection\CollectionInterface;
use Teknoo\Sellsy\Client\ResultInterface;
use Teknoo\Sellsy\Sellsy;

/**
 * Implementation to define entity able to represent an available method in the Sellsy Api/
 * Instance are directly invokable, but parameters must passed into an array and not been passed
 * like a normal PHP method.
 *
 * @copyright   Copyright (c) 2009-2019 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Method implements MethodInterface
{
    use ImmutableTrait;

    private CollectionInterface $collection;

    private string $name;

    /**
     * @var array<ParameterInterface>
     */
    private array $parameters;

    private ?Sellsy $sellsy = null;

    /**
     * @param array<ParameterInterface> $parameters
     */
    public function __construct(CollectionInterface $collection, string $name, array $parameters = [])
    {
        $this->collection = $collection;
        $this->name = $name;
        $this->parameters = $parameters;

        $this->collection->registerMethod($this);

        $this->uniqueConstructorCheck();
    }

    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<ParameterInterface>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    private ?array $definitions = null;

    private function getDefinitions(): array
    {
        if (null !== $this->definitions) {
            return $this->definitions;
        }

        $this->definitions = [];
        foreach ($this->parameters as $parameter) {
            $this->parameters += $parameter->getFilterDefinition();
        }

        return $this->parameters;
    }

    private function checksParameters(array &$params): array
    {
        if (null === $this->sellsy || !$this->sellsy->hasParametersCheckingsEnabled()) {
            return $params;
        }

        if (\count($this->parameters) !== ($count = \count($params))) {
            throw new \RuntimeException('Error');
        }

        if (empty($params)) {
            return $params;
        }

        $result = \filter_var_array($params, $this->getDefinitions());
        if (false === $result) {
            throw new \RuntimeException('Error');
        }

        return $result;
    }

    /**
     * @param array<mixed, mixed> $params
     */
    public function __invoke(array $params = []): ResultInterface
    {
        $client = $this->collection->getClient();

        $params = $this->checksParameters($params);

        return $client->run($this, $params);
    }

    public function __toString(): string
    {
        return ($this->collection->getName()) . '.' . $this->name;
    }
}
