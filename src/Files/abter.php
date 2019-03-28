<?php

namespace AbterPhp\Files;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER    => 'AbterPhp\Files',
    Module::DEPENDENCIES  => ['AbterPhp\Admin'],
    Module::BOOTSTRAPPERS => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::COMMANDS      => [
        Console\Commands\File\Cleanup::class,
    ],
    Module::ROUTE_PATHS        => [
        800 => [
            __DIR__ . '/routes.php',
        ],
    ],
    Module::EVENTS        => [
        Event::AUTH_READY            => [
            /** @see \AbterPhp\Files\Events\Listeners\AuthInitializer::handle */
            sprintf('%s@handle', Events\Listeners\AuthInitializer::class),
        ],
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Files\Events\Listeners\TemplateInitializer::handle */
            sprintf('%s@handle', Events\Listeners\TemplateInitializer::class),
        ],
        Event::NAVIGATION_READY      => [
            /** @see \AbterPhp\Files\Events\Listeners\NavigationBuilder::handle */
            sprintf('%s@handle', Events\Listeners\NavigationBuilder::class),
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Files\Events\Listeners\DashboardBuilder::handle */
            sprintf('%s@handle', Events\Listeners\DashboardBuilder::class),
        ],
    ],
    Module::MIGRATION_PATHS    => [
        1000 => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
