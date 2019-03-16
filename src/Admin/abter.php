<?php

namespace AbterPhp\Admin;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER         => 'AbterPhp\Admin',
    Module::DEPENDENCIES       => ['AbterPhp\Framework'],
    Module::BOOTSTRAPPERS      => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS  => [
        Bootstrappers\Console\Commands\CommandsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Execute\LoginBootstrapper::class,
        Bootstrappers\Http\Controllers\Form\LoginBootstrapper::class,
        Bootstrappers\Http\Controllers\Form\UserBootstrapper::class,
        Bootstrappers\Http\Views\BuildersBootstrapper::class,
        Bootstrappers\Vendor\SlugifyBootstrapper::class,
    ],
    Module::COMMANDS           => [
        Console\Commands\User\Create::class,
        Console\Commands\User\Delete::class,
        Console\Commands\User\UpdatePassword::class,
        Console\Commands\UserGroup\Display::class,
    ],
    Module::EVENTS             => [
        Event::AUTH_READY         => [
            /** @see Events\Listeners\AuthRegistrar::register */
            sprintf('%s@register', Events\Listeners\AuthRegistrar::class),
        ],
        Event::NAVIGATION_READY   => [
            /** @see Events\Listeners\NavigationRegistrar::register */
            sprintf('%s@register', Events\Listeners\NavigationRegistrar::class),
        ],
        Event::ENTITY_POST_CHANGE => [
            /** @see Events\Listeners\AuthInvalidator::handle() */
            sprintf('%s@handle', Events\Listeners\AuthInvalidator::class),
        ],
        Event::DASHBOARD_READY    => [
            /** @see Events\Listeners\DashboardRegistrar::handle() */
            sprintf('%s@handle', Events\Listeners\DashboardRegistrar::class),
        ],
    ],
    Module::MIDDLEWARE         => [
        1000 => [
            Http\Middleware\CheckCsrfToken::class,
            Http\Middleware\Security::class,
        ],
    ],
    Module::ROUTE_PATHS        => [
        1000 => [
            __DIR__ . '/routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        1000 => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
