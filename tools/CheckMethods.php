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

namespace Teknoo\Sellsy\Tools;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Teknoo\Sellsy\Sellsy;
use Teknoo\Sellsy\Transport\PromiseInterface;
use Teknoo\Sellsy\Transport\TransportInterface;

/**
 * To check if all methods available in the api are available here.
 *
 * @link https://api.sellsy.com/documentation/methods#ttgetlist
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/sellsy-client Project website
 *
 * @license     http://teknoo.software/sellsy-client/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class CheckMethods extends Command
{
    /**
     * To extract from the documentation (downloaded from the Sellsy server) all methods declared into it.
     * @return array<string, string>
     */
    private function extractMethodsName(string $websiteUrl): array
    {
        $documentSource = \file_get_contents($websiteUrl);

        $methods = [];
        $pattern = '#<div class="page-header">.*?<h1><a name="[a-z0-9]+"></a>([a-z0-9]+\.[a-z0-9]+)</h1>#isS';
        if (false === \preg_match_all($pattern, $documentSource, $methods)) {
            throw new \RuntimeException(\preg_last_error());
        }

        return $methods[1];
    }

    /**
     * To return a instance of sellsy to check if a collection and a method is defined and is available here.
     *
     * TO prevent some issues with the inconsistency of the Sellsy Document, this method will preload some collections.
     *
     * to avoid false positive.
     * @return Sellsy
     */
    private function getSellsyInstance(): Sellsy
    {
        $sellSy = new Sellsy('', '', '', '', '');
        $transport = new class implements TransportInterface {
            public function createUri(string $uri = ''): UriInterface
            {
                throw new \RuntimeException('Not implemented');
            }

            public function createRequest(string $method, $uri): RequestInterface
            {
                throw new \RuntimeException('Not implemented');
            }

            public function createStream(array &$elements, ?RequestInterface $request = null): StreamInterface
            {
                throw new \RuntimeException('Not implemented');
            }

            public function asyncExecute(RequestInterface $request): PromiseInterface
            {
                throw new \RuntimeException('Not implemented');
            }
        };

        $sellSy->setTransport($transport);
        //To prevent case issues from the inconsistent documentation
        $sellSy->AccountDatas();
        $sellSy->TimeTracking();

        return $sellSy;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('teknoo:sellsy:checks-methods');
        $this->setDescription('To check if all methods available in the Sellsy API are defined here');
        $this->addArgument('website', InputArgument::REQUIRED, 'The URL of the documentation provided by Sellsy');
        $this->addOption('ignore', 'i', InputOption::VALUE_OPTIONAL, 'Collection to ignore');
    }


    private function testMethods(
        array $methodsList,
        array &$missingCollections,
        array &$missingMethods,
        array $collectionsToIgnore
    ) {
        $sellsy = $this->getSellsyInstance();

        foreach ($methodsList as $method) {
            list($collection, $methodName) = \explode('.', $method);

            if (isset($collectionsToIgnore[$collection])) {
                continue;
            }

            try {
                $collection = $sellsy->{$collection}();
            } catch (\DomainException $e) {
                $missingCollections[$collection] = $collection;
                $missingMethods[$method] = $method;

                continue;
            }

            try {
                $collection->{$methodName};
            } catch (\DomainException $e) {
                $missingMethods[$method] = $method;
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $websiteUrl = $input->getArgument('website');
        $output->writeln(\sprintf('Check the documentation at "%s"', $websiteUrl));

        try {
            $methodsList = $this->extractMethodsName($websiteUrl);
        } catch (\Throwable $e) {
            $output->writeln($e->getMessage());
            return 1;
        }

        $collectionsToIgnore = \array_flip(\explode(',', $input->getOption('ignore')));
        $missingCollections = [];
        $missingMethods = [];

        $this->testMethods($methodsList, $missingCollections, $missingMethods, $collectionsToIgnore);

        if (empty($missingCollections) && empty($missingMethods)) {
            $output->writeln('Definitions is synchronized');
            return 0;
        }

        $output->writeln(PHP_EOL . 'Missing collections :');
        foreach ($missingCollections as $collection) {
            $output->writeln($collection);
        }

        $output->writeln(PHP_EOL . 'Missing methods :');
        foreach ($missingMethods as $method) {
            $output->writeln($method);
        }

        return 1;
    }
}
