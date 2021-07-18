<?php

use AbterPhp\Files\Bootstrappers;
use AbterPhp\Files\Console;
use AbterPhp\Files\Events;
use AbterPhp\Files\Routes;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;

return [
    Module::IDENTIFIER          => 'AbterPhp\Files',
    Module::DEPENDENCIES        => ['AbterPhp\Admin'],
    Module::ENABLED             => true,
    Module::BOOTSTRAPPERS       => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS   => [
        Bootstrappers\Database\MigrationsBootstrapper::class,
    ],
    Module::COMMANDS            => [
        Console\Commands\File\Cleanup::class,
    ],
    Module::EVENTS              => [
        Event::AUTH_READY            => [
            /** @see Events\Listeners\AuthInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\AuthInitializer::class)],
        ],
        Event::TEMPLATE_ENGINE_READY => [
            /** @see Events\Listeners\TemplateInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\TemplateInitializer::class)],
        ],
        Event::NAVIGATION_READY      => [
            /** @see Events\Listeners\NavigationBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\NavigationBuilder::class)],
        ],
        Event::DASHBOARD_READY       => [
            /** @see Events\Listeners\DashboardBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\DashboardBuilder::class)],
        ],
    ],
    Module::ROUTE_CONFIGURATORS => [
        Priorities::BELOW_NORMAL => [
            Routes\RouteConfigurator::class,
        ],
    ],
    Module::MIGRATION_PATHS     => [
        Priorities::NORMAL => [
            realpath(__DIR__ . '/Databases/Migration'),
        ],
    ],
    Module::RESOURCE_PATH       => realpath(__DIR__ . '/resources'),
];
