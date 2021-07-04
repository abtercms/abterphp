<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Console;

use Opulence\Console\Commands\CommandCollection;
use Opulence\Framework\Composer\Console\Commands\ComposerDumpAutoloadCommand;
use Opulence\Framework\Composer\Console\Commands\ComposerUpdateCommand;
use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Console\Bootstrappers\CommandsBootstrapper as BaseBootstrapper;
use Opulence\Framework\Console\Commands\AppDownCommand;
use Opulence\Framework\Console\Commands\AppEnvironmentCommand;
use Opulence\Framework\Console\Commands\AppUpCommand;
use Opulence\Framework\Console\Commands\FlushFrameworkCacheCommand;
use Opulence\Framework\Console\Commands\MakeCommandCommand;
use Opulence\Framework\Console\Commands\RenameAppCommand;
use Opulence\Framework\Console\Commands\RunAppLocallyCommand;
use Opulence\Framework\Cryptography\Console\Commands\EncryptionKeyGenerationCommand;
use Opulence\Framework\Cryptography\Console\Commands\UuidGenerationCommand;
//use Opulence\Framework\Databases\Console\Commands\FixMigrationsCommand;
use Opulence\Framework\Databases\Console\Commands\MakeMigrationCommand;
use Opulence\Framework\Databases\Console\Commands\RunDownMigrationsCommand;
use Opulence\Framework\Databases\Console\Commands\RunUpMigrationsCommand;
use Opulence\Framework\Orm\Console\Commands\MakeDataMapperCommand;
use Opulence\Framework\Orm\Console\Commands\MakeEntityCommand;
use Opulence\Framework\Routing\Console\Commands\MakeControllerCommand;
use Opulence\Framework\Routing\Console\Commands\MakeHttpMiddlewareCommand;
use Opulence\Framework\Views\Console\Commands\FlushViewCacheCommand;
use Opulence\Ioc\Bootstrappers\Caching\FileCache;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Routing\Routes\Caching\ICache as RouteCache;
use Opulence\Views\Caching\ICache as ViewCache;
use RuntimeException;

class CommandsBootstrapper extends BaseBootstrapper
{
    /** @var array The list of built-in command classes */
    private static $commandClasses = [
        AppDownCommand::class,
        AppEnvironmentCommand::class,
        AppUpCommand::class,
        ComposerDumpAutoloadCommand::class,
        ComposerUpdateCommand::class,
        EncryptionKeyGenerationCommand::class,
        FlushViewCacheCommand::class,
        MakeCommandCommand::class,
        MakeControllerCommand::class,
        MakeMigrationCommand::class,
        MakeDataMapperCommand::class,
        MakeEntityCommand::class,
        MakeHttpMiddlewareCommand::class,
        RenameAppCommand::class,
        RunDownMigrationsCommand::class,
        RunUpMigrationsCommand::class,
//        FixMigrationsCommand::class,
        UuidGenerationCommand::class
    ];

    /**
     * Binds commands to the collection
     *
     * @param CommandCollection $commands The collection to add commands to
     * @param IContainer $container The dependency injection container to use
     */
    protected function bindCommands(CommandCollection $commands, IContainer $container)
    {
        // Resolve and add each command class
        foreach (static::$commandClasses as $commandClass) {
            $commands->add($container->resolve($commandClass));
        }

        // The command to run Opulence locally requires a path to the router file
        $commands->add(new RunAppLocallyCommand(Config::get('paths', 'root') . '/localhost_router.php'));

        // The flush-cache command requires some special configuration
        try {
            $flushCacheCommand = new FlushFrameworkCacheCommand(
                new FileCache(Config::get('paths', 'tmp.framework.http') . '/cachedBootstrapperRegistry.json'),
                new FileCache(Config::get('paths', 'tmp.framework.console') . '/cachedBootstrapperRegistry.json'),
                $container->resolve(RouteCache::class),
                $container->resolve(ViewCache::class)
            );
            $commands->add($flushCacheCommand);
        } catch (IocException $ex) {
            throw new RuntimeException('Failed to resolve ' . FlushFrameworkCacheCommand::class, 0, $ex);
        }
    }
}
