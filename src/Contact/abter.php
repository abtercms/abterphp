<?php

namespace AbterPhp\Contact;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER         => 'AbterPhp\Contact',
    Module::DEPENDENCIES       => ['AbterPhp\Website'],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Website\ContactBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Contact\Events\Listeners\TemplateRegistrar::register */
            sprintf('%s@register', Events\Listeners\TemplateRegistrar::class),
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Contact\Events\Listeners\DashboardRegistrar::handle() */
            sprintf('%s@handle', Events\Listeners\DashboardRegistrar::class),
        ],
    ],
    Module::ROUTE_PATHS        => [
        2000 => [
            __DIR__ . '/routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        1000 => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
