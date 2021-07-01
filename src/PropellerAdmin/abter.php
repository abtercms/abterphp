<?php

use AbterPhp\Admin\Constant\Event as AdminEvent;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;
use AbterPhp\PropellerAdmin\Bootstrappers;
use AbterPhp\PropellerAdmin\Events;

return [
    Module::IDENTIFIER         => 'AbterPhp\PropellerAdmin',
    Module::DEPENDENCIES       => ['AbterPhp\Admin'],
    Module::ENABLED            => true,
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Events\ListenersBootstrapper::class,
        Bootstrappers\Html\Component\ButtonFactoryBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::NAVIGATION_READY => [
            /** @see Events\Listeners\NavigationBuilder::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\NavigationBuilder::class)],
        ],
        Event::FORM_READY       => [
            /** @see Events\Listeners\FormDecorator::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\FormDecorator::class)],
        ],
        Event::GRID_READY       => [
            /** @see Events\Listeners\GridDecorator::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\GridDecorator::class)],
        ],
        AdminEvent::ADMIN_READY => [
            /** @see Events\Listeners\AdminDecorator::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\AdminDecorator::class)],
        ],
        AdminEvent::LOGIN_READY => [
            /** @see Events\Listeners\LoginDecorator::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\LoginDecorator::class)],
        ],
    ],
    Module::RESOURCE_PATH      => realpath(__DIR__ . '/resources'),
    Module::ASSETS_PATHS       => [
        'fonts'        => realpath(__DIR__ . '/resources/rawassets/vendor/propeller/fonts'),
        'admin-assets' => realpath(__DIR__ . '/resources/rawassets'),
    ],
];
