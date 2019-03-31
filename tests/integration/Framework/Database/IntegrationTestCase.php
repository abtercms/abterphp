<?php

namespace Integration\Framework\Database;

use AbterPhp\Framework\Module\Manager;
use Opulence\Applications\Tasks\Dispatchers\ITaskDispatcher;
use Opulence\Databases\Migrations\IMigrator;
use Opulence\Framework\Configuration\Config;
use PHPUnit\Framework\TestCase;
use Opulence\Ioc\Bootstrappers\Caching\FileCache;
use Opulence\Ioc\Bootstrappers\Caching\ICache;
use Opulence\Ioc\Bootstrappers\Dispatchers\BootstrapperDispatcher;
use Opulence\Ioc\Bootstrappers\Factories\BootstrapperRegistryFactory;
use Opulence\Ioc\Bootstrappers\IBootstrapperResolver;
use Opulence\Ioc\IContainer;

/**
 * Defines the console application integration test
 */
class IntegrationTestCase extends TestCase
{
    /** @var IContainer The IoC container */
    protected $container = null;

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

        /** @var IMigrator $migrator */
        $migrator = $container->resolve(IMigrator::class);
        $migrator->runMigrations();

        parent::setUp();
    }

    public function tearDown()
    {
        // TODO: Fix this after https://github.com/opulencephp/Opulence/issues/106
        /** @var IMigrator $migrator */
//        $migrator = $this->container->resolve(IMigrator::class);
//        $migrator->rollBackAllMigrations();
    }
}
