<?php

namespace AbterPhp\Website;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER         => 'AbterPhp\Website',
    Module::DEPENDENCIES       => ['AbterPhp\Framework'],
    Module::BOOTSTRAPPERS      => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Website\IndexBootstrapper::class,
        Bootstrappers\Http\Views\BuildersBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Website\Events\Listeners\TemplateRegistrar::register */
            sprintf('%s@register', Events\Listeners\TemplateRegistrar::class),
        ],
        Event::NAVIGATION_READY      => [
            /** @see \AbterPhp\Website\Events\Listeners\NavigationRegistrar::register */
            sprintf('%s@register', Events\Listeners\NavigationRegistrar::class),
        ],
        Event::ENTITY_POST_CHANGE    => [
            /** @see \AbterPhp\Website\Events\Listeners\PageInvalidator::handle() */
            sprintf('%s@handle', Events\Listeners\PageInvalidator::class),
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Website\Events\Listeners\DashboardRegistrar::handle() */
            sprintf('%s@handle', Events\Listeners\DashboardRegistrar::class),
        ],
    ],
    Module::ROUTE_PATHS        => [
        50000 => [
            __DIR__ . '/routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        1000 => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
