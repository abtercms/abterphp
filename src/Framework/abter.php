<?php

namespace AbterPhp\Framework;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER         => 'AbterPhp\Framework',
    Module::DEPENDENCIES       => [],
    Module::BOOTSTRAPPERS      => [
        Bootstrappers\Assets\AssetManagerBootstrapper::class,
        Bootstrappers\Authorization\CacheManagerBootstrapper::class,
        Bootstrappers\Cache\CacheBootstrapper::class,
        Bootstrappers\Crypto\CryptoBootstrapper::class,
        Bootstrappers\Databases\SqlBootstrapper::class,
        Bootstrappers\Email\TransportBootstrapper::class,
        Bootstrappers\Events\EventDispatcherBootstrapper::class,
        Bootstrappers\Filesystem\FilesystemBootstrapper::class,
        Bootstrappers\Http\LoggerBootstrapper::class,
        Bootstrappers\Http\RouterBootstrapper::class,
        Bootstrappers\Http\ViewBootstrapper::class,
        Bootstrappers\Session\FlashServiceBootstrapper::class,
        Bootstrappers\Template\CacheManagerBootstrapper::class,
        Bootstrappers\Template\EngineBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS  => [
        Bootstrappers\Console\Commands\Cache\FlushCacheBootstrapper::class,
        Bootstrappers\Console\Commands\Security\SecretGeneratorBootstrapper::class,
        Bootstrappers\Databases\QueryFileLoaderBootstrapper::class,
        Bootstrappers\Databases\MigrationsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Authorization\EnforcerBootstrapper::class,
        Bootstrappers\Dashboard\DashboardBootstrapper::class,
        Bootstrappers\Grid\GridBootstrapper::class,
        Bootstrappers\Http\SessionBootstrapper::class,
        Bootstrappers\I18n\I18nBootstrapper::class,
        Bootstrappers\Navigation\NavigationBootstrapper::class,
        Bootstrappers\Views\ViewFunctionsBootstrapper::class,
    ],
    Module::COMMANDS           => [
        Assets\Command\FlushCache::class,
        Authorization\Command\FlushCache::class,
        Console\Commands\Cache\FlushCache::class,
        Console\Commands\Security\SecretGenerator::class,
        Console\Commands\Template\FlushCache::class,
    ],
    Module::EVENTS             => [
        Event::NAVIGATION_READY => [
            /** @see \AbterPhp\Framework\Events\Listeners\NavigationBuilder::handle */
            sprintf('%s@handle', Events\Listeners\NavigationBuilder::class),
        ],
    ],
    Module::MIDDLEWARE             => [
        1000 => [
            Http\Middleware\EnvironmentWarning::class,
            Http\Middleware\Session::class,
            Http\Middleware\Security::class,
        ],
    ],
];
