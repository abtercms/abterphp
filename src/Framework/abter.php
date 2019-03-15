<?php

namespace AbterPhp\Framework;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER         => 'AbterPhp\Framework',
    Module::DEPENDENCIES       => [],
    Module::BOOTSTRAPPERS      => [
        Assets\Bootstrappers\AssetManagerBootstrapper::class,
        Authorization\Bootstrappers\CacheManagerBootstrapper::class,
        Cache\Bootstrappers\CacheBootstrapper::class,
        Crypto\Bootstrappers\CryptoBootstrapper::class,
        Databases\Bootstrappers\SqlBootstrapper::class,
        Email\Bootstrappers\TransportBootstrapper::class,
        Events\Bootstrappers\EventDispatcherBootstrapper::class,
        Filesystem\Bootstrappers\FilesystemBootstrapper::class,
        Http\Bootstrappers\LoggerBootstrapper::class,
        Http\Bootstrappers\RouterBootstrapper::class,
        Http\Bootstrappers\ViewBootstrapper::class,
        Session\Bootstrappers\FlashServiceBootstrapper::class,
        Template\Bootstrappers\CacheManagerBootstrapper::class,
        Template\Bootstrappers\TemplateEngineBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS  => [
        Console\Bootstrappers\Commands\Cache\FlushCacheBootstrapper::class,
        Console\Bootstrappers\Commands\Security\SecretGeneratorBootstrapper::class,
        Databases\Bootstrappers\QueryFileLoaderBootstrapper::class,
        Databases\Bootstrappers\MigrationsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Authorization\Bootstrappers\EnforcerBootstrapper::class,
        Dashboard\Bootstrappers\DashboardBootstrapper::class,
        Grid\Bootstrappers\GridBootstrapper::class,
        Http\Bootstrappers\SessionBootstrapper::class,
        I18n\Bootstrappers\I18nBootstrapper::class,
        Navigation\Bootstrappers\PrimaryBootstrapper::class,
        Views\Bootstrappers\ViewFunctionsBootstrapper::class,
    ],
    Module::COMMANDS           => [
        Assets\Command\FlushCache::class,
        Authorization\Command\FlushCache::class,
        Console\Commands\Cache\FlushCache::class,
        Console\Commands\Security\SecretGenerator::class,
        Template\Command\FlushCache::class,
    ],
    Module::EVENTS             => [
        Event::NAVIGATION_READY => [
            /** @see \AbterPhp\Framework\Events\Listeners\NavigationRegistrar::register */
            sprintf('%s@register', Events\Listeners\NavigationRegistrar::class),
        ],
    ],
    Module::MIDDLEWARE             => [
        1000 => [
            Http\Middleware\EnvironmentWarning::class,
            Http\Middleware\Session::class,
            Http\Middleware\Security::class,
        ],
    ],
    Module::MIGRATION_PATHS    => [
        1000 => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
