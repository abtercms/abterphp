<?php

namespace AbterPhp\Propeller;

use AbterPhp\Admin\Constant\Event as AdminEvent;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;

return [
    Module::IDENTIFIER   => 'AbterPhp\Propeller',
    Module::DEPENDENCIES => ['AbterPhp\Admin'],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Events\ListenersBootstrapper::class,
        Bootstrappers\Html\Component\ButtonFactoryBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::FORM_READY => [
            /** @see \AbterPhp\Propeller\Events\Listeners\FormDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\FormDecorator::class),
        ],
        Event::GRID_READY => [
            /** @see \AbterPhp\Propeller\Events\Listeners\GridDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\GridDecorator::class),
        ],
        Event::NAVIGATION_READY => [
            /** @see \AbterPhp\Propeller\Events\Listeners\NavigationRegistrar::register() */
            sprintf('%s@handle', Events\Listeners\NavigationRegistrar::class),
        ],
        AdminEvent::ADMIN_READY  => [
            /** @see \AbterPhp\Propeller\Events\Listeners\AdminDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\AdminDecorator::class),
        ],
        AdminEvent::LOGIN_READY  => [
            /** @see \AbterPhp\Propeller\Events\Listeners\LoginDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\LoginDecorator::class),
        ],
    ],
];
