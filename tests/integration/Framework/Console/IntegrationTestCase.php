<?php

namespace Integration\Framework\Console;

use AbterPhp\Framework\Module\Manager;
use LogicException;
use Opulence\Applications\Tasks\Dispatchers\ITaskDispatcher;
use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Console\Testing\PhpUnit\IntegrationTestCase as BaseIntegrationTestCase;
use Opulence\Ioc\Bootstrappers\Caching\FileCache;
use Opulence\Ioc\Bootstrappers\Caching\ICache;
use Opulence\Ioc\Bootstrappers\Dispatchers\BootstrapperDispatcher;
use Opulence\Ioc\Bootstrappers\Factories\BootstrapperRegistryFactory;
use Opulence\Ioc\Bootstrappers\IBootstrapperResolver;
use Opulence\Ioc\IContainer;

/**
 * Defines the console application integration test
 */
class IntegrationTestCase extends BaseIntegrationTestCase
{
    /** @var array */
    protected $supportedTags = ['success', 'info', 'question', 'comment', 'error', 'fatal', 'b', 'u'];

    /** @var array */
    protected $compiledTags = [];

    /**
     * @return array
     */
    protected function getCompiledTags(): array
    {
        if (!empty($this->compiledTags)) {
            return $this->compiledTags;
        }

        foreach ($this->supportedTags as $supportedTag) {
            $this->compiledTags[] = "<$supportedTag>";
            $this->compiledTags[] = "</$supportedTag>";
        }

        return $this->compiledTags;
    }

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        /** @var Manager $abterModuleManager */
        global $abterModuleManager;

        $paths = require __DIR__ . '/../../../../config/paths.php';
        require __DIR__ . '/../../../../config/environment.php';
        require __DIR__ . '/../../../../config/application.php';

        /** @var IContainer $container */
        $this->container = $container;

        /**
         * ----------------------------------------------------------
         * Configure the bootstrappers for the console kernel
         * ----------------------------------------------------------
         *
         * @var string                $globalBootstrapperPath
         * @var array                 $globalBootstrappers
         * @var IBootstrapperResolver $bootstrapperResolver
         * @var ITaskDispatcher       $taskDispatcher
         */
        $consoleBootstrapperPath = Config::get('paths', 'config.console') . '/bootstrappers.php';
        $bootstrapperCache       = new FileCache(
            Config::get('paths', 'tmp.framework.console') . '/cachedBootstrapperRegistry.json',
            max(filemtime($globalBootstrapperPath), filemtime($consoleBootstrapperPath))
        );
        $abterModuleManager      = new \AbterPhp\Framework\Module\Manager(
            new \AbterPhp\Framework\Module\Loader(
                [
                    Config::get('paths', 'src'),
                    Config::get('paths', 'vendor'),
                ]
            )
        );
        $abterBootstrappers      = $abterModuleManager->getCliBootstrappers();
        $container->bindInstance(ICache::class, $bootstrapperCache);
        $consoleBootstrappers   = require $consoleBootstrapperPath;
        $allBootstrappers       = array_merge($globalBootstrappers, $consoleBootstrappers, $abterBootstrappers);
        $bootstrapperFactory    = new BootstrapperRegistryFactory($bootstrapperResolver);
        $bootstrapperRegistry   = $bootstrapperFactory->createBootstrapperRegistry($allBootstrappers);
        $bootstrapperDispatcher = new BootstrapperDispatcher($container, $bootstrapperRegistry, $bootstrapperResolver);
        $bootstrapperDispatcher->dispatch(false);

        parent::setUp();
    }

    /**
     * Asserts that the output is an expected value
     *
     * @param string $expected The expected output
     *
     * @return self For method chaining
     */
    public function outputContains(string $needle, string $message = ''): self
    {
        $needle = str_replace($this->getCompiledTags(), '', $needle);

        $this->checkResponseIsSet();
        $this->assertContains($needle, $this->getOutput(), $message);

        return $this;
    }

    /**
     * Checks if the response was set
     * Useful for making sure the response was set before making any assertions on it
     */
    private function checkResponseIsSet()
    {
        if ($this->response === null) {
            $this->fail('Must call call() before assertions');
        }
    }

    /**
     * Gets the output of the previous command
     *
     * @return string The output
     * @throws LogicException Thrown if the response is not set
     */
    private function getOutput(): string
    {
        if ($this->response === null) {
            throw new LogicException('Must call call() before assertions');
        }

        rewind($this->response->getStream());

        return stream_get_contents($this->response->getStream());
    }
}
